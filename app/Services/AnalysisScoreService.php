<?php

namespace App\Services;

use App\Repositories\AnalysisRepository;
use App\Repositories\AnalysisJiebaRepository;
use App\Repositories\AnalysisNegRepository;
use App\Repositories\AnalysisNegJiebaRepository;
use App\Repositories\AnalysisPosRepository;
use App\Repositories\AnalysisPosJiebaRepository;
use App\Repositories\PostAnalysisLogRepository;
use App\Repositories\PostAnalysisJudgeLogRepository;
use App\Repositories\PostAnalysisJiebaCacheRepository;

class AnalysisScoreService extends Service
{
    protected $analysisRepository;
    protected $analysisJiebaRepository;
    protected $analysisNegRepository;
    protected $analysisNegJiebaRepository;
    protected $analysisPosRepository;
    protected $analysisPosJiebaRepository;
    protected $postAnalysisLogRepository;
    protected $postAnalysisJudgeLogRepository;
    protected $postAnalysisJiebaCacheRepository;

    public function __construct(AnalysisRepository $analysisRepository, AnalysisJiebaRepository $analysisJiebaRepository, AnalysisNegRepository $analysisNegRepository, AnalysisNegJiebaRepository $analysisNegJiebaRepository, AnalysisPosRepository $analysisPosRepository, AnalysisPosJiebaRepository $analysisPosJiebaRepository, PostAnalysisLogRepository $postAnalysisLogRepository, PostAnalysisJudgeLogRepository $postAnalysisJudgeLogRepository, PostAnalysisJiebaCacheRepository $postAnalysisJiebaCacheRepository)
    {
        $this->analysisRepository = $analysisRepository;
        $this->analysisJiebaRepository = $analysisJiebaRepository;
        $this->analysisNegRepository = $analysisNegRepository;
        $this->analysisNegJiebaRepository = $analysisNegJiebaRepository;
        $this->analysisPosRepository = $analysisPosRepository;
        $this->analysisPosJiebaRepository = $analysisPosJiebaRepository;
        $this->postAnalysisLogRepository = $postAnalysisLogRepository;
        $this->postAnalysisJudgeLogRepository = $postAnalysisJudgeLogRepository;
        $this->postAnalysisJiebaCacheRepository = $postAnalysisJiebaCacheRepository;

        $this->max_length = 6;
    }

    public function calBasicScore($post)
    {
        $array = [];
        for ($length = 1; $length <= $this->max_length; $length++)
        {
            $score = $this->calSingleScroe($post->content, $length);
            $array['length' . $length] = $score;

            $this->postAnalysisLogRepository->create([
                'post_id' => $post->id,
                'type' => 'length' . $length,
                'score' => $score,
            ]);
        }

        // return calAverageByArray($array);
        return $array;
    }

    public function calPosScore($post)
    {
        # TODO: cal tf-idf
    }

    public function calNagScore($post)
    {
        # TODO: cal tf-idf
    }

    public function calJiebaScore($words, $post_id)
    {
        $array_unique = array_unique($words);
        $total = count($words);

        $result = $this->analysisJiebaRepository->getItemsByArray($array_unique);
        $sum = 0;

        foreach ($words as $word) {
            $item_data = searchObjectByKeyValue($result, 'word', $word);
            if ($item_data != null)
                $sum += (float) $item_data->score;
        }

        $score = $sum / ($total + 1);

        $this->postAnalysisLogRepository->create([
            'post_id' => $post_id,
            'type' => 'jieba',
            'score' => $score,
        ]);

        return $score;
    }

    public function calJiebaPosScore($post)
    {
        # TODO: cal tf-idf
    }

    public function calJiebaNagScore($post)
    {
        # TODO: cal tf-idf
    }

    public function calFinalScore($basic_score, $jieba_score)
    {
        $sum = $jieba_score;
        foreach ($basic_score as $key => $value)
            $sum += $value;

        return $sum / (count($basic_score) + 1);
    }

    public function calSingleScroe($string, $length)
    {
        $array = sliceStringToArray($string, $length);
        $array_unique = array_unique($array);
        $total = count($array);

        $result = $this->analysisRepository->getItemsByArray($array_unique);
        $sum = 0;

        foreach ($array as $word) {
            $item_data = searchObjectByKeyValue($result, 'word', $word);
            if ($item_data != null)
                $sum += (float) $item_data->score;
        }

        return $sum / ($total + 1);
    }

    public function calWordScore($data)
    {
        $ws_base = $data['dwt'] == 0 ? $data['wt'] * .1 : $data['dwt'];
        $ps_base = $data['dpt'] == 0 ? $data['pt'] * .1 : $data['dpt'];
        $ws = log10($data['wt']/$ws_base);
        $ps = log10($data['pt']/$ps_base);

        return ($ws + $ps) * 0.5;
    }
}
