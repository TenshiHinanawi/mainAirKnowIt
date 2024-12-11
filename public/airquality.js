// PM 2.5 Chart
const lineCtx1 = document.getElementById('pm2_5').getContext('2d');
const lineChart1 = new Chart(lineCtx1, {
    type: 'line',
    data: {
        labels: labels, // This already contains the 'created_at' data
        datasets: [{
            label: 'PM 2.5',
            data: pm2_5,
            borderColor: 'rgb(244, 67, 54)',
            backgroundColor: 'rgba(244, 67, 54, 0.2)',
            fill: false,
            tension: 0.1,
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            title: {
                display: true,
                text: `Historical PM 2.5 levels of ${locationName}`,
                font: { size: 18 }
            }
        },
        scales: {
            x: { title: { display: true, text: 'Date/Time' } },
            y: { title: { display: true, text: 'PM 2.5 (µg/m³)' } }
        }
    }
});

// PM 10 Chart
const lineCtx2 = document.getElementById('pm10').getContext('2d');
const lineChart2 = new Chart(lineCtx2, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: 'PM 10',
            data: pm10,
            borderColor: 'rgb(255, 99, 132)',
            backgroundColor: 'rgba(255, 99, 132, 0.6)',
            fill: false,
            tension: 0.1,
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            title: {
                display: true,
                text: `Historical PM 10 levels of ${locationName}`,
                font: { size: 18 }
            }
        },
        scales: {
            x: { title: { display: true, text: 'Date/Time' } },
            y: { title: { display: true, text: 'PM 10 (µg/m³)' } }
        }
    }
});

// Ozone Chart
const lineCtx3 = document.getElementById('ozone').getContext('2d');
const lineChart3 = new Chart(lineCtx3, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: 'Ozone',
            data: o3,
            borderColor: 'rgb(0, 255, 255)',
            backgroundColor: 'rgba(0, 255, 255, 0.2)',
            fill: false,
            tension: 0.1,
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            title: {
                display: true,
                text: `Historical Ozone levels of ${locationName}`,
                font: { size: 18 }
            }
        },
        scales: {
            x: { title: { display: true, text: 'Date/Time' } },
            y: { title: { display: true, text: 'Ozone (µg/m³)' } }
        }
    }
});

// Nitrogen Monoxide Chart
const lineCtx4 = document.getElementById('nitro-mono').getContext('2d');
const lineChart4 = new Chart(lineCtx4, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: 'Nitrogen Monoxide',
            data: no,
            borderColor: 'rgb(54, 162, 235)',
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            fill: false,
            tension: 0.1,
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            title: {
                display: true,
                text: `Historical Nitrogen Monoxide levels of ${locationName}`,
                font: { size: 18 }
            }
        },
        scales: {
            x: { title: { display: true, text: 'Date/Time' } },
            y: { title: { display: true, text: 'Nitrogen Monoxide (µg/m³)' } }
        }
    }
});

// Nitrogen Dioxide Chart
const lineCtx5 = document.getElementById('nitro-dio').getContext('2d');
const lineChart5 = new Chart(lineCtx5, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: 'Nitrogen Dioxide',
            data: no2,
            borderColor: 'rgb(255, 87, 34)',
            backgroundColor: 'rgba(255, 87, 34, 0.2)',
            fill: false,
            tension: 0.1,
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            title: {
                display: true,
                text: `Historical Nitrogen Dioxide levels of ${locationName}`,
                font: { size: 18 }
            }
        },
        scales: {
            x: { title: { display: true, text: 'Date/Time' } },
            y: { title: { display: true, text: 'Nitrogen Dioxide (µg/m³)' } }
        }
    }
});

// Carbon Monoxide Chart
const lineCtx6 = document.getElementById('carbon').getContext('2d');
const lineChart6 = new Chart(lineCtx6, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: 'Carbon Monoxide',
            data: co,
            borderColor: 'rgb(54, 69, 79)',
            backgroundColor: 'rgba(54, 69, 79, 0.2)',
            fill: false,
            tension: 0.1,
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            title: {
                display: true,
                text: `Historical Carbon Monoxide levels of ${locationName}`,
                font: { size: 18 }
            }
        },
        scales: {
            x: { title: { display: true, text: 'Date/Time' } },
            y: { title: { display: true, text: 'Carbon Monoxide (µg/m³)' } }
        }
    }
});

// Sulfur Dioxide Chart
const lineCtx7 = document.getElementById('sulfur').getContext('2d');
const lineChart7 = new Chart(lineCtx7, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: 'Sulfur Dioxide',
            data: so2,
            borderColor: 'rgb(153, 102, 255)',
            backgroundColor: 'rgba(153, 102, 255, 0.2)',
            fill: false,
            tension: 0.1,
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            title: {
                display: true,
                text: `Historical Sulfur Dioxide levels of ${locationName}`,
                font: { size: 18 }
            }
        },
        scales: {
            x: { title: { display: true, text: 'Date/Time' } },
            y: { title: { display: true, text: 'Sulfur Dioxide (µg/m³)' } }
        }
    }
});

// Ammonia Chart
const lineCtx8 = document.getElementById('ammonia').getContext('2d');
const lineChart8 = new Chart(lineCtx8, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: 'Ammonia',
            data: nh3,
            borderColor: 'rgb(139, 195, 74)',
            backgroundColor: 'rgba(139, 195, 74, 0.2)',
            fill: false,
            tension: 0.1,
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            title: {
                display: true,
                text: `Historical Ammonia levels of ${locationName}`,
                font: { size: 18 }
            }
        },
        scales: {
            x: { title: { display: true, text: 'Date/Time' } },
            y: { title: { display: true, text: 'Ammonia (µg/m³)' } }
        }
    }
});
