<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class Coordinator
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
        if (Auth::check() && Auth::user()->role == 3) {
            return $next($request);
        }
        elseif (Auth::check() && Auth::user()->role == 5) {
            return redirect('/admin');
        }
        elseif (Auth::check() && Auth::user()->role == 4) {
            return redirect('/manager');
        }
        elseif (Auth::check() && Auth::user()->role == 2) {
            return redirect('/student');
        }
        else {
            return redirect('/faculty');
        }
    }
}
