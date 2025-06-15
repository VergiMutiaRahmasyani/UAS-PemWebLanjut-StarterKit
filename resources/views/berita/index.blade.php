@extends('layouts.app')

@push('styles')
<style>
    .modal-reject .modal-header {
        background-color: #f39c12;
        color: #fff;
    }
    .modal-reject .modal-title {
        font-weight: 600;
    }
    .reject-form label {
        font-weight: 500;
    }
    .reject-form textarea {
        resize: vertical;
        min-height: 120px;
    }
</style>
@endpush

@section('title', 'Daftar Berita')

@section('content')
<section class="content">
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <i class="icon fas fa-check"></i> {{ session('success') }}
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Daftar Berita</h3>
                    <a href="{{ route('berita.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Tambah Berita
                    </a>
                </div>
                <div class="card-tools">
                    <form action="{{ route('berita.index') }}" method="GET" class="input-group input-group-sm" style="width: 250px;">
                        <input type="text" name="search" class="form-control float-right" placeholder="Cari berita..." value="{{ request('search') }}">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-default">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-body p-0">
                @if($beritas->count() > 0)
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th style="width: 30%">Judul</th>
                                <th>Kategori</th>
                                <th>Status</th>
                                <th>Tanggal Dibuat</th>
                                <th style="width: 25%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($beritas as $berita)
                            <tr>
                                <td>
                                    <a href="{{ route('berita.edit', $berita) }}" title="Lihat detail">
                                        {{ $berita->judul }}
                                    </a>
                                </td>
                                <td>{{ $berita->kategori->nama }}</td>
                                <td>
                                    @if($berita->status === 'approved')
                                        <span class="badge badge-success">Disetujui</span>
                                    @elseif($berita->status === 'pending')
                                        <span class="badge badge-warning">Menunggu</span>
                                    @else
                                        <span class="badge badge-danger">Ditolak</span>
                                    @endif
                                </td>
                                <td>{{ $berita->created_at->format('d M Y H:i') }}</td>
                                <td class="project-actions">
                                    <a class="btn btn-info btn-sm" href="{{ route('berita.edit', $berita) }}" title="Edit">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                    
                                    @if($berita->status === 'pending' && Auth::user()->hasRole('editor'))
                                        <form action="{{ route('berita.approve', $berita) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm" title="Setujui">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        
                                        <button type="button" class="btn btn-warning btn-sm btn-reject" 
                                        data-berita-id="{{ $berita->id }}" 
                                        data-toggle="tooltip" 
                                        title="Tolak Berita">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    @endif
                                    
                                    <form action="{{ route('berita.destroy', $berita) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" 
                                            onclick="return confirm('Yakin menghapus berita ini?')"
                                            title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="p-3">
                        <div class="alert alert-info">
                            <i class="icon fas fa-info"></i> Tidak ada berita yang ditemukan.
                        </div>
                    </div>
                @endif
            </div>
            @if($beritas->hasPages())
                <div class="card-footer clearfix">
                    {{ $beritas->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</section>
@push('modals')
<!-- Modal Penolakan Berita -->
@foreach($beritas as $berita)
<div class="modal fade" id="rejectModal{{ $berita->id }}" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="rejectModalLabel">Tolak Berita</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('berita.reject', $berita) }}" method="POST" class="reject-form">
                @csrf
                <div class="modal-body">
                    <p>Anda akan menolak berita dengan judul: <strong>{{ $berita->judul }}</strong></p>
                    <div class="form-group">
                        <label for="rejection_reason{{ $berita->id }}">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea name="rejection_reason" id="rejection_reason{{ $berita->id }}" class="form-control @error('rejection_reason') is-invalid @enderror" required>{{ old('rejection_reason') }}</textarea>
                        @error('rejection_reason')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <small class="form-text text-muted">Berikan alasan penolakan yang jelas dan bermanfaat (minimal 10 karakter).</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">Tolak Berita</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        // Inisialisasi tooltip
        $('[data-toggle="tooltip"]').tooltip();
        
        // Handle modal penolakan
        $('.btn-reject').on('click', function() {
            var beritaId = $(this).data('berita-id');
            $('#rejectModal' + beritaId).modal('show');
        });
        
        // Validasi form penolakan
        $('.reject-form').on('submit', function(e) {
            var reason = $(this).find('textarea').val().trim();
            if (reason.length < 10) {
                e.preventDefault();
                alert('Alasan penolakan minimal 10 karakter.');
                return false;
            }
            return true;
        });
    });
</script>
@endpush

@endsection