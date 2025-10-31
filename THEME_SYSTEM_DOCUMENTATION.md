# Theme System - SimpleCMS Documentation

## 📋 Overview

SimpleCMS memiliki sistem **Theme Management** yang memungkinkan Anda untuk:
- ✅ Mengelola multiple themes
- ✅ Mengaktifkan theme secara global
- ✅ Assign theme ke individual pages
- ✅ Upload theme baru dengan thumbnail
- ✅ Delete theme yang tidak digunakan

## 🎨 Current Status

### Installed Themes

Saat ini ada **3 themes** ter-install:

1. **Default Theme** (✓ ACTIVE)
   - Version: 1.0.0
   - Author: SimpleCMS Team
   - Pages using: 0

2. **Business Theme** (Inactive)
   - Version: 1.0.0
   - Author: SimpleCMS Team
   - Pages using: 0

3. **Creative Theme** (Inactive)
   - Version: 1.0.0
   - Author: SimpleCMS Team
   - Pages using: 0

### Pages Status
- Total Pages: 3
- Pages dengan theme assigned: 0
- Pages tanpa theme: 3 (akan menggunakan active theme)

## 🔧 How It Works

### 1. **Theme Activation**

**Konsep**: Hanya **1 theme** yang bisa aktif pada satu waktu. Theme aktif menjadi **default theme** untuk semua pages yang tidak memiliki theme assignment khusus.

**Cara Aktivasi**:
1. Login ke Admin Panel (`/admin`)
2. Navigate to **Appearance → Themes**
3. Klik tombol **"Activate"** pada theme yang diinginkan
4. Konfirmasi aktivasi
5. System akan otomatis:
   - Deactivate semua theme lainnya
   - Activate theme yang dipilih

**Code Implementation**:
```php
// Model: Theme.php (Line 39-46)
public function activate()
{
    // Deactivate all other themes
    static::where('id', '!=', $this->id)->update(['is_active' => false]);

    // Activate this theme
    $this->update(['is_active' => true]);
}
```

### 2. **Theme Assignment to Pages**

**Konsep**: Setiap page bisa memilih theme sendiri atau menggunakan active theme (default).

**Struktur Database**:
```sql
pages table:
- id
- title
- theme_id (nullable) ← foreign key to themes table
- ...
```

**Behavior**:
- Jika `theme_id` = NULL → Gunakan active theme
- Jika `theme_id` = [ID] → Gunakan theme spesifik tersebut

**Cara Assign Theme ke Page**:
1. Edit page di Admin Panel
2. Pilih theme dari dropdown (jika ada)
3. Save page

**Note**: Saat ini page edit form **belum menampilkan dropdown theme selector**. Perlu enhancement.

### 3. **Upload New Theme**

**Cara Upload**:
1. Navigate to **Appearance → Themes**
2. Klik tombol **"Upload New Theme"**
3. Modal akan muncul dengan form:
   - **Theme Name** (required) - nama unik untuk theme
   - **Description** (optional) - deskripsi theme
   - **Thumbnail** (optional) - screenshot theme (800x600px recommended)
4. Klik **"Upload Theme"**

**Validation**:
- Name: Required, max 255 chars, must be unique
- Thumbnail: Image only (jpeg, png, jpg, gif), max 2MB
- Version: Optional, max 20 chars
- Author: Optional, max 255 chars

**Storage**:
- Thumbnails disimpan di: `storage/app/public/themes/`
- Accessible via: `storage/themes/[filename]`

### 4. **Delete Theme**

**Restrictions**:
- ❌ **Tidak bisa delete active theme** - harus activate theme lain dulu
- ❌ **Tidak bisa delete theme yang masih digunakan pages** - harus reassign/delete pages dulu

**Cara Delete**:
1. Pastikan theme **tidak aktif** (activate theme lain dulu jika perlu)
2. Pastikan **tidak ada pages** yang menggunakan theme tersebut
3. Klik tombol **"Delete"** pada theme card
4. Konfirmasi delete

## 📁 File Structure

### Core Files

```
simplecms/
├── app/
│   ├── Models/
│   │   └── Theme.php                    # Theme model
│   └── Http/Controllers/Admin/
│       └── ThemeController.php          # Theme management controller
├── resources/views/admin/themes/
│   └── index.blade.php                  # Theme listing & upload UI
├── database/migrations/
│   └── xxxx_create_themes_table.php     # Themes table schema
└── routes/
    └── web.php                          # Theme routes
```

### Database Schema

**Table: `themes`**
```sql
CREATE TABLE themes (
    id BIGINT PRIMARY KEY,
    name VARCHAR(255) UNIQUE,
    slug VARCHAR(255),
    description TEXT,
    thumbnail VARCHAR(255),
    author VARCHAR(255),
    version VARCHAR(20),
    is_active BOOLEAN DEFAULT false,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

**Table: `pages`**
```sql
CREATE TABLE pages (
    id BIGINT PRIMARY KEY,
    title VARCHAR(255),
    theme_id BIGINT NULL,  -- Foreign key to themes
    ...
    FOREIGN KEY (theme_id) REFERENCES themes(id)
);
```

## 🚀 Routes

| Method | Route | Action | Description |
|--------|-------|--------|-------------|
| GET | `/admin/themes` | `index` | List all themes |
| POST | `/admin/themes` | `store` | Upload new theme |
| PUT | `/admin/themes/{id}/activate` | `activate` | Activate a theme |
| GET | `/admin/themes/{id}` | `show` | View theme details |
| DELETE | `/admin/themes/{id}` | `destroy` | Delete theme |

## 💡 Use Cases

### Use Case 1: Switch Global Theme
**Scenario**: Anda ingin mengubah tampilan seluruh website.

**Steps**:
1. Navigate to **Appearance → Themes**
2. Preview themes yang tersedia
3. Klik **"Activate"** pada theme pilihan
4. Semua pages yang tidak memiliki theme khusus akan otomatis menggunakan theme baru

### Use Case 2: Different Theme for Landing Page
**Scenario**: Homepage menggunakan "Creative Theme", halaman lain menggunakan "Default Theme".

**Steps**:
1. Activate **"Default Theme"** sebagai global theme
2. Edit **Homepage**
3. Assign **"Creative Theme"** ke homepage (via theme_id)
4. Result:
   - Homepage: Creative Theme
   - Other pages: Default Theme

### Use Case 3: Upload Custom Theme
**Scenario**: Anda membuat theme sendiri dan ingin upload.

**Steps**:
1. Klik **"Upload New Theme"**
2. Isi form:
   - Name: "My Custom Theme"
   - Description: "Custom theme for my brand"
   - Upload thumbnail (screenshot)
3. Save
4. Activate theme baru jika ingin langsung digunakan

## ⚠️ Current Limitations

### 1. **Theme Upload Hanya Metadata**
**Issue**: Upload theme hanya menyimpan **nama dan thumbnail**, tidak upload actual theme files (CSS, JS, Blade templates).

**Impact**:
- Theme yang diupload tidak bisa langsung digunakan
- Hanya berfungsi sebagai **placeholder/reference**
- Actual theme files harus di-deploy manual ke server

**Recommended Enhancement**:
- Support upload theme package (ZIP file)
- Extract ke `resources/views/themes/[theme-name]/`
- Register theme templates otomatis

### 2. **No Theme Selector di Page Edit Form**
**Issue**: Saat edit page, tidak ada dropdown untuk memilih theme.

**Impact**:
- Theme hanya bisa di-assign via database manual
- User tidak bisa switch theme per-page via UI

**Recommended Enhancement**:
- Tambah dropdown "Select Theme" di page edit form
- Options: [Use Active Theme (Default)] + list of available themes
- Save ke `pages.theme_id`

### 3. **No Theme Preview/Customization**
**Issue**: Tidak ada cara untuk preview theme sebelum activate.

**Impact**:
- Harus activate dulu baru bisa lihat hasilnya
- Risky jika theme tidak kompatibel

**Recommended Enhancement**:
- Tambah **"Preview"** button
- Open theme preview di new tab/modal
- Show sample page dengan theme tersebut

### 4. **No Theme Settings/Configuration**
**Issue**: Theme tidak bisa dikustomisasi via UI (colors, fonts, layouts, etc).

**Impact**:
- Semua customization harus edit code manual
- Tidak user-friendly untuk non-developers

**Recommended Enhancement**:
- Tambah theme settings panel
- Support customizable options (colors, logo, footer text, etc)
- Save to `theme_settings` table

## 🔄 How Themes Integrate with Pages

### Current Implementation

**Page Rendering Flow**:
```
1. User requests page: /about
2. PageController loads Page model
3. Check page.theme_id:
   - If NULL → Use Theme::active()->first()
   - If set → Use Theme::find(theme_id)
4. Load theme's layout file
5. Render page with theme
```

**Example Code**:
```php
// PageController.php
public function show($slug)
{
    $page = Page::where('slug', $slug)->with('theme')->firstOrFail();

    // Determine which theme to use
    $theme = $page->theme ?? Theme::active()->first();

    // Load theme layout
    $layout = $theme ? "themes.{$theme->slug}.layout" : 'frontend.layouts.app';

    return view($layout, compact('page'));
}
```

**Note**: Implementasi sebenarnya mungkin berbeda. Perlu cek PageController untuk detail lengkap.

## 📝 Recommendations for Improvement

### Priority 1: Theme Selector in Page Edit
**Why**: Users need UI to assign themes to pages.

**Implementation**:
1. Edit `resources/views/admin/pages/edit.blade.php`
2. Add dropdown field:
```blade
<div class="form-group">
    <label>Theme</label>
    <select name="theme_id" class="form-control">
        <option value="">Use Active Theme (Default)</option>
        @foreach(\App\Models\Theme::all() as $theme)
            <option value="{{ $theme->id }}"
                {{ old('theme_id', $page->theme_id) == $theme->id ? 'selected' : '' }}>
                {{ $theme->name }}
            </option>
        @endforeach
    </select>
</div>
```

### Priority 2: Theme Package Upload
**Why**: Enable uploading complete theme with templates.

**Implementation**:
1. Accept ZIP file upload
2. Extract to `resources/views/themes/[slug]/`
3. Validate required files (layout.blade.php, etc)
4. Register theme in database

### Priority 3: Theme Preview
**Why**: Safe way to test themes before activating.

**Implementation**:
1. Add "Preview" button
2. Route: `/admin/themes/{id}/preview`
3. Load sample page with selected theme
4. Add "Activate" button in preview

### Priority 4: Theme Customizer
**Why**: Allow non-developers to customize themes.

**Implementation**:
1. Create `theme_settings` table
2. Add "Customize" button per theme
3. Visual customizer panel (colors, fonts, etc)
4. Save settings and apply to theme

## 🎯 Summary

### ✅ What Works Now
- Theme listing & management UI
- Activate/deactivate themes
- Upload theme metadata (name, description, thumbnail)
- Delete unused themes
- Database relationships (themes ↔ pages)

### ⚠️ What Needs Enhancement
- Theme package upload (actual files)
- Theme selector in page edit form
- Theme preview functionality
- Theme customization panel
- Better theme documentation

### 🚀 How to Use (Current State)
1. **View Themes**: Navigate to `/admin/themes`
2. **Activate Theme**: Click "Activate" on desired theme
3. **Upload Theme**: Click "Upload New Theme", fill form (metadata only)
4. **Delete Theme**: Ensure not active & no pages using it, then click "Delete"
5. **Assign to Page**: Currently requires manual database update to `pages.theme_id`

---

**Last Updated**: October 31, 2025
**Version**: 1.0
