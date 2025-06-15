@extends('layouts.app')

@section('title', 'Halaman Tidak Ditemukan')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="error-page">
                <h2 class="headline text-warning">404</h2>
                <div class="error-content">
                    <h3><i class="fas fa-exclamation-triangle text-warning"></i> Oops! Halaman tidak ditemukan.</h3>
                    <p>
                        Kami tidak dapat menemukan halaman yang Anda cari.
                        Sementara itu, Anda dapat <a href="{{ route('dashboard') }}">kembali ke dashboard</a>.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
