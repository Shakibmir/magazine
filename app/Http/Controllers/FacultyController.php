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

class FacultyController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');    
        $this->middleware('faculty');

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        $data['role'] = "Faculty";

        $data['title'] = "Dashboard";
        $data['dep'] = 1;

        $data['rcon'] = "coor-contributions";
        $data['racon'] = "coor-approved-contributions";
        $data['rccon'] = "coor-commented-contributions";
        $data['rpcon'] = "coor-pending-contributions";

        
        $cay = AcademicYear::whereYear('opening_date', '=', date('Y'))->first();
        $data['cay'] = $cay;



        $urep = Contribution::whereAcademicYear($cay->id)->with('user')->get()->where('user.department_id', Auth::user()->department_id)->groupBy('user_id')->count(); // total number of contributor all dep
        $data['ureps'] = $urep;

        $data['totalcons'] = Contribution::where('dep_id', Auth::user()->department_id)->count();
        $data['allcons'] = Contribution::where('dep_id', Auth::user()->department_id)->whereAcademicYear($cay->id)->count();
        $data['userc'] = User::whereRole(2)->where('department_id', Auth::user()->department_id)->get()->count(); //User count

        $data['comcons'] = Contribution::where('dep_id', Auth::user()->department_id)->whereAcademicYear($cay->id)->whereIn('status',[2,4])->count();
        $data['apvcons'] = Contribution::where('dep_id', Auth::user()->department_id)->whereAcademicYear($cay->id)->whereIn('status',[3,4])->count();
        $data['pencons'] = Contribution::where('dep_id', Auth::user()->department_id)->whereAcademicYear($cay->id)->whereNotIn('status',[3,4])->count();
        $data['nocoms'] = Contribution::where('dep_id', Auth::user()->department_id)->whereAcademicYear($cay->id)->whereNotIn('status',[2,4])->count();
        $data['nocomsl'] = Contribution::where('dep_id', Auth::user()->department_id)->whereAcademicYear($cay->id)->whereNotIn('status',[2,4])->where('created_at', '<=', Carbon::now()->subDays(14)->toDateTimeString())->count();
        return view('dashboard', $data);
    }



    public function getContributionReport(Request $request)
    {
        $data['title'] = "Number of Contributions";

        $uay = Cookie::get('uay');

        if ($request->academic_year) {

        $this->validate($request,[
            'academic_year' => 'required|exists:academic_years,id',
        ]);

        Cookie::queue('uay', $request->academic_year, 300);

         $uay = $request->academic_year;
        }

        
        $data['rptype'] = 1;



       

        if ($uay) {
            $cay = AcademicYear::findOrFail($uay);
        }else{
            $cay = AcademicYear::whereYear('opening_date', '=', date('Y'))->first();
        }
        $data['cay'] = $cay;
        $data['reps'] = Contribution::where('dep_id', Auth::user()->department_id)->whereAcademicYear($cay->id)->with('user')->get()->groupBy('user.department_id');
        // $reps = Contribution::with('user')->get()->groupBy('user.department_id');
        $data['ays'] = AcademicYear::orderBy('id', 'desc')->get();
        $data['deps'] = Department::all();


        return view('common.reports', $data);
    }

    public function getContributionPercentage(Request $request)
    {
        $data['title'] = "Percentage of Contributions";
        $data['rptype'] = 3;
        $data['dep'] = 1;



        $uay = Cookie::get('uay');

        if ($request->academic_year) {

        $this->validate($request,[
            'academic_year' => 'required|exists:academic_years,id',
        ]);

        Cookie::queue('uay', $request->academic_year, 300);

         $uay = $request->academic_year;
        }

        if ($uay) {
            $cay = AcademicYear::findOrFail($uay);
        }else{
            $cay = AcademicYear::whereYear('opening_date', '=', date('Y'))->first();
        }
        $data['cay'] = $cay;
      
        $data['reps'] = Contribution::whereAcademicYear($cay->id)->with('user')->get()->groupBy('user.department_id');
        // $reps = Contribution::with('user')->get()->groupBy('user.department_id');
        $data['ays'] = AcademicYear::orderBy('id', 'desc')->get();
        $data['deps'] = Department::all();


        return view('common.reports', $data);
    }

    public function getContributorNumberpage()
    {
        $data['title'] = "Number of Contributors";
        $data['route'] = "post-contributor-number";
        $data['rptype'] = 2;

        $uay = Cookie::get('uay');

        if ($uay) {
            $cay = AcademicYear::findOrFail($uay);
        }else{
            $cay = AcademicYear::whereYear('opening_date', '=', date('Y'))->first();
        }
        $data['cay'] = $cay;

        $data['pay'] = AcademicYear::findOrFail($cay->id);
        $data['pdp'] = Department::findOrFail(Auth::user()->department_id);
      
        // $data['reps'] = Contribution::with('user')->get()->groupBy('user.department_id');
        // $urep = Contribution::with('user')->get()->where('user.department_id', '1')->groupBy('user_id');
        // $urep = Contribution::with('user')->get()->where('user.department_id', '1')->groupBy('user_id')->count();
        // $reps = Contribution::with('user')->get()->groupBy('user.department_id');
        // $data['ureps'] = $urep;

        $data['reps'] = Contribution::where('dep_id', Auth::user()->department_id)->with('user')->get()->groupBy('user.department_id');
        // $urep = Contribution::with('user')->get()->where('user.department_id', '1')->groupBy('user_id');
        $urep = Contribution::whereAcademicYear($cay->id)->with('user')->get()->where('user.department_id', Auth::user()->department_id)->groupBy('user_id')->count();
        // $reps = Contribution::with('user')->get()->groupBy('user.department_id');
        $data['ureps'] = $urep;
        $data['ays'] = AcademicYear::orderBy('id', 'desc')->get();
        $data['deps'] = Department::all();

        return view('common.reports', $data);
    }


    public function getContributorNumber(Request $request)
    {
        $data['title'] = "Number of Contributors";
        $data['route'] = "post-contributor-number";


        $this->validate($request,[
            'academic_year' => 'required|exists:academic_years,id',
            'department' => 'required|exists:departments,id',
        ]);

        // $data['route'] = "post-contribution";
        $data['pay'] = AcademicYear::findOrFail($request->academic_year);
        $data['pdp'] = Department::findOrFail($request->department);

        $data['rptype'] = 2;

        $uay = Cookie::get('uay');

        if ($uay) {
            $cay = AcademicYear::findOrFail($uay);
        }else{
            $cay = AcademicYear::whereYear('opening_date', '=', date('Y'))->first();
        }
        $data['cay'] = $cay;
      
        $data['reps'] = Contribution::with('user')->get()->groupBy('user.department_id');
        // $urep = Contribution::with('user')->get()->where('user.department_id', '1')->groupBy('user_id');
        $urep = Contribution::whereAcademicYear($request->academic_year)->with('user')->get()->where('user.department_id', $request->department)->groupBy('user_id')->count();
        // $reps = Contribution::with('user')->get()->groupBy('user.department_id');
        $data['ureps'] = $urep;
        $data['ays'] = AcademicYear::orderBy('id', 'desc')->get();
        $data['deps'] = Department::all();



        // dd($urep);

        return view('common.reports', $data);
    }



    public function getContributorWithoutComment(Request $request)
    {
        $data['title'] = "Contribution Without Comment";

        $data['rptype'] = 4;

        $uay = Cookie::get('uay');

        if ($request->academic_year) {

        $this->validate($request,[
            'academic_year' => 'required|exists:academic_years,id',
        ]);

        Cookie::queue('uay', $request->academic_year, 300);

         $uay = $request->academic_year;
        }

        if ($uay) {
            $cay = AcademicYear::findOrFail($uay);
        }else{
            $cay = AcademicYear::whereYear('opening_date', '=', date('Y'))->first();
        }
        $data['cay'] = $cay;
      
        $data['ays'] = AcademicYear::orderBy('id', 'desc')->get();
        $data['deps'] = Department::all();
        // $data['cons'] = Contribution::whereStatus(1)->orderBy('id', 'asc')->paginate(100);
        $data['allcons'] = Contribution::where('dep_id', Auth::user()->department_id)->whereAcademicYear($cay->id)->get()->count();
        $data['comcons'] = Contribution::where('dep_id', Auth::user()->department_id)->whereAcademicYear($cay->id)->whereNotIn('status',[2,4])->count();
        $data['nocoms'] = Contribution::where('dep_id', Auth::user()->department_id)->whereAcademicYear($cay->id)->whereNotIn('status',[2,4])->where('created_at', '<=', Carbon::now()->subDays(14)->toDateTimeString())->count();

        return view('common.reports', $data);
    }

    public function getAcademicYear()
    {
        $data['title'] = "Academic Year";
        // $data['eroute'] = "edit-academic-year";
        $data['ays'] = AcademicYear::orderBy('id', 'asc')->paginate(10);

        return view('admin.academic-year', $data);
    }
}
