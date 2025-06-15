<!DOCTYPE html>
     <html lang="en">
     <head>
         <meta charset="UTF-8">
         <meta name="viewport" content="width=device-width, initial-scale=1.0">
         <meta http-equiv="X-UA-Compatible" content="ie=edge">
         <title>Register - Starter Kit UAS</title>
         <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
     </head>
     <body class="bg-light">
         <div class="container d-flex justify-content-center align-items-center min-vh-100">
             <div class="card p-4" style="width: 100%; max-width: 400px;">
                 <h2 class="text-center mb-4">Register</h2>
                 @if ($errors->any())
                     <div class="alert alert-danger">
                         <ul>
                             @foreach ($errors->all() as $error)
                                 <li>{{ $error }}</li>
                             @endforeach
                         </ul>
                     </div>
                 @endif
                 <form method="POST" action="{{ route('register') }}">
                     @csrf
                     <div class="mb-3">
                         <label for="name" class="form-label">Name</label>
                         <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                     </div>
                     <div class="mb-3">
                         <label for="email" class="form-label">Email</label>
                         <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                     </div>
                     <div class="mb-3">
                         <label for="password" class="form-label">Password</label>
                         <input type="password" class="form-control" id="password" name="password" required>
                     </div>
                     <div class="mb-3">
                         <label for="password-confirm" class="form-label">Confirm Password</label>
                         <input type="password" class="form-control" id="password-confirm" name="password_confirmation" required>
                     </div>
                     <button type="submit" class="btn btn-primary w-100">Register</button>
                 </form>
                 <p class="text-center mt-3">
                     Sudah punya akun? <a href="{{ route('login') }}">Login</a>
                 </p>
             </div>
         </div>
     </body>
     </html>