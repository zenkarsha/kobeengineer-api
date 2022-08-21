<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAutobotTable extends Migration
{
    public function up()
    {
        Schema::create('autobot', function (Blueprint $table)
        {
            $table->increments('id');
            $table->string('name');
            $table->string('access_token');
            $table->string('session');
            $table->string('job');
            $table->integer('frequency');
            $table->timestamp('last_poked_at');
            $table->tinyInteger('booting')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::drop('autobot');
    }
}
