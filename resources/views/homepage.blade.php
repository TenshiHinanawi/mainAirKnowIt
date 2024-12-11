<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AirKnowIt</title>
    <link rel="stylesheet" href="{{ asset('homepage.css') }}">
</head>

<body>
    <header>
        <h1>Welcome to AirKnowIt!</h1>
        <p>Empowering You with Real-Time Air Quality Insights for a Healthier Tomorrow</p>
    </header>

    <div class="container">
        @auth<h2>Hello, {{ Auth::user()->name }}</h2>
        @else
        <h2>Get Started</h2>
        @endauth
        <div class="auth-buttons">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}">Go to Home</a>
                @else
                    <a href="{{ route('login') }}">Log In</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}">Sign Up</a>
                    @endif
                @endauth
            @endif
        </div>
    </div>
</body>

</html>
