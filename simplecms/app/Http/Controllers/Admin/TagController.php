<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TagController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:tags.view')->only(['index', 'show']);
        $this->middleware('permission:tags.create')->only(['create', 'store']);
        $this->middleware('permission:tags.edit')->only(['edit', 'update']);
        $this->middleware('permission:tags.delete')->only(['destroy']);
    }

    /**
     * Display a listing of the tags.
     */
    public function index(Request $request)
    {
        $query = Tag::withCount('posts');

        // Search
        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%");
            });
        }

        $tags = $query->orderBy('name')->paginate(20);

        // Statistics
        $stats = [
            'total' => Tag::count(),
            'with_posts' => Tag::has('posts')->count(),
            'empty' => Tag::doesntHave('posts')->count(),
        ];

        return view('admin.tags.index', compact('tags', 'stats'));
    }

    /**
     * Show the form for creating a new tag.
     */
    public function create()
    {
        return view('admin.tags.create');
    }

    /**
     * Store a newly created tag in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:tags,name',
            'slug' => 'nullable|string|max:255|unique:tags,slug',
            'description' => 'nullable|string|max:500',
        ]);

        // Auto-generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $tag = Tag::create($validated);

        // Log activity
        ActivityLog::log(
            ActivityLog::TYPE_CREATE,
            "Created tag: {$tag->name}",
            $tag
        );

        return redirect()->route('admin.tags.index')
            ->with('success', 'Tag created successfully.');
    }

    /**
     * Show the form for editing the specified tag.
     */
    public function edit($id)
    {
        $tag = Tag::findOrFail($id);
        return view('admin.tags.edit', compact('tag'));
    }

    /**
     * Update the specified tag in storage.
     */
    public function update(Request $request, $id)
    {
        $tag = Tag::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:tags,name,' . $id,
            'slug' => 'required|string|max:255|unique:tags,slug,' . $id,
            'description' => 'nullable|string|max:500',
        ]);

        $old = $tag->getAttributes();
        $tag->update($validated);

        // Log activity
        ActivityLog::log(
            ActivityLog::TYPE_UPDATE,
            "Updated tag: {$tag->name}",
            $tag,
            ['old' => $old, 'new' => $tag->getAttributes()]
        );

        return redirect()->route('admin.tags.index')
            ->with('success', 'Tag updated successfully.');
    }

    /**
     * Remove the specified tag from storage.
     */
    public function destroy($id)
    {
        $tag = Tag::findOrFail($id);
        $name = $tag->name;

        // Log activity before deletion
        ActivityLog::log(
            ActivityLog::TYPE_DELETE,
            "Deleted tag: {$name}",
            $tag
        );

        $tag->delete();

        return redirect()->route('admin.tags.index')
            ->with('success', 'Tag deleted successfully.');
    }
}
