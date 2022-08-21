<?php

namespace App\Repositories;

use Doctrine\Common\Collections\Collection;
use App\PostPublished;

class PostPublishedRepository
{
    protected $model;

    public function __construct(PostPublished $model)
    {
        $this->model = $model;
    }

    public function create($data)
    {
        $model = new PostPublished;
        $model->fill($data);
        return $model->save();
    }

    public function update($id, $data)
    {
        return $this->model->where('id', $id)->update($data);
    }

    public function delete($id)
    {
        return $this->model->where('id', $id)->delete();
    }

    public function deleteByPostId($post_id)
    {
        return $this->model->where('post_id', $post_id)->delete();
    }

    public function deleteByPostIdAndType($post_id, $type)
    {
        return $this->model->where('post_id', $post_id)->where('type', $type)->delete();
    }

    public function getItem($id)
    {
        return $this->model->where('id', $id)->first();
    }

    public function getItemsByPostId($post_id)
    {
        return $this->model->where('post_id', $post_id)->where('url', '!=', '')->get();
    }

    public function getItemsByPostIdAndType($post_id, $type)
    {
        return $this->model->where('post_id', $post_id)->where('type', $type)->first();
    }

    public function countPublished($post_id)
    {
        return $this->model->where('post_id', $post_id)->where('success', 1)->count();
    }

    public function countFailed($post_id)
    {
        return $this->model->where('post_id', $post_id)->where('success', 0)->count();
    }
}
