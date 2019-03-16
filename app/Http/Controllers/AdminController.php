<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\AcademicYear;
use App\Department;
use App\Comment;
use App\Contribution;
use App\ConImg;

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
        return view('dashboard');
    }


    public function getContribution()
    {
        $data['title'] = "Contribution";
        $data['route'] = "post-contribution";
        $data['eroute'] = "edit-contribution";
        $data['sroute'] = "single-contribution";
        $data['aroute'] = "approve-contribution";
      
        $data['ays'] = AcademicYear::orderBy('id', 'desc')->get();
        $cay = AcademicYear::whereYear('opening_date', '=', date('Y'))->first();
        $data['cay'] = $cay;
        $data['cons'] = Contribution::whereAcademicYear($cay->id)->orderBy('id', 'asc')->paginate(10);
        $data['allcons'] = Contribution::whereAcademicYear($cay->id)->get()->count();
        $data['comcons'] = Contribution::whereAcademicYear($cay->id)->whereIn('status',[2,4])->count();
        $data['apvcons'] = Contribution::whereAcademicYear($cay->id)->whereIn('status',[3,4])->count();
        $data['pencons'] = Contribution::whereAcademicYear($cay->id)->whereStatus(1)->count();





        return view('common.contribution', $data);
    }

    public function getContributionsByYear($year)
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
        $data['cay'] = $cay;
        $uid = Auth::user()->id;
        $data['cons'] = Contribution::whereAcademicYear($cay->id)->orderBy('id', 'asc')->paginate(10);
        $data['ays'] = AcademicYear::orderBy('id', 'desc')->get();

        $data['allcons'] = Contribution::whereAcademicYear($cay->id)->get()->count();
        $data['comcons'] = Contribution::whereAcademicYear($cay->id)->whereIn('status',[2,4])->count();
        $data['apvcons'] = Contribution::whereAcademicYear($cay->id)->whereIn('status',[3,4])->count();
        $data['pencons'] = Contribution::whereAcademicYear($cay->id)->whereStatus(1)->count();

        return view('common.contribution', $data);
    }

       


    public function getApprovedContributions()
    {
        $data['title'] = "Contribution";
        $data['route'] = "post-contribution";
        $data['eroute'] = "edit-contribution";
        $data['sroute'] = "single-contribution";
        $data['aroute'] = "approve-contribution";
      
        $data['ays'] = AcademicYear::orderBy('id', 'desc')->get();
        $cay = AcademicYear::whereYear('opening_date', '=', date('Y'))->first();
        $data['cay'] = $cay;
        $data['cons'] = Contribution::whereAcademicYear($cay->id)->whereIn('status',[3,4])->orderBy('id', 'asc')->paginate(10);
        $data['allcons'] = Contribution::whereAcademicYear($cay->id)->get()->count();
        $data['comcons'] = Contribution::whereAcademicYear($cay->id)->whereIn('status',[2,4])->count();
        $data['apvcons'] = Contribution::whereAcademicYear($cay->id)->whereIn('status',[3,4])->count();
        $data['pencons'] = Contribution::whereAcademicYear($cay->id)->whereStatus(1)->count();



        return view('common.contribution', $data);
    }


    public function getCommentedContribution()
    {
        $data['title'] = "Contribution";
        $data['route'] = "post-contribution";
        $data['eroute'] = "edit-contribution";
        $data['sroute'] = "single-contribution";
        $data['aroute'] = "approve-contribution";
      
        $data['ays'] = AcademicYear::orderBy('id', 'desc')->get();
        $cay = AcademicYear::whereYear('opening_date', '=', date('Y'))->first();
        $data['cay'] = $cay;
        $data['cons'] = Contribution::whereAcademicYear($cay->id)->whereIn('status',[2,4])->orderBy('id', 'asc')->paginate(10);
        $data['allcons'] = Contribution::whereAcademicYear($cay->id)->get()->count();
        $data['comcons'] = Contribution::whereAcademicYear($cay->id)->whereIn('status',[2,4])->count();
        $data['apvcons'] = Contribution::whereAcademicYear($cay->id)->whereIn('status',[3,4])->count();
        $data['pencons'] = Contribution::whereAcademicYear($cay->id)->whereStatus(1)->count();



        return view('common.contribution', $data);
    }

    public function getPendingContribution()
    {
        $data['title'] = "Contribution";
        $data['route'] = "post-contribution";
        $data['eroute'] = "edit-contribution";
        $data['sroute'] = "single-contribution";
        $data['aroute'] = "approve-contribution";
      
        $data['ays'] = AcademicYear::orderBy('id', 'desc')->get();
        $cay = AcademicYear::whereYear('opening_date', '=', date('Y'))->first();
        $data['cay'] = $cay;
        $data['cons'] = Contribution::whereAcademicYear($cay->id)->whereStatus(1)->orderBy('id', 'asc')->paginate(10);
        $data['allcons'] = Contribution::whereAcademicYear($cay->id)->get()->count();
        $data['comcons'] = Contribution::whereAcademicYear($cay->id)->whereIn('status',[2,4])->count();
        $data['apvcons'] = Contribution::whereAcademicYear($cay->id)->whereIn('status',[3,4])->count();
        $data['pencons'] = Contribution::whereAcademicYear($cay->id)->whereStatus(1)->count();



        return view('common.contribution', $data);
    }


    public function getApproveContribution($id){
        $con = Contribution::findOrFail($id);

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
            # code...
       

        $con = Contribution::findOrFail($id);

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



    public function postContribution(Request $request)
    {
        $data['title'] = "Contribution";
        $data['eroute'] = "edit-contribution";

        $this->validate($request,[
            'title' => 'required|string|max:255',
            'academic_year' => 'required|exists:academic_years,id',
            'doc' => 'required|file|mimes:doc,docx,pdf|max:5120',
            'file' => 'required',
            'file.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // dd($diff."<br>".$cdiff."<br>".$fdiff);

        $con['title'] = $request->title;
        $con['academic_year'] = $request->academic_year;
        $con['user_id'] = Auth::user()->id;

        $con['file_name'] = $request->doc->getClientOriginalName();
        $request->doc->store('public/upload');

        $files = $request->file('file');



        $con = Contribution::create($con);

        $lcon = $con->id;

        foreach ($files as $file) {

            $img['con_id'] = $lcon;
            $img['name'] = $file->getClientOriginalName();
            $file->store('public/upload');



            ConImg::create($img);
           
        }

        

        session()->flash('message', 'Contribution Successfully Added!');
        Session::flash('type', 'success');
        return redirect()->back();
    }

    public function getSingleContribution($id)
    {
        $data['title'] = "Contribution";

        // $data['uroute'] = "update-contribution";
        $data['route'] = "add-comment";

        // $data['isDep'] = 2;
        // $data['eroute'] = "edit-academic-year";
        $data['con'] = Contribution::findOrFail($id);

        $data['comments'] = Comment::whereConId($id)->orderBy('id', 'asc')->paginate(10);
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
        $data['title'] = "Department";
        $data['route'] = "post-department";
        $data['eroute'] = "edit-department";
      
        $data['deps'] = Department::orderBy('id', 'asc')->paginate(10);



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
        $data['ays'] = AcademicYear::orderBy('id', 'asc')->paginate(10);



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



    public function getContributionReport()
    {
        $data['title'] = "Number of Contributions";
        $data['rptype'] = 1;
      
        $data['reps'] = Contribution::with('user')->get()->groupBy('user.department_id');
        // $reps = Contribution::with('user')->get()->groupBy('user.department_id');
        $data['ays'] = AcademicYear::orderBy('id', 'desc')->get();
        $data['deps'] = Department::all();


        return view('common.reports', $data);
    }

    public function getContributionPercentage()
    {
        $data['title'] = "Percentage of Contributions";
        $data['rptype'] = 3;
      
        $data['reps'] = Contribution::with('user')->get()->groupBy('user.department_id');
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



    public function getContributorWithoutComment()
    {
        $data['title'] = "Contribution Without Comment";
        // $data['route'] = "post-contribution";
        // $data['eroute'] = "edit-contribution";
        // $data['sroute'] = "single-contribution";
        // $data['aroute'] = "approve-contribution";
        $data['rptype'] = 4;
      
        $data['ays'] = AcademicYear::orderBy('id', 'desc')->get();
        $data['deps'] = Department::all();
        // $data['cons'] = Contribution::whereStatus(1)->orderBy('id', 'asc')->paginate(10);
        $data['allcons'] = Contribution::all()->count();
        $data['comcons'] = Contribution::whereNotIn('status',[2,4])->count();
        $data['nocoms'] = Contribution::whereNotIn('status',[2,4])->where('created_at', '<=', Carbon::now()->subDays(14)->toDateTimeString())->count();




        return view('common.reports', $data);
    }
}
