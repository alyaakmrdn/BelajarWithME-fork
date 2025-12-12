<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CheckSession
{
    public function handle(Request $request, Closure $next)
    {
        if (!Session::has('uid')) {
            return redirect('/login')->with('error', 'Please login first.');
        }

        return $next($request);
    }
}
