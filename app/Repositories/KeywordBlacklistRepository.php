<?php

namespace App\Repositories;

use Doctrine\Common\Collections\Collection;
use App\KeywordBlacklist;

class KeywordBlacklistRepository
{
    protected $model;

    public function __construct(KeywordBlacklist $model)
    {
        $this->model = $model;
    }

    public function create($data)
    {
        $model = new KeywordBlacklist;
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

    public function updateColumn($id, $column, $value)
    {
        return $this->model->where('id', $id)->update([$column => $value]);
    }
}
