<?php

namespace App\Repositories;

use Doctrine\Common\Collections\Collection;
use App\AnalysisJieba;
use Carbon\Carbon;

class AnalysisJiebaRepository
{
    protected $model;

    public function __construct(AnalysisJieba $model)
    {
        $this->model = $model;
    }

    public function create($data)
    {
        $model = new AnalysisJieba;
        $model->fill($data);
        return $model->save();
    }

    public static function insertAll($datas)
    {
        $now = Carbon\Carbon::now();
        $datas = collect($datas)->map(function (array $data) use ($now) {
            return $this->timestamps ? array_merge([
                'created_at' => $now,
                'updated_at' => $now,
            ], $data) : $data;
        })->all();

        return \DB::table(static::table())->insert($datas);
    }

    public function update($word, $data)
    {
        return $this->model->where('word', $word)->update($data);
    }

    public function increase($word, $column, $value = 1)
    {
        return $this->model->where('word', $word)->increment($column, $value);
    }

    public function decrease($word, $column, $value = 1)
    {
        return $this->model->where('word', $word)->decrement($column, $value);
    }

    public function ignore($word)
    {
        return $this->model->where('word', $word)->update(['ignore' => 1]);
    }

    public function getItem($word, $exclude_ignore = true)
    {
        $query = $this->model->where('word', $word);
        if ($exclude_ignore) $query->where('ignore', 0);

        return $query->first();
    }

    public function getItemsByArray($array)
    {
        return $this->model->whereIn('word', $array)->get();
    }

    public function getByGroup($group, $exclude_ignore = true)
    {
        $query = $this->model->where('group', $group);
        if ($exclude_ignore) $query->where('ignore', 0);

        return $query->get();
    }

}
