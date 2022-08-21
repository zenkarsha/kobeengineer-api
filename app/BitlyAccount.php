<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BitlyAccount extends Model
{
    protected $table = 'bitly_account';
    protected $fillable = [
        'bitly_key', 'bitly_login', 'bitly_clientid', 'bitly_secret',
        'bitly_access_token', 'usage',
    ];
}
