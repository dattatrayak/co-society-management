<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Models\User;

class AdminController extends Controller
{
    // Show login form
    public function login()
    {
        return view('auth.login');
    }

    // Authenticate the admin user
    public function authenticate(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'User not found.']);
        }

        // Check if the user is blocked
        if ($user->blocked_until && Carbon::now()->lessThan($user->blocked_until)) {
            $blockedTime = $user->blocked_until->diffForHumans();
            return back()->withErrors(['email' => "User is blocked. Try again $blockedTime."]);
        }
        // Verify the password
        if (!Hash::check($request->password, $user->password)) {
            $user->increment('failed_attempts');

            // Block the user if 5 attempts reached
            if ($user->failed_attempts >= 5) {
                $user->update([
                    'blocked_until' => Carbon::now()->addMinutes(30),
                    'failed_attempts' => 0,
                ]);

                // Send email notification
               // $this->sendBlockedEmail($user);

                return back()->withErrors(['email' => 'Too many failed attempts. User is blocked for 30 minutes.']);
            }

            return back()->withErrors(['password' => 'Incorrect password.']);
        }

        // Reset failed attempts on successful login
        $user->update(['failed_attempts' => 0, 'blocked_until' => null]);

        // Authenticate and redirect
        Auth::login($user);
        $request->session()->regenerate();
      return redirect( 'admin/dashboard');
    }

    // Admin dashboard
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    protected function sendBlockedEmail($user)
    {
        $details = [
            'subject' => 'Account Blocked',
            'body' => 'Your account has been blocked due to multiple failed login attempts. Please try again after 30 minutes.',
        ];

        Mail::send([], [], function ($message) use ($user, $details) {
            $message->to($user->email)
                    ->subject($details['subject'])
                    ->setBody($details['body']);
        });
    }


    /**
     * Logout the authenticated user.
     */
    public function logout(Request $request)
    {
        Auth::logout(); // Logs the user out

        // Invalidate the session and regenerate the CSRF token
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect to login page
        return redirect('/admin/login')->with('status', 'You have been logged out successfully.');
    }
}
