<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeatherCondition extends Model
{
    use CrudTrait, HasFactory;

    protected $fillable = ['location_id', 'weather', 'description'];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
