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
}
