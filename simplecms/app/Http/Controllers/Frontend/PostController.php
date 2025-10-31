<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;

/**
 * Frontend Post Controller
 *
 * Handles displaying blog posts, post listings, and category pages
 */
class PostController extends Controller
{
    /**
     * Display a listing of published posts
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        try {
            $posts = Post::published()
                ->with(['author', 'category'])
                ->latest('published_at')
                ->paginate(12);

            // Get all categories with post counts
            $categories = Category::withCount(['posts' => function ($query) {
                $query->published();
            }])
            ->having('posts_count', '>', 0)
            ->get();

            // Get active theme to determine which view to use
            $activeTheme = \App\Models\Theme::where('is_active', true)->first();
            $viewName = 'frontend.posts.index';

            // If Crafto or Creative theme is active, use default view for now
            // In the future, we can create theme-specific views

            return view($viewName, compact('posts', 'categories'));
        } catch (\Exception $e) {
            \Log::error('Posts listing error: ' . $e->getMessage());
            abort(500, 'Unable to load posts');
        }
    }

    /**
     * Display the specified post by slug
     *
     * @param  string  $slug
     * @return \Illuminate\View\View
     */
    public function show($slug)
    {
        try {
            // Find the post by slug
            $post = Post::where('slug', $slug)
                ->published()
                ->with(['author', 'category'])
                ->firstOrFail();

            // Increment views count
            $post->incrementViewsCount();

            // Get related posts from same category
            $relatedPosts = Post::published()
                ->where('category_id', $post->category_id)
                ->where('id', '!=', $post->id)
                ->latest('published_at')
                ->take(3)
                ->get();

            return view('frontend.posts.show', compact('post', 'relatedPosts'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            abort(404, 'Post not found');
        } catch (\Exception $e) {
            \Log::error('Post display error: ' . $e->getMessage());
            abort(500, 'Unable to load post');
        }
    }

    /**
     * Search posts by query
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function search(Request $request)
    {
        try {
            $query = $request->input('q', '');

            $posts = Post::published()
                ->where(function($q) use ($query) {
                    $q->where('title', 'LIKE', "%{$query}%")
                      ->orWhere('excerpt', 'LIKE', "%{$query}%")
                      ->orWhere('content', 'LIKE', "%{$query}%");
                })
                ->with(['author', 'category'])
                ->latest('published_at')
                ->paginate(12);

            $categories = Category::withCount(['posts' => function ($q) {
                $q->published();
            }])
            ->having('posts_count', '>', 0)
            ->get();

            return view('frontend.blog.index', compact('posts', 'categories', 'query'));
        } catch (\Exception $e) {
            \Log::error('Search error: ' . $e->getMessage());
            return back()->with('error', 'Search failed');
        }
    }

    /**
     * Display posts by category
     *
     * @param  string  $slug
     * @return \Illuminate\View\View
     */
    public function category($slug)
    {
        try {
            // Find the category by slug
            $category = Category::where('slug', $slug)->firstOrFail();

            // Get posts in this category
            $posts = Post::published()
                ->where('category_id', $category->id)
                ->with(['author', 'category'])
                ->latest('published_at')
                ->paginate(12);

            // Get all categories with post counts
            $categories = Category::withCount(['posts' => function ($query) {
                $query->published();
            }])
            ->having('posts_count', '>', 0)
            ->get();

            return view('frontend.posts.category', compact('posts', 'category', 'categories'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            abort(404, 'Category not found');
        } catch (\Exception $e) {
            \Log::error('Category posts error: ' . $e->getMessage());
            abort(500, 'Unable to load category posts');
        }
    }
}
