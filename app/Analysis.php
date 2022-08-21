<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Analysis extends Model
{
    protected $table = 'words_analysis';
    protected $fillable = [
        'length', 'word', 'wt', 'dwt', 'pt', 'dpt',
        'score', 'ignore', 'group', 'label',
    ];

    public $timestamps = false;
}
