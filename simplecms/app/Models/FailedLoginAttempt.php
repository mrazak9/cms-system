<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class FailedLoginAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'ip_address',
        'user_agent',
        'reason',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Log a failed login attempt
     */
    public static function logAttempt($email, $reason = 'invalid_credentials')
    {
        return static::create([
            'email' => $email,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'reason' => $reason,
        ]);
    }

    /**
     * Get recent failed attempts for an email
     */
    public static function getRecentAttempts($email, $minutes = 15)
    {
        return static::where('email', $email)
            ->where('created_at', '>=', Carbon::now()->subMinutes($minutes))
            ->count();
    }

    /**
     * Get recent failed attempts for an IP
     */
    public static function getRecentAttemptsForIP($ipAddress, $minutes = 15)
    {
        return static::where('ip_address', $ipAddress)
            ->where('created_at', '>=', Carbon::now()->subMinutes($minutes))
            ->count();
    }

    /**
     * Clear old attempts (older than specified days)
     */
    public static function clearOldAttempts($days = 30)
    {
        return static::where('created_at', '<', Carbon::now()->subDays($days))->delete();
    }

    /**
     * Get failed attempts by email with pagination
     */
    public function scopeByEmail($query, $email)
    {
        return $query->where('email', $email);
    }

    /**
     * Get failed attempts by IP
     */
    public function scopeByIP($query, $ip)
    {
        return $query->where('ip_address', $ip);
    }

    /**
     * Get recent attempts within timeframe
     */
    public function scopeRecent($query, $minutes = 60)
    {
        return $query->where('created_at', '>=', Carbon::now()->subMinutes($minutes));
    }

    /**
     * Get statistics for dashboard
     */
    public static function getStatistics()
    {
        return [
            'total_today' => static::whereDate('created_at', Carbon::today())->count(),
            'total_week' => static::where('created_at', '>=', Carbon::now()->subWeek())->count(),
            'total_month' => static::where('created_at', '>=', Carbon::now()->subMonth())->count(),
            'unique_ips_today' => static::whereDate('created_at', Carbon::today())
                ->distinct('ip_address')
                ->count('ip_address'),
            'top_targeted_emails' => static::where('created_at', '>=', Carbon::now()->subWeek())
                ->selectRaw('email, COUNT(*) as count')
                ->groupBy('email')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->get(),
            'top_ips' => static::where('created_at', '>=', Carbon::now()->subWeek())
                ->selectRaw('ip_address, COUNT(*) as count')
                ->groupBy('ip_address')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->get(),
        ];
    }
}
