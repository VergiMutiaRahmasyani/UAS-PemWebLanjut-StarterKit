```blade
@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <h1 class="m-0">Login</h1>
    </div>
</div>
<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Sign In to Your Account</h3>
            </div>
            <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="form-group">
                        <label for="email">{{ __('Email Address') }}</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="password">{{ __('Password') }}</label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">
                                {{ __('Remember Me') }}
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Login') }}
                        </button>
                        @if (Route::has('password.request'))
                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                {{ __('Forgot Your Password?') }}
                            </a>
                        @endif
                    </div>
                </form>
                <!-- Social Login Buttons -->
                <hr>
                <div class="form-group">
                    <p class="text-center">Or sign in with:</p>
                    <div class="d-flex justify-content-center">
                        <a href="{{ route('socialite.redirect', 'github') }}" class="btn btn-dark mr-2">
                            <i class="fab fa-github"></i> GitHub
                        </a>
                        <a href="{{ route('socialite.redirect', 'google') }}" class="btn btn-danger mr-2">
                            <i class="fab fa-google"></i> Google
                        </a>
                        <a href="{{ route('socialite.redirect', 'microsoft') }}" class="btn btn-primary">
                            <i class="fab fa-microsoft"></i> Microsoft
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
```

### Penjelasan Perubahan
1. **Layout AdminLTE**:
   - Kode memperluas `layouts.app` yang sudah disesuaikan dengan AdminLTE (dari panduan sebelumnya).
   - Menggunakan struktur AdminLTE seperti `content-header`, `container-fluid`, dan `card` untuk tampilan yang konsisten.
   - Mengganti kelas Bootstrap default (seperti `col-md-8`) dengan kelas AdminLTE seperti `form-group` dan `btn`.

2. **Fitur Autentikasi**:
   - **Sign In**: Form tetap menggunakan `POST` ke rute `login` (disediakan oleh `laravel/ui`).
   - **Remember Me**: Checkbox `remember` dipertahankan seperti kode awal.
   - **Lupa Password**: Link ke rute `password.request` dipertahankan.
   - **Validasi Error**: Direktif `@error` untuk menampilkan pesan error pada email dan password.

3. **Login Sosial**:
   - Menambahkan tombol untuk login dengan GitHub, Google, dan Microsoft menggunakan rute `socialite.redirect`.
   - Ikon FontAwesome (`fab fa-github`, `fab fa-google`, `fab fa-microsoft`) digunakan untuk styling (pastikan FontAwesome sudah disertakan di `layouts/app.blade.php`).
   - Tombol diberi gaya dengan kelas AdminLTE/Bootstrap seperti `btn btn-dark`, `btn btn-danger`, dll.

4. **Notifikasi Status**:
   - Menambahkan pengecekan `session('status')` untuk menampilkan pesan sukses (misalnya, setelah reset password).

---

### Prasyarat
Agar kode di atas berfungsi, pastikan:
1. **Layout AdminLTE**:
   - File `resources/views/layouts/app.blade.php` sudah ada dan menggunakan struktur AdminLTE (seperti di panduan sebelumnya):
     ```blade
     <!DOCTYPE html>
     <html lang="en">
     <head>
         <meta charset="UTF-8">
         <meta name="viewport" content="width=device-width, initial-scale=1.0">
         <title>Starter Kit - @yield('title')</title>
         <link rel="stylesheet" href="{{ asset('assets/adminlte/css/adminlte.min.css') }}">
         <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
     </head>
     <body class="hold-transition sidebar-mini">
         <div class="wrapper">
             @include('layouts.navbar