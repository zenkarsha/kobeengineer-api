<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostAnalysisJiebaCacheTable extends Migration
{
    public function up()
    {
        Schema::create('post_analysis_jieba_cache', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('post_id');
            $table->longText('cache');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('post_analysis_jieba_cache');
    }
}
