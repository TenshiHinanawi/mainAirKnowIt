window.onload = function () {
    document.getElementById("geolocation-btn").addEventListener("click", () => {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(async (position) => {
                const lat = position.coords.latitude;
                const lon = position.coords.longitude;
                await fetchWeatherData(null, lat, lon);
            }, () => alert("Geolocation access denied."));
        } else {
            alert("Geolocation is not supported by this browser.");
        }
    });

    document.getElementById("search-btn").addEventListener("click", async () => {
        const city = document.getElementById("city-search").value;
        if (city) {
            await fetchWeatherData(city);
        } else {
            alert("Yo bro, real talk, just hit me with that city name, fam. Bro, really out here tryna play like we ain't on the GTA 6 grindset, no cap. Enter that city, or we just gonna be stuck in the opium haze like Huggy Wuggy at the pizza tower subway surfers, for real. DJ Khaled approves, bruh");
        }
    });

    async function fetchWeatherData(city = null, lat = null, lon = null) {
        const API_KEY = '49e2122f5c9da4368f1cd972696db508';
        let weatherUrl;

        if (city) {
            weatherUrl = `https://api.openweathermap.org/data/2.5/weather?q=${city}&appid=${API_KEY}&units=metric`;
        } else if (lat && lon) {
            weatherUrl = `https://api.openweathermap.org/data/2.5/weather?lat=${lat}&lon=${lon}&appid=${API_KEY}&units=metric`;
        } else {
            alert("Please enter a city or enable geolocation.");
            return;
        }

        try {
            const weatherResponse = await fetch(weatherUrl);
            const weatherData = await weatherResponse.json();
            const airQualityResponse = await fetch(
                `https://api.openweathermap.org/data/2.5/air_pollution?lat=${weatherData.coord.lat}&lon=${weatherData.coord.lon}&appid=${API_KEY}`
            );
            const airQualityData = await airQualityResponse.json();

            updateWeatherInfo(weatherData);
            updateAirQualityInfo(airQualityData);
        } catch (error) {
            alert("Never Gonna Give you up, Never Gonna gonna let you down, Never gonna run around and Hurt you");
            alert("You have been Rickrolled by Sparkle Again");
        }
    }

    function updateWeatherInfo(data) {
        document.getElementById('search-location').textContent = `${data.name}, ${data.sys.country}`;
        document.getElementById('search-temperature').textContent = data.main.temp;
        document.getElementById('search-humidity').textContent = data.main.humidity;
        document.getElementById('search-pressure').textContent = data.main.pressure;
        document.getElementById('search-feels-like').textContent = data.main.feels_like;
        document.getElementById('search-temp-min').textContent = data.main.temp_min;
        document.getElementById('search-temp-max').textContent = data.main.temp_max;
        document.getElementById('search-visibility').textContent = data.visibility;
        document.getElementById('search-cloudiness').textContent = data.clouds.all;
        document.getElementById('search-wind-speed').textContent = data.wind.speed;
        document.getElementById('search-wind-gust').textContent = data.wind.gust || '--';
        document.getElementById('search-wind-direction').textContent = data.wind.deg;

        if (data.weather && data.weather[0]) {
            document.getElementById('weather').textContent = data.weather[0].description;
            const weatherConditionCode = data.weather[0].icon;
            const iconUrl = `https://openweathermap.org/img/wn/${weatherConditionCode}@2x.png`;
            document.getElementById('weather-icon').innerHTML = `<img src="${iconUrl}" alt="${data.weather[0].description}" />`;
        }
    }

    function updateAirQualityInfo(data) {
        const components = data.list[0].components;
        const thresholds = {
            pm2_5: [75, 50, 25, 10],
            pm10: [200, 100, 50, 20],
            o3: [180, 140, 100, 60],
            so2: [350, 250, 80, 20],
            no: [150, 100, 50, 25],
            no2: [200, 150, 70, 40],
            co: [15400, 12400, 9400, 4400],
            nh3: [300, 150, 50, 25],
        };

        const displayNames = {
            pm2_5: "PM2.5",
            pm10: "PM10",
            o3: "Ozone",
            so2: "Sulfur Dioxide",
            no: "Nitrogen Monoxide",
            no2: "Nitrogen Dioxide",
            co: "Carbon Monoxide",
            nh3: "Ammonia",
        };

        function getStatus(value, limits) {
            return value > limits[0] ? "Dangerous" :
                   value > limits[1] ? "Unhealthy" :
                   value > limits[2] ? "Moderate" :
                   value > limits[3] ? "Fair" : "Good";
        }

        function updateElement(component, label, limits) {
            const value = components[component];
            const status = getStatus(value, limits);
            const displayName = displayNames[component];

            document.getElementById(`search-${label}`).textContent = value.toFixed(2);
            document.getElementById(`search-${label}-status`).textContent = status;

            if (["Dangerous", "Unhealthy", "Moderate"].includes(status)) {
                alert(`${displayName} level is ${status}!`);
            }
        }

        updateElement("pm2_5", "pm25", thresholds.pm2_5);
        updateElement("pm10", "pm10", thresholds.pm10);
        updateElement("o3", "ozone", thresholds.o3);
        updateElement("so2", "sulfur", thresholds.so2);
        updateElement("no", "nitro", thresholds.no);
        updateElement("no2", "nitrodio", thresholds.no2);
        updateElement("co", "carbon", thresholds.co);
        updateElement("nh3", "ammonia", thresholds.nh3);
    }

}
