<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TablePostsAddColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->integer('status')->after('id')->nullable();
            $table->text('location')->after('id')->nullable();
            $table->text('content')->after('id')->nullable();
            $table->string('title')->after('id')->nullable();
            $table->integer('topic_id')->after('id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('topic_id');
            $table->dropColumn('title');
            $table->dropColumn('content');
            $table->dropColumn('location');
            $table->dropColumn('status');
        });
    }
}
