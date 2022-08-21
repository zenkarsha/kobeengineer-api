<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentLikeTable extends Migration
{
    public function up()
    {
        Schema::create('comment_like', function (Blueprint $table)
        {
            $table->bigIncrements('id');
            $table->bigInteger('comment_id');
            $table->bigInteger('user_id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('comment_like');
    }
}
