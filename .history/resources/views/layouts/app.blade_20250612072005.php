<!DOCTYPE html>
     <html lang="en">
     <head>
         <meta charset="UTF-8">
         <meta name="viewport" content="width=device-width, initial-scale=1.0">
         <title>Starter Kit - @yield('title')</title>
         <!-- Vite compiled CSS (Bootstrap) -->
         @vite('resources/css/app.css')
         <!-- AdminLTE CSS -->
         <link rel="stylesheet" href="{{ asset('assets/adminlte/css/adminlte.min.css') }}">
         <!-- FontAwesome untuk ikon -->
         <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
         <!-- Instrument Sans font -->
         <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans&display=swap" rel="stylesheet">
     </head>
     <body class="hold-transition sidebar-mini">
         <div class="wrapper">
             @include('layouts.navbar')
             @include('layouts.sidebar')
             <div class="content-wrapper">
                 <section class="content">
                     @yield('content')
                 </section>
             </div>
             @include('layouts.footer')
         </div>
         <!-- Vite compiled JS -->
         @vite('resources/js/app.js')
         <!-- AdminLTE JS -->
         <script src="{{ asset('assets/adminlte/js/adminlte.min.js') }}"></script>
     </body>
     </html>