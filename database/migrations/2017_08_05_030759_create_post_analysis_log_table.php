<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostAnalysisLogTable extends Migration
{
    public function up()
    {
        Schema::create('post_analysis_log', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('post_id');
            $table->string('type'); // length_1, length_1_pos, jieba, jieba_pos ...
            $table->float('score', 5, 4);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('post_analysis_log');
    }
}
