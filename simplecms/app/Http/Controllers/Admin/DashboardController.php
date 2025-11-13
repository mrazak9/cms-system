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
            $user = auth()->user();

            // Base statistics for all roles
            $stats = [
                'total_pages' => Page::count(),
                'published_pages' => Page::where('is_published', true)->count(),
                'draft_pages' => Page::where('is_published', false)->count(),
                'total_posts' => Post::count(),
                'published_posts' => Post::where('is_published', true)->count(),
                'draft_posts' => Post::where('is_published', false)->count(),
                'total_categories' => Category::count(),
                'total_users' => User::count(),
                'total_media' => Media::count(),
            ];

            // Additional stats for Authors (their own content)
            if ($user->hasRole('author')) {
                $stats['my_posts'] = Post::where('author_id', $user->id)->count();
                $stats['my_published_posts'] = Post::where('author_id', $user->id)
                    ->where('is_published', true)
                    ->count();
                $stats['my_draft_posts'] = Post::where('author_id', $user->id)
                    ->where('is_published', false)
                    ->count();
                $stats['my_media'] = Media::where('uploaded_by', $user->id)->count();
            }

            // Activity statistics (last 7 days)
            $stats['posts_this_week'] = Post::where('created_at', '>=', now()->subDays(7))->count();
            $stats['users_this_week'] = User::where('created_at', '>=', now()->subDays(7))->count();

            // Get recent posts (last 5)
            $recentPostsQuery = Post::with(['author', 'category'])->latest();

            // Authors only see their own posts
            if ($user->hasRole('author') && !$user->can('posts.edit-all')) {
                $recentPostsQuery->where('author_id', $user->id);
            }

            $recentPosts = $recentPostsQuery->take(5)->get();

            // Get recent pages (last 5) - only for users with pages.view permission
            $recentPages = collect();
            if ($user->can('pages.view')) {
                $recentPages = Page::latest()->take(5)->get();
            }

            // Get popular posts (by views, last 5)
            $popularPostsQuery = Post::where('is_published', true)
                ->orderBy('views_count', 'desc');

            if ($user->hasRole('author') && !$user->can('posts.edit-all')) {
                $popularPostsQuery->where('author_id', $user->id);
            }

            $popularPosts = $popularPostsQuery->take(5)->get();

            // Get recent users (last 5) - only for admins
            $recentUsers = collect();
            if ($user->can('users.view')) {
                $recentUsers = User::with('roles')
                    ->latest()
                    ->take(5)
                    ->get();
            }

            // Get recent media (last 5)
            $recentMediaQuery = Media::with('uploader')->latest();

            if ($user->hasRole('author') && !$user->can('media.edit-all')) {
                $recentMediaQuery->where('uploaded_by', $user->id);
            }

            $recentMedia = $recentMediaQuery->take(5)->get();

            // Chart data for posts over last 7 days
            $chartData = [];
            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $count = Post::whereDate('created_at', $date->toDateString())->count();
                $chartData['labels'][] = $date->format('M d');
                $chartData['data'][] = $count;
            }

            return view('admin.dashboard', compact(
                'stats',
                'recentPosts',
                'recentPages',
                'popularPosts',
                'recentUsers',
                'recentMedia',
                'chartData'
            ));
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading dashboard: ' . $e->getMessage());
        }
    }
}
