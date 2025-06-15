```blade
    @extends('layouts.app')
    @section('title', 'Welcome')
    @section('content')
    <div class="content-header">
        <div class="container-fluid">
            <h1 class="m-0">Selamat Datang di Starter Kit</h1>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <p>Aplikasi starter kit untuk UAS Pemrograman Web Lanjut.</p>
                    <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-secondary">Register</a>
                </div>
            </div>
        </div>
    </section>
    @endsection