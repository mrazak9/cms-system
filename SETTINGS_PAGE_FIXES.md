# Settings Page Fixes

## Problem Summary
User reported that after saving settings in the admin panel:
1. Success notification appeared
2. But form fields became empty again (data not persisting)
3. Photo uploads needed verification

## Root Causes Identified

### 1. Controller Data Structure Mismatch
**File:** `simplecms/app/Http/Controllers/Admin/SettingController.php`

**Problem:** Controller was sending settings grouped by `group` field:
```php
$settings = Setting::all()->groupBy('group');
```

This created a structure like:
```php
[
    'general' => Collection of Setting models,
    'social' => Collection of Setting models,
]
```

But the view was trying to access settings as a flat array:
```php
$settings['site_name']  // ❌ This would fail
```

**Fix:** Changed controller to provide both flat and grouped data:
```php
// Create flat key-value array for easy access
$settingsFlat = Setting::all()->pluck('value', 'key')->toArray();

// Also provide grouped data if needed
$settingsGrouped = Setting::all()->groupBy('group');

return view('admin.settings.index', [
    'settings' => $settingsFlat,        // ✅ Now accessible as $settings['site_name']
    'settingsGrouped' => $settingsGrouped,
    'groups' => $groups
]);
```

### 2. Inconsistent Field Naming in View
**File:** `simplecms/resources/views/admin/settings/index.blade.php`

**Problem:** Some fields used `settings[]` array prefix while others didn't:
```blade
name="settings[site_name]"  ❌ Inconsistent
name="admin_email"          ❌ Inconsistent
name="timezone"             ❌ Inconsistent
```

**Fix:** Removed all `settings[]` prefixes, using direct field names:
```blade
name="site_name"            ✅ Consistent
name="mail_from_address"    ✅ Consistent
name="timezone"             ✅ Consistent
```

### 3. Wrong Database Field Names
**File:** `simplecms/resources/views/admin/settings/index.blade.php`

**Problems:** Many fields didn't match actual database column names:

| View Field (❌ WRONG) | Database Key (✅ CORRECT) |
|----------------------|---------------------------|
| `admin_email` | `mail_from_address` |
| `posts_per_page` | `items_per_page` |
| `facebook_url` | `facebook` |
| `twitter_url` | `twitter` |
| `instagram_url` | `instagram` |
| `linkedin_url` | `linkedin` |
| `youtube_url` | `youtube` |
| `google_analytics_id` | `google_analytics` |

**Fix:** Updated all field names to match database keys.

### 4. Missing WhatsApp Field
**Database:** Has `whatsapp` field in social group
**View:** Field was missing

**Fix:** Added WhatsApp field to social media section:
```blade
<div class="form-group">
    <label for="whatsapp">
        <i class="fab fa-whatsapp"></i> WhatsApp Number
    </label>
    <input type="text" name="whatsapp" value="{{ old('whatsapp', $settings['whatsapp'] ?? '') }}">
</div>
```

### 5. File Upload Not Handled
**File:** `simplecms/app/Http/Controllers/Admin/SettingController.php`

**Problem:** Controller skipped file uploads entirely:
```php
if ($request->hasFile($key) || ...) {
    continue;  // ❌ Files were being skipped
}
```

**Fix:** Added proper file upload handling:
```php
// Handle file uploads first
$fileFields = ['site_logo', 'site_favicon'];
foreach ($fileFields as $fileField) {
    if ($request->hasFile($fileField)) {
        $file = $request->file($fileField);

        // Validate file
        $request->validate([
            $fileField => 'image|mimes:jpeg,png,jpg,gif,ico|max:2048'
        ]);

        // Store file in public/storage
        $path = $file->store('images', 'public');

        // Update setting
        $setting = Setting::where('key', $fileField)->first();
        if ($setting) {
            // Delete old file if exists
            if ($setting->value && \Storage::disk('public')->exists($setting->value)) {
                \Storage::disk('public')->delete($setting->value);
            }
            $setting->update(['value' => $path]);
        } else {
            Setting::create([
                'key' => $fileField,
                'value' => $path,
                'type' => 'text',
                'group' => 'general',
            ]);
        }
        $updatedCount++;
    }
}
```

### 6. Image Display Path Issue
**File:** `simplecms/resources/views/admin/settings/index.blade.php`

**Problem:** View always used `asset('storage/' . $settings['site_logo'])` but existing data had `/assets/logo.png` format.

**Fix:** Added logic to handle both old and new path formats:
```blade
@php
    $logoUrl = str_starts_with($settings['site_logo'], '/')
        ? asset($settings['site_logo'])          // Old format: /assets/logo.png
        : asset('storage/' . $settings['site_logo']); // New format: images/logo123.png
@endphp
<img src="{{ $logoUrl }}" alt="Site Logo">
```

## Files Modified

1. **simplecms/app/Http/Controllers/Admin/SettingController.php**
   - Changed `index()` method to provide flat settings array
   - Enhanced `update()` method with file upload handling

2. **simplecms/resources/views/admin/settings/index.blade.php**
   - Removed `settings[]` prefix from all field names
   - Fixed field names to match database keys:
     - `admin_email` → `mail_from_address`
     - `posts_per_page` → `items_per_page`
     - `*_url` → `*` (for social media fields)
     - `google_analytics_id` → `google_analytics`
   - Added missing `whatsapp` field
   - Fixed image display logic for logo and favicon

## Testing

Created test scripts:
- `simplecms/check-settings.php` - Lists all current settings in database
- `simplecms/test-settings-form.php` - Verifies settings load correctly for the form

### Test Results
All 30 settings in database are now correctly accessible:
- ✅ General settings (site_name, site_tagline, site_description, timezone, etc.)
- ✅ Email settings (mail_from_address, mail_from_name)
- ✅ Social media (facebook, twitter, instagram, linkedin, youtube, whatsapp)
- ✅ SEO settings (meta_description, meta_keywords, google_analytics, etc.)
- ✅ Contact settings (contact_email, contact_phone, contact_address)
- ✅ File uploads (site_logo, site_favicon)

## Expected Behavior After Fixes

1. ✅ Form loads with all current settings populated
2. ✅ User can edit any field
3. ✅ Clicking "Save" persists all changes to database
4. ✅ Form reloads with updated values (no longer empty)
5. ✅ File uploads work for logo and favicon
6. ✅ Old files are deleted when new ones are uploaded
7. ✅ Success message shows correct number of updated settings

## Next Steps

1. Test the settings page in browser
2. Try updating various fields to confirm persistence
3. Test file uploads for logo and favicon
4. Verify images display correctly after upload
