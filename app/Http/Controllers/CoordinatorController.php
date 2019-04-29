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


class CoordinatorController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');    
        $this->middleware('coordinator');

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        $data['role'] = "Coordinator";

        $data['title'] = "Dashboard";
        $data['dep'] = "Dashboard";

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

    public function getContribution(Request $request)
    {
        $data['title'] = "Contribution";
        $data['route'] = "post-contribution";
        $data['eroute'] = "edit-contribution";
        $data['sroute'] = "coor-single-contribution";
        $data['aroute'] = "coor-approve-contribution";
        $data['asroute'] = "coor-approve-contributions";

        $data['rcon'] = "coor-contributions";
        $data['racon'] = "coor-approved-contributions";
        $data['rccon'] = "coor-commented-contributions";
        $data['rpcon'] = "coor-pending-contributions";
      
        
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
        $data['cons'] = Contribution::whereAcademicYear($cay->id)->where('dep_id', Auth::user()->department_id)->orderBy('id', 'desc')->paginate(100);
        $data['allcons'] = Contribution::whereAcademicYear($cay->id)->where('dep_id', Auth::user()->department_id)->count();
        $data['comcons'] = Contribution::whereAcademicYear($cay->id)->whereIn('status',[2,4])->where('dep_id', Auth::user()->department_id)->count();
        $data['apvcons'] = Contribution::whereAcademicYear($cay->id)->whereIn('status',[3,4])->where('dep_id', Auth::user()->department_id)->count();
        $data['pencons'] = Contribution::whereAcademicYear($cay->id)->whereNotIn('status',[3,4])->where('dep_id', Auth::user()->department_id)->count();





        return view('common.contribution', $data);
    }

    /*public function getContributionsByYear($year)
    {

        // $this->validate($request,[
        //     'academic_year' => 'required|exists:academic_years,year',
        // ]);

        $data['title'] = "Contribution";
        $data['route'] = "post-contribution";
        $data['eroute'] = "edit-contribution";
        $data['sroute'] = "coor-single-contribution";
        $data['aroute'] = "coor-approve-contribution";

        $ays = AcademicYear::orderBy('id', 'desc')->get();
        $cay = AcademicYear::where('year',$year)->first();
        $cay = AcademicYear::findOrFail($cay->id);
        Cookie::queue('uay', $cay->id, 300);
        $data['cay'] = $cay;
        $uid = Auth::user()->id;
        $data['cons'] = Contribution::whereAcademicYear($cay->id)->orderBy('id', 'asc')->paginate(100);
        $data['ays'] = AcademicYear::orderBy('id', 'desc')->get();

        $data['allcons'] = Contribution::whereAcademicYear($cay->id)->get()->count();
        $data['comcons'] = Contribution::whereAcademicYear($cay->id)->whereIn('status',[2,4])->count();
        $data['apvcons'] = Contribution::whereAcademicYear($cay->id)->whereIn('status',[3,4])->count();
        $data['pencons'] = Contribution::whereAcademicYear($cay->id)->whereNotIn('status',[3,4])->count();

        return view('common.contribution', $data);
    }*/

       


    public function getApprovedContributions(Request $request)
    {
        $data['title'] = "Contribution";
        $data['route'] = "post-contribution";
        $data['eroute'] = "edit-contribution";
        $data['sroute'] = "coor-single-contribution";
        $data['aroute'] = "coor-approve-contribution";
        $data['asroute'] = "coor-approve-contributions";

        $data['rcon'] = "coor-contributions";
        $data['racon'] = "coor-approved-contributions";
        $data['rccon'] = "coor-commented-contributions";
        $data['rpcon'] = "coor-pending-contributions";
      
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
        $data['cons'] = Contribution::whereAcademicYear($cay->id)->where('dep_id', Auth::user()->department_id)->whereIn('status',[3,4])->orderBy('id', 'asc')->paginate(100);
        $data['allcons'] = Contribution::whereAcademicYear($cay->id)->get()->where('dep_id', Auth::user()->department_id)->count();
        $data['comcons'] = Contribution::whereAcademicYear($cay->id)->where('dep_id', Auth::user()->department_id)->whereIn('status',[2,4])->count();
        $data['apvcons'] = Contribution::whereAcademicYear($cay->id)->where('dep_id', Auth::user()->department_id)->whereIn('status',[3,4])->count();
        $data['pencons'] = Contribution::whereAcademicYear($cay->id)->where('dep_id', Auth::user()->department_id)->whereNotIn('status',[3,4])->count();



        return view('common.contribution', $data);
    }


    public function getCommentedContribution(Request $request)
    {
        $data['title'] = "Contribution";
        $data['route'] = "post-contribution";
        $data['eroute'] = "edit-contribution";
        $data['sroute'] = "coor-single-contribution";
        $data['aroute'] = "coor-approve-contribution";
        $data['asroute'] = "coor-approve-contributions";

        $data['rcon'] = "coor-contributions";
        $data['racon'] = "coor-approved-contributions";
        $data['rccon'] = "coor-commented-contributions";
        $data['rpcon'] = "coor-pending-contributions";
      
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
        $data['cons'] = Contribution::whereAcademicYear($cay->id)->where('dep_id', Auth::user()->department_id)->whereIn('status',[2,4])->orderBy('id', 'asc')->paginate(100);
        $data['allcons'] = Contribution::whereAcademicYear($cay->id)->where('dep_id', Auth::user()->department_id)->get()->count();
        $data['comcons'] = Contribution::whereAcademicYear($cay->id)->where('dep_id', Auth::user()->department_id)->whereIn('status',[2,4])->count();
        $data['apvcons'] = Contribution::whereAcademicYear($cay->id)->where('dep_id', Auth::user()->department_id)->whereIn('status',[3,4])->count();
        $data['pencons'] = Contribution::whereAcademicYear($cay->id)->where('dep_id', Auth::user()->department_id)->whereNotIn('status',[3,4])->count();



        return view('common.contribution', $data);
    }

    public function getPendingContribution(Request $request)
    {
        $data['title'] = "Contribution";
        $data['route'] = "post-contribution";
        $data['eroute'] = "edit-contribution";
        $data['sroute'] = "coor-single-contribution";
        $data['aroute'] = "coor-approve-contribution";
        $data['asroute'] = "coor-approve-contributions";

        $data['rcon'] = "coor-contributions";
        $data['racon'] = "coor-approved-contributions";
        $data['rccon'] = "coor-commented-contributions";
        $data['rpcon'] = "coor-pending-contributions";
      
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
        $data['cons'] = Contribution::whereAcademicYear($cay->id)->where('dep_id', Auth::user()->department_id)->whereNotIn('status',[3,4])->orderBy('id', 'asc')->paginate(100);
        $data['allcons'] = Contribution::whereAcademicYear($cay->id)->where('dep_id', Auth::user()->department_id)->get()->count();
        $data['comcons'] = Contribution::whereAcademicYear($cay->id)->where('dep_id', Auth::user()->department_id)->whereIn('status',[2,4])->count();
        $data['apvcons'] = Contribution::whereAcademicYear($cay->id)->where('dep_id', Auth::user()->department_id)->whereIn('status',[3,4])->count();
        $data['pencons'] = Contribution::whereAcademicYear($cay->id)->where('dep_id', Auth::user()->department_id)->whereNotIn('status',[3,4])->count();



        return view('common.contribution', $data);
    }


    public function getApproveContribution($id){
        $con = Contribution::findOrFail($id);

        if (!($con->dep_id == Auth::user()->department_id)){
            session()->flash('message', "You don't have the required permission to approve this contribution!");
            Session::flash('type', 'danger');
            return redirect()->back();

        }


        $conimgs = ConImg::whereConId($id)->get();


         if ($con->status < 3 && ($con->file_name || $conimgs)) {
            if(File::exists('upload/'.$con->acyear->year.'/con_'.$con->id.'_user_'.$con->user_id.'/')) {

                if (!File::exists('upload/approved/'.$con->acyear->year.'/')) {
                    $nepath = 'upload/approved/'.$con->acyear->year.'/';
                    File::makeDirectory($nepath);
                }
            File::move('upload/'.$con->acyear->year.'/con_'.$con->id.'_user_'.$con->user_id.'/', 'upload/approved/'.$con->acyear->year.'/con_'.$con->id.'_user_'.$con->user_id.'/');
        }
    }


        if ($con->status == 1) {
            $con->status = 3;

            $con->save();
        }elseif ($con->status == 2) {
            $con->status = 4;
            $con->save();
        }else{
        session()->flash('message', 'Contribution is already approved or something went wrong!');
        Session::flash('type', 'warning');
        return redirect()->back();
        }


        session()->flash('message', 'Contribution status Successfully updated!');
        Session::flash('type', 'success');
        return redirect()->back();
        
    }


    public function postApproveContributions(Request $request){

        $this->validate($request,[
            'id' => 'required',
            'id.*' => 'numeric|exists:contributions,id',
        ]);

        $ids = $request->id;

    foreach ($ids as $id) {       

        $con = Contribution::findOrFail($id);
        $conimgs = ConImg::whereConId($id)->get();

        if ($con->dep_id != Auth::user()->department_id){
            session()->flash('message', "You don't have the required permission to approve this contribution!");
            Session::flash('type', 'danger');
            return redirect()->back();

        }


        if ($con->status < 3 && ($con->file_name || $conimgs)) {
            if(File::exists('upload/'.$con->acyear->year.'/con_'.$con->id.'_user_'.$con->user_id.'/')) {
    // path does not exist
                 if (!File::exists('upload/approved/'.$con->acyear->year.'/')) {
                    $nepath = 'upload/approved/'.$con->acyear->year.'/';
                    File::makeDirectory($nepath);
                }
            File::move('upload/'.$con->acyear->year.'/con_'.$con->id.'_user_'.$con->user_id.'/', 'upload/approved/'.$con->acyear->year.'/con_'.$con->id.'_user_'.$con->user_id.'/');
            }
        }

        if ($con->status == 1) {
            $con->status = 3;
            $con->save();
        }elseif ($con->status == 2) {
            $con->status = 4;
            $con->save();
        }elseif ($con->status == 3 || $con->status == 4 ) {
            # Do nothing...
        }else{
        session()->flash('message', ' One or more Contribution is already approved or something went wrong with it!');
        Session::flash('type', 'warning');
        return redirect()->back();
        }

    }
        session()->flash('message', 'Contribution(s) status Successfully updated!');
        Session::flash('type', 'success');
        return redirect()->back();
  
    }

    public function getSingleContribution($id)
    {
        $data['title'] = "Contribution";
        $data['aroute'] = "coor-approve-contribution";

        // $data['uroute'] = "update-contribution";
        $data['route'] = "coor-add-comment";

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

        if ($con->dep_id != Auth::user()->department_id){
            session()->flash('message', "You do not have permission to view this page!");
            Session::flash('type', 'danger');
            return redirect()->back();

        }

        // $uid = Auth::user()->id; 

        // if ($uid != $con->user_id) {
        //     session()->flash('message', 'You do not have permission to view this page!');
        //     Session::flash('type', 'error');
        //     return redirect()->route('stdcontributions');
        // }





        return view('common.contribution-single', $data);
    }

    public function addComment($id,Request $request)
    {


       $con = Contribution::findOrFail($id);

       if ($con->dep_id != Auth::user()->department_id){
            session()->flash('message', "You do not have permission to view this page!");
            Session::flash('type', 'danger');
            return redirect()->back();

        }


       $uid = Auth::user()->id; 

       $this->validate($request,[
            'comment' => 'required|string',
        ]);


        $com['comment'] = $request->comment;
        $com['user_id'] = $uid;
        $com['con_id'] = $id;

        Comment::create($com);


        if ($con->status == 1) {
            $con->status = 2;

            $con->save();
        }elseif ($con->status == 3) {
            $con->status = 4;
            $con->save();
        }elseif ($con->status == 3 || $con->status == 4 ) {
            # Do nothing...
        }else{
        session()->flash('message', 'Something went wrong with your update please contact the server admin!');
        Session::flash('type', 'warning');
        return redirect()->back();
        }

        session()->flash('message', 'Comment Successfully Added!');
        Session::flash('type', 'success');




        return redirect()->back();
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
