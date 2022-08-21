<?php

namespace App\Services;

use App\Repositories\PostRepository;
use App\Repositories\PublisherQueueRepository;
use App\Repositories\PostPublishedRepository;
use App\Services\PostDomService;
use Queue;

class PostActionService extends Service
{
    protected $postRepository;
    protected $publisherQueueRepository;
    protected $postPublishedRepository;
    protected $postDomService;

    public function __construct(PostRepository $postRepository, PublisherQueueRepository $publisherQueueRepository, PostPublishedRepository $postPublishedRepository, PostDomService $postDomService)
    {
        $this->postRepository = $postRepository;
        $this->publisherQueueRepository = $publisherQueueRepository;
        $this->postPublishedRepository = $postPublishedRepository;
        $this->postDomService = $postDomService;
    }

    public function allow($id)
    {
        $this->postRepository->update($id, [
            'denied' => 0,
            'pending' => 0,
            'queuing' => 1
        ]);
        $this->publisherQueueRepository->create([
            'post_id' => $id,
        ]);

        return $this->successResponse('Ok', ['dom' => $this->postDomService->reloadPostDom($id)]);
    }

    public function denied($id)
    {
        $this->postRepository->update($id, [
            'pending' => 0,
            'denied' => 1
        ]);

        return $this->successResponse('Ok', ['dom' => $this->postDomService->reloadPostDom($id)]);
    }

    public function delete($id)
    {
        $result = $this->postRepository->getItem($id);

        if ($result->published > 0) {
            Queue::push('App\Jobs\Unpublisher@boot', $id);
        }

        if ($result->queuing == 1) {
            $this->publisherQueueRepository->delete($id);
        }

        # TODO: delete post media here

        if ($this->postRepository->delete($id))
            return $this->successResponse('Ok.');
        else
            return $this->badRequestResponse('Post delete failed.');
    }

    public function pending($id)
    {
        $this->postRepository->update($id, [
            'pending' => 1,
        ]);

        return $this->successResponse();
    }

    public function analysed($id)
    {
        $this->postRepository->update($id, [
            'analysed' => 1,
        ]);

        return $this->successResponse();
    }

    public function cancelQueuing($id)
    {
        $this->postRepository->update($id, [
            'queuing' => 0,
            'denied' => 1
        ]);
        $this->publisherQueueRepository->delete($id);

        return $this->successResponse('Ok', ['dom' => $this->postDomService->reloadPostDom($id)]);
    }

    public function setPriority($id)
    {
        $this->postRepository->update($id, [
            'priority' => 1,
        ]);

        return $this->successResponse('Ok', ['dom' => $this->postDomService->reloadPostDom($id)]);
    }

    public function unpublish($id)
    {
        $this->postRepository->update($id, [
            'published' => 0,
            'unpublished' => 1,
        ]);

        Queue::push('App\Jobs\Unpublisher@boot', $id);

        return $this->successResponse('Ok', ['dom' => $this->postDomService->reloadPostDom($id)]);
    }

    public function republish($id)
    {
        $this->postRepository->update($id, [
            'queuing' => 1,
        ]);
        $this->publisherQueueRepository->create([
            'post_id' => $id,
        ]);
        $this->postPublishedRepository->deleteByPostId($id);

        return $this->successResponse('Ok', ['dom' => $this->postDomService->reloadPostDom($id)]);
    }
}
