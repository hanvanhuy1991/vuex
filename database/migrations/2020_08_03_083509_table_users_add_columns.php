<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TableUsersAddColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable();
            $table->tinyInteger('gender')->nullable();
            $table->dateTime('birthday')->nullable();
            $table->tinyInteger('type')->nullable();
            $table->dateTime('expiry_date')->nullable();
            $table->string('url')->nullable();
            $table->text('description')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->double('point')->nullable();
            $table->string('avatar')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('phone');
            $table->dropColumn('gender');
            $table->dropColumn('birthday');
            $table->dropColumn('type');
            $table->dropColumn('expiry_date');
            $table->dropColumn('url');
            $table->dropColumn('description');
            $table->dropColumn('status');
            $table->dropColumn('point');
            $table->dropColumn('avatar');
            $table->dropColumn('created_by');
            $table->dropColumn('updated_by');
            $table->dropColumn('deleted_by');
        });
    }
}
