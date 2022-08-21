<?php

namespace App\Repositories;

use Doctrine\Common\Collections\Collection;
use App\CommentLike;

class CommentLikeRepository
{
    protected $model;

    public function __construct(CommentLike $model)
    {
        $this->model = $model;
    }

    public function create($data)
    {
        $model = new CommentLike;
        $model->fill($data);
        return $model->save();
    }

    public function delete($id)
    {
        return $this->model->where('id', $id)->delete();
    }

    public function deleteByUserIdAndCommentId($user_id, $comment_id)
    {
        return $this->model->where('user_id', $user_id)->where('comment_id', $comment_id)->delete();
    }

    public function getItemByUserIdAndCommentId($user_id, $comment_id)
    {
        return $this->model->where('user_id', $user_id)->where('comment_id', $comment_id)->first();
    }

    public function getCommentLikeCount($comment_id)
    {
        return $this->model->where('comment_id', $comment_id)->count();
    }
}
