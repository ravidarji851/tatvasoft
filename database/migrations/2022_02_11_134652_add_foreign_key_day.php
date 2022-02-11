<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeyDay extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event_recurence', function (Blueprint $table) {
            $table->bigInteger('days_id')->unsigned()->nullable();
            $table->foreign('days_id')->references('id')->on('days')->onDelete('cascade');

            //
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('event_recurence', function (Blueprint $table) {
            //
        });
    }
}
