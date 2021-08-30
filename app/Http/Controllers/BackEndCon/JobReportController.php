<?php

namespace App\Http\Controllers\BackEndCon;

use Illuminate\Http\Request;
use App\Http\Controllers\BackEndCon\Controller;
use App\Http\Controllers\BackEndCon\JobController;
use Auth;
use App\ProjectDetails;
use App\Employee;
use App\Job;
use App\Appraisal;
use App\Skill;

class JobReportController extends Controller
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
        $Employee=Job::join('tbl_employee','tbl_job.emp_id','=','tbl_employee.emp_id')
            ->groupBy('tbl_job.emp_id')
            ->get([
                'tbl_employee.emp_id',
                'tbl_employee.emp_name',
                'tbl_employee.emp_empid'
            ]);

        return view('Admin.performance.report.index',compact('id','mainlink','sublink','Adminminlink','adminsublink','Employee'));
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

    public function getStartDate($start_date)
    {
        // $dateRange=explode(' - ',$dateRange);
        // $start_date=explode('/',$dateRange[0]);
        return $start_date=$start_date.'-'.$start_date.'-'.$start_date;
    }

    public function getEndDate($end_date)
    {
        // $dateRange=explode(' - ',$dateRange);
        // $end_date=explode('/',$dateRange[1]);
        return $end_date=$end_date.'-'.$end_date.'-'.$end_date;
    }
    public function store(Request $request)
    {
        $emp_id=$request->emp_id;
        $type=$request->type;
        $reportController=new reportController();
        $start_date=$this->getStartDate($request->start_date);
        $end_date=$this->getEndDate($request->end_date);
        if($type=="1"){
            $projectDetails=ProjectDetails::find('1');
            $Employee=Employee::find($emp_id);
            $Job=Job::with(['project','project.goal','appraisal','appraisal.skill'])
                ->where([
                    ['start_date','>=',$start_date],
                    ['start_date','<=',$end_date]
                ])
                ->orWhere([
                    ['start_date','<=',$start_date],
                    ['end_date','>=',$end_date]
                ])->orWhere([
                    ['end_date','>=',$start_date],
                    ['end_date','<=',$end_date]
                ])
                ->get();
            $Job->where('emp_id',$emp_id);
            return view('Admin.performance.report.valueReport',compact('projectDetails','Job','Employee','start_date','end_date'));
        }else if($type="2"){
            $projectDetails=ProjectDetails::find('1');
            $Employee=Employee::find($emp_id);
            $Skill=Skill::get();
            $JobController=new JobController();
            $incentive=$JobController->incentive();
            return view('Admin.performance.report.appraisalReport',compact('projectDetails','Skill','Employee','start_date','end_date','incentive'));
        }
    }

    static public function skillPerformance($emp_id,$skill_id,$start_date,$end_date)
    {
        $Controller=new Controller();
        $c=0;
        $total_target=0;
        $total_achieve=0;
        $total_performance=0;
        $total_target_incentive=0;
        $total_achieve_incentive=0;
        $Job=Job::with(['appraisal'])
                ->where([
                    ['start_date','>=',$start_date],
                    ['start_date','<=',$end_date]
                ])
                ->orWhere([
                    ['start_date','<=',$start_date],
                    ['end_date','>=',$end_date]
                ])->orWhere([
                    ['end_date','>=',$start_date],
                    ['end_date','<=',$end_date]
                ])
                ->get();
        if(isset($Job[0])){
            $Job->where('emp_id',$emp_id);
            if(isset($Job)){
                foreach ($Job as $app) {
                    foreach ($app->appraisal as $appraisal) {
                        if($appraisal->skill_id==$skill_id){
                            $c++;
                            $total_target+=$appraisal->target;
                            $total_achieve+=$appraisal->achieve;
                            $performance=0;
                            if($appraisal->target>0 && $appraisal->achieve>0){
                                $performance=$Controller->decimal(($appraisal->achieve/$appraisal->target)*100);
                            }
                            $total_performance+=$performance;
                            $total_target_incentive+=$appraisal->incentive_target_amount;
                            $total_achieve_incentive+=$appraisal->incentive_achieve_amount;
                        }
                    }
                }
            }
        }

        if($c>0){
            (($total_target>0) ? $target=$Controller->decimal($total_target) : $target=0 );
            (($total_achieve>0) ? $achieve=$Controller->decimal($total_achieve) : $achieve=0 );
            (($total_performance>0) ? $performance=$Controller->decimal($total_performance/$c) : $performance=0 );
            return array(
                'result' => true,
                'target' => $target, 
                'achieve' => $achieve, 
                'performance' => $performance, 
                'target_incentive' => $total_target_incentive, 
                'achieve_incentive' => $total_achieve_incentive, 
            );
        }else{
            return array(
                'result' => false, 
            );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
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
