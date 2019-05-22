<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddParamsDimmer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ll_configs', function (Blueprint $table) {
            $table->unsignedInteger('level_min')->default(0);
            $table->unsignedInteger('level_max')->default(100);
            $table->unsignedInteger('dimmer_delay')->default(3)->comment("Dimming time in seconds");
            $table->boolean('enable_delay')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ll_configs', function (Blueprint $table) {
            $table->dropColumn(['level_min', 'level_max', 'dimmer_delay', 'enable_delay']);
        });
    }
}
