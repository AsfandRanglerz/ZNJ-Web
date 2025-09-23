<?php

namespace App\Http\Middleware;

use App\Http\Controllers\web\RecruiterController;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class Recruiter
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(auth::guard('user')->check()){
            return $next($request);
        }else{
        return redirect('recruiter/login');
     }
    }
}
