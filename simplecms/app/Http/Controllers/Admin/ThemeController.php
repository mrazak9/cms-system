<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Theme;
use Illuminate\Http\Request;

/**
 * Admin Theme Controller
 *
 * Handles theme listing and activation
 */
class ThemeController extends Controller
{
    /**
     * Display a listing of themes
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        try {
            $themes = Theme::withCount('pages')
                ->orderBy('is_active', 'desc')
                ->get();

            return view('admin.themes.index', compact('themes'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading themes: ' . $e->getMessage());
        }
    }

    /**
     * Store a new theme (upload theme package)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate request
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:themes,name',
            'description' => 'nullable|string',
            'version' => 'nullable|string|max:20',
            'author' => 'nullable|string|max:255',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $thumbnailPath = null;

            // Handle thumbnail upload
            if ($request->hasFile('thumbnail')) {
                $thumbnailPath = $request->file('thumbnail')->store('themes', 'public');
            }

            // Create the theme
            $theme = Theme::create([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'version' => $validated['version'] ?? '1.0.0',
                'author' => $validated['author'] ?? null,
                'thumbnail' => $thumbnailPath,
                'is_active' => false,
            ]);

            return redirect()->route('admin.themes.index')
                ->with('success', "Theme '{$theme->name}' has been created successfully!");
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error creating theme: ' . $e->getMessage());
        }
    }

    /**
     * Activate a theme
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function activate($id)
    {
        try {
            $theme = Theme::findOrFail($id);

            // Use the activate method from the Theme model
            // This will deactivate all other themes and activate this one
            $theme->activate();

            return redirect()->route('admin.themes.index')
                ->with('success', "Theme '{$theme->name}' has been activated successfully!");
        } catch (\Exception $e) {
            return back()->with('error', 'Error activating theme: ' . $e->getMessage());
        }
    }

    /**
     * Show the specified theme details
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        try {
            $theme = Theme::with('pages')->findOrFail($id);

            return view('admin.themes.show', compact('theme'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading theme: ' . $e->getMessage());
        }
    }

    /**
     * Delete a theme
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        try {
            $theme = Theme::findOrFail($id);

            // Prevent deleting active theme
            if ($theme->is_active) {
                return back()->with('error', 'Cannot delete an active theme. Please activate another theme first.');
            }

            // Check if theme has pages
            if ($theme->pages()->count() > 0) {
                return back()->with('error', 'Cannot delete theme because it is being used by ' . $theme->pages()->count() . ' page(s). Please reassign or delete those pages first.');
            }

            $themeName = $theme->name;
            $theme->delete();

            return redirect()->route('admin.themes.index')
                ->with('success', "Theme '{$themeName}' has been deleted successfully!");
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting theme: ' . $e->getMessage());
        }
    }
}
