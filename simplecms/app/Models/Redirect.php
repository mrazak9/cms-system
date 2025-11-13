<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Redirect extends Model
{
    use HasFactory;

    protected $fillable = [
        'from_url',
        'to_url',
        'status_code',
        'is_active',
        'hits',
        'last_used_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_used_at' => 'datetime',
    ];

    /**
     * Boot method to clear cache on updates
     */
    protected static function boot()
    {
        parent::boot();

        static::saved(function () {
            Cache::forget('active_redirects');
        });

        static::deleted(function () {
            Cache::forget('active_redirects');
        });
    }

    /**
     * Scope for active redirects
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Find redirect by source URL
     */
    public static function findByUrl($url)
    {
        $redirects = Cache::remember('active_redirects', 3600, function () {
            return static::active()->get()->keyBy('from_url');
        });

        // Normalize URL
        $normalizedUrl = '/' . trim($url, '/');

        return $redirects->get($normalizedUrl);
    }

    /**
     * Record a hit on this redirect
     */
    public function recordHit()
    {
        $this->increment('hits');
        $this->update(['last_used_at' => now()]);
    }
}
