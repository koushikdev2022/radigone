<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Closure;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class Authenticate extends  Middleware
{
    public function handle($request, Closure $next, ...$guards)
    {
        if ($request->segment(1) != 'api') {
            if (Auth::check()) {
                return $next($request);
            }
            return redirect()->route('user.login');

        }
        else {
            if (Auth::guard('api')->check()) {
                return $next($request);
            }
            return redirect()->route('api::login');
//            $this->redirectTo($request);
        }

    }





}
