<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // General Settings
            [
                'key' => 'site_name',
                'value' => 'SimpleCMS',
                'type' => 'text',
                'group' => 'general',
            ],
            [
                'key' => 'site_tagline',
                'value' => 'Easy Content Management System',
                'type' => 'text',
                'group' => 'general',
            ],
            [
                'key' => 'site_logo',
                'value' => '/assets/logo.png',
                'type' => 'text',
                'group' => 'general',
            ],
            [
                'key' => 'site_favicon',
                'value' => '/assets/favicon.ico',
                'type' => 'text',
                'group' => 'general',
            ],
            [
                'key' => 'items_per_page',
                'value' => '10',
                'type' => 'number',
                'group' => 'general',
            ],
            [
                'key' => 'timezone',
                'value' => 'Asia/Jakarta',
                'type' => 'text',
                'group' => 'general',
            ],
            [
                'key' => 'date_format',
                'value' => 'Y-m-d',
                'type' => 'text',
                'group' => 'general',
            ],
            [
                'key' => 'time_format',
                'value' => 'H:i:s',
                'type' => 'text',
                'group' => 'general',
            ],

            // Contact Settings
            [
                'key' => 'contact_email',
                'value' => 'info@simplecms.test',
                'type' => 'text',
                'group' => 'contact',
            ],
            [
                'key' => 'contact_phone',
                'value' => '+62 812-3456-7890',
                'type' => 'text',
                'group' => 'contact',
            ],
            [
                'key' => 'contact_address',
                'value' => 'Jakarta, Indonesia',
                'type' => 'text',
                'group' => 'contact',
            ],
            [
                'key' => 'contact_map_latitude',
                'value' => '-6.2088',
                'type' => 'text',
                'group' => 'contact',
            ],
            [
                'key' => 'contact_map_longitude',
                'value' => '106.8456',
                'type' => 'text',
                'group' => 'contact',
            ],

            // Social Media Settings
            [
                'key' => 'facebook',
                'value' => 'https://facebook.com/simplecms',
                'type' => 'text',
                'group' => 'social',
            ],
            [
                'key' => 'twitter',
                'value' => 'https://twitter.com/simplecms',
                'type' => 'text',
                'group' => 'social',
            ],
            [
                'key' => 'instagram',
                'value' => 'https://instagram.com/simplecms',
                'type' => 'text',
                'group' => 'social',
            ],
            [
                'key' => 'linkedin',
                'value' => 'https://linkedin.com/company/simplecms',
                'type' => 'text',
                'group' => 'social',
            ],
            [
                'key' => 'youtube',
                'value' => 'https://youtube.com/@simplecms',
                'type' => 'text',
                'group' => 'social',
            ],
            [
                'key' => 'whatsapp',
                'value' => '+6281234567890',
                'type' => 'text',
                'group' => 'social',
            ],

            // SEO Settings
            [
                'key' => 'meta_description',
                'value' => 'SimpleCMS - An easy-to-use content management system for building beautiful websites',
                'type' => 'text',
                'group' => 'seo',
            ],
            [
                'key' => 'meta_keywords',
                'value' => 'cms, content management, website builder, simplecms, laravel',
                'type' => 'text',
                'group' => 'seo',
            ],
            [
                'key' => 'google_analytics',
                'value' => '',
                'type' => 'text',
                'group' => 'seo',
            ],
            [
                'key' => 'google_site_verification',
                'value' => '',
                'type' => 'text',
                'group' => 'seo',
            ],
            [
                'key' => 'meta_author',
                'value' => 'SimpleCMS Team',
                'type' => 'text',
                'group' => 'seo',
            ],
            [
                'key' => 'og_image',
                'value' => '/assets/og-image.jpg',
                'type' => 'text',
                'group' => 'seo',
            ],

            // Email Settings
            [
                'key' => 'mail_from_address',
                'value' => 'noreply@simplecms.test',
                'type' => 'text',
                'group' => 'email',
            ],
            [
                'key' => 'mail_from_name',
                'value' => 'SimpleCMS',
                'type' => 'text',
                'group' => 'email',
            ],
            [
                'key' => 'admin_email',
                'value' => 'admin@simplecms.test',
                'type' => 'text',
                'group' => 'email',
            ],
            [
                'key' => 'contact_notification_email',
                'value' => 'admin@simplecms.test',
                'type' => 'text',
                'group' => 'email',
            ],
            [
                'key' => 'contact_notifications_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'email',
            ],
            [
                'key' => 'contact_auto_reply_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'email',
            ],

            // Comments Settings
            [
                'key' => 'comments_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'comments',
            ],
            [
                'key' => 'comments_require_moderation',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'comments',
            ],

            // Maintenance Settings
            [
                'key' => 'maintenance_mode',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'maintenance',
            ],
            [
                'key' => 'maintenance_message',
                'value' => 'We are currently performing scheduled maintenance. We will be back shortly.',
                'type' => 'text',
                'group' => 'maintenance',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }

        $this->command->info('Settings created successfully!');
    }
}
