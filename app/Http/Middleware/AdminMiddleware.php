<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('admin.login')->with('error', 'Please log in to access the admin portal.');
        }

        if (!auth()->user()->isAdmin()) {
            return redirect()->route('home')->with('error', 'Unauthorized. Administrator access required.');
        }

        if (auth()->user()->is_deactivated) {
            auth()->logout();
            return redirect()->route('admin.login')->with('error', 'Your account has been deactivated. Please contact the Super Admin.');
        }

        return $next($request);
    }
}
