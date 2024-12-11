<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('location');
            $table->string('country');
            $table->timestamps();
            $table->unique(['location', 'country']);
        });

        Schema::create('weather_conditions', function (Blueprint $table) {
            $table->foreignId('location_id')
                ->constrained('locations')
                ->onDelete('cascade');
            $table->string('weather');
            $table->string('description');
            $table->timestamps();
        });

        Schema::create('temperature_readings', function (Blueprint $table) {
            $table->foreignId('location_id')
                ->constrained('locations')
                ->onDelete('cascade');
            $table->float('temperature');
            $table->float('feels_like');
            $table->float('temp_min');
            $table->float('temp_max');
            $table->timestamps();
        });

        Schema::create('atmospheric_conditions', function (Blueprint $table) {
            $table->foreignId('location_id')
                ->constrained('locations')
                ->onDelete('cascade');
            $table->unsignedTinyInteger('humidity');
            $table->float('pressure');
            $table->unsignedBigInteger('visibility');
            $table->unsignedTinyInteger('cloudiness');
            $table->float('wind_speed');
            $table->float('wind_gust')->nullable();
            $table->float('wind_direction')->nullable();
            $table->timestamps();
        });

        Schema::create('air_quality_data', function (Blueprint $table) {
            $table->foreignId('location_id')
                ->constrained('locations')
                ->onDelete('cascade');
            $table->float('pm2_5');
            $table->float('pm10');
            $table->float('o3');
            $table->float('so2');
            $table->float('no');
            $table->float('no2');
            $table->float('co');
            $table->float('nh3');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('air_quality_data');
        Schema::dropIfExists('atmospheric_conditions');
        Schema::dropIfExists('temperature_readings');
        Schema::dropIfExists('weather_conditions');
        Schema::dropIfExists('locations');
    }

};


