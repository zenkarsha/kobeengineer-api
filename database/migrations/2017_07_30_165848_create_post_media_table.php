<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostMediaTable extends Migration
{
    public function up()
    {
        Schema::create('post_media', function (Blueprint $table)
        {
            $table->bigIncrements('id');
            $table->bigInteger('post_id');
            $table->string('type');
            $table->string('url');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('post_media');
    }
}
