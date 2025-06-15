@extends('layouts.app')
     @section('title', 'Dashboard')
     @section('content')
     <div class="content-header">
         <div class="container-fluid">
             <div class="row mb-2">
                 <div class="col-sm-6">
                     <h1 class="m-0">Dashboard</h1>
                 </div>
             </div>
         </div>
     </div>
     <section class="content">
         <div class="container-fluid">
             <div class="row">
                 <div class="col-lg-3 col-6">
                     <div class="small-box bg-info">
                         <div class="inner">
                             <h3>{{ \App\Models\Berita::where('status', 'approved')->count() }}</h3>
                             <p>Berita Terverifikasi</p>
                         </div>
                         <div class="icon">
                             <i class="fas fa-check"></i>
                         </div>
                     </div>
                 </div>
                 <div class="col-lg-3 col-6">
                     <div class="small-box bg-warning">
                         <div class="inner">
                             <h3>{{ \App\Models\Berita::where('status', 'pending')->count() }}</h3>
                             <p>Berita Menunggu</p>
                         </div>
                         <div class="icon">
                             <i class="fas fa-clock"></i>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
     </section>
     @endsection