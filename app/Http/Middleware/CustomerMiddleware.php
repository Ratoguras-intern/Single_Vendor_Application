<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomerMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (auth()->user()->status === 'blocked') {
            auth()->logout();
            return redirect()->route('login');
        }

        if (auth()->user()->role !== 'customer') {
            abort(403);
        }

        return $next($request);
    }
}
