<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Theme;
use Carbon\Carbon;

class NewThemesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Add new themes from Crafto template-landing-page
     */
    public function run(): void
    {
        $themes = [
            [
                'name' => 'Business Professional',
                'slug' => 'business',
                'description' => 'Professional business template with modern design, perfect for corporate websites and business services.',
                'version' => '1.0.0',
                'author' => 'ThemeZaa',
                'thumbnail' => null,
                'is_active' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Corporate Modern',
                'slug' => 'corporate',
                'description' => 'Modern corporate design with clean layout, ideal for large businesses and enterprises.',
                'version' => '1.0.0',
                'author' => 'ThemeZaa',
                'thumbnail' => null,
                'is_active' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Digital Agency',
                'slug' => 'digital-agency',
                'description' => 'Creative digital agency template with stunning visuals, perfect for agencies and creative studios.',
                'version' => '1.0.0',
                'author' => 'ThemeZaa',
                'thumbnail' => null,
                'is_active' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Restaurant Elegant',
                'slug' => 'restaurant',
                'description' => 'Elegant restaurant template with beautiful design, perfect for restaurants, cafes, and food businesses.',
                'version' => '1.0.0',
                'author' => 'ThemeZaa',
                'thumbnail' => null,
                'is_active' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        foreach ($themes as $theme) {
            // Check if theme already exists
            $existing = Theme::where('slug', $theme['slug'])->first();

            if (!$existing) {
                Theme::create($theme);
                $this->command->info("✓ Theme '{$theme['name']}' created successfully");
            } else {
                $this->command->warn("⚠ Theme '{$theme['name']}' already exists, skipping");
            }
        }

        $this->command->info("\n=== Theme Creation Summary ===");
        $this->command->info("Total themes in database: " . Theme::count());
        $this->command->table(
            ['ID', 'Name', 'Slug', 'Active'],
            Theme::all(['id', 'name', 'slug', 'is_active'])->map(function ($theme) {
                return [
                    $theme->id,
                    $theme->name,
                    $theme->slug,
                    $theme->is_active ? 'YES' : 'NO'
                ];
            })->toArray()
        );
    }
}
