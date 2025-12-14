<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     * $role can be 'admin', 'student', 'lecturer', 'parent'
     */
    public function handle(Request $request, Closure $next, $role)
    {
        if (!Session::has('uid') || Session::get('role') !== $role) {
            // Redirect to login or show forbidden
            return redirect('/login')->with('error', 'Access denied.');
        }

        return $next($request);
    }
}
