<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SectionTemplate;

class UpdateSectionTemplateFieldsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hero Simple Template Fields
        $heroSimpleFields = [
            ['name' => 'title', 'type' => 'text', 'label' => 'Title', 'required' => true, 'placeholder' => 'Enter hero title'],
            ['name' => 'subtitle', 'type' => 'text', 'label' => 'Subtitle', 'required' => false, 'placeholder' => 'Enter subtitle (optional)'],
            ['name' => 'description', 'type' => 'textarea', 'label' => 'Description', 'required' => false, 'rows' => 3, 'placeholder' => 'Enter description'],
            ['name' => 'button_text', 'type' => 'text', 'label' => 'Button Text', 'required' => false, 'placeholder' => 'Get Started'],
            ['name' => 'button_url', 'type' => 'url', 'label' => 'Button URL', 'required' => false, 'placeholder' => '#'],
            ['name' => 'button_2_text', 'type' => 'text', 'label' => 'Second Button Text', 'required' => false, 'placeholder' => 'Learn More (optional)'],
            ['name' => 'button_2_url', 'type' => 'url', 'label' => 'Second Button URL', 'required' => false, 'placeholder' => '#'],
            ['name' => 'background_image', 'type' => 'image', 'label' => 'Background Image', 'required' => false, 'placeholder' => 'crafto/images/demo-corporate-slider-bg.jpg'],
            ['name' => 'text_color', 'type' => 'color', 'label' => 'Text Color', 'required' => false, 'default' => '#ffffff'],
            ['name' => 'overlay_opacity', 'type' => 'select', 'label' => 'Overlay Opacity', 'required' => false, 'options' => [
                ['value' => '0', 'label' => 'None'],
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
        ];

        // CTA Banner Template Fields
        $ctaBannerFields = [
            ['name' => 'title', 'type' => 'text', 'label' => 'Title', 'required' => true, 'placeholder' => 'Ready to get started?'],
            ['name' => 'subtitle', 'type' => 'text', 'label' => 'Subtitle', 'required' => false],
            ['name' => 'description', 'type' => 'textarea', 'label' => 'Description', 'required' => false, 'rows' => 2],
            ['name' => 'button_text', 'type' => 'text', 'label' => 'Button Text', 'required' => false, 'placeholder' => 'Get Started'],
            ['name' => 'button_url', 'type' => 'url', 'label' => 'Button URL', 'required' => false, 'placeholder' => '#'],
            ['name' => 'button_2_text', 'type' => 'text', 'label' => 'Second Button Text', 'required' => false],
            ['name' => 'button_2_url', 'type' => 'url', 'label' => 'Second Button URL', 'required' => false],
            ['name' => 'background_color', 'type' => 'color', 'label' => 'Background Color', 'required' => false, 'default' => '#0039e3'],
            ['name' => 'background_image', 'type' => 'image', 'label' => 'Background Image', 'required' => false],
            ['name' => 'text_color', 'type' => 'color', 'label' => 'Text Color', 'required' => false, 'default' => '#ffffff'],
            ['name' => 'layout', 'type' => 'select', 'label' => 'Layout Style', 'required' => false, 'options' => [
                ['value' => 'centered', 'label' => 'Centered'],
                ['value' => 'left-right', 'label' => 'Left-Right Split'],
                ['value' => 'with-image', 'label' => 'With Image']
            ]]
        ];

        // Update templates with fields definitions
        $this->updateTemplateBySlug('hero-1', $heroSimpleFields);
        $this->updateTemplateBySlug('features-1', $heroSimpleFields); // Using same for now
        $this->updateTemplateBySlug('cta-1', $ctaBannerFields);

        $this->command->info('Section template fields updated successfully!');
    }

    private function updateTemplateBySlug($slug, $fields)
    {
        // Try different search patterns
        $template = SectionTemplate::where('blade_view', 'like', "%{$slug}%")
            ->orWhere('name', 'like', "%{$slug}%")
            ->first();

        if (!$template) {
            // Check by exact slug or name
            $slugParts = explode('-', $slug);
            $searchTerm = '%' . implode('%', $slugParts) . '%';
            $template = SectionTemplate::where('blade_view', 'like', $searchTerm)
                ->orWhere('name', 'like', $searchTerm)
                ->first();
        }

        if ($template) {
            $template->update(['fields' => $fields]);
            $this->command->info("Updated fields for: {$template->name} (ID: {$template->id})");
        } else {
            $this->command->warn("Template not found for slug: {$slug}");
            $this->command->warn("Available templates:");
            SectionTemplate::all()->each(function($t) {
                $this->command->line("  - ID {$t->id}: {$t->name} ({$t->blade_view})");
            });
        }
    }
}
