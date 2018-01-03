<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLightLevelPeriods extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ll_periods_configs', function (Blueprint $table){
            $table->increments('id');

            $table->integer('ll_periods_levels_id')->unsigned();
            $table->integer('configuration_id')->unsigned();

            $table->foreign('ll_periods_levels_id')->references('id')->on('ll_periods_levels');
            $table->foreign('configuration_id')->references('id')->on('ll_configs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ll_periods_configs');
    }
}
