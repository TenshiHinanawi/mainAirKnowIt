<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemperatureReading extends Model
{
    use CrudTrait, HasFactory;

    protected $fillable = ['location_id', 'temperature', 'feels_like', 'temp_min', 'temp_max'];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
