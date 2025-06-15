<!DOCTYPE html>
     <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
     <head>
         <meta charset="utf-8">
         <meta name="viewport" content="width=device-width, initial-scale=1">
         <meta name="csrf-token" content="{{ csrf_token() }}">
         <title>@yield('title', 'Starter Kit UAS') - {{ config('app.name', 'Laravel') }}</title>
         <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
         <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
     </head>
     <body class="hold-transition sidebar-mini layout-fixed">
         <div class="wrapper">
             <!-- Navbar -->
             <nav class="main-header navbar navbar-expand navbar-white navbar-light">
                 <ul class="navbar-nav">
                     <li class="nav-item">
                         <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                     </li>
                     <li class="nav-item d-none d-sm-inline-block">
                         <a href="{{ route('berita.publik') }}" class="nav-link">Home</a>
                     </li>
                 </ul>
                 <ul class="navbar-nav ml-auto">
                     <li class="nav-item">
                         <a class="nav-link" href="{{ route('profile.edit') }}">
                             <i class="fas fa-user"></i> Profile
                         </a>
                     </li>
                     <li class="nav-item">
                         <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                             <i class="fas fa-sign-out-alt"></i> Logout
                         </a>
                         <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                             @csrf
                         </form>
                     </li>
                 </ul>
             </nav>

             <!-- Main Sidebar Container -->
             <aside class="main-sidebar sidebar-dark-primary elevation-4">
                 <a href="{{ route('dashboard') }}" class="brand-link">
                     <span class="brand-text font-weight-light">Starter Kit UAS</span>
                 </a>
                 <div class="sidebar">
                     <nav class="mt-2">
                         <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                             <li class="nav-item">
                                 <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                                     <i class="nav-icon fas fa-tachometer-alt"></i>
                                     <p>Dashboard</p>
                                 </a>
                             </li>
                             <li class="nav-item">
                                 <a href="{{ route('berita.index') }}" class="nav-link {{ request()->routeIs('berita.*') ? 'active' : '' }}">
                                     <i class="nav-icon fas fa-newspaper"></i>
                                     <p>Berita</p>
                                 </a>
                             </li>
                             @if (Auth::user()->hasRole('editor'))
                             <li class="nav-item">
                                 <a href="#" class="nav-link">
                                     <i class="nav-icon fas fa-check-circle"></i>
                                     <p>Approval Berita</p>
                                 </a>
                             </li>
                             @endif
                         </ul>
                     </nav>
                 </div>
             </aside>

             <!-- Content Wrapper -->
             <div class="content-wrapper">
                 @yield('content')
             </div>

             <!-- Footer -->
             <footer class="main-footer">
                 <strong>Copyright © 2025 <a href="#">Starter Kit UAS</a>.</strong>
                 All rights reserved.
                 <div class="float-right d-none d-sm-inline-block">
                     <b>Version</b> 1.0.0
                 </div>
             </footer>
         </div>
         <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
     </body>
     </html>