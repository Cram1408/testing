<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('admin.login')->with('error', 'Please log in to access the Super Admin panel.');
        }

        if (auth()->user()->is_deactivated) {
            auth()->logout();
            return redirect()->route('admin.login')->with('error', 'Your administrator account has been deactivated. Please contact the Super Admin.');
        }

        if (!auth()->user()->isSuperAdmin()) {
            return redirect()->route('admin.dashboard')->with('error', 'Access denied. Super Administrator privileges are required to access this module.');
        }

        return $next($request);
    }
}

