<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Berita;

class Kategori extends Model
{
    use HasFactory;
    
    protected $table = 'kategoris';
    
    protected $fillable = [
        'nama',
        'slug',
        'keterangan'
    ];
    
    /**
     * Get all of the beritas for the Kategori
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function beritas()
    {
        return $this->hasMany(Berita::class);
    }
}