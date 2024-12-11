function populateCities(country) {
    const cityDropdown = document.getElementById(`cityDropdown-${country}`);
    cityDropdown.style.display = 'block'; // Show city dropdown
}

function hideCities(country) {
    const cityDropdown = document.getElementById(`cityDropdown-${country}`);
    cityDropdown.style.display = 'none'; // Hide city dropdown
}
