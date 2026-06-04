<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckMaintenanceMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if maintenance mode is enabled in settings
        $maintenanceMode = get_setting('MAINTENANCE_MODE');

        // If maintenance mode is OFF, just proceed
        if ($maintenanceMode != '1' && $maintenanceMode !== true) {
            return $next($request);
        }

        // Allow access to admin panel routes
        if ($request->is('admin*') || str_starts_with($request->path(), 'admin')) {
            return $next($request);
        }

        // Removed the Auth::guard('admin')->check() bypass to ensure 
        // admins also see the maintenance page on the frontend.

        return response()->view('errors.maintenance', [], 503);
    }
}
