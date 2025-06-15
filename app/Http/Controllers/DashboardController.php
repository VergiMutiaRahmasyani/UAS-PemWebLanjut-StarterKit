<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Berita;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isEditor = $user->hasRole('editor');
        
        $data = [
            'isEditor' => $isEditor,
        ];

        // Jika user adalah editor, tambahkan data berita yang menunggu persetujuan
        if ($isEditor) {
            $data['beritaMenunggu'] = Berita::where('status', 'pending')->count();
            $data['beritaTerverifikasi'] = Berita::where('status', 'approved')->count();
            $data['beritaPending'] = Berita::with(['user', 'kategori'])
                ->where('status', 'pending')
                ->latest()
                ->take(5)
                ->get();
        } else {
            // Untuk penulis, tampilkan berita mereka sendiri
            $data['beritaSaya'] = $user->berita()
                ->with('kategori')
                ->latest()
                ->take(5)
                ->get();
            $data['beritaTerverifikasi'] = $user->berita()->where('status', 'approved')->count();
            $data['beritaMenunggu'] = $user->berita()->where('status', 'pending')->count();
        }

        return view('dashboard', $data);
    }
}