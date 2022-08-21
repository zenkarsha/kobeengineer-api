<?php

namespace App\Repositories;

use Doctrine\Common\Collections\Collection;
use App\PostReportLog;

class PostReportLogRepository
{
    protected $model;

    public function __construct(PostReportLog $model)
    {
        $this->model = $model;
    }

    public function create($data)
    {
        $model = new PostReportLog;
        $model->fill($data);
        return $model->save();
    }

    public function checkItemExist($post_id, $user_id)
    {
        return $this->model->where('post_id', $post_id)->where('user_id', $user_id)->first();
    }

    public function countReport($post_id)
    {
        return $this->model->where('post_id', $post_id)->count();
    }
}
