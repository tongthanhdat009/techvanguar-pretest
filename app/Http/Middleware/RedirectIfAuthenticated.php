<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (auth()->guard($guard)->check()) {
                $guardName = $guard ?? config('auth.defaults.guard');

                return match($guardName) {
                    'admin' => redirect()->route('admin.overview'),
                    'client' => redirect()->route('client.portal'),
                    default => redirect()->route('home'),
                };
            }
        }

        return $next($request);
    }
}
