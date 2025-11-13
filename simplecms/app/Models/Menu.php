<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Boot method to clear cache on updates
     */
    protected static function boot()
    {
        parent::boot();

        static::saved(function () {
            Cache::tags(['menus'])->flush();
        });

        static::deleted(function () {
            Cache::tags(['menus'])->flush();
        });
    }

    // Relationships
    public function menuItems()
    {
        return $this->hasMany(MenuItem::class)->whereNull('parent_id')->orderBy('order');
    }

    public function allItems()
    {
        return $this->hasMany(MenuItem::class)->orderBy('order');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByLocation($query, $location)
    {
        return $query->where('location', $location);
    }
}
