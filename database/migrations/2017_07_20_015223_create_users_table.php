<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('provider');
            $table->string('provider_id');
            $table->tinyInteger('public')->default(0);
            $table->tinyInteger('flagged')->default(0);
            $table->tinyInteger('banned')->default(0);
            $table->tinyInteger('verified')->default(0);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('users');
    }
}
