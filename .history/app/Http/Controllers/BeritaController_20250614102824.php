<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

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
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required',
                'kategori_id' => 'required|exists:kategoris,id',
                'image' => 'nullable|image|max:2048',
            ]);

            $berita = new Berita();
            $berita->title = $validated['title'];
            $berita->content = $validated['content'];
            $berita->kategori_id = $validated['kategori_id'];
            $berita->user_id = Auth::id();
            $berita->status = 'pending';

            if ($request->hasFile('image')) {
                $berita->image = $request->file('image')->store('berita', 'public');
            }

            $berita->save();

            return redirect()->route('berita.index')->with('success', 'Berita dibuat, menunggu persetujuan.');
        } catch (\Exception $e) {
            Log::error('Error menyimpan berita: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menyimpan berita. Silakan coba lagi atau hubungi administrator.');
        }
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
        try {
            if ($berita->user_id !== Auth::id() && !Auth::user()->hasRole('editor')) {
                return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk mengedit berita ini.');
            }

            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required',
                'kategori_id' => 'required|exists:kategoris,id',
                'image' => 'nullable|image|max:2048',
            ]);

            $berita->title = $validated['title'];
            $berita->content = $validated['content'];
            $berita->kategori_id = $validated['kategori_id'];
            $berita->status = 'pending';

            if ($request->hasFile('image')) {
                if ($berita->image) {
                    Storage::disk('public')->delete($berita->image);
                }
                $berita->image = $request->file('image')->store('berita', 'public');
            }

            $berita->save();

            return redirect()->route('berita.index')->with('success', 'Berita diperbarui, menunggu persetujuan.');
        } catch (\Exception $e) {
            Log::error('Error memperbarui berita: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memperbarui berita. Silakan coba lagi atau hubungi administrator.');
        }
    }

    public function destroy(Berita $berita)
    {
        try {
            if ($berita->user_id !== Auth::id() && !Auth::user()->hasRole('editor')) {
                return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk menghapus berita ini.');
            }

            if ($berita->image) {
                Storage::disk('public')->delete($berita->image);
            }

            $berita->delete();
            return redirect()->route('berita.index')->with('success', 'Berita dihapus.');
        } catch (\Exception $e) {
            Log::error('Error menghapus berita: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus berita. Silakan coba lagi atau hubungi administrator.');
        }
    }

    public function approve(Berita $berita)
    {
        try {
            if (!Auth::user()->hasRole('editor')) {
                return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk menyetujui berita.');
            }

            $berita->status = 'approved';
            $berita->save();
            return redirect()->route('berita.index')->with('success', 'Berita disetujui.');
        } catch (\Exception $e) {
            Log::error('Error menyetujui berita: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menyetujui berita. Silakan coba lagi atau hubungi administrator.');
        }
    }

    public function reject(Berita $berita)
    {
        try {
            if (!Auth::user()->hasRole('editor')) {
                return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk menolak berita.');
            }

            $berita->status = 'rejected';
            $berita->save();
            return redirect()->route('berita.index')->with('success', 'Berita ditolak.');
        } catch (\Exception $e) {
            Log::error('Error menolak berita: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menolak berita. Silakan coba lagi atau hubungi administrator.');
        }
    }

    public function dashboard()
    {
        $beritaTerverifikasi = Berita::where('status', 'approved')->count();
        $beritaMenunggu = Berita::where('status', 'pending')->count();
        return view('dashboard', compact('beritaTerverifikasi', 'beritaMenunggu'));
    }