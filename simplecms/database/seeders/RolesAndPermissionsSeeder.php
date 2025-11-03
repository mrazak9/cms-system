<?php

namespace Database\Seeders;

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

        echo "\n\033[32mSeeding roles and permissions...\033[0m\n";

        // Create permissions
        $permissions = $this->getPermissions();

        foreach ($permissions as $group => $perms) {
            echo "\033[36m  Creating {$group} permissions...\033[0m\n";
            foreach ($perms as $permission) {
                Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
            }
        }

        echo "\033[32m  ✓ " . Permission::count() . " permissions created\033[0m\n\n";

        // Create roles and assign permissions
        $this->createAdminRole();
        $this->createEditorRole();
        $this->createAuthorRole();
        $this->createSubscriberRole();

        echo "\n\033[32mRoles and permissions seeded successfully!\033[0m\n";
        $this->command->info('Roles and permissions created successfully!');
    }

    /**
     * Define all permissions grouped by module
     */
    private function getPermissions(): array
    {
        return [
            'pages' => [
                'pages.view',
                'pages.create',
                'pages.edit',
                'pages.delete',
            ],
            'posts' => [
                'posts.view',
                'posts.create',
                'posts.edit',
                'posts.edit-all',
                'posts.delete',
                'posts.delete-all',
                'posts.publish',
            ],
            'categories' => [
                'categories.view',
                'categories.create',
                'categories.edit',
                'categories.delete',
            ],
            'media' => [
                'media.view',
                'media.upload',
                'media.edit',
                'media.edit-all',
                'media.delete',
                'media.delete-all',
            ],
            'menus' => [
                'menus.view',
                'menus.create',
                'menus.edit',
                'menus.delete',
            ],
            'themes' => [
                'themes.view',
                'themes.activate',
                'themes.upload',
                'themes.delete',
                'themes.settings',
            ],
            'settings' => [
                'settings.view',
                'settings.edit',
            ],
            'users' => [
                'users.view',
                'users.create',
                'users.edit',
                'users.delete',
            ],
            'roles' => [
                'roles.view',
                'roles.create',
                'roles.edit',
                'roles.delete',
            ],
        ];
    }

    /**
     * Create Admin role with all permissions
     */
    private function createAdminRole(): void
    {
        echo "\033[36m  Creating Admin role...\033[0m\n";

        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $admin->syncPermissions(Permission::all());

        echo "\033[32m  ✓ Admin role created with all permissions\033[0m\n";
    }

    /**
     * Create Editor role with content management permissions
     */
    private function createEditorRole(): void
    {
        echo "\033[36m  Creating Editor role...\033[0m\n";

        $editor = Role::firstOrCreate(['name' => 'editor', 'guard_name' => 'web']);

        $editorPermissions = [
            // Pages
            'pages.view', 'pages.create', 'pages.edit', 'pages.delete',

            // Posts
            'posts.view', 'posts.create', 'posts.edit', 'posts.edit-all',
            'posts.delete', 'posts.delete-all', 'posts.publish',

            // Categories
            'categories.view', 'categories.create', 'categories.edit', 'categories.delete',

            // Media
            'media.view', 'media.upload', 'media.edit', 'media.edit-all',
            'media.delete', 'media.delete-all',

            // Menus
            'menus.view', 'menus.create', 'menus.edit', 'menus.delete',
        ];

        $editor->syncPermissions($editorPermissions);

        echo "\033[32m  ✓ Editor role created with " . count($editorPermissions) . " permissions\033[0m\n";
    }

    /**
     * Create Author role with limited content creation permissions
     */
    private function createAuthorRole(): void
    {
        echo "\033[36m  Creating Author role...\033[0m\n";

        $author = Role::firstOrCreate(['name' => 'author', 'guard_name' => 'web']);

        $authorPermissions = [
            // Posts (own only)
            'posts.view', 'posts.create', 'posts.edit', 'posts.delete',

            // Media (own only)
            'media.view', 'media.upload', 'media.edit', 'media.delete',
        ];

        $author->syncPermissions($authorPermissions);

        echo "\033[32m  ✓ Author role created with " . count($authorPermissions) . " permissions\033[0m\n";
    }

    /**
     * Create Subscriber role with minimal permissions
     */
    private function createSubscriberRole(): void
    {
        echo "\033[36m  Creating Subscriber role...\033[0m\n";

        $subscriber = Role::firstOrCreate(['name' => 'subscriber', 'guard_name' => 'web']);

        // Subscribers have no special permissions, just dashboard access
        $subscriber->syncPermissions([]);

        echo "\033[32m  ✓ Subscriber role created (basic access only)\033[0m\n";
    }
}
