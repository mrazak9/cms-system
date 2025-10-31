<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThemeSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'theme_id',
        'key',
        'value',
        'type',
        'group',
        'order',
    ];

    /**
     * Get the theme that owns the setting
     */
    public function theme()
    {
        return $this->belongsTo(Theme::class);
    }

    /**
     * Get settings for a specific theme as key-value array
     */
    public static function getForTheme($themeId)
    {
        return static::where('theme_id', $themeId)
            ->orderBy('order')
            ->get()
            ->pluck('value', 'key')
            ->toArray();
    }

    /**
     * Get settings grouped by group
     */
    public static function getGroupedForTheme($themeId)
    {
        return static::where('theme_id', $themeId)
            ->orderBy('order')
            ->get()
            ->groupBy('group');
    }
}
