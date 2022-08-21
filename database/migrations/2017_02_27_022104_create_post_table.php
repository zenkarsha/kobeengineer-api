<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostTable extends Migration
{
    public function up()
    {
        Schema::create('post', function (Blueprint $table)
        {
            $table->bigIncrements('id');
            $table->string('key');
            $table->tinyInteger('type')->default(1);
            $table->binary('content');
            $table->string('hashtag')->nullable();
            $table->string('link')->nullable();
            $table->string('reply_to')->nullable();

            $table->integer('fb_likes')->default(0);
            $table->integer('fb_comments')->default(0);
            $table->integer('fb_shares')->default(0);
            $table->integer('fb_social_total')->default(0);
            $table->integer('report')->default(0);

            // post states
            $table->tinyInteger('pending')->default(0);
            $table->tinyInteger('queuing')->default(0);
            $table->tinyInteger('denied')->default(0);
            $table->tinyInteger('analysed')->default(0);
            $table->integer('published')->default(0);
            $table->tinyInteger('unpublished')->default(0);
            $table->tinyInteger('priority')->default(1);

            // author detail
            $table->string('client_ip')->default(0);
            $table->bigInteger('client_identification')->default(0);
            $table->integer('user_id')->default(0);

            // allow not logged in to modify post
            $table->string('query_token');
            $table->string('delete_token');

            // allow user to verify pending post
            $table->integer('pending_votes_goal')->default(0);
            $table->integer('pending_votes')->default(0);

            // auto clear fb post link ater 1 month
            $table->tinyInteger('fb_content_cleared')->default(0);

            // true post id (base 16)
            $table->string('true_id')->nullable();

            // publish to original page
            $table->tinyInteger('sync_to_bigplatform')->default(0);

            $table->tinyInteger('mark_positive')->default(0);
            $table->tinyInteger('mark_negative')->default(0);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::drop('post');
    }
}
