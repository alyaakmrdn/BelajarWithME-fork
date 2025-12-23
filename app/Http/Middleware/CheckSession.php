<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CheckSession
{
     public function handle($request, Closure $next)
    {
        if (
            !Session::has('uid') ||
            !Session::has('role') && !$request->routeIs('login')
        ) {
            Session::flush();
            return redirect('/login')->with('error', 'Session expired');
        }

        return $next($request);
    }
}
