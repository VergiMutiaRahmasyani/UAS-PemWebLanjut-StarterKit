@extends('layouts.app')
@section('title', 'Edit Berita')
@section('content')
<div class="content-header">
    <div class="container-fluid">
        <h1 class="m-0">Edit Berita</h1>
    </div>
</div>
<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('berita.update', $berita) }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="form-group">
                        <label>Judul</label>
                        <input type="text" name="judul" class="form-control" value="{{ $berita->judul }}" required>
                    </div>
                    <div class="form-group">
                        <label>Kategori</label>
                        <select name="kategori_id" class="form-control" required>
                            @foreach ($kategoris as $kategori)
                            <option value="{{ $kategori->id }}" {{ $berita->kategori_id == $kategori->id ? 'selected' : '' }}>{{ $kategori->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Isi</label>
                        <textarea name="isi" class="form-control" rows="5" required>{{ $berita->isi }}</textarea>
                    </div>
                    <div class="form-group">
                        <label>Gambar</label>
                        <input type="file" name="gambar" class="form-control-file">
                        @if ($berita->gambar)
                        <img src="{{ asset('storage/' . $berita->gambar) }}" width="100" class="mt-2">
                        @endif
                    </div>
                    <button type="submit" class="btn btn-primary">Perbarui</button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection