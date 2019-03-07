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
        if($event->message->getType() == 16 && $event->message->getMessage()==1)//V_TRIPPED type for pir sensor
        {
            //Get the pir sensor
            $pir_sensor = Sensor::where('node_address', $event->message->getNodeId())
                ->where('sensor_address', $event->message->getChildId())
                ->first();

            if($pir_sensor != null)
            {
                //Find all config that have this pir_sensor_id
                $configs = LaraLightConfig::where('pir_sensor_id', $pir_sensor->id)->get();
                foreach ($configs as $config)//Trigger all laralight
                {
                    $sensor = $config->sensor;
                    $sensor->pirTriggered();
                }
            }
        }
    }

    //Handle when the light level change
    public function onLevelChange()
    {

    }
}
