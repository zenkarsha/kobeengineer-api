<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostImageConfigTable extends Migration
{
    public function up()
    {
        Schema::create('post_image_config', function (Blueprint $table)
        {
            $table->bigIncrements('id');
            $table->bigInteger('post_id');
            $table->longText('config');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('post_image_config');
    }
}
