# Theme Content Management System

## Overview
Sistem manajemen konten untuk mengisi dan mengedit konten homepage setiap theme secara dinamis melalui database.

## Database Structure

### Table: `theme_settings`
```sql
- id (primary key)
- theme_id (foreign key → themes)
- key (string) - e.g., 'hero_title', 'service_1_title'
- value (text) - konten yang ditampilkan
- type (string) - 'text', 'textarea', 'image', 'url', 'number'
- group (string) - 'hero', 'services', 'features', 'cta'
- order (integer) - urutan tampilan
- created_at, updated_at
```

### Unique Constraint
- Kombinasi `theme_id` + `key` harus unik
- Setiap theme memiliki set settings sendiri

## Cara Mengisi/Edit Konten Theme

### Metode 1: Via Database Seeder (Recommended untuk Default Content)

```php
// File: database/seeders/ThemeContentSeeder.php

$settings = [
    ['key' => 'hero_title', 'value' => 'Your Title Here', 'type' => 'text', 'group' => 'hero', 'order' => 1],
    ['key' => 'hero_description', 'value' => 'Your description...', 'type' => 'textarea', 'group' => 'hero', 'order' => 2],
    // ... dan seterusnya
];

foreach ($settings as $setting) {
    ThemeSetting::updateOrCreate(
        ['theme_id' => $themeId, 'key' => $setting['key']],
        [
            'value' => $setting['value'],
            'type' => $setting['type'],
            'group' => $setting['group'],
            'order' => $setting['order'],
        ]
    );
}
```

**Run seeder:**
```bash
php artisan db:seed --class=ThemeContentSeeder
```

### Metode 2: Via Tinker (Quick Edit)

```bash
php artisan tinker
```

```php
// Update single setting
ThemeSetting::where('theme_id', 7)  // ID theme Business Professional
    ->where('key', 'hero_slide_1_title')
    ->update(['value' => 'Judul Baru Anda']);

// Create new setting
ThemeSetting::create([
    'theme_id' => 7,
    'key' => 'new_section_title',
    'value' => 'Konten Baru',
    'type' => 'text',
    'group' => 'new_section',
    'order' => 100,
]);

// View all settings for a theme
ThemeSetting::where('theme_id', 7)->get(['key', 'value']);
```

### Metode 3: Direct Database Query

```sql
-- Update existing content
UPDATE theme_settings
SET value = 'Judul Baru'
WHERE theme_id = 7 AND `key` = 'hero_slide_1_title';

-- Insert new content
INSERT INTO theme_settings (theme_id, `key`, value, type, `group`, `order`, created_at, updated_at)
VALUES (7, 'new_key', 'New Value', 'text', 'section', 100, NOW(), NOW());

-- View all content for Business theme
SELECT `key`, value, `group`
FROM theme_settings
WHERE theme_id = 7
ORDER BY `order`;
```

## Available Content Keys untuk Business Professional Theme

### Hero Slider (3 slides)
```
hero_slide_1_subtitle    - "Best solutions for your business"
hero_slide_1_title       - "Agency for your great business"
hero_slide_1_highlight   - "great business" (kata yang akan di-bold)

hero_slide_2_subtitle    - "Delivering beautiful digital products"
hero_slide_2_title       - "Shape the future of marketing"
hero_slide_2_highlight   - "marketing"

hero_slide_3_subtitle    - "Business strategies and top ideas"
hero_slide_3_title       - "Provide solutions to small business"
hero_slide_3_highlight   - "small business"
```

### Services Section
```
services_section_title      - "Business services"
services_section_subtitle   - "What we offer"

service_1_title            - "Business planning"
service_1_description      - "Build strategies that grow..."

service_2_title            - "Market research"
service_2_description      - "Understand your market..."

service_3_title            - "Digital solutions"
service_3_description      - "Transform your business..."
```

### CTA Section
```
cta_title          - "Ready to grow your business with us?"
cta_button_text    - "Get started now"
```

## Cara Menggunakan di View

### Di Blade Template

```blade
{{-- Basic usage with fallback --}}
<h1>{{ $themeSettings['hero_title'] ?? 'Default Title' }}</h1>

{{-- With highlight/bold text --}}
<h1>{!! str_replace(
    $themeSettings['hero_highlight'] ?? 'highlight',
    '<span class="fw-600">' . ($themeSettings['hero_highlight'] ?? 'highlight') . '</span>',
    $themeSettings['hero_title'] ?? 'Default Title'
) !!}</h1>

{{-- Textarea content --}}
<p>{{ $themeSettings['hero_description'] ?? 'Default description' }}</p>
```

### Variable yang Tersedia

Semua views frontend akan mendapat variable berikut:
- `$themeSettings` - Array key-value settings untuk active theme
- `$activeTheme` - Object Theme yang sedang aktif
- `$settings` - Global site settings
- `$primaryMenu` - Menu utama
- `$footerMenu` - Menu footer

## Contoh Implementasi Lengkap

### 1. Buat Settings untuk Theme Baru

```php
// File: database/seeders/MyCustomThemeSeeder.php

$themeId = Theme::where('slug', 'my-custom-theme')->first()->id;

$settings = [
    // Hero Section
    [
        'key' => 'hero_title',
        'value' => 'Welcome to Our Website',
        'type' => 'text',
        'group' => 'hero',
        'order' => 1
    ],
    [
        'key' => 'hero_subtitle',
        'value' => 'We provide amazing services',
        'type' => 'text',
        'group' => 'hero',
        'order' => 2
    ],
    [
        'key' => 'hero_button_text',
        'value' => 'Learn More',
        'type' => 'text',
        'group' => 'hero',
        'order' => 3
    ],
    [
        'key' => 'hero_button_url',
        'value' => '/contact',
        'type' => 'url',
        'group' => 'hero',
        'order' => 4
    ],

    // Features Section
    [
        'key' => 'features_title',
        'value' => 'Our Features',
        'type' => 'text',
        'group' => 'features',
        'order' => 10
    ],
    // ... dll
];

foreach ($settings as $setting) {
    $setting['theme_id'] = $themeId;
    ThemeSetting::updateOrCreate(
        ['theme_id' => $themeId, 'key' => $setting['key']],
        $setting
    );
}
```

### 2. Gunakan di View

```blade
@extends('frontend.layouts.crafto')

@section('content')
<section>
    <div class="container">
        <h1>{{ $themeSettings['hero_title'] ?? 'Default Title' }}</h1>
        <p>{{ $themeSettings['hero_subtitle'] ?? 'Default Subtitle' }}</p>
        <a href="{{ $themeSettings['hero_button_url'] ?? '#' }}" class="btn btn-primary">
            {{ $themeSettings['hero_button_text'] ?? 'Click Here' }}
        </a>
    </div>
</section>

<section>
    <h2>{{ $themeSettings['features_title'] ?? 'Features' }}</h2>
    <!-- Features content -->
</section>
@endsection
```

## Tips & Best Practices

### 1. Gunakan Naming Convention yang Konsisten
```
{section}_{element}_{property}

Contoh:
- hero_title
- hero_subtitle
- service_1_title
- service_1_description
- footer_copyright_text
```

### 2. Selalu Gunakan Fallback
```blade
{{-- Good ✓ --}}
{{ $themeSettings['title'] ?? 'Default Title' }}

{{-- Bad ✗ --}}
{{ $themeSettings['title'] }}
```

### 3. Gunakan Groups untuk Organisasi
- `hero` - Hero section
- `services` - Services/Features section
- `about` - About section
- `testimonials` - Testimonials
- `cta` - Call to Action
- `footer` - Footer content

### 4. Gunakan Type yang Tepat
- `text` - Text pendek (judul, nama, dll)
- `textarea` - Text panjang (deskripsi, paragraf)
- `image` - Path gambar
- `url` - Link/URL
- `number` - Angka

### 5. Order dengan Kelipatan 10
Gunakan kelipatan 10 untuk `order` agar mudah menyisipkan item baru:
```
order: 10, 20, 30, 40...
```
Jika perlu sisipkan, bisa gunakan: 15, 25, 35...

## Helper Functions (Optional)

Buat helper untuk memudahkan akses:

```php
// File: app/Helpers/ThemeHelper.php

function theme_setting($key, $default = '') {
    global $themeSettings;
    return $themeSettings[$key] ?? $default;
}

function theme_image($key, $default = '') {
    $path = theme_setting($key, $default);
    return $path ? asset('storage/' . $path) : asset($default);
}
```

Gunakan di view:
```blade
<h1>{{ theme_setting('hero_title', 'Welcome') }}</h1>
<img src="{{ theme_image('hero_bg', 'images/default-bg.jpg') }}">
```

## Future Enhancements

### Admin Panel Integration (Coming Soon)

Rencana fitur admin panel untuk edit theme settings:

1. **Theme Settings Page**
   - Route: `admin/themes/{theme}/settings`
   - Form input untuk edit semua settings
   - Upload gambar
   - Preview changes

2. **Visual Editor**
   - Edit content langsung di preview
   - Drag & drop images
   - Real-time preview

3. **Import/Export Settings**
   - Export settings to JSON
   - Import from other themes
   - Duplicate theme with settings

### Example Admin Controller

```php
// File: app/Http/Controllers/Admin/ThemeSettingController.php

public function edit($themeId)
{
    $theme = Theme::findOrFail($themeId);
    $settings = ThemeSetting::getGroupedForTheme($themeId);

    return view('admin.themes.settings', compact('theme', 'settings'));
}

public function update(Request $request, $themeId)
{
    $validated = $request->validate([
        'settings' => 'required|array',
        'settings.*.value' => 'required',
    ]);

    foreach ($validated['settings'] as $key => $value) {
        ThemeSetting::updateOrCreate(
            ['theme_id' => $themeId, 'key' => $key],
            ['value' => $value]
        );
    }

    return redirect()->back()->with('success', 'Theme settings updated!');
}
```

## Quick Reference

### Get All Settings for Theme
```php
$settings = ThemeSetting::getForTheme($themeId);
```

### Get Grouped Settings
```php
$grouped = ThemeSetting::getGroupedForTheme($themeId);
// Returns: ['hero' => [...], 'services' => [...], ...]
```

### Update Single Setting
```php
ThemeSetting::where('theme_id', $themeId)
    ->where('key', 'hero_title')
    ->update(['value' => 'New Title']);
```

### Create New Setting
```php
ThemeSetting::create([
    'theme_id' => $themeId,
    'key' => 'new_setting',
    'value' => 'Value',
    'type' => 'text',
    'group' => 'section',
    'order' => 100,
]);
```

## Troubleshooting

### Settings tidak muncul
1. Cek apakah theme sudah aktif
2. Cek apakah settings ada di database
3. Clear cache: `php artisan cache:clear`
4. Clear view cache: `php artisan view:clear`

### Error "Undefined array key"
- Gunakan null coalescing operator: `??`
- Contoh: `{{ $themeSettings['key'] ?? 'default' }}`

### Settings tidak update
1. Cek theme_id benar
2. Cek key name (case sensitive)
3. Run seeder lagi jika perlu

---

**Created**: 2025-10-31
**Status**: ✅ Production Ready
**Version**: 1.0

Untuk pertanyaan atau bantuan, silakan cek kode di:
- Model: `app/Models/ThemeSetting.php`
- Seeder: `database/seeders/ThemeContentSeeder.php`
- Provider: `app/Providers/AppServiceProvider.php`
- Views: `resources/views/home-*.blade.php`
