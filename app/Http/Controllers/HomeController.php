<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Berita;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        $isEditor = $user->hasRole('editor');
        
        // Hitung total berita
        $beritaTerverifikasi = Berita::where('status', 'approved')->count();
        $beritaMenunggu = Berita::where('status', 'pending')->count();
        
        // Ambil data berita
        $beritaPending = $isEditor 
            ? Berita::with(['kategori', 'user'])
                  ->where('status', 'pending')
                  ->orderBy('created_at', 'desc')
                  ->limit(5)
                  ->get()
            : collect();
            
        $beritaSaya = !$isEditor
            ? Berita::with('kategori')
                  ->where('user_id', $user->id)
                  ->orderBy('created_at', 'desc')
                  ->limit(5)
                  ->get()
            : collect();
        
        return view('dashboard', compact(
            'isEditor',
            'beritaTerverifikasi',
            'beritaMenunggu',
            'beritaPending',
            'beritaSaya'
        ));
    }
}
