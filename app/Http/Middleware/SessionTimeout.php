<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SessionTimeout
{
    protected $timeout = 1800; // 30 minutes in seconds

    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $lastActivity = session('last_activity');
            $currentTime = time();

            // Check if session has timed out
            if ($lastActivity && ($currentTime - $lastActivity > $this->timeout)) {
                Auth::logout(); // Log out the user
                session()->flush(); // Clear session data
                return redirect('/login')->with('message', 'You have been logged out due to inactivity.');
            }

            // Update the last activity timestamp
            session(['last_activity' => $currentTime]);
        }

        return $next($request);
    }
}
