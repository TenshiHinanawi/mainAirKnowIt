<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('homepage');
})->name('homepage');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


use App\Http\Controllers\WeatherController;


Route::get('/search', function () {
    return view('main');
});

Route::get('/api/weather', [WeatherController::class, 'getWeatherData']);


use App\Http\Controllers\WeatherHistoryController;

Route::get('/historical-weather', [WeatherHistoryController::class, 'showWeatherData'])->name('historical.weather');
Route::get('/historical-air', [WeatherHistoryController::class, 'showAirData'])->name('historical.air');


Route::post('/fetch-weather', [WeatherController::class, 'fetchData'])->name('fetch.weather');


use App\Http\Controllers\AirQualityForecastController;
Route::get('/forecast', [AirQualityForecastController::class, 'forecast'])->name('forecast');

use App\Http\Controllers\TemperatureForecastController;
Route::get('/forecast-temperature', [TemperatureForecastController::class, 'temperature_forecast'])->name('forecast.temperature');



require __DIR__.'/auth.php';
