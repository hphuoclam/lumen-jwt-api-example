<?php

namespace App\Policies;

use App\Post;
use App\User;

class UserPolicy
{
    /**
     * Determine if the given user can create posts.
     *
     * @param  \App\User  $user
     * @return bool
     */
    public function profile(User $user)
    {
        // As long as the user is real, allowed
        return $user->id != null;
    }

    /**
     * Determine if the given user can create posts.
     *
     * @param  \App\User  $user
     * @return bool
     */
    public function lists(User $user)
    {
        // As long as the user is real, allowed
        return $user->id != null;
    }
}