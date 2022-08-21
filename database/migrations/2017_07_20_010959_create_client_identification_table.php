<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientIdentificationTable extends Migration
{
    public function up()
    {
        Schema::create('client_identification', function (Blueprint $table)
        {
            $table->bigIncrements('id');
            $table->string('identification');
            $table->tinyInteger('forbidden')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('client_identification');
    }
}
