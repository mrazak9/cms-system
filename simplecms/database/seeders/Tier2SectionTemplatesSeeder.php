<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SectionTemplate;

class Tier2SectionTemplatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            // 1. Stats Counter
            [
                'name' => 'Crafto - Stats Counter',
                'slug' => 'crafto-stats-counter',
                'category' => 'stats',
                'description' => 'Animated statistics counter with icons',
                'blade_view' => 'crafto.stats-counter',
                'thumbnail' => null,
                'fields' => [
                    ['name' => 'heading', 'type' => 'text', 'label' => 'Section Heading', 'required' => false],
                    ['name' => 'subheading', 'type' => 'text', 'label' => 'Subheading', 'required' => false],
                    ['name' => 'background_color', 'type' => 'color', 'label' => 'Background Color', 'default' => '#0039e3'],
                    ['name' => 'text_color', 'type' => 'color', 'label' => 'Text Color', 'default' => '#ffffff'],
                    ['name' => 'animation', 'type' => 'checkbox', 'label' => 'Enable Animation', 'default' => true],
                    [
                        'name' => 'stats',
                        'type' => 'repeater',
                        'label' => 'Statistics',
                        'item_label' => 'Stat',
                        'fields' => [
                            ['name' => 'number', 'type' => 'text', 'label' => 'Number', 'required' => true],
                            ['name' => 'suffix', 'type' => 'text', 'label' => 'Suffix (e.g., +, %, K)', 'placeholder' => '+'],
                            ['name' => 'label', 'type' => 'text', 'label' => 'Label', 'required' => true],
                            ['name' => 'icon', 'type' => 'text', 'label' => 'Icon Class', 'placeholder' => 'fa-users']
                        ]
                    ]
                ],
                'is_active' => true
            ],

            // 2. Pricing Table
            [
                'name' => 'Crafto - Pricing Table',
                'slug' => 'crafto-pricing-table',
                'category' => 'pricing',
                'description' => 'Pricing plans comparison table',
                'blade_view' => 'crafto.pricing-table',
                'thumbnail' => null,
                'fields' => [
                    ['name' => 'heading', 'type' => 'text', 'label' => 'Section Heading', 'default' => 'Choose Your Plan'],
                    ['name' => 'subheading', 'type' => 'text', 'label' => 'Subheading', 'default' => 'Pricing'],
                    ['name' => 'description', 'type' => 'textarea', 'label' => 'Description', 'rows' => 2],
                    ['name' => 'background_color', 'type' => 'color', 'label' => 'Background Color', 'default' => '#ffffff'],
                    ['name' => 'animation', 'type' => 'checkbox', 'label' => 'Enable Animation', 'default' => true],
                    [
                        'name' => 'plans',
                        'type' => 'json',
                        'label' => 'Pricing Plans (JSON)',
                        'rows' => 15,
                        'help' => 'Enter pricing plans as JSON array'
                    ]
                ],
                'is_active' => true
            ],

            // 3. Blog Posts
            [
                'name' => 'Crafto - Blog Posts',
                'slug' => 'crafto-blog-posts',
                'category' => 'blog',
                'description' => 'Latest blog posts grid',
                'blade_view' => 'crafto.blog-posts',
                'thumbnail' => null,
                'fields' => [
                    ['name' => 'heading', 'type' => 'text', 'label' => 'Section Heading', 'default' => 'Latest News'],
                    ['name' => 'subheading', 'type' => 'text', 'label' => 'Subheading', 'default' => 'Blog'],
                    ['name' => 'description', 'type' => 'textarea', 'label' => 'Description', 'rows' => 2],
                    ['name' => 'columns', 'type' => 'select', 'label' => 'Columns', 'default' => 3, 'options' => [
                        ['value' => 2, 'label' => '2 Columns'],
                        ['value' => 3, 'label' => '3 Columns'],
                        ['value' => 4, 'label' => '4 Columns']
                    ]],
                    ['name' => 'show_excerpt', 'type' => 'checkbox', 'label' => 'Show Excerpt', 'default' => true],
                    ['name' => 'show_date', 'type' => 'checkbox', 'label' => 'Show Date', 'default' => true],
                    ['name' => 'show_author', 'type' => 'checkbox', 'label' => 'Show Author', 'default' => true],
                    ['name' => 'show_category', 'type' => 'checkbox', 'label' => 'Show Category', 'default' => true],
                    ['name' => 'background_color', 'type' => 'color', 'label' => 'Background Color', 'default' => '#ffffff'],
                    ['name' => 'animation', 'type' => 'checkbox', 'label' => 'Enable Animation', 'default' => true],
                    ['name' => 'posts', 'type' => 'json', 'label' => 'Blog Posts (JSON)', 'rows' => 12]
                ],
                'is_active' => true
            ],

            // 4. Clients Logo
            [
                'name' => 'Crafto - Clients Logo',
                'slug' => 'crafto-clients-logo',
                'category' => 'clients',
                'description' => 'Client/partner logos showcase',
                'blade_view' => 'crafto.clients-logo',
                'thumbnail' => null,
                'fields' => [
                    ['name' => 'heading', 'type' => 'text', 'label' => 'Section Heading', 'required' => false],
                    ['name' => 'subheading', 'type' => 'text', 'label' => 'Subheading', 'required' => false],
                    ['name' => 'grayscale', 'type' => 'checkbox', 'label' => 'Grayscale Effect', 'default' => true],
                    ['name' => 'background_color', 'type' => 'color', 'label' => 'Background Color', 'default' => '#f8f9fa'],
                    [
                        'name' => 'clients',
                        'type' => 'repeater',
                        'label' => 'Client Logos',
                        'item_label' => 'Client',
                        'fields' => [
                            ['name' => 'logo', 'type' => 'image', 'label' => 'Logo URL', 'required' => true],
                            ['name' => 'name', 'type' => 'text', 'label' => 'Client Name', 'required' => true],
                            ['name' => 'url', 'type' => 'url', 'label' => 'Website URL', 'placeholder' => 'https://']
                        ]
                    ]
                ],
                'is_active' => true
            ],

            // 5. FAQ Accordion
            [
                'name' => 'Crafto - FAQ Accordion',
                'slug' => 'crafto-faq-accordion',
                'category' => 'faq',
                'description' => 'Frequently asked questions accordion',
                'blade_view' => 'crafto.faq-accordion',
                'thumbnail' => null,
                'fields' => [
                    ['name' => 'heading', 'type' => 'text', 'label' => 'Section Heading', 'default' => 'FAQ'],
                    ['name' => 'subheading', 'type' => 'text', 'label' => 'Subheading', 'default' => 'FAQ'],
                    ['name' => 'description', 'type' => 'textarea', 'label' => 'Description', 'rows' => 2],
                    ['name' => 'background_color', 'type' => 'color', 'label' => 'Background Color', 'default' => '#ffffff'],
                    [
                        'name' => 'faqs',
                        'type' => 'repeater',
                        'label' => 'FAQ Items',
                        'item_label' => 'FAQ',
                        'fields' => [
                            ['name' => 'question', 'type' => 'text', 'label' => 'Question', 'required' => true],
                            ['name' => 'answer', 'type' => 'textarea', 'label' => 'Answer', 'rows' => 3, 'required' => true]
                        ]
                    ]
                ],
                'is_active' => true
            ],

            // 6. Contact Form
            [
                'name' => 'Crafto - Contact Form',
                'slug' => 'crafto-contact-form',
                'category' => 'contact',
                'description' => 'Contact form with information',
                'blade_view' => 'crafto.contact-form',
                'thumbnail' => null,
                'fields' => [
                    ['name' => 'heading', 'type' => 'text', 'label' => 'Section Heading', 'default' => 'Get In Touch'],
                    ['name' => 'subheading', 'type' => 'text', 'label' => 'Subheading', 'default' => 'Contact Us'],
                    ['name' => 'description', 'type' => 'textarea', 'label' => 'Description', 'rows' => 2],
                    ['name' => 'email', 'type' => 'email', 'label' => 'Email Address', 'default' => 'info@example.com'],
                    ['name' => 'phone', 'type' => 'text', 'label' => 'Phone Number', 'default' => '+1 234 567 8900'],
                    ['name' => 'address', 'type' => 'textarea', 'label' => 'Address', 'rows' => 2, 'default' => '123 Main Street'],
                    ['name' => 'show_map', 'type' => 'checkbox', 'label' => 'Show Map', 'default' => false],
                    ['name' => 'map_embed_url', 'type' => 'url', 'label' => 'Google Maps Embed URL'],
                    ['name' => 'background_color', 'type' => 'color', 'label' => 'Background Color', 'default' => '#ffffff']
                ],
                'is_active' => true
            ],

            // 7. Portfolio Grid
            [
                'name' => 'Crafto - Portfolio Grid',
                'slug' => 'crafto-portfolio-grid',
                'category' => 'portfolio',
                'description' => 'Project showcase grid with overlay',
                'blade_view' => 'crafto.portfolio-grid',
                'thumbnail' => null,
                'fields' => [
                    ['name' => 'heading', 'type' => 'text', 'label' => 'Section Heading', 'default' => 'Our Portfolio'],
                    ['name' => 'subheading', 'type' => 'text', 'label' => 'Subheading', 'default' => 'Recent Work'],
                    ['name' => 'description', 'type' => 'textarea', 'label' => 'Description', 'rows' => 2],
                    ['name' => 'columns', 'type' => 'select', 'label' => 'Columns', 'default' => 3, 'options' => [
                        ['value' => 2, 'label' => '2 Columns'],
                        ['value' => 3, 'label' => '3 Columns'],
                        ['value' => 4, 'label' => '4 Columns']
                    ]],
                    ['name' => 'background_color', 'type' => 'color', 'label' => 'Background Color', 'default' => '#ffffff'],
                    ['name' => 'animation', 'type' => 'checkbox', 'label' => 'Enable Animation', 'default' => true],
                    ['name' => 'projects', 'type' => 'json', 'label' => 'Projects (JSON)', 'rows' => 12]
                ],
                'is_active' => true
            ]
        ];

        foreach ($templates as $templateData) {
            // Remove fields that don't exist in table
            unset($templateData['slug']);
            unset($templateData['description']);

            // Add default_fields as empty JSON if not set
            if (!isset($templateData['default_fields'])) {
                $templateData['default_fields'] = [];
            }

            SectionTemplate::updateOrCreate(
                ['blade_view' => $templateData['blade_view']],
                $templateData
            );
        }

        $this->command->info('TIER 2 Section Templates seeded successfully!');
    }
}
