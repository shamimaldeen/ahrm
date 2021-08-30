<?php

namespace App\Http\Controllers\BackEndCon;

use Illuminate\Http\Request;
use App\Http\Controllers\BackEndCon\Controller;
use Auth;
use App\ProjectDetails;
use App\Employee;
use App\Job;
use App\JobNote;
use App\Skill;
use App\Admin;
use App\Project;
use App\JobSkill;
use App\JobReviewer;
use App\Appraisal;
use App\SwitchIncentive;

class JobController extends Controller
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
        $Employee=Employee::with(['department','designation','jobs','runningjobs','finishedjobs'])->get();

        return view('Admin.performance.jobs.view',compact('id','mainlink','sublink','Adminminlink','adminsublink','Employee'));
    }

    public function assignJob($emp_id)
    {
        // $search=Job::with(['employee'])->where(['emp_id'=>$emp_id,['completion','<','100']])->get();
        // if(isset($search[0])){
        //     session()->flash('error','Whoops!'.$search[0]->employee->emp_name.'( '.$search[0]->employee->emp_empid.' ) has an unfinished job.');
        //     return redirect('jobs');
        // }

        $id =   Auth::guard('admin')->user();
        $mainlink = $this->adminmainmenu();
        $sublink = $this->adminsubmenu();
        $Adminminlink = $this->adminlink();
        $adminsublink = $this->adminsublink();
        $Employee=Employee::find($emp_id);
        $Project=Project::get();
        $Skill=Skill::get();
        $Reviewer=Admin::with('employee')->where('suser_level','1')->get();

        return view('Admin.performance.jobs.assign',compact('id','mainlink','sublink','Adminminlink','adminsublink','Project','Skill','Reviewer','Employee','emp_id'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'project_id'=>'required',
            'job_title'=>'required',
            'emp_id'=>'required',
            'job_weight'=>'required',
            'completion'=>'required',
            'start_date'=>'required',
            'end_date'=>'required',
        ]);
        $projectWeight = Job::where("project_id", "=", $request->project_id)->get();

        if($projectWeight->sum("job_weight") + (int) $request->job_weight > "100"){
            
            return redirect()->back();
        }

        $job->fill($request->all());   

        if($job->save()){
            $countSkill = count($request->skill);
            $countReviewer = count($request->reviewers);

            for ($i=0; $i < $countSkill; $i++) { 
                JobSkill::insert([
                    'job_id' => $job->id,
                    'skill_id' => $request->skill[$i]
                ]);
            }

            for ($i=0; $i < $countReviewer; $i++) { 
                JobReviewer::insert([
                    'job_id' => $job->id,
                    'emp_id' => $request->reviewers[$i]
                ]);
            }

            $this->assignAppraisal($job->id,$request->skill);
            session()->flash('success','Job Assigned successfuly');
        }else{
            session()->flash('error','Something Went Wrong!');
        }
        return redirect()->back();
    }

    public function assignAppraisal($job_id,$skill)
    {
        for($i=0;$i<count($skill);$i++) {
            $search=Appraisal::where(['job_id'=>$job_id,'skill_id'=>$skill[$i]])->get();
            if(!isset($search->job_id)){
                Appraisal::insert([
                    'job_id'=>$job_id,
                    'skill_id'=>$skill[$i],
                    'created_at'=>date('Y-m-d H:i:s'),
                ]);
            }
        }
    }

    public function viewJob($emp_id)
    {
        $id =   Auth::guard('admin')->user();
        $mainlink = $this->adminmainmenu();
        $sublink = $this->adminsubmenu();
        $Adminminlink = $this->adminlink();
        $adminsublink = $this->adminsublink();
        $Employee=Employee::with(['jobs','jobs.project','jobs.project.goal'])->find($emp_id);

        return view('Admin.performance.jobs.viewJobs',compact('id','mainlink','sublink','Adminminlink','adminsublink','Employee'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($job_id)
    {
        $id =   Auth::guard('admin')->user();
        $mainlink = $this->adminmainmenu();
        $sublink = $this->adminsubmenu();
        $Adminminlink = $this->adminlink();
        $adminsublink = $this->adminsublink();
        
        $Job=Job::with(['employee','project','project.goal','appraisal','reviewer','notes'])->find($job_id);
        $incentive=$this->incentive();

        return view('Admin.performance.jobs.job',compact('id','mainlink','sublink','Adminminlink','adminsublink','Job','incentive'));
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
        $Project=Project::get();
        $Skill=Skill::get();
        $Reviewer=Admin::with('employee')->where('suser_level','1')->get();
        $Job=Job::with(['employee','skill','reviewer'])->find($job_id);

        return view('Admin.performance.jobs.edit',compact('id','mainlink','sublink','Adminminlink','adminsublink','Job','Project','Skill','Reviewer'));
    }

    static public function checkSkill($job_id,$skill_id)
    {
        $search=JobSkill::where(['job_id'=>$job_id,'skill_id'=>$skill_id])->first();
        if(isset($search->job_id)){
            return '1';
        }else{
            return '0';
        }
    }

    static public function checkReviewer($job_id,$emp_id)
    {
        $search=JobReviewer::where(['job_id'=>$job_id,'emp_id'=>$emp_id])->first();
        if(isset($search->job_id)){
            return '1';
        }else{
            return '0';
        }
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
            'project_id'=>'required',
            'job_title'=>'required',
            'job_weight'=>'required',
            'completion'=>'required',
            'start_date'=>'required',
            'end_date'=>'required',
            'status'=>'required',
        ]);
        $projectWeight = Job::where("project_id", "=", $request->project_id)->get();

        if($projectWeight->sum("job_weight") + (int) $request->job_weight > "100"){
            
            return redirect()->back();
        }
        $job=Job::find($id);
        $job->fill($request->all());   

        if($job->save()){
            JobSkill::where(['job_id'=>$id])->delete();
            JobReviewer::where(['job_id'=>$id])->delete();
            $countSkill = count($request->skill);
            $countReviewer = count($request->reviewers);

            for ($i=0; $i < $countSkill; $i++) { 
                JobSkill::insert([
                    'job_id' => $id,
                    'skill_id' => $request->skill[$i]
                ]);
            }

            for ($i=0; $i < $countReviewer; $i++) { 
                JobReviewer::insert([
                    'job_id' => $id,
                    'emp_id' => $request->reviewers[$i]
                ]);
            }
            $this->assignAppraisal($job->id,$request->skill);
            session()->flash('success','Job Updated successfuly');
        }else{
            session()->flash('error','Something Went Wrong!');
        }
        return redirect('jobs/'.$id);
    }

    public function jobAppraisal($job_id)
    {
        $id =   Auth::guard('admin')->user();
        $mainlink = $this->adminmainmenu();
        $sublink = $this->adminsubmenu();
        $Adminminlink = $this->adminlink();
        $adminsublink = $this->adminsublink();
        $Job=Job::with(['employee','skill','project'])->find($job_id);
        $Appraisal=Appraisal::where(['job_id'=>$job_id])->get();
        $incentive=$this->incentive();
        return view('Admin.performance.jobs.appraisal',compact('id','mainlink','sublink','Adminminlink','adminsublink','Job','Appraisal','incentive'));
    }

    public function incentive()
    {
        $SwitchIncentive=SwitchIncentive::orderBy('id','desc')->first();
        $incentive=true;
        $percentage=0;
        if(isset($SwitchIncentive->incentive)){
            if($SwitchIncentive->incentive=="1"){
                $incentive=true;
            }elseif($SwitchIncentive->incentive=="0"){
                $incentive=false;
            }
            $percentage=$SwitchIncentive->percentage;
        }
        return array(
            'incentive' => $incentive, 
            'percentage' => $percentage, 
        );
    }

    public function jobAppraisalSubmit(Request $request,$job_id)
    {
        $skill_weight=0;
        $success=0;
        $error=0;
        foreach ($request->skill_weight as $key => $value) {
            $skill_weight+=$value;
        }

        if($skill_weight>100){
            session()->flash('error','Whoops! Skill Weight has been exceeded. Try Again.');
            return redirect()->back();
        }else{
            $Appraisal=Appraisal::where(['job_id'=>$job_id])->get();
            foreach ($Appraisal as $app) {
                if($this->incentive()){
                    $update=Appraisal::where([
                        'job_id'=>$job_id,
                        'skill_id'=>$app->skill_id,
                    ])
                    ->update([
                        "skill_weight"=>$request->skill_weight[$app->skill_id],
                        "target"=>$request->target[$app->skill_id],
                        "achieve"=>$request->achieve[$app->skill_id],
                        "incentive_target_amount"=>$request->incentive_target_amount[$app->skill_id],
                        "incentive_achieve_amount"=>$request->incentive_achieve_amount[$app->skill_id]
                    ]);
                }else{
                    $update=Appraisal::where([
                        'job_id'=>$job_id,
                        'skill_id'=>$app->skill_id,
                    ])
                    ->update([
                        "skill_weight"=>$request->skill_weight[$app->skill_id],
                        "target"=>$request->target[$app->skill_id],
                        "achieve"=>$request->achieve[$app->skill_id]
                    ]);
                }

                if(isset($update)){
                    $success++;
                }else{
                    $error++;
                }   
            }

            if($success>0){
                session()->flash('success',$success.' Appraisal Updated successfuly.');
            }

            if($error>0){
                session()->flash('error',$error.' Appraisal could not be updated due to error.');
            }
            return redirect()->back();
        }
    }

    public function getNote($job_id)
    {
        $JobNote=JobNote::with(['employee'])->where(['job_id'=>$job_id])->get();
        if(isset($JobNote[0])){
            $notes='';
            foreach ($JobNote as $note) {
                $notes.='<div class="panel panel-default">
                          <div class="panel-heading">
                              <small><strong>'.$note->employee->emp_name.' ( '.$note->employee->emp_empid.' )</strong></small>
                              <br>
                              <span style="font-size: 10px">'.date("l jS \of F Y g:i A",strtotime($note->date)).'</span>
                          </div>
                          <div class="panel-body">
                            '.$note->message.'
                          </div>
                        </div>';
            }
            return $notes;
        }else{
            return '<div class="alert alert-info alert-dismissible">
                      <a class="close" data-dismiss="alert" aria-label="close">&times;</a>
                      <strong>Hello!</strong> Keep notes about this job here.
                    </div';
        }
    }

    public function sendNote(Request $request,$job_id)
    {
        if(!empty($request->message)){
            $id =   Auth::guard('admin')->user();
            $JobNote=new JobNote();
            $JobNote->fill($request->all());
            $JobNote->job_id=$job_id;
            $JobNote->emp_id=$id->suser_empid;
            $JobNote->date=date('Y-m-d H:i:s');
            $JobNote->save();
            if(isset($JobNote->id)){
                $note=JobNote::with(['employee'])->find($JobNote->id);
                return response()->json([
                    "success"=>true,
                    "emp_name"=>$note->employee->emp_name,
                    "emp_empid"=>$note->employee->emp_empid,
                    "date"=>date("l jS \of F Y g:i A",strtotime($note->date)),
                    "message"=>$note->message
                ]);
            }else{
                return response()->json(["success"=>false]);
            }

        }else{
            return response()->json(["success"=>false]);
        }
    }

    public function jobReport($job_id)
    {
        $projectDetails=ProjectDetails::find('1');
        $Job=Job::with(['employee','project','project.goal','appraisal','reviewer'])->find($job_id);
        $incentive=$this->incentive();
        return view('Admin.performance.jobs.jobReport',compact('projectDetails','Job','incentive'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
