<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWordsAnalysisJiebaTable extends Migration
{
    public function up()
    {
        Schema::create('words_analysis_jieba', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('word');
            $table->bigInteger('wt'); // word total
            $table->bigInteger('dwt'); // deny word total
            $table->bigInteger('pt'); // post total
            $table->bigInteger('dpt'); // deny post total
            $table->float('score', 5, 4);
            $table->tinyInteger('ignore')->default(0);
            $table->string('group')->nullable();
        });
    }

    public function down()
    {
        Schema::drop('words_analysis_jieba');
    }
}
