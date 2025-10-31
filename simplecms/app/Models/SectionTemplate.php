<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SectionTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'blade_view',
        'thumbnail',
        'default_fields',
        'fields',
        'is_active',
    ];

    protected $casts = [
        'default_fields' => 'array',
        'fields' => 'array',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function pageSections()
    {
        return $this->hasMany(PageSection::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }
}
