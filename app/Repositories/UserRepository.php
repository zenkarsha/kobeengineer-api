<?php

namespace App\Repositories;

use Doctrine\Common\Collections\Collection;
use App\User;

class UserRepository
{
    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function update($id, $data)
    {
        return $this->model->where('id', $id)->update($data);
    }

    public function getList($page)
    {
        return $this->model->orderby('id', 'desc')->paginate(50);
    }

    public function getSearchList($keyword, $page)
    {
        return $this->model
            ->where('name', 'LIKE', '%' . $keyword . '%')
            ->orWhere('name', 'LIKE', '%' . strtolower($keyword) . '%')
            ->paginate(50);
    }

    public function getItem($id)
    {
        return $this->model->where('id', $id)->first();
    }

    public function getItemByName($name)
    {
        return $this->model->where('name', $name)->first();
    }

    public function getPublicUsers($page)
    {
        return $this->model
            ->where('public', 1)
            ->select('id', 'name')
            ->paginate(50);
    }
}
