<?php

namespace App\Http\Controllers;

use App\Models\AirQualityData;
use App\Models\Location;
use Phpml\Regression\SVR;
use Phpml\SupportVectorMachine\Kernel;
use Illuminate\Http\Request;
use Phpml\Preprocessing\Normalizer;

class AirQualityForecastController extends Controller
{
    public function forecast(Request $request)
    {
        $locations = Location::all();
        $attribute = $request->input('attribute', 'pm2_5');
        $predictions = [];

        // Define unhealthy levels for different pollutants
        $unhealthyLevels = [
            'pm2_5' => 35.4,
            'pm10' => 154,
            'o3' => 140,
            'so2' => 250,
            'no' => 100,
            'no2' => 150,
            'co' => 12400,
            'nh3' => 150,
        ];

        // Check if a location is selected
        if ($request->has('location_id')) {
            $locationId = $request->input('location_id');
            $data = AirQualityData::where('location_id', $locationId)
                ->orderBy('created_at', 'asc')
                ->get();

            // Return a message if no data exists for the location
            if ($data->isEmpty()) {
                return view('forecast', [
                    'locations' => $locations,
                    'message' => 'Unable to generate predictions. Try again later.',
                    'predictions' => $predictions,
                    'unhealthyLevels' => $unhealthyLevels,
                ]);
            }

            // Prepare the data for training the regression model
            $samples = [];
            $targets = [
                'pm2_5' => [],
                'pm10' => [],
                'o3' => [],
                'so2' => [],
                'no' => [],
                'no2' => [],
                'co' => [],
                'nh3' => [],
            ];

            // Populate the samples and targets arrays
            foreach ($data as $entry) {
                // Collect sample data (timestamp, temperature, humidity, wind speed, pressure)
                $samples[] = [
                    $entry->created_at->timestamp,
                    $entry->temperature,
                    $entry->humidity,
                    $entry->wind_speed,
                    $entry->pressure,
                ];

                // Collect target data for pollutants
                $targets['pm2_5'][] = $entry->pm2_5;
                $targets['pm10'][] = $entry->pm10;
                $targets['o3'][] = $entry->o3;
                $targets['so2'][] = $entry->so2;
                $targets['no'][] = $entry->no;
                $targets['no2'][] = $entry->no2;
                $targets['co'][] = $entry->co;
                $targets['nh3'][] = $entry->nh3;
            }

            // Define the number of hours to predict (12 hours)
            $predictionHours = 12;
            $predictions = [];

            // For each pollutant, we will predict the next 12 hours progressively
            foreach ($targets as $key => $values) {
                $predictedValues = [];
                $regressor = new SVR(Kernel::RBF, $cost = 1000);

                // Initially, use the last 24 data points
                $samplesSubset = array_slice($samples, -24);
                $valuesSubset = array_slice($values, -24); // Use the last 24 target values

                // Train the model with the initial 24 data points
                $regressor->train($samplesSubset, $valuesSubset);

                // Generate predictions for the next 12 hours
                for ($hour = 1; $hour <= $predictionHours; $hour++) {
                    // Use the latest known data to predict the next hour
                    $latestData = $data->last();
                    $nextSample = [
                        now()->addHours(1)->timestamp,  // Predict for the next hour
                        $latestData->temperature,
                        $latestData->humidity,
                        $latestData->wind_speed,
                        $latestData->pressure,
                    ];

                    // Normalize and predict using the model
                    $normalizer = new Normalizer();
                    $nextSampleNormalized = [$nextSample];
                    $normalizer->transform($nextSampleNormalized); // Normalize the data
                    $predictedValue = $regressor->predict($nextSampleNormalized[0]);

                    // Store the predicted value for the corresponding hour
                    $predictedHour = now()->addHours($hour)->format('g A');
                    $predictedValues[$predictedHour] = round($predictedValue, 4);

                    // After each prediction, update the samplesSubset with the new predicted value
                    // Add the predicted value to the samples and values for the next prediction
                    $samplesSubset[] = $nextSample;
                    $valuesSubset[] = $predictedValue;

                    // Ensure that we only use the latest data (shift the window)
                    if (count($samplesSubset) > $hour * 24) {
                        array_shift($samplesSubset); // Remove the oldest data point
                        array_shift($valuesSubset);  // Remove the oldest target value
                    }

                    // Re-train the model with the updated subset
                    $regressor->train($samplesSubset, $valuesSubset);
                }

                $predictions[$key] = $predictedValues;
            }

            $location = Location::find($locationId);

            // Return the forecast view with predictions
            return view('forecast', [
                'locations' => $locations,
                'location' => $location,
                'predictions' => $predictions,
                'unhealthyLevels' => $unhealthyLevels,
                'attribute' => $attribute,
            ]);
        }

        // Return the forecast view when no location is selected
        return view('forecast', [
            'locations' => $locations,
            'attribute' => $attribute,
            'predictions' => $predictions,
            'unhealthyLevels' => $unhealthyLevels,
        ]);
    }
}
