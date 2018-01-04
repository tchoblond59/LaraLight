<?php

namespace Tchoblond59\LaraLight\Controllers;

use App\Http\Controllers\Controller;

use App\ScheduledMSCommands;
use App\Widget;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Sensor;
use App\Mqtt\MSMessage;
use App\Mqtt\MqttSender;
use App\Message;
use App\MSCommand;
use Tchoblond59\LaraLight\Events\LaraLightEvent;
use Tchoblond59\LaraLight\Models\LaraLight;
use Tchoblond59\LaraLight\Models\LaraLightConfig;
use Tchoblond59\LaraLight\Models\Period;
use Tchoblond59\LaraLight\Models\PeriodConfig;
use Tchoblond59\LaraLight\Models\PeriodLevel;

class LaraLightController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function config($id, Request $request)
    {
        $widget = Widget::findOrFail($id);
        $sensor = $widget->sensor;
        $laralight_configs = LaraLightConfig::all();
        $sub_view = 'laralight::config_table';
        $state = 'config';
        if($request->has('tab'))
        {
            $sub_view = 'laralight::'.$request->tab;
        }
        return view('laralight::index')->with(['sensor' => $sensor,
            'widget' => $widget,
            'laralight_configs' => $laralight_configs,
            'sub_view' => $sub_view,
            'state' => $state,
            ]);
    }

    public function createPeriod(Request $request)
    {
        $this->validate($request, [
            'from' => "required",
            'to' => "required",
            'light_level' => "required",
        ]);

        $period = new Period();
        $period->from = $request->from;
        $period->to = $request->to;
        $period->light_level = $request->light_level;
        $period->save();

        return redirect()->back();
    }

    public function assignPeriod(Request $request)
    {
        $this->validate($request, [
            'relay_id' => "required|exists:sensors,id",
            'period' => 'required|exists:ll_periods_levels,id',
        ]);

        $config = LaraLightConfig::where('relay_id', '=', $request->relay_id)->first();
        $period = new PeriodConfig();
        $period->ll_periods_levels_id = $request->period;
        $period->configuration_id = $config->id;
        $period->save();

        return redirect()->back();
    }

    public function periodConfig($id)
    {
        $widget = Widget::findOrFail($id);
        $sensor = $widget->sensor;
        $sub_view = 'laralight::assign_periods';
        $state = 'periods_configs';
        $config = LaraLightConfig::where('relay_id', '=', $sensor->id)->first();
        $periods_config = PeriodConfig::where('configuration_id', '=', $config->id)->get();
        return view('laralight::index')->with(['sensor' => $sensor,
            'widget' => $widget,
            'periods_configs' => $periods_config,
            'sub_view' => $sub_view,
            'state' => $state,
        ]);

    }

    public function period($id, Request $request)
    {
        $widget = Widget::findOrFail($id);
        $sensor = $widget->sensor;
        $sub_view = 'laralight::periods';
        $periods = Period::all();
        $state = 'periods';

        return view('laralight::index')->with(['sensor' => $sensor,
            'widget' => $widget,
            'periods' => $periods,
            'sub_view' => $sub_view,
            'state' => $state,
        ]);

    }

    public function createConfig(Request $request)
    {
        $this->validate($request, [
            'relay_id' => "required|exists:sensors,id",
            'mode' => "required",
            'delay' => "required",
            'lux_limit' => "required",
        ]);

        $light = new LaraLightConfig();
        $light->mode = $request->mode;
        $light->delay = $request->delay;
        $light->lux_limit = $request->lux_limit;
        $light->relay_id = $request->relay_id;
        if($request->has('pir_sensor'))
        {
            $light->pir_sensor_id = $request->pir_sensor;
        }
        if($request->has('lux_sensor'))
        {
            $light->light_sensor_id = $request->lux_sensor;
        }
        $light->save();

        return redirect()->back();
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
        $event = new LaraLightEvent($sensor, $request->level);
        event($event);
        return json_encode($request->all());
    }
}
