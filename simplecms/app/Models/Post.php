<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasRevisions;

class Post extends Model
{
    use HasFactory, HasRevisions;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'category_id',
        'author_id',
        'is_published',
        'is_featured',
        'featured_order',
        'published_at',
        'scheduled_publish_at',
        'workflow_status',
        'meta_description',
        'meta_keywords',
        'views_count',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
        'scheduled_publish_at' => 'datetime',
        'views_count' => 'integer',
        'featured_order' => 'integer',
    ];

    /**
     * Fields that should be tracked for revisions
     */
    protected $revisionable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'category_id',
        'is_published',
        'is_featured',
        'featured_order',
        'published_at',
        'meta_description',
        'meta_keywords',
    ];

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function menuItems()
    {
        return $this->hasMany(MenuItem::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function approvedComments()
    {
        return $this->comments()->where('status', Comment::STATUS_APPROVED);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'post_tag')
                    ->withTimestamps();
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('is_published', true)
                    ->where('published_at', '<=', now());
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeByAuthor($query, $authorId)
    {
        return $query->where('author_id', $authorId);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true)
                    ->orderBy('featured_order', 'asc');
    }

    public function scopeByTag($query, $tagSlug)
    {
        return $query->whereHas('tags', function ($q) use ($tagSlug) {
            $q->where('slug', $tagSlug);
        });
    }

    // Workflow status scopes
    public function scopeByWorkflowStatus($query, $status)
    {
        return $query->where('workflow_status', $status);
    }

    public function scopeDraft($query)
    {
        return $query->where('workflow_status', 'draft');
    }

    public function scopePendingReview($query)
    {
        return $query->where('workflow_status', 'pending_review');
    }

    public function scopeScheduled($query)
    {
        return $query->where('workflow_status', 'scheduled');
    }

    public function scopeArchived($query)
    {
        return $query->where('workflow_status', 'archived');
    }

    // Mutators & Accessors
    public function incrementViewsCount()
    {
        $this->increment('views_count');
    }

    /**
     * Check if post is scheduled for future publishing
     */
    public function isScheduled()
    {
        return $this->workflow_status === 'scheduled'
            && $this->scheduled_publish_at
            && $this->scheduled_publish_at->isFuture();
    }

    /**
     * Get workflow status badge color
     */
    public function getWorkflowStatusBadgeClass()
    {
        return match($this->workflow_status) {
            'draft' => 'badge-secondary',
            'pending_review' => 'badge-warning',
            'scheduled' => 'badge-info',
            'published' => 'badge-success',
            'archived' => 'badge-dark',
            default => 'badge-secondary',
        };
    }

    /**
     * Get workflow status display label
     */
    public function getWorkflowStatusLabel()
    {
        return match($this->workflow_status) {
            'draft' => 'Draft',
            'pending_review' => 'Pending Review',
            'scheduled' => 'Scheduled',
            'published' => 'Published',
            'archived' => 'Archived',
            default => ucfirst($this->workflow_status),
        };
    }

    /**
     * PageViews relationship (polymorphic)
     */
    public function pageViews()
    {
        return $this->morphMany(PageView::class, 'viewable');
    }
}
