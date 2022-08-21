<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostPublished extends Model
{
    use SoftDeletes;

    protected $table = 'post_published';
    protected $fillable = [
        'post_id', 'type', 'success', 'pid', 'url',
    ];
}
