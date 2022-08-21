<?php

namespace App\Repositories;

use Doctrine\Common\Collections\Collection;
use App\IpBlacklist;

class IpBlacklistRepository
{
    protected $model;

    public function __construct(IpBlacklist $model)
    {
        $this->model = $model;
    }

    public function create($data)
    {
        $model = new IpBlacklist;
        $model->fill($data);
        return $model->save();
    }

    public function firstOrCreate($data)
    {
        $model = new IpBlacklist;
        return $model->firstOrCreate($data);
    }

    public function delete($id)
    {
        return $this->model->where('id', $id)->delete();
    }

    public function getItem($id)
    {
        return $this->model->where('id', $id)->first();
    }

    public function getItemByIp($ip)
    {
        return $this->model->where('ip', $ip)->first();
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
