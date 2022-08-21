<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIpBlacklistTable extends Migration
{
    public function up()
    {
        Schema::create('ip_blacklist', function (Blueprint $table)
        {
            $table->increments('id');
            $table->string('ip');
            $table->tinyInteger('forbidden')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('ip_blacklist');
    }
}
