<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (Auth::user()->status === 'blocked') {
            Auth::logout();
            return redirect()->route('login');
        }

        if (!in_array(Auth::user()->role, ['admin', 'super_admin'])) {
            abort(403);
        }

        return $next($request);
    }
}
