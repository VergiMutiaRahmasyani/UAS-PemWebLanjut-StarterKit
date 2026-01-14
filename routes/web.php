<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Laravel\Socialite\Facades\Socialite;
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

// Test route untuk permission dan logging
Route::get('/test-logging', function() {
    // Coba tulis ke log dengan berbagai level
    \Log::emergency('Ini adalah pesan EMERGENCY');
    \Log::alert('Ini adalah pesan ALERT');
    \Log::critical('Ini adalah pesan CRITICAL');
    \Log::error('Ini adalah pesan ERROR');
    \Log::warning('Ini adalah pesan WARNING');
    \Log::notice('Ini adalah pesan NOTICE');
    \Log::info('Ini adalah pesan INFO');
    \Log::debug('Ini adalah pesan DEBUG');
    
    // Tulis ke file langsung untuk memastikan PHP bisa menulis
    $testFile = storage_path('logs/test_permission.txt');
    file_put_contents($testFile, 'Test write at ' . now()->toDateTimeString());
    
    return response()->json([
        'log_file' => storage_path('logs/laravel.log'),
        'log_writable' => is_writable(storage_path('logs/laravel.log')),
        'test_file' => $testFile,
        'test_file_written' => file_exists($testFile),
        'php_user' => get_current_user(),
    ]);
});

// Halaman utama
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Redirect /home ke dashboard setelah login
Route::get('/home', function () {
    return Auth::check() 
        ? redirect()->route('dashboard') 
        : redirect()->route('login');
})->name('home.redirect');

// Authentication routes
Auth::routes([
    'register' => true,
    'login'    => true,
    'logout'   => true,
    'reset'    => true,
    'verify'   => false,
]);

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
                'email_verified_at' => now(),
            ]
        );
        Auth::login($user, true);
        return redirect()->route('dashboard');
    } catch (\Exception $e) {
        return redirect()->route('login')
            ->with('error', 'Gagal login dengan ' . ucfirst($provider));
    }
})->name('socialite.callback');

// Rute berita yang bisa diakses tanpa login
Route::get('/berita/publik', [BeritaController::class, 'indexPublik'])
    ->name('berita.publik');

// Rute khusus untuk create berita - diletakkan di atas rute parameter {berita}
Route::get('/berita/create', function () {
    try {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        $kategoris = \App\Models\Kategori::all();
        return view('berita.create', compact('kategoris'));
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine();
    }
})->name('berita.create');

// Rute untuk menampilkan detail berita
Route::get('/berita/{berita}', [BeritaController::class, 'show'])
    ->name('berita.show');

// Protected routes (require authentication)
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])
        ->name('dashboard');

    // Profile routes
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::put('/password', [ProfileController::class, 'updatePassword'])
            ->name('profile.password.update');
    });

    // Rute CRUD berita (kecuali create dan show yang sudah didefinisikan di atas)
    Route::resource('berita', BeritaController::class)->except(['index', 'show', 'create']);
    
    // Rute untuk list berita
    Route::get('/berita', [BeritaController::class, 'index'])
        ->name('berita.index');
    
    // Rute khusus editor
    Route::middleware('role:editor')->group(function () {
        // Rute untuk approval/reject berita
        Route::any('/berita/{berita}/approve', [BeritaController::class, 'approve'])
            ->name('berita.approve');
            
        Route::any('/berita/{berita}/reject', [BeritaController::class, 'reject'])
            ->name('berita.reject');
            
        // Rute untuk melihat berita yang menunggu persetujuan
        Route::get('/berita/menunggu', [BeritaController::class, 'menungguPersetujuan'])
            ->name('berita.menunggu');
    });
}); // Penutup untuk Route::middleware(['auth'])->group

// Test routes
Route::get('/test-berita', function () {
    return view('berita.test');
});

Route::get('/test-simple', function () {
    return view('test-simple');
});

// Rute alternatif untuk create berita
Route::get('/buat-berita', function () {
    try {
        $kategoris = \App\Models\Kategori::all();
        return view('berita.create', compact('kategoris'));
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine();
    }
})->name('buat.berita');

// Test view create berita
Route::get('/test-create-berita', function () {
    return view('berita.create');
});

// Rute test langsung ke view create
Route::get('/test-create', function () {
    try {
        $kategoris = \App\Models\Kategori::all();
        return view('berita.create', compact('kategoris'));
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine();
    }
});

// Fallback route
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});