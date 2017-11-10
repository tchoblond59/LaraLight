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

class LaraLight extends Sensor
{
    public function getWidget(\App\Widget $widget)
    {
        $sensor  = $widget->sensor;
        $state = 0;
        $last_message = Message::where('node_address', '=', $sensor->node_address)
            ->where('sensor_address', '=', $sensor->sensor_address)
            ->orderBy('created_at', 'desc')->first();

        if($last_message!=null)
        {
            $state = $last_message->value;
        }

        return view('laralight::widget')->with(['widget' => $widget,
        'sensor' => $sensor,
        'state' => $state,
        ]);
    }

    public function getCss()
    {
        return ['css/bootstrap-slider.css'];
    }

    public function getJs()
    {
        return ['js/bootstrap-slider.js'];
    }
    public function getWidgetList()
    {
        return [1 => 'Relay NONC'];
    }

    public function onDelete()
    {

    }
}