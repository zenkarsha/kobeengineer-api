<?php

namespace App\Presenters;

use Illuminate\Http\Request;
use App\Repositories\PostRepository;
use App\Repositories\PublisherQueueRepository;

class PublisherQueuePresenter
{
    protected $postRepository;
    protected $publisherQueueRepository;

    public function __construct(PostRepository $postRepository, PublisherQueueRepository $publisherQueueRepository)
    {
        $this->postRepository = $postRepository;
        $this->publisherQueueRepository = $publisherQueueRepository;
    }

    public function getPostContent($post_id)
    {
        $result = $this->postRepository->getItem($post_id);

        return $result->content;
    }

    public function getPostType($post_id)
    {
        $result = $this->postRepository->getItem($post_id);
        $type = (int) $result->type;
        return convertPostTypeToText($type);
    }
}
