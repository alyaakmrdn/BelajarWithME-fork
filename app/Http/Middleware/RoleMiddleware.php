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
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Session::has('role')) {
            return redirect('/login')->with('error', 'Unauthorized access.');
        }

        $userRole = Session::get('role');

        if (!in_array($userRole, $roles)) {
            abort(403, 'Access denied.');
        }

        return $next($request);
    }
}
