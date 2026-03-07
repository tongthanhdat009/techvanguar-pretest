<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();

        if (! $user) {
            return $request->expectsJson()
                ? response()->json(['message' => 'Authentication required.'], Response::HTTP_UNAUTHORIZED)
                : redirect()->route('home');
        }

        if ($user->role !== $role) {
            return $request->expectsJson()
                ? response()->json(['message' => 'You are not authorized to access this resource.'], Response::HTTP_FORBIDDEN)
                : abort(Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
