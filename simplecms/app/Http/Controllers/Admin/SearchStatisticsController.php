<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SearchLog;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SearchStatisticsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:search-statistics.view')->only(['index']);
    }

    /**
     * Display search statistics
     */
    public function index(Request $request)
    {
        // Date range filter
        $dateFrom = $request->input('date_from', now()->subDays(30)->format('Y-m-d'));
        $dateTo = $request->input('date_to', now()->format('Y-m-d'));

        // General statistics
        $stats = [
            'total_searches' => SearchLog::count(),
            'total_searches_period' => SearchLog::dateRange($dateFrom, $dateTo)->count(),
            'unique_queries' => SearchLog::distinct('query')->count(),
            'searches_today' => SearchLog::whereDate('created_at', today())->count(),
            'searches_this_week' => SearchLog::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'searches_this_month' => SearchLog::whereMonth('created_at', now()->month)->count(),
            'avg_results_per_search' => round(SearchLog::avg('results_count'), 2),
            'searches_with_no_results' => SearchLog::where('results_count', 0)->count(),
        ];

        // Popular searches (period)
        $popularSearches = SearchLog::selectRaw('query, COUNT(*) as search_count, SUM(results_count) as total_results, AVG(results_count) as avg_results')
            ->dateRange($dateFrom, $dateTo)
            ->groupBy('query')
            ->orderBy('search_count', 'desc')
            ->limit(20)
            ->get();

        // Searches with no results (period)
        $noResultsSearches = SearchLog::where('results_count', 0)
            ->dateRange($dateFrom, $dateTo)
            ->selectRaw('query, COUNT(*) as search_count')
            ->groupBy('query')
            ->orderBy('search_count', 'desc')
            ->limit(20)
            ->get();

        // Recent searches
        $recentSearches = SearchLog::with('user')
            ->latest()
            ->limit(50)
            ->get();

        // Search trends (daily for the period)
        $searchTrends = SearchLog::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->dateRange($dateFrom, $dateTo)
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // Top filters used
        $topFilters = SearchLog::whereNotNull('filters')
            ->where('filters', '!=', '[]')
            ->where('filters', '!=', '""')
            ->dateRange($dateFrom, $dateTo)
            ->get()
            ->flatMap(function($log) {
                $filters = is_string($log->filters) ? json_decode($log->filters, true) : $log->filters;
                return collect($filters)->filter()->keys();
            })
            ->countBy()
            ->sortDesc()
            ->take(10);

        return view('admin.search-statistics.index', compact(
            'stats',
            'popularSearches',
            'noResultsSearches',
            'recentSearches',
            'searchTrends',
            'topFilters',
            'dateFrom',
            'dateTo'
        ));
    }
}
