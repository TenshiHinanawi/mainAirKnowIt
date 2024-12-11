<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Location</title>
</head>
<body>
    <h1>Select a Location to See the Air Quality Forecast</h1>

    <ul>
        @foreach ($locations as $location)
            <li>
                <a href="{{ route('forecast', ['locationId' => $location->id]) }}">
                    {{ $location->location }} ({{ $location->country }})
                </a>
            </li>
        @endforeach
    </ul>
</body>
</html>
