<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use CrudTrait;
    use HasFactory;

    protected $fillable = [
        'location', 'country',
    ];
    /**
     * One-to-many relationship with AirQualityData.
     */
    public function airQualityData()
    {
        return $this->hasMany(AirQualityData::class); // A location can have many air quality data entries
    }

    /**
     * One-to-many relationship with other Tables.
     */
    public function weatherConditions()
    {
        return $this->hasMany(WeatherCondition::class);
    }

    public function temperatureReadings()
    {
        return $this->hasMany(TemperatureReading::class);
    }

    public function atmosphericConditions()
    {
        return $this->hasMany(AtmosphericCondition::class);
    }
}
