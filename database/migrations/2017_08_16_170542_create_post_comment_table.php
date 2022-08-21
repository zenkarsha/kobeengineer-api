<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostCommentTable extends Migration
{
    public function up()
    {
        Schema::create('post_comment', function (Blueprint $table)
        {
            $table->bigIncrements('id');
            $table->bigInteger('post_id');
            $table->bigInteger('user_id');
            $table->bigInteger('main_id')->defualt(0);
            $table->text('content');
            $table->bigInteger('likes')->default(0);
            $table->integer('report')->default(0);
            $table->tinyInteger('abuse')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::drop('post_comment');
    }
}
