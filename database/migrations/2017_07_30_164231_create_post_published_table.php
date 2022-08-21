<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostPublishedTable extends Migration
{
    public function up()
    {
        Schema::create('post_published', function (Blueprint $table)
        {
            $table->bigIncrements('id');
            $table->bigInteger('post_id');
            $table->string('type');
            $table->tinyInteger('success')->default(0);
            $table->string('pid')->nullable();
            $table->string('url')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::drop('post_published');
    }
}
