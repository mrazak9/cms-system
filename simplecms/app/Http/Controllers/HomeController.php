<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Page;
use App\Models\Theme;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the homepage
     */
    public function index()
    {
        // Get latest posts
        $posts = Post::published()->latest()->take(6)->get();

        // Get the active theme
        $activeTheme = Theme::where('is_active', true)->first();

        // Determine which view to use based on active theme
        $viewName = 'home';

        // Map theme slugs to their respective home views
        if ($activeTheme) {
            $themeViewMap = [
                'crafto' => 'home-crafto',
                'creative' => 'home-crafto',           // Creative theme uses Crafto layout
                'business' => 'home-business',         // Business Professional theme
                'corporate' => 'home-corporate',       // Corporate Modern theme
                'digital-agency' => 'home-digital-agency', // Digital Agency theme
                'restaurant' => 'home-crafto',         // Restaurant theme (placeholder)
                'business-classic' => 'home',          // Business Classic uses default layout
                'default' => 'home',                   // Default theme uses default layout
            ];

            // Use the mapped view if it exists, otherwise use default
            if (isset($themeViewMap[$activeTheme->slug])) {
                $viewName = $themeViewMap[$activeTheme->slug];
            }

            // Alternatively, check if a specific theme view exists
            $themeSpecificView = "home-{$activeTheme->slug}";
            if (view()->exists($themeSpecificView)) {
                $viewName = $themeSpecificView;
            }
        }

        return view($viewName, compact('posts'));
    }
}
