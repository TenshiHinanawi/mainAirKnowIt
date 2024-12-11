<?php

namespace App\Http\Controllers;

use App\Models\TemperatureReading;
use App\Models\AtmosphericCondition;
use App\Models\Location;
use Phpml\Regression\SVR;
use Phpml\SupportVectorMachine\Kernel;
use Illuminate\Http\Request;
use Phpml\Preprocessing\Normalizer;

class TemperatureForecastController extends Controller
{
    public function temperature_forecast(Request $request)
    {
        $locations = Location::all();
        $predictions = [];

        // Check if a location is selected
        if ($request->has('location_id')) {
            $locationId = $request->input('location_id');
            $data = TemperatureReading::where('location_id', $locationId)
                ->orderBy('created_at', 'asc')
                ->get();

            // Return a message if no data exists for the location
            if ($data->isEmpty()) {
                return view('temperature_forecast', [
                    'locations' => $locations,
                    'message' => 'Unable to generate predictions. Try again later.',
                    'predictions' => $predictions,
                ]);
            }

            // Prepare the data for training the regression model
            $samples = [];
            $targets = [];

            // Populate the samples and targets arrays for temperature
            foreach ($data as $entry) {
                $samples[] = [
                    $entry->created_at->timestamp,
                    $entry->humidity,
                    $entry->wind_speed,
                    $entry->pressure,
                ];

                $targets[] = $entry->temperature;
            }

            // Prepare the subsets dynamically
            $minDataPoints = 24;  // Minimum number of data points
            $maxDataPoints = 288; // Maximum number of data points
            $step = 24;           // Increment by 24 data points at each step

            // Loop through the data to make predictions for each of the 12 hours
            for ($i = $minDataPoints; $i <= $maxDataPoints; $i += $step) {
                // Create the data subset (samples and targets)
                $samplesSubset = array_slice($samples, -$i);
                $valuesSubset = array_slice($targets, -$i);

                // Ensure there are enough data points to train the model
                if (count($valuesSubset) >= $i) {
                    // Create and train the SVR model
                    $regressor = new SVR(Kernel::RBF, $cost = 1000);
                    $regressor->train($samplesSubset, $valuesSubset);

                    // Get the most recent data point
                    $latestData = $data->last();
                    $latestCreatedAt = $latestData->created_at;

                    // Calculate the prediction time (increment by $i hours)
                    $predictedTime = $latestCreatedAt->copy()->addHours($i / 24);  // Divide by 24 for hourly prediction

                    // Prepare the next sample for prediction
                    $nextSample = [
                        $predictedTime->timestamp,
                        $latestData->humidity,
                        $latestData->wind_speed,
                        $latestData->pressure,
                    ];

                    // Normalize and predict using the model
                    $normalizer = new Normalizer();
                    $nextSampleNormalized = [$nextSample];
                    $normalizer->transform($nextSampleNormalized); // Normalize the data
                    $predictedValue = $regressor->predict($nextSampleNormalized[0]);

                    // Store the predicted temperature with the predicted time as the key
                    $predictions[$predictedTime->format('g A')] = round($predictedValue, 4);
                }
            }

            $location = Location::find($locationId);

            // Return the forecast view with predictions
            return view('temperature_forecast', [
                'locations' => $locations,
                'location' => $location,
                'predictions' => $predictions,
            ]);
        }

        // Return the forecast view when no location is selected
        return view('temperature_forecast', [
            'locations' => $locations,
            'predictions' => $predictions,
        ]);
    }
}
