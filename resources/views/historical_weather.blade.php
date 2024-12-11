<x-app-layout>
    @auth

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <link rel="stylesheet" href="{{ asset('historical.css') }}">
            <title>Weather Data</title>
        </head>
        <x-slot name="header">
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200">
                    <a href="{{ url('/') }}"
                        class="text-base font-large text-gray-800 dark:text-gray-200 hover:underline">
                        AirKnowIt
                    </a>
                </h2>
                <div class="dropdown">
                    <button class="dropdown-btn">{{ request('location', 'Select Location') }}</button>
                    <div class="dropdown-content">
                        @php
                            // Get locations grouped by country for a cleaner dropdown structure
                            $locationsGrouped = App\Models\Location::all()->groupBy('country');
                        @endphp
                        @foreach ($locationsGrouped as $country => $groupedLocations)
                            <div class="dropdown-group" onmouseenter="populateCities('{{ $country }}')" onmouseleave="hideCities('{{ $country }}')">
                                <strong>{{ $country }}</strong>
                                <div id="cityDropdown-{{ $country }}" class="city-dropdown" style="display: none;">
                                    @foreach ($groupedLocations as $location)
                                        <a href="{{ url('/historical-weather?location=' . urlencode($location->location)) }}"
                                            class="{{ request('location') == $location->location ? 'active' : '' }}">
                                            {{ $location->location }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="flex gap-4">
                    <a href="{{ url('/dashboard') }}"
                        class="text-base font-medium text-gray-800 dark:text-gray-200 hover:underline">
                        Dashboard
                    </a>
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

                            <!-- Authentication -->
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
        <br>
        <div id="container">
            <div class="card">
                <h2>Temperature</h2>
                <div class="linechart1">
                    <canvas id="temp"></canvas>
                </div>
            </div>
            <!-- Humidity Chart Section -->
            <div class="card">
                <h2>Humidity</h2>
                <div class="linechart1">
                    <canvas id="humidity"></canvas>
                </div>
            </div>
            <!-- Wind Speed Chart Section -->
            <div class="card">
                <h2>Wind Speed</h2>
                <div class="linechart1">
                    <canvas id="wind"></canvas>
                </div>
            </div>
        </div>
        <br>
        <br>
        <script>
            // Prepare data for the charts
            const labels = @json(
                $tempData->pluck('created_at')->map(function ($date) {
                    return \Carbon\Carbon::parse($date)->format('Y-m-d H:i');
                })
            );

            const temperatures = @json($tempData->pluck('temperature')->toArray());
            const feelsLike = @json($tempData->pluck('feels_like')->toArray());
            const tempmax = @json($tempData->pluck('temp_max')->toArray());
            const tempmin = @json($tempData->pluck('temp_min')->toArray());
            const humidity = @json($atmosData->pluck('humidity')->toArray());
            const windSpeed = @json($atmosData->pluck('wind_speed')->toArray());
            const pressure = @json($atmosData->pluck('pressure')->toArray());
            const weather = @json($atmosData->pluck('description')->toArray());
            const windDirection = @json($atmosData->pluck('wind_direction')->toArray());
            const gust = @json($atmosData->pluck('wind_gust')->toArray());
            const locationName = "{{ request('location', '---') }}";
        </script>

        <script src="{{ asset('weather.js') }}"></script>
        <script src="{{ asset('forecast.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.5.1/chart.min.js"></script>
        </div>

        </body>
    @endauth

</x-app-layout>
