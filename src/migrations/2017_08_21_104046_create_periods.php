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
        Schema::create('ll_periods_levels', function (Blueprint $table){
            $table->increments('id');
            $table->time('from')->default('00:00:00');
            $table->time('to')->default('23:59:59');
            $table->integer('light_level')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ll_periods_levels');
    }
}
