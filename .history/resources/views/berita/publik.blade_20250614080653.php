@extends('layouts.app')
  @section('title', 'Berita Publik')
  @section('content')
  <div class="content-header">
      <div class="container-fluid">
          <h1 class="m-0">Berita Terkini</h1>
      </div>
  </div>
  <section class="content">
      <div class="container-fluid">
          @foreach($beritas as $berita)
          <div class="card mb-3">
              <div class="card-body">
                  <h5>{{ $berita->judul }}</h5>
                  <p>{{ Str::limit($berita->isi, 200) }}</p>
                  <small>Kategori: {{ $berita->kategori->nama }} | Diposting: {{ $berita->created_at->format('d M Y') }}</small>
              </div>
          </div>
          @endforeach
      </div>
  </section>
@endsection