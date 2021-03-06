<?php

namespace Tchoblond59\LaraLight\Models;
use App\Interfaces\CommandInterface;
use App\Sensor;
use Illuminate\Database\Eloquent\Model;

class LaraLightCommand extends Model implements CommandInterface
{
    protected $table = 'll_commands';
    protected $guarded = ['created_at', 'updated_at'];
    public function play()
    {
        if($this->type == 'SWITCH_OFF_ALL')
        {
            $lights = LaraLight::where('classname', '\Tchoblond59\LaraLight\Models\LaraLight')->get();
            foreach ($lights as $light)
            {
                $light->setLevel(0);
                usleep(250*1000);
            }
        }
        else if($this->type == 'SWITCH_ON_ALL')
        {
            $lights = LaraLight::where('classname', '\Tchoblond59\LaraLight\Models\LaraLight')->get();
            foreach ($lights as $light)
            {
                $light->setLevel(100);
                usleep(250*1000);
            }
        }
        else if($this->type == 'SET_LEVEL')
        {
            $light = LaraLight::find($this->sensor_id);
            $light->setLevel($this->value);
        }
    }

    public function sensor()
    {
        return $this->belongsTo(Sensor::class);
    }
}
