<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TablePostsAddColumnsForLocation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->renameColumn('location', 'location_name');

        });

        Schema::table('posts', function (Blueprint $table) {
            $table->double('location_long')->nullable()->after('location_name');
            $table->double('location_lat')->nullable()->after('location_name');
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
            $table->renameColumn('location_name', 'location');
            $table->dropColumn('location_lat');
            $table->dropColumn('location_long');
        });
    }
}
