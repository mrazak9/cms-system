<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_id',
        'section_template_id',
        'order',
        'content',
        'is_visible',
    ];

    protected $casts = [
        'content' => 'array',
        'is_visible' => 'boolean',
        'order' => 'integer',
    ];

    // Relationships
    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    public function sectionTemplate()
    {
        return $this->belongsTo(SectionTemplate::class);
    }

    // Scopes
    public function scopeVisible($query)
    {
        return $query->where('is_visible', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}
