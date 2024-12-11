<x-app-layout>
    @auth

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Air Quality Forecast</title>
            <link rel="stylesheet" href="{{ asset('forecast.css') }}">
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        </head>

        <body>
            <x-slot name="header">
                <div class="flex justify-between items-center">
                    <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200">
                        <a href="{{ url('/') }}"
                            class="text-base font-large text-gray-800 dark:text-gray-200 hover:underline">
                            AirKnowIt
                        </a>
                        <h2 class="text-base font-large text-gray-800 dark:text-gray-200 hover:underline">
                            Forecasted Air Quality for the Next 12 Hours at <span
                                id="location-name">{{ request('location', '---') }}</span>
                        </h2>
                    </h2>
                    <form action="{{ route('forecast') }}" method="GET">
                        <div class="dropdown">
                            <button type="button" class="dropdown-btn">
                                {{ request('location_id') ? App\Models\Location::find(request('location_id'))->location : 'Select a Location' }}
                            </button>
                            <div class="dropdown-content">
                                @php
                                    $locationsGrouped = $locations->groupBy('country');
                                @endphp
                                @foreach ($locationsGrouped as $country => $groupedLocations)
                                    <div class="dropdown-group" onmouseenter="populateCities('{{ $country }}')"
                                        onmouseleave="hideCities('{{ $country }}')">
                                        <strong>{{ $country }}</strong>
                                        <div id="cityDropdown-{{ $country }}" class="city-dropdown"
                                            style="display: none;">
                                            @foreach ($groupedLocations as $location)
                                                <a href="{{ route('forecast', ['location_id' => $location->id]) }}"
                                                    class="{{ request('location_id') == $location->id ? 'active' : '' }}">
                                                    {{ $location->location }}
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </form>
                    <div class="flex gap-4">
                        <a href="{{ url('/dashboard') }}"
                            class="text-base font-medium text-gray-800 dark:text-gray-200 hover:underline">Dashboard</a>

                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button>
                                    <div style="font-weight: bold;" class="font-bold">{{ Auth::user()->name }}</div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <x-dropdown-link :href="route('profile.edit')"
                                    class="text-gray-800 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700">
                                    {{ __('Profile') }}
                                </x-dropdown-link>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault(); this.closest('form').submit();"
                                        class="text-gray-800 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700">
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                </div>
            </x-slot>

            <div class="card-container">
                @if (isset($location))
                    @if (isset($predictions) && !empty($predictions))
                        @php
                            $airQualityNames = [
                                'pm2_5' => 'PM2.5 (Fine Particulate Matter)',
                                'pm10' => 'PM10 (Coarse Particulate Matter)',
                                'o3' => 'Ozone (O₃)',
                                'so2' => 'Sulfur Dioxide (SO₂)',
                                'no' => 'Nitric Oxide (NO)',
                                'no2' => 'Nitrogen Dioxide (NO₂)',
                                'co' => 'Carbon Monoxide (CO)',
                                'nh3' => 'Ammonia (NH₃)',
                            ];
                        @endphp

                        <div class="card-wrapper">
                            @foreach (['pm2_5', 'pm10', 'o3', 'so2', 'no', 'no2', 'co', 'nh3'] as $key)
                                <div class="card2">
                                    <h1>{{ $airQualityNames[$key] }}</h1>
                                    @if (isset($predictions[$key]))
                                        <p>Forecasted Levels for the next 12 hours:</p>
                                        <ul>
                                        </ul>
                                        <canvas id="{{ $key }}-chart"></canvas>
                                    @else
                                        <p>No data available for {{ $airQualityNames[$key] }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p>No forecast available for this location yet. Please try again later or select another location.
                        </p>
                    @endif
                @endif
            </div>

            <script>
                const locationName =
                    "{{ request('location_id') ? App\Models\Location::find(request('location_id'))->location : '---' }}";
                document.getElementById("location-name").innerText = locationName;

                document.addEventListener('DOMContentLoaded', function() {
                    const predictions = @json($predictions);

                    if (Object.keys(predictions).length === 0) {
                        console.log("No predictions available");
                    }

                    Object.keys(predictions).forEach(key => {
                        const ctx = document.getElementById(`${key}-chart`);
                        if (ctx && predictions[key]) {
                            const labels = [];
                            const data = [];

                            // Loop through the predictions for this specific air quality key
                            Object.keys(predictions[key]).forEach(hour => {
                                labels.push(hour); // Use the hour as the label
                                data.push(predictions[key][hour] !== null ? predictions[key][hour] :
                                0); // Add the value or 0 if null
                            });

                            new Chart(ctx, {
                                type: 'line',
                                data: {
                                    labels: labels,
                                    datasets: [{
                                        label: `${key.toUpperCase()} Levels (µg/m³)`,
                                        data: data,
                                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                        borderColor: 'rgba(75, 192, 192, 1)',
                                        borderWidth: 1
                                    }]
                                },
                                options: {
                                    scales: {
                                        y: {
                                            beginAtZero: true
                                        }
                                    }
                                }
                            });
                        }
                    });
                });
            </script>
            <script src="{{ asset('forecast.js') }}"></script>
        </body>
    @endauth
</x-app-layout>
