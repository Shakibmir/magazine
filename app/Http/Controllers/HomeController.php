<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        $data['title'] = "Dashboard";

        if (Auth::user()->role == 5) {
            return redirect('/admin');
        }elseif (Auth::user()->role == 4) {
            return redirect('/manager');
        }elseif (Auth::user()->role == 3) {
            return redirect('/coordinator');
        }elseif (Auth::user()->role == 2) {
            return redirect('/student');
        }elseif (Auth::user()->role == 1) {
            return redirect('/faculty');
        }
        return view('dashboard', $data);
    }
}
