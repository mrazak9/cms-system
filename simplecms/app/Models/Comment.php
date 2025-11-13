<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'post_id',
        'user_id',
        'parent_id',
        'author_name',
        'author_email',
        'content',
        'ip_address',
        'user_agent',
        'status',
    ];

    /**
     * Status constants
     */
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_SPAM = 'spam';
    const STATUS_TRASH = 'trash';

    /**
     * Get the post that owns the comment
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Get the user that owns the comment (if logged in)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the parent comment
     */
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    /**
     * Get the child comments (replies)
     */
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id')->with('replies');
    }

    /**
     * Get all approved replies
     */
    public function approvedReplies()
    {
        return $this->replies()->where('status', self::STATUS_APPROVED);
    }

    /**
     * Scope to get only approved comments
     */
    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    /**
     * Scope to get only pending comments
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope to get only spam comments
     */
    public function scopeSpam($query)
    {
        return $query->where('status', self::STATUS_SPAM);
    }

    /**
     * Scope to get only trashed comments
     */
    public function scopeTrashed($query)
    {
        return $query->where('status', self::STATUS_TRASH);
    }

    /**
     * Scope to get only top-level comments (no parent)
     */
    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Check if comment is approved
     */
    public function isApproved()
    {
        return $this->status === self::STATUS_APPROVED;
    }

    /**
     * Check if comment is pending
     */
    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Check if comment is spam
     */
    public function isSpam()
    {
        return $this->status === self::STATUS_SPAM;
    }

    /**
     * Check if comment is trashed
     */
    public function isTrashed()
    {
        return $this->status === self::STATUS_TRASH;
    }

    /**
     * Approve the comment
     */
    public function approve()
    {
        $this->status = self::STATUS_APPROVED;
        return $this->save();
    }

    /**
     * Mark comment as spam
     */
    public function markAsSpam()
    {
        $this->status = self::STATUS_SPAM;
        return $this->save();
    }

    /**
     * Move comment to trash
     */
    public function moveToTrash()
    {
        $this->status = self::STATUS_TRASH;
        return $this->save();
    }

    /**
     * Get author name (from user or author_name field)
     */
    public function getAuthorNameAttribute()
    {
        return $this->user ? $this->user->name : $this->attributes['author_name'];
    }

    /**
     * Get author email (from user or author_email field)
     */
    public function getAuthorEmailAttribute()
    {
        return $this->user ? $this->user->email : $this->attributes['author_email'];
    }
}
