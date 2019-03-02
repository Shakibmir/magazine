<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

use Illuminate\Support\Facades\Session;

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

            session()->flash('message', "You don't have the required permission to view the requested page!");
            Session::flash('type', 'danger');
            return redirect('/admin');
        }
        elseif (Auth::check() && Auth::user()->role == 4) {

            session()->flash('message', "You don't have the required permission to view the requested page!");
            Session::flash('type', 'danger');
            return redirect('/manager');
        }
        elseif (Auth::check() && Auth::user()->role == 2) {

            session()->flash('message', "You don't have the required permission to view the requested page!");
            Session::flash('type', 'danger');
            return redirect('/student');
        }
        else {

            session()->flash('message', "You don't have the required permission to view the requested page!");
            Session::flash('type', 'danger');
            return redirect('/faculty');
        }
    }
}
