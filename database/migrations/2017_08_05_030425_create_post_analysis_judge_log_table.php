<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostAnalysisJudgeLogTable extends Migration
{
    public function up()
    {
        Schema::create('post_analysis_judge_log', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('post_id');
            $table->float('score', 5, 4);
            $table->string('judge');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('post_analysis_judge_log');
    }
}
