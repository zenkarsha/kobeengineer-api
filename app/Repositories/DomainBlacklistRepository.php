<?php

namespace App\Repositories;

use Doctrine\Common\Collections\Collection;
use App\DomainBlacklist;

class DomainBlacklistRepository
{
    protected $model;

    public function __construct(DomainBlacklist $model)
    {
        $this->model = $model;
    }

    public function create($data)
    {
        $model = new DomainBlacklist;
        $model->fill($data);
        return $model->save();
    }

    public function delete($id)
    {
        return $this->model->where('id', $id)->delete();
    }

    public function getAll()
    {
        return $this->model->orderby('id', 'desc')->get();
    }
}
