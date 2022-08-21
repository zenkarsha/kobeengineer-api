<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostCode extends Model
{
    protected $table = 'post_code';
    protected $fillable = [
        'post_id', 'code',
    ];
}
