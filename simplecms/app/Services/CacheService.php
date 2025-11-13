<?php

namespace App\Services;

use App\Models\Setting;
use App\Models\Menu;
use App\Models\Category;
use App\Models\Post;
use App\Models\Page;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CacheService
{
    /**
     * Default cache TTL (1 hour)
     */
    const DEFAULT_TTL = 3600;

    /**
     * Long cache TTL (24 hours)
     */
    const LONG_TTL = 86400;

    /**
     * Short cache TTL (5 minutes)
     */
    const SHORT_TTL = 300;

    /**
     * Get all settings with caching
     */
    public function getSettings(): array
    {
        return Cache::tags(['settings'])->remember('all_settings', self::LONG_TTL, function () {
            return Setting::pluck('value', 'key')->toArray();
        });
    }

    /**
     * Get specific setting with caching
     */
    public function getSetting(string $key, $default = null)
    {
        $settings = $this->getSettings();
        return $settings[$key] ?? $default;
    }

    /**
     * Get menu by location with caching
     */
    public function getMenuByLocation(string $location)
    {
        return Cache::tags(['menus'])->remember("menu_location_{$location}", self::DEFAULT_TTL, function () use ($location) {
            return Menu::with(['items' => function ($query) {
                $query->whereNull('parent_id')
                    ->with(['children' => function ($q) {
                        $q->orderBy('order');
                    }])
                    ->orderBy('order');
            }])
            ->where('location', $location)
            ->first();
        });
    }

    /**
     * Get all categories with post count
     */
    public function getCategories()
    {
        return Cache::tags(['categories'])->remember('all_categories', self::DEFAULT_TTL, function () {
            return Category::withCount('posts')->orderBy('name')->get();
        });
    }

    /**
     * Get featured posts with caching
     */
    public function getFeaturedPosts(int $limit = 5)
    {
        return Cache::tags(['posts'])->remember("featured_posts_{$limit}", self::SHORT_TTL, function () use ($limit) {
            return Post::with(['author', 'category'])
                ->published()
                ->featured()
                ->limit($limit)
                ->get();
        });
    }

    /**
     * Get recent posts with caching
     */
    public function getRecentPosts(int $limit = 10)
    {
        return Cache::tags(['posts'])->remember("recent_posts_{$limit}", self::SHORT_TTL, function () use ($limit) {
            return Post::with(['author', 'category'])
                ->published()
                ->latest('published_at')
                ->limit($limit)
                ->get();
        });
    }

    /**
     * Get popular posts by views
     */
    public function getPopularPosts(int $limit = 5)
    {
        return Cache::tags(['posts'])->remember("popular_posts_{$limit}", self::DEFAULT_TTL, function () use ($limit) {
            return Post::with(['author', 'category'])
                ->published()
                ->orderBy('views_count', 'desc')
                ->limit($limit)
                ->get();
        });
    }

    /**
     * Get homepage page
     */
    public function getHomepage()
    {
        return Cache::tags(['pages'])->remember('homepage', self::DEFAULT_TTL, function () {
            return Page::with(['sections', 'theme'])
                ->where('is_homepage', true)
                ->published()
                ->first();
        });
    }

    /**
     * Clear all application caches
     */
    public function clearAll(): void
    {
        Cache::flush();
    }

    /**
     * Clear specific cache tags
     */
    public function clearTag(string $tag): void
    {
        Cache::tags([$tag])->flush();
    }

    /**
     * Clear multiple cache tags
     */
    public function clearTags(array $tags): void
    {
        foreach ($tags as $tag) {
            $this->clearTag($tag);
        }
    }

    /**
     * Warm up all caches
     */
    public function warmUp(): array
    {
        $results = [];

        // Warm settings
        $results['settings'] = $this->getSettings() ? 'success' : 'failed';

        // Warm categories
        $results['categories'] = $this->getCategories() ? 'success' : 'failed';

        // Warm featured posts
        $results['featured_posts'] = $this->getFeaturedPosts() ? 'success' : 'failed';

        // Warm recent posts
        $results['recent_posts'] = $this->getRecentPosts() ? 'success' : 'failed';

        // Warm popular posts
        $results['popular_posts'] = $this->getPopularPosts() ? 'success' : 'failed';

        // Warm homepage
        $results['homepage'] = $this->getHomepage() !== null ? 'success' : 'failed';

        // Warm menus
        $menuLocations = ['header', 'footer', 'sidebar'];
        foreach ($menuLocations as $location) {
            $results["menu_{$location}"] = $this->getMenuByLocation($location) !== null ? 'success' : 'failed';
        }

        return $results;
    }

    /**
     * Get cache statistics
     */
    public function getStatistics(): array
    {
        try {
            $stats = [
                'driver' => config('cache.default'),
                'enabled' => true,
            ];

            // Get cache size if using file driver
            if ($stats['driver'] === 'file') {
                $cachePath = storage_path('framework/cache/data');
                if (is_dir($cachePath)) {
                    $size = 0;
                    $files = 0;
                    foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($cachePath)) as $file) {
                        if ($file->isFile()) {
                            $size += $file->getSize();
                            $files++;
                        }
                    }
                    $stats['size'] = $this->formatBytes($size);
                    $stats['files'] = $files;
                }
            }

            return $stats;
        } catch (\Exception $e) {
            return [
                'driver' => config('cache.default'),
                'enabled' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Format bytes to human readable
     */
    private function formatBytes($bytes, $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
