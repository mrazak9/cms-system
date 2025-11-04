<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Page;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Admin Menu Controller
 *
 * Handles menu management with menu items (hierarchical structure)
 */
class MenuController extends Controller
{
    /**
     * Constructor - Apply permission middleware
     */
    public function __construct()
    {
        // View permissions
        $this->middleware('permission:menus.view')->only(['index', 'show']);

        // Create permissions
        $this->middleware('permission:menus.create')->only(['create', 'store', 'storeItem']);

        // Edit permissions
        $this->middleware('permission:menus.edit')->only(['edit', 'update', 'updateItem', 'reorderItems']);

        // Delete permissions
        $this->middleware('permission:menus.delete')->only(['destroy', 'destroyItem']);
    }

    /**
     * Display a listing of menus
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        try {
            $menus = Menu::withCount('menuItems')
                ->latest()
                ->paginate(15);

            return view('admin.menus.index', compact('menus'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading menus: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new menu
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        try {
            return view('admin.menus.create');
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading menu form: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created menu
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        try {
            // Create menu
            $menu = Menu::create([
                'name' => $validated['name'],
                'location' => $validated['location'],
                'is_active' => $request->is_active ?? false,
            ]);

            return redirect()->route('admin.menus.edit', $menu->id)
                ->with('success', 'Menu created successfully! Now add menu items.');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error creating menu: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified menu
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        try {
            $menu = Menu::with(['menuItems' => function ($query) {
                $query->with('children')->whereNull('parent_id');
            }])->findOrFail($id);

            return view('admin.menus.show', compact('menu'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading menu: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the menu with items management
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        try {
            $menu = Menu::with(['allItems' => function ($query) {
                $query->with(['page', 'post', 'parent']);
            }])->findOrFail($id);

            // Get available pages, posts, and categories for menu items
            $pages = Page::published()->get(['id', 'title', 'slug']);
            $posts = Post::published()->get(['id', 'title', 'slug']);
            $categories = \App\Models\Category::all(['id', 'name', 'slug']);

            return view('admin.menus.edit', compact('menu', 'pages', 'posts', 'categories'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading menu for editing: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified menu and its items
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Validate request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'is_active' => 'boolean',
            'menu_items' => 'nullable|array',
            'menu_items.*.id' => 'nullable|exists:menu_items,id',
            'menu_items.*.title' => 'required|string|max:255',
            'menu_items.*.url' => 'nullable|string|max:255',
            'menu_items.*.page_id' => 'nullable|exists:pages,id',
            'menu_items.*.post_id' => 'nullable|exists:posts,id',
            'menu_items.*.type' => 'required|in:page,post,custom,category',
            'menu_items.*.target' => 'nullable|in:_self,_blank',
            'menu_items.*.parent_id' => 'nullable|exists:menu_items,id',
            'menu_items.*.order' => 'required|integer',
            'menu_items.*.css_class' => 'nullable|string|max:255',
            'deleted_items' => 'nullable|array',
            'deleted_items.*' => 'exists:menu_items,id',
        ]);

        try {
            DB::beginTransaction();

            $menu = Menu::findOrFail($id);

            // Update menu
            $menu->update([
                'name' => $validated['name'],
                'location' => $validated['location'],
                'is_active' => $request->is_active ?? false,
            ]);

            // Delete removed menu items
            if (!empty($validated['deleted_items'])) {
                MenuItem::whereIn('id', $validated['deleted_items'])->delete();
            }

            // Update or create menu items
            if (!empty($validated['menu_items'])) {
                foreach ($validated['menu_items'] as $itemData) {
                    $menuItemData = [
                        'menu_id' => $menu->id,
                        'title' => $itemData['title'],
                        'url' => $itemData['url'] ?? null,
                        'page_id' => $itemData['page_id'] ?? null,
                        'post_id' => $itemData['post_id'] ?? null,
                        'type' => $itemData['type'],
                        'target' => $itemData['target'] ?? '_self',
                        'parent_id' => $itemData['parent_id'] ?? null,
                        'order' => $itemData['order'],
                        'css_class' => $itemData['css_class'] ?? null,
                    ];

                    if (!empty($itemData['id'])) {
                        // Update existing menu item
                        MenuItem::where('id', $itemData['id'])->update($menuItemData);
                    } else {
                        // Create new menu item
                        MenuItem::create($menuItemData);
                    }
                }
            }

            DB::commit();

            return redirect()->route('admin.menus.index')
                ->with('success', 'Menu updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Error updating menu: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified menu
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $menu = Menu::findOrFail($id);

            // Delete all menu items
            $menu->allItems()->delete();

            // Delete the menu
            $menu->delete();

            DB::commit();

            return redirect()->route('admin.menus.index')
                ->with('success', 'Menu deleted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error deleting menu: ' . $e->getMessage());
        }
    }

    /**
     * Store a new menu item
     */
    public function storeItem(Request $request, $menuId)
    {
        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'type' => 'required|in:custom,page,post,category',
            'url' => 'nullable|string',
            'page_id' => 'nullable|exists:pages,id',
            'post_id' => 'nullable|exists:posts,id',
            'category_id' => 'nullable|exists:categories,id',
            'parent_id' => 'nullable|exists:menu_items,id',
            'target' => 'nullable|in:_self,_blank',
            'order' => 'nullable|integer',
            'css_class' => 'nullable|string|max:255',
        ]);

        try {
            $menu = Menu::findOrFail($menuId);

            // Set title same as label if not provided
            $validated['title'] = $validated['label'];
            $validated['target'] = $validated['target'] ?? '_self';
            $validated['order'] = $validated['order'] ?? 0;

            $item = $menu->allItems()->create($validated);

            // Check if it's an AJAX request
            if ($request->expectsJson()) {
                return response()->json(['success' => true, 'item' => $item]);
            }

            return redirect()->route('admin.menus.edit', $menu->id)
                ->with('success', 'Menu item added successfully!');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error adding menu item: ' . $e->getMessage(),
                ], 500);
            }

            return back()->with('error', 'Error adding menu item: ' . $e->getMessage());
        }
    }

    /**
     * Get a specific menu item
     */
    public function getItem($menuId, $itemId)
    {
        try {
            $item = MenuItem::where('menu_id', $menuId)->findOrFail($itemId);

            return response()->json([
                'success' => true,
                'item' => $item
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading menu item: ' . $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update a menu item
     */
    public function updateItem(Request $request, $menuId, $itemId)
    {
        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'url' => 'nullable|string',
            'parent_id' => 'nullable|exists:menu_items,id',
            'target' => 'nullable|in:_self,_blank',
            'order' => 'nullable|integer',
            'css_class' => 'nullable|string|max:255',
        ]);

        try {
            $item = MenuItem::where('menu_id', $menuId)->findOrFail($itemId);

            // Set title same as label
            $validated['title'] = $validated['label'];
            $validated['target'] = $validated['target'] ?? '_self';
            $validated['order'] = $validated['order'] ?? 0;

            $item->update($validated);

            // Check if it's an AJAX request
            if ($request->expectsJson()) {
                return response()->json(['success' => true]);
            }

            return redirect()->route('admin.menus.edit', $menuId)
                ->with('success', 'Menu item updated successfully!');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error updating menu item: ' . $e->getMessage(),
                ], 500);
            }

            return back()->with('error', 'Error updating menu item: ' . $e->getMessage());
        }
    }

    /**
     * Delete a menu item
     */
    public function destroyItem(Request $request, $menuId, $itemId)
    {
        try {
            $item = MenuItem::where('menu_id', $menuId)->findOrFail($itemId);
            $item->delete();

            // Check if it's an AJAX request
            if ($request->expectsJson()) {
                return response()->json(['success' => true]);
            }

            return redirect()->route('admin.menus.edit', $menuId)
                ->with('success', 'Menu item deleted successfully!');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error deleting menu item: ' . $e->getMessage(),
                ], 500);
            }

            return back()->with('error', 'Error deleting menu item: ' . $e->getMessage());
        }
    }

    /**
     * Reorder menu items and update parent-child relationships
     */
    public function reorderItems(Request $request, $menuId)
    {
        try {
            DB::beginTransaction();

            // Support both old 'order' format and new 'structure' format
            $structure = $request->input('structure', []);

            // Fallback to old format if structure is empty
            if (empty($structure)) {
                $order = $request->input('order', []);
                foreach ($order as $index => $itemId) {
                    MenuItem::where('id', $itemId)
                        ->where('menu_id', $menuId)
                        ->update([
                            'order' => $index,
                            'parent_id' => null
                        ]);
                }
            } else {
                // New format with parent-child support
                foreach ($structure as $item) {
                    MenuItem::where('id', $item['id'])
                        ->where('menu_id', $menuId)
                        ->update([
                            'order' => $item['order'],
                            'parent_id' => $item['parent_id']
                        ]);
                }
            }

            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error reordering menu items: ' . $e->getMessage()
            ], 500);
        }
    }
}
