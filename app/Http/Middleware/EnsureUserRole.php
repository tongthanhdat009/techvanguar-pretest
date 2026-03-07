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

        // Redirect admin to admin panel when trying to access client routes
        if ($user->role === 'admin' && $role === 'client') {
            return redirect()->route('admin.overview')->with('status', 'Admins should use the admin panel.');
        }

        // Redirect client to client panel when trying to access admin routes
        if ($user->role === 'client' && $role === 'admin') {
            return redirect()->route('client.dashboard')->with('status', 'Clients should use the learning portal.');
        }

        if ($user->role !== $role) {
            return $request->expectsJson()
                ? response()->json(['message' => 'You are not authorized to access this resource.'], Response::HTTP_FORBIDDEN)
                : abort(Response::HTTP_FORBIDDEN, 'You do not have permission to access this resource.');
        }

        return $next($request);
    }
}
