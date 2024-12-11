<x-app-layout>
    @auth

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Temperature Forecast</title>
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
                        <h2 class="text-base font-large text-gray-800 dark:text-gray-200 hover:underline">Forecasted
                            Temperatures for the Next 12 Hours at <span
                                id="location-name">{{ request('location', '---') }}</span></h2>
                    </h2>
                    <form action="{{ route('forecast.temperature') }}" method="GET">
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
                                                <a href="{{ route('forecast.temperature', ['location_id' => $location->id]) }}"
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
                                    class="text-gray-800 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700">{{ __('Profile') }}</x-dropdown-link>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault(); this.closest('form').submit();"
                                        class="text-gray-800 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700">{{ __('Log Out') }}</x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                </div>
            </x-slot>
            <div class="card-container">
                @if (isset($location))
                    @if (isset($predictions) && !empty($predictions))
                        <div class="card-wrapper">
                            <div class="card2">
                                <h1>Temperature</h1>
                                <p>Forecasted Temperatures for the next 12 hours in <strong>{{ request('location_id') ? App\Models\Location::find(request('location_id'))->location : '---' }}</strong></p>
                                <ul>
                                </ul>
                                <canvas id="temperature-chart"></canvas>
                            </div>
                        </div>
                    @else
                        <p>No forecast available for this location yet. Please try again later or select another location.</p>
                    @endif
                @endif
            </div>
            <script>
                const locationName =
                    "{{ request('location_id') ? App\Models\Location::find(request('location_id'))->location : '---' }}";
                document.getElementById("location-name").innerText = locationName;

                document.addEventListener('DOMContentLoaded', function() {
                    const predictions = @json($predictions);

                    const data = [];
                    const labels = [];

                    // Assuming predictions is an object like {'1_hour': 20, '2_hour': 22, ...}
                    Object.keys(predictions).forEach((key) => {
                        const label = key.replace('_', ' ').replace('_hour', ' hour'); // Format key
                        labels.push(label);
                        data.push(predictions[key]);
                    });

                    const ctx = document.getElementById('temperature-chart').getContext('2d');

                    // Calculate dynamic min and max values for Y-axis based on data
                    const minTemp = Math.min(...data);
                    const maxTemp = Math.max(...data);
                    const yMin = minTemp - 2; // Add some padding below the minimum value
                    const yMax = maxTemp + 5; // Add some padding above the maximum value

                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Temperature (°C)',
                                data: data,
                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                x: {
                                    ticks: {
                                        autoSkip: false
                                    }
                                },
                                y: {
                                    suggestedMin: yMin,
                                    suggestedMax: yMax
                                }
                            }
                        }
                    });
                });
            </script>
            <script src="{{ asset('forecast.js') }}"></script>
        </body>
    @endauth
</x-app-layout>
