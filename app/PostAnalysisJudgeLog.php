<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostAnalysisJudgeLog extends Model
{
    protected $table = 'post_analysis_judge_log';
    protected $fillable = [
        'post_id', 'score', 'judge',
    ];
}
