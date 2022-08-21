<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostComment extends Model
{
    use SoftDeletes;

    protected $table = 'post_comment';
    protected $fillable = [
        'post_id', 'user_id', 'main_id', 'content',
        'report', 'abuse',
    ];
}
