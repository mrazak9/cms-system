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
        'published_at',
        'scheduled_publish_at',
        'workflow_status',
        'theme_id',
        'created_by',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'is_homepage' => 'boolean',
        'published_at' => 'datetime',
        'scheduled_publish_at' => 'datetime',
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

    /**
     * Check if page is scheduled for future publishing
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
