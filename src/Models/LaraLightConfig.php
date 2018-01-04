<?php

namespace Tchoblond59\LaraLight\Models;

use Illuminate\Database\Eloquent\Model;
use App\Message;
use App\Sensor;

class LaraLightConfig extends Model
{
    public $table = 'll_configs';

    public $timestamps = false;

    private $lux = 0;

    protected $dates = ['on_since'];

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

    public function loadLightSensorValue()
    {
        if($this->light_sensor_id)
        {
            $sensor = Sensor::find($this->light_sensor_id);
            if($sensor)
            {
                $message = new Message();
                $message = $message->whereSensorIs($sensor)
                    ->where('command', '=', 1)
                    ->where('type', '=', 37);
                if($message->exists())
                {
                    $this->lux = $message->first()->value;
                    return true;
                }
            }
            else
            {
                return false;
            }
        }
        else
            return false;
    }

    public function getLux()
    {
        return $this->lux;
    }
}
