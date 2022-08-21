<?php

namespace App\Repositories;

use Doctrine\Common\Collections\Collection;
use App\PostAnalysisLog;

class PostAnalysisLogRepository
{
    protected $model;

    public function __construct(PostAnalysisLog $model)
    {
        $this->model = $model;
    }

    public function create($data)
    {
        $model = new PostAnalysisLog;
        $model->fill($data);
        return $model->save();
    }

    public function getItem($post_id)
    {
        return $this->model->where('post_id', $post_id)->first();
    }

}
