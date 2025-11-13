<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SearchLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'query',
        'results_count',
        'user_id',
        'filters',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relationship to User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get decoded filters
     */
    public function getFiltersAttribute($value)
    {
        return $value ? json_decode($value, true) : [];
    }

    /**
     * Set filters as JSON
     */
    public function setFiltersAttribute($value)
    {
        $this->attributes['filters'] = is_array($value) ? json_encode($value) : $value;
    }

    /**
     * Log a search query
     */
    public static function logSearch($query, $resultsCount, $filters = [])
    {
        return static::create([
            'query' => $query,
            'results_count' => $resultsCount,
            'user_id' => auth()->id(),
            'filters' => $filters,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Get popular searches
     */
    public static function popularSearches($limit = 10)
    {
        return static::selectRaw('query, COUNT(*) as search_count, SUM(results_count) as total_results')
            ->groupBy('query')
            ->orderBy('search_count', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get searches with no results
     */
    public static function noResultsSearches($limit = 10)
    {
        return static::where('results_count', 0)
            ->selectRaw('query, COUNT(*) as search_count')
            ->groupBy('query')
            ->orderBy('search_count', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get recent searches
     */
    public function scopeRecent($query, $limit = 20)
    {
        return $query->latest()->limit($limit);
    }

    /**
     * Get searches by date range
     */
    public function scopeDateRange($query, $from, $to)
    {
        return $query->whereBetween('created_at', [$from, $to]);
    }
}
