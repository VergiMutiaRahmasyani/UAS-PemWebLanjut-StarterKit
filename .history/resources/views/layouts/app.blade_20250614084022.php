<!DOCTYPE html>
<html lang="{{ config('app.locale', 'id') }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Starter Kit UAS - @yield('title')</title>
    <!-- Vite compiled CSS -->
    @vite(['resources/css/app.css'])
    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">
    <!-- FontAwesome untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-xxx" crossorigin="anonymous">
    <!-- Instrument Sans font -->
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans&display=swap" rel="stylesheet">
</head>
<body class="{{ Auth::check() ? 'hold-transition sidebar-mini layout-fixed' : '' }}">
    @if (Auth::check())
        <div class="wrapper">
            <!-- Navbar -->
            @include('layouts.navbar')
            <!-- Main Sidebar Container -->
            @include('layouts.sidebar')
            <!-- Content Wrapper -->
            <div class="content-wrapper">
                <!-- Content Header -->
                <div class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0">@yield('header', 'Dashboard')</h1>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Main Content -->
                <section class="content">
                    @yield('content')
                </section>
            </div>
            <!-- Footer -->
            @include('layouts.footer')
        </div>
    @else
        <!-- Konten untuk halaman login/register -->
        <div class="container d-flex justify-content-center align-items-center min-vh-100">
            @yield('content')
        </div>
    @endif

    <!-- Vite compiled JS -->
    @vite(['resources/js/app.js'])
    <!-- jQuery untuk AdminLTE -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- AdminLTE JS -->
    <script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>
</body>
</html>