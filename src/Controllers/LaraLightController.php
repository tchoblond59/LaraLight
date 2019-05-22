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
use Tchoblond59\LaraLight\Models\LaraLightMode;
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
        $config = LaraLightConfig::where('relay_id', '=', $sensor->id)->first();
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
            'll_config' => $config
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
            'll_config' => $config
        ]);

    }

    public function period($id, Request $request)
    {
        $widget = Widget::findOrFail($id);
        $sensor = $widget->sensor;
        $config = LaraLightConfig::where('relay_id', '=', $sensor->id)->first();
        $sub_view = 'laralight::periods';
        $periods = Period::all();
        $state = 'periods';

        return view('laralight::index')->with(['sensor' => $sensor,
            'widget' => $widget,
            'periods' => $periods,
            'sub_view' => $sub_view,
            'state' => $state,
            'll_config' => $config
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
        $sensor = LaraLight::findOrFail($widget->sensor_id);
        /*$message = new MSMessage($sensor->id);
        $message->set($sensor->node_address, $sensor->sensor_address, 'V_PERCENTAGE',1);
        $message->setMessage($request->level);
        MqttSender::sendMessage($message);
        $event = new LaraLightEvent($sensor, $request->level);
        event($event);*/
        $sensor->setLevel($request->level);
        return json_encode($request->all());
    }

    public function postConfiguration($id, Request $request)
    {
        $this->validate($request, [
            'mode' => 'required',
            'lux_limit' => 'required|numeric',
            'delay' => 'required|numeric',
            'level_min' => 'required|numeric|min:0|max:100',
            'level_max' => 'required|numeric|min:0|max:100',
            'dimmer_delay' => 'required|numeric|min:0',
            'lux_sensor' => 'required|exists:sensors,id',
            'pir_sensor' => 'required|exists:sensors,id',
        ]);
        $widget = Widget::findOrFail($id);
        $sensor = $widget->sensor;
        $config = LaraLightConfig::where('relay_id', '=', $sensor->id)->first();
        $config->mode = $request->mode;
        $config->lux_limit = $request->lux_limit;
        $config->delay = $request->delay;
        $config->light_sensor_id = $request->lux_sensor;
        $config->pir_sensor_id = $request->pir_sensor;
        $config->level_min = $request->level_min;
        $config->level_max = $request->level_max;
        $config->dimmer_delay= $request->dimmer_delay;
        $config->enable_delay = $request->has('enable_delay') ? true : false;
        $config->save();
        $event = new LaraLightEvent($sensor, $config->state, $config);
        event($event);
        $sensor = LaraLight::find($sensor->id);
        $sensor->sendConfig();
        return redirect()->back();
    }

    public function updateMode(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|exists:widgets',
            'sensor_id' => 'required|exists:sensors,id',
        ]);
        $widget = Widget::findOrFail($request->id);
        $sensor = LaraLight::findOrFail($widget->sensor_id);
        $config = $sensor->config;
        if($config->mode == LaraLightMode::Auto)
            $config->mode = LaraLightMode::Manual;
        else if($config->mode == LaraLightMode::Manual)
            $config->mode = LaraLightMode::TimeOnly;
        else if($config->mode == LaraLightMode::TimeOnly)
            $config->mode = LaraLightMode::Auto;
        $config->save();
        $event = new LaraLightEvent($sensor, $config->state, $config);
        event($event);
        return json_encode('ok');
    }
}
