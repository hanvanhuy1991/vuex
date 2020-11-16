<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TablePostsNullableFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->string('media_type')->nullable()->change();
            $table->text('thumbnail')->nullable()->change();
            $table->string('thumbnail_width')->nullable()->change();
            $table->string('thumbnail_height')->nullable()->change();
            $table->string('account_type')->nullable()->change();
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
            $table->string('media_type', 32)->change();
            $table->text('thumbnail')->change();
            $table->integer('thumbnail_height')->after('thumbnail')->change();
            $table->integer('thumbnail_width')->after('thumbnail')->change();
            $table->integer('account_type')->after('thumbnail')->change();
        });
    }
}
