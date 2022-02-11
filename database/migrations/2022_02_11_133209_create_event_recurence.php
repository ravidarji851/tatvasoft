<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventRecurence extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_recurence', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('event_id')->unsigned();
            $table->foreign('event_id')->references('id')->on('event')->onDelete('cascade');
            $table->enum('repeat_every',['0','1','2','3'])->nullable()->comment="0-Every,1-Other,2-Third,3-Four";
            $table->enum('repeat_day',['0','1','2','3'])->nullable()->comment="0-Day,1-week,2-month,3-year";
            $table->enum('repeat_on_the',['0','1','2','3'])->nullable()->comment="0-First,1-Second,2-Third,3-Four";
            $table->enum('repeat_on_the_year',['0','1','2','3','4'])->nullable()->comment="0-Month,1-3Month,2-4Month,3-6Month,4-6month";
            $table->timestamps();

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_recurence');
    }
}
