<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\AcademicYear;

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

        return view('dashboard');
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

        $add['year'] = $request->year;
        $add['opening_date'] = $request->opening_date;
        $add['closing_date'] = $request->closing_date;
        $add['final_date'] = $request->final_date;

        $ay->save();


        session()->flash('message', 'Academic Year Successfully Updated!');
        Session::flash('type', 'success');
        return redirect()->back();
    }
}
