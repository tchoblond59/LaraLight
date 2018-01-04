<?php

namespace Tchoblond59\LaraLight\EventListener;

use App\Events\MSMessageEvent;
use App\Sensor;
use Carbon\Carbon;
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
                $light_level = 100;
                $now = Carbon::now();
                \Log::info($now->format('H:i:s'));
                $periods = $ll_config->periods()->where('from', '<=' ,$now->format('H:i:s'))->where('to', '>=', $now->format('H:i:s'));
                if($periods->exists())
                {
                    $periods = $periods->first();
                    $light_level = $periods->light_level;
                    \Log::info('We have a specified level: '. $periods->light_level);
                    if($ll_config->loadLightSensorValue())
                        $lux = $ll_config->getLux();
                }
            }
        }
    }
}
