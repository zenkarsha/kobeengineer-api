<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPostPublishedAtColumn extends Migration
{
    public function up()
    {
        Schema::table('post', function(Blueprint $table) {
          $table->bigInteger('true_id_base10')->nullable();
          $table->timestamp('published_at')->nullable();
        });
    }

    public function down()
    {
        Schema::table('post', function($table) {
            $table->dropColumn('true_id_base10');
            $table->dropColumn('published_at');
        });
    }
}
