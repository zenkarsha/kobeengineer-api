<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;
use App\Repositories\PostRepository;
use App\Services\AnalysisService;

class Analysiser extends Job implements SelfHandling
{
    protected $postRepository;
    protected $analysisService;

    public function __construct(PostRepository $postRepository, AnalysisService $analysisService)
    {
        $this->postRepository = $postRepository;
        $this->analysisService = $analysisService;
    }

    public function boot($job)
    {
        $post = $this->postRepository->getItemForAnalysis();
        if (count($post)) {
            \Log::info('Analysis post #' . $post->id);

            $this->analysisService->analysisPost($post->id);
        }
    }

    public function oldDeny($job, $content)
    {
        $this->analysisService->updateAnalysisData($content, true);
        $jieba_array = $this->analysisService->getJiebaSlice($content);
        $this->analysisService->updateAnalysisDataJieba($jieba_array, true);
    }

    public function oldAllow($job, $content)
    {
        $this->analysisService->updateAnalysisData($content);
        $jieba_array = $this->analysisService->getJiebaSlice($content);
        $this->analysisService->updateAnalysisDataJieba($jieba_array);
    }
}
