<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'meta_description',
        'meta_keywords',
        'is_published',
        'is_homepage',
        'theme_id',
        'created_by',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'is_homepage' => 'boolean',
    ];

    // Relationships
    public function sections()
    {
        return $this->hasMany(PageSection::class)->orderBy('order');
    }

    public function theme()
    {
        return $this->belongsTo(Theme::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function menuItems()
    {
        return $this->hasMany(MenuItem::class);
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeHomepage($query)
    {
        return $query->where('is_homepage', true);
    }

    /**
     * PageViews relationship (polymorphic)
     */
    public function pageViews()
    {
        return $this->morphMany(PageView::class, 'viewable');
    }
}
