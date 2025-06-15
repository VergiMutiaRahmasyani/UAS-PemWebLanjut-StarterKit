<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Berita extends Model
{
    protected $fillable = ['judul', 'isi', 'kategori_id', 'user_id', 'gambar', 'status'];
}