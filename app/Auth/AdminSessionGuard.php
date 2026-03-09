<?php

namespace App\Auth;

use Illuminate\Auth\SessionGuard;
use Illuminate\Contracts\Auth\Authenticatable;

class AdminSessionGuard extends SessionGuard
{
    /**
     * Get the user from the session and validate they are an admin.
     * Only validates and returns null if user doesn't have admin role or is banned.
     */
    public function user(): ?Authenticatable
    {
        // Use parent's cached user if available
        if ($this->user) {
            return $this->user;
        }

        // Get user from parent (which handles caching)
        $user = parent::user();

        // Validate user is admin
        if ($user && method_exists($user, 'isAdmin') && !$user->isAdmin()) {
            // Don't call logout() here as it may cause recursion
            // Just clear and return null
            $this->user = null;
            return null;
        }

        // Validate user is not banned
        if ($user && method_exists($user, 'isBanned') && $user->isBanned()) {
            $this->user = null;
            return null;
        }

        return $user;
    }
}
