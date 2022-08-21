<?php

namespace App\Repositories;

use Doctrine\Common\Collections\Collection;
use App\UsersNamePool;

class UsersNamePoolRepository
{
    protected $model;

    public function __construct(UsersNamePool $model)
    {
        $this->model = $model;
    }

    public function create($data)
    {
        $name = $data['name'];
        $type = $data['type'];

        $record = $this->getItemByNameAndType($name, $type);
        if (is_null($record)) {
            $model = new UsersNamePool;
            $model->fill($data);
            return $model->save();
        }

        return NULL;
    }

    public function update($id, $data)
    {
        return $this->model->where('id', $id)->update($data);
    }

    public function delete($id)
    {
        return $this->model->where('id', $id)->delete();
    }

    public function getItemByNameAndType($name, $type)
    {
        return $this->model->where('name', $name)->where('type', $type)->first();
    }

    public function getRandomGroupItem()
    {
        return $this->model->orderByRaw("RAND()")->get();
    }
}
