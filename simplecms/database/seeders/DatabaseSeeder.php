<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('Starting database seeding...');
        $this->command->newLine();

        // 1. Create roles and permissions first (required for user assignment)
        $this->command->info('1/5 Seeding roles and permissions...');
        $this->call(RolesAndPermissionsSeeder::class);
        $this->command->newLine();

        // 2. Create admin user (depends on roles)
        $this->command->info('2/5 Seeding admin user...');
        $this->call(AdminUserSeeder::class);
        $this->command->newLine();

        // 3. Create themes (optional for pages but good to have early)
        $this->command->info('3/5 Seeding themes...');
        $this->call(ThemeSeeder::class);
        $this->command->newLine();

        // 4. Create section templates (required for page sections)
        $this->command->info('4/5 Seeding section templates...');
        $this->call(SectionTemplateSeeder::class);
        $this->command->newLine();

        // 5. Create site settings
        $this->command->info('5/5 Seeding site settings...');
        $this->call(SettingSeeder::class);
        $this->command->newLine();

        $this->command->info('Database seeding completed successfully!');
        $this->command->newLine();
        $this->command->warn('IMPORTANT: Default admin credentials:');
        $this->command->line('Email: admin@simplecms.test');
        $this->command->line('Password: admin123');
        $this->command->newLine();
    }
}
