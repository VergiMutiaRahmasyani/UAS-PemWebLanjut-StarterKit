<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions if they don't exist
        $permissions = [
            'create berita',
            'edit berita',
            'delete berita',
            'publish berita',
            'unpublish berita',
            'manage users',
            'manage roles',
            'manage permissions',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles if they don't exist
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $editorRole = Role::firstOrCreate(['name' => 'editor']);
        $wartawanRole = Role::firstOrCreate(['name' => 'wartawan']);

        // Sync permissions for admin role
        $adminRole->syncPermissions(Permission::all());

        // Sync permissions for editor role
        $editorPermissions = [
            'create berita',
            'edit berita',
            'publish berita',
            'unpublish berita'
        ];
        $editorRole->syncPermissions($editorPermissions);

        // Sync permissions for wartawan role
        $wartawanPermissions = [
            'create berita',
            'edit berita'
        ];
        $wartawanRole->syncPermissions($wartawanPermissions);
    }
}
