<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Post;
use App\Models\Setting;
use Illuminate\Http\Request;

/**
 * Frontend Home Controller
 *
 * Handles the homepage display - either a custom page or latest posts
 */
class HomeController extends Controller
{
    /**
     * Display the homepage
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        try {
            // Check if there's a homepage page set
            $homepage = Page::published()
                ->homepage()
                ->with(['sections.sectionTemplate', 'theme'])
                ->first();

            if ($homepage) {
                // Display the custom homepage
                return view('frontend.home', compact('homepage'));
            }

            // Otherwise, show latest blog posts as homepage
            $posts = Post::published()
                ->with(['author', 'category'])
                ->latest('published_at')
                ->paginate(10);

            // Get featured posts (top 3 by views)
            $featuredPosts = Post::published()
                ->orderBy('views_count', 'desc')
                ->take(3)
                ->get();

            // Get site settings for display
            $siteName = Setting::get('site_name', 'SimpleCMS');
            $siteDescription = Setting::get('site_description', 'A simple content management system');

            // Get latest posts for the homepage
            $latestPosts = $posts;
            $homepage = null; // No custom homepage

            return view('frontend.home', compact('posts', 'featuredPosts', 'siteName', 'siteDescription', 'latestPosts', 'homepage'));
        } catch (\Exception $e) {
            // Log the error and show a friendly error page
            \Log::error('Homepage error: ' . $e->getMessage());
            abort(500, 'Unable to load homepage');
        }
    }
}
