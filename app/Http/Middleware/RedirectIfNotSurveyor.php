<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfNotSurveyor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = 'surveyor')
    {
        if (!Auth::guard($guard)->check()) {
            return redirect()->route('surveyor.login');
        }
        return $next($request);
    }
}
