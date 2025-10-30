<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'thumbnail',
        'author',
        'version',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function pages()
    {
        return $this->hasMany(Page::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Helper method to activate this theme
    public function activate()
    {
        // Deactivate all other themes
        static::where('id', '!=', $this->id)->update(['is_active' => false]);

        // Activate this theme
        $this->update(['is_active' => true]);
    }
}
