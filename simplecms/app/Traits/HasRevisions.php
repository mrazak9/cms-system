<?php

namespace App\Traits;

use App\Models\Revision;

trait HasRevisions
{
    /**
     * Boot the trait.
     */
    protected static function bootHasRevisions()
    {
        // Create revision on update
        static::updating(function ($model) {
            if ($model->shouldCreateRevision()) {
                $original = $model->getOriginal();
                $changes = $model->getDirty();

                // Only create revision if there are actual changes to tracked fields
                $trackedFields = $model->getRevisionableFields();
                $relevantChanges = array_intersect_key($changes, array_flip($trackedFields));

                if (!empty($relevantChanges)) {
                    Revision::createRevision($model, $original);
                }
            }
        });
    }

    /**
     * Get all revisions for this model.
     */
    public function revisions()
    {
        return $this->morphMany(Revision::class, 'revisionable')->orderBy('version', 'desc');
    }

    /**
     * Get the latest revision.
     */
    public function latestRevision()
    {
        return $this->revisions()->latest()->first();
    }

    /**
     * Get a specific revision by version.
     */
    public function getRevision($version)
    {
        return $this->revisions()->where('version', $version)->first();
    }

    /**
     * Restore to a specific revision.
     */
    public function restoreToRevision($version)
    {
        $revision = $this->getRevision($version);

        if (!$revision) {
            return false;
        }

        // Get the values from that revision
        $values = $revision->new_values;

        // Only restore revisionable fields
        $trackedFields = $this->getRevisionableFields();
        $restoreData = array_intersect_key($values, array_flip($trackedFields));

        // Temporarily disable revision creation
        $this->disableRevisionCreation();

        // Update the model
        $this->update($restoreData);

        // Re-enable revision creation
        $this->enableRevisionCreation();

        // Create a new revision documenting the restore
        Revision::createRevision($this, $this->getOriginal(), "Restored to version {$version}");

        return true;
    }

    /**
     * Compare two revisions.
     */
    public function compareRevisions($version1, $version2)
    {
        $rev1 = $this->getRevision($version1);
        $rev2 = $this->getRevision($version2);

        if (!$rev1 || !$rev2) {
            return null;
        }

        $diff = [];
        $fields = $this->getRevisionableFields();

        foreach ($fields as $field) {
            $val1 = $rev1->new_values[$field] ?? null;
            $val2 = $rev2->new_values[$field] ?? null;

            if ($val1 !== $val2) {
                $diff[$field] = [
                    'version1' => $val1,
                    'version2' => $val2,
                ];
            }
        }

        return $diff;
    }

    /**
     * Get the fields that should be tracked for revisions.
     * Override this in your model to specify which fields to track.
     */
    public function getRevisionableFields()
    {
        return property_exists($this, 'revisionable')
            ? $this->revisionable
            : array_keys($this->getAttributes());
    }

    /**
     * Check if a revision should be created.
     */
    protected function shouldCreateRevision()
    {
        return !isset($this->skipRevision) || !$this->skipRevision;
    }

    /**
     * Temporarily disable revision creation.
     */
    public function disableRevisionCreation()
    {
        $this->skipRevision = true;
        return $this;
    }

    /**
     * Enable revision creation.
     */
    public function enableRevisionCreation()
    {
        $this->skipRevision = false;
        return $this;
    }

    /**
     * Get the number of revisions.
     */
    public function getRevisionsCount()
    {
        return $this->revisions()->count();
    }

    /**
     * Get the current version number.
     */
    public function getCurrentVersion()
    {
        return Revision::getLatestVersion(get_class($this), $this->id);
    }
}
