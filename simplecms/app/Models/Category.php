<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

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
