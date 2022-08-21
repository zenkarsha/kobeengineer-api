<?php

namespace App\Repositories;

use Doctrine\Common\Collections\Collection;
use App\PublisherQueue;

class PublisherQueueRepository
{
    protected $model;

    public function __construct(PublisherQueue $model)
    {
        $this->model = $model;
    }

    public function create($data)
    {
        $model = new PublisherQueue;
        $model->fill($data);
        return $model->save();
    }

    public function update($id, $data)
    {
        return $this->model->where('id', $id)->update($data);
    }

    public function delete($post_id)
    {
        return $this->model->where('post_id', $post_id)->delete();
    }

    public function getList($page)
    {
        return $this->model->orderby('id', 'asc')->paginate(50);
    }

    public function getItem($id)
    {
        return $this->model->where('id', $id)->first();
    }

    public function getItemByPostId($post_id)
    {
        return $this->model->where('post_id', $post_id)->first();
    }

    public function getFirstItem($priority = 1)
    {
        $item = $this->model
            ->join('post', 'post.id', '=', 'publisher_queue.post_id')
            ->where('post.priority', $priority)
            ->select('publisher_queue.id', 'publisher_queue.post_id')
            ->orderBy('publisher_queue.id', 'asc')
            ->first();

        return $item;
    }

    public function countLessThanId($id)
    {
        return $this->model
            ->join('post', 'post.id', '=', 'publisher_queue.post_id')
            ->where('post.priority', 1)
            ->where('publisher_queue.id', '<', $id)
            ->count();
    }
}
