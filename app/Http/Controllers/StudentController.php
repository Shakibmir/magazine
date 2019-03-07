<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\AcademicYear;
use App\Department;
use App\Contribution;
use App\ConImg;

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

        $uid = Auth::user()->id;
      
        $data['cons'] = Contribution::whereUserId($uid)->orderBy('id', 'asc')->paginate(10);
        $data['ays'] = AcademicYear::orderBy('id', 'desc')->get();



        return view('common.contribution', $data);
    }



    public function postContribution(Request $request)
    {
        $data['title'] = "Contribution";
        $data['eroute'] = "edit-contribution";

        $this->validate($request,[
            'title' => 'required|string|max:255',
            'academic_year' => 'required|exists:academic_years,year',
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


        $data['ays'] = AcademicYear::orderBy('id', 'desc')->get();



        return view('admin.edit-academic-year', $data);
    }


    public function updateContribution($id, Request $request)
    {
        $data['title'] = "Contribution";
        // $data['eroute'] = "edit-contribution";

        $this->validate($request,[
            'title' => 'required|string|max:255',
            'academic_year' => 'required|exists:academic_years,year',
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
}
