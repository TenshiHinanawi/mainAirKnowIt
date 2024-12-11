<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AirQualityData extends Model
{
    use CrudTrait;
    use HasFactory;

    protected $table = 'air_quality_data';

    protected $fillable = [
        'location_id', 'pm2_5', 'pm10', 'o3', 'so2', 'no', 'no2', 'co', 'nh3'
    ];
    /**
     * Relationship with the Location model.
     */
    public function location()
    {
        return $this->belongsTo(Location::class); // Relating to the location model (one-to-many inverse)
    }
}
