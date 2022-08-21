<?php

namespace App\Services;

use App\Repositories\PostRepository;
use App\Repositories\PostLikeRepository;
use App\Repositories\PostCommentRepository;
use App\Repositories\CommentLikeRepository;
use Auth;

class APIReactionService extends PostService
{
    protected $postRepository;
    protected $postLikeRepository;
    protected $postCommentRepository;
    protected $commentLikeRepository;

    public function __construct(PostRepository $postRepository, PostLikeRepository $postLikeRepository, PostCommentRepository $postCommentRepository, CommentLikeRepository $commentLikeRepository)
    {
        $this->postRepository = $postRepository;
        $this->postLikeRepository = $postLikeRepository;
        $this->postCommentRepository = $postCommentRepository;
        $this->commentLikeRepository = $commentLikeRepository;

        $this->user = Auth::user();
    }

    public function likePost($true_id)
    {
        $post = $this->postRepository->getItemByTrueId($true_id);
        if ($post->published == 0 || $post->deleted_at != '' || (int) $this->user->banned == 1)
        {
            return $this->badRequestResponse('Request failed.');
            exit;
        }

        $user_id = $this->user->id;
        $post_id = $post->id;
        $check = $this->postLikeRepository->getItemByUserIdAndPostId($post_id, $user_id);

        if (!count($check)) {
            $data = [
                'post_id' => $post_id,
                'user_id' => $user_id,
            ];

            if ($this->postLikeRepository->create($data))
                return $this->updatePostLikes($post_id, true);
            else
                return $this->badRequestResponse('Request failed.');
        }
        else {
            if ($this->postLikeRepository->deleteByUserIdAndPostId($post_id, $user_id))
                return $this->updatePostLikes($post_id, false);
            else
                return $this->badRequestResponse('Request failed.');
        }
    }

    private function updatePostLikes($post_id, $liked)
    {
        $likes = $this->postLikeRepository->getPostLikeCount($post_id);
        $this->postRepository->update($post_id, ['likes' => $likes]);

        return $this->successResponse('Ok.', [
            'liked' => $liked,
            'likes' => $likes,
        ]);
    }

    public function likeComment($id)
    {
        $comment = $this->postCommentRepository->getItem($id);
        if ((int) $this->user->banned == 1)
        {
            return $this->badRequestResponse('Request failed.');
            exit;
        }

        $user_id = $this->user->id;
        $comment_id = $comment->id;
        $check = $this->commentLikeRepository->getItemByUserIdAndCommentId($user_id, $comment_id);

        if (!count($check)) {
            $data = [
                'comment_id' => $comment_id,
                'user_id' => $user_id,
            ];

            if ($this->commentLikeRepository->create($data))
                return $this->updateCommentLikes($comment_id, true);
            else
                return $this->badRequestResponse('Request failed.');
        }
        else {
            if ($this->commentLikeRepository->deleteByUserIdAndCommentId($user_id, $comment_id))
                return $this->updateCommentLikes($comment_id, false);
            else
                return $this->badRequestResponse('Request failed.');
        }
    }

    private function updateCommentLikes($comment_id, $liked)
    {
        $likes = $this->commentLikeRepository->getCommentLikeCount($comment_id);
        $this->postCommentRepository->update($comment_id, ['likes' => $likes]);

        return $this->successResponse('Ok.', [
            'liked' => $liked,
            'likes' => $likes,
        ]);
    }

    public function commentPost($true_id, $content, $main_id = 0)
    {
        $post = $this->postRepository->getItemByTrueId($true_id);
        if ($post->published == 0 || $post->deleted_at != '' || (int) $this->user->banned == 1)
        {
            return $this->badRequestResponse('Request failed.');
            exit;
        }

        $user_id = $this->user->id;
        $post_id = $post->id;

        # TODO: 確認是否要新增關鍵字審核
        $data = [
            'post_id' => $post_id,
            'user_id' => $user_id,
            'main_id' => $main_id,
            'content' => $content,
        ];

        if ($this->postCommentRepository->create($data)) {
            return $this->successResponse('Ok.', ['comment' => $data]);
        }
        else {
            return $this->badRequestResponse('Request failed.');
        }
    }
}
