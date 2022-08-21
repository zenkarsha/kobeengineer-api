<?php

namespace App\Repositories;

use Doctrine\Common\Collections\Collection;
use App\PostCode;

class PostCodeRepository
{
    protected $model;

    public function __construct(PostCode $model)
    {
        $this->model = $model;
    }

    public function create($data)
    {
        $model = new PostCode;
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

    public function getValueByPostId($post_id)
    {
        $result = $this->model->where('post_id', $post_id)->first();

        return $result->code;
    }
}
