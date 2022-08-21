<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AnalysisPos extends Model
{
    protected $table = 'words_analysis_pos';
    protected $fillable = [
        'length', 'word', 'wt', 'pt',
        'ignore', 'group', 'label',
    ];

    public $timestamps = false;
}
