<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminPermission
{
    /**
     * Handle an incoming request with granular module permission check.
     */
    public function handle(Request $request, Closure $next, string $module): Response
    {
        if (!auth()->check()) {
            return redirect()->route('admin.login')->with('error', 'Please log in to access the admin portal.');
        }

        $user = auth()->user();

        if ($user->is_deactivated) {
            auth()->logout();
            return redirect()->route('admin.login')->with('error', 'Your account has been deactivated.');
        }

        if (!$user->isAdmin()) {
            return redirect()->route('home')->with('error', 'Administrator access required.');
        }

        if (!$user->hasPermission($module)) {
            return redirect()->route('admin.dashboard')->with('error', "Access denied. You do not have permission to manage the " . ucfirst($module) . " module. Contact your Super Admin for access.");
        }

        return $next($request);
    }
}

