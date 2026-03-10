<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetSessionCookie
{
    /**
     * Handle an incoming request.
     *
     * Sets guard-specific session cookie names to separate admin and client sessions.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ?string $guard = null)
    {
        $guard ??= $this->resolveGuardFromRequest($request);

        if ($guard !== null) {
            $cookieName = 'flashcard_learning_hub_' . $guard . '_session';
            config(['session.cookie' => $cookieName]);

            // Also update session manager for current request
            if (app()->resolved('session')) {
                app('session')->setCookieName($cookieName);
            }
        }

        return $next($request);
    }

    private function resolveGuardFromRequest(Request $request): ?string
    {
        if ($request->is('admin') || $request->is('admin/*') || $request->is('login/admin')) {
            return 'admin';
        }

        if (
            $request->is('client')
            || $request->is('client/*')
            || $request->is('login/client')
            || $request->is('register')
            || $request->is('register/*')
            || $request->is('forgot-password')
            || $request->is('forgot-password/*')
            || $request->is('logout')
        ) {
            return 'client';
        }

        return null;
    }
}
