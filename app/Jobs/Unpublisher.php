<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;
use App\Repositories\PostRepository;
use App\Repositories\PostPublishedRepository;
use App\Services\GithubService;
use App\Services\TwitterService;
use App\Services\FacebookService;

class Unpublisher extends Job implements SelfHandling
{
    protected $postRepository;
    protected $postPublishedRepository;
    protected $githubService;
    protected $twitterService;
    protected $facebookService;

    public function __construct(PostRepository $postRepository, PostPublishedRepository $postPublishedRepository, GithubService $githubService, TwitterService $twitterService, FacebookService $facebookService)
    {
        $this->postRepository = $postRepository;
        $this->postPublishedRepository = $postPublishedRepository;
        $this->githubService = $githubService;
        $this->twitterService = $twitterService;
        $this->facebookService = $facebookService;
    }

    public function boot($job, $post_id)
    {
        \Log::info('Unpublish post: #' . $post_id . ' at ' . currentTime());

        $result = $this->postPublishedRepository->getItemsByPostId($post_id);
        foreach ($result as $item)
        {
            if ($item->type == 'github')
                $this->githubService->unpublish($post_id);
            // if ($item->type == 'github_issue')
            //     $this->githubService->unpublishIssue($post_id);
            if ($item->type == 'twitter')
                $this->twitterService->unpublish($post_id);
            if ($item->type == 'facebook')
                $this->facebookService->unpublish($post_id);
        }

        $this->postRepository->update($post_id, [
            'published' => 0,
            'unpublished' => 1,
        ]);
    }
}
