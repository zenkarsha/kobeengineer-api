<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostLikeTable extends Migration
{
    public function up()
    {
        Schema::create('post_like', function (Blueprint $table)
        {
            $table->bigIncrements('id');
            $table->bigInteger('post_id');
            $table->bigInteger('user_id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('post_like');
    }
}
