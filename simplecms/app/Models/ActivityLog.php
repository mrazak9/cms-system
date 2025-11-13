<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'log_type',
        'subject_type',
        'subject_id',
        'description',
        'properties',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'properties' => 'array',
    ];

    /**
     * Log type constants
     */
    const TYPE_CREATE = 'create';
    const TYPE_UPDATE = 'update';
    const TYPE_DELETE = 'delete';
    const TYPE_LOGIN = 'login';
    const TYPE_LOGOUT = 'logout';
    const TYPE_RESTORE = 'restore';

    /**
     * Get the user that performed the activity
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the subject model (polymorphic)
     */
    public function subject()
    {
        return $this->morphTo('subject');
    }

    /**
     * Scope to filter by log type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('log_type', $type);
    }

    /**
     * Scope to filter by user
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to filter by subject
     */
    public function scopeForSubject($query, $subjectType, $subjectId = null)
    {
        $query->where('subject_type', $subjectType);

        if ($subjectId) {
            $query->where('subject_id', $subjectId);
        }

        return $query;
    }

    /**
     * Scope to get recent activities
     */
    public function scopeRecent($query, $limit = 50)
    {
        return $query->orderBy('created_at', 'desc')->limit($limit);
    }

    /**
     * Static method to log activity
     */
    public static function log($type, $description, $subject = null, $properties = null)
    {
        return static::create([
            'user_id' => auth()->id(),
            'log_type' => $type,
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id' => $subject ? $subject->id : null,
            'description' => $description,
            'properties' => $properties,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Get the old values from properties
     */
    public function getOldValues()
    {
        return $this->properties['old'] ?? [];
    }

    /**
     * Get the new values from properties
     */
    public function getNewValues()
    {
        return $this->properties['new'] ?? [];
    }

    /**
     * Get the changes between old and new
     */
    public function getChanges()
    {
        $old = $this->getOldValues();
        $new = $this->getNewValues();

        $changes = [];

        foreach ($new as $key => $value) {
            if (!isset($old[$key]) || $old[$key] !== $value) {
                $changes[$key] = [
                    'old' => $old[$key] ?? null,
                    'new' => $value,
                ];
            }
        }

        return $changes;
    }
}
