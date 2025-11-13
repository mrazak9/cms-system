<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use App\Models\SearchLog;
use App\Models\PageView;
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
                ->with(['author', 'category', 'tags'])
                ->firstOrFail();

            // Increment views count
            $post->incrementViewsCount();

            // Log page view for analytics
            PageView::logView($post);

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
     * Search posts with advanced filters
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function search(Request $request)
    {
        try {
            $searchQuery = $request->input('q', '');
            $categoryFilter = $request->input('category');
            $tagFilter = $request->input('tag');
            $dateFrom = $request->input('date_from');
            $dateTo = $request->input('date_to');
            $sortBy = $request->input('sort_by', 'latest'); // latest, popular, oldest

            // Build query
            $postsQuery = Post::published()
                ->with(['author', 'category', 'tags']);

            // Apply search query
            if (!empty($searchQuery)) {
                $postsQuery->where(function($q) use ($searchQuery) {
                    $q->where('title', 'LIKE', "%{$searchQuery}%")
                      ->orWhere('excerpt', 'LIKE', "%{$searchQuery}%")
                      ->orWhere('content', 'LIKE', "%{$searchQuery}%")
                      ->orWhere('meta_description', 'LIKE', "%{$searchQuery}%")
                      ->orWhere('meta_keywords', 'LIKE', "%{$searchQuery}%");
                });
            }

            // Apply category filter
            if ($categoryFilter) {
                $postsQuery->where('category_id', $categoryFilter);
            }

            // Apply tag filter
            if ($tagFilter) {
                $postsQuery->whereHas('tags', function($q) use ($tagFilter) {
                    $q->where('tags.id', $tagFilter);
                });
            }

            // Apply date range filter
            if ($dateFrom) {
                $postsQuery->whereDate('published_at', '>=', $dateFrom);
            }
            if ($dateTo) {
                $postsQuery->whereDate('published_at', '<=', $dateTo);
            }

            // Apply sorting
            switch ($sortBy) {
                case 'popular':
                    $postsQuery->orderBy('views_count', 'desc');
                    break;
                case 'oldest':
                    $postsQuery->oldest('published_at');
                    break;
                case 'latest':
                default:
                    $postsQuery->latest('published_at');
                    break;
            }

            $posts = $postsQuery->paginate(12)->appends($request->except('page'));
            $resultsCount = $posts->total();

            // Log search if query is not empty
            if (!empty($searchQuery)) {
                SearchLog::logSearch($searchQuery, $resultsCount, [
                    'category' => $categoryFilter,
                    'tag' => $tagFilter,
                    'date_from' => $dateFrom,
                    'date_to' => $dateTo,
                    'sort_by' => $sortBy,
                ]);
            }

            // Get all categories for filter
            $categories = Category::withCount(['posts' => function ($q) {
                $q->published();
            }])
            ->having('posts_count', '>', 0)
            ->get();

            // Get all tags for filter
            $tags = Tag::withCount(['posts' => function ($q) {
                $q->published();
            }])
            ->having('posts_count', '>', 0)
            ->orderBy('name')
            ->get();

            return view('frontend.search.results', compact(
                'posts',
                'categories',
                'tags',
                'searchQuery',
                'categoryFilter',
                'tagFilter',
                'dateFrom',
                'dateTo',
                'sortBy',
                'resultsCount'
            ));
        } catch (\Exception $e) {
            \Log::error('Search error: ' . $e->getMessage());
            return back()->with('error', 'Search failed');
        }
    }

    /**
     * Get search suggestions (AJAX)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchSuggestions(Request $request)
    {
        try {
            $query = $request->input('q', '');

            if (strlen($query) < 2) {
                return response()->json([]);
            }

            $suggestions = Post::published()
                ->where('title', 'LIKE', "%{$query}%")
                ->select('id', 'title', 'slug', 'featured_image')
                ->limit(5)
                ->get()
                ->map(function($post) {
                    return [
                        'title' => $post->title,
                        'url' => route('blog.show', $post->slug),
                        'image' => $post->featured_image ? asset('storage/' . $post->featured_image) : null,
                    ];
                });

            return response()->json($suggestions);
        } catch (\Exception $e) {
            return response()->json([]);
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

    /**
     * Display posts by tag
     *
     * @param  string  $slug
     * @return \Illuminate\View\View
     */
    public function tag($slug)
    {
        try {
            // Find the tag by slug
            $tag = Tag::where('slug', $slug)->firstOrFail();

            // Get posts with this tag
            $posts = Post::published()
                ->whereHas('tags', function ($query) use ($tag) {
                    $query->where('tags.id', $tag->id);
                })
                ->with(['author', 'category', 'tags'])
                ->latest('published_at')
                ->paginate(12);

            // Get all categories with post counts
            $categories = Category::withCount(['posts' => function ($query) {
                $query->published();
            }])
            ->having('posts_count', '>', 0)
            ->get();

            // Get popular tags (tags with most posts)
            $popularTags = Tag::withCount(['posts' => function ($query) {
                $query->published();
            }])
            ->having('posts_count', '>', 0)
            ->orderBy('posts_count', 'desc')
            ->take(10)
            ->get();

            return view('frontend.posts.tag', compact('posts', 'tag', 'categories', 'popularTags'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            abort(404, 'Tag not found');
        } catch (\Exception $e) {
            \Log::error('Tag posts error: ' . $e->getMessage());
            abort(500, 'Unable to load tag posts');
        }
    }
}
