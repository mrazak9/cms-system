<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Redirect;
use Illuminate\Http\Request;

class RedirectController extends Controller
{
    /**
     * Display a listing of redirects
     */
    public function index(Request $request)
    {
        $this->authorize('settings.edit');

        $query = Redirect::query();

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('from_url', 'like', "%{$search}%")
                  ->orWhere('to_url', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('is_active', $request->status == 'active');
        }

        $redirects = $query->orderBy('hits', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        return view('admin.redirects.index', compact('redirects'));
    }

    /**
     * Show the form for creating a new redirect
     */
    public function create()
    {
        $this->authorize('settings.edit');

        return view('admin.redirects.create');
    }

    /**
     * Store a newly created redirect
     */
    public function store(Request $request)
    {
        $this->authorize('settings.edit');

        $validated = $request->validate([
            'from_url' => 'required|string|max:500|unique:redirects,from_url',
            'to_url' => 'required|string|max:500',
            'status_code' => 'required|integer|in:301,302',
            'is_active' => 'boolean',
        ]);

        // Normalize URLs
        $validated['from_url'] = '/' . trim($validated['from_url'], '/');
        $validated['to_url'] = trim($validated['to_url']);

        Redirect::create($validated);

        return redirect()->route('admin.redirects.index')
            ->with('success', 'Redirect created successfully.');
    }

    /**
     * Show the form for editing the redirect
     */
    public function edit(Redirect $redirect)
    {
        $this->authorize('settings.edit');

        return view('admin.redirects.edit', compact('redirect'));
    }

    /**
     * Update the specified redirect
     */
    public function update(Request $request, Redirect $redirect)
    {
        $this->authorize('settings.edit');

        $validated = $request->validate([
            'from_url' => 'required|string|max:500|unique:redirects,from_url,' . $redirect->id,
            'to_url' => 'required|string|max:500',
            'status_code' => 'required|integer|in:301,302',
            'is_active' => 'boolean',
        ]);

        // Normalize URLs
        $validated['from_url'] = '/' . trim($validated['from_url'], '/');
        $validated['to_url'] = trim($validated['to_url']);

        $redirect->update($validated);

        return redirect()->route('admin.redirects.index')
            ->with('success', 'Redirect updated successfully.');
    }

    /**
     * Remove the specified redirect
     */
    public function destroy(Redirect $redirect)
    {
        $this->authorize('settings.edit');

        $redirect->delete();

        return redirect()->route('admin.redirects.index')
            ->with('success', 'Redirect deleted successfully.');
    }
}
