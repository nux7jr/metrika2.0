<?php

namespace App\Console\Commands;

use App\Http\Controllers\YandexMetrikaController;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Console\Command;

class metrika extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:metrika {date1} {date2}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get info by click calls on sites from Yandex Metrika';

    /**
     * Execute the console command.
     * @throws \Exception
     */
    public function handle(): void
    {
        $period = CarbonPeriod::create($this->argument('date1'),$this->argument('date2'));
        foreach ($period as $date){
            $controller = new YandexMetrikaController();
            $controller->preparingAndSendData([
                'date_start' => $date,
                'date_end' => $date,
            ]);
        }
    }
}
