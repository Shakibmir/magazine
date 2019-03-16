<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\AcademicYear;
use App\Comment;
use App\ConImg;
use App\Contribution;
use App\Department;

use Carbon\Carbon;

class StudentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');    
        $this->middleware('student');

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        $data['role'] = "Student";
        $data['title'] = "Dashboard";
        return view('dashboard', $data);
    }

    public function getContribution()
    {
        $data['title'] = "Contribution";
        $data['route'] = "post-stdcontribution";
        $data['eroute'] = "edit-stdcontribution";
        $data['sroute'] = "single-stdcontribution";
        $data['yroute'] = "stdcontributions-year";

        $ays = AcademicYear::orderBy('id', 'desc')->get();
        $cay = AcademicYear::whereYear('opening_date', '=', date('Y'))->first();
        $data['cay'] = $cay;
        $uid = Auth::user()->id;
        $data['cons'] = Contribution::whereAcademicYear($cay->id)->whereUserId($uid)->orderBy('id', 'asc')->paginate(10);
        $data['ays'] = AcademicYear::orderBy('id', 'desc')->get();

        return view('common.contribution', $data);
    }


    public function getContributionsByYear($year)
    {

        // $this->validate($request,[
        //     'academic_year' => 'required|exists:academic_years,year',
        // ]);

        $data['title'] = "Contribution";
        $data['route'] = "post-stdcontribution";
        $data['eroute'] = "edit-stdcontribution";
        $data['sroute'] = "single-stdcontribution";

        $ays = AcademicYear::orderBy('id', 'desc')->get();
        $cay = AcademicYear::where('year',$year)->first();
        $cay = AcademicYear::findOrFail($cay->id);
        $data['cay'] = $cay;
        $uid = Auth::user()->id;
        $data['cons'] = Contribution::whereAcademicYear($cay->id)->whereUserId($uid)->orderBy('id', 'asc')->paginate(10);
        $data['ays'] = AcademicYear::orderBy('id', 'desc')->get();

        return view('common.contribution', $data);
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

        $acyear = AcademicYear::findOrFail($request->academic_year);

        $ct = Carbon::today();
        // $cn = Carbon::now();

            $diff = $ct->diffInDays($acyear->opening_date, false);


            // dd($diff);

            if ($diff > 0) {
            session()->flash('message', 'You can not submit before the Starting date of the academic year!');
            Session::flash('type', 'error');
            return redirect()->back();
            }


            $cdiff = $ct->diffInDays($acyear->closing_date, false);


            // dd($cdiff."<br>".$ct."<br>".$cn);

            if ($cdiff < 0) {
            session()->flash('message', 'You can not submit after the Closing date of the academic year!');
            Session::flash('type', 'error');
            return redirect()->back();
            }


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


    public function editContribution($id)
    {
        $data['title'] = "Contribution";

        $data['uroute'] = "update-stdcontribution";
        $data['route'] = "stdcontributions";

        $data['isDep'] = 2;
        // $data['eroute'] = "edit-academic-year";

        

        $data['ay'] = Contribution::findOrFail($id);

       $con = Contribution::findOrFail($id);

       $uid = Auth::user()->id; 

        if ($uid != $con->user_id) {
            session()->flash('message', 'You do not have permission to view this page!');
            Session::flash('type', 'error');
            return redirect()->route('stdcontributions');
        }

        $acyear = AcademicYear::findOrFail($con->academic_year);

        $ct = Carbon::today();
        // $cn = Carbon::now();


            $odiff = $ct->diffInDays($acyear->opening_date, false);


            // dd($diff);

            if ($odiff > 0) {
            session()->flash('message', 'You can not submit or edit before the Starting date of the academic year!');
            Session::flash('type', 'error');
            return redirect()->back();
            }

            $diff = $ct->diffInDays($acyear->final_date, false);


            // dd($diff);

            if ($diff < 0) {
            session()->flash('message', 'You can not edit after Final Submission date of the academic year!');
            Session::flash('type', 'error');
            return redirect()->route('stdcontributions');
            }


        $con = Contribution::findOrFail($id);

        


        $data['ays'] = AcademicYear::orderBy('id', 'desc')->get();



        return view('admin.edit-academic-year', $data);
    }


    public function updateContribution($id, Request $request)
    {
        $data['title'] = "Contribution";
        // $data['eroute'] = "edit-contribution";

        $this->validate($request,[
            'title' => 'required|string|max:255',
            'academic_year' => 'required|exists:academic_years,id',
            'doc' => 'file|mimes:doc,docx,pdf|max:5120',
            // 'file' => 'required',
            'file.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // dd($diff."<br>".$cdiff."<br>".$fdiff);
        $con = Contribution::findOrFail($id);

        $uid = Auth::user()->id; 

        if ($uid != $con->user_id) {
           session()->flash('message', 'You do not have permission to view this page!');
            Session::flash('type', 'error');
            return redirect()->route('stdcontributions');
        }

        

        $acyear = AcademicYear::findOrFail($con->academic_year);

        $ct = Carbon::today();
        // $cn = Carbon::now();

            $odiff = $ct->diffInDays($acyear->opening_date, false);


            // dd($diff);

            if ($odiff > 0) {
            session()->flash('message', 'You can not submit or edit before the Starting date of the academic year!');
            Session::flash('type', 'error');
            return redirect()->back();
            }

            $diff = $ct->diffInDays($acyear->final_date, false);


            // dd($diff);

            if ($diff < 0) {
            session()->flash('message', 'You can not edit after the Final Submission date of the academic year!');
            Session::flash('type', 'error');
            return redirect()->route('stdcontributions');
            }


        $con['title'] = $request->title;
        $con['academic_year'] = $request->academic_year;
        // $con['user_id'] = Auth::user()->id;


        if ($request->doc) {
            $con['file_name'] = $request->doc->getClientOriginalName();

            $request->doc->store('public/upload');

            
        }

        
        

        

        $con->save();

        $lcon = $id;

        if ($request->file('file')) {

            $files = $request->file('file');

            foreach ($files as $file) {

                $img['con_id'] = $lcon;
                $img['name'] = $file->getClientOriginalName();
                $file->store('public/upload');

                ConImg::create($img);
               
            }

        }

        

        session()->flash('message', 'Contribution Successfully Updated!');
        Session::flash('type', 'success');
        return redirect()->back();
    }


    public function getSingleContribution($id)
    {
        $data['title'] = "Contribution";

        // $data['uroute'] = "update-contribution";
        $data['route'] = "add-stdcomment";
        $data['eroute'] = "edit-stdcontribution";

        $uid = Auth::user()->id;

        // $data['isDep'] = 2;
        // $data['eroute'] = "edit-academic-year";
        $data['con'] = Contribution::findOrFail($id);
        $con = Contribution::findOrFail($id);


        if ($uid != $con->user_id) {

        session()->flash('message', "You don't have the required permission to view the requested page!");
        Session::flash('type', 'danger');
        return redirect()->back();        
        }

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


       if ($uid != $con->user_id) {

        session()->flash('message', "You don't have the required permission to view the requested page!");
        Session::flash('type', 'danger');
        return redirect()->back();        
        }

       if ($con->status ==2 || $con->status ==4) {

       $this->validate($request,[
            'comment' => 'required|string',
        ]);


        $com['comment'] = $request->comment;
        $com['user_id'] = $uid;
        $com['con_id'] = $id;

        Comment::create($com);

        session()->flash('message', 'Comment Successfully Added!');
        Session::flash('type', 'success');




        return redirect()->back();


        }else{
        session()->flash('message', 'You can not interact with a faculty unless it has been commented!');
        Session::flash('type', 'warning');

        return redirect()->back();
       }
    }
}
