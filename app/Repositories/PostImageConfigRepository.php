<?php

namespace App\Repositories;

use Doctrine\Common\Collections\Collection;
use App\PostImageConfig;

class PostImageConfigRepository
{
    protected $model;

    public function __construct(PostImageConfig $model)
    {
        $this->model = $model;
    }

    public function create($data)
    {
        $model = new PostImageConfig;
        $model->fill($data);
        return $model->save();
    }

    public function update($id, $data)
    {
        return $this->model->where('id', $id)->update($data);
    }

    public function getItem($id)
    {
        return $this->model->where('id', $id)->first();
    }

    public function getItemByPostId($post_id)
    {
        return $this->model->where('post_id', $post_id)->first();
    }
}
