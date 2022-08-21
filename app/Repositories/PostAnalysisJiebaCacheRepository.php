<?php

namespace App\Repositories;

use Doctrine\Common\Collections\Collection;
use App\PostAnalysisJiebaCache;

class PostAnalysisJiebaCacheRepository
{
    protected $model;

    public function __construct(PostAnalysisJiebaCache $model)
    {
        $this->model = $model;
    }

    public function create($data)
    {
        $model = new PostAnalysisJiebaCache;
        $model->fill($data);
        return $model->save();
    }

    public function getItem($post_id)
    {
        return $this->model->where('post_id', $post_id)->first();
    }

}
