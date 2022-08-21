<?php

namespace App\Repositories;

use Doctrine\Common\Collections\Collection;
use App\PostComment;

class PostCommentRepository
{
    protected $model;

    public function __construct(PostComment $model)
    {
        $this->model = $model;
    }

    public function create($data)
    {
        $model = new PostComment;
        $model->fill($data);
        return $model->save();
    }

    public function delete($id)
    {
        return $this->model->where('id', $id)->delete();
    }

    public function getItem($id)
    {
        return $this->model->where('id', $id)->first();
    }

    public function getItemsByPostId($post_id)
    {
        return $this->model->where('post_id', $post_id)->orderBy('created_at', 'desc')->get();
    }

    public function getItemsByUserId($post_id)
    {
        return $this->model->where('user_id', $user_id)->orderBy('created_at', 'desc')->get();
    }
}
