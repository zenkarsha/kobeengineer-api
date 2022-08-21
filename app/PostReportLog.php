<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostReportLog extends Model
{
    protected $table = 'post_report_log';
    protected $fillable = [
        'post_id', 'user_id',
    ];
}
