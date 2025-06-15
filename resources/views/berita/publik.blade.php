@extends('layouts.app')

@section('title', 'Berita Terkini')

@push('styles')
<style>
    .berita-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        height: 100%;
    }
    .berita-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .berita-image {
        height: 200px;
        object-fit: cover;
    }
    .berita-content {
        padding: 1.25rem;
    }
    .berita-title {
        font-size: 1.25rem;
        margin-bottom: 0.75rem;
        color: #333;
    }
    .berita-excerpt {
        color: #6c757d;
        margin-bottom: 1rem;
    }
    .berita-meta {
        font-size: 0.85rem;
        color: #6c757d;
    }
    .berita-category {
        display: inline-block;
        padding: 0.25rem 0.5rem;
        background-color: #f8f9fa;
        border-radius: 3px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .no-berita {
        text-align: center;
        padding: 3rem 0;
    }
</style>
@endpush

@section('content')
<section class="content">
    <div class="container-fluid">
        @if($beritas->isEmpty())
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i> Belum ada berita yang tersedia.
            </div>
        @else
            <div class="row">
                @foreach($beritas as $berita)
                <div class="col-md-4 mb-4">
                    <div class="card berita-card h-100">
                        @if($berita->gambar)
                            <img src="{{ asset('storage/' . $berita->gambar) }}" 
                                 class="card-img-top berita-image" 
                                 alt="{{ $berita->judul }}">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                <i class="fas fa-newspaper fa-4x text-muted"></i>
                            </div>
                        @endif
                        <div class="card-body d-flex flex-column">
                            <div class="mb-2">
                                <span class="berita-category">{{ $berita->kategori->nama }}</span>
                            </div>
                            <h5 class="card-title berita-title">
                                <a href="{{ route('berita.show', $berita) }}" class="text-dark text-decoration-none">
                                    {{ $berita->judul }}
                                </a>
                            </h5>
                            <p class="card-text berita-excerpt">
                                {{ Str::limit(strip_tags($berita->isi), 120) }}
                            </p>
                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <i class="far fa-user me-1"></i> {{ $berita->user->name }}
                                    </small>
                                    <small class="text-muted">
                                        <i class="far fa-calendar-alt me-1"></i> {{ $berita->created_at->format('d M Y') }}
                                    </small>
                                </div>
                                <div class="mt-2">
                                    <a href="{{ route('berita.show', $berita) }}" class="btn btn-sm btn-outline-primary">
                                        Baca Selengkapnya <i class="fas fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                {{ $beritas->links() }}
            </div>
        @endif
    </div>
</section>
@endsection