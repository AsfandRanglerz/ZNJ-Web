<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckAuth
{
    /**
     * Handle an incoming request.
     */
    public function handle($request, Closure $next)
    {
        // agar user login nahi hai to login page pr redirect kar do
        if (!Auth::check()) {
            return redirect()->route('recruiter.login.form'); // <-- apne login route ka naam
        }

        return $next($request);
    }
}
