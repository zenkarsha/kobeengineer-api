<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AnalysisNeg extends Model
{
    protected $table = 'words_analysis_neg';
    protected $fillable = [
        'length', 'word', 'wt', 'pt',
        'ignore', 'group', 'label',
    ];

    public $timestamps = false;
}
