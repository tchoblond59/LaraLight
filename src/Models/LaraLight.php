<?php
/**
 * Created by PhpStorm.
 * User: julien
 * Date: 21/06/17
 * Time: 15:01
 */

namespace Tchoblond59\LaraLight\Models;


use App\Sensor;
use App\Message;
use DB;
use App\Mqtt\MSMessage;
use App\Mqtt\MqttSender;
use Tchoblond59\LaraLight\Events\LaraLightEvent;
use Carbon\Carbon;

class LaraLight extends Sensor
{
    public function getWidget(\App\Widget $widget)
    {
        $sensor  = $widget->sensor;
        $state = 0;
        $ll_config = LaraLightConfig::where('relay_id', '=', $sensor->id)->first();
        if(!$ll_config)//No config exist
        {
            $ll_config = new LaraLightConfig();
            $ll_config->mode = LaraLightMode::Manual;
            $ll_config->lux_limit = 0;
            $ll_config->delay = 1;//Off delay
            $ll_config->on_since = Carbon::now();//Last time it was up
            $ll_config->state = 0;//Say its off at creation
            $ll_config->relay_id = $widget->sensor->id;//Sensor id that control the light
            $ll_config->pir_sensor_id = $widget->sensor->id;//Movement detection sensor
            $ll_config->light_sensor_id = $widget->sensor->id;//Lux sensor
            $ll_config->save();
        }
        return view('laralight::widget')->with(['widget' => $widget,
            'sensor' => $sensor,
            'll_config' => $ll_config,
        ]);
    }

    public function getCss()
    {
        return ['css/bootstrap-slider.css', 'css/tchoblond59/laralight/laralight.css'];
    }

    public function getJs()
    {
        return ['js/bootstrap-slider.js', 'js/tchoblond59/laralight/laralight.js'];
    }
    public function getWidgetList()
    {
        return [1 => 'Relay NONC'];
    }

    public function onDelete()
    {

    }

    public function setLevel($level)
    {
        $message = new MSMessage($this->id);
        $message->set($this->node_address, $this->sensor_address, 'V_PERCENTAGE',1);
        $message->setMessage($level);
        MqttSender::sendMessage($message);

        $config = $this->config;
        $config->on_since = Carbon::now();
        $config->state = $level;
        $config->save();

        $event = new LaraLightEvent($this, $level);
        event($event);

    }

    public function config()
    {
        return $this->hasOne('Tchoblond59\LaraLight\Models\LaraLightConfig', 'relay_id');
    }
}