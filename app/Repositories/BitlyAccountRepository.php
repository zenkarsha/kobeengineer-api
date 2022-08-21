<?php

namespace App\Repositories;

use Doctrine\Common\Collections\Collection;
use App\BitlyAccount;

class BitlyAccountRepository
{
    protected $model;

    public function __construct(BitlyAccount $model)
    {
        $this->model = $model;
    }

    public function create($data)
    {
        $model = new BitlyAccount;
        $model->fill($data);
        return $model->save();
    }

    public function update($id, $data)
    {
        return $this->model->where('id', $id)->update($data);
    }

    public function increaseUsage($id)
    {
        return $this->model->where('id', $id)->increment('usage');
    }

    public function delete($id)
    {
        return $this->model->where('id', $id)->delete();
    }

    public function getItem($id)
    {
        return $this->model->where('id', $id)->first();
    }

    public function getLeastUsageItem()
    {
        return $this->model->where('usage', '<', 5000)->orderby('usage', 'asc')->first();
    }

    public function getAll()
    {
        return $this->model->orderby('created_at', 'desc')->get();
    }

    public function getExpiredItems($datetime)
    {
        return $this->model->where('updated_at', '<', $datetime)->get();
    }
}
