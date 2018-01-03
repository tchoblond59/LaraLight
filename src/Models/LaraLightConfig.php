<?php

namespace Tchoblond59\LaraLight\Models;

use Illuminate\Database\Eloquent\Model;

class LaraLightConfig extends Model
{
    public $table = 'll_configs';

    public $timestamps = false;

    public function sensor()
    {
        return $this->belongsTo('App\Sensor', 'relay_id');
    }

    public function pir_sensor()
    {
        return $this->belongsTo('App\Sensor', 'pir_sensor_id');
    }

    public function lux_sensor()
    {
        return $this->belongsTo('App\Sensor', 'light_sensor_id');
    }

    public function periodsConfig()
    {
        return $this->hasMany('Tchoblond59\LaraLight\Models\PeriodConfig');
    }

    public function periods()
    {
        return $this->belongsToMany('Tchoblond59\LaraLight\Models\Period', 'll_periods_configs', 'configuration_id', 'll_periods_levels_id');
    }
}
