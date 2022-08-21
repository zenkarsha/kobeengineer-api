<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AnalysisPosJieba extends Model
{
    protected $table = 'words_analysis_pos_jieba';
    protected $fillable = [
        'word', 'wt', 'pt',
        'ignore', 'group', 'label',
    ];

    public $timestamps = false;
}
