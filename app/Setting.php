<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'setting';
    protected $fillable = [
        'key', 'value', 'type', 'label', 'group', 'custom_config',
    ];

    public $timestamps = false;
}
