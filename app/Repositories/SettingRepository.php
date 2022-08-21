<?php

namespace App\Repositories;

use Doctrine\Common\Collections\Collection;
use App\Setting;

class SettingRepository
{
    protected $model;

    public function __construct(Setting $model)
    {
        $this->model = $model;
    }

    public function create($data)
    {
        $model = new Setting;
        $model->fill($data);
        return $model->save();
    }

    public function update($id, $data)
    {
        return $this->model->where('id', $id)->update($data);
    }

    public function updateByKey($key, $value)
    {
        return $this->model->where('key', $key)->update(['value' => $value]);
    }

    public function delete($id)
    {
        return $this->model->where('id', $id)->delete();
    }

    public function getAll()
    {
        return $this->model->orderby('group', 'desc')->get();
    }

    public function getItem($id)
    {
        return $this->model->where('id', $id)->first();
    }

    public function getValue($key)
    {
        $item = $this->model->where('key', $key)->first();

        return $item->value;
    }

    public function getItemByKey($key)
    {
        return $this->model->where('key', $key)->first();
    }

    public function getItemsByGroup($group)
    {
        return $this->model->groupBy('group')->get();
    }
}
