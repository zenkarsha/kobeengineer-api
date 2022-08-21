<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBitlyAccountsTable extends Migration
{
    public function up()
    {
        Schema::create('bitly_accounts', function (Blueprint $table)
        {
            $table->increments('id');
            $table->string('bitly_key');
            $table->string('bitly_login');
            $table->string('bitly_clientid');
            $table->string('bitly_secret');
            $table->string('bitly_access_token');
            $table->integer('usage');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('bitly_accounts');
    }
}
