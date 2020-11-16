<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TableImageTagAddColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('image_tag', function (Blueprint $table) {
            $table->integer('ratio_height')->default(0)->after('tag_id');
            $table->integer('ratio_width')->default(0)->after('tag_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('image_tag', function (Blueprint $table) {
            $table->dropColumn('ratio_height');
            $table->dropColumn('ratio_width');
        });
    }
}
