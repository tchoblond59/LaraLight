<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePeriods extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('periods', function (Blueprint $table){
            $table->increments('id');
            $table->integer('day_of_week')->unsigned();
            $table->time('from')->default('00:00:00');
            $table->time('to')->default('23:59:59');
            $table->string('group', 255);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('periods');
    }
}
