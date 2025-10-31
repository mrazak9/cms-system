<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;
use App\Models\PageSection;
use App\Models\SectionTemplate;
use Illuminate\Support\Str;

class CraftoTestPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if test page already exists
        $existingPage = Page::where('slug', 'crafto-showcase')->first();
        if ($existingPage) {
            $this->command->warn('Crafto Showcase page already exists. Deleting old version...');
            $existingPage->sections()->delete();
            $existingPage->delete();
        }

        // Create test page
        $page = Page::create([
            'title' => 'Crafto Template Showcase',
            'slug' => 'crafto-showcase',
            'meta_description' => 'A comprehensive showcase of all Crafto template sections available in SimpleCMS',
            'meta_keywords' => 'crafto, template, showcase, sections, components',
            'is_published' => true,
            'created_by' => 1, // Assuming admin user ID is 1
        ]);

        $this->command->info("Created page: {$page->title}");

        // Get all Crafto templates
        $templates = [
            'crafto.hero-simple',
            'crafto.features-grid',
            'crafto.about-left-image',
            'crafto.services-cards',
            'crafto.team-grid',
            'crafto.testimonials-carousel',
            'crafto.cta-banner',
            'crafto.about-right-image',
        ];

        $order = 0;

        // 1. Hero Simple
        $this->createSection($page, 'crafto.hero-simple', [
            'title' => 'Welcome to Crafto Template Showcase',
            'subtitle' => 'Explore Premium Components',
            'description' => 'Discover 8 beautifully crafted section templates designed for modern websites. Each component is fully responsive and customizable.',
            'button_text' => 'Explore Features',
            'button_url' => '#features',
            'button_2_text' => 'Contact Us',
            'button_2_url' => '#contact',
            'background_image' => 'crafto/images/demo-corporate-slider-bg.jpg',
            'text_color' => '#ffffff',
            'overlay_opacity' => '0.6',
            'height' => 'large'
        ], $order++);

        // 2. Features Grid
        $this->createSection($page, 'crafto.features-grid', [
            'heading' => 'Powerful Features',
            'subheading' => 'What We Offer',
            'description' => 'Our platform provides everything you need to build stunning websites',
            'columns' => 3,
            'background_color' => '#f7f7f7',
            'animation' => true,
            'features' => [
                [
                    'icon' => 'line-icon-Rocket',
                    'icon_type' => 'line-icon',
                    'title' => 'Fast Performance',
                    'description' => 'Lightning-fast load times and optimized code for the best user experience.'
                ],
                [
                    'icon' => 'line-icon-Palette',
                    'icon_type' => 'line-icon',
                    'title' => 'Beautiful Design',
                    'description' => 'Modern, clean designs that make your content stand out and engage visitors.'
                ],
                [
                    'icon' => 'line-icon-Gear-2',
                    'icon_type' => 'line-icon',
                    'title' => 'Easy Customization',
                    'description' => 'Flexible options to customize every aspect of your website without coding.'
                ],
                [
                    'icon' => 'line-icon-Shield',
                    'icon_type' => 'line-icon',
                    'title' => 'Secure & Reliable',
                    'description' => 'Enterprise-grade security features to protect your data and visitors.'
                ],
                [
                    'icon' => 'line-icon-Phone-2',
                    'icon_type' => 'line-icon',
                    'title' => 'Mobile Responsive',
                    'description' => 'Perfect display on all devices - desktop, tablet, and smartphone.'
                ],
                [
                    'icon' => 'line-icon-Heart',
                    'icon_type' => 'line-icon',
                    'title' => '24/7 Support',
                    'description' => 'Dedicated support team ready to help you whenever you need assistance.'
                ]
            ]
        ], $order++);

        // 3. About Left Image
        $this->createSection($page, 'crafto.about-left-image', [
            'heading' => 'About Our Platform',
            'subheading' => 'Who We Are',
            'description' => 'Building the future of web design',
            'content' => 'SimpleCMS with Crafto templates provides a comprehensive solution for creating professional websites. Our platform combines powerful features with an intuitive interface, making it easy for anyone to build stunning websites without technical expertise.',
            'image' => 'crafto/images/demo-corporate-about-01.jpg',
            'features' => [
                ['icon' => 'fa-check', 'text' => 'Professional team with 10+ years experience'],
                ['icon' => 'fa-check', 'text' => 'Award-winning designs and solutions'],
                ['icon' => 'fa-check', 'text' => '1000+ successful projects delivered'],
                ['icon' => 'fa-check', 'text' => '24/7 customer support and maintenance']
            ]
        ], $order++);

        // 4. Services Cards
        $this->createSection($page, 'crafto.services-cards', [
            'heading' => 'Our Services',
            'subheading' => 'What We Do',
            'description' => 'Comprehensive solutions for all your web development needs',
            'services' => [
                [
                    'icon' => 'line-icon-Laptop-3',
                    'title' => 'Web Development',
                    'description' => 'Custom websites built with modern technologies and best practices for optimal performance.',
                    'link' => '#'
                ],
                [
                    'icon' => 'line-icon-Phone-2',
                    'title' => 'Mobile Apps',
                    'description' => 'Native and cross-platform mobile applications that deliver exceptional user experiences.',
                    'link' => '#'
                ],
                [
                    'icon' => 'line-icon-Bar-Chart',
                    'title' => 'Digital Marketing',
                    'description' => 'Strategic campaigns to increase your online presence and drive business growth.',
                    'link' => '#'
                ],
                [
                    'icon' => 'line-icon-Gear-2',
                    'title' => 'Consulting',
                    'description' => 'Expert guidance on technology strategy, architecture, and digital transformation.',
                    'link' => '#'
                ]
            ]
        ], $order++);

        // 5. Team Grid
        $this->createSection($page, 'crafto.team-grid', [
            'heading' => 'Meet Our Team',
            'subheading' => 'The People Behind Success',
            'description' => 'Our talented team of professionals dedicated to your success',
            'members' => [
                [
                    'name' => 'John Anderson',
                    'position' => 'CEO & Founder',
                    'image' => 'crafto/images/team-01.jpg',
                    'social' => [
                        'linkedin' => 'https://linkedin.com',
                        'twitter' => 'https://twitter.com'
                    ]
                ],
                [
                    'name' => 'Sarah Mitchell',
                    'position' => 'Creative Director',
                    'image' => 'crafto/images/team-02.jpg',
                    'social' => [
                        'linkedin' => 'https://linkedin.com',
                        'twitter' => 'https://twitter.com'
                    ]
                ],
                [
                    'name' => 'Michael Chen',
                    'position' => 'Lead Developer',
                    'image' => 'crafto/images/team-03.jpg',
                    'social' => [
                        'linkedin' => 'https://linkedin.com',
                        'twitter' => 'https://twitter.com'
                    ]
                ],
                [
                    'name' => 'Emily Rodriguez',
                    'position' => 'Marketing Manager',
                    'image' => 'crafto/images/team-04.jpg',
                    'social' => [
                        'linkedin' => 'https://linkedin.com',
                        'twitter' => 'https://twitter.com'
                    ]
                ]
            ]
        ], $order++);

        // 6. Testimonials Carousel
        $this->createSection($page, 'crafto.testimonials-carousel', [
            'heading' => 'What Clients Say',
            'subheading' => 'Testimonials',
            'description' => 'Hear from our satisfied clients about their experience',
            'testimonials' => [
                [
                    'name' => 'David Thompson',
                    'company' => 'Tech Innovations Inc',
                    'position' => 'CEO',
                    'content' => 'Working with this team has been an absolute pleasure. They delivered a stunning website that exceeded all our expectations. The attention to detail and professional service was outstanding.',
                    'avatar' => 'crafto/images/avatar-01.jpg',
                    'rating' => 5
                ],
                [
                    'name' => 'Jessica Williams',
                    'company' => 'Creative Studios',
                    'position' => 'Creative Director',
                    'content' => 'The Crafto templates are simply amazing! They helped us launch our new website in record time, and the results have been fantastic. Our conversion rate increased by 150%.',
                    'avatar' => 'crafto/images/avatar-02.jpg',
                    'rating' => 5
                ],
                [
                    'name' => 'Robert Martinez',
                    'company' => 'Digital Solutions',
                    'position' => 'Founder',
                    'content' => 'Exceptional quality and support. The components are well-designed, easy to customize, and the documentation is clear. Highly recommend to anyone building professional websites.',
                    'avatar' => 'crafto/images/avatar-03.jpg',
                    'rating' => 5
                ]
            ]
        ], $order++);

        // 7. CTA Banner
        $this->createSection($page, 'crafto.cta-banner', [
            'title' => 'Ready to Get Started?',
            'subtitle' => 'Limited Time Offer',
            'description' => 'Join thousands of satisfied customers and take your website to the next level today!',
            'button_text' => 'Start Free Trial',
            'button_url' => '/register',
            'button_2_text' => 'View Pricing',
            'button_2_url' => '/pricing',
            'background_color' => '#0039e3',
            'text_color' => '#ffffff',
            'background_image' => ''
        ], $order++);

        // 8. About Right Image
        $this->createSection($page, 'crafto.about-right-image', [
            'heading' => 'Our Mission',
            'subheading' => 'What Drives Us',
            'description' => 'Empowering businesses through technology',
            'content' => 'Our mission is to democratize web development by providing powerful, easy-to-use tools that enable anyone to create professional websites. We believe that great design and functionality should be accessible to everyone, not just those with technical skills.',
            'image' => 'crafto/images/demo-corporate-about-02.jpg',
            'features' => [
                ['icon' => 'fa-check', 'text' => 'Innovation-driven development approach'],
                ['icon' => 'fa-check', 'text' => 'Customer-centric design philosophy'],
                ['icon' => 'fa-check', 'text' => 'Continuous improvement and updates']
            ]
        ], $order++);

        $this->command->info("✓ Created {$order} sections for test page");
        $this->command->info("\n🎉 Crafto Test Page created successfully!");
        $this->command->info("📄 Page URL: /crafto-showcase");
        $this->command->info("🔗 Admin URL: /admin/pages/{$page->id}/edit");
    }

    private function createSection($page, $templateBlade, $content, $order)
    {
        $template = SectionTemplate::where('blade_view', $templateBlade)->first();

        if (!$template) {
            $this->command->error("Template not found: {$templateBlade}");
            return;
        }

        $section = PageSection::create([
            'page_id' => $page->id,
            'section_template_id' => $template->id,
            'content' => $content,
            'order' => $order,
            'is_visible' => true
        ]);

        $this->command->info("  ✓ Added section: {$template->name}");
    }
}
