<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Usage: role:super_admin  or  role:admin,super_admin
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if ($user->status === 'blocked') {
            Auth::logout();
            return redirect()->route('login');
        }

        if ($user->is_frozen) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Your account is frozen. Please contact support.');
        }

        if (!in_array($user->role, $roles)) {
            abort(403);
        }

        return $next($request);
    }
}
