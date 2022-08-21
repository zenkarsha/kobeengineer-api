<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KeywordBlacklist extends Model
{
    protected $table = 'keyword_blacklist';
    protected $fillable = [
        'keyword', 'forbidden',
    ];
}
