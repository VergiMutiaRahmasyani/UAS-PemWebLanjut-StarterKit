<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BeritaController;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Halaman utama (publik)
Route::get('/', [BeritaController::class, 'indexPublik'])->name('berita.publik');

// Authentication routes
Auth::routes(['register' => true, 'login' => true, 'logout' => true, 'reset' => true]);

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// Socialite authentication routes
Route::get('/auth/{provider}', function ($provider) {
    abort_unless(in_array($provider, ['google', 'github']), 404);
    return Socialite::driver($provider)->redirect();
})->name('socialite.redirect');

Route::get('/auth/{provider}/callback', function ($provider) {
    try {
        $socialUser = Socialite::driver($provider)->user();
        $user = \App\Models\User::updateOrCreate(
            ['email' => $socialUser->email],
            [
                'name' => $socialUser->name,
                'password' => bcrypt(Str::random(16)),
                'provider' => $provider,
                'provider_id' => $socialUser->id,
            ]
        );
        Auth::login($user, true);
        return redirect()->route('dashboard');
    } catch (\Exception $e) {
        return redirect()->route('login')->with('error', 'Gagal login dengan ' . ucfirst($provider));
    }
})->name('socialite.callback');

// Berita routes
Route::middleware('auth')->group(function () {
    Route::resource('berita', BeritaController::class)->except(['show']);

    // Approval routes (hanya untuk Editor)
    Route::middleware('role:editor')->group(function () {
        Route::post('/berita/{berita}/approve', [BeritaController::class, 'approve'])->name('berita.approve');
        Route::post('/berita/{berita}/reject', [BeritaController::class, 'reject'])->name('berita.reject');
    });
});

// Dashboard route
Route::middleware('auth')->get('/dashboard', [BeritaController::class, 'dashboard'])->name('dashboard');
