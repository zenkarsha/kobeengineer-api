<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;
use App\Repositories\SettingRepository;
use App\Repositories\PostRepository;
use App\Repositories\PublisherQueueRepository;
use App\Services\GithubService;
use App\Services\TwitterService;
use App\Services\FacebookService;

class Publisher2 extends Job implements SelfHandling
{
    protected $settingRepository;
    protected $postRepository;
    protected $publisherQueueRepository;
    protected $githubService;
    protected $twitterService;
    protected $facebookService;

    public function __construct(SettingRepository $settingRepository, PostRepository $postRepository, PublisherQueueRepository $publisherQueueRepository, GithubService $githubService, TwitterService $twitterService, FacebookService $facebookService)
    {
        $this->settingRepository = $settingRepository;
        $this->postRepository = $postRepository;
        $this->publisherQueueRepository = $publisherQueueRepository;
        $this->githubService = $githubService;
        $this->twitterService = $twitterService;
        $this->facebookService = $facebookService;

        $this->rand_time = 120;
    }

    public function boot($job, $delay = true)
    {
        $check = $this->publisherQueueRepository->getFirstItem(0);
        if (count($check))
        {
            # generate and update post true id
            $id = $check->post_id;
            $true_id_base10 = $this->getPostTrueId();
            $true_id = base_convert($true_id_base10, 10, 16);
            $this->postRepository->update($id,[
                'true_id' => $true_id,
                'true_id_base10' => $true_id_base10,
            ]);
            $this->publisherQueueRepository->delete($id);

            $rand = rand(0, $this->rand_time);
            \Log::info('Publish post: #' . $check->post_id . ' sleep ' . $rand . 's');
            sleep($rand);
            \Log::info('Publish post: #' . $check->post_id . ', true_id: ' . $true_id . ' ,at ' . currentTime());

            # check if item still exsit after sleep
            $check_again = $this->postRepository->getItem($id);
            if ($check_again->deleted_at == '' && $check_again->queuing == 1)
            {
                # do publish
                $this->postRepository->update($id,[
                    'denied' => 0,
                    'queuing' => 0,
                    'published' => 0,
                    'unpublished' => 0,
                    'published_at' => currentTime(),
                ]);
                $this->handlePublish($id, $true_id);
            }
        }
    }

    private function handlePublish($id, $true_id)
    {
        $this->githubService->publish($id, $true_id);
        $this->twitterService->publish($id, $true_id);
        $this->facebookService->publish($id, $true_id);
    }

    private function getPostTrueId()
    {
        $current_published_count = $this->settingRepository->getValue('publisher_published_count');
        $true_id = (int) $current_published_count + 1;
        $this->settingRepository->updateByKey('publisher_published_count', $true_id);

        return $true_id;
    }
}
