<?php
/**
 * Created by PhpStorm.
 * User: julien
 * Date: 29/08/18
 * Time: 11:43
 */

namespace Tchoblond59\LaraLight\Console;

use App\Console\Kernel as ConsoleKernel;
use Illuminate\Console\Scheduling\Schedule;

class Kernel extends ConsoleKernel
{
    public function schedule(Schedule $schedule)
    {
        parent::schedule($schedule);
        $schedule->command('laralight:auto_switch_off')->everyMinute();
    }
}