<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'manage-pages',
            'manage-posts',
            'manage-menus',
            'manage-themes',
            'manage-settings',
            'manage-users',
        ];

        foreach ($permissions as $permission) {
            Permission::create([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }

        // Create roles
        $adminRole = Role::create([
            'name' => 'admin',
            'guard_name' => 'web'
        ]);

        $editorRole = Role::create([
            'name' => 'editor',
            'guard_name' => 'web'
        ]);

        $viewerRole = Role::create([
            'name' => 'viewer',
            'guard_name' => 'web'
        ]);

        // Assign all permissions to admin role
        $adminRole->givePermissionTo(Permission::all());

        // Assign specific permissions to editor role
        $editorRole->givePermissionTo([
            'manage-pages',
            'manage-posts',
            'manage-menus',
        ]);

        // Viewer role has no permissions (read-only access handled by controllers)

        $this->command->info('Roles and permissions created successfully!');
    }
}
