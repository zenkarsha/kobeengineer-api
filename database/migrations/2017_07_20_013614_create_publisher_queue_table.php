<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePublisherQueueTable extends Migration
{
    public function up()
    {
        Schema::create('publisher_queue', function (Blueprint $table)
        {
            $table->bigIncrements('id');
            $table->bigInteger('post_id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('publisher_queue');
    }
}
