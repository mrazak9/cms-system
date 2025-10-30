<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\PageSection;
use App\Models\SectionTemplate;
use App\Models\Theme;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

/**
 * Admin Page Controller
 *
 * Handles CRUD operations for pages with section management
 */
class PageController extends Controller
{
    /**
     * Display a listing of pages with pagination
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        try {
            $pages = Page::with(['theme', 'creator', 'sections'])
                ->latest()
                ->paginate(15);

            return view('admin.pages.index', compact('pages'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading pages: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new page
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        try {
            $themes = Theme::all();
            $sectionTemplates = SectionTemplate::all();

            return view('admin.pages.create', compact('themes', 'sectionTemplates'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading page form: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created page
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate request
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages,slug',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
            'is_published' => 'boolean',
            'is_homepage' => 'boolean',
            'theme_id' => 'nullable|exists:themes,id',
            'sections' => 'nullable|array',
            'sections.*.section_template_id' => 'required|exists:section_templates,id',
            'sections.*.content' => 'nullable|array',
            'sections.*.order' => 'required|integer',
            'sections.*.is_visible' => 'boolean',
        ]);

        try {
            DB::beginTransaction();

            // Generate slug if not provided
            if (empty($validated['slug'])) {
                $validated['slug'] = Str::slug($validated['title']);
            }

            // If this page is set as homepage, remove homepage flag from other pages
            if ($request->is_homepage) {
                Page::where('is_homepage', true)->update(['is_homepage' => false]);
            }

            // Create page
            $page = Page::create([
                'title' => $validated['title'],
                'slug' => $validated['slug'],
                'meta_description' => $validated['meta_description'] ?? null,
                'meta_keywords' => $validated['meta_keywords'] ?? null,
                'is_published' => $request->is_published ?? false,
                'is_homepage' => $request->is_homepage ?? false,
                'theme_id' => $validated['theme_id'] ?? null,
                'created_by' => auth()->id(),
            ]);

            // Create sections if provided
            if (!empty($validated['sections'])) {
                foreach ($validated['sections'] as $sectionData) {
                    PageSection::create([
                        'page_id' => $page->id,
                        'section_template_id' => $sectionData['section_template_id'],
                        'content' => $sectionData['content'] ?? [],
                        'order' => $sectionData['order'],
                        'is_visible' => $sectionData['is_visible'] ?? true,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('admin.pages.index')
                ->with('success', 'Page created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Error creating page: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified page
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        try {
            $page = Page::with(['sections.sectionTemplate', 'theme', 'creator'])
                ->findOrFail($id);

            return view('admin.pages.show', compact('page'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading page: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the page with sections
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        try {
            $page = Page::with(['sections.sectionTemplate'])
                ->findOrFail($id);
            $themes = Theme::all();
            $sectionTemplates = SectionTemplate::all();

            return view('admin.pages.edit', compact('page', 'themes', 'sectionTemplates'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading page for editing: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified page
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
            'slug' => 'nullable|string|max:255|unique:pages,slug,' . $id,
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
            'is_published' => 'boolean',
            'is_homepage' => 'boolean',
            'theme_id' => 'nullable|exists:themes,id',
            'sections' => 'nullable|array',
            'sections.*.id' => 'nullable|exists:page_sections,id',
            'sections.*.section_template_id' => 'required|exists:section_templates,id',
            'sections.*.content' => 'nullable|array',
            'sections.*.order' => 'required|integer',
            'sections.*.is_visible' => 'boolean',
            'deleted_sections' => 'nullable|array',
            'deleted_sections.*' => 'exists:page_sections,id',
        ]);

        try {
            DB::beginTransaction();

            $page = Page::findOrFail($id);

            // Generate slug if not provided
            if (empty($validated['slug'])) {
                $validated['slug'] = Str::slug($validated['title']);
            }

            // If this page is set as homepage, remove homepage flag from other pages
            if ($request->is_homepage) {
                Page::where('id', '!=', $id)
                    ->where('is_homepage', true)
                    ->update(['is_homepage' => false]);
            }

            // Update page
            $page->update([
                'title' => $validated['title'],
                'slug' => $validated['slug'],
                'meta_description' => $validated['meta_description'] ?? null,
                'meta_keywords' => $validated['meta_keywords'] ?? null,
                'is_published' => $request->is_published ?? false,
                'is_homepage' => $request->is_homepage ?? false,
                'theme_id' => $validated['theme_id'] ?? null,
            ]);

            // Delete removed sections
            if (!empty($validated['deleted_sections'])) {
                PageSection::whereIn('id', $validated['deleted_sections'])->delete();
            }

            // Update or create sections
            if (!empty($validated['sections'])) {
                foreach ($validated['sections'] as $sectionData) {
                    if (!empty($sectionData['id'])) {
                        // Update existing section
                        PageSection::where('id', $sectionData['id'])->update([
                            'section_template_id' => $sectionData['section_template_id'],
                            'content' => $sectionData['content'] ?? [],
                            'order' => $sectionData['order'],
                            'is_visible' => $sectionData['is_visible'] ?? true,
                        ]);
                    } else {
                        // Create new section
                        PageSection::create([
                            'page_id' => $page->id,
                            'section_template_id' => $sectionData['section_template_id'],
                            'content' => $sectionData['content'] ?? [],
                            'order' => $sectionData['order'],
                            'is_visible' => $sectionData['is_visible'] ?? true,
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()->route('admin.pages.index')
                ->with('success', 'Page updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Error updating page: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified page
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $page = Page::findOrFail($id);

            // Delete all related sections
            $page->sections()->delete();

            // Delete the page
            $page->delete();

            DB::commit();

            return redirect()->route('admin.pages.index')
                ->with('success', 'Page deleted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error deleting page: ' . $e->getMessage());
        }
    }

    /**
     * Store a new section for a page
     */
    public function storeSection(Request $request, $pageId)
    {
        $validated = $request->validate([
            'template_id' => 'required|exists:section_templates,id',
            'content' => 'nullable|array',
            'order_index' => 'nullable|integer',
            'is_visible' => 'boolean',
        ]);

        $page = Page::findOrFail($pageId);
        $section = $page->sections()->create($validated);

        return response()->json(['success' => true, 'section' => $section]);
    }

    /**
     * Update a section
     */
    public function updateSection(Request $request, $pageId, $sectionId)
    {
        $validated = $request->validate([
            'content' => 'nullable|array',
            'is_visible' => 'boolean',
        ]);

        $section = \App\Models\PageSection::where('page_id', $pageId)->findOrFail($sectionId);
        $section->update($validated);

        return response()->json(['success' => true]);
    }

    /**
     * Delete a section
     */
    public function destroySection($pageId, $sectionId)
    {
        $section = \App\Models\PageSection::where('page_id', $pageId)->findOrFail($sectionId);
        $section->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Reorder sections
     */
    public function reorderSections(Request $request, $pageId)
    {
        $order = $request->input('order', []);

        foreach ($order as $index => $sectionId) {
            \App\Models\PageSection::where('id', $sectionId)
                ->where('page_id', $pageId)
                ->update(['order_index' => $index]);
        }

        return response()->json(['success' => true]);
    }
}
