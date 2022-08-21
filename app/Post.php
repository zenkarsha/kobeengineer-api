<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;

    protected $table = 'post';
    protected $fillable = [
        'key', 'type', 'content', 'hashtag', 'link', 'reply_to',
        'fb_likes', 'fb_comments', 'fb_shares', 'fb_social_total', 'report',
        'pending', 'queuing', 'denied', 'analysed',
        'published', 'unpublished', 'priority',
        'client_ip', 'client_identification', 'user_id',
        'query_token', 'delete_token',
        'pending_votes_goal', 'pending_votes', 'fb_content_cleared',
        'true_id', 'sync_to_bigplatform',
        'mark_positive', 'mark_negative',
    ];
}
