<?php

namespace App\Policies;

use App\Models\Media;
use App\Models\User;

class MediaPolicy
{
    /**
     * Determine if the user can view any media.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('media.view');
    }

    /**
     * Determine if the user can view a specific media.
     */
    public function view(User $user, Media $media): bool
    {
        return $user->can('media.view');
    }

    /**
     * Determine if the user can upload media.
     */
    public function create(User $user): bool
    {
        return $user->can('media.upload');
    }

    /**
     * Determine if the user can update the media.
     *
     * Admin and Editor can edit all media (has media.edit-all permission)
     * Author can only edit their own media (has media.edit permission)
     */
    public function update(User $user, Media $media): bool
    {
        // Admin and Editor can edit all media
        if ($user->can('media.edit-all')) {
            return true;
        }

        // Author can only edit own media
        if ($user->can('media.edit') && $media->uploaded_by === $user->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine if the user can delete the media.
     *
     * Admin and Editor can delete all media (has media.delete-all permission)
     * Author can only delete their own media (has media.delete permission)
     */
    public function delete(User $user, Media $media): bool
    {
        // Admin and Editor can delete all media
        if ($user->can('media.delete-all')) {
            return true;
        }

        // Author can only delete own media
        if ($user->can('media.delete') && $media->uploaded_by === $user->id) {
            return true;
        }

        return false;
    }
}
