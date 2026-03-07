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
     * @param  string  $guard  The guard name (admin or client)
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, string $guard)
    {
        // Set guard-specific session cookie name
        config([
            'session.cookie' => 'flashcard_learning_hub_' . $guard . '_session'
        ]);

        return $next($request);
    }
}
