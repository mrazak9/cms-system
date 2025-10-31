<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Theme;
use App\Models\ThemeSetting;
use Carbon\Carbon;

class ThemeContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Seeds default content for theme homepages
     */
    public function run(): void
    {
        $this->command->info('Seeding theme content...');

        // Get Business Professional theme
        $businessTheme = Theme::where('slug', 'business')->first();
        if ($businessTheme) {
            $this->seedBusinessTheme($businessTheme->id);
        }

        // Get Corporate Modern theme
        $corporateTheme = Theme::where('slug', 'corporate')->first();
        if ($corporateTheme) {
            $this->seedCorporateTheme($corporateTheme->id);
        }

        // Get Digital Agency theme
        $digitalAgencyTheme = Theme::where('slug', 'digital-agency')->first();
        if ($digitalAgencyTheme) {
            $this->seedDigitalAgencyTheme($digitalAgencyTheme->id);
        }

        $this->command->info('Theme content seeded successfully!');
    }

    /**
     * Seed Business Professional theme content
     */
    private function seedBusinessTheme($themeId)
    {
        $settings = [
            // Hero Slider
            ['key' => 'hero_slide_1_subtitle', 'value' => 'Best solutions for your business', 'type' => 'text', 'group' => 'hero', 'order' => 1],
            ['key' => 'hero_slide_1_title', 'value' => 'Agency for your great business', 'type' => 'text', 'group' => 'hero', 'order' => 2],
            ['key' => 'hero_slide_1_highlight', 'value' => 'great business', 'type' => 'text', 'group' => 'hero', 'order' => 3],

            ['key' => 'hero_slide_2_subtitle', 'value' => 'Delivering beautiful digital products', 'type' => 'text', 'group' => 'hero', 'order' => 4],
            ['key' => 'hero_slide_2_title', 'value' => 'Shape the future of marketing', 'type' => 'text', 'group' => 'hero', 'order' => 5],
            ['key' => 'hero_slide_2_highlight', 'value' => 'marketing', 'type' => 'text', 'group' => 'hero', 'order' => 6],

            ['key' => 'hero_slide_3_subtitle', 'value' => 'Business strategies and top ideas', 'type' => 'text', 'group' => 'hero', 'order' => 7],
            ['key' => 'hero_slide_3_title', 'value' => 'Provide solutions to small business', 'type' => 'text', 'group' => 'hero', 'order' => 8],
            ['key' => 'hero_slide_3_highlight', 'value' => 'small business', 'type' => 'text', 'group' => 'hero', 'order' => 9],

            // Services Section
            ['key' => 'services_section_title', 'value' => 'Business services', 'type' => 'text', 'group' => 'services', 'order' => 10],
            ['key' => 'services_section_subtitle', 'value' => 'What we offer', 'type' => 'text', 'group' => 'services', 'order' => 11],

            ['key' => 'service_1_title', 'value' => 'Business planning', 'type' => 'text', 'group' => 'services', 'order' => 12],
            ['key' => 'service_1_description', 'value' => 'Build strategies that grow your business and achieve success.', 'type' => 'textarea', 'group' => 'services', 'order' => 13],

            ['key' => 'service_2_title', 'value' => 'Market research', 'type' => 'text', 'group' => 'services', 'order' => 14],
            ['key' => 'service_2_description', 'value' => 'Understand your market and make informed decisions.', 'type' => 'textarea', 'group' => 'services', 'order' => 15],

            ['key' => 'service_3_title', 'value' => 'Digital solutions', 'type' => 'text', 'group' => 'services', 'order' => 16],
            ['key' => 'service_3_description', 'value' => 'Transform your business with cutting-edge technology.', 'type' => 'textarea', 'group' => 'services', 'order' => 17],

            // CTA Section
            ['key' => 'cta_title', 'value' => 'Ready to grow your business with us?', 'type' => 'text', 'group' => 'cta', 'order' => 18],
            ['key' => 'cta_button_text', 'value' => 'Get started now', 'type' => 'text', 'group' => 'cta', 'order' => 19],
        ];

        $this->insertSettings($themeId, $settings);
        $this->command->info('  ✓ Business Professional theme content seeded');
    }

    /**
     * Seed Corporate Modern theme content
     */
    private function seedCorporateTheme($themeId)
    {
        $settings = [
            // Hero Section
            ['key' => 'hero_subtitle', 'value' => 'Corporate business solutions', 'type' => 'text', 'group' => 'hero', 'order' => 1],
            ['key' => 'hero_title', 'value' => 'Modern approach to business', 'type' => 'text', 'group' => 'hero', 'order' => 2],
            ['key' => 'hero_highlight', 'value' => 'business', 'type' => 'text', 'group' => 'hero', 'order' => 3],
            ['key' => 'hero_description', 'value' => 'Powerful solutions for growing companies and enterprises.', 'type' => 'textarea', 'group' => 'hero', 'order' => 4],

            // Features Section
            ['key' => 'features_section_title', 'value' => 'Professional services', 'type' => 'text', 'group' => 'features', 'order' => 5],
            ['key' => 'features_section_subtitle', 'value' => 'Our expertise', 'type' => 'text', 'group' => 'features', 'order' => 6],

            // CTA Section
            ['key' => 'cta_title', 'value' => "Let's work together on your next project", 'type' => 'text', 'group' => 'cta', 'order' => 7],
            ['key' => 'cta_button_text', 'value' => 'Contact us', 'type' => 'text', 'group' => 'cta', 'order' => 8],
        ];

        $this->insertSettings($themeId, $settings);
        $this->command->info('  ✓ Corporate Modern theme content seeded');
    }

    /**
     * Seed Digital Agency theme content
     */
    private function seedDigitalAgencyTheme($themeId)
    {
        $settings = [
            // Hero Section
            ['key' => 'hero_title', 'value' => 'Creative digital agency', 'type' => 'text', 'group' => 'hero', 'order' => 1],
            ['key' => 'hero_highlight', 'value' => 'agency', 'type' => 'text', 'group' => 'hero', 'order' => 2],
            ['key' => 'hero_description', 'value' => 'We create unique digital experiences that inspire and engage your audience.', 'type' => 'textarea', 'group' => 'hero', 'order' => 3],

            // Services Section
            ['key' => 'services_section_title', 'value' => 'Our expertise', 'type' => 'text', 'group' => 'services', 'order' => 4],
            ['key' => 'services_section_subtitle', 'value' => 'What we do', 'type' => 'text', 'group' => 'services', 'order' => 5],

            // CTA Section
            ['key' => 'cta_title', 'value' => 'Ready to start your next project?', 'type' => 'text', 'group' => 'cta', 'order' => 6],
            ['key' => 'cta_button_text', 'value' => "Let's talk", 'type' => 'text', 'group' => 'cta', 'order' => 7],
        ];

        $this->insertSettings($themeId, $settings);
        $this->command->info('  ✓ Digital Agency theme content seeded');
    }

    /**
     * Insert settings with duplicate check
     */
    private function insertSettings($themeId, $settings)
    {
        foreach ($settings as $setting) {
            ThemeSetting::updateOrCreate(
                [
                    'theme_id' => $themeId,
                    'key' => $setting['key'],
                ],
                [
                    'value' => $setting['value'],
                    'type' => $setting['type'],
                    'group' => $setting['group'],
                    'order' => $setting['order'],
                ]
            );
        }
    }
}
