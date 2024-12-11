<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TemperatureReading;
use App\Models\AtmosphericCondition;
use App\Models\AirQualityData;
use App\Models\Location;
use Carbon\Carbon;

class WeatherHistoryController extends Controller
{
    public function showWeatherData(Request $request)
    {
        // Get the location from the request, default to 'all'
        $locationName = $request->input('location');

        // Query for weather data based on location
        if ($locationName != 'all') {
            $location = Location::where('location', $locationName)->first();

            if ($location) {
                $tempData = TemperatureReading::where('location_id', $location->id)->get();
                $atmosData = AtmosphericCondition::where('location_id', $location->id)->get();

                // Format created_at to include full date and time (e.g., '2024-12-04 10:33:55')
                $tempData = $tempData->map(function($item) {
                    // Use Carbon to format the created_at date
                    $item->created_at = Carbon::parse($item->created_at)->format('Y-m-d H:i:s');
                    return $item;
                });
                $atmosData = $atmosData->map(function($item) {
                    // Use Carbon to format the created_at date
                    $item->created_at = Carbon::parse($item->created_at)->format('Y-m-d H:i:s');
                    return $item;
                });
            } else {
                $tempData = collect();
                $atmosData = collect();
            }
        } else {
            $tempData = TemperatureReading::all();
            $atmosData = AtmosphericCondition::all();


            $tempData = $tempData->map(function($item) {

                $item->created_at = $item->created_at->toDateTimeString();
                return $item;
            });
            $atmosData = $atmosData->map(function($item) {

                $item->created_at = $item->created_at->toDateTimeString();
                return $item;
            });
        }

        return view('historical_weather', [
            'tempData' => $tempData,
            'atmosData' => $atmosData,
            'location' => $locationName
        ]);
    }

    public function showAirData(Request $request)
    {
        $locationName = $request->input('location'); // Default to 'Navotas' if no location is provided

        if ($locationName != 'all') {
            $location = Location::where('location', $locationName)->first();

            if ($location) {
                $airQualityData = AirQualityData::where('location_id', $location->id)->get();

                $airQualityData = $airQualityData->map(function($item) {
                    // Format the created_at field to a full date and time string
                    $item->created_at = $item->created_at->toDateTimeString();
                    return $item;
                });
            } else {
                $airQualityData = collect();
            }
        } else {
            // If location is 'all', fetch all air quality data
            $airQualityData = AirQualityData::all();

            // Format created_at to include full date and time
            $airQualityData = $airQualityData->map(function($item) {
                // Use Carbon to format the created_at date
                $item->created_at = Carbon::parse($item->created_at)->format('Y-m-d H:i:s');
                return $item;
            });
        }

        return view('historical_air', [
            'airQualityData' => $airQualityData,
            'location' => $locationName
        ]);
    }

}
