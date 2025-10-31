<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Theme;
use App\Models\ThemeSetting;
use Illuminate\Http\Request;

class ThemeSettingController extends Controller
{
    /**
     * Show the form for editing theme settings
     */
    public function edit($themeId)
    {
        $theme = Theme::findOrFail($themeId);

        // Get settings grouped by group
        $settingsGrouped = ThemeSetting::where('theme_id', $themeId)
            ->orderBy('group')
            ->orderBy('order')
            ->get()
            ->groupBy('group');

        // Get all settings as flat array for easier access
        $settings = ThemeSetting::getForTheme($themeId);

        return view('admin.themes.settings', compact('theme', 'settingsGrouped', 'settings'));
    }

    /**
     * Update theme settings
     */
    public function update(Request $request, $themeId)
    {
        $theme = Theme::findOrFail($themeId);

        $validated = $request->validate([
            'settings' => 'required|array',
        ]);

        $updatedCount = 0;

        foreach ($validated['settings'] as $key => $value) {
            // Skip empty values
            if ($value === null || $value === '') {
                continue;
            }

            $setting = ThemeSetting::updateOrCreate(
                [
                    'theme_id' => $themeId,
                    'key' => $key,
                ],
                [
                    'value' => $value,
                ]
            );

            $updatedCount++;
        }

        return redirect()
            ->route('admin.themes.settings.edit', $themeId)
            ->with('success', "Successfully updated {$updatedCount} settings for {$theme->name}!");
    }

    /**
     * Create new setting for theme
     */
    public function store(Request $request, $themeId)
    {
        $theme = Theme::findOrFail($themeId);

        $validated = $request->validate([
            'key' => 'required|string|max:255',
            'value' => 'required|string',
            'type' => 'required|in:text,textarea,image,url,number',
            'group' => 'required|string|max:255',
            'order' => 'nullable|integer',
        ]);

        // Check if key already exists
        $exists = ThemeSetting::where('theme_id', $themeId)
            ->where('key', $validated['key'])
            ->exists();

        if ($exists) {
            return redirect()
                ->back()
                ->withErrors(['key' => 'This key already exists for this theme.'])
                ->withInput();
        }

        ThemeSetting::create([
            'theme_id' => $themeId,
            'key' => $validated['key'],
            'value' => $validated['value'],
            'type' => $validated['type'],
            'group' => $validated['group'],
            'order' => $validated['order'] ?? 0,
        ]);

        return redirect()
            ->route('admin.themes.settings.edit', $themeId)
            ->with('success', 'New setting added successfully!');
    }

    /**
     * Delete a setting
     */
    public function destroy($themeId, $settingId)
    {
        $setting = ThemeSetting::where('theme_id', $themeId)
            ->where('id', $settingId)
            ->firstOrFail();

        $setting->delete();

        return redirect()
            ->route('admin.themes.settings.edit', $themeId)
            ->with('success', 'Setting deleted successfully!');
    }
}
