<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
    ];

    /**
     * Boot method to clear cache on updates
     */
    protected static function boot()
    {
        parent::boot();

        static::saved(function () {
            Cache::tags(['settings'])->flush();
        });

        static::deleted(function () {
            Cache::tags(['settings'])->flush();
        });
    }

    // Scopes
    public function scopeByGroup($query, $group)
    {
        return $query->where('group', $group);
    }

    public function scopeByKey($query, $key)
    {
        return $query->where('key', $key);
    }

    // Helper methods for getting/setting values
    public static function get($key, $default = null)
    {
        $settings = Cache::tags(['settings'])->remember('all_settings', 86400, function () {
            return static::pluck('value', 'key')->toArray();
        });

        if (!isset($settings[$key])) {
            return $default;
        }

        // Get type for casting
        $setting = static::where('key', $key)->first();
        $type = $setting ? $setting->type : 'text';

        // Cast value based on type
        return static::castValue($settings[$key], $type);
    }

    public static function set($key, $value, $type = 'text', $group = 'general')
    {
        $result = static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'group' => $group,
            ]
        );

        // Clear cache
        Cache::tags(['settings'])->flush();

        return $result;
    }

    public static function getGroup($group)
    {
        return Cache::tags(['settings'])->remember("settings_group_{$group}", 86400, function () use ($group) {
            return static::where('group', $group)->pluck('value', 'key');
        });
    }

    // Cast value based on type
    protected static function castValue($value, $type)
    {
        switch ($type) {
            case 'boolean':
                return filter_var($value, FILTER_VALIDATE_BOOLEAN);
            case 'number':
            case 'integer':
                return (int) $value;
            case 'float':
                return (float) $value;
            case 'json':
                return json_decode($value, true);
            case 'array':
                return is_array($value) ? $value : json_decode($value, true);
            default:
                return $value;
        }
    }
}
