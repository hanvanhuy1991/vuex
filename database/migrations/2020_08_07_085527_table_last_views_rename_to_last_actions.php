<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TableLastViewsRenameToLastActions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('last_views', 'last_actions');
        Schema::table('last_actions', function (Blueprint $table) {
            $table->integer('type')->after('post_id');
            $table->dropColumn('last_view');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('last_actions', 'last_views');
        Schema::table('last_views', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dateTime('last_view')->after('post_id')->nullable();
        });
    }
}
