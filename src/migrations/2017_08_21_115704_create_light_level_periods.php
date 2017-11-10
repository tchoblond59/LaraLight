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
        Schema::table('light_level_periods', function (Blueprint $table){
            $table->increments('id');
            $table->integer('light_level')->unsigned();
            $table->string('period_group', 255);

            $table->integer('configuration_id')->unsigned();

            $table->foreign('configuration_id')->references('id')->on('laralight_configs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('light_level_periods');
    }
}
