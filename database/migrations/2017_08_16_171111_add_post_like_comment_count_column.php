<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPostLikeCommentCountColumn extends Migration
{
    public function up()
    {
        Schema::table('post', function(Blueprint $table) {
          $table->bigInteger('likes')->default(0);
          $table->bigInteger('comments')->default(0);
        });
    }

    public function down()
    {
        Schema::table('post', function($table) {
            $table->dropColumn('likes');
            $table->dropColumn('comments');
        });
    }
}
