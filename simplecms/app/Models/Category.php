<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    /**
     * Boot method to clear cache on updates
     */
    protected static function boot()
    {
        parent::boot();

        static::saved(function () {
            Cache::tags(['categories', 'posts'])->flush();
        });

        static::deleted(function () {
            Cache::tags(['categories', 'posts'])->flush();
        });
    }

    // Relationships
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    // Helper method to get published posts count
    public function publishedPostsCount()
    {
        return $this->posts()->published()->count();
    }
}
