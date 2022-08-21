<?php

namespace App\Presenters;

use Illuminate\Http\Request;
use App\Repositories\PostRepository;
use App\Repositories\PostMediaRepository;
use App\Repositories\PostCodeRepository;
use App\Repositories\PostPublishedRepository;
use App\Repositories\UserRepository;
use Lang;

class PostPresenter
{
    protected $postRepository;
    protected $postMediaRepository;
    protected $postCodeRepository;
    protected $postPublishedRepository;
    protected $userRepository;

    public function __construct(Request $request, PostRepository $postRepository, PostMediaRepository $postMediaRepository, PostCodeRepository $postCodeRepository, PostPublishedRepository $postPublishedRepository, UserRepository $userRepository)
    {
        $this->request = $request;

        $this->postRepository = $postRepository;
        $this->postMediaRepository = $postMediaRepository;
        $this->postCodeRepository = $postCodeRepository;
        $this->postPublishedRepository = $postPublishedRepository;
        $this->userRepository = $userRepository;
    }

    public function getStateText($post)
    {
        if ((int) $post->denied == 1)
            return Lang::get('post.label-state-denied');
        elseif ((int) $post->pending == 1)
            return Lang::get('post.label-state-pending');
        elseif ((int) $post->queuing == 1)
            return Lang::get('post.label-state-queuing');
        elseif ((int) $post->published > 0)
            return Lang::get('post.label-state-published');
        elseif ((int) $post->unpublished == 1)
            return Lang::get('post.label-state-unpublished');
        elseif ($this->countPublishFailed($post->id) > 0)
            return Lang::get('post.label-state-failed');
        elseif ((int) $post->analysed == 0)
            return Lang::get('post.label-state-analysising');
        else
            return Lang::get('post.label-state-error');
    }

    public function getPostStateColor($post)
    {
        if ((int) $post->denied == 1)
            return 'tertiary inverted red post-denied';
        elseif ((int) $post->pending == 1)
            return 'warning post-pending';
        elseif ((int) $post->queuing == 1)
            return 'brown post-queuing';
        elseif ((int) $post->published > 0)
            return 'teal post-published';
        elseif ((int) $post->unpublished == 1)
            return 'secondary post-unpublished';
        elseif ($this->countPublishFailed($post->id) > 0)
            return 'yellow inverted post-failed';
        elseif ((int) $post->analysed == 0)
            return '';
        else
            return '';
    }

    public function getStateLabelColor($post)
    {
        if ((int) $post->denied == 1)
            return 'red';
        elseif ((int) $post->pending == 1)
            return 'gray';
        elseif ((int) $post->queuing == 1)
            return 'brown';
        elseif ((int) $post->published > 0)
            return 'teal';
        elseif ((int) $post->unpublished == 1)
            return 'gray';
        elseif ($this->countPublishFailed($post->id) > 0)
            return 'yellow';
        elseif ((int) $post->analysed == 0)
            return '';
        else
            return 'red';
    }

    public function getPostTypeText($type)
    {
        return convertPostTypeToText($type);
    }

    public function getPostCode($post_id)
    {
        $result = $this->postCodeRepository->getItemByPostId($post_id);

        return $result->code;
    }

    public function getPostMedia($post_id)
    {
        $result = $this->postMediaRepository->getItemByPostId($post_id);
        if (count(array($result)) > 0 && $result != null)
            return $result->url;
        else {
            return false;
        }
    }

    public function getUserName($user_id)
    {
        if ($user_id == 0)
            return 'Administrator';
        else {
            $user = $this->userRepository->getItem($user_id);
            return $user->name;
        }
    }

    public function getUserStateColor($user_id)
    {
        if ($user_id == 0)
            return '';
        else {
            $user = $this->userRepository->getItem($user_id);

            if ((int) $user->flagged == 1) {
                return ' red';
            }
            if ((int) $user->banned == 1) {
                return ' black';
            }
            else {
                return '';
            }
        }
    }

    public function countPublishFailed($post_id)
    {
        return $this->postPublishedRepository->countFailed($post_id);
    }

    public function getPublishedLinks($post_id)
    {
        return $this->postPublishedRepository->getItemsByPostId($post_id);
    }

    public function getPostMediaLinks($post_id)
    {
        return $this->postMediaRepository->getItemsByPostId($post_id);
    }
}
