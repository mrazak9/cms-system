<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SectionTemplate;

class SectionTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            // Hero Sections
            [
                'name' => 'Hero Style 1 - Full Width',
                'category' => 'hero',
                'blade_view' => 'hero-1',
                'thumbnail' => '/assets/thumbnails/hero-1.jpg',
                'default_fields' => json_encode([
                    'heading' => 'Welcome to Your Amazing Website',
                    'subheading' => 'Transform your ideas into reality with our innovative solutions',
                    'button_text' => 'Get Started',
                    'button_link' => '#contact',
                    'image' => '/assets/default-images/hero-1.jpg',
                    'background_color' => '#1a202c',
                    'text_color' => '#ffffff',
                    'button_color' => '#3182ce',
                ]),
                'is_active' => true,
            ],
            [
                'name' => 'Hero Style 2 - With Video',
                'category' => 'hero',
                'blade_view' => 'hero-2',
                'thumbnail' => '/assets/thumbnails/hero-2.jpg',
                'default_fields' => json_encode([
                    'heading' => 'Innovative Solutions for Modern Business',
                    'subheading' => 'Join thousands of satisfied customers worldwide',
                    'button_text' => 'Watch Video',
                    'button_link' => '#video',
                    'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                    'background_overlay' => 'rgba(0, 0, 0, 0.5)',
                ]),
                'is_active' => true,
            ],

            // About Sections
            [
                'name' => 'About Us - Two Column',
                'category' => 'about',
                'blade_view' => 'about-1',
                'thumbnail' => '/assets/thumbnails/about-1.jpg',
                'default_fields' => json_encode([
                    'heading' => 'About Our Company',
                    'subheading' => 'Leading the Industry Since 2010',
                    'content' => 'We are a dedicated team of professionals committed to delivering exceptional results. Our mission is to provide innovative solutions that help businesses grow and succeed in the digital age.',
                    'image' => '/assets/default-images/about-1.jpg',
                    'stats' => [
                        ['number' => '10+', 'label' => 'Years Experience'],
                        ['number' => '500+', 'label' => 'Happy Clients'],
                        ['number' => '1000+', 'label' => 'Projects Completed'],
                    ],
                ]),
                'is_active' => true,
            ],
            [
                'name' => 'About Us - Single Column',
                'category' => 'about',
                'blade_view' => 'about-2',
                'thumbnail' => '/assets/thumbnails/about-2.jpg',
                'default_fields' => json_encode([
                    'heading' => 'Our Story',
                    'content' => 'Founded with a vision to revolutionize the industry, we have grown from a small startup to a leading company. Our journey has been marked by innovation, dedication, and an unwavering commitment to our clients.',
                    'button_text' => 'Learn More',
                    'button_link' => '/about',
                ]),
                'is_active' => true,
            ],

            // Features Sections
            [
                'name' => 'Features - 3 Column Grid',
                'category' => 'features',
                'blade_view' => 'features-1',
                'thumbnail' => '/assets/thumbnails/features-1.jpg',
                'default_fields' => json_encode([
                    'heading' => 'Our Amazing Features',
                    'subheading' => 'Everything you need to succeed',
                    'features' => [
                        [
                            'icon' => 'fa-rocket',
                            'title' => 'Fast Performance',
                            'description' => 'Lightning-fast load times for the best user experience',
                        ],
                        [
                            'icon' => 'fa-shield-alt',
                            'title' => 'Secure & Reliable',
                            'description' => 'Enterprise-grade security to protect your data',
                        ],
                        [
                            'icon' => 'fa-mobile-alt',
                            'title' => 'Mobile Responsive',
                            'description' => 'Perfect experience on all devices and screen sizes',
                        ],
                    ],
                ]),
                'is_active' => true,
            ],
            [
                'name' => 'Features - Icon List',
                'category' => 'features',
                'blade_view' => 'features-2',
                'thumbnail' => '/assets/thumbnails/features-2.jpg',
                'default_fields' => json_encode([
                    'heading' => 'Why Choose Us',
                    'features' => [
                        [
                            'icon' => 'fa-check-circle',
                            'title' => '24/7 Customer Support',
                            'description' => 'Round-the-clock assistance whenever you need it',
                        ],
                        [
                            'icon' => 'fa-check-circle',
                            'title' => 'Easy to Use',
                            'description' => 'Intuitive interface designed for everyone',
                        ],
                        [
                            'icon' => 'fa-check-circle',
                            'title' => 'Regular Updates',
                            'description' => 'Continuous improvements and new features',
                        ],
                        [
                            'icon' => 'fa-check-circle',
                            'title' => 'Affordable Pricing',
                            'description' => 'Flexible plans to suit any budget',
                        ],
                    ],
                ]),
                'is_active' => true,
            ],

            // Services Sections
            [
                'name' => 'Services - Card Layout',
                'category' => 'services',
                'blade_view' => 'services-1',
                'thumbnail' => '/assets/thumbnails/services-1.jpg',
                'default_fields' => json_encode([
                    'heading' => 'Our Services',
                    'subheading' => 'Comprehensive solutions for your business needs',
                    'services' => [
                        [
                            'icon' => 'fa-laptop-code',
                            'title' => 'Web Development',
                            'description' => 'Custom websites built with the latest technologies',
                            'link' => '/services/web-development',
                        ],
                        [
                            'icon' => 'fa-mobile',
                            'title' => 'Mobile Apps',
                            'description' => 'Native and cross-platform mobile applications',
                            'link' => '/services/mobile-apps',
                        ],
                        [
                            'icon' => 'fa-chart-line',
                            'title' => 'Digital Marketing',
                            'description' => 'Strategic campaigns to grow your online presence',
                            'link' => '/services/digital-marketing',
                        ],
                    ],
                ]),
                'is_active' => true,
            ],

            // Testimonials Sections
            [
                'name' => 'Testimonials - Carousel',
                'category' => 'testimonials',
                'blade_view' => 'testimonials-1',
                'thumbnail' => '/assets/thumbnails/testimonials-1.jpg',
                'default_fields' => json_encode([
                    'heading' => 'What Our Clients Say',
                    'subheading' => 'Trusted by businesses worldwide',
                    'testimonials' => [
                        [
                            'name' => 'John Smith',
                            'position' => 'CEO, Tech Corp',
                            'avatar' => '/assets/avatars/avatar-1.jpg',
                            'content' => 'Exceptional service and outstanding results. The team exceeded our expectations in every way.',
                            'rating' => 5,
                        ],
                        [
                            'name' => 'Sarah Johnson',
                            'position' => 'Marketing Director, StartupXYZ',
                            'avatar' => '/assets/avatars/avatar-2.jpg',
                            'content' => 'Professional, reliable, and highly skilled. I highly recommend their services to anyone.',
                            'rating' => 5,
                        ],
                        [
                            'name' => 'Michael Chen',
                            'position' => 'Founder, InnovateLab',
                            'avatar' => '/assets/avatars/avatar-3.jpg',
                            'content' => 'Working with this team has been a game-changer for our business. Truly outstanding work.',
                            'rating' => 5,
                        ],
                    ],
                ]),
                'is_active' => true,
            ],

            // Contact Sections
            [
                'name' => 'Contact Form - Modern',
                'category' => 'contact',
                'blade_view' => 'contact-1',
                'thumbnail' => '/assets/thumbnails/contact-1.jpg',
                'default_fields' => json_encode([
                    'heading' => 'Get In Touch',
                    'subheading' => 'We\'d love to hear from you',
                    'form_fields' => ['name', 'email', 'phone', 'message'],
                    'show_info' => true,
                    'email' => 'info@example.com',
                    'phone' => '+62 812-3456-7890',
                    'address' => 'Jakarta, Indonesia',
                ]),
                'is_active' => true,
            ],

            // Gallery Sections
            [
                'name' => 'Gallery - Grid Layout',
                'category' => 'gallery',
                'blade_view' => 'gallery-1',
                'thumbnail' => '/assets/thumbnails/gallery-1.jpg',
                'default_fields' => json_encode([
                    'heading' => 'Our Portfolio',
                    'subheading' => 'Showcasing our best work',
                    'columns' => 3,
                    'images' => [
                        ['url' => '/assets/gallery/image-1.jpg', 'title' => 'Project 1'],
                        ['url' => '/assets/gallery/image-2.jpg', 'title' => 'Project 2'],
                        ['url' => '/assets/gallery/image-3.jpg', 'title' => 'Project 3'],
                        ['url' => '/assets/gallery/image-4.jpg', 'title' => 'Project 4'],
                        ['url' => '/assets/gallery/image-5.jpg', 'title' => 'Project 5'],
                        ['url' => '/assets/gallery/image-6.jpg', 'title' => 'Project 6'],
                    ],
                ]),
                'is_active' => true,
            ],

            // CTA Sections
            [
                'name' => 'Call to Action - Centered',
                'category' => 'cta',
                'blade_view' => 'cta-1',
                'thumbnail' => '/assets/thumbnails/cta-1.jpg',
                'default_fields' => json_encode([
                    'heading' => 'Ready to Get Started?',
                    'subheading' => 'Join thousands of satisfied customers today',
                    'button_text' => 'Start Free Trial',
                    'button_link' => '/signup',
                    'secondary_button_text' => 'Contact Sales',
                    'secondary_button_link' => '/contact',
                    'background_color' => '#3182ce',
                    'text_color' => '#ffffff',
                ]),
                'is_active' => true,
            ],

            // Stats Sections
            [
                'name' => 'Statistics - Counter',
                'category' => 'stats',
                'blade_view' => 'stats-1',
                'thumbnail' => '/assets/thumbnails/stats-1.jpg',
                'default_fields' => json_encode([
                    'heading' => 'Our Impact in Numbers',
                    'stats' => [
                        ['number' => '10000+', 'label' => 'Active Users'],
                        ['number' => '50+', 'label' => 'Countries Served'],
                        ['number' => '99.9%', 'label' => 'Uptime'],
                        ['number' => '24/7', 'label' => 'Support Available'],
                    ],
                    'background_color' => '#f7fafc',
                ]),
                'is_active' => true,
            ],

            // FAQ Sections
            [
                'name' => 'FAQ - Accordion',
                'category' => 'faq',
                'blade_view' => 'faq-1',
                'thumbnail' => '/assets/thumbnails/faq-1.jpg',
                'default_fields' => json_encode([
                    'heading' => 'Frequently Asked Questions',
                    'subheading' => 'Find answers to common questions',
                    'faqs' => [
                        [
                            'question' => 'How do I get started?',
                            'answer' => 'Getting started is easy! Simply sign up for an account, choose your plan, and follow our onboarding guide.',
                        ],
                        [
                            'question' => 'What payment methods do you accept?',
                            'answer' => 'We accept all major credit cards, PayPal, and bank transfers for annual subscriptions.',
                        ],
                        [
                            'question' => 'Can I cancel my subscription anytime?',
                            'answer' => 'Yes, you can cancel your subscription at any time from your account settings. No questions asked.',
                        ],
                        [
                            'question' => 'Do you offer customer support?',
                            'answer' => 'Absolutely! We provide 24/7 customer support via email, chat, and phone to all our users.',
                        ],
                        [
                            'question' => 'Is there a free trial available?',
                            'answer' => 'Yes, we offer a 14-day free trial with full access to all features. No credit card required.',
                        ],
                    ],
                ]),
                'is_active' => true,
            ],
        ];

        foreach ($templates as $template) {
            SectionTemplate::create($template);
        }

        $this->command->info('Section templates created successfully!');
    }
}
