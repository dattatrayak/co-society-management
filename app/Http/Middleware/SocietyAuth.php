<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class SocietyAuth
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
        $user = Auth::guard('society_user')->user();


        // Check if the user is logged in and has an "admin" role
        if ($user && $user->userType->name === 'Manager') {
            return $next($request); // Allow access to the admin area
        }
        // If not authenticated as admin, redirect to /admin/login
        return redirect('/society/login');
    }
}
