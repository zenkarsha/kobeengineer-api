<?php

namespace App\Repositories;

use Doctrine\Common\Collections\Collection;
use App\Autobot;

class AutobotRepository
{
    protected $model;

    public function __construct(Autobot $model)
    {
        $this->model = $model;
    }

    public function create($data)
    {
        $model = new Autobot;
        $model->fill($data);
        return $model->save();
    }

    public function update($id, $data)
    {
        return $this->model->where('id', $id)->update($data);
    }

    public function updateBoot($id, $value)
    {
        return $this->model->where('id', $id)->update(['booting' => $value]);
    }

    public function delete($id)
    {
        return $this->model->where('id', $id)->delete();
    }

    public function getList($page)
    {
        return $this->model->orderby('id', 'desc')->paginate(50);
    }

    public function getItem($id)
    {
        return $this->model->where('id', $id)->first();
    }

    public function getItemByName($name)
    {
        return $this->model->where('name', $name)->first();
    }
}
