<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Page;
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

            // Get the active theme
            $theme = $page->theme;

            // Determine which view to use based on theme
            $viewName = $theme ? "themes.{$theme->slug}.page" : 'frontend.page';

            // Check if the theme view exists, fallback to default
            if ($theme && !view()->exists($viewName)) {
                $viewName = 'frontend.page';
            }

            return view($viewName, compact('page'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            abort(404, 'Page not found');
        } catch (\Exception $e) {
            \Log::error('Page display error: ' . $e->getMessage());
            abort(500, 'Unable to load page');
        }
    }
}
