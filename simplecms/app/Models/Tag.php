<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    /**
     * Get the posts that belong to the tag
     */
    public function posts()
    {
        return $this->belongsToMany(Post::class, 'post_tag')
                    ->withTimestamps();
    }

    /**
     * Get published posts count
     */
    public function getPostsCountAttribute()
    {
        return $this->posts()->where('is_published', true)->count();
    }

    /**
     * Automatically generate slug from name
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($tag) {
            if (empty($tag->slug)) {
                $tag->slug = Str::slug($tag->name);
            }
        });

        static::updating(function ($tag) {
            if ($tag->isDirty('name') && empty($tag->slug)) {
                $tag->slug = Str::slug($tag->name);
            }
        });
    }
}
