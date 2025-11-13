<?php

namespace App\Http\Middleware;

use App\Models\Redirect;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip for admin routes and API routes
        if ($request->is('admin/*') || $request->is('api/*')) {
            return $next($request);
        }

        // Get the current path
        $path = $request->path();

        // Check if there's a redirect for this URL
        $redirect = Redirect::findByUrl($path);

        if ($redirect) {
            // Record the hit
            $redirect->recordHit();

            // Perform the redirect
            return redirect($redirect->to_url, $redirect->status_code);
        }

        return $next($request);
    }
}
