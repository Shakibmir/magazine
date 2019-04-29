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
        $data['title'] = "Dashboard";
        $data['rcon'] = "man-contributions";
        $data['racon'] = "man-approved-contributions";
        $data['rccon'] = "man-commented-contributions";
        $data['rpcon'] = "man-pending-contributions";

        
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


    public function getContribution(Request $request)
    {
        $data['title'] = "Contribution";
        $data['route'] = "post-contribution";
        $data['eroute'] = "edit-contribution";
        $data['sroute'] = "man-single-contribution";
        $data['aroute'] = "approve-contribution";
        $data['downroute'] = "man-download-approved";
        $data['zroute'] = "man-zip";
        $data['asroute'] = "approve-contributions";

         $data['rcon'] = "man-contributions";
        $data['racon'] = "man-approved-contributions";
        $data['rccon'] = "man-commented-contributions";
        $data['rpcon'] = "man-pending-contributions";
      
        
        // $uay = Cookie::get('uay');
        $uay = Cookie::get('uay');

        if ($request->year) {

        $this->validate($request,[
            'year' => 'required|exists:academic_years,year',
        ]);

        $nray = AcademicYear::where('year',$request->year)->first();

        $uay = $nray->id;

        Cookie::queue('uay', $uay, 300);

         
        }


        $data['ays'] = AcademicYear::orderBy('id', 'desc')->get();

        if ($uay) {
            $cay = AcademicYear::findOrFail($uay);
        }else{
            $cay = AcademicYear::whereYear('opening_date', '=', date('Y'))->first();
        }
        $data['cay'] = $cay;
        $data['cons'] = Contribution::whereAcademicYear($cay->id)->orderBy('id', 'asc')->paginate(100);
        $data['allcons'] = Contribution::whereAcademicYear($cay->id)->get()->count();
        $data['comcons'] = Contribution::whereAcademicYear($cay->id)->whereIn('status',[2,4])->count();
        $data['apvcons'] = Contribution::whereAcademicYear($cay->id)->whereIn('status',[3,4])->count();
        $data['pencons'] = Contribution::whereAcademicYear($cay->id)->whereNotIn('status',[3,4])->count();





        return view('common.contribution', $data);
    }


    public function getApprovedContributions(Request $request)
    {
        $data['title'] = "Contribution";
        $data['route'] = "post-contribution";
        $data['eroute'] = "edit-contribution";
        $data['sroute'] = "man-single-contribution";
        $data['aroute'] = "approve-contribution";
        $data['asroute'] = "approve-contributions";

        $data['downroute'] = "man-download-approved";
        $data['zroute'] = "man-zip";

         $data['rcon'] = "man-contributions";
        $data['racon'] = "man-approved-contributions";
        $data['rccon'] = "man-commented-contributions";
        $data['rpcon'] = "man-pending-contributions";
      
        $data['ays'] = AcademicYear::orderBy('id', 'desc')->get();
        $uay = Cookie::get('uay');

        if ($request->year) {

        $this->validate($request,[
            'year' => 'required|exists:academic_years,year',
        ]);

        $nray = AcademicYear::where('year',$request->year)->first();

        $uay = $nray->id;

        Cookie::queue('uay', $uay, 300);

         
        }

        if ($uay) {
            $cay = AcademicYear::findOrFail($uay);
        }else{
            $cay = AcademicYear::whereYear('opening_date', '=', date('Y'))->first();
        }
        $data['cay'] = $cay;
        $data['cons'] = Contribution::whereAcademicYear($cay->id)->whereIn('status',[3,4])->orderBy('id', 'asc')->paginate(100);
        $data['allcons'] = Contribution::whereAcademicYear($cay->id)->get()->count();
        $data['comcons'] = Contribution::whereAcademicYear($cay->id)->whereIn('status',[2,4])->count();
        $data['apvcons'] = Contribution::whereAcademicYear($cay->id)->whereIn('status',[3,4])->count();
        $data['pencons'] = Contribution::whereAcademicYear($cay->id)->whereNotIn('status',[3,4])->count();



        return view('common.contribution', $data);
    }


    public function getCommentedContribution(Request $request)
    {
        $data['title'] = "Contribution";
        $data['route'] = "post-contribution";
        $data['eroute'] = "edit-contribution";
        $data['sroute'] = "man-single-contribution";
        $data['aroute'] = "approve-contribution";
        $data['asroute'] = "approve-contributions";

        $data['downroute'] = "man-download-approved";
        $data['zroute'] = "man-zip";

         $data['rcon'] = "man-contributions";
        $data['racon'] = "man-approved-contributions";
        $data['rccon'] = "man-commented-contributions";
        $data['rpcon'] = "man-pending-contributions";
      
        $data['ays'] = AcademicYear::orderBy('id', 'desc')->get();
        $uay = Cookie::get('uay');

        if ($request->year) {

        $this->validate($request,[
            'year' => 'required|exists:academic_years,year',
        ]);

        $nray = AcademicYear::where('year',$request->year)->first();

        $uay = $nray->id;

        Cookie::queue('uay', $uay, 300);    
        }

        if ($uay) {
            $cay = AcademicYear::findOrFail($uay);
        }else{
            $cay = AcademicYear::whereYear('opening_date', '=', date('Y'))->first();
        }
        $data['cay'] = $cay;
        $data['cons'] = Contribution::whereAcademicYear($cay->id)->whereIn('status',[2,4])->orderBy('id', 'asc')->paginate(100);
        $data['allcons'] = Contribution::whereAcademicYear($cay->id)->get()->count();
        $data['comcons'] = Contribution::whereAcademicYear($cay->id)->whereIn('status',[2,4])->count();
        $data['apvcons'] = Contribution::whereAcademicYear($cay->id)->whereIn('status',[3,4])->count();
        $data['pencons'] = Contribution::whereAcademicYear($cay->id)->whereNotIn('status',[3,4])->count();



        return view('common.contribution', $data);
    }

    public function getPendingContribution(Request $request)
    {
        $data['title'] = "Contribution";
        $data['route'] = "post-contribution";
        $data['eroute'] = "edit-contribution";
        $data['sroute'] = "man-single-contribution";
        $data['aroute'] = "approve-contribution";
        $data['asroute'] = "approve-contributions";

        $data['downroute'] = "man-download-approved";
        $data['zroute'] = "man-zip";

         $data['rcon'] = "man-contributions";
        $data['racon'] = "man-approved-contributions";
        $data['rccon'] = "man-commented-contributions";
        $data['rpcon'] = "man-pending-contributions";
      
        $data['ays'] = AcademicYear::orderBy('id', 'desc')->get();
        $uay = Cookie::get('uay');

        if ($request->year) {

        $this->validate($request,[
            'year' => 'required|exists:academic_years,year',
        ]);

        $nray = AcademicYear::where('year',$request->year)->first();

        $uay = $nray->id;

        Cookie::queue('uay', $uay, 300);    
        }

        if ($uay) {
            $cay = AcademicYear::findOrFail($uay);
        }else{
            $cay = AcademicYear::whereYear('opening_date', '=', date('Y'))->first();
        }
        $data['cay'] = $cay;
        $data['cons'] = Contribution::whereAcademicYear($cay->id)->whereNotIn('status',[3,4])->orderBy('id', 'asc')->paginate(100);
        $data['allcons'] = Contribution::whereAcademicYear($cay->id)->get()->count();
        $data['comcons'] = Contribution::whereAcademicYear($cay->id)->whereIn('status',[2,4])->count();
        $data['apvcons'] = Contribution::whereAcademicYear($cay->id)->whereIn('status',[3,4])->count();
        $data['pencons'] = Contribution::whereAcademicYear($cay->id)->whereNotIn('status',[3,4])->count();



        return view('common.contribution', $data);
    }




    public function getSingleContribution($id)
    {
        $data['title'] = "Contribution";
        $data['aroute'] = "approve-contribution";

        // $data['uroute'] = "update-contribution";
        $data['route'] = "add-comment";

        // $data['isDep'] = 2;
        // $data['eroute'] = "edit-academic-year";
        $data['con'] = Contribution::findOrFail($id);
        $conimgs = ConImg::whereConId($id)->get();
        $data['conimgs'] = $conimgs;

        $data['comments'] = Comment::whereConId($id)->orderBy('id', 'asc')->paginate(100);
        $data['comcount'] = Comment::whereConId($id)->count();

       // $con = Contribution::findOrFail($id);

        // $acyear = AcademicYear::findOrFail($con->academic_year);



        $con = Contribution::findOrFail($id);

        return view('common.contribution-single', $data);
    }



    public function getAcademicYear()
    {
        $data['title'] = "Academic Year";
        // $data['eroute'] = "edit-academic-year";
        $data['ays'] = AcademicYear::orderBy('id', 'asc')->paginate(10);

        return view('admin.academic-year', $data);
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
        $data['reps'] = Contribution::whereAcademicYear($cay->id)->with('user')->get()->groupBy('user.department_id');
        // $reps = Contribution::with('user')->get()->groupBy('user.department_id');
        $data['ays'] = AcademicYear::orderBy('id', 'desc')->get();
        $data['deps'] = Department::all();


        return view('common.reports', $data);
    }

    public function getContributionPercentage(Request $request)
    {
        $data['title'] = "Percentage of Contributions";
        $data['rptype'] = 3;

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
        $data['rptype'] = 0;

        $uay = Cookie::get('uay');

        if ($uay) {
            $cay = AcademicYear::findOrFail($uay);
        }else{
            $cay = AcademicYear::whereYear('opening_date', '=', date('Y'))->first();
        }
        $data['cay'] = $cay;
      
        // $data['reps'] = Contribution::with('user')->get()->groupBy('user.department_id');
        // $urep = Contribution::with('user')->get()->where('user.department_id', '1')->groupBy('user_id');
        // $urep = Contribution::with('user')->get()->where('user.department_id', '1')->groupBy('user_id')->count();
        // $reps = Contribution::with('user')->get()->groupBy('user.department_id');
        // $data['ureps'] = $urep;
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
        $data['allcons'] = Contribution::whereAcademicYear($cay->id)->get()->count();
        $data['comcons'] = Contribution::whereAcademicYear($cay->id)->whereNotIn('status',[2,4])->count();
        $data['nocoms'] = Contribution::whereAcademicYear($cay->id)->whereNotIn('status',[2,4])->where('created_at', '<=', Carbon::now()->subDays(14)->toDateTimeString())->count();

        return view('common.reports', $data);
    }


    public function zip()
    {
        // Storage::delete('public/test.zip');

        $uay = Cookie::get('uay');

        if ($uay) {
            $cay = AcademicYear::findOrFail($uay);
        }else{
            $cay = AcademicYear::whereYear('opening_date', '=', date('Y'))->first();
        }
        $data['cay'] = $cay;

        File::delete($cay->year.'_approved.zip');

        $files = glob('upload/approved/'.$cay->year.'/');
        Zipper::make($cay->year.'_approved.zip')->add($files)->close();


        return redirect($cay->year.'_approved.zip');
    }

    public function postDownloadApproved(Request $request){

        $this->validate($request,[
            'id' => 'required',
            'id.*' => 'numeric|exists:contributions,id',
        ]);

        $ids = $request->id;
        $uay = Cookie::get('uay');

        if ($uay) {
            $cay = AcademicYear::findOrFail($uay);
        }else{
            $cay = AcademicYear::whereYear('opening_date', '=', date('Y'))->first();
        }
        $data['cay'] = $cay;

        File::delete('selected_'.$cay->year.'_approved.zip');

    foreach ($ids as $id) {
            # code...
       

        $con = Contribution::findOrFail($id);

        if ($con->status == 1) {
            session()->flash('message', ' One or more Contribution is not approved or something went wrong with it!');
            Session::flash('type', 'warning');
            return redirect()->back();
            
        }elseif ($con->status == 2) {
            session()->flash('message', ' One or more Contribution is not approved or something went wrong with it!');
            Session::flash('type', 'warning');
            return redirect()->back();
           
        }elseif ($con->status == 3 || $con->status == 4 ) {
            $files = glob('upload/approved/'.$con->acyear->year.'/con_'.$con->id.'_user_'.$con->user_id.'/');
        Zipper::make('selected_'.$cay->year.'_approved.zip')->add($files)->close();
        }else{
        session()->flash('message', ' One or more Contribution is already approved or something went wrong with it!');
        Session::flash('type', 'warning');
        return redirect()->back();
        }

    }
        // session()->flash('message', 'Contribution(s) status Successfully updated!');
        // Session::flash('type', 'success');
        // return redirect()->back();

        return redirect('selected_'.$cay->year.'_approved.zip');  
    }
}

