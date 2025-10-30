<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Admin Category Controller
 *
 * Handles CRUD operations for blog post categories
 */
class CategoryController extends Controller
{
    /**
     * Display a listing of categories with post count
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        try {
            $categories = Category::withCount('posts')
                ->latest()
                ->paginate(15);

            return view('admin.categories.index', compact('categories'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading categories: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new category
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        try {
            return view('admin.categories.create');
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading category form: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created category
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate request
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'slug' => 'nullable|string|max:255|unique:categories,slug',
            'description' => 'nullable|string|max:1000',
        ]);

        try {
            // Generate slug if not provided
            if (empty($validated['slug'])) {
                $validated['slug'] = Str::slug($validated['name']);
            }

            // Create category
            $category = Category::create([
                'name' => $validated['name'],
                'slug' => $validated['slug'],
                'description' => $validated['description'] ?? null,
            ]);

            return redirect()->route('admin.categories.index')
                ->with('success', 'Category created successfully!');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error creating category: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified category
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        try {
            $category = Category::withCount('posts')
                ->with(['posts' => function ($query) {
                    $query->latest()->take(10);
                }])
                ->findOrFail($id);

            return view('admin.categories.show', compact('category'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading category: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the category
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        try {
            $category = Category::findOrFail($id);

            return view('admin.categories.edit', compact('category'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading category for editing: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified category
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Validate request
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
            'slug' => 'nullable|string|max:255|unique:categories,slug,' . $id,
            'description' => 'nullable|string|max:1000',
        ]);

        try {
            $category = Category::findOrFail($id);

            // Generate slug if not provided
            if (empty($validated['slug'])) {
                $validated['slug'] = Str::slug($validated['name']);
            }

            // Update category
            $category->update([
                'name' => $validated['name'],
                'slug' => $validated['slug'],
                'description' => $validated['description'] ?? null,
            ]);

            return redirect()->route('admin.categories.index')
                ->with('success', 'Category updated successfully!');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error updating category: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified category
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        try {
            $category = Category::findOrFail($id);

            // Check if category has posts
            if ($category->posts()->count() > 0) {
                return back()->with('error', 'Cannot delete category with existing posts. Please reassign or delete posts first.');
            }

            // Delete the category
            $category->delete();

            return redirect()->route('admin.categories.index')
                ->with('success', 'Category deleted successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting category: ' . $e->getMessage());
        }
    }
}
