<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TablePostsAddColumnWithHeightAccountType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->integer('account_type')->after('thumbnail');
            $table->integer('thumbnail_height')->after('thumbnail');
            $table->integer('thumbnail_width')->after('thumbnail');
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
            $table->dropColumn('thumbnail_width');
            $table->dropColumn('thumbnail_height');
            $table->dropColumn('account_type');
        });
    }
}
