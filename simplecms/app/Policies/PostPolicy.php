<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    /**
     * Determine if the user can view any posts.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('posts.view');
    }

    /**
     * Determine if the user can view a specific post.
     */
    public function view(User $user, Post $post): bool
    {
        return $user->can('posts.view');
    }

    /**
     * Determine if the user can create posts.
     */
    public function create(User $user): bool
    {
        return $user->can('posts.create');
    }

    /**
     * Determine if the user can update the post.
     *
     * Admin and Editor can edit all posts (has posts.edit-all permission)
     * Author can only edit their own posts (has posts.edit permission)
     */
    public function update(User $user, Post $post): bool
    {
        // Admin and Editor can edit all posts
        if ($user->can('posts.edit-all')) {
            return true;
        }

        // Author can only edit own posts
        if ($user->can('posts.edit') && $post->author_id === $user->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine if the user can delete the post.
     *
     * Admin and Editor can delete all posts (has posts.delete-all permission)
     * Author can only delete their own posts (has posts.delete permission)
     */
    public function delete(User $user, Post $post): bool
    {
        // Admin and Editor can delete all posts
        if ($user->can('posts.delete-all')) {
            return true;
        }

        // Author can only delete own posts
        if ($user->can('posts.delete') && $post->author_id === $user->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine if the user can publish posts.
     */
    public function publish(User $user): bool
    {
        return $user->can('posts.publish');
    }
}
