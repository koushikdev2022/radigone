<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckSurveyorStatus
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
        if (Auth::guard('surveyor')->check()) {
            $surveyor = Auth::guard('surveyor')->user();
            if ($surveyor->status && $surveyor->tv  && $surveyor->sv && $surveyor->ev && $surveyor->rv) {
                return $next($request);
            } else {
                return redirect()->route('surveyor.authorization');
            }
        }
        abort(403);
    }
}
