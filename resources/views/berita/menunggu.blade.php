@extends('layouts.app')

@section('title', 'Berita Menunggu Persetujuan')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-clock text-warning me-2"></i>Berita Menunggu Persetujuan
                    </h3>
                </div>
                <div class="card-body">
                    @if($beritas->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Tidak ada berita yang menunggu persetujuan</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Judul</th>
                                        <th>Penulis</th>
                                        <th>Kategori</th>
                                        <th>Tanggal Dibuat</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($beritas as $index => $berita)
                                    <tr>
                                        <td>{{ $beritas->firstItem() + $index }}</td>
                                        <td>{{ Str::limit($berita->judul, 50) }}</td>
                                        <td>{{ $berita->user->name }}</td>
                                        <td>{{ $berita->kategori->nama }}</td>
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
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $beritas->links() }}
                        </div>
                    @endif
                </div>
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
