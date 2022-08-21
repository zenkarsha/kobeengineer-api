<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsersNamePool extends Model
{

    protected $table = 'users_name_pool';
    protected $fillable = [
        'id', 'name', 'type',
    ];

    public $timestamps = false;
}
