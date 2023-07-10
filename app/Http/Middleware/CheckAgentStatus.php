<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckAgentStatus
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
//        return $next($request);
         if (Auth::guard('agent')->check()) {
             $surveyor = Auth::guard('agent')->user();
//             return $next($request);
              if ($surveyor->status && $surveyor->tv  && $surveyor->sv && $surveyor->ev && $surveyor->rv) {
                  return $next($request);
              } else {
                  return redirect()->route('agent.authorization');
              }
         }
         abort(403);
    }
}
