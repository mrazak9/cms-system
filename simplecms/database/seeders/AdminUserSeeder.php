<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        echo "\n\033[32mSeeding users...\033[0m\n";

        // Create admin user
        echo "\033[36m  Creating admin user...\033[0m\n";
        $admin = User::firstOrCreate(
            ['email' => 'admin@simplecms.test'],
            [
                'name' => 'Admin',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
            ]
        );

        // Assign admin role
        if (!$admin->hasRole('admin')) {
            $admin->assignRole('admin');
        }

        echo "\033[32m  ✓ Admin user created\033[0m\n";
        echo "\033[33m    Email: admin@simplecms.test\033[0m\n";
        echo "\033[33m    Password: admin123\033[0m\n\n";

        // Create editor user
        echo "\033[36m  Creating editor user...\033[0m\n";
        $editor = User::firstOrCreate(
            ['email' => 'editor@simplecms.test'],
            [
                'name' => 'Editor',
                'password' => Hash::make('editor123'),
                'email_verified_at' => now(),
            ]
        );

        if (!$editor->hasRole('editor')) {
            $editor->assignRole('editor');
        }

        echo "\033[32m  ✓ Editor user created\033[0m\n";
        echo "\033[33m    Email: editor@simplecms.test\033[0m\n";
        echo "\033[33m    Password: editor123\033[0m\n\n";

        // Create author user
        echo "\033[36m  Creating author user...\033[0m\n";
        $author = User::firstOrCreate(
            ['email' => 'author@simplecms.test'],
            [
                'name' => 'Author',
                'password' => Hash::make('author123'),
                'email_verified_at' => now(),
            ]
        );

        if (!$author->hasRole('author')) {
            $author->assignRole('author');
        }

        echo "\033[32m  ✓ Author user created\033[0m\n";
        echo "\033[33m    Email: author@simplecms.test\033[0m\n";
        echo "\033[33m    Password: author123\033[0m\n\n";

        echo "\033[32mUsers seeded successfully!\033[0m\n";

        $this->command->info('Users created successfully!');
        $this->command->info('Login credentials:');
        $this->command->info('  Admin  - admin@simplecms.test / admin123');
        $this->command->info('  Editor - editor@simplecms.test / editor123');
        $this->command->info('  Author - author@simplecms.test / author123');
    }
}
