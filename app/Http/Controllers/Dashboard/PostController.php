<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\PostService;
use App\Services\PublisherQueueService;

class PostController extends DashboardController
{
    protected $postService;
    protected $publisherQueueService;

    public function __construct(PostService $postService, PublisherQueueService $publisherQueueService)
    {
        parent::__construct();

        $this->postService = $postService;
        $this->publisherQueueService = $publisherQueueService;
    }

    public function create(Requests\PostCreateRequest $request)
    {
        $response = $this->postService->create($request->all());
        if (isset($response['success']))
            return redirect(__('/post/overview'));
    }

    public function queueDelete($id)
    {
        $response = $this->publisherQueueService->delete($id);
        if (isset($response['success']))
            return redirect(__('/post/queue/overview'));
    }
}
