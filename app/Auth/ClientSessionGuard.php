<?php

namespace App\Auth;

use Illuminate\Auth\SessionGuard;
use Illuminate\Contracts\Auth\Authenticatable;

class ClientSessionGuard extends SessionGuard
{
    /**
     * Get the user from the session and validate they are a client.
     * Only validates and returns null if user doesn't have client role.
     */
    public function user(): ?Authenticatable
    {
        // Use parent's cached user if available
        if ($this->user) {
            return $this->user;
        }

        // Get user from parent (which handles caching)
        $user = parent::user();

        // Validate user is client and is not banned
        if ($user && method_exists($user, 'isClient') && !$user->isClient()) {
            $this->user = null;
            return null;
        }

        if ($user && method_exists($user, 'isBanned') && $user->isBanned()) {
            $this->user = null;
            return null;
        }

        return $user;
    }
}
