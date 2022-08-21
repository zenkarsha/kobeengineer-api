<?php

namespace App\Repositories;

use Doctrine\Common\Collections\Collection;
use App\ClientIdentification;

class ClientIdentificationRepository
{
    protected $model;

    public function __construct(ClientIdentification $model)
    {
        $this->model = $model;
    }

    public function create($data)
    {
        $model = new ClientIdentification;
        $model->fill($data);
        return $model->save();
    }

    public function update($id, $data)
    {
        return $this->model->where('id', $id)->update($data);
    }

    public function updateColumn($id, $column, $value)
    {
        return $this->model->where('id', $id)->update([$column => $value]);
    }

    public function getList($page)
    {
        return $this->model->orderby('id', 'desc')->paginate(100);
    }

    public function getItem($id)
    {
        return $this->model->where('id', $id)->first();
    }

    public function getItemByIdentification($identification)
    {
        return $this->model->where('identification', $identification)->first();
    }

    public function countByIdentification($identification)
    {
        return $this->model->where('identification', $identification)->count();
    }

}
