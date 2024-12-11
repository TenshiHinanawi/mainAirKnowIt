<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\WeatherCondition;
use App\Models\TemperatureReading;
use App\Models\AtmosphericCondition;
use App\Models\AirQualityData;
use App\Models\Location;

class FetchWeatherData extends Command
{
    protected $signature = 'fetch:data {cities?}';
    protected $description = 'Fetch weather and air quality data and save to the database';

    public function handle()
    {
        $user = auth()->user();

        if (!$user) {
            $this->error("No authenticated user found. Please ensure you're logged in.");
            return;
        }

        $citiesInput = $this->argument('cities');
        $cities = $citiesInput ? explode(',', $citiesInput) : [
            "Navotas", "Malabon", "Quezon", "Manila", "Caloocan", "Valenzuela",
            "Taguig", "Mandaluyong", "Makati", "Pasay", "Pasig", "Marikina"
        ];

        $API_KEY = '49e2122f5c9da4368f1cd972696db508';

        foreach ($cities as $city) {
            $weatherUrl = "https://api.openweathermap.org/data/2.5/weather?q={$city}&appid={$API_KEY}&units=metric";
            $response = Http::get($weatherUrl);

            if ($response->successful()) {
                $data = $response->json();

                // Check if location already exists or create a new one
                $location = Location::firstOrCreate(
                    [
                        'location' => $city,
                        'country' => $data['sys']['country'],
                    ],
                    [
                        'updated_at' => now()
                    ]
                );

                // Create weather condition data entry
                WeatherCondition::create([
                    'location_id' => $location->id,
                    'weather' => $data['weather'][0]['main'],
                    'description' => $data['weather'][0]['description'],
                ]);

                // Create temperature reading data entry
                TemperatureReading::create([
                    'location_id' => $location->id,
                    'temperature' => $data['main']['temp'],
                    'feels_like' => $data['main']['feels_like'],
                    'temp_min' => $data['main']['temp_min'],
                    'temp_max' => $data['main']['temp_max'],
                ]);

                // Create atmospheric condition data entry
                AtmosphericCondition::create([
                    'location_id' => $location->id,
                    'humidity' => $data['main']['humidity'],
                    'pressure' => $data['main']['pressure'],
                    'visibility' => $data['visibility'],
                    'cloudiness' => $data['clouds']['all'],
                    'wind_speed' => $data['wind']['speed'],
                    'wind_gust' => $data['wind']['gust'] ?? null,
                    'wind_direction' => $data['wind']['deg'],
                ]);

                // Fetch and save air quality data
                $lat = $data['coord']['lat'];
                $lon = $data['coord']['lon'];
                $airQualityUrl = "https://api.openweathermap.org/data/2.5/air_pollution?lat={$lat}&lon={$lon}&appid={$API_KEY}";
                $airQualityResponse = Http::get($airQualityUrl);

                if ($airQualityResponse->successful()) {
                    $airQualityData = $airQualityResponse->json();

                    // Create the air quality data entry
                    AirQualityData::create([
                        'location_id' => $location->id,
                        'pm2_5' => $airQualityData['list'][0]['components']['pm2_5'],
                        'pm10' => $airQualityData['list'][0]['components']['pm10'],
                        'o3' => $airQualityData['list'][0]['components']['o3'],
                        'so2' => $airQualityData['list'][0]['components']['so2'],
                        'no' => $airQualityData['list'][0]['components']['no'],
                        'no2' => $airQualityData['list'][0]['components']['no2'],
                        'co' => $airQualityData['list'][0]['components']['co'],
                        'nh3' => $airQualityData['list'][0]['components']['nh3'],
                    ]);

                    $this->info("Air quality data for {$city} saved successfully.");
                } else {
                    $this->error("Failed to fetch air quality data for {$city}");
                }

                $this->info("Weather data for {$city} saved successfully.");
            } else {
                $this->error("Failed to fetch weather data for {$city}");
            }
        }
    }
}
