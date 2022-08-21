<?php

namespace App\Repositories;

use Doctrine\Common\Collections\Collection;
use App\Post;

class PostRepository
{
    protected $model;

    public function __construct(Post $model)
    {
        $this->model = $model;
        $this->pre_page = 10;
    }

    public function create($data)
    {
        $model = new Post;
        $model->fill($data);
        return $model->save();
    }

    public function createGetId($data)
    {
        $model = new Post;
        $model->fill($data);
        $model->save();
        return $model->id;
    }

    public function update($id, $data)
    {
        return $this->model->where('id', $id)->withTrashed()->update($data);
    }

    public function increasePublished($id)
    {
        return $this->model->where('id', $id)->increment('published');
    }

    public function increaseReport($id)
    {
        return $this->model->where('id', $id)->increment('report');
    }

    public function delete($id)
    {
        return $this->model->where('id', $id)->delete();
    }

    public function deleteByKey($key)
    {
        return $this->model->where('key', $key)->delete();
    }

    public function getList($page, $include_admin_post = true)
    {
        if ($include_admin_post)
            return $this->model->orderby('id', 'desc')->paginate($this->pre_page);
        else
            return $this->model->where('user_id', '!=', 0)->orderby('id', 'desc')->paginate($this->pre_page);
    }

    public function getPendingList($page)
    {
        return $this->model->where('pending', 1)->orderby('id', 'asc')->paginate($this->pre_page);
    }

    public function getListByUserId($user_id, $page)
    {
        return $this->model->where('user_id', $user_id)->orderby('id', 'desc')
          ->select('id', 'key', 'type', 'content', 'true_id', 'pending', 'queuing', 'denied', 'analysed', 'published', 'unpublished', 'created_at')
          ->paginate(50);
    }

    public function getFeedList($page)
    {
        return $this->model
            ->where('published', '>', 0)
            ->select('id', 'key', 'type', 'content', 'hashtag', 'link', 'reply_to', 'true_id', 'fb_likes', 'fb_comments', 'fb_shares', 'updated_at', 'published_at', 'likes')
            ->orderby('true_id_base10', 'desc')->paginate(6);
    }

    public function getFeedTop100List($page)
    {
        return $this->model
            ->where('published', '>', 0)
            ->select('id', 'key', 'type', 'content', 'hashtag', 'link', 'reply_to', 'true_id', 'fb_likes', 'fb_comments', 'fb_shares', 'updated_at', 'published_at', 'likes')
            ->orderby('fb_social_total', 'desc')->take(100)->paginate(6);
    }

    public function getFeedRankingList($page, $datetime, $order_by = 'fb_social_total')
    {
        return $this->model
            ->where('published', '>', 0)
            ->where('created_at', '>=', $datetime)
            ->select('id', 'key', 'type', 'content', 'hashtag', 'link', 'reply_to', 'true_id', 'fb_likes', 'fb_comments', 'fb_shares', 'updated_at', 'published_at', 'likes')
            ->orderby($order_by, 'desc')->paginate(6);
    }

    public function getRecentlyPublishedPosts()
    {
        return $this->model
            ->where('published', '>', 0)
            ->select('id', 'key', 'type', 'content', 'hashtag', 'link', 'reply_to', 'true_id', 'updated_at', 'published_at', 'likes')
            ->orderby('id', 'desc')->take(10)->get();
    }

    public function getUserLastPost($user_id)
    {
        return $this->model
            ->where('user_id', $user_id)
            ->select('id', 'key', 'type', 'content', 'hashtag', 'link', 'reply_to', 'true_id', 'updated_at', 'published_at')
            ->where('published', '>', 0)
            ->orderby('true_id_base10', 'desc')
            ->first();
    }

    public function getUserPosts($page, $user_id)
    {
        return $this->model
            ->where('published', '>', 0)
            ->select('id', 'key', 'type', 'content', 'hashtag', 'link', 'reply_to', 'true_id', 'fb_likes', 'fb_comments', 'fb_shares', 'updated_at', 'published_at', 'likes')
            ->where('user_id', $user_id)
            ->orderby('true_id_base10', 'desc')->paginate(6);
    }

    public function getPublishedPostsByDatetime($datetime)
    {
        return $this->model
            ->where('published', '>', 0)
            ->where('created_at', '>=', $datetime)
            ->select('id')
            ->get();
    }

    public function getItem($id)
    {
        return $this->model->where('id', $id)->withTrashed()->first();
    }

    public function getItemByKey($key)
    {
        return $this->model->where('key', $key)->first();
    }

    public function getItemByTrueId($true_id)
    {
        return $this->model->where('true_id', $true_id)->first();
    }

    public function getItemForAnalysis()
    {
        # TODO: 需分析優先，如為新文章要優先分析
        # 如已經後台新增或已經手動審核，則排到最後分析
        return $this->model->where('analysed', 0)->orderby('id', 'asc')->first();
    }

    public function searchPosts($keywords, $state = 'all', $type = 0, $page)
    {
        $query = $this->model;

        if ($type != 0) {
            $query = $query->where('type', $type);
        }

        if ($state != 'all' && $state != '') {
            if ($state == 'analysising')
                $query = $query->where('analysed', 0);
            elseif ($state == 'deleted')
                $query = $query->where('deleted_at', '!=', '')->withTrashed();
            else
                $query = $query->where($state, 1);
        }

        if (count($keywords) > 0) {
            $query = $query->where(function ($q) use ($keywords) {
                foreach ($keywords as $keyword) {
                    # TODO: 需改為真正的模糊搜尋
                    $q->orWhere('content', 'LIKE', '%' . $keyword . '%');
                    $q->orWhere('content', 'LIKE', '%' . strtolower($keyword) . '%');
                    $q->orWhere('content', 'LIKE', '%' . strtoupper($keyword) . '%');
                    $q->orWhere('content', 'LIKE', '%' . ucfirst($keyword) . '%');
                }
            });
        }

        return $query->paginate($this->pre_page);
    }

    public function searchPostsByColumnValue($column, $value, $state = 'all', $type = 0, $page)
    {
        $query = $this->model->where($column, $value)->orderby('id', 'desc');

        if ($type != 0) {
            $query = $query->where('type', $type);
        }

        if ($state != 'all' && $state != '') {
            if ($state == 'analysising')
                $query = $query->where('analysed', 0);
            elseif ($state == 'deleted')
                $query = $query->where('deleted_at', '!=', '')->withTrashed();
            else
                $query = $query->where($state, 1);
        }

        return $query->paginate($this->pre_page);
    }

    public function getFieldCount($field_key, $value)
    {
        return $this->model->where($field_key, $value)->count();
    }

    public function countPublishedPost()
    {
        return $this->model->where('true_id', '!=', '')->withTrashed()->count();
    }

    public function countUserPosts($user_id)
    {
        return $this->model->where('user_id', $user_id)->count();
    }
}
