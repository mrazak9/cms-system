<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SectionTemplate;

class CraftoSectionTemplatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check and create/update Crafto Hero Simple template
        $this->createOrUpdateTemplate([
            'name' => 'Crafto - Hero Simple',
            'category' => 'hero',
            'blade_view' => 'crafto.hero-simple',
            'thumbnail' => '/assets/thumbnails/crafto-hero-simple.jpg',
            'default_fields' => [
                'title' => 'Welcome to Our Website',
                'subtitle' => 'Build Amazing Experiences',
                'description' => '',
                'button_text' => 'Get Started',
                'button_url' => '#',
                'button_2_text' => '',
                'button_2_url' => '#',
                'background_image' => 'crafto/images/demo-corporate-slider-bg.jpg',
                'text_color' => '#ffffff',
                'overlay_opacity' => '0.5',
                'height' => 'default'
            ],
            'fields' => [
                ['name' => 'title', 'type' => 'text', 'label' => 'Title', 'required' => true, 'placeholder' => 'Welcome to Our Website'],
                ['name' => 'subtitle', 'type' => 'text', 'label' => 'Subtitle', 'required' => false, 'placeholder' => 'Build Amazing Experiences'],
                ['name' => 'description', 'type' => 'textarea', 'label' => 'Description', 'required' => false, 'rows' => 3, 'placeholder' => 'Enter description text'],
                ['name' => 'button_text', 'type' => 'text', 'label' => 'Primary Button Text', 'required' => false, 'placeholder' => 'Get Started'],
                ['name' => 'button_url', 'type' => 'url', 'label' => 'Primary Button URL', 'required' => false, 'placeholder' => '#'],
                ['name' => 'button_2_text', 'type' => 'text', 'label' => 'Secondary Button Text', 'required' => false, 'placeholder' => 'Learn More'],
                ['name' => 'button_2_url', 'type' => 'url', 'label' => 'Secondary Button URL', 'required' => false, 'placeholder' => '#'],
                ['name' => 'background_image', 'type' => 'image', 'label' => 'Background Image', 'required' => false, 'placeholder' => 'crafto/images/demo-corporate-slider-bg.jpg'],
                ['name' => 'text_color', 'type' => 'color', 'label' => 'Text Color', 'required' => false, 'default' => '#ffffff'],
                ['name' => 'overlay_opacity', 'type' => 'select', 'label' => 'Overlay Opacity', 'required' => false, 'options' => [
                    ['value' => '0', 'label' => 'None (0%)'],
                    ['value' => '0.3', 'label' => 'Light (30%)'],
                    ['value' => '0.5', 'label' => 'Medium (50%)'],
                    ['value' => '0.7', 'label' => 'Dark (70%)'],
                    ['value' => '0.9', 'label' => 'Very Dark (90%)']
                ]],
                ['name' => 'height', 'type' => 'select', 'label' => 'Section Height', 'required' => false, 'options' => [
                    ['value' => 'small', 'label' => 'Small'],
                    ['value' => 'default', 'label' => 'Default'],
                    ['value' => 'large', 'label' => 'Large']
                ]]
            ],
            'is_active' => true
        ]);

        // Crafto Features Grid
        $this->createOrUpdateTemplate([
            'name' => 'Crafto - Features Grid',
            'category' => 'features',
            'blade_view' => 'crafto.features-grid',
            'thumbnail' => '/assets/thumbnails/crafto-features-grid.jpg',
            'default_fields' => [
                'heading' => 'Our Features',
                'subheading' => '',
                'description' => '',
                'columns' => 3,
                'background_color' => '#f7f7f7',
                'animation' => true,
                'features' => []
            ],
            'fields' => [
                ['name' => 'heading', 'type' => 'text', 'label' => 'Heading', 'required' => true, 'placeholder' => 'Our Features'],
                ['name' => 'subheading', 'type' => 'text', 'label' => 'Subheading', 'required' => false, 'placeholder' => 'What We Offer'],
                ['name' => 'description', 'type' => 'textarea', 'label' => 'Description', 'required' => false, 'rows' => 3, 'placeholder' => 'Feature section description'],
                ['name' => 'columns', 'type' => 'select', 'label' => 'Grid Columns', 'required' => false, 'options' => [
                    ['value' => '2', 'label' => '2 Columns'],
                    ['value' => '3', 'label' => '3 Columns'],
                    ['value' => '4', 'label' => '4 Columns']
                ]],
                ['name' => 'background_color', 'type' => 'color', 'label' => 'Background Color', 'required' => false, 'default' => '#f7f7f7'],
                ['name' => 'animation', 'type' => 'checkbox', 'label' => 'Enable Animation', 'required' => false, 'description' => 'Enable entrance animations for feature items'],
                ['name' => 'features', 'type' => 'textarea', 'label' => 'Features (JSON Array)', 'required' => true, 'rows' => 15, 'placeholder' => '[{"icon":"line-icon-File-Edit","icon_type":"line-icon","title":"Content Management","description":"Create and manage unlimited pages"}]', 'help' => 'Enter features as JSON array with "icon", "icon_type", "title", and "description" keys. Icon types: "line-icon", "feather", or "fontawesome"']
            ],
            'is_active' => true
        ]);

        // Crafto About Left Image
        $this->createOrUpdateTemplate([
            'name' => 'Crafto - About Left Image',
            'category' => 'about',
            'blade_view' => 'crafto.about-left-image',
            'thumbnail' => '/assets/thumbnails/crafto-about-left.jpg',
            'default_fields' => [
                'heading' => 'About Our Company',
                'subheading' => '',
                'description' => '',
                'image' => 'crafto/images/demo-corporate-about-01.jpg',
                'content' => 'We are a team of dedicated professionals...',
                'features' => []
            ],
            'fields' => [
                ['name' => 'heading', 'type' => 'text', 'label' => 'Heading', 'required' => true, 'placeholder' => 'About Our Company'],
                ['name' => 'subheading', 'type' => 'text', 'label' => 'Subheading', 'required' => false, 'placeholder' => 'Who We Are'],
                ['name' => 'description', 'type' => 'textarea', 'label' => 'Short Description', 'required' => false, 'rows' => 2, 'placeholder' => 'Brief intro'],
                ['name' => 'content', 'type' => 'textarea', 'label' => 'Main Content', 'required' => true, 'rows' => 5, 'placeholder' => 'Detailed about content'],
                ['name' => 'image', 'type' => 'image', 'label' => 'Image', 'required' => false, 'placeholder' => 'crafto/images/demo-corporate-about-01.jpg'],
                ['name' => 'features', 'type' => 'textarea', 'label' => 'Features/Points (JSON Array)', 'required' => false, 'rows' => 8, 'placeholder' => '[{"icon":"fa-check","text":"Professional team"}]', 'help' => 'Optional list of features/points with "icon" and "text" keys']
            ],
            'is_active' => true
        ]);

        // Crafto About Right Image
        $this->createOrUpdateTemplate([
            'name' => 'Crafto - About Right Image',
            'category' => 'about',
            'blade_view' => 'crafto.about-right-image',
            'thumbnail' => '/assets/thumbnails/crafto-about-right.jpg',
            'default_fields' => [
                'heading' => 'Our Mission',
                'subheading' => '',
                'description' => '',
                'image' => 'crafto/images/demo-corporate-about-02.jpg',
                'content' => 'Our mission is to deliver excellence...',
                'features' => []
            ],
            'fields' => [
                ['name' => 'heading', 'type' => 'text', 'label' => 'Heading', 'required' => true, 'placeholder' => 'Our Mission'],
                ['name' => 'subheading', 'type' => 'text', 'label' => 'Subheading', 'required' => false, 'placeholder' => 'What Drives Us'],
                ['name' => 'description', 'type' => 'textarea', 'label' => 'Short Description', 'required' => false, 'rows' => 2, 'placeholder' => 'Brief intro'],
                ['name' => 'content', 'type' => 'textarea', 'label' => 'Main Content', 'required' => true, 'rows' => 5, 'placeholder' => 'Detailed mission content'],
                ['name' => 'image', 'type' => 'image', 'label' => 'Image', 'required' => false, 'placeholder' => 'crafto/images/demo-corporate-about-02.jpg'],
                ['name' => 'features', 'type' => 'textarea', 'label' => 'Features/Points (JSON Array)', 'required' => false, 'rows' => 8, 'placeholder' => '[{"icon":"fa-check","text":"Customer focused"}]', 'help' => 'Optional list of features/points with "icon" and "text" keys']
            ],
            'is_active' => true
        ]);

        // Crafto Services Cards
        $this->createOrUpdateTemplate([
            'name' => 'Crafto - Services Cards',
            'category' => 'services',
            'blade_view' => 'crafto.services-cards',
            'thumbnail' => '/assets/thumbnails/crafto-services-cards.jpg',
            'default_fields' => [
                'heading' => 'Our Services',
                'subheading' => '',
                'description' => '',
                'services' => []
            ],
            'fields' => [
                ['name' => 'heading', 'type' => 'text', 'label' => 'Heading', 'required' => true, 'placeholder' => 'Our Services'],
                ['name' => 'subheading', 'type' => 'text', 'label' => 'Subheading', 'required' => false, 'placeholder' => 'What We Do'],
                ['name' => 'description', 'type' => 'textarea', 'label' => 'Description', 'required' => false, 'rows' => 3, 'placeholder' => 'Services description'],
                ['name' => 'services', 'type' => 'textarea', 'label' => 'Services (JSON Array)', 'required' => true, 'rows' => 15, 'placeholder' => '[{"icon":"line-icon-Laptop-3","title":"Web Development","description":"Custom websites","link":"/services/web"}]', 'help' => 'Enter services as JSON array with "icon", "title", "description", and "link" keys']
            ],
            'is_active' => true
        ]);

        // Crafto Team Grid
        $this->createOrUpdateTemplate([
            'name' => 'Crafto - Team Grid',
            'category' => 'team',
            'blade_view' => 'crafto.team-grid',
            'thumbnail' => '/assets/thumbnails/crafto-team-grid.jpg',
            'default_fields' => [
                'heading' => 'Our Team',
                'subheading' => '',
                'description' => '',
                'members' => []
            ],
            'fields' => [
                ['name' => 'heading', 'type' => 'text', 'label' => 'Heading', 'required' => true, 'placeholder' => 'Our Team'],
                ['name' => 'subheading', 'type' => 'text', 'label' => 'Subheading', 'required' => false, 'placeholder' => 'Meet The Team'],
                ['name' => 'description', 'type' => 'textarea', 'label' => 'Description', 'required' => false, 'rows' => 3, 'placeholder' => 'Team section description'],
                ['name' => 'members', 'type' => 'textarea', 'label' => 'Team Members (JSON Array)', 'required' => true, 'rows' => 15, 'placeholder' => '[{"name":"John Doe","position":"CEO","image":"/images/team-1.jpg","social":{"linkedin":"#","twitter":"#"}}]', 'help' => 'Enter team members as JSON array with "name", "position", "image", and "social" keys']
            ],
            'is_active' => true
        ]);

        // Crafto Testimonials Carousel
        $this->createOrUpdateTemplate([
            'name' => 'Crafto - Testimonials Carousel',
            'category' => 'testimonials',
            'blade_view' => 'crafto.testimonials-carousel',
            'thumbnail' => '/assets/thumbnails/crafto-testimonials-carousel.jpg',
            'default_fields' => [
                'heading' => 'What Clients Say',
                'subheading' => '',
                'description' => '',
                'testimonials' => []
            ],
            'fields' => [
                ['name' => 'heading', 'type' => 'text', 'label' => 'Heading', 'required' => true, 'placeholder' => 'What Clients Say'],
                ['name' => 'subheading', 'type' => 'text', 'label' => 'Subheading', 'required' => false, 'placeholder' => 'Testimonials'],
                ['name' => 'description', 'type' => 'textarea', 'label' => 'Description', 'required' => false, 'rows' => 3, 'placeholder' => 'Testimonials description'],
                ['name' => 'testimonials', 'type' => 'textarea', 'label' => 'Testimonials (JSON Array)', 'required' => true, 'rows' => 15, 'placeholder' => '[{"name":"Jane Smith","company":"Tech Corp","position":"CEO","content":"Excellent service!","avatar":"/images/avatar.jpg","rating":5}]', 'help' => 'Enter testimonials as JSON array with "name", "company", "position", "content", "avatar", and "rating" keys']
            ],
            'is_active' => true
        ]);

        // Crafto CTA Banner
        $this->createOrUpdateTemplate([
            'name' => 'Crafto - CTA Banner',
            'category' => 'cta',
            'blade_view' => 'crafto.cta-banner',
            'thumbnail' => '/assets/thumbnails/crafto-cta-banner.jpg',
            'default_fields' => [
                'title' => 'Ready to Get Started?',
                'subtitle' => '',
                'description' => 'Join thousands of satisfied customers',
                'button_text' => 'Get Started',
                'button_url' => '#',
                'button_2_text' => '',
                'button_2_url' => '#',
                'background_color' => '#0039e3',
                'text_color' => '#ffffff',
                'background_image' => ''
            ],
            'fields' => [
                ['name' => 'title', 'type' => 'text', 'label' => 'Title', 'required' => true, 'placeholder' => 'Ready to Get Started?'],
                ['name' => 'subtitle', 'type' => 'text', 'label' => 'Subtitle', 'required' => false, 'placeholder' => 'Optional subtitle'],
                ['name' => 'description', 'type' => 'textarea', 'label' => 'Description', 'required' => false, 'rows' => 2, 'placeholder' => 'Join thousands of satisfied customers'],
                ['name' => 'button_text', 'type' => 'text', 'label' => 'Primary Button Text', 'required' => false, 'placeholder' => 'Get Started'],
                ['name' => 'button_url', 'type' => 'url', 'label' => 'Primary Button URL', 'required' => false, 'placeholder' => '#'],
                ['name' => 'button_2_text', 'type' => 'text', 'label' => 'Secondary Button Text', 'required' => false, 'placeholder' => 'Learn More'],
                ['name' => 'button_2_url', 'type' => 'url', 'label' => 'Secondary Button URL', 'required' => false, 'placeholder' => '#'],
                ['name' => 'background_color', 'type' => 'color', 'label' => 'Background Color', 'required' => false, 'default' => '#0039e3'],
                ['name' => 'text_color', 'type' => 'color', 'label' => 'Text Color', 'required' => false, 'default' => '#ffffff'],
                ['name' => 'background_image', 'type' => 'image', 'label' => 'Background Image (Optional)', 'required' => false, 'placeholder' => 'crafto/images/cta-bg.jpg', 'help' => 'Leave empty to use solid color background']
            ],
            'is_active' => true
        ]);

        $this->command->info('Crafto section templates created/updated successfully!');
    }

    private function createOrUpdateTemplate($data)
    {
        $template = SectionTemplate::where('blade_view', $data['blade_view'])->first();

        if ($template) {
            $template->update($data);
            $this->command->info("✓ Updated: {$data['name']} (blade_view: {$data['blade_view']})");
        } else {
            SectionTemplate::create($data);
            $this->command->info("✓ Created: {$data['name']} (blade_view: {$data['blade_view']})");
        }
    }
}
