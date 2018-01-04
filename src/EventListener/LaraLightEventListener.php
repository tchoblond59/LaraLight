<?php

namespace Tchoblond59\LaraLight\EventListener;

use App\Events\MSMessageEvent;
use App\Sensor;
use Carbon\Carbon;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Tchoblond59\LaraLight\Models\LaraLight;
use Tchoblond59\LaraLight\Models\LaraLightConfig;

class LaraLightEventListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  MSMessageEvent  $event
     * @return void
     */
    public function handle(MSMessageEvent $event)
    {
        $sensor = LaraLight::where('node_address', '=', $event->message->getNodeId())->where('classname', '=', '\Tchoblond59\LaraLight\Models\LaraLight')->first();
        if($sensor)//Sensor find in database
        {
            $ll_config = LaraLightConfig::where('pir_sensor_id', '=', $sensor->id)->first();//Find config
            if($ll_config)//We are concern about the message
            {
                $light_level = 100;
                $now = Carbon::now();
                $periods = $ll_config->periods()->where('from', '<=' ,$now->format('H:i:s'))->where('to', '>=', $now->format('H:i:s'));
                if($periods->exists())//Find specific level for now
                {
                    $periods = $periods->first();
                    $light_level = $periods->light_level;

                }
                if($ll_config->mode=='auto')//In auto mode
                {
                    $lux=0;
                    if($ll_config->loadLightSensorValue())
                        $lux = $ll_config->getLux();
                    if($ll_config->lux_limit<$lux)//Luminosity of the room is under limit
                    {
                        //Trigger the light
                        $sensor->setLevel($light_level);
                    }
                }
            }
        }
    }
}
