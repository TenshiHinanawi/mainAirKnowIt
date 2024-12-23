<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Artisan;
use App\Models\WeatherData;
use App\Models\AirQualityData;


class WeatherController extends Controller
{
    public function getWeatherData(Request $request)
    {
        $lat = $request->input('lat');
        $lon = $request->input('lon');
        $apiKey = config('services.openweather.api_key');

        $weatherUrl = "https://api.openweathermap.org/data/2.5/weather?lat={$lat}&lon={$lon}&appid={$apiKey}&units=metric";
        $airQualityUrl = "https://api.openweathermap.org/data/2.5/air_pollution?lat={$lat}&lon={$lon}&appid={$apiKey}";
        $locationUrl = "https://api.openweathermap.org/geo/1.0/reverse?lat={$lat}&lon={$lon}&limit=1&appid={$apiKey}";

        $weatherData = Http::get($weatherUrl)->json();
        $airQualityData = Http::get($airQualityUrl)->json();
        $locationData = Http::get($locationUrl)->json();

        return response()->json([
            'weather' => $weatherData,
            'air_quality' => $airQualityData,
            'location' => $locationData,
        ]);
    }

    public function fetchData(Request $request)
    {

        $cities = $request->input('cities', null);
        $citiesArgument = $cities ? $cities : null;
        Artisan::call('fetch:data', ['cities' => $citiesArgument]);
        Artisan::call('clean:cleanup');
        return redirect()->route('dashboard')->with('success', 'Fetched and cleaned successfully');
    }

}
