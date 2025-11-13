<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Revision extends Model
{
    use HasFactory;

    protected $fillable = [
        'revisionable_type',
        'revisionable_id',
        'user_id',
        'version',
        'old_values',
        'new_values',
        'description',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the parent revisionable model.
     */
    public function revisionable()
    {
        return $this->morphTo();
    }

    /**
     * Get the user who made this revision.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get revisions by version number.
     */
    public function scopeVersion($query, $version)
    {
        return $query->where('version', $version);
    }

    /**
     * Get the latest version number for a revisionable.
     */
    public static function getLatestVersion($type, $id)
    {
        return static::where('revisionable_type', $type)
            ->where('revisionable_id', $id)
            ->max('version') ?? 0;
    }

    /**
     * Get all revisions for a model.
     */
    public static function forModel($model)
    {
        return static::where('revisionable_type', get_class($model))
            ->where('revisionable_id', $model->id)
            ->orderBy('version', 'desc')
            ->get();
    }

    /**
     * Get the changes made in this revision.
     */
    public function getChanges()
    {
        $changes = [];
        $old = $this->old_values ?? [];
        $new = $this->new_values ?? [];

        foreach ($new as $key => $value) {
            $oldValue = $old[$key] ?? null;
            if ($oldValue !== $value) {
                $changes[$key] = [
                    'old' => $oldValue,
                    'new' => $value,
                ];
            }
        }

        return $changes;
    }

    /**
     * Get a summary of changes.
     */
    public function getChangesSummary()
    {
        $changes = $this->getChanges();
        if (empty($changes)) {
            return 'No changes';
        }

        $summary = [];
        foreach ($changes as $field => $change) {
            $summary[] = ucfirst(str_replace('_', ' ', $field));
        }

        return implode(', ', $summary);
    }

    /**
     * Check if a specific field was changed.
     */
    public function fieldChanged($field)
    {
        $changes = $this->getChanges();
        return isset($changes[$field]);
    }

    /**
     * Get the old value of a field.
     */
    public function getOldValue($field)
    {
        return $this->old_values[$field] ?? null;
    }

    /**
     * Get the new value of a field.
     */
    public function getNewValue($field)
    {
        return $this->new_values[$field] ?? null;
    }

    /**
     * Create a revision for a model.
     */
    public static function createRevision($model, $oldValues, $description = null)
    {
        $latestVersion = static::getLatestVersion(get_class($model), $model->id);

        return static::create([
            'revisionable_type' => get_class($model),
            'revisionable_id' => $model->id,
            'user_id' => auth()->id(),
            'version' => $latestVersion + 1,
            'old_values' => $oldValues,
            'new_values' => $model->getAttributes(),
            'description' => $description ?? 'Updated ' . class_basename($model),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
