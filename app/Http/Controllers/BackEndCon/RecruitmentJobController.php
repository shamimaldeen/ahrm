<?php

namespace App\Http\Controllers\BackEndCon;

use Illuminate\Http\Request;
use App\Http\Controllers\BackEndCon\Controller;
use Auth;
use App\Recruitment\Job;
use App\Recruitment\Jobseeker;
use App\Recruitment\Application;
use App\Employee;
use App\Admin;

class RecruitmentJobController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $id =   Auth::guard('admin')->user();
        $mainlink = $this->adminmainmenu();
        $sublink = $this->adminsubmenu();
        $Adminminlink = $this->adminlink();
        $adminsublink = $this->adminsublink();

        $jobs=Job::get();
        return view('Admin.recruitment.jobs',compact('id','mainlink','sublink','Adminminlink','adminsublink','jobs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $id =   Auth::guard('admin')->user();
        $mainlink = $this->adminmainmenu();
        $sublink = $this->adminsubmenu();
        $Adminminlink = $this->adminlink();
        $adminsublink = $this->adminsublink();

        return view('Admin.recruitment.openjob',compact('id','mainlink','sublink','Adminminlink','adminsublink'));
    }

    public function applications()
    {
        $id =   Auth::guard('admin')->user();
        $mainlink = $this->adminmainmenu();
        $sublink = $this->adminsubmenu();
        $Adminminlink = $this->adminlink();
        $adminsublink = $this->adminsublink();
        $applications=Application::with(['job','jobseeker'])->where('status','0')->get();

        return view('Admin.recruitment.applications',compact('id','mainlink','sublink','Adminminlink','adminsublink','applications'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,Job $job)
    {
        $this->validate($request,[
            'name' => 'required', 
            'description' => 'required', 
            'salary' => 'required', 
            'closing_date' => 'required', 
            'experience' => 'required',
            'email' => 'required', 
            'who_apply' => 'required', 
            'offer' => 'required', 
            'skills' => 'required'
        ]);

        $job->fill($request->all())->save();
        if(isset($job)){
            session()->flash('success','Job has been published!');
        }else{
            session()->flash('error','something went wrong!');
        }
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($application_id)
    {
        $id =   Auth::guard('admin')->user();
        $mainlink = $this->adminmainmenu();
        $sublink = $this->adminsubmenu();
        $Adminminlink = $this->adminlink();
        $adminsublink = $this->adminsublink();

        $application=Application::with(['job','jobseeker'])->find($application_id);
        return view('Admin.recruitment.application',compact('id','mainlink','sublink','Adminminlink','adminsublink','application'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($job_id)
    {
        $id =   Auth::guard('admin')->user();
        $mainlink = $this->adminmainmenu();
        $sublink = $this->adminsubmenu();
        $Adminminlink = $this->adminlink();
        $adminsublink = $this->adminsublink();
        $job=Job::find($job_id);

        return view('Admin.recruitment.editjob',compact('id','mainlink','sublink','Adminminlink','adminsublink','job'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'name' => 'required', 
            'description' => 'required', 
            'salary' => 'required', 
            'closing_date' => 'required', 
            'experience' => 'required',
            'email' => 'required', 
            'who_apply' => 'required', 
            'offer' => 'required', 
            'skills' => 'required',
            'inactive' => 'required'
        ]);

        $job=Job::find($id);
        $job->fill($request->all());
        $job->updated_at=date('Y-m-d H:i:s');
        $job->save();
        if(isset($job)){
            session()->flash('success','Job has been updated!');
        }else{
            session()->flash('error','something went wrong!');
        }
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(Job::find($id)->delete()){
            return response()->json(["success"=>true]);
        }else{
            return response()->json(["success"=>false]);
        }
    }


    public function approve(Request $request)
    {
        $msg='';
        $application=Application::find($request->application_id);
        $application->status='1';
        $application->feedback=$request->feedback;
        $application->save();
        if($application){
            $msg.='This Application has been Approved.';
        }else{
            return response()->json(["success"=>false,"msg"=>"Something Went Wrong!"]);
        }

        $jobseeker=Jobseeker::find($application->jobseeker_id);
        $employee=Employee::insertGetId([
            'emp_empid'=>$request->emp_empid,
            'emp_name'=>$jobseeker->name,
            'emp_type'=>'1',
            'emp_phone'=>$jobseeker->phone,
            'emp_country'=>'18',
            'emp_email'=>$jobseeker->email,
            'emp_joindate'=>date('Y-m-d'),
            'emp_confirmdate'=>date('Y-m-d',strtotime('+4 months')),
            'emp_workhistoryfrom'=>date('Y-m-d'),
            'emp_emjcontact'=>$jobseeker->phone,
            'emp_machineid'=>$request->emp_machineid,
            'emp_status'=>'1',
        ]);

        $explode=explode('-',$request->emp_empid);
        if(isset($explode[1]) && $explode[1]!=""){
            $email=$request->emp_empid;
            $password=$explode[1];
        }else{
            $email=$request->emp_empid;
            $password=substr($email,6,10);
        }
        $admin=Admin::insert([
            'email'=>$email,
            'password'=>bcrypt($password),
            'suser_empid'=>$employee,
            'suser_emptype'=>'1',
            'suser_level'=>'5',
        ]);

        if(isset($application) && isset($employee) && isset($admin)){
            session()->flash('success',$msg.'User profile & User Account has been created. To Login use Username : '.$email.' & Password : '.$password);
            return response()->json(['success'=>true]);
        }else{
            return response()->json(['success'=>false,"msg"=>"something went wrong!"]);
        }
    }

    public function reject(Request $request)
    {
        $application=Application::find($request->application_id);
        $application->status='2';
        $application->feedback=$request->feedback;
        $application->save();
        if($application){
            session()->flash('success','This Application has been rejected.');
            return response()->json(["success"=>true]);
        }else{
            return response()->json(["success"=>false,"msg"=>"Something Went Wrong!"]);
        }
    }

    public function jobseekers()
    {
        $id =   Auth::guard('admin')->user();
        $mainlink = $this->adminmainmenu();
        $sublink = $this->adminsubmenu();
        $Adminminlink = $this->adminlink();
        $adminsublink = $this->adminsublink();

        $jobseekers=Jobseeker::get();
        return view('Admin.recruitment.jobseekers',compact('id','mainlink','sublink','Adminminlink','adminsublink','jobseekers'));
    }

    public function jobseeker($jobseeker_id)
    {
        $id =   Auth::guard('admin')->user();
        $mainlink = $this->adminmainmenu();
        $sublink = $this->adminsubmenu();
        $Adminminlink = $this->adminlink();
        $adminsublink = $this->adminsublink();

        $jobseeker=Jobseeker::find($jobseeker_id);
        return view('Admin.recruitment.jobseeker',compact('id','mainlink','sublink','Adminminlink','adminsublink','jobseeker'));
    }
}
