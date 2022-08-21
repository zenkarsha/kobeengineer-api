<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDomainWhitelistTable extends Migration
{
    public function up()
    {
        Schema::create('domain_whitelist', function (Blueprint $table) {
            $table->increments('id');
            $table->string('domain');
        });
    }

    public function down()
    {
        Schema::drop('domain_whitelist');
    }
}
