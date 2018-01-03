<?php

namespace Tchoblond59\LaraLight\EventListener;

use App\Events\MSMessageEvent;
use App\Sensor;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
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
        $sensor = Sensor::where('node_address', '=', $event->message->getNodeId())->where('classname', '=', '\Tchoblond59\LaraLight\Models\LaraLight')->first();
        if($sensor)
        {
            $ll_config = LaraLightConfig::where('pir_sensor_id', '=', $sensor->id)->first();
            if($ll_config)//We are concern about the message
            {

            }
        }
    }
}
