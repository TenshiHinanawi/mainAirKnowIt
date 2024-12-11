<?php

// app/Console/Kernel.php
namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

use App\Models\WeatherData;
use App\Models\AirQualityData;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        \App\Console\Commands\FetchWeatherData::class,
    ];

    protected function schedule(Schedule $schedule)
{

    $schedule->command('fetch:data')->everyTenMinutes();
}


    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
    protected $routeMiddleware = [

        'admin' => \App\Http\Middleware\AdminMiddleware::class,
    ];
}


