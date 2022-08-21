<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AnalysisNegJieba extends Model
{
    protected $table = 'words_analysis_neg_jieba';
    protected $fillable = [
        'word', 'wt', 'pt',
        'ignore', 'group', 'label',
    ];

    public $timestamps = false;
}
