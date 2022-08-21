<?php

namespace App\Repositories;

use Doctrine\Common\Collections\Collection;
use App\PostLike;

class PostLikeRepository
{
    protected $model;

    public function __construct(PostLike $model)
    {
        $this->model = $model;
    }

    public function create($data)
    {
        $model = new PostLike;
        $model->fill($data);
        return $model->save();
    }

    public function delete($id)
    {
        return $this->model->where('id', $id)->delete();
    }

    public function deleteByUserIdAndPostId($post_id, $user_id)
    {
        return $this->model->where('post_id', $post_id)->where('user_id', $user_id)->delete();
    }

    public function getItemByUserIdAndPostId($post_id, $user_id)
    {
        return $this->model->where('post_id', $post_id)->where('user_id', $user_id)->first();
    }

    public function getPostLikeCount($post_id)
    {
        return $this->model->where('post_id', $post_id)->count();
    }
}
