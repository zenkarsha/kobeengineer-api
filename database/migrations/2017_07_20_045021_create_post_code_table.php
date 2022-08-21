<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostCodeTable extends Migration
{
    public function up()
    {
        Schema::create('post_code', function (Blueprint $table)
        {
            $table->bigIncrements('id');
            $table->bigInteger('post_id');
            $table->text('code');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('post_code');
    }
}
