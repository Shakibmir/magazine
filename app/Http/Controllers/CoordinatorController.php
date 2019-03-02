<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CoordiantorController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');    
        $this->middleware('coordiantor');

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        $data['role'] = "Coordiantor";
        $data['title'] = "Dashboard";
        return view('home', $data);
    }
}
