@extends('layouts.app')

@section('title', 'Buat Berita')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Buat Berita</h1>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Formulir Berita</h3>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('berita.store') }}" method="POST" enctype="multipart/form-data" id="beritaForm">
                        @csrf
                        <div class="form-group">
                            <label for="title">Judul</label>
                            <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="kategori_id">Kategori</label>
                            <select name="kategori_id" id="kategori_id" class="form-control @error('kategori_id') is-invalid @enderror" required>
                                <option value="">Pilih Kategori</option>
                                @foreach ($kategoris as $kategori)
                                    <option value="{{ $kategori->id }}" {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>{{ $kategori->nama }}</option>
                                @endforeach
                            </select>
                            @error('kategori_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="content">Isi</label>
                            <textarea name="content" id="content" class="form-control @error('content') is-invalid @enderror" rows="5" required>{{ old('content') }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="image">Gambar</label>
                            <input type="file" name="image" id="image" class="form-control-file @error('image') is-invalid @enderror" onchange="previewFile()">
                            <small class="form-text text-muted">Pilih file gambar (max 2MB, format: jpg, jpeg, png, gif).</small>
                            <div id="fileName" class="mt-1 text-info"></div>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @endif
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        function previewFile() {
            const fileInput = document.getElementById('image');
            const fileNameDiv = document.getElementById('fileName');
            if (fileInput.files.length > 0) {
                fileNameDiv.textContent = 'File dipilih: ' + fileInput.files[0].name;
            } else {
                fileNameDiv.textContent = '';
            }
        }
    </script>
@endpush