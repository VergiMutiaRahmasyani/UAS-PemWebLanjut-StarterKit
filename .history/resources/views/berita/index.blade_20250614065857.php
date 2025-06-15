@extends('layouts.app')
@section('title', 'Daftar Berita')
@section('content')
<div class="content-header">
    <div class="container-fluid">
        <h1 class="m-0">Daftar Berita</h1>
    </div>
</div>
<section class="content">
    <div class="container-fluid">
        <a href="{{ route('berita.create') }}" class="btn btn-primary mb-3">Buat Berita</a>
        <div class="card">
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Judul</th>
                            <th>Kategori</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($beritas as $berita)
                        <tr>
                            <td>{{ $berita->judul }}</td>
                            <td>{{ $berita->kategori->nama }}</td>
                            <td>{{ ucfirst($berita->status) }}</td>
                            <td>
                                @if ($berita->user_id == auth()->id() || auth()->user()->hasRole('editor'))
                                <a href="{{ route('berita.edit', $berita) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('berita.destroy', $berita) }}" method="POST" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                                @endif
                                @if (auth()->user()->hasRole('editor') && $berita->status == 'pending')
                                <form action="{{ route('berita.approve', $berita) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success">Setujui</button>
                                </form>
                                <form action="{{ route('berita.reject', $berita) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-danger">Tolak</button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection