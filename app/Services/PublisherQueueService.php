<?php

namespace App\Services;

use App\Repositories\PostRepository;
use App\Repositories\PublisherQueueRepository;

class PublisherQueueService extends Service
{
    protected $postRepository;
    protected $publisherQueueRepository;

    public function __construct(PostRepository $postRepository, PublisherQueueRepository $publisherQueueRepository)
    {
        $this->postRepository = $postRepository;
        $this->publisherQueueRepository = $publisherQueueRepository;
    }

    public function delete($id)
    {
        if ($this->publisherQueueRepository->delete($id)) {
            if ($this->postRepository->update(['denied' => 1, 'queuing' => 0])) {
                # TODO: rollback post analysis data
                return $this->successResponse();
            }
        }
        else
            return $this->badRequestResponse('Delete failed.');
    }

    public function published($id)
    {
        if ($this->publisherQueueRepository->delete($id))
            if ($this->postRepository->update(['published' => 1, 'queuing' => 0]))
                return $this->successResponse();
        else
            return $this->badRequestResponse('Delete failed.');
    }
}
