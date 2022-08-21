<?php

namespace App\Repositories;

use Doctrine\Common\Collections\Collection;
use App\PostMedia;

class PostMediaRepository
{
    protected $model;

    public function __construct(PostMedia $model)
    {
        $this->model = $model;
    }

    public function create($data)
    {
        $model = new PostMedia;
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

    public function deleteByPostIdAndType($post_id, $type)
    {
        return $this->model->where('post_id', $post_id)->where('type', $type)->delete();
    }

    public function getItem($id)
    {
        return $this->model->where('id', $id)->first();
    }

    public function getItemValueByPostIdAndType($post_id, $type)
    {
        $result = $this->model->where('post_id', $post_id)->where('type', $type)->first();

        return count($result) ? $result->url : '';
    }

    public function getItemByPostId($post_id, $type = '')
    {
        if ($type != '')
            return $this->model->where('post_id', $post_id)->where('type', $type)->first();
        else
            return $this->model->where('post_id', $post_id)->orderBy('id', 'desc')->first();
    }

    public function getItemsByPostId($post_id)
    {
        return $this->model->where('post_id', $post_id)->get();
    }
}
