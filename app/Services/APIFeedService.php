<?php

namespace App\Services;

use App\Repositories\PostRepository;
use App\Repositories\PostCodeRepository;
use App\Repositories\UserRepository;
use App\Repositories\PostPublishedRepository;
use App\Repositories\PostLikeRepository;
use App\Services\PostService;
use JWTAuth;

class APIFeedService extends Service
{
    protected $postRepository;
    protected $postCodeRepository;
    protected $userRepository;
    protected $postPublishedRepository;
    protected $postLikeRepository;
    protected $postService;

    public function __construct(PostRepository $postRepository, PostCodeRepository $postCodeRepository, UserRepository $userRepository, PostPublishedRepository $postPublishedRepository, PostLikeRepository $postLikeRepository, PostService $postService)
    {
        $this->postRepository = $postRepository;
        $this->postCodeRepository = $postCodeRepository;
        $this->userRepository = $userRepository;
        $this->postPublishedRepository = $postPublishedRepository;
        $this->postLikeRepository = $postLikeRepository;
        $this->postService = $postService;
    }

    public function getPosts($sort, $current_page)
    {
        switch ($sort) {
            case 'ranking_today':
                $time = time() - 60 * 60 * 24;
                $datetime = timestampToDatetime($time);
                $result = $this->postRepository->getFeedRankingList($current_page, $datetime);
                break;
            case 'ranking_week':
                $time = time() - 60 * 60 * 24 * 7;
                $datetime = timestampToDatetime($time);
                $result = $this->postRepository->getFeedRankingList($current_page, $datetime);
                break;
            case 'ranking_month':
                $time = time() - 60 * 60 * 24 * 30;
                $datetime = timestampToDatetime($time);
                $result = $this->postRepository->getFeedRankingList($current_page, $datetime);
                break;
            case 'ranking_top100':
                $result = $this->postRepository->getFeedTop100List($current_page);
                break;
            default:
                $result = $this->postRepository->getFeedList($current_page);
                break;
        }

        $current_page = $result->currentPage();
        $total_page = $result->lastPage();
        $total = $result->total();

        $posts = [];
        if ($total > 0) {
            $posts = $result->toArray();
            $posts = $posts['data'];

            for ($i = 0; $i < count($posts); $i++)
            {
                $posts[$i] = $this->handlePostData($posts[$i]);
            }
        }

        $data = [
            'current_page' => $current_page,
            'total_page' => $total_page,
            'posts' => $posts,
        ];

        return $this->successResponse('Ok.', $data);
    }

    public function getAuthorPosts($author, $current_page)
    {
        $user = $this->userRepository->getItemByName($author);
        if (!count($user)) return $this->badRequestResponse();

        $result = $this->postRepository->getUserPosts($current_page, $user->id);

        $current_page = $result->currentPage();
        $total_page = $result->lastPage();
        $total = $result->total();

        $posts = [];
        if ($total > 0) {
            $posts = $result->toArray();
            $posts = $posts['data'];

            for ($i = 0; $i < count($posts); $i++)
            {
                $posts[$i] = $this->handlePostData($posts[$i]);
            }
        }

        $data = [
            'current_page' => $current_page,
            'total_page' => $total_page,
            'posts' => $posts,
        ];

        return $this->successResponse('Ok.', $data);
    }

    public function getRecentlyPosts()
    {
        $posts = $this->postRepository->getRecentlyPublishedPosts();

        if (count($posts)) {
            $posts = $posts->toArray();
            for ($i = 0; $i < count($posts); $i++)
            {
                $posts[$i] = $this->handlePostData($posts[$i], true);
            }
        }

        $data = [
            'total' => count($posts),
            'posts' => $posts,
        ];

        return $this->successResponse('Ok.', $data);
    }

    public function getAuthors($current_page)
    {
        $result = $this->userRepository->getPublicUsers($current_page);

        $current_page = $result->currentPage();
        $total_page = $result->lastPage();
        $total = $result->total();

        $authors = $result->toArray();
        $authors = $authors['data'];
        $author_bucket = [];
        for ($i = 0; $i < count($authors); $i++) {
            $post = $this->getUserLastPost($authors[$i]['id']);
            if (count($post) > 0) {
                $authors[$i]['post'] = $post;
                unset($authors[$i]['id']);
                array_push($author_bucket, $authors[$i]);
            }
        }

        $data = [
            'current_page' => $current_page,
            'total_page' => $total_page,
            'authors' => $author_bucket,
        ];

        return $this->successResponse('Ok.', $data);
    }

    private function handlePostData($post, $is_summary = false, $no_links = false)
    {
        $post['type'] = convertPostTypeToName($post['type']);
        if ($is_summary)
            if (strlen($post['content']) > 100) $post['content'] = mb_substr($post['content'], 0, 100, 'UTF-8') . '...';
        $post['content'] = nl2br(htmlentities($post['content']));
        // $post['published_at'] = $post['updated_at'];
        // unset($post['updated_at']);

        if ($post['type'] == 'image') {
            $post['image'] = $this->postService->getPostImageUrl($post['id'], true);
            unset($post['content']);
        }

        if ($post['type'] == 'code')
            $post['code'] = htmlentities($this->postCodeRepository->getValueByPostId($post['id']));

        if ($post['type'] != 'link')
            unset($post['link']);

        if ($post['reply_to'] == '')
            unset($post['reply_to']);

        if (!$no_links) {
            $post['links'] = [];
            $links = $this->postPublishedRepository->getItemsByPostId($post['id']);
            if (count($links)) {
                foreach ($links as $link) {
                    $post['links'][$link->type] = $link->url;
                }
            }
        }

        $post['liked'] = false;
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if (isset($user->id)) {
                $liked_check = $this->postLikeRepository->getItemByUserIdAndPostId($post['id'], $user->id);
                if (count($liked_check)) $post['liked'] = true;
            }
        } catch (\Exception $e) {}

        $post['id'] = $post['true_id'];
        unset($post['true_id']);

        return $post;
    }

    private function getUserLastPost($user_id)
    {
        $post = $this->postRepository->getUserLastPost($user_id);
        if (count($post))
            return $this->handlePostData($post, true, true);
        else
            return [];
    }
}
