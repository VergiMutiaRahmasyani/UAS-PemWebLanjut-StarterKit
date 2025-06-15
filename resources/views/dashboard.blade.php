@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <!-- Card Total Berita -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Total Berita</h6>
                            <h3 class="mb-0">{{ $beritaTerverifikasi + $beritaMenunggu }}</h3>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded">
                            <i class="fas fa-newspaper fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Card Terverifikasi -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Terverifikasi</h6>
                            <h3 class="mb-0 text-success">{{ $beritaTerverifikasi }}</h3>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded">
                            <i class="fas fa-check-circle fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Card Menunggu -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Menunggu</h6>
                            <h3 class="mb-0 text-warning">{{ $beritaMenunggu }}</h3>
                        </div>
                        <div class="bg-warning bg-opacity-10 p-3 rounded">
                            <i class="fas fa-clock fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar Berita -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        @if($isEditor)
                            <i class="fas fa-clock text-warning me-2"></i>Berita Menunggu Persetujuan
                        @else
                            <i class="fas fa-newspaper text-primary me-2"></i>Berita Terbaru Saya
                        @endif
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('berita.create') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus me-1"></i> Buat Baru
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(($isEditor && $beritaPending->isEmpty()) || (!$isEditor && $beritaSaya->isEmpty()))
                        <div class="text-center py-5">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Tidak ada data yang tersedia</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Judul</th>
                                        <th>Kategori</th>
                                        <th>Status</th>
                                        <th>Tanggal</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($isEditor)
                                        @foreach($beritaPending as $index => $berita)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ Str::limit($berita->judul, 50) }}</td>
                                            <td>{{ $berita->kategori->nama }}</td>
                                            <td>
                                                <span class="badge bg-warning">Menunggu</span>
                                            </td>
                                            <td>{{ $berita->created_at->format('d/m/Y H:i') }}</td>
                                            <td class="text-center">
                                                <div class="btn-group">
                                                    <a href="{{ route('berita.show', $berita->id) }}" 
                                                       class="btn btn-sm btn-info" title="Lihat">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <form action="{{ route('berita.approve', $berita->id) }}" 
                                                          method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success" 
                                                                title="Setujui">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    </form>
                                                    <button type="button" class="btn btn-sm btn-danger" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#rejectModal{{ $berita->id }}"
                                                            title="Tolak">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                                
                                                <!-- Modal Tolak Berita -->
                                                <div class="modal fade" id="rejectModal{{ $berita->id }}" tabindex="-1">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <form action="{{ route('berita.reject', $berita->id) }}" 
                                                                  method="POST">
                                                                @csrf
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">Tolak Berita</h5>
                                                                    <button type="button" class="btn-close" 
                                                                            data-bs-dismiss="modal"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="mb-3">
                                                                        <label class="form-label">Alasan Penolakan</label>
                                                                        <textarea name="rejection_reason" 
                                                                                  class="form-control" 
                                                                                  rows="4" required></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" 
                                                                            class="btn btn-secondary" 
                                                                            data-bs-dismiss="modal">Batal</button>
                                                                    <button type="submit" 
                                                                            class="btn btn-danger">Tolak</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @else
                                        @foreach($beritaSaya as $index => $berita)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ Str::limit($berita->judul, 50) }}</td>
                                            <td>{{ $berita->kategori->nama }}</td>
                                            <td>
                                                @if($berita->status == 'approved')
                                                    <span class="badge bg-success">Disetujui</span>
                                                @elseif($berita->status == 'rejected')
                                                    <span class="badge bg-danger">Ditolak</span>
                                                    @if($berita->rejection_reason)
                                                        <button class="btn btn-sm btn-link p-0 ms-1" 
                                                                data-bs-toggle="tooltip" 
                                                                title="{{ $berita->rejection_reason }}">
                                                            <i class="fas fa-info-circle text-danger"></i>
                                                        </button>
                                                    @endif
                                                @else
                                                    <span class="badge bg-warning">Menunggu</span>
                                                @endif
                                            </td>
                                            <td>{{ $berita->created_at->format('d/m/Y H:i') }}</td>
                                            <td class="text-center">
                                                <div class="btn-group">
                                                    <a href="{{ route('berita.show', $berita->id) }}" 
                                                       class="btn btn-sm btn-info" title="Lihat">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('berita.edit', $berita->id) }}" 
                                                       class="btn btn-sm btn-warning" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('berita.destroy', $berita->id) }}" 
                                                          method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" 
                                                                onclick="return confirm('Yakin ingin menghapus?')"
                                                                title="Hapus">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
                @if(($isEditor && $beritaPending->count() > 0) || (!$isEditor && $beritaSaya->count() > 0))
                <div class="card-footer text-end">
                    <a href="{{ $isEditor ? route('berita.menunggu') : route('berita.index') }}" 
                       class="btn btn-outline-primary btn-sm">
                        Lihat Semua <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Inisialisasi tooltip
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush