<!DOCTYPE html>
     <html lang="en">
     <head>
         <meta charset="UTF-8">
         <meta name="viewport" content="width=device-width, initial-scale=1.0">
         <meta http-equiv="X-UA-Compatible" content="ie=edge">
         <title>Login - Starter Kit UAS</title>
         <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
         <link rel="stylesheet" href="{{ asset('assets/AdminLTE-4.0.0-beta3/dist/css/adminlte.min.css') }}">
     </head>
     <body class="bg-light">
         <div class="container d-flex justify-content-center align-items-center min-vh-100">
             <div class="card p-4" style="width: 100%; max-width: 400px;">
                 <h2 class="text-center mb-4">Login</h2>
                 @if ($errors->any())
                     <div class="alert alert-danger">
                         <ul>
                             @foreach ($errors->all() as $error)
                                 <li>{{ $error }}</li>
                             @endforeach
                         </ul>
                     </div>
                 @endif
                 @if (session('error'))
                     <div class="alert alert-danger">
                         {{ session('error') }}
                     </div>
                 @endif
                 <form method="POST" action="{{ route('login') }}">
                     @csrf
                     <div class="mb-3">
                         <label for="email" class="form-label">Email</label>
                         <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                     </div>
                     <div class="mb-3">
                         <label for="password" class="form-label">Password</label>
                         <input type="password" class="form-control" id="password" name="password" required>
                     </div>
                     <div class="mb-3 form-check">
                         <input type="checkbox" class="form-check-input" id="remember" name="remember">
                         <label class="form-check-label" for="remember">Remember Me</label>
                     </div>
                     <button type="submit" class="btn btn-primary w-100">Login</button>
                 </form>
                 <p class="text-center mt-3">
                     Belum punya akun? <a href="{{ route('register') }}">Register</a>
                 </p>
                 <hr>
                 <p class="text-center">Atau login dengan:</p>
                 <div class="d-flex justify-content-center gap-2">
                     <a href="{{ route('socialite.redirect', 'google') }}" class="btn btn-danger">Google</a>
                     <a href="{{ route('socialite.redirect', 'github') }}" class="btn btn-dark">GitHub</a>
                 </div>
             </div>
         </div>
     </body>
     </html>