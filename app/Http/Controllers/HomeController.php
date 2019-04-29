<?php

namespace App\Http\Controllers;

use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use App\User;

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

    public function getChangePassword()
    {
        $data['title'] = "Password";
        return view('common.change-password', $data);
    }

    public function postChangePassword(Request $request)
    {
        if (!(Hash::check($request->get('current_password'), Auth::user()->password))) {
            // The passwords matches
            session()->flash('message', 'Your current password does not matches with the password you provided. Please try again.');
            Session::flash('type', 'danger');
            return redirect()->back();
        }
        if(strcmp($request->get('current_password'), $request->get('password')) == 0){
            //Current password and new password are same
            session()->flash('message', 'New Password cannot be same as your current password. Please choose a different password.');
            Session::flash('type', 'danger');
            return redirect()->back();
        }
        $validatedData = $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:6|confirmed',
        ]);
        //Change Password
        $user = Auth::user();
        $user->password = bcrypt($request->get('password'));
        $user->save();

        session()->flash('message', 'Password changed successfully !');
        Session::flash('type', 'success');
        return redirect()->back();
    }
}
