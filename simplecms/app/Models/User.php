<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'bio',
        'phone',
        'location',
        'social_links',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'social_links' => 'array',
        'locked_until' => 'datetime',
        'last_login_at' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function pages()
    {
        return $this->hasMany(Page::class, 'created_by');
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'author_id');
    }

    public function media()
    {
        return $this->hasMany(Media::class, 'uploaded_by');
    }

    /**
     * Get the user's avatar URL
     */
    public function getAvatarUrl()
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }

        // Return default avatar or Gravatar
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=7F9CF5&background=EBF4FF';
    }

    /**
     * Get social media link
     */
    public function getSocialLink($platform)
    {
        $links = $this->social_links ?? [];
        return $links[$platform] ?? null;
    }

    /**
     * Set social media link
     */
    public function setSocialLink($platform, $url)
    {
        $links = $this->social_links ?? [];
        $links[$platform] = $url;
        $this->social_links = $links;
    }

    /**
     * Security Methods
     */

    /**
     * Check if the account is locked
     */
    public function isLocked()
    {
        return $this->locked_until && $this->locked_until->isFuture();
    }

    /**
     * Lock the account for a specified duration (in minutes)
     */
    public function lockAccount($minutes = 15)
    {
        $this->locked_until = now()->addMinutes($minutes);
        $this->save();

        ActivityLog::log(
            ActivityLog::TYPE_LOGIN,
            "Account locked due to multiple failed login attempts",
            $this
        );
    }

    /**
     * Unlock the account
     */
    public function unlockAccount()
    {
        $this->locked_until = null;
        $this->failed_login_attempts = 0;
        $this->save();

        ActivityLog::log(
            ActivityLog::TYPE_LOGIN,
            "Account unlocked",
            $this
        );
    }

    /**
     * Increment failed login attempts
     */
    public function incrementFailedLoginAttempts()
    {
        $this->failed_login_attempts++;
        $this->save();

        // Auto-lock after 5 failed attempts
        if ($this->failed_login_attempts >= 5) {
            $this->lockAccount(15); // Lock for 15 minutes
        }
    }

    /**
     * Reset failed login attempts
     */
    public function resetFailedLoginAttempts()
    {
        $this->failed_login_attempts = 0;
        $this->save();
    }

    /**
     * Update last login information
     */
    public function updateLastLogin()
    {
        $this->last_login_at = now();
        $this->last_login_ip = request()->ip();
        $this->failed_login_attempts = 0;
        $this->save();
    }

    /**
     * Get time remaining until account is unlocked
     */
    public function getLockedTimeRemaining()
    {
        if (!$this->isLocked()) {
            return null;
        }

        return $this->locked_until->diffForHumans();
    }
}
