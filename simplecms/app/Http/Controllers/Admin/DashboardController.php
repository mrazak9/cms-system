<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Post;
use App\Models\Category;
use App\Models\User;
use App\Models\Media;
use Illuminate\Http\Request;

/**
 * Admin Dashboard Controller
 *
 * Handles the admin dashboard display with statistics and overview
 */
class DashboardController extends Controller
{
    /**
     * Display the admin dashboard with statistics
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        try {
            // Gather statistics for dashboard
            $stats = [
                'total_pages' => Page::count(),
                'published_pages' => Page::published()->count(),
                'total_posts' => Post::count(),
                'published_posts' => Post::published()->count(),
                'total_categories' => Category::count(),
                'total_users' => User::count(),
                'total_media' => Media::count(),
            ];

            // Get recent posts (last 5)
            $recentPosts = Post::with(['author', 'category'])
                ->latest()
                ->take(5)
                ->get();

            // Get recent pages (last 5)
            $recentPages = Page::with('creator')
                ->latest()
                ->take(5)
                ->get();

            // Get popular posts (by views, last 5)
            $popularPosts = Post::published()
                ->orderBy('views_count', 'desc')
                ->take(5)
                ->get();

            return view('admin.dashboard', compact(
                'stats',
                'recentPosts',
                'recentPages',
                'popularPosts'
            ));
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading dashboard: ' . $e->getMessage());
        }
    }
}
