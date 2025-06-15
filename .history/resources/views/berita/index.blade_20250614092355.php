@extends('layouts.app')
     @section('title', 'Daftar Berita')
     @section('content')
     <div class="content-header">
         <div class="container-fluid">
             <div class="row mb-2">
                 <div class="col-sm-6">
                     <h1 class="m-0">Daftar Berita</h1>
                 </div>
                 <div class="col-sm-6">
                     <a href="{{ route('berita.create') }}" class="btn btn-primary float-right">Tambah Berita</a>
                 </div>
             </div>
         </div>
     </div>
     <section class="content">
         <div class="container-fluid">
             <div class="card">
                 <div class="card-body">
                     <table class="table table-striped">
                         <thead>
                             <tr>
                                 <th>Judul</th>
                                 <th>Kategori</th>
                                 <th>Status</th>
                                 <th>Aksi</th>
                             </tr>
                         </thead>
                         <tbody>
                             @forelse ($beritas as $berita)
                             <tr>
                                 <td>{{ $berita->judul }}</td>
                                 <td>{{ $berita->kategori->nama }}</td>
                                 <td>{{ ucfirst($berita->status) }}</td>
                                 <td>
                                     <a href="{{ route('berita.edit', $berita) }}" class="btn btn-sm btn-info">Edit</a>
                                     <form action="{{ route('berita.destroy', $berita) }}" method="POST" style="display:inline;">
                                         @csrf
                                         @method('DELETE')
                                         <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')">Hapus</button>
                                     </form>
                                     @if ($berita->status === 'pending' && Auth::user()->hasRole('editor'))
                                     <form action="{{ route('berita.approve', $berita) }}" method="POST" style="display:inline;">
                                         @csrf
                                         <button type="submit" class="btn btn-sm btn-success">Setuju</button>
                                     </form>
                                     <form action="{{ route('berita.reject', $berita) }}" method="POST" style="display:inline;">
                                         @csrf
                                         <button type="submit" class="btn btn-sm btn-warning">Tolak</button>
                                     </form>
                                     @endif
                                 </td>
                             </tr>
                             @empty
                             <tr>
                                 <td colspan="4" class="text-center">Tidak ada berita.</td>
                             </tr>
                             @endforelse
                         </tbody>
                     </table>
                 </div>
             </div>
         </div>
     </section>
     @endsection