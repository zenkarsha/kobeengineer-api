<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingTable extends Migration
{
    public function up()
    {
        Schema::create('setting', function (Blueprint $table)
        {
            $table->increments('id');
            $table->string('key');
            $table->string('value');
            $table->string('type');
            $table->string('label');
            $table->string('group')->default(0);
            $table->text('custom_config')->nullable();
        });
    }

    public function down()
    {
        Schema::drop('setting');
    }
}
