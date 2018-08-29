<?php

namespace Tchoblond59\LaraLight\Console\Commands;

use App\Sensor;
use App\SensorFactory;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Tchoblond59\LaraLight\Models\LaraLightConfig;
use Tchoblond59\LaraLight\Models\LaraLightMode;
use Tchoblond59\LaraLight\Models\LaraLight;

class AutoSwitchOffCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laralight:auto_switch_off';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto switch off light if needed';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        \Log::useFiles(storage_path('/logs/laralight.log'), 'info');
        \Log::info('AutoSwitchOff');
        $ll_configs = LaraLightConfig::where('mode', LaraLightMode::Auto)->where('state', '!=', '0')->get();
        foreach ($ll_configs as $config)
        {
            $now = Carbon::now();
            $diff_in_minutes = $now->diffInMinutes($config->on_since);

            if($diff_in_minutes>=$config->delay)//Delay is passed from last detection
            {
                \Log::info('Delay is passed, diff in minutes is '.$diff_in_minutes);
                $laralight = SensorFactory::create($config->sensor->classname, $config->sensor->id);
                $laralight->setLevel(0);
                \Log::info('Switch OFF '.$config->sensor->id);
                \Log::info('Switch OFF '.$config->sensor->classname);
                //\Log::info('Switch OFF '.$laralight->id);
            }
        }
    }
}
