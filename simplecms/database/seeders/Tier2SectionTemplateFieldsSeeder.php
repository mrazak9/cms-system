<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SectionTemplate;

class Tier2SectionTemplateFieldsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hero Style 2 - With Video
        $hero2Fields = [
            ['name' => 'heading', 'type' => 'text', 'label' => 'Heading', 'required' => true, 'placeholder' => 'Innovative Solutions for Modern Business'],
            ['name' => 'subheading', 'type' => 'textarea', 'label' => 'Subheading', 'required' => false, 'rows' => 2, 'placeholder' => 'Join thousands of satisfied customers worldwide'],
            ['name' => 'button_text', 'type' => 'text', 'label' => 'Button Text', 'required' => false, 'placeholder' => 'Watch Video'],
            ['name' => 'button_link', 'type' => 'url', 'label' => 'Button Link', 'required' => false, 'placeholder' => '#video'],
            ['name' => 'video_url', 'type' => 'url', 'label' => 'Video URL (YouTube/Vimeo Embed)', 'required' => false, 'placeholder' => 'https://www.youtube.com/embed/VIDEO_ID'],
            ['name' => 'background_overlay', 'type' => 'select', 'label' => 'Background Overlay', 'required' => false, 'options' => [
                ['value' => 'rgba(0, 0, 0, 0)', 'label' => 'None'],
                ['value' => 'rgba(0, 0, 0, 0.3)', 'label' => 'Light (30%)'],
                ['value' => 'rgba(0, 0, 0, 0.5)', 'label' => 'Medium (50%)'],
                ['value' => 'rgba(0, 0, 0, 0.7)', 'label' => 'Dark (70%)'],
                ['value' => 'rgba(0, 0, 0, 0.9)', 'label' => 'Very Dark (90%)']
            ]]
        ];

        // Stats/Counter Section
        $stats1Fields = [
            ['name' => 'heading', 'type' => 'text', 'label' => 'Heading', 'required' => false, 'placeholder' => 'Our Impact in Numbers'],
            ['name' => 'background_color', 'type' => 'color', 'label' => 'Background Color', 'required' => false, 'default' => '#f7fafc'],
            ['name' => 'stats', 'type' => 'textarea', 'label' => 'Statistics (JSON Array)', 'required' => true, 'rows' => 10, 'placeholder' => '[{"number":"10000+","label":"Active Users"},{"number":"50+","label":"Countries Served"}]', 'help' => 'Enter statistics as JSON array with "number" and "label" keys']
        ];

        // Gallery Grid
        $gallery1Fields = [
            ['name' => 'heading', 'type' => 'text', 'label' => 'Heading', 'required' => true, 'placeholder' => 'Our Portfolio'],
            ['name' => 'subheading', 'type' => 'text', 'label' => 'Subheading', 'required' => false, 'placeholder' => 'Showcasing our best work'],
            ['name' => 'columns', 'type' => 'select', 'label' => 'Grid Columns', 'required' => false, 'options' => [
                ['value' => '2', 'label' => '2 Columns'],
                ['value' => '3', 'label' => '3 Columns'],
                ['value' => '4', 'label' => '4 Columns']
            ]],
            ['name' => 'images', 'type' => 'textarea', 'label' => 'Images (JSON Array)', 'required' => true, 'rows' => 10, 'placeholder' => '[{"url":"/assets/gallery/image-1.jpg","title":"Project 1"}]', 'help' => 'Enter images as JSON array with "url" and "title" keys']
        ];

        // FAQ Accordion
        $faq1Fields = [
            ['name' => 'heading', 'type' => 'text', 'label' => 'Heading', 'required' => true, 'placeholder' => 'Frequently Asked Questions'],
            ['name' => 'subheading', 'type' => 'text', 'label' => 'Subheading', 'required' => false, 'placeholder' => 'Find answers to common questions'],
            ['name' => 'faqs', 'type' => 'textarea', 'label' => 'FAQs (JSON Array)', 'required' => true, 'rows' => 15, 'placeholder' => '[{"question":"How do I get started?","answer":"Getting started is easy!"}]', 'help' => 'Enter FAQs as JSON array with "question" and "answer" keys']
        ];

        // Services - Card Layout
        $services1Fields = [
            ['name' => 'heading', 'type' => 'text', 'label' => 'Heading', 'required' => true, 'placeholder' => 'Our Services'],
            ['name' => 'subheading', 'type' => 'text', 'label' => 'Subheading', 'required' => false, 'placeholder' => 'Comprehensive solutions for your business needs'],
            ['name' => 'services', 'type' => 'textarea', 'label' => 'Services (JSON Array)', 'required' => true, 'rows' => 15, 'placeholder' => '[{"icon":"fa-laptop-code","title":"Web Development","description":"Custom websites","link":"/services/web"}]', 'help' => 'Enter services as JSON array with "icon", "title", "description", and "link" keys']
        ];

        // Testimonials - Carousel
        $testimonials1Fields = [
            ['name' => 'heading', 'type' => 'text', 'label' => 'Heading', 'required' => true, 'placeholder' => 'What Our Clients Say'],
            ['name' => 'subheading', 'type' => 'text', 'label' => 'Subheading', 'required' => false, 'placeholder' => 'Trusted by businesses worldwide'],
            ['name' => 'testimonials', 'type' => 'textarea', 'label' => 'Testimonials (JSON Array)', 'required' => true, 'rows' => 15, 'placeholder' => '[{"name":"John Smith","position":"CEO, Tech Corp","avatar":"/avatars/1.jpg","content":"Exceptional service","rating":5}]', 'help' => 'Enter testimonials as JSON array with "name", "position", "avatar", "content", and "rating" keys']
        ];

        // Contact Form - Modern
        $contact1Fields = [
            ['name' => 'heading', 'type' => 'text', 'label' => 'Heading', 'required' => true, 'placeholder' => 'Get In Touch'],
            ['name' => 'subheading', 'type' => 'text', 'label' => 'Subheading', 'required' => false, 'placeholder' => 'We\'d love to hear from you'],
            ['name' => 'show_info', 'type' => 'checkbox', 'label' => 'Show Contact Information', 'required' => false, 'description' => 'Display email, phone, and address alongside the form'],
            ['name' => 'email', 'type' => 'email', 'label' => 'Email Address', 'required' => false, 'placeholder' => 'info@example.com'],
            ['name' => 'phone', 'type' => 'text', 'label' => 'Phone Number', 'required' => false, 'placeholder' => '+62 812-3456-7890'],
            ['name' => 'address', 'type' => 'textarea', 'label' => 'Address', 'required' => false, 'rows' => 2, 'placeholder' => 'Jakarta, Indonesia'],
            ['name' => 'form_fields', 'type' => 'textarea', 'label' => 'Form Fields (JSON Array)', 'required' => false, 'rows' => 3, 'placeholder' => '["name","email","phone","message"]', 'help' => 'Enter form fields as JSON array']
        ];

        // About Us - Two Column
        $about1Fields = [
            ['name' => 'heading', 'type' => 'text', 'label' => 'Heading', 'required' => true, 'placeholder' => 'About Our Company'],
            ['name' => 'subheading', 'type' => 'text', 'label' => 'Subheading', 'required' => false, 'placeholder' => 'Leading the Industry Since 2010'],
            ['name' => 'content', 'type' => 'textarea', 'label' => 'Content', 'required' => true, 'rows' => 5, 'placeholder' => 'We are a dedicated team of professionals...'],
            ['name' => 'image', 'type' => 'image', 'label' => 'Image', 'required' => false, 'placeholder' => '/assets/images/about.jpg'],
            ['name' => 'stats', 'type' => 'textarea', 'label' => 'Statistics (JSON Array)', 'required' => false, 'rows' => 5, 'placeholder' => '[{"number":"10+","label":"Years Experience"}]', 'help' => 'Enter statistics as JSON array with "number" and "label" keys']
        ];

        // About Us - Single Column
        $about2Fields = [
            ['name' => 'heading', 'type' => 'text', 'label' => 'Heading', 'required' => true, 'placeholder' => 'Our Story'],
            ['name' => 'content', 'type' => 'textarea', 'label' => 'Content', 'required' => true, 'rows' => 5, 'placeholder' => 'Founded with a vision to revolutionize...'],
            ['name' => 'button_text', 'type' => 'text', 'label' => 'Button Text', 'required' => false, 'placeholder' => 'Learn More'],
            ['name' => 'button_link', 'type' => 'url', 'label' => 'Button Link', 'required' => false, 'placeholder' => '/about']
        ];

        // Features - Icon List
        $features2Fields = [
            ['name' => 'heading', 'type' => 'text', 'label' => 'Heading', 'required' => true, 'placeholder' => 'Why Choose Us'],
            ['name' => 'features', 'type' => 'textarea', 'label' => 'Features (JSON Array)', 'required' => true, 'rows' => 15, 'placeholder' => '[{"icon":"fa-check-circle","title":"24/7 Support","description":"Round-the-clock assistance"}]', 'help' => 'Enter features as JSON array with "icon", "title", and "description" keys']
        ];

        // Update templates with field definitions
        $this->updateTemplateByBlade('hero-2', $hero2Fields);
        $this->updateTemplateByBlade('stats-1', $stats1Fields);
        $this->updateTemplateByBlade('gallery-1', $gallery1Fields);
        $this->updateTemplateByBlade('faq-1', $faq1Fields);
        $this->updateTemplateByBlade('services-1', $services1Fields);
        $this->updateTemplateByBlade('testimonials-1', $testimonials1Fields);
        $this->updateTemplateByBlade('contact-1', $contact1Fields);
        $this->updateTemplateByBlade('about-1', $about1Fields);
        $this->updateTemplateByBlade('about-2', $about2Fields);
        $this->updateTemplateByBlade('features-2', $features2Fields);

        $this->command->info('TIER 2 section template fields updated successfully!');
    }

    private function updateTemplateByBlade($bladeView, $fields)
    {
        $template = SectionTemplate::where('blade_view', $bladeView)->first();

        if ($template) {
            $template->update(['fields' => $fields]);
            $this->command->info("✓ Updated fields for: {$template->name} (blade_view: {$bladeView})");
        } else {
            $this->command->warn("✗ Template not found for blade_view: {$bladeView}");
        }
    }
}
