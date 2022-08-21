<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostImageConfig extends Model
{
    protected $table = 'post_image_config';
    protected $fillable = [
        'post_id', 'config',
    ];
}
