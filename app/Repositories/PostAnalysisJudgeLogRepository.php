<?php

namespace App\Repositories;

use Doctrine\Common\Collections\Collection;
use App\PostAnalysisJudgeLog;

class PostAnalysisJudgeLogRepository
{
    protected $model;

    public function __construct(PostAnalysisJudgeLog $model)
    {
        $this->model = $model;
    }

    public function create($data)
    {
        $model = new PostAnalysisJudgeLog;
        $model->fill($data);
        return $model->save();
    }

    public function getItem($post_id)
    {
        return $this->model->where('post_id', $post_id)->first();
    }

}
