<style>
    body {
        background: linear-gradient(to bottom, #4fc3f7, #ffffff);
        font-family: 'Arial', sans-serif;
        color: #333;
        margin: 0;
        padding: 0;
    }

    .register-container {
        max-width: 400px;
        margin: 60px auto;
        padding: 20px;
        background-color: #ffffff;
        border-radius: 12px;
        box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    h2 {
        color: #0288d1;
        font-size: 2em;
        margin-bottom: 20px;
    }

    .form-group {
        margin-bottom: 20px;
        text-align: left;
    }

    label {
        display: block;
        font-weight: bold;
        margin-bottom: 6px;
        color: #4caf50;
    }

    input {
        width: 100%;
        padding: 12px;
        border: 1px solid #ccc;
        border-radius: 8px;
        box-shadow: inset 0 4px 6px rgba(0, 0, 0, 0.1);
        font-size: 1em;
    }

    input:focus {
        outline: none;
        border-color: #0288d1;
        box-shadow: 0 0 8px rgba(2, 136, 209, 0.5);
    }

    .form-footer {
        margin-top: 20px;
        text-align: center;
    }

    .form-footer a {
        color: #0288d1;
        text-decoration: none;
        font-size: 0.9em;
    }

    .form-footer a:hover {
        text-decoration: underline;
    }

    button {
        width: 100%;
        background-color: #0288d1;
        color: white;
        border: none;
        padding: 14px;
        font-size: 1.1em;
        font-weight: bold;
        border-radius: 8px;
        cursor: pointer;
        transition: background-color 0.3s ease;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    button:hover {
        background-color: #01579b;
    }

    button:focus {
        outline: 3px solid #81d4fa;
        background-color: #0288d1;
    }

    button:active {
        background-color: #01579b;
    }

    @media (max-width: 400px) {
        .register-container {
            margin: 20px;
            padding: 15px;
        }

        h2 {
            font-size: 1.7em;
        }
    }
</style>

<div class="register-container">
    <h2>Forgot Password</h2>

    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __('Let us know your email so we can send a reset link to your email') }}
    </div>
    <br>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div class="form-group">
            <label for="email">{{ __('Email') }}</label>
            <input id="email" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Submit Button -->
        <button type="submit">
            {{ __('Email Password Reset Link') }}
        </button>

        <div class="form-footer">
            <a href="{{ route('login') }}">{{ __('Back to Login') }}</a>
        </div>
    </form>
</div>
