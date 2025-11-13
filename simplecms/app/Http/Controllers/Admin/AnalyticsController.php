<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageView;
use App\Models\Post;
use App\Models\Page;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    /**
     * Display analytics dashboard
     */
    public function index(Request $request)
    {
        $this->authorize('analytics.view');

        $days = $request->get('days', 30);

        // General statistics
        $stats = PageView::getStatistics($days);

        // Views by date (for chart)
        $viewsByDate = PageView::getViewsByDate($days);

        // Views by device
        $viewsByDevice = PageView::getViewsByDevice($days);

        // Views by browser
        $viewsByBrowser = PageView::getViewsByBrowser($days);

        // Top referrers
        $topReferrers = PageView::getTopReferrers($days, 10);

        // Most viewed posts
        $mostViewedPosts = PageView::getMostViewedContent('App\Models\Post', $days, 10);
        $postsData = $this->hydrateContentData($mostViewedPosts, Post::class);

        // Most viewed pages
        $mostViewedPages = PageView::getMostViewedContent('App\Models\Page', $days, 10);
        $pagesData = $this->hydrateContentData($mostViewedPages, Page::class);

        // Real-time stats (last 24 hours)
        $realtimeViews = PageView::where('created_at', '>=', Carbon::now()->subHours(24))
            ->selectRaw('HOUR(created_at) as hour, COUNT(*) as views')
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();

        return view('admin.analytics.index', compact(
            'stats',
            'viewsByDate',
            'viewsByDevice',
            'viewsByBrowser',
            'topReferrers',
            'postsData',
            'pagesData',
            'realtimeViews',
            'days'
        ));
    }

    /**
     * Content performance report
     */
    public function contentPerformance(Request $request)
    {
        $this->authorize('analytics.view');

        $days = $request->get('days', 30);
        $type = $request->get('type', 'posts'); // posts or pages

        $modelClass = $type === 'posts' ? Post::class : Page::class;

        // Get all content with view counts
        $content = $modelClass::withCount(['pageViews' => function ($query) use ($days) {
            $query->where('created_at', '>=', Carbon::now()->subDays($days));
        }])
            ->orderBy('page_views_count', 'desc')
            ->paginate(50);

        return view('admin.analytics.content-performance', compact('content', 'type', 'days'));
    }

    /**
     * Traffic sources report
     */
    public function trafficSources(Request $request)
    {
        $this->authorize('analytics.view');

        $days = $request->get('days', 30);

        // Top referrers with details
        $referrers = PageView::getTopReferrers($days, 50);

        // Direct vs referral traffic
        $directViews = PageView::recent($days)
            ->where(function($q) {
                $q->whereNull('referrer')
                  ->orWhere('referrer', '');
            })
            ->count();

        $referralViews = PageView::recent($days)
            ->whereNotNull('referrer')
            ->where('referrer', '!=', '')
            ->count();

        return view('admin.analytics.traffic-sources', compact('referrers', 'directViews', 'referralViews', 'days'));
    }

    /**
     * User engagement report
     */
    public function userEngagement(Request $request)
    {
        $this->authorize('analytics.view');

        $days = $request->get('days', 30);

        // Registered users activity
        $registeredUserViews = PageView::recent($days)
            ->whereNotNull('user_id')
            ->with('user')
            ->get()
            ->groupBy('user_id')
            ->map(function ($views) {
                return [
                    'user' => $views->first()->user,
                    'views' => $views->count(),
                    'last_active' => $views->max('created_at'),
                ];
            })
            ->sortByDesc('views')
            ->take(50);

        // Anonymous vs registered
        $anonymousViews = PageView::recent($days)->whereNull('user_id')->count();
        $registeredViews = PageView::recent($days)->whereNotNull('user_id')->count();

        // Active users by day
        $activeUsersByDay = PageView::recent($days)
            ->whereNotNull('user_id')
            ->selectRaw('DATE(created_at) as date, COUNT(DISTINCT user_id) as users')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('admin.analytics.user-engagement', compact(
            'registeredUserViews',
            'anonymousViews',
            'registeredViews',
            'activeUsersByDay',
            'days'
        ));
    }

    /**
     * Export analytics data
     */
    public function export(Request $request)
    {
        $this->authorize('analytics.view');

        $days = $request->get('days', 30);
        $format = $request->get('format', 'csv'); // csv or json

        $data = [
            'stats' => PageView::getStatistics($days),
            'views_by_date' => PageView::getViewsByDate($days),
            'views_by_device' => PageView::getViewsByDevice($days),
            'views_by_browser' => PageView::getViewsByBrowser($days),
            'top_referrers' => PageView::getTopReferrers($days, 50),
        ];

        if ($format === 'json') {
            return response()->json($data);
        }

        // CSV export
        $filename = 'analytics_' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');

            // Write statistics
            fputcsv($file, ['Analytics Report']);
            fputcsv($file, ['Generated', date('Y-m-d H:i:s')]);
            fputcsv($file, []);

            fputcsv($file, ['Metric', 'Value']);
            foreach ($data['stats'] as $key => $value) {
                fputcsv($file, [ucfirst(str_replace('_', ' ', $key)), round($value, 2)]);
            }

            fputcsv($file, []);
            fputcsv($file, ['Views by Date']);
            fputcsv($file, ['Date', 'Views']);
            foreach ($data['views_by_date'] as $row) {
                fputcsv($file, [$row->date, $row->views]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Helper: Hydrate content data with actual models
     */
    protected function hydrateContentData($viewData, $modelClass)
    {
        $ids = $viewData->pluck('viewable_id');
        $models = $modelClass::whereIn('id', $ids)->get()->keyBy('id');

        return $viewData->map(function ($item) use ($models) {
            $item->content = $models->get($item->viewable_id);
            return $item;
        })->filter(function ($item) {
            return $item->content !== null;
        });
    }
}
