<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\CacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class CacheController extends Controller
{
    protected $cacheService;

    public function __construct(CacheService $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    /**
     * Display cache management page
     */
    public function index()
    {
        $this->authorize('settings.edit');

        $stats = $this->cacheService->getStatistics();

        return view('admin.cache.index', compact('stats'));
    }

    /**
     * Clear all application caches
     */
    public function clearAll()
    {
        $this->authorize('settings.edit');

        try {
            $this->cacheService->clearAll();

            // Also clear Laravel's caches
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('route:clear');
            Artisan::call('view:clear');

            return redirect()->route('admin.cache.index')
                ->with('success', 'All caches have been cleared successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.cache.index')
                ->with('error', 'Error clearing caches: ' . $e->getMessage());
        }
    }

    /**
     * Clear specific cache tag
     */
    public function clearTag(Request $request)
    {
        $this->authorize('settings.edit');

        $tag = $request->input('tag');

        if (!$tag) {
            return redirect()->route('admin.cache.index')
                ->with('error', 'No cache tag specified.');
        }

        try {
            $this->cacheService->clearTag($tag);

            return redirect()->route('admin.cache.index')
                ->with('success', "Cache tag '{$tag}' has been cleared successfully.");
        } catch (\Exception $e) {
            return redirect()->route('admin.cache.index')
                ->with('error', 'Error clearing cache: ' . $e->getMessage());
        }
    }

    /**
     * Warm up caches
     */
    public function warm()
    {
        $this->authorize('settings.edit');

        try {
            $results = $this->cacheService->warmUp();

            $successCount = count(array_filter($results, fn($s) => $s === 'success'));
            $totalCount = count($results);

            return redirect()->route('admin.cache.index')
                ->with('success', "Cache warming completed: {$successCount}/{$totalCount} caches warmed successfully.");
        } catch (\Exception $e) {
            return redirect()->route('admin.cache.index')
                ->with('error', 'Error warming caches: ' . $e->getMessage());
        }
    }

    /**
     * Optimize application
     */
    public function optimize()
    {
        $this->authorize('settings.edit');

        try {
            // Run optimization commands
            Artisan::call('config:cache');
            Artisan::call('route:cache');
            Artisan::call('view:cache');

            // Warm up application caches
            $this->cacheService->warmUp();

            return redirect()->route('admin.cache.index')
                ->with('success', 'Application has been optimized successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.cache.index')
                ->with('error', 'Error optimizing application: ' . $e->getMessage());
        }
    }
}
