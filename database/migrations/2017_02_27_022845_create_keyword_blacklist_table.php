<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKeywordBlacklistTable extends Migration
{
    public function up()
    {
        Schema::create('keyword_blacklist', function (Blueprint $table)
        {
            $table->increments('id');
            $table->string('keyword');
            $table->tinyInteger('forbidden')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('keyword_blacklist');
    }
}
