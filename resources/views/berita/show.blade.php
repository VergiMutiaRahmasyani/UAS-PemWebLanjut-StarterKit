@extends('layouts.app')

@section('title', $berita->judul)

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $berita->judul }}</h3>
                    <div class="card-tools">
                        <span class="badge bg-{{ $berita->status === 'approved' ? 'success' : ($berita->status === 'pending' ? 'warning' : 'danger') }}">
                            {{ $berita->status_label }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    @if($berita->gambar)
                        <div class="text-center mb-4">
                            <img src="{{ asset('storage/' . $berita->gambar) }}" alt="{{ $berita->judul }}" class="img-fluid rounded">
                        </div>
                    @endif
                    
                    <div class="mb-4">
                        <div class="d-flex justify-content-between text-muted mb-3">
                            <div>
                                <i class="fas fa-user me-1"></i> {{ $berita->user->name }}
                            </div>
                            <div>
                                <i class="fas fa-calendar me-1"></i> {{ $berita->created_at->format('d M Y H:i') }}
                            </div>
                            <div>
                                <i class="fas fa-eye me-1"></i> {{ number_format($berita->views ?? 0) }} x dilihat
                            </div>
                            <div>
                                <i class="fas fa-tag me-1"></i> {{ $berita->kategori->nama }}
                            </div>
                        </div>
                    </div>
                    
                    <div class="berita-content">
                        {!! $berita->isi !!}
                    </div>
                    
                    @if($berita->status === 'rejected' && $berita->rejection_reason)
                        <div class="alert alert-danger mt-4">
                            <h5><i class="fas fa-exclamation-circle me-2"></i>Alasan Penolakan</h5>
                            <p class="mb-0">{{ $berita->rejection_reason }}</p>
                        </div>
                    @endif
                </div>
                
                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('berita.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                        
                        @if(Auth::check() && (Auth::user()->hasRole('editor') || Auth::id() === $berita->user_id))
                            <div class="btn-group">
                                <a href="{{ route('berita.edit', $berita->id) }}" class="btn btn-warning">
                                    <i class="fas fa-edit me-1"></i> Edit
                                </a>
                                @if(Auth::user()->hasRole('editor'))
                                    @if($berita->status === 'pending')
                                        <form action="{{ route('berita.approve', $berita->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-success">
                                                <i class="fas fa-check me-1"></i> Setujui
                                            </button>
                                        </form>
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                            <i class="fas fa-times me-1"></i> Tolak
                                        </button>
                                    @endif
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if(Auth::check() && Auth::user()->hasRole('editor') && $berita->status === 'pending')
<!-- Modal Tolak Berita -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('berita.reject', $berita->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tolak Berita</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Alasan Penolakan</label>
                        <textarea name="rejection_reason" class="form-control" rows="4" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection

@push('styles')
<style>
    .berita-content {
        line-height: 1.8;
        font-size: 1.05rem;
    }
    .berita-content img {
        max-width: 100%;
        height: auto;
        border-radius: 0.25rem;
        margin: 1.5rem 0;
    }
    .berita-content h2, 
    .berita-content h3, 
    .berita-content h4 {
        margin-top: 1.5rem;
        margin-bottom: 1rem;
    }
    .berita-content p {
        margin-bottom: 1.2rem;
    }
</style>
@endpush
