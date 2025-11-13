<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\PageView;
use Illuminate\Http\Request;

/**
 * Frontend Page Controller
 *
 * Handles displaying individual pages with their sections
 */
class PageController extends Controller
{
    /**
     * Display the specified page by slug
     *
     * @param  string  $slug
     * @return \Illuminate\View\View
     */
    public function show($slug)
    {
        try {
            // Find the page by slug
            $page = Page::where('slug', $slug)
                ->published()
                ->with(['sections' => function ($query) {
                    $query->visible()->ordered()->with('sectionTemplate');
                }, 'theme'])
                ->firstOrFail();

            // Log page view for analytics
            PageView::logView($page);

            // Get the theme for this page (specific theme or active theme)
            $theme = $page->theme;

            // If no specific theme assigned, use the active theme
            if (!$theme) {
                $theme = \App\Models\Theme::where('is_active', true)->first();
            }

            // Determine which view to use based on theme
            // For now, we'll use the default view since theme-specific views aren't created yet
            // Future: create views at resources/views/themes/{theme_slug}/page.blade.php
            $viewName = 'frontend.page';

            // Check for theme-specific view (if exists)
            if ($theme) {
                $themeViewName = "themes.{$theme->slug}.page";
                if (view()->exists($themeViewName)) {
                    $viewName = $themeViewName;
                }
            }

            return view($viewName, compact('page', 'theme'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            abort(404, 'Page not found');
        } catch (\Exception $e) {
            \Log::error('Page display error: ' . $e->getMessage());
            abort(500, 'Unable to load page');
        }
    }
}
