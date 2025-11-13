<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

/**
 * Admin Post Controller
 *
 * Handles CRUD operations for blog posts with category relationships
 */
class PostController extends Controller
{
    /**
     * Constructor - Apply permission middleware
     */
    public function __construct()
    {
        // View permissions
        $this->middleware('permission:posts.view')->only(['index', 'show']);

        // Create permissions
        $this->middleware('permission:posts.create')->only(['create', 'store']);

        // Edit permissions (will check ownership in Phase 2)
        $this->middleware('permission:posts.edit')->only(['edit', 'update']);

        // Delete permissions (will check ownership in Phase 2)
        $this->middleware('permission:posts.delete')->only(['destroy']);
    }

    /**
     * Display a listing of posts with pagination
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        try {
            $query = Post::with(['author', 'category']);

            // Filter by category
            if ($request->filled('category')) {
                $query->where('category_id', $request->category);
            }

            // Filter by workflow status
            if ($request->filled('workflow_status')) {
                $query->where('workflow_status', $request->workflow_status);
            }

            // Search
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('content', 'like', "%{$search}%")
                      ->orWhere('excerpt', 'like', "%{$search}%");
                });
            }

            $posts = $query->latest()->paginate(15)->withQueryString();
            $categories = Category::withCount('posts')->get();

            return view('admin.posts.index', compact('posts', 'categories'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading posts: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new post
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        try {
            $categories = Category::all();
            $tags = Tag::orderBy('name')->get();

            return view('admin.posts.create', compact('categories', 'tags'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading post form: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created post
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate request
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:posts,slug',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category_id' => 'nullable|exists:categories,id',
            'is_published' => 'boolean',
            'published_at' => 'nullable|date',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        try {
            // Generate slug if not provided
            if (empty($validated['slug'])) {
                $validated['slug'] = Str::slug($validated['title']);
            }

            // Handle featured image upload
            $featuredImagePath = null;
            if ($request->hasFile('featured_image')) {
                $featuredImagePath = $request->file('featured_image')
                    ->store('posts', 'public');
            }

            // Set published_at if not provided and is_published is true
            $publishedAt = $validated['published_at'] ?? null;
            if ($request->is_published && !$publishedAt) {
                $publishedAt = now();
            }

            // Create post
            $post = Post::create([
                'title' => $validated['title'],
                'slug' => $validated['slug'],
                'excerpt' => $validated['excerpt'] ?? null,
                'content' => $validated['content'],
                'featured_image' => $featuredImagePath,
                'category_id' => $validated['category_id'],
                'author_id' => auth()->id(),
                'is_published' => $request->is_published ?? false,
                'published_at' => $publishedAt,
                'meta_description' => $validated['meta_description'] ?? null,
                'meta_keywords' => $validated['meta_keywords'] ?? null,
                'views_count' => 0,
            ]);

            // Sync tags
            if ($request->has('tags')) {
                $post->tags()->sync($request->tags);
            }

            return redirect()->route('admin.posts.index')
                ->with('success', 'Post created successfully!');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error creating post: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified post
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        try {
            $post = Post::with(['author', 'category'])
                ->findOrFail($id);

            return view('admin.posts.show', compact('post'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading post: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the post
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        try {
            $post = Post::with('tags')->findOrFail($id);

            // Check authorization - only post owner or users with edit-all permission
            $this->authorize('update', $post);

            $categories = Category::all();
            $tags = Tag::orderBy('name')->get();

            return view('admin.posts.edit', compact('post', 'categories', 'tags'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading post for editing: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified post
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Validate request
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:posts,slug,' . $id,
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category_id' => 'nullable|exists:categories,id',
            'is_published' => 'boolean',
            'published_at' => 'nullable|date',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
            'remove_featured_image' => 'boolean',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        try {
            $post = Post::findOrFail($id);

            // Check authorization - only post owner or users with edit-all permission
            $this->authorize('update', $post);

            // Generate slug if not provided
            if (empty($validated['slug'])) {
                $validated['slug'] = Str::slug($validated['title']);
            }

            // Handle featured image
            $featuredImagePath = $post->featured_image;

            // Remove old image if requested
            if ($request->remove_featured_image && $featuredImagePath) {
                Storage::disk('public')->delete($featuredImagePath);
                $featuredImagePath = null;
            }

            // Upload new image if provided
            if ($request->hasFile('featured_image')) {
                // Delete old image if exists
                if ($featuredImagePath) {
                    Storage::disk('public')->delete($featuredImagePath);
                }
                $featuredImagePath = $request->file('featured_image')
                    ->store('posts', 'public');
            }

            // Set published_at if not provided and is_published is true
            $publishedAt = $validated['published_at'] ?? $post->published_at;
            if ($request->is_published && !$publishedAt) {
                $publishedAt = now();
            }

            // Update post
            $post->update([
                'title' => $validated['title'],
                'slug' => $validated['slug'],
                'excerpt' => $validated['excerpt'] ?? null,
                'content' => $validated['content'],
                'featured_image' => $featuredImagePath,
                'category_id' => $validated['category_id'],
                'is_published' => $request->is_published ?? false,
                'published_at' => $publishedAt,
                'meta_description' => $validated['meta_description'] ?? null,
                'meta_keywords' => $validated['meta_keywords'] ?? null,
            ]);

            // Sync tags
            if ($request->has('tags')) {
                $post->tags()->sync($request->tags);
            } else {
                // If no tags selected, detach all
                $post->tags()->sync([]);
            }

            return redirect()->route('admin.posts.index')
                ->with('success', 'Post updated successfully!');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error updating post: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified post
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        try {
            $post = Post::findOrFail($id);

            // Check authorization - only post owner or users with delete-all permission
            $this->authorize('delete', $post);

            // Delete featured image if exists
            if ($post->featured_image) {
                Storage::disk('public')->delete($post->featured_image);
            }

            // Delete the post
            $post->delete();

            return redirect()->route('admin.posts.index')
                ->with('success', 'Post deleted successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting post: ' . $e->getMessage());
        }
    }
}
