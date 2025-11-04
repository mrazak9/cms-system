<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Admin Middleware
 *
 * This middleware checks if the authenticated user has any admin-related role
 * (admin, editor, author) using Spatie Permission package.
 * Specific permission checks are handled by controller middleware.
 */
class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect('/login')->with('error', 'Please login to access admin panel.');
        }

        // Check if user has any admin-related role
        // (admin, editor, or author can access admin panel)
        // Specific permissions are checked by controller middleware
        if (!auth()->user()->hasAnyRole(['admin', 'editor', 'author'])) {
            abort(403, 'Unauthorized access. You need admin, editor, or author role to access this area.');
        }

        return $next($request);
    }
}
