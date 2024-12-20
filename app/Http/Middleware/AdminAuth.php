<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        // Check if the user is logged in and has an "admin" role
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request); // Allow access to the admin area
        }
        // If not authenticated as admin, redirect to /admin/login
        return redirect('/admin/login');
    }
}
