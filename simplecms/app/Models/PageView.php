<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PageView extends Model
{
    use HasFactory;

    protected $fillable = [
        'viewable_type',
        'viewable_id',
        'user_id',
        'url',
        'ip_address',
        'user_agent',
        'referrer',
        'country',
        'device_type',
        'browser',
        'platform',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Polymorphic relationship
     */
    public function viewable()
    {
        return $this->morphTo();
    }

    /**
     * User relationship
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Log a page view
     */
    public static function logView($viewable = null, $url = null)
    {
        $agent = request()->userAgent();

        return static::create([
            'viewable_type' => $viewable ? get_class($viewable) : null,
            'viewable_id' => $viewable ? $viewable->id : null,
            'user_id' => auth()->id(),
            'url' => $url ?? request()->fullUrl(),
            'ip_address' => request()->ip(),
            'user_agent' => $agent,
            'referrer' => request()->header('referer'),
            'device_type' => static::detectDeviceType($agent),
            'browser' => static::detectBrowser($agent),
            'platform' => static::detectPlatform($agent),
        ]);
    }

    /**
     * Detect device type from user agent
     */
    protected static function detectDeviceType($userAgent)
    {
        if (empty($userAgent)) return 'unknown';

        if (preg_match('/(tablet|ipad|playbook)|(android(?!.*mobile))/i', $userAgent)) {
            return 'tablet';
        }

        if (preg_match('/Mobile|iP(hone|od)|Android|BlackBerry|IEMobile/', $userAgent)) {
            return 'mobile';
        }

        return 'desktop';
    }

    /**
     * Detect browser from user agent
     */
    protected static function detectBrowser($userAgent)
    {
        if (empty($userAgent)) return 'unknown';

        if (strpos($userAgent, 'Edg') !== false) return 'Edge';
        if (strpos($userAgent, 'Chrome') !== false) return 'Chrome';
        if (strpos($userAgent, 'Safari') !== false) return 'Safari';
        if (strpos($userAgent, 'Firefox') !== false) return 'Firefox';
        if (strpos($userAgent, 'MSIE') !== false || strpos($userAgent, 'Trident') !== false) return 'IE';
        if (strpos($userAgent, 'Opera') !== false || strpos($userAgent, 'OPR') !== false) return 'Opera';

        return 'Other';
    }

    /**
     * Detect platform from user agent
     */
    protected static function detectPlatform($userAgent)
    {
        if (empty($userAgent)) return 'unknown';

        if (strpos($userAgent, 'Windows') !== false) return 'Windows';
        if (strpos($userAgent, 'Mac') !== false) return 'macOS';
        if (strpos($userAgent, 'Linux') !== false) return 'Linux';
        if (strpos($userAgent, 'Android') !== false) return 'Android';
        if (strpos($userAgent, 'iPhone') !== false || strpos($userAgent, 'iPad') !== false) return 'iOS';

        return 'Other';
    }

    /**
     * Get statistics for dashboard
     */
    public static function getStatistics($days = 30)
    {
        $startDate = Carbon::now()->subDays($days);

        return [
            'total_views' => static::where('created_at', '>=', $startDate)->count(),
            'unique_visitors' => static::where('created_at', '>=', $startDate)
                ->distinct('ip_address')
                ->count('ip_address'),
            'views_today' => static::whereDate('created_at', Carbon::today())->count(),
            'views_yesterday' => static::whereDate('created_at', Carbon::yesterday())->count(),
            'avg_daily_views' => static::where('created_at', '>=', $startDate)
                ->selectRaw('COUNT(*) / ' . $days . ' as avg')
                ->value('avg'),
        ];
    }

    /**
     * Get views by date
     */
    public static function getViewsByDate($days = 30)
    {
        $startDate = Carbon::now()->subDays($days);

        return static::where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as views')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    /**
     * Get views by device type
     */
    public static function getViewsByDevice($days = 30)
    {
        $startDate = Carbon::now()->subDays($days);

        return static::where('created_at', '>=', $startDate)
            ->selectRaw('device_type, COUNT(*) as views')
            ->groupBy('device_type')
            ->orderBy('views', 'desc')
            ->get();
    }

    /**
     * Get views by browser
     */
    public static function getViewsByBrowser($days = 30)
    {
        $startDate = Carbon::now()->subDays($days);

        return static::where('created_at', '>=', $startDate)
            ->selectRaw('browser, COUNT(*) as views')
            ->groupBy('browser')
            ->orderBy('views', 'desc')
            ->get();
    }

    /**
     * Get top referrers
     */
    public static function getTopReferrers($days = 30, $limit = 10)
    {
        $startDate = Carbon::now()->subDays($days);

        return static::where('created_at', '>=', $startDate)
            ->whereNotNull('referrer')
            ->where('referrer', '!=', '')
            ->selectRaw('referrer, COUNT(*) as views')
            ->groupBy('referrer')
            ->orderBy('views', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get most viewed content
     */
    public static function getMostViewedContent($type = null, $days = 30, $limit = 10)
    {
        $query = static::where('created_at', '>=', Carbon::now()->subDays($days))
            ->whereNotNull('viewable_type')
            ->whereNotNull('viewable_id');

        if ($type) {
            $query->where('viewable_type', $type);
        }

        return $query->selectRaw('viewable_type, viewable_id, COUNT(*) as views')
            ->groupBy('viewable_type', 'viewable_id')
            ->orderBy('views', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Scope for recent views
     */
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', Carbon::now()->subDays($days));
    }

    /**
     * Scope for views by type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('viewable_type', $type);
    }
}
