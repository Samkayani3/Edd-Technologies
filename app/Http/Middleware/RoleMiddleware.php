<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request; // Make sure this import is correct
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (Auth::check() && Auth::user()->role === $role) {
            return $next($request);
        }

        // Redirect user if not authorized
        return redirect('/home'); // Change this route based on your application logic
    }
}
