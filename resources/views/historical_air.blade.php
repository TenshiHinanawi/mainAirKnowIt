<x-app-layout>
    @auth

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <link rel="stylesheet" href="{{ asset('historical.css') }}">
            <title>Air Quality Data</title>
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
                                        <a href="{{ url('/historical-air?location=' . urlencode($location->location)) }}"
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

        <body>
            <div id="container">
                <div class="card2">
                    <h1 style="font-weight: bold;">Particulate Matter</h1>
                    <div class="linechart1">
                        <p style="text-align: justify">PM 2.5 and PM 10 (Particulate Matter) refer to airborne particles
                            with
                            diameters of 2.5 micrometers and 10 micrometers or smaller, respectively. These particles are
                            small
                            enough to be inhaled and can penetrate deep into the lungs, causing respiratory and
                            cardiovascular
                            issues. PM 2.5, being finer, can reach deeper into the lungs and even enter the bloodstream,
                            while
                            PM 10 is generally trapped in the upper respiratory system but can still cause irritation and
                            health
                            problems. Both types of particulate matter are primarily produced by vehicle emissions,
                            industrial
                            processes, and the burning of fossil fuels. Long-term exposure to elevated levels of PM 2.5 and
                            PM
                            10 is linked to an increased risk of lung disease, heart disease, and stroke.</p>
                    </div>
                    <br>
                    <div class="linechart1">
                        <h3 style="font-weight: bold;">PM 2.5</h3>
                        <p>Current PM 2.5 Level: {{ $airQualityData->pluck('pm2_5')->last() }}µg/m³</p>
                        <p>Unhealthy Levels: 35.4+ µg/m³</p>
                        <canvas id="pm2_5"></canvas>
                    </div>
                    <br>
                    <br>
                    <br>
                    <div class="linechart1">
                        <h3 style="font-weight: bold;">PM 10</h3>
                        <p>Current PM 10 Level: {{ $airQualityData->pluck('pm10')->last() }}µg/m³</p>
                        <p>Unhealthy Levels: 154+ µg/m³</p>
                        <canvas id="pm10"></canvas>
                    </div>
                </div>
                <br>
                <div class="card2">
                    <h1 style="font-weight: bold;">Ozone</h1>
                    <div class="linechart1">
                        <p style="text-align: justify">Ozone (O₃) is created at ground level through a chemical reaction
                            between
                            nitrogen oxides (NO) and volatile organic compounds (VOCs) in the presence of sunlight. This
                            process,
                            known as photochemical smog, leads to the formation of harmful ozone, especially in urban areas.
                            Ground-level ozone can cause
                            respiratory issues, worsen asthma, and damage lung tissue. It also harms plants and exacerbates
                            existing
                            health conditions, making it a significant environmental and public health concern.
                        </p>
                    </div>
                    <br>
                    <div class="linechart1">
                        <h3 style="font-weight: bold;">Ozone</h3>
                        <p>Current Ozone Level: {{ $airQualityData->pluck('o3')->last() }}µg/m³</p>
                        <p>Unhealthy Levels: 140+ µg/m³</p>
                        <canvas id="ozone"></canvas>
                    </div>
                </div>
                <br>
                <div class="card2">
                    <h1 style="font-weight: bold;">Nitrogen Oxides</h1>
                    <div class="linechart1">
                        <p style="text-align: justify">Nitrogen compounds, like nitrogen dioxide (NO₂) and nitric oxide
                            (NO),
                            are
                            harmful air pollutants primarily produced by vehicle emissions and industrial activities. At
                            ground
                            level, they can irritate the respiratory system, exacerbate asthma, and contribute to the
                            formation
                            of
                            smog and acid rain, which can damage ecosystems and human health.</p>
                    </div>
                    <br>
                    <div class="linechart1">
                        <h3 style="font-weight: bold;">Nitrogen Monoxide</h3>
                        <p>Current Nitrogen Monoxide Level: {{ $airQualityData->pluck('no')->last() }}µg/m³</p>
                        <p>Unhealthy Levels: 100+ µg/m³</p>
                        <canvas id="nitro-mono"></canvas>
                    </div>
                    <br>
                    <div class="linechart1">
                        <h3 style="font-weight: bold;">Nitrogen Dioxide</h3>
                        <p>Current Nitrogen Dioxide Level: {{ $airQualityData->pluck('no2')->last() }}µg/m³</p>
                        <p>Unhealthy Levels: 150+ µg/m³</p>
                        <canvas id="nitro-dio"></canvas>
                    </div>
                </div>
                <br>
                <div class="card2">
                    <h1 style="font-weight: bold;">Carbon Monoxide</h1>
                    <div class="linechart1">
                        <p style="text-align: justify">Carbon monoxide (CO) is a colorless, odorless gas produced by the
                            incomplete combustion of fossil fuels. It can interfere with the body's ability to transport
                            oxygen,
                            leading to symptoms like dizziness, confusion, and in severe cases, death. High CO levels are
                            particularly dangerous in enclosed spaces and contribute to air pollution.</p>
                    </div>
                    <br>
                    <div class="linechart1">
                        <h3 style="font-weight: bold;">Carbon Monoxide</h3>
                        <p>Current Carbon Monoxide Level: {{ $airQualityData->pluck('co')->last() }}µg/m³</p>
                        <p>Unhealthy Levels: 12400+ µg/m³</p>
                        <canvas id="carbon"></canvas>
                    </div>
                </div>
                <br>
                <div class="card2">
                    <h1 style="font-weight: bold;">Sulfur Dioxide</h1>
                    <div class="linechart1">
                        <p style="text-align: justify">Sulfur dioxide (SO₂) is a colorless gas with a pungent odor,
                            primarily
                            produced by the burning of fossil fuels containing sulfur, such as coal and oil. It can irritate
                            the
                            respiratory system, leading to coughing, shortness of breath, and exacerbating asthma or other
                            lung
                            diseases. SO₂ can also contribute to the formation of acid rain, which can damage ecosystems and
                            water
                            bodies.</p>
                    </div>
                    <br>
                    <div class="linechart1">
                        <h3 style="font-weight: bold;">Sulfur Dioxide</h3>
                        <p>Current Sulfur Dioxide Level: {{ $airQualityData->pluck('so2')->last() }}µg/m³</p>
                        <p>Unhealthy Levels: 250+ µg/m³</p>
                        <canvas id="sulfur"></canvas>
                    </div>
                </div>
                <br>
                <div class="card2">
                    <h1 style="font-weight: bold;">Ammonia</h1>
                    <div class="linechart1">
                        <p style="text-align: justify">Ammonia (NH₃) is a colorless gas with a strong odor, primarily
                            emitted
                            from
                            agricultural activities, waste treatment plants, and industrial processes. While ammonia itself
                            is not
                            directly harmful in low concentrations, it can combine with other pollutants in the atmosphere
                            to
                            form
                            fine particulate matter (PM2.5), which can have serious health impacts when inhaled.</p>
                    </div>
                    <br>
                    <div class="linechart1">
                        <h3 style="font-weight: bold;">Ammonia</h3>
                        <p>Current Ammonia Level: {{ $airQualityData->pluck('nh3')->last() }}µg/m³</p>
                        <p>Unhealthy Levels: 150+ µg/m³</p>
                        <canvas id="ammonia"></canvas>
                    </div>
                </div>
                <br>
            </div>
        </body>
        <script>
            const labels = @json(
                $airQualityData->pluck('created_at')->map(function ($date) {
                    return \Carbon\Carbon::parse($date)->format('Y-m-d H:i');
                }));
            var pm2_5 = @json($airQualityData->pluck('pm2_5')->toArray());
            var pm10 = @json($airQualityData->pluck('pm10')->toArray());
            var o3 = @json($airQualityData->pluck('o3')->toArray());
            var no = @json($airQualityData->pluck('no')->toArray());
            var no2 = @json($airQualityData->pluck('no2')->toArray());
            var co = @json($airQualityData->pluck('co')->toArray());
            var so2 = @json($airQualityData->pluck('so2')->toArray());
            var nh3 = @json($airQualityData->pluck('nh3')->toArray());
            const locationName = "{{ request('location', '---') }}";
        </script>

        <script src="{{ asset('airquality.js') }}"></script>
        <script src="{{ asset('forecast.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.5.1/chart.min.js"></script>
    @endauth
</x-app-layout>
