<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AnalysisJieba extends Model
{
    protected $table = 'words_analysis_jieba';
    protected $fillable = [
        'word', 'wt', 'dwt', 'pt', 'dpt',
        'score', 'ignore', 'group', 'label',
    ];

    public $timestamps = false;
}
