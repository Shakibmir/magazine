<?php

namespace App\Http\Controllers;

use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use App\AcademicYear;
use App\Comment;
use App\Contribution;
use App\ConImg;
use App\Department;
use App\User;
use Zipper;

use Carbon\Carbon;



class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');    
        $this->middleware('admin');

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
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


    public function getContribution(Request $request)
    {
        $data['title'] = "Contribution";
        $data['route'] = "post-contribution";
        $data['eroute'] = "edit-contribution";
        $data['sroute'] = "single-contribution";
        $data['aroute'] = "approve-contribution";
      
        
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

    /*public function getContributionsByYear($year)
    {

        // $this->validate($request,[
        //     'academic_year' => 'required|exists:academic_years,year',
        // ]);

        $data['title'] = "Contribution";
        $data['route'] = "post-contribution";
        $data['eroute'] = "edit-contribution";
        $data['sroute'] = "single-contribution";
        $data['aroute'] = "approve-contribution";

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
        $data['sroute'] = "single-contribution";
        $data['aroute'] = "approve-contribution";
      
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
        $data['sroute'] = "single-contribution";
        $data['aroute'] = "approve-contribution";
      
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
        $data['sroute'] = "single-contribution";
        $data['aroute'] = "approve-contribution";
      
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


    public function getApproveContribution($id){
        $con = Contribution::findOrFail($id);


        $conimgs = ConImg::whereConId($id)->get();


        if (!$con->status > 2 && ($con->file_name || $conimgs)) {

            File::move('upload/'.$con->acyear->year.'/con_'.$con->id.'_user_'.$con->user_id.'/', 'upload/approved/'.$con->acyear->year.'/con_'.$con->id.'_user_'.$con->user_id.'/');
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


        if (!$con->status > 2 && ($con->file_name || $conimgs)) {
            File::move('upload/'.$con->acyear->year.'/con_'.$con->id.'_user_'.$con->user_id.'/', 'upload/approved/'.$con->acyear->year.'/con_'.$con->id.'_user_'.$con->user_id.'/');
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



    // public function postContribution(Request $request)
    // {
    //     $data['title'] = "Contribution";
    //     $data['eroute'] = "edit-contribution";

    //     $this->validate($request,[
    //         'title' => 'required|string|max:255',
    //         'academic_year' => 'required|exists:academic_years,id',
    //         'doc' => 'required|file|mimes:doc,docx,pdf|max:5120',
    //         'file' => 'required',
    //         'file.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    //     ]);

    //     // dd($diff."<br>".$cdiff."<br>".$fdiff);

    //     $con['title'] = $request->title;
    //     $con['academic_year'] = $request->academic_year;
    //     $con['user_id'] = Auth::user()->id;

    //     $con['file_name'] = $request->doc->getClientOriginalName();
    //     $request->doc->store('public/upload');

    //     $files = $request->file('file');



    //     $con = Contribution::create($con);

    //     $lcon = $con->id;

    //     foreach ($files as $file) {

    //         $img['con_id'] = $lcon;
    //         $img['name'] = $file->getClientOriginalName();
    //         $file->store('public/upload');



    //         ConImg::create($img);
           
    //     }

        

    //     session()->flash('message', 'Contribution Successfully Added!');
    //     Session::flash('type', 'success');
    //     return redirect()->back();
    // }

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


    public function getDepartment()
    {
        $data['title'] = "Faculty";
        $data['route'] = "post-department";
        $data['eroute'] = "edit-department";
      
        $data['deps'] = Department::orderBy('id', 'asc')->paginate(100);



        return view('admin.department', $data);
    }


    public function postDepartment(Request $request)
    {
        $data['title'] = "Department";
        $data['eroute'] = "edit-department";

        $this->validate($request,[
            'name' => 'required|string|max:255',
        ]);

        // dd($diff."<br>".$cdiff."<br>".$fdiff);

        $dep['name'] = $request->name;

        Department::create($dep);

        session()->flash('message', 'Department Successfully Added!');
        Session::flash('type', 'success');
        return redirect()->back();
    }



    public function editDepartment($id)
    {
        $data['title'] = "Department";

        $data['uroute'] = "update-department";
        $data['route'] = "departments";

        $data['isDep'] = 1;
        // $data['eroute'] = "edit-academic-year";
        $data['ay'] = Department::findOrFail($id);



        return view('admin.edit-academic-year', $data);
    }


    public function updateDepartment($id,Request $request)
    {
        $data['title'] = "Department";
        $data['eroute'] = "edit-department";

        $this->validate($request,[
            'name' => 'required|string|max:255',
        ]);

        // dd($diff."<br>".$cdiff."<br>".$fdiff);

        $dep = Department::findOrFail($id);



        $dep['name'] = $request->name;

        $dep->save();

        session()->flash('message', 'Department Successfully Updated!');
        Session::flash('type', 'success');
        return redirect()->back();
    }

    public function getAcademicYear()
    {
        $data['title'] = "Academic Year";
        $data['eroute'] = "edit-academic-year";
        $data['ays'] = AcademicYear::orderBy('id', 'asc')->paginate(100);



        return view('admin.academic-year', $data);
    }

    public function postAcademicYear( Request $request)
    {
        $data['title'] = "Academic Year";
        $data['eroute'] = "edit-academic-year";

        $this->validate($request,[
            'year' => 'required|numeric|max:2999|min:2019',
            'opening_date' => 'required|date',
            'closing_date' => 'required|date',
            'final_date' => 'required|date',
            'tc' => 'required',
        ]);


        $ct = Carbon::today();

        $diff = $ct->diffInDays($request->opening_date, false);

        if ($diff < 0) {
        session()->flash('message', 'Start time can not be older than today!');
        Session::flash('type', 'error');
        return redirect()->back();
        }

        $od=Carbon::parse($request->opening_date);
        $cdiff = $od->diffInDays($request->closing_date, false);

        if ($cdiff < 1) {
        session()->flash('message', 'The closing date can not be older than opening date!');
        Session::flash('type', 'error');
        return redirect()->back();
        }

        $cd=Carbon::parse($request->closing_date);
        $fdiff = $cd->diffInDays($request->final_date, false);

        if ($fdiff < 1) {
        session()->flash('message', 'The final date can not be older than closing date!');
        Session::flash('type', 'error');
        return redirect()->back();
        }

        // dd($diff."<br>".$cdiff."<br>".$fdiff);

        $add['year'] = $request->year;
        $add['opening_date'] = $request->opening_date;
        $add['closing_date'] = $request->closing_date;
        $add['final_date'] = $request->final_date;

        AcademicYear::create($add);





        session()->flash('message', 'Academic Year Successfully Added!');
        Session::flash('type', 'success');
        return redirect()->back();
    }


    public function editAcademicYear($id)
    {
        $data['title'] = "Academic Year";

        $data['uroute'] = "update-academic-year";
        $data['route'] = "academic-years";

        $data['isDep'] = 0;
        // $data['eroute'] = "edit-academic-year";
        $data['ay'] = AcademicYear::findOrFail($id);



        return view('admin.edit-academic-year', $data);
    }

    public function updateAcademicYear($id, Request $request)
    {
        $data['title'] = "Academic Year";
        // $data['eroute'] = "edit-academic-year";

        $this->validate($request,[
            'year' => 'required|numeric|max:2999|min:2019',
            'opening_date' => 'required|date',
            'closing_date' => 'required|date',
            'final_date' => 'required|date',
        ]);

        $ay = AcademicYear::findOrFail($id);



        $ct = Carbon::today();

        if ($ay->opening_date !=$request->opening_date) {
            $diff = $ct->diffInDays($request->opening_date, false);

            if ($diff < 0) {
            session()->flash('message', 'Starting date can not be changed to older dates!');
            Session::flash('type', 'error');
            return redirect()->back();
            }

            $od=Carbon::parse($request->opening_date);
            $cdiff = $od->diffInDays($request->closing_date, false);

            if ($cdiff < 1 && $ay->closing_date ==$request->closing_date) {
            session()->flash('message', 'Opening date can not be later than the closing date!');
            Session::flash('type', 'error');
            return redirect()->back();
            }
        }

        
        if ($ay->closing_date !=$request->closing_date) {
            $od=Carbon::parse($request->opening_date);
            $cdiff = $od->diffInDays($request->closing_date, false);

            if ($cdiff < 1) {
            session()->flash('message', 'Closing date can not be changed to older dates!');
            Session::flash('type', 'error');
            return redirect()->back();
            }

            $cd=Carbon::parse($request->closing_date);
            $fdiff = $cd->diffInDays($ay->final_date, false);

            if ($fdiff < 1 && $ay->final_date == $request->final_date) {
            session()->flash('message', 'The Closing date can not be changed to later than the final date!');
            Session::flash('type', 'error');
            return redirect()->back();
            }
        }

        if ($ay->final_date !=$request->final_date) {
            $cd=Carbon::parse($request->closing_date);
            $fdiff = $cd->diffInDays($request->final_date, false);

            if ($fdiff < 1) {
            session()->flash('message', 'The final date can not be changed to older dates!');
            Session::flash('type', 'error');
            return redirect()->back();
            }
        }

        $ay['year'] = $request->year;
        $ay['opening_date'] = $request->opening_date;
        $ay['closing_date'] = $request->closing_date;
        $ay['final_date'] = $request->final_date;

        $ay->save();


        session()->flash('message', 'Academic Year Successfully Updated!');
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

    public function getUsers()
    {
        $data['title'] = "User";
        $data['eroute'] = "edit-user";
        $users = User::paginate(100);
        $data['users'] =$users;
        return view('admin.users', $data);
    }

    public function getAddUser()
    {
        $data['title'] = "Add User";
        $data['route'] = "post-add-user";
        $data['deps'] = Department::all();
        return view('admin.add-edit-user', $data);
    }

    public function postAddUser(Request $request)
    {
        
        $this->validate($request,[
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'role' => 'required|in:1,2,3,4,5', //validate role input
            'department_id' => 'required|exists:departments,id', //validate role input
        ]);

        if ($request->role == 3) {
            
            $coor = User::whereDepartmentId($request->department_id)->whereRole(3)->first();
            if ($coor) {
                session()->flash('message', 'A coordinator already exists for this department!');
                Session::flash('type', 'error');
                return redirect()->back()->withInput(Input::all());
             } 
        }

        if ($request->role == 4) {
            
            $man = User::whereRole(4)->first();
            if ($man) {
                session()->flash('message', 'A Marketing manager has already been assigned!');
                Session::flash('type', 'error');
                return redirect()->back()->withInput(Input::all());
             } 
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'department_id' => $request->department_id,
        ]);

        session()->flash('message', 'User successfully Added!');
        Session::flash('type', 'success');
        return redirect()->route('users');
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
