<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
    \App\Models\Kategori::create(['nama' => 'Politik']);
    \App\Models\Kategori::create(['nama' => 'Ekonomi']);
    \App\Models\Kategori::create(['nama' => 'Olahraga']);
    }
}
