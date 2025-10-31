<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

/**
 * Admin Setting Controller
 *
 * Handles system settings management with grouping
 */
class SettingController extends Controller
{
    /**
     * Display settings form grouped by group
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        try {
            // Get all settings and create a flat key-value array for easy access in views
            $settingsFlat = Setting::all()->pluck('value', 'key')->toArray();

            // Also get settings grouped by their group field for organized display
            $settingsGrouped = Setting::all()->groupBy('group');

            // Define available groups and their labels
            $groups = [
                'general' => 'General Settings',
                'site' => 'Site Information',
                'social' => 'Social Media',
                'seo' => 'SEO Settings',
                'email' => 'Email Configuration',
                'appearance' => 'Appearance',
            ];

            return view('admin.settings.index', [
                'settings' => $settingsFlat,
                'settingsGrouped' => $settingsGrouped,
                'groups' => $groups
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading settings: ' . $e->getMessage());
        }
    }

    /**
     * Update settings
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        try {
            // Get all input except CSRF and method tokens
            $inputs = $request->except(['_token', '_method']);

            // Check if settings are in array format or flat format
            $settingsData = $request->has('settings') ? $request->settings : $inputs;

            if (empty($settingsData) && !$request->hasAny(['site_logo', 'site_favicon'])) {
                return back()->with('error', 'No settings to update.');
            }

            $updatedCount = 0;

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

                    // Update or create setting
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

            // Loop through each setting and update
            foreach ($settingsData as $key => $value) {
                // Skip file inputs and empty arrays
                if (in_array($key, $fileFields) || (is_array($value) && empty($value))) {
                    continue;
                }

                $setting = Setting::where('key', $key)->first();

                if ($setting) {
                    // Convert boolean strings to actual booleans
                    if ($setting->type === 'boolean') {
                        $value = filter_var($value, FILTER_VALIDATE_BOOLEAN);
                    }

                    // Update the setting
                    $setting->update(['value' => $value]);
                    $updatedCount++;
                } else {
                    // Create new setting if it doesn't exist
                    Setting::create([
                        'key' => $key,
                        'value' => $value,
                        'type' => 'text',
                        'group' => 'general',
                    ]);
                    $updatedCount++;
                }
            }

            return redirect()->route('admin.settings.index')
                ->with('success', "Settings updated successfully! ({$updatedCount} setting(s) updated)");
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error updating settings: ' . $e->getMessage());
        }
    }

    /**
     * Show settings by group
     *
     * @param  string  $group
     * @return \Illuminate\View\View
     */
    public function showGroup($group)
    {
        try {
            $settings = Setting::where('group', $group)->get();

            $groupLabels = [
                'general' => 'General Settings',
                'site' => 'Site Information',
                'social' => 'Social Media',
                'seo' => 'SEO Settings',
                'email' => 'Email Configuration',
                'appearance' => 'Appearance',
            ];

            $groupLabel = $groupLabels[$group] ?? ucfirst($group);

            return view('admin.settings.group', compact('settings', 'group', 'groupLabel'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading settings group: ' . $e->getMessage());
        }
    }
}
