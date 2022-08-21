<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClientIdentification extends Model
{
    protected $table = 'client_identification';
    protected $fillable = [
        'identification', 'forbidden',
    ];
}
