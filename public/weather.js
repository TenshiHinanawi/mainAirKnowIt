// Create Temperature Chart
const ctxLine = document.getElementById('temp').getContext('2d');
const lineChart = new Chart(ctxLine, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [
            {
                label: 'Temperature (°C)',
                data: temperatures,
                borderColor: 'rgb(54, 162, 235)',
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                fill: false,
                tension: 0.1,
                borderWidth: 1
            },
            {
                label: 'Feels Like (°C)',
                data: feelsLike,
                borderColor: 'rgb(255, 159, 64)',
                backgroundColor: 'rgba(255, 159, 64, 0.2)',
                fill: false,
                tension: 0.1,
                borderWidth: 1
            },
            {
                label: 'Min (°C)',
                data: tempmin,
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                fill: false,
                tension: 0.1,
                borderWidth: 1
            },
            {
                label: 'Max (°C)',
                data: tempmax,
                borderColor: 'rgb(153, 102, 255)',
                backgroundColor: 'rgba(153, 102, 255, 0.2)',
                fill: false,
                tension: 0.1,
                borderWidth: 1
            }
        ]
    },
    options: {
        responsive: true,
        plugins: {
            title: {
                display: true,
                text: `Historical Temperature of ${locationName}`,
                font: {
                    size: 18
                }
            }
        },
        scales: {
            x: {
                title: {
                    display: true,
                    text: 'Date/Time'
                }
            },
            y: {
                title: {
                    display: true,
                    text: 'Temperature (°C)'
                }
            }
        }
    }
});

// Create Humidity Chart
const ctxBar = document.getElementById('humidity').getContext('2d');
const barChart = new Chart(ctxBar, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: 'Humidity (%)',
            data: humidity,
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            borderColor: 'rgb(54, 162, 235)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            title: {
                display: true,
                text: `Historical Humidity Levels at ${locationName}`,
                font: {
                    size: 18
                }
            }
        },
        scales: {
            x: {
                title: {
                    display: true,
                    text: 'Date/Time'
                }
            },
            y: {
                title: {
                    display: true,
                    text: 'Humidity (%)'
                }
            }
        }
    }
});

// Create Wind Speed & Gust Chart
const ctxBar1 = document.getElementById('wind').getContext('2d');
const wind = new Chart(ctxBar1, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: 'Wind Speed (m/s)',
            data: windSpeed,
            backgroundColor: 'rgba(19, 100, 255, 0.2)',
            borderColor: 'rgb(19, 100, 255)',
            borderWidth: 1
        },
        {
            label: 'Wind Gust (m/s)',
            data: gust,
            backgroundColor: 'rgba(135, 206, 200, 0.2)',
            borderColor: 'rgb(135, 206, 200)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            title: {
                display: true,
                text: `Historical Wind Speed at ${locationName}`,
                font: {
                    size: 18
                }
            }
        },
        scales: {
            x: {
                title: {
                    display: true,
                    text: 'Date/Time'
                }
            },
            y: {
                title: {
                    display: true,
                    text: 'Wind Speed (m/s)'
                }
            }
        }
    }
});

// Display weather description
const weatherElement = document.getElementById('weather-description');
if (weather && weather.length > 0) {
    let weatherCondition = weather[0];  // Get the first weather description
    // Display weather condition
    weatherElement.innerText = `Weather: ${weatherCondition.charAt(0).toUpperCase() + weatherCondition.slice(1)}`;
} else {
    weatherElement.innerText = 'Weather data not available';
}
