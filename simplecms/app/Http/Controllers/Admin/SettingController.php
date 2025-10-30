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
            // Get all settings grouped by their group field
            $settings = Setting::all()->groupBy('group');

            // Define available groups and their labels
            $groups = [
                'general' => 'General Settings',
                'site' => 'Site Information',
                'social' => 'Social Media',
                'seo' => 'SEO Settings',
                'email' => 'Email Configuration',
                'appearance' => 'Appearance',
            ];

            return view('admin.settings.index', compact('settings', 'groups'));
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

            if (empty($settingsData)) {
                return back()->with('error', 'No settings to update.');
            }

            $updatedCount = 0;

            // Loop through each setting and update
            foreach ($settingsData as $key => $value) {
                // Skip file inputs and empty arrays
                if ($request->hasFile($key) || (is_array($value) && empty($value))) {
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
