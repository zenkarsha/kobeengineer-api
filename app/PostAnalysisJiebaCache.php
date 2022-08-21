<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostAnalysisJiebaCache extends Model
{
    protected $table = 'post_analysis_jieba_cache';
    protected $fillable = [
        'post_id', 'cache',
    ];
}
