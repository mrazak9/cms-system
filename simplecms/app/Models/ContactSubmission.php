<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactSubmission extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'subject',
        'message',
        'ip_address',
        'user_agent',
        'status',
    ];

    /**
     * Status constants
     */
    const STATUS_UNREAD = 'unread';
    const STATUS_READ = 'read';

    /**
     * Mark submission as read
     *
     * @return bool
     */
    public function markAsRead()
    {
        $this->status = self::STATUS_READ;
        return $this->save();
    }

    /**
     * Mark submission as unread
     *
     * @return bool
     */
    public function markAsUnread()
    {
        $this->status = self::STATUS_UNREAD;
        return $this->save();
    }

    /**
     * Check if submission is read
     *
     * @return bool
     */
    public function isRead()
    {
        return $this->status === self::STATUS_READ;
    }

    /**
     * Check if submission is unread
     *
     * @return bool
     */
    public function isUnread()
    {
        return $this->status === self::STATUS_UNREAD;
    }

    /**
     * Scope to get only unread submissions
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnread($query)
    {
        return $query->where('status', self::STATUS_UNREAD);
    }

    /**
     * Scope to get only read submissions
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRead($query)
    {
        return $query->where('status', self::STATUS_READ);
    }
}
