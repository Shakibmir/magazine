<?php

namespace App\Http\Controllers;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use App\AcademicYear;
use App\Comment;
use App\Contribution;
use App\ConImg;
use App\Department;
use App\User;
use Zipper;

use Carbon\Carbon;

class ManagerController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');    
        $this->middleware('manager');

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

        
        $cay = AcademicYear::whereYear('opening_date', '=', date('Y'))->first();
        $data['cay'] = $cay;

        $urep = Contribution::whereAcademicYear($cay->id)->with('user')->get()->groupBy('user_id')->count(); // total number of contributor all dep
        $data['ureps'] = $urep;

        $data['totalcons'] = Contribution::all()->count();
        $data['allcons'] = Contribution::whereAcademicYear($cay->id)->get()->count();
        $data['userc'] = User::whereRole(2)->get()->count(); //User count
        $data['comcons'] = Contribution::whereAcademicYear($cay->id)->whereIn('status',[2,4])->count();
        $data['apvcons'] = Contribution::whereAcademicYear($cay->id)->whereIn('status',[3,4])->count();
        $data['pencons'] = Contribution::whereAcademicYear($cay->id)->whereNotIn('status',[3,4])->count();
        $data['nocoms'] = Contribution::whereAcademicYear($cay->id)->whereNotIn('status',[2,4])->count();
        $data['nocomsl'] = Contribution::whereAcademicYear($cay->id)->whereNotIn('status',[2,4])->where('created_at', '<=', Carbon::now()->subDays(14)->toDateTimeString())->count();
        return view('dashboard', $data);
    }
}
