<?php

     namespace App\Http\Controllers;

     use App\Models\Berita;
     use App\Models\Kategori;
     use Illuminate\Http\Request;
     use Illuminate\Support\Facades\Auth;
     use Illuminate\Support\Str;

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
             $request->validate([
                 'judul' => 'required|string|max:255',
                 'isi' => 'required',
                 'kategori_id' => 'required|exists:kategoris,id',
                 'gambar' => 'nullable|image|max:2048',
             ]);

             $berita = new Berita();
             $berita->judul = $request->judul;
             $berita->isi = $request->isi;
             $berita->kategori_id = $request->kategori_id;
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
                 abort(403);
             }
             $kategoris = Kategori::all();
             return view('berita.edit', compact('berita', 'kategoris'));
         }

         public function update(Request $request, Berita $berita)
         {
             if ($berita->user_id !== Auth::id() && !Auth::user()->hasRole('editor')) {
                 abort(403);
             }

             $request->validate([
                 'judul' => 'required|string|max:255',
                 'isi' => 'required',
                 'kategori_id' => 'required|exists:kategoris,id',
                 'gambar' => 'nullable|image|max:2048',
             ]);

             $berita->judul = $request->judul;
             $berita->isi = $request->isi;
             $berita->kategori_id = $request->kategori_id;
             $berita->status = 'pending';

             if ($request->hasFile('gambar')) {
                 $berita->gambar = $request->file('gambar')->store('berita', 'public');
             }

             $berita->save();

             return redirect()->route('berita.index')->with('success', 'Berita diperbarui, menunggu persetujuan.');
         }

         public function destroy(Berita $berita)
         {
             if ($berita->user_id !== Auth::id() && !Auth::user()->hasRole('editor')) {
                 abort(403);
             }

             $berita->delete();
             return redirect()->route('berita.index')->with('success', 'Berita dihapus.');
         }

         public function approve(Berita $berita)
         {
             $berita->status = 'approved';
             $berita->save();
             return redirect()->route('berita.index')->with('success', 'Berita disetujui.');
         }

         public function reject(Berita $berita)
         {
             $berita->status = 'rejected';
             $berita->save();
             return redirect()->route('berita.index')->with('success', 'Berita ditolak.');
         }

         public function dashboard()
{
    $beritaTerverifikasi = \App\Models\Berita::where('status', 'approved')->count();
    $beritaMenunggu = \App\Models\Berita::where('status', 'pending')->count();
    return view('dashboard', compact('beritaTerverifikasi', 'beritaMenunggu'));
}
     }