<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Kategori;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Jalankan seeder untuk role dan permission
        $this->call([
            RoleSeeder::class,
            RolePermissionSeeder::class,
        ]);

        // Buat atau update user admin
        $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'), // Ganti dengan password yang aman
                'email_verified_at' => now(),
            ]
        );
        $admin->syncRoles(['admin']);

        // Buat atau update user editor
        $editor = User::updateOrCreate(
            ['email' => 'editor@example.com'],
            [
                'name' => 'Editor',
                'password' => Hash::make('password'), // Ganti dengan password yang aman
                'email_verified_at' => now(),
            ]
        );
        $editor->syncRoles(['editor']);

        // Buat atau update user wartawan
        $wartawan = User::updateOrCreate(
            ['email' => 'wartawan@example.com'],
            [
                'name' => 'Wartawan',
                'password' => Hash::make('password'), // Ganti dengan password yang aman
                'email_verified_at' => now(),
            ]
        );
        $wartawan->syncRoles(['wartawan']);

        // Tambahkan beberapa kategori contoh
        $kategoris = [
            [
                'nama' => 'Politik',
                'slug' => Str::slug('Politik'),
                'keterangan' => 'Berita seputar politik dalam dan luar negeri'
            ],
            [
                'nama' => 'Ekonomi',
                'slug' => Str::slug('Ekonomi'),
                'keterangan' => 'Berita seputar perekonomian dan bisnis'
            ],
            [
                'nama' => 'Olahraga',
                'slug' => Str::slug('Olahraga'),
                'keterangan' => 'Berita seputar dunia olahraga'
            ],
            [
                'nama' => 'Hiburan',
                'slug' => Str::slug('Hiburan'),
                'keterangan' => 'Berita seputar dunia hiburan dan selebriti'
            ],
            [
                'nama' => 'Teknologi',
                'slug' => Str::slug('Teknologi'),
                'keterangan' => 'Berita seputar teknologi dan inovasi'
            ],
        ];

        foreach ($kategoris as $kategori) {
            Kategori::firstOrCreate(
                ['slug' => $kategori['slug']],
                $kategori
            );
        }
    }
}
