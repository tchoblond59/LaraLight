<?php

namespace Tchoblond59\LaraLight\Controllers;

use App\Http\Controllers\Controller;

use App\ScheduledMSCommands;
use App\Widget;
use Illuminate\Http\Request;
use Tchoblond59\LaraLight\Models\LaraLight;
use App\Sensor;
use App\Mqtt\MSMessage;
use App\Mqtt\MqttSender;
use App\Message;
use App\MSCommand;

class LaraLightController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function setLevel(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|exists:widgets',
            'sensor_id' => 'required|exists:sensors,id',
            'level' => 'required|min:0|max:100'
        ]);
        $widget = Widget::findOrFail($request->id);
        $sensor = Sensor::findOrFail($widget->sensor_id);
        $message = new MSMessage($sensor->id);
        $message->set($sensor->node_address, $sensor->sensor_address, 'V_PERCENTAGE',1);
        $message->setMessage($request->level);
        MqttSender::sendMessage($message);
        return json_encode($request->all());
    }
}
