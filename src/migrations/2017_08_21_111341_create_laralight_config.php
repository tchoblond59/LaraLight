<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLaralightConfig extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ll_configs', function (Blueprint $table){
            $table->increments('id');
            $table->string('mode', 50);
            $table->integer('lux_limit')->unsigned()->default('0');;
            $table->integer('delay')->unsigned()->default('60');
            $table->dateTime('on_since')->nullable();
            $table->boolean('state')->default('0');
            $table->integer('relay_id')->unsigned();
            $table->integer('pir_sensor_id')->unsigned()->nullable();
            $table->integer('light_sensor_id')->unsigned()->nullable();
            $table->foreign('relay_id')->references('id')->on('sensors');
            $table->foreign('pir_sensor_id')->references('id')->on('sensors');
            $table->foreign('light_sensor_id')->references('id')->on('sensors');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ll_configs');
    }
}
