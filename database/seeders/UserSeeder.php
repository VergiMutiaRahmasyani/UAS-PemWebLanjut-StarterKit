<?php
     namespace Database\Seeders;

     use Illuminate\Database\Seeder;
     use App\Models\User;

     class UserSeeder extends Seeder
     {
         public function run(): void
         {
             User::create([
                 'name' => 'Admin User',
                 'email' => 'admin@example.com',
                 'password' => bcrypt('password'),
                 'role' => 'admin',
             ]);
             User::create([
                 'name' => 'Editor User',
                 'email' => 'editor@example.com',
                 'password' => bcrypt('password'),
                 'role' => 'editor',
             ]);
             User::create([
                 'name' => 'Wartawan User',
                 'email' => 'wartawan@example.com',
                 'password' => bcrypt('password'),
                 'role' => 'wartawan',
             ]);
         }
     }