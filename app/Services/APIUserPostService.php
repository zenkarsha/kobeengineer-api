<?php

namespace App\Services;

use App\Repositories\PostRepository;
use App\Repositories\PostPublishedRepository;
use App\Repositories\PublisherQueueRepository;
use Auth;
use Queue;

class APIUserPostService extends Service
{
    protected $postRepository;
    protected $postPublishedRepository;
    protected $publisherQueueRepository;

    public function __construct(PostRepository $postRepository, PostPublishedRepository $postPublishedRepository, PublisherQueueRepository $publisherQueueRepository)
    {
        $this->postRepository = $postRepository;
        $this->postPublishedRepository = $postPublishedRepository;
        $this->publisherQueueRepository = $publisherQueueRepository;

        $this->user = Auth::user();
    }

    public function getUserPosts($current_page)
    {
        $result = $this->postRepository->getListByUserId($this->user->id, $current_page);
        $current_page = $result->currentPage();
        $total_page = $result->lastPage();
        $total = $result->total();

        $posts = [];
        if ($total > 0) {
            $posts = $result->toArray();
            $posts = $posts['data'];
            for ($i = 0; $i < count($posts); $i++) {
                $posts[$i]['type'] = convertPostTypeToName($posts[$i]['type']);
                $posts[$i]['content'] = textToSummary($posts[$i]['content']);
                if ($posts[$i]['content'] == '') $posts[$i]['content'] = '[empty]';
                $posts[$i]['content'] = htmlentities($posts[$i]['content']);
                $posts[$i]['state'] = getPostState($posts[$i]);
                $posts[$i]['id'] = $posts[$i]['true_id'] == '' ? '' :$posts[$i]['true_id'];
                if ($posts[$i]['true_id'] != '')
                    unset($posts[$i]['true_id']);

                unset($posts[$i]['pending']);
                unset($posts[$i]['queuing']);
                unset($posts[$i]['denied']);
                unset($posts[$i]['analysed']);
                unset($posts[$i]['published']);
                unset($posts[$i]['unpublished']);
            }
        }

        $data = [
            'current_page' => $current_page,
            'total_page' => $total_page,
            'total' => $total,
            'posts' => $posts,
        ];

        return $this->successResponse('Ok.', $data);
    }

    public function deleteUserPost($key)
    {
        $result = $this->postRepository->getItemByKey($key);
        if ($result->user_id != $this->user->id) {
            \Log::warning($this->user->name . ' try to delete others post.');
            return $this->badRequestResponse('Request deined.');
            exit;
        }

        if ($result->published > 0) Queue::push('App\Jobs\Unpublisher@boot', $result->id);
        if ($result->queuing == 1) $this->publisherQueueRepository->delete($result->id);

        # TODO: delete post media here

        if ($this->postRepository->deleteByKey($key))
            return $this->successResponse('Ok.');
        else
            return $this->badRequestResponse('Post delete failed.');
    }
}
