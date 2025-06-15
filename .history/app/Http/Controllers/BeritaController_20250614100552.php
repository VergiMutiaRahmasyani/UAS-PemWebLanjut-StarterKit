<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BeritaController extends Controller
{
    public function index()
    {
        $beritas = Berita::where('user_id', Auth::id())->orWhere('status', 'approved')->latest()->get();
        return view('berita.index', compact('beritas'));
    }

    public function indexPublik()
    {
        $beritas = Berita::where('status', 'approved')->latest()->get();
        return view('berita.publik', compact('beritas'));
    }

    public function create()
    {
        $kategoris = Kategori::all();
        return view('berita.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required',
            'kategori_id' => 'required|exists:kategoris,id',
            'gambar' => 'nullable|image|max:2048',
        ]);

        $berita = new Berita();
        $berita->judul = $validated['judul'];
        $berita->isi = $validated['isi'];
        $berita->kategori_id = $validated['kategori_id'];
        $berita->user_id = Auth::id();
        $berita->status = 'pending';

        if ($request->hasFile('gambar')) {
            $berita->gambar = $request->file('gambar')->store('berita', 'public');
        }

        $berita->save();

        return redirect()->route('berita.index')->with('success', 'Berita dibuat, menunggu persetujuan.');
    }

    public function edit(Berita $berita)
    {
        if ($berita->user_id !== Auth::id() && !Auth::user()->hasRole('editor')) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk mengedit berita ini.');
        }
        $kategoris = Kategori::all();
        return view('berita.edit', compact('berita', 'kategoris'));
    }

    public function update(Request $request, Berita $berita)
    {
        if ($berita->user_id !== Auth::id() && !Auth::user()->hasRole('editor')) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk mengedit berita ini.');
        }

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required',
            'kategori_id' => 'required|exists:kategoris,id',
            'gambar' => 'nullable|image|max:2048',
        ]);

        $berita->judul = $validated['judul'];
        $berita->isi = $validated['isi'];
        $berita->kategori_id = $validated['kategori_id'];
        $berita->status = 'pending';

        if ($request->hasFile('gambar')) {
            if ($berita->gambar) {
                Storage::disk('public')->delete($berita->gambar);
            }
            $berita->gambar = $request->file('gambar')->store('berita', 'public');
        }

        $berita->save();

        return redirect()->route('berita.index')->with('success', 'Berita diperbarui, menunggu persetujuan.');
    }

    public function destroy(Berita $berita)
    {
        if ($berita->user_id !== Auth::id() && !Auth::user()->hasRole('editor')) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk menghapus berita ini.');
        }

        if ($berita->gambar) {
            Storage::disk('public')->delete($berita->gambar);
        }

        $berita->delete();
        return redirect()->route('berita.index')->with('success', 'Berita dihapus.');
    }

    public function approve(Berita $berita)
    {
        if (!Auth::user()->hasRole('editor')) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk menyetujui berita.');
        }

        $berita->status = 'approved';
        $berita->save();
        return redirect()->route('berita.index')->with('success', 'Berita disetujui.');
    }

    public function reject(Berita $berita)
    {
        if (!Auth::user()->hasRole('editor')) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk menolak berita.');
        }

        $berita->status = 'rejected';
        $berita->save();
        return redirect()->route('berita.index')->with('success', 'Berita ditolak.');
    }

    public function dashboard()
    {
        $beritaTerverifikasi = Berita::where('status', 'approved')->count();
        $beritaMenunggu = Berita::where('status', 'pending')->count();
        return view('dashboard', compact('beritaTerverifikasi', 'beritaMenunggu'));
    }
}