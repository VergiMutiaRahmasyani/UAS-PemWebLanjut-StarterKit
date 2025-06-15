<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Starter Kit UAS</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f8f9fa;
        }
        .welcome-container {
            text-align: center;
            padding: 2rem;
        }
        .welcome-title {
            font-size: 2.5rem;
            color: #2d3748;
            margin-bottom: 1rem;
        }
        .welcome-message {
            font-size: 1.25rem;
            color: #4a5568;
            margin-bottom: 2rem;
        }
        .btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            border-radius: 0.375rem;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s;
        }
        .btn-primary {
            background-color: #4f46e5;
            color: white;
        }
        .btn-primary:hover {
            background-color: #4338ca;
        }
    </style>
</head>
<body>
    <div class="welcome-container">
        <div>
            <h1 class="welcome-title">Selamat Datang di Starter Kit UAS</h1>
            <p class="welcome-message">
                Aplikasi Manajemen Berita dengan Laravel
            </p>
            <div>
                @guest
                    <a href="{{ route('login') }}" class="btn btn-primary">Masuk</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn" style="margin-left: 1rem;">Daftar</a>
                    @endif
                @else
                    <a href="{{ route('dashboard') }}" class="btn btn-primary">Dashboard</a>
                @endguest
            </div>
        </div>
    </div>
</body>
</html>