# Logo & Favicon Fix - App Layout (Home & Blog)

## Issue Report
User melaporkan: **Logo belum muncul di landing page home dan blog**

## Root Cause Analysis

### Ditemukan 2 Layout Frontend yang Berbeda:

#### 1. **Layout Crafto** (`frontend.layouts.crafto`)
- ✅ Sudah diperbaiki sebelumnya
- Digunakan oleh: Halaman dengan template Crafto
- File: `resources/views/frontend/layouts/crafto.blade.php`
- File: `resources/views/frontend/layouts/crafto-header.blade.php`

#### 2. **Layout App** (`frontend.layouts.app`) ❌ BELUM DIPERBAIKI
- ❌ Logo dan Favicon masih hardcoded
- Digunakan oleh:
  - **Home page**: `resources/views/home.blade.php`
  - **Blog index**: `resources/views/frontend/posts/index.blade.php`
  - **Blog show**: `resources/views/frontend/posts/show.blade.php`
  - **Blog category**: `resources/views/frontend/posts/category.blade.php`

### Masalah Spesifik di Layout App:

**File: `resources/views/frontend/layouts/app.blade.php`**
- ❌ **Line 28**: Favicon hardcoded `asset('favicon.ico')`
- ❌ Tidak membaca `site_favicon` dari database

**File: `resources/views/frontend/layouts/header.blade.php`**
- ❌ **Line 7**: Logo menggunakan `asset($settings['site_logo'])`
- ❌ Tidak handle format path lama (`/assets/logo.png`) dan baru (`images/logo.png`)
- ❌ Akan error jika path dimulai dengan `/` karena akan jadi `asset('/assets/logo.png')` yang salah

## Solutions Applied

### ✅ Fix 1: App Layout Favicon

**File**: `resources/views/frontend/layouts/app.blade.php` (Line 27-36)

**Sebelum:**
```blade
{{-- Favicon --}}
<link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
```

**Sesudah:**
```blade
{{-- Favicon --}}
@php
    $faviconPath = $settings['site_favicon'] ?? '';
    if ($faviconPath) {
        $faviconUrl = str_starts_with($faviconPath, '/')
            ? asset($faviconPath)                // Old: /assets/favicon.ico
            : asset('storage/' . $faviconPath);  // New: images/favicon123.png
    } else {
        $faviconUrl = asset('favicon.ico');      // Fallback
    }
@endphp
<link rel="icon" type="image/x-icon" href="{{ $faviconUrl }}">
```

### ✅ Fix 2: App Header Logo

**File**: `resources/views/frontend/layouts/header.blade.php` (Line 4-15)

**Sebelum:**
```blade
{{-- Logo/Brand --}}
<a class="navbar-brand" href="{{ url('/') }}">
    @if(isset($settings['site_logo']) && $settings['site_logo'])
        <img src="{{ asset($settings['site_logo']) }}" alt="..." height="40">
    @else
        {{ $settings['site_name'] ?? 'SimpleCMS' }}
    @endif
</a>
```

**Sesudah:**
```blade
{{-- Logo/Brand --}}
<a class="navbar-brand" href="{{ url('/') }}">
    @if(isset($settings['site_logo']) && $settings['site_logo'])
        @php
            $logoPath = $settings['site_logo'];
            $logoUrl = str_starts_with($logoPath, '/')
                ? asset($logoPath)                // Old: /assets/logo.png
                : asset('storage/' . $logoPath);  // New: images/logo123.png
        @endphp
        <img src="{{ $logoUrl }}" alt="{{ $settings['site_name'] ?? 'SimpleCMS' }}"
             height="40" style="object-fit: contain;">
    @else
        {{ $settings['site_name'] ?? 'SimpleCMS' }}
    @endif
</a>
```

**Perubahan:**
- ✅ Handle 2 format path (lama & baru)
- ✅ Tambah `object-fit: contain` untuk responsive
- ✅ Fallback ke site_name jika tidak ada logo

## Pages Affected (Now Fixed)

### ✅ Home Page
- **Route**: `/`
- **View**: `resources/views/home.blade.php`
- **Layout**: `@extends('frontend.layouts.app')`
- **Status**: Logo & Favicon sekarang DINAMIS

### ✅ Blog Index
- **Route**: `/blog`
- **View**: `resources/views/frontend/posts/index.blade.php`
- **Layout**: `@extends('frontend.layouts.app')`
- **Status**: Logo & Favicon sekarang DINAMIS

### ✅ Blog Single Post
- **Route**: `/blog/{slug}`
- **View**: `resources/views/frontend/posts/show.blade.php`
- **Layout**: `@extends('frontend.layouts.app')`
- **Status**: Logo & Favicon sekarang DINAMIS

### ✅ Blog Category
- **Route**: `/blog/category/{slug}`
- **View**: `resources/views/frontend/posts/category.blade.php`
- **Layout**: `@extends('frontend.layouts.app')`
- **Status**: Logo & Favicon sekarang DINAMIS

## Complete Implementation Status

### Frontend Layouts

| Layout | Favicon | Logo | Status |
|--------|---------|------|--------|
| **Crafto** (`frontend.layouts.crafto`) | ✅ Dinamis | ✅ Dinamis | Fixed (sebelumnya) |
| **App** (`frontend.layouts.app`) | ✅ Dinamis | ✅ Dinamis | Fixed (sekarang) |

### Admin Panel

| Component | Favicon | Logo | Status |
|-----------|---------|------|--------|
| **Admin Layout** (`admin.layouts.app`) | ✅ Dinamis | N/A | Fixed (sebelumnya) |
| **Admin Sidebar** (`admin.layouts.sidebar`) | N/A | ✅ Dinamis | Fixed (sebelumnya) |

## Testing Checklist

### ✅ Home Page (`/`)
- [ ] Buka `http://yourdomain.com/`
- [ ] Periksa **tab browser** → Favicon custom harus muncul
- [ ] Periksa **navbar logo** → Logo INABA Portal harus tampil (height 40px)
- [ ] Test responsive → Logo harus tampil dengan baik di mobile

### ✅ Blog Pages
- [ ] Buka `http://yourdomain.com/blog`
- [ ] Periksa **tab browser** → Favicon custom harus muncul
- [ ] Periksa **navbar logo** → Logo INABA Portal harus tampil
- [ ] Klik salah satu artikel
- [ ] Periksa logo tetap tampil di halaman artikel
- [ ] Klik kategori
- [ ] Periksa logo tetap tampil di halaman kategori

### ✅ Browser Cache
Jika logo/favicon tidak muncul:
1. **Hard refresh**: `Ctrl + F5` (Windows) atau `Cmd + Shift + R` (Mac)
2. **Clear browser cache**
3. **Test di Incognito window**

## Current Logo & Favicon

Berdasarkan database saat ini:

```
Site Name: INABA Portal
Logo:      images/1IGvCdqsAgj5CTWb16YadwS9rGFqCRFBLH9lqwTy.png (13KB)
Favicon:   images/yPXScIxPtUsE4Ar5cIZrGgqVPHdlFvaBbtgsD2Tg.png (3.2KB)
Format:    NEW (storage upload)
URL Logo:  http://domain.com/storage/images/1IGvCdqsAgj5CTWb16YadwS9rGFqCRFBLH9lqwTy.png
```

## Files Modified (This Fix)

1. ✅ `simplecms/resources/views/frontend/layouts/app.blade.php` (Line 27-36)
2. ✅ `simplecms/resources/views/frontend/layouts/header.blade.php` (Line 4-15)

## Files Modified (Previous Fix)

1. ✅ `simplecms/resources/views/frontend/layouts/crafto.blade.php`
2. ✅ `simplecms/resources/views/frontend/layouts/crafto-header.blade.php`
3. ✅ `simplecms/resources/views/admin/layouts/app.blade.php`
4. ✅ `simplecms/resources/views/admin/layouts/sidebar.blade.php`

## Summary

**Masalah**: Logo tidak muncul di home dan blog karena menggunakan layout berbeda (`app` bukan `crafto`)

**Solusi**: Terapkan logika path handling yang sama ke layout `app` seperti yang sudah diterapkan di layout `crafto`

**Hasil**:
- ✅ Semua halaman frontend (home, blog, crafto pages) sekarang menampilkan logo & favicon dinamis
- ✅ Semua halaman admin sekarang menampilkan logo & favicon dinamis
- ✅ Support 2 format path (lama & baru)
- ✅ Fallback ke default jika tidak ada setting
