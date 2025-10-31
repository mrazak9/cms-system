<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu_id',
        'parent_id',
        'title',
        'url',
        'page_id',
        'post_id',
        'category_id',
        'type',
        'target',
        'order',
        'css_class',
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    // Relationships
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function parent()
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->orderBy('order');
    }

    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function category()
    {
        return $this->belongsTo(\App\Models\Category::class);
    }

    // Scopes
    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    // Helper method to get the actual URL
    public function getUrl()
    {
        if ($this->type === 'page' && $this->page) {
            return route('page.show', $this->page->slug);
        } elseif ($this->type === 'post' && $this->post) {
            return route('blog.show', $this->post->slug);
        } elseif ($this->type === 'category' && $this->category) {
            return route('blog.category', $this->category->slug);
        }
        return $this->url ?: '#';
    }
}
