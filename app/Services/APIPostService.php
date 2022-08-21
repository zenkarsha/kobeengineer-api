<?php

namespace App\Services;

use App\Repositories\PostRepository;
use App\Repositories\PostCodeRepository;
use App\Repositories\PostPublishedRepository;
use App\Repositories\PublisherQueueRepository;
use App\Repositories\AutobotRepository;
use App\Repositories\PostLikeRepository;
use App\Repositories\PostCommentRepository;
use App\Services\PostService;
use App\Services\FacebookService;
use Carbon\Carbon;
use JWTAuth;

class APIPostService extends PostService
{
    protected $postRepository;
    protected $postCodeRepository;
    protected $postPublishedRepository;
    protected $publisherQueueRepository;
    protected $autobotRepository;
    protected $postLikeRepository;
    protected $postCommentRepository;
    protected $postService;
    protected $facebookService;

    public function __construct(PostRepository $postRepository, PostCodeRepository $postCodeRepository, PostPublishedRepository $postPublishedRepository, PublisherQueueRepository $publisherQueueRepository, AutobotRepository $autobotRepository, PostLikeRepository $postLikeRepository, PostCommentRepository $postCommentRepository, PostService $postService, FacebookService $facebookService)
    {
        $this->postRepository = $postRepository;
        $this->postCodeRepository = $postCodeRepository;
        $this->postPublishedRepository = $postPublishedRepository;
        $this->publisherQueueRepository = $publisherQueueRepository;
        $this->autobotRepository = $autobotRepository;
        $this->postLikeRepository = $postLikeRepository;
        $this->postCommentRepository = $postCommentRepository;

        $this->postService = $postService;
        $this->facebookService = $facebookService;
    }

    public function getPost($form)
    {
        if (isset($form['key']))
            $result = $this->postRepository->getItemByKey($form['key']);
        elseif (isset($form['id']))
            $result = $this->postRepository->getItemByTrueId($form['id']);

        if (count($result) && $result->unpublished != 1) {
            $post = $this->handlePostData($result);
            return $this->successResponse('Ok.', ['post' => $post]);
        }
        else
            return $this->badRequestResponse('Post not exist.');
    }

    public function getPostComments($form)
    {
        if (isset($form['pid'])) {
            $after = isset($form['after']) ? $form['after'] : '';
            return $this->facebookService->getPostComments($form['pid'], $after);
        }
        else {
            return $this->badRequestResponse();
        }
    }

    private function handlePostData($post)
    {
        $type = convertPostTypeToName($post->type);
        $created_at = Carbon::parse($post->created_at);
        $updated_at = Carbon::parse($post->updated_at);
        $data = [
            'key' => $post->key,
            'type' => $type,
            'state' => getPostState($post),
            'created_at' => $created_at->format('Y-m-d H:i:s'),
            'published_at' => $updated_at->format('Y-m-d H:i:s'),
        ];

        if ($data['state'] == 'queuing') $data['countdown'] = $this->calcPostCountdown($post->id);
        if ($post->hashtag != '') $data['hashtag'] = $post->hashtag;
        if ($post->reply_to != '') $data['reply_to'] = $post->reply_to;
        if ($type == 'image')
            $data['image'] = $this->postService->getPostImageUrl($post->id, true);
        else
            $data['content'] = htmlentities($post->content);
        if ($type == 'code')
            $data['code'] = htmlentities($this->postCodeRepository->getValueByPostId($post->id));

        if ($post->true_id != '' && $post->published > 0)
        {
            $data['id'] = $post->true_id;
            $data['fb_likes'] = $post->fb_likes;
            $data['fb_comments'] = $post->fb_comments;
            $data['fb_shares'] = $post->fb_shares;
            $data = $this->handlePostLinks($data, $post);
            $data = $this->handlePostLikes($data, $post);
        }

        return $data;
    }

    private function handlePostLinks($data, $post)
    {
        $data['links'] = [];
        $links = $this->postPublishedRepository->getItemsByPostId($post->id);
        foreach ($links as $link) {
            $data['links'][$link->type] = $link->url;
            if ($link->type == 'facebook') $data['facebook_pid'] = $link->pid;
            if ($link->type == 'twitter') $data['twitter_pid'] = $link->pid;
        }

        return $data;
    }

    private function handlePostLikes($data, $post)
    {
        $data['likes'] = $post->likes;
        $data['liked'] = false;

        try {
            $user = JWTAuth::parseToken()->authenticate();
            if (isset($user->id)) {
                $liked_check = $this->postLikeRepository->getItemByUserIdAndPostId($post->id, $user->id);
                if (count($liked_check)) $data['liked'] = true;
            }
        } catch (\Exception $e) {}

        return $data;
    }

    private function calcPostCountdown($post_id)
    {
        try {
            $result = $this->publisherQueueRepository->getItemByPostId($post_id);
            $count = $this->publisherQueueRepository->countLessThanId($result->id);
            $autobot = $this->autobotRepository->getItemByName('publisher');
            $frequency = (int) $autobot->frequency;
            $time = time() + $count * $frequency + ($frequency - (time() % $frequency)) + 120 + 30;
        } catch (\Exception $e) {
            $time = time() + 120;
        }

        return $time;
    }
}
