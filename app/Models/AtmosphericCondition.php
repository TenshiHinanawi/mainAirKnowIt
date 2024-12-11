<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AtmosphericCondition extends Model
{
    use CrudTrait, HasFactory;

    protected $fillable = ['location_id', 'humidity', 'pressure', 'visibility', 'cloudiness', 'wind_speed', 'wind_gust', 'wind_direction'];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
