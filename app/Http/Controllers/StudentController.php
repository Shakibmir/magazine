<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use App\AcademicYear;
use App\Comment;
use App\ConImg;
use App\Contribution;
use App\Department;
use App\Mail\NewContribution;
use App\User;

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

        $uid = Auth::user()->id;


        $cay = AcademicYear::whereYear('opening_date', '=', date('Y'))->first();
        $data['cay'] = $cay;

        $urep = Contribution::whereAcademicYear($cay->id)->with('user')->get()->groupBy('user_id')->count(); // total number of contributor all dep
        $data['ureps'] = $urep;

        $data['totalcons'] = Contribution::whereUserId($uid)->get()->count();
        $data['allcons'] = Contribution::whereUserId($uid)->whereAcademicYear($cay->id)->get()->count();
        $data['comcons'] = Contribution::whereUserId($uid)->whereAcademicYear($cay->id)->whereIn('status',[2,4])->count();
        $data['apvcons'] = Contribution::whereUserId($uid)->whereAcademicYear($cay->id)->whereIn('status',[3,4])->count();
        $data['pencons'] = Contribution::whereUserId($uid)->whereAcademicYear($cay->id)->whereNotIn('status',[3,4])->count();
        return view('dashboard', $data);
    }

    public function getContribution(Request $request)
    {
        $data['title'] = "Contribution";
        $data['route'] = "post-stdcontribution";
        $data['eroute'] = "edit-stdcontribution";
        $data['sroute'] = "single-stdcontribution";
        $data['yroute'] = "stdcontributions-year";

        $ays = AcademicYear::orderBy('id', 'desc')->get();
        $uay = Cookie::get('uay'); //$uay Your Academic Year ID

        if ($request->year) {

        $this->validate($request,[
            'year' => 'required|exists:academic_years,year',
        ]);

        $nray = AcademicYear::where('year',$request->year)->first(); //$nray New requested Academic Year

        $uay = $nray->id; //$uay Your Academic Year ID

        Cookie::queue('uay', $uay, 300);    
        }

        if ($uay) {
            $cay = AcademicYear::findOrFail($uay); //$cay Current Academic Year ID
        }else{
            $cay = AcademicYear::whereYear('opening_date', '=', date('Y'))->first();
        }
        $data['cay'] = $cay;
        $data['cayf'] = AcademicYear::whereYear('opening_date', '=', date('Y'))->first(); //cayf Current academic Year fixed
        $uid = Auth::user()->id;
        $data['cons'] = Contribution::whereAcademicYear($cay->id)->whereUserId($uid)->orderBy('id', 'asc')->paginate(100);
        $data['ays'] = AcademicYear::orderBy('id', 'desc')->get();

        return view('common.contribution', $data);
    }

    public function postContribution(Request $request)
    {
        $data['title'] = "Contribution";
        $data['eroute'] = "edit-contribution";

        $messages = [
            'doc.required_without' => 'You must Upload Document File Or Images as your contribution!',
            'file.required_without' => 'You must Upload Images Or Document File for your contribution!',
            'Terms_and_Conditions.required' => 'You must agree to :attribute before you can proceed!',
        ];

        $this->validate($request,[
            'title' => 'required|string|max:255',
            'academic_year' => 'required|exists:academic_years,id',
            'doc' => 'required_without:file|file|mimes:doc,docx,pdf|max:5120',
            'file' => 'required_without:doc',
            'file.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'Terms_and_Conditions' => 'required',
        ], $messages);

        // dd($diff."<br>".$cdiff."<br>".$fdiff);

        $acyear = AcademicYear::findOrFail($request->academic_year);

        /**
        * Checking Dates before submission
        **/

        $ct = Carbon::today();  // $cn = Carbon::now();

        $diff = $ct->diffInDays($acyear->opening_date, false); // dd($diff);
        if ($diff > 0) {
        session()->flash('message', 'You can not submit before the Starting date of the academic year!');
        Session::flash('type', 'error');
        return redirect()->back();
        }
        $cdiff = $ct->diffInDays($acyear->closing_date, false);  // dd($cdiff."<br>".$ct."<br>".$cn);
        if ($cdiff < 0) {
        session()->flash('message', 'You can not submit after the Closing date of the academic year!');
        Session::flash('type', 'error');
        return redirect()->back();
        }
        // End of checking dates


        $con['title'] = $request->title;

        $ray =  $acyear->year;
        $uid = Auth::user()->id;
        $user = User::findOrFail($uid);
        $coor = User::whereRole(3)->whereDepartmentId($user->department_id)->first();
       


        $con['academic_year'] = $acyear->id;
        $con['user_id'] = Auth::user()->id;
        $con['dep_id'] = Auth::user()->department_id;

        $con = Contribution::create($con);

        $lcon = $con->id;

        if ($request->doc) {
            $docname = pathinfo($request->doc->getClientOriginalName(), PATHINFO_FILENAME);

            $docname = preg_replace('!\s+!', ' ', $docname);
            $docname = str_replace(' ', '-', $docname);
            // $docname = strtolower($docname);

            $doc = $docname . '.' . $request->doc->getClientOriginalExtension();
            $doc = 'Con_'.$lcon. '_' . $doc;

            $request->doc->move(public_path('upload/' . $ray . '/con_' .$lcon.'_user_'.$uid), $doc);

            

             $con['file_name'] = $doc;
             $con->save();
        }


    if ($request->file) {
        $files = $request->file('file');
        foreach ($files as $file) {

            $newimg['con_id'] = $lcon;

            $imgname = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

            $imgname = preg_replace('!\s+!', ' ', $imgname);
            $imgname = str_replace(' ', '-', $imgname);

            $img = $imgname . '.' . $file->getClientOriginalExtension();
            $img = 'Con_'.$lcon. '_' . $img;

            $file->move(public_path('upload/' . $ray . '/con_' .$lcon.'_user_'.$uid), $img);

            $newimg['name'] = $img;

            ConImg::create($newimg);
           
        }
    }

        if ($coor) {
            Mail::to($coor)->send(new NewContribution($user, $con));
            session()->flash('message', 'Contribution Successfully Added and the Marketing Coordinator has been notified!');
        }else{
        
            session()->flash('message', 'Contribution Successfully Added!');
        }
        

        
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

       $uid = Auth::user()->id; 
       $con = Contribution::findOrFail($id);

        if ($uid != $con->user_id) {
            session()->flash('message', 'You do not have permission to view this page!');
            Session::flash('type', 'error');
            return redirect()->route('stdcontributions');
        }

        $data['ay'] = Contribution::findOrFail($id);

        
        $conimgs = ConImg::whereConId($id)->get();
        $data['conimgs'] = $conimgs;


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
            // 'academic_year' => 'required|exists:academic_years,id',
            'doc' => 'file|mimes:doc,docx,pdf|max:5120',
            // 'file' => 'required',
            'file.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',

            // 'exfile' => 'required',
            'exfile.*' => 'exists:con_imgs,id',
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
        // $con['academic_year'] = $request->academic_year;
        // $con['user_id'] = Auth::user()->id;


        if ($request->doc) {


            $docname = pathinfo($request->doc->getClientOriginalName(), PATHINFO_FILENAME);

            $docname = preg_replace('!\s+!', ' ', $docname);
            $docname = str_replace(' ', '-', $docname);
            // $docname = strtolower($docname);

            $doc = $docname . '.' . $request->doc->getClientOriginalExtension();
            $doc = 'Con_'.$con->id. '_' . $doc;

            if ($con->status > 2) {
                $filepath = 'upload/approved/'.$con->acyear->year.'/con_'.$con->id.'_user_'.$con->user_id.'/';

                File::delete($filepath.$con->file_name);
                $request->doc->move(public_path('upload/approved/' . $con->acyear->year . '/con_' .$con->id.'_user_'.$uid), $doc);
            }else{
                $filepath = 'upload/'.$con->acyear->year.'/con_'.$con->id.'_user_'.$con->user_id.'/';

                File::delete($filepath.$con->file_name);
                $request->doc->move(public_path('upload/' . $con->acyear->year . '/con_' .$con->id.'_user_'.$uid), $doc);
            }

            // File::delete('upload/'.$con->acyear->year.'/con_'.$con->id.'_user_'.$con->user_id.'/'.$con->file_name);
            

            

            

             $con['file_name'] = $doc;
        }        

        $con->save();

        $lcon = $id;

        $exconimgs = ConImg::whereConId($id)->get(); 

        if ($request->exfile) {
            $exfiles = $request->exfile;



            $tbdci = ConImg::whereConId($id)->whereNotIn('id',$exfiles)->get();//to be deleted con images


            // dd($tbdci);

            foreach ($tbdci as $ici) {

                if ($con->status > 2) {
                    $filepath = 'upload/approved/'.$ici->cont->acyear->year.'/con_'.$ici->cont->id.'_user_'.$ici->cont->user_id.'/';

                    File::delete($filepath.$ici->name);
                    // $request->doc->move(public_path('upload/approved/' . $con->acyear->year . '/con_' .$con->id.'_user_'.$uid), $doc);
                }else{
                    $filepath = 'upload/'.$ici->cont->acyear->year.'/con_'.$ici->cont->id.'_user_'.$ici->cont->user_id.'/';

                    File::delete($filepath.$ici->name);
                    // $request->doc->move(public_path('upload/' . $con->acyear->year . '/con_' .$con->id.'_user_'.$uid), $doc);
                }
                $ici->delete();
            }      
        }else{
            if (!$request->file && !$request->doc && !$con->file_name) {
                session()->flash('message', 'You can not delete all Contribution Images with out uploading new ones!');
                Session::flash('type', 'danger');
                return redirect()->back();
            }

            if ($exconimgs) {
                foreach ($exconimgs as $exci) {
                    if ($con->status > 2) {
                        $filepath = 'upload/approved/'.$exci->cont->acyear->year.'/con_'.$exci->cont->id.'_user_'.$exci->cont->user_id.'/';

                        File::delete($filepath.$exci->name);
                        // $request->doc->move(public_path('upload/approved/' . $con->acyear->year . '/con_' .$con->id.'_user_'.$uid), $doc);
                    }else{
                        $filepath = 'upload/'.$exci->cont->acyear->year.'/con_'.$exci->cont->id.'_user_'.$exci->cont->user_id.'/';

                        File::delete($filepath.$exci->name);
                        // $request->doc->move(public_path('upload/' . $con->acyear->year . '/con_' .$con->id.'_user_'.$uid), $doc);
                    }
                    $exci->delete();
                }
            }


        }

        if ($request->file) {
        $files = $request->file('file');
        foreach ($files as $file) {

            $newimg['con_id'] = $lcon;

            $imgname = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

            $imgname = preg_replace('!\s+!', ' ', $imgname);
            $imgname = str_replace(' ', '-', $imgname);

            $img = $imgname . '.' . $file->getClientOriginalExtension();
            $img = 'Con_'.$lcon. '_' . $img;

            if ($con->status > 2) {
                $file->move(public_path('upload/approved/' . $con->acyear->year . '/con_' .$lcon.'_user_'.$uid), $img);
            }else{
                $file->move(public_path('upload/' . $con->acyear->year . '/con_' .$lcon.'_user_'.$uid), $img);
            }
            $newimg['name'] = $img;

            ConImg::create($newimg);
           
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
        $conimgs = ConImg::whereConId($id)->get();
        $data['conimgs'] = $conimgs;


        if ($uid != $con->user_id) {

        session()->flash('message', "You don't have the required permission to view the requested page!");
        Session::flash('type', 'danger');
        return redirect()->back();        
        }

        $data['comments'] = Comment::whereConId($id)->orderBy('id', 'asc')->paginate(10);
        $data['comcount'] = Comment::whereConId($id)->count();


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

    public function getAcademicYear()
    {
        $data['title'] = "Academic Year";
        // $data['eroute'] = "edit-academic-year";
        $data['ays'] = AcademicYear::orderBy('id', 'asc')->paginate(10);

        return view('admin.academic-year', $data);
    }

    // public function getDeleteCheck()
    // {
    //     File::delete('upload/2019/con_2_user_3/Con_2_DE-TEST-PAPER.docx');

    //     return redirect()->back();
    // }
}
