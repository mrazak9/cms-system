<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Theme;

class ThemeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $themes = [
            [
                'name' => 'Default Theme',
                'slug' => 'default',
                'description' => 'Clean and minimal general-purpose theme with modern design. Perfect for any type of website.',
                'thumbnail' => '/assets/themes/default-preview.jpg',
                'author' => 'SimpleCMS Team',
                'version' => '1.0.0',
                'is_active' => true,
            ],
            [
                'name' => 'Business Theme',
                'slug' => 'business',
                'description' => 'Professional corporate theme designed for businesses and enterprises. Features clean layouts and sophisticated styling.',
                'thumbnail' => '/assets/themes/business-preview.jpg',
                'author' => 'SimpleCMS Team',
                'version' => '1.0.0',
                'is_active' => false,
            ],
            [
                'name' => 'Creative Theme',
                'slug' => 'creative',
                'description' => 'Bold and creative portfolio theme for agencies, designers, and creative professionals. Stand out with unique layouts.',
                'thumbnail' => '/assets/themes/creative-preview.jpg',
                'author' => 'SimpleCMS Team',
                'version' => '1.0.0',
                'is_active' => false,
            ],
        ];

        foreach ($themes as $theme) {
            Theme::create($theme);
        }

        $this->command->info('Themes created successfully!');
    }
}
