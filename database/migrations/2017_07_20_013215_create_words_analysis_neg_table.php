<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWordsAnalysisNegTable extends Migration
{
    public function up()
    {
        Schema::create('words_analysis_neg', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('length');
            $table->string('word');
            $table->bigInteger('wt'); // word total
            $table->bigInteger('pt'); // post total
            $table->tinyInteger('ignore')->default(0);
            $table->string('group')->nullable();
            $table->string('label')->nullable();
        });
    }

    public function down()
    {
        Schema::drop('words_analysis_neg');
    }
}
