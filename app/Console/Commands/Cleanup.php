<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AirQualityData;

class Cleanup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clean:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test cleanup for weather data older than 7 days.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        // Log cleanup process start
        $this->info('Starting cleanup for weather data older than 7 days.');

        // Delete weather data older than 3 hours using 'created_at' field (or 'recorded_at' if you changed it)
        $deletedAirData = AirQualityData::where('created_at', '<', now()->subWeek())->delete();

        // Log the number of weather records deleted
        if ($deletedAirData ) {
            $this->info("Deleted $deletedAirData air quality records.");
        } else {
            $this->info('No air quality records older than 7 days found for deletion.');
        }

        // Log cleanup completion
        $this->info('Cleanup process completed.');
    }
}
