<?php

namespace App\Services;

use App\Repositories\AnalysisRepository;
use App\Repositories\AnalysisJiebaRepository;
use App\Repositories\AnalysisNegRepository;
use App\Repositories\AnalysisNegJiebaRepository;
use App\Repositories\AnalysisPosRepository;
use App\Repositories\AnalysisPosJiebaRepository;
use App\Repositories\PostRepository;
use App\Repositories\PostAnalysisJudgeLogRepository;
use App\Repositories\PostAnalysisJiebaCacheRepository;
use App\Services\PostService;
use App\Services\PostActionService;
use App\Services\AnalysisScoreService;
use Jieba;
use Finalseg;

ini_set('memory_limit', '2048M');

class AnalysisService extends Service
{
    protected $analysisRepository;
    protected $analysisJiebaRepository;
    protected $analysisNegRepository;
    protected $analysisNegJiebaRepository;
    protected $analysisPosRepository;
    protected $analysisPosJiebaRepository;
    protected $postAnalysisJudgeLogRepository;
    protected $postAnalysisJiebaCacheRepository;
    protected $postRepository;
    protected $postActionRepository;
    protected $postService;
    protected $analysisScoreService;

    public function __construct(AnalysisRepository $analysisRepository, AnalysisJiebaRepository $analysisJiebaRepository, AnalysisNegRepository $analysisNegRepository, AnalysisNegJiebaRepository $analysisNegJiebaRepository, AnalysisPosRepository $analysisPosRepository, AnalysisPosJiebaRepository $analysisPosJiebaRepository, PostAnalysisJudgeLogRepository $postAnalysisJudgeLogRepository, PostAnalysisJiebaCacheRepository $postAnalysisJiebaCacheRepository, PostRepository $postRepository, PostService $postService, PostActionService $postActionService, AnalysisScoreService $analysisScoreService)
    {
        $this->analysisRepository = $analysisRepository;
        $this->analysisJiebaRepository = $analysisJiebaRepository;
        $this->analysisNegRepository = $analysisNegRepository;
        $this->analysisNegJiebaRepository = $analysisNegJiebaRepository;
        $this->analysisPosRepository = $analysisPosRepository;
        $this->analysisPosJiebaRepository = $analysisPosJiebaRepository;
        $this->postAnalysisJudgeLogRepository = $postAnalysisJudgeLogRepository;
        $this->postAnalysisJiebaCacheRepository = $postAnalysisJiebaCacheRepository;
        $this->postRepository = $postRepository;
        $this->postService = $postService;
        $this->postActionService = $postActionService;
        $this->analysisScoreService = $analysisScoreService;

        $this->max_length = 6;
        $this->allow_score = 0.5;
        $this->deny_score = 0.2;
    }

    public function analysisPost($post_id)
    {
        $post = $this->postRepository->getItem($post_id);

        # TODO: 把slice資料抽共用，設為$this
        $basic_score = $this->analysisScoreService->calBasicScore($post);
        // $pos_score = $this->analysisScoreService->calPosScore($post);
        // $neg_score = $this->analysisScoreService->calNagScore($post);

        $jieba_words = $this->getJiebaSlice($post->content);
        $this->postAnalysisJiebaCacheRepository->create([
            'post_id' => $post->id,
            'cache' => json_encode($jieba_words),
        ]);
        $jieba_score = $this->analysisScoreService->calJiebaScore($jieba_words, $post->id);
        // $jieba_pos_score = $this->analysisScoreService->calJiebaPosScore($jieba_words, $post->id);
        // $jieba_neg_score = $this->analysisScoreService->calJiebaNagScore($jieba_words, $post->id);

        $final_score = $this->analysisScoreService->calFinalScore($basic_score, $jieba_score);
        # TODO: 分析數量足夠後開啟
        // $judge = $this->getScoreJudge($final_score);
        $judge = 'allow';

        \Log::info('Analysis post #' . $post_id . ' finished, final score: ' . $final_score . ', judge: ' . $judge);

        # TODO: this is temp for test
        if ($post->denied == 1) $judge = 'denied';

        $this->postAnalysisJudgeLogRepository->create([
            'post_id' => $post->id,
            'score' => $final_score,
            'judge' => $judge,
        ]);
        $this->postActionService->analysed($post->id);

        if ((int) $post->pending != 1) {
            if ($judge == 'denied') {
                $this->postActionService->denied($post->id);
                $this->updateAnalysisData($post->content, true);
                $this->updateAnalysisDataJieba($jieba_words, true);
            }
            elseif ($judge == 'allow') {
                $this->postActionService->allow($post->id);
                $this->updateAnalysisData($post->content);
                $this->updateAnalysisDataJieba($jieba_words);
            }
            elseif ($judge == 'pending')
                $this->postActionService->pending($post->id);
        }
    }

    public function getJiebaSlice($string)
    {
        Jieba::init(['mode'=>'default', 'dict'=>'big']);
        Finalseg::init();

        return Jieba::cut($string);
    }

    private function getScoreJudge($score)
    {
        if ($score <= $this->deny_score)
            return 'deny';
        elseif ($score >= $this->allow_score)
            return 'allow';
        else
            return 'pending';
    }

    public function updateAnalysisData($string, $deny = false, $pos_nag = false)
    {
        for ($length = 1; $length <= $this->max_length; $length++)
        {
            $array = sliceStringToArray($string, $length);
            $array_unique = array_unique($array);
            $array_count = array_count_values($array);

            foreach ($array_unique as $word)
            {
                $data = $this->handleAnalysisDate($word, $array_count, $deny);
                $this->handleAnalysisDataInsert($this->analysisRepository, $word, $data);
                if ($pos_nag == 'pos')
                    $this->handlePosNegAnalysisDataInsert($this->analysisPosRepository, $word, $data);
                if ($pos_nag == 'neg')
                    $this->handlePosNegAnalysisDataInsert($this->analysisNegRepository, $word, $data);
            }
        }
    }

    public function updateAnalysisDataJieba($array, $deny = false, $pos_nag = false)
    {
        $array_unique = array_unique($array);
        $array_count = array_count_values($array);

        foreach ($array_unique as $word)
        {
            $data = $this->handleAnalysisDate($word, $array_count, $deny);
            $this->handleAnalysisDataInsert($this->analysisJiebaRepository, $word, $data, true);
            if ($pos_nag == 'pos')
                $this->handlePosNegAnalysisDataInsert($this->analysisPosJiebaRepository, $word, $data, true);
            if ($pos_nag == 'neg')
                $this->handlePosNegAnalysisDataInsert($this->analysisNegJiebaRepository, $word, $data, true);
        }
    }

    private function handleAnalysisDate($word, $array_count, $deny)
    {
        $data = [
            'wt' => $array_count[$word],
            'dwt' => 0,
            'pt' => 1,
            'dpt' => 0,
        ];

        if ($deny) {
            $data['dwt'] = $data['wt'];
            $data['dpt'] = 1;
        }

        return $data;
    }

    private function handleAnalysisDataInsert($model, $word, $analysis_data, $jieba = false)
    {
        $result = $model->getItem($word);
        $data = [
            'wt' => count($result) ? (int) $result->wt + $analysis_data['wt'] : $analysis_data['wt'],
            'dwt' => count($result) ? (int) $result->dwt + $analysis_data['dwt'] : $analysis_data['dwt'],
            'pt' => count($result) ? (int) $result->pt + $analysis_data['pt'] : $analysis_data['pt'],
            'dpt' => count($result) ? (int) $result->dpt + $analysis_data['dpt'] : $analysis_data['dpt'],
        ];
        $data['score'] = $this->analysisScoreService->calWordScore($data);

        if (count($result))
            $model->update($word, $data);
        else {
            $data['word'] = $word;
            if (!$jieba) $data['length'] = mb_strlen($word, 'UTF-8');

            $model->create($data);
        }
    }

    private function handlePosNegAnalysisDataInsert($model, $word, $analysis_data, $jieba = false)
    {
        $result = $model->getItem($word);
        $data = [
            'wt' => count($result) ? (int) $result->wt + $analysis_data['wt'] : $analysis_data['wt'],
            'pt' => count($result) ? (int) $result->pt + $analysis_data['pt'] : $analysis_data['pt'],
        ];
        if (count($result))
            $model->update($word, $data);
        else {
            $data['word'] = $word;
            if (!$jieba) $data['length'] = mb_strlen($word, 'UTF-8');

            $model->create($data);
        }
    }
}
