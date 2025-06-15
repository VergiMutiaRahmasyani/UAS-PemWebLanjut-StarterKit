<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Berita extends Model
{
    protected $fillable = ['judul', 'isi', 'kategori_id', 'gambar'];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }
}