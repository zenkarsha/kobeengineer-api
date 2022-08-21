<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostAnalysisLog extends Model
{
    protected $table = 'post_analysis_log';
    protected $fillable = [
        'post_id', 'type', 'score',
    ];
}
