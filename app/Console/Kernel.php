<?php

namespace App\Console;

use App\Http\Controllers\YandexMetrikaController;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
         $schedule->call(function (){
             $controller = new YandexMetrikaController();
             $controller->preparingAndSendData([
                 'date_start' => Carbon::yesterday(),
                 'date_end' => Carbon::yesterday(),
             ]);
         })->timezone('Europe/Moscow')->name('push_metrika_data')->withoutOverlapping()->dailyAt('01:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
