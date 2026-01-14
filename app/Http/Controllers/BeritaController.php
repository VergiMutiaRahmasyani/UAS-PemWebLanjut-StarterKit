<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class BeritaController extends Controller
{

    public function index(Request $request)
    {
        $query = Berita::with(['kategori', 'user']);
        
        // Pencarian berdasarkan judul
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('judul', 'like', "%{$search}%");
        }
        
        if (Auth::user()->hasRole('editor')) {
            // Editor melihat semua berita kecuali yang ditolak
            $beritas = $query->where('status', '!=', Berita::STATUS_REJECTED)
                           ->latest()
                           ->paginate(10)
                           ->withQueryString();
        } else {
            // User biasa hanya melihat berita yang mereka buat atau yang sudah disetujui
            $beritas = $query->where(function($q) {
                $q->where('user_id', Auth::id())
                  ->orWhere('status', Berita::STATUS_APPROVED);
            })
            ->where('status', '!=', Berita::STATUS_REJECTED)
            ->latest()
            ->paginate(10)
            ->withQueryString();
        }
        
        return view('berita.index', compact('beritas'));
    }

    /**
     * Menampilkan daftar berita yang sudah disetujui untuk umum
     *
     * @return \Illuminate\View\View
     */
    public function indexPublik()
    {
        $beritas = Berita::with(['kategori', 'user'])
            ->where('status', Berita::STATUS_APPROVED)
            ->latest()
            ->paginate(9); // 9 berita per halaman
            
        return view('berita.publik', compact('beritas'));
    }
    
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Berita  $berita
     * @return \Illuminate\Http\Response
     */
    public function show(Berita $berita)
    {
        // Jika berita belum disetujui, hanya bisa diakses oleh pemilik atau editor
        if ($berita->status !== Berita::STATUS_APPROVED) {
            if (Auth::guest() || (Auth::id() !== $berita->user_id && !Auth::user()->hasRole('editor'))) {
                abort(403, 'Anda tidak memiliki akses ke berita ini.');
            }
        }
        
        // Tambah jumlah view
        $berita->increment('views');
        
        $berita->load(['kategori', 'user']);
        
        return view('berita.show', compact('berita'));
    }

    public function create()
    {
        // Pastikan user sudah login
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu.');
        }
        
        $kategoris = Kategori::all();
        return view('berita.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        try {
            \Log::info('Memvalidasi input', $request->all());
            
            // Validasi input
            $validated = $request->validate([
                'judul' => 'required|string|max:255',
                'isi' => 'required',
                'kategori_id' => 'required|exists:kategoris,id',
                'gambar' => 'required|image|max:2048|mimes:jpg,jpeg,png,gif',
            ]);
            
            \Log::info('Validasi berhasil', $validated);

            // Buat berita baru
            $berita = new Berita();
            $berita->judul = $validated['judul'];
            $berita->isi = $validated['isi'];
            $berita->slug = \Illuminate\Support\Str::slug($validated['judul']);
            $berita->kategori_id = $validated['kategori_id'];
            $berita->user_id = Auth::id();
            
            // Set status berdasarkan role user
            if (Auth::user()->hasRole('editor')) {
                // Editor bisa memilih status
                $berita->status = $request->has('status') && in_array($request->status, [
                    Berita::STATUS_DRAFT, 
                    Berita::STATUS_PENDING, 
                    Berita::STATUS_APPROVED
                ]) ? $request->status : Berita::STATUS_PENDING;
                
                if ($berita->status === Berita::STATUS_APPROVED) {
                    $berita->published_at = now();
                }
            } else {
                // User biasa, status selalu pending untuk diverifikasi editor
                $berita->status = Berita::STATUS_PENDING;
            }

            // Handle upload gambar
            if ($request->hasFile('gambar')) {
                $file = $request->file('gambar');
                \Log::info('File details: ', ['name' => $file->getClientOriginalName(), 'size' => $file->getSize()]);
                $berita->gambar = $file->store('berita', 'public');
                \Log::info('Gambar disimpan: ' . $berita->gambar);
            } else {
                return redirect()->back()->with('error', 'Gambar berita wajib diunggah.');
            }

            $berita->save();

            // Set pesan sukses berdasarkan status
            $message = 'Berita berhasil dibuat';
            if ($berita->status === Berita::STATUS_PENDING) {
                $message .= ' dan menunggu verifikasi editor';
            } elseif ($berita->status === Berita::STATUS_APPROVED) {
                $message .= ' dan telah dipublikasikan';
            } elseif ($berita->status === Berita::STATUS_DRAFT) {
                $message .= ' dan disimpan sebagai draft';
            }

            return redirect()->route('berita.index')
                ->with('success', $message . '.');
                
        } catch (\Exception $e) {
            \Log::error('Error menyimpan berita: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menyimpan berita. Silakan coba lagi atau hubungi administrator.');
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
            // Cek izin edit
            if ($berita->user_id !== Auth::id() && !Auth::user()->hasRole('editor')) {
                return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk mengedit berita ini.');
            }

            // Validasi input
            $validated = $request->validate([
                'judul' => 'required|string|max:255',
                'isi' => 'required',
                'kategori_id' => 'required|exists:kategoris,id',
                'gambar' => 'nullable|image|max:2048|mimes:jpg,jpeg,png,gif',
            ]);

            // Update data berita
            $berita->judul = $validated['judul'];
            $berita->isi = $validated['isi'];
            $berita->slug = \Illuminate\Support\Str::slug($validated['judul']);
            $berita->kategori_id = $validated['kategori_id'];
            
            // Jika editor mengedit, status bisa diubah
            if (Auth::user()->hasRole('editor')) {
                // Editor bisa mengubah status secara manual jika diperlukan
                if ($request->has('status') && in_array($request->status, [
                    Berita::STATUS_DRAFT, 
                    Berita::STATUS_PENDING, 
                    Berita::STATUS_APPROVED,
                    Berita::STATUS_REJECTED
                ])) {
                    $berita->status = $request->status;
                    if ($request->status === Berita::STATUS_APPROVED) {
                        $berita->published_at = now();
                    }
                }
            } else {
                // User biasa, set status pending untuk verifikasi editor
                $berita->status = Berita::STATUS_PENDING;
            }

            // Handle upload gambar
            if ($request->hasFile('gambar')) {
                // Hapus gambar lama jika ada
                if ($berita->gambar && Storage::disk('public')->exists($berita->gambar)) {
                    Storage::disk('public')->delete($berita->gambar);
                }
                $berita->gambar = $request->file('gambar')->store('berita', 'public');
            }

            $berita->save();

            // Set pesan sukses berdasarkan status
            $message = 'Berita berhasil diperbarui';
            if ($berita->status === Berita::STATUS_PENDING) {
                if (Auth::user()->hasRole('editor')) {
                    $message .= ' dan menunggu verifikasi';
                } else {
                    $message .= ' dan menunggu verifikasi editor';
                }
            } elseif ($berita->status === Berita::STATUS_APPROVED) {
                $message .= ' dan telah dipublikasikan';
            } elseif ($berita->status === Berita::STATUS_DRAFT) {
                $message .= ' dan disimpan sebagai draft';
            }

            return redirect()->route('berita.index')
                ->with('success', $message . '.');
        } catch (\Exception $e) {
            Log::error('Error memperbarui berita: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memperbarui berita. Silakan coba lagi atau hubungi administrator.');
        }
    }

    public function destroy(Berita $berita)
    {
        try {
            // Cek izin hapus
            if ($berita->user_id !== Auth::id() && !Auth::user()->hasRole('editor')) {
                return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk menghapus berita ini.');
            }

            // Jika status berita adalah 'draft' atau 'rejected', atau user adalah pemilik/editor
            if (in_array($berita->status, [Berita::STATUS_DRAFT, Berita::STATUS_REJECTED]) || 
                $berita->user_id === Auth::id() || 
                Auth::user()->hasRole('editor')) {
                
                // Hapus gambar terkait jika ada
                if ($berita->gambar && Storage::disk('public')->exists($berita->gambar)) {
                    Storage::disk('public')->delete($berita->gambar);
                }

                $berita->delete();
                return redirect()->route('berita.index')
                    ->with('success', 'Berita berhasil dihapus.');
            }
            
            // Jika berita sudah dipublikasikan, gunakan soft delete
            if ($berita->status === Berita::STATUS_APPROVED) {
                $berita->status = Berita::STATUS_REJECTED;
                $berita->rejection_reason = 'Dihapus oleh ' . Auth::user()->name;
                $berita->save();
                
                return redirect()->route('berita.index')
                    ->with('success', 'Berita berhasil ditandai sebagai ditolak dan diarsipkan.');
            }
            
            return redirect()->back()
                ->with('error', 'Tidak dapat menghapus berita dengan status ini.');
                
        } catch (\Exception $e) {
            Log::error('Error menghapus berita: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal menghapus berita. Silakan coba lagi atau hubungi administrator.');
        }
    }

    /**
     * Menyetujui berita yang menunggu persetujuan
     *
     * @param  \App\Models\Berita  $berita
     * @return \Illuminate\Http\Response
     */
    public function approve(Berita $berita)
    {
        try {
            // Pastikan berita dalam status menunggu atau draft
            if (!in_array($berita->status, [Berita::STATUS_PENDING, Berita::STATUS_DRAFT])) {
                return redirect()->back()
                    ->with('error', 'Hanya berita dengan status "Menunggu" atau "Draft" yang dapat disetujui.');
            }

            // Mulai database transaction
            \DB::beginTransaction();

            try {
                // Update status berita
                $berita->status = Berita::STATUS_APPROVED;
                $berita->published_at = now();
                $berita->rejection_reason = null; // Reset alasan penolakan jika ada
                $berita->save();

                // Log aktivitas
                Log::info('Berita disetujui', [
                    'berita_id' => $berita->id,
                    'judul' => $berita->judul,
                    'editor_id' => Auth::id(),
                    'editor_name' => Auth::user()->name,
                    'published_at' => now()->toDateTimeString()
                ]);

                // Commit transaksi
                \DB::commit();

                // Redirect ke halaman berita yang disetujui
                return redirect()->route('berita.show', $berita)
                    ->with('success', 'Berita berhasil disetujui dan dipublikasikan.');
                    
            } catch (\Exception $e) {
                // Rollback transaksi jika terjadi error
                \DB::rollBack();
                Log::error('Error dalam transaksi approve berita: ' . $e->getMessage());
                throw $e;
            }
            
        } catch (\Exception $e) {
            Log::error('Error menyetujui berita: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal menyetujui berita. ' . $e->getMessage());
        }
    }

    /**
     * Menolak berita yang menunggu persetujuan
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Berita  $berita
     * @return \Illuminate\Http\Response
     */
    public function reject(Request $request, Berita $berita)
    {
        try {
            // Pastikan status berita adalah 'pending' sebelum ditolak
            if ($berita->status !== Berita::STATUS_PENDING) {
                return redirect()->back()
                    ->with('error', 'Hanya berita dengan status "Menunggu" yang dapat ditolak.');
            }

            // Validasi alasan penolakan
            $validated = $request->validate([
                'rejection_reason' => 'required|string|min:10|max:1000',
            ]);

            // Mulai database transaction
            \DB::beginTransaction();

            try {
                // Update status dan alasan penolakan
                $berita->status = Berita::STATUS_REJECTED;
                $berita->rejection_reason = $validated['rejection_reason'];
                $berita->save();

                // Log aksi
                Log::info('Berita ditolak', [
                    'berita_id' => $berita->id,
                    'judul' => $berita->judul,
                    'editor_id' => Auth::id(),
                    'editor_name' => Auth::user()->name,
                    'alasan' => $validated['rejection_reason'],
                    'rejected_at' => now()->toDateTimeString()
                ]);

                // Commit transaksi
                \DB::commit();

                // TODO: Kirim notifikasi ke pembuat berita
                // Notification::send($berita->user, new BeritaDitolak($berita));


                return redirect()->route('dashboard')
                    ->with('success', 'Berita berhasil ditolak. Alasan: ' . $validated['rejection_reason']);
                    
            } catch (\Exception $e) {
                // Rollback transaksi jika terjadi error
                \DB::rollBack();
                throw $e;
            }
            
        } catch (\Exception $e) {
            Log::error('Error menolak berita: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal menolak berita. ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Menampilkan daftar berita yang menunggu persetujuan (untuk editor)
     *
     * @return \Illuminate\View\View
     */
    public function menungguPersetujuan()
    {
        $beritas = Berita::with(['kategori', 'user'])
            ->pending()
            ->latest()
            ->paginate(10);

        return view('berita.menunggu', compact('beritas'));
    }


}