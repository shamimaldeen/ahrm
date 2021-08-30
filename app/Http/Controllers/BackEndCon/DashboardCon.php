<?php

namespace App\Http\Controllers\BackEndCon;

use Illuminate\Http\Request;
use App\Http\Controllers\BackEndCon;
use Auth;
use App\CreateAdminModel;
use DB;
use DateTime;
use URL;
use Session;
use DateInterval;
use DateTimezone;
use App\Http\Controllers\BackEndCon\reportController;
use App\Http\Controllers\BackEndCon\otController;
use App\Http\Controllers\BackEndCon\leaveInfoController;
use App\TotalWorkingHours;
use App\ProbationPeriodNotifications;
use App\Employee;
use Image;
use App\EmployeeTypes;
use App\LeaveType;
use \Carbon\Carbon;

class DashboardCon extends Controller
{
    public function returnToHome()
    {
        Session::flash('error','Sorry! You might have entered an invalid Link or You might not have the permission to view this link.');
        return redirect('/');
    }

    public function chartArray()
    {
        return $chartArray=array(
            '1'=>array(
                'chart_header'=>'Attendence',
                'chart_header_label'=>'No. Of Employee',
                'chart_id'=>'attendance',
                'chart_div_class'=>'col-md-4',
            ),
            '2'=>array(
                'chart_header'=>'Monthly Leave',
                'chart_header_label'=>'No. Of Employee',
                'chart_id'=>'monthlyLeave',
                'chart_div_class'=>'col-md-4',
            ),
            '3'=>array(
                'chart_header'=>'Joinning & Separation',
                'chart_header_label'=>'No. Of Employee',
                'chart_id'=>'joinningSeparation',
                'chart_div_class'=>'col-md-4',
            ),
            '4'=>array(
                'chart_header'=>'Absent More Than 3 days',
                'chart_header_label'=>'No. Of Employee',
                'chart_id'=>'absentMoreThan3Days',
                'chart_div_class'=>'col-md-6',
            ),
            '5'=>array(
                'chart_header'=>'Late More Than 3 Days',
                'chart_header_label'=>'No. Of Employee',
                'chart_id'=>'lateMoreThan3Days',
                'chart_div_class'=>'col-md-6',
            ),
            '6'=>array(
                'chart_header'=>'Total OverTime',
                'chart_header_label'=>'Total Hours',
                'chart_id'=>'totalOvertime',
                'chart_div_class'=>'col-md-6',
            ),
            '7'=>array(
                'chart_header'=>'Total NC OverTime',
                'chart_header_label'=>'Total Hours',
                'chart_id'=>'totalNCOvertime',
                'chart_div_class'=>'col-md-6',
            ),
            '8'=>array(
                'chart_header'=>'NC Compensatory Leave',
                'chart_header_label'=>'No. Of Employee',
                'chart_id'=>'ncCompensatoryLeave',
                'chart_div_class'=>'col-md-6',
            ),
        );
    }

    public function index(){
        $id =   Auth::guard('admin')->user();
        //dd($id);
        $mainlink = $this->adminmainmenu();
        $sublink = $this->adminsubmenu();
        $Adminminlink = $this->adminlink();
        $adminsublink = $this->adminsublink();

//probation employee list count
        if($id->suser_level=="1"){
            $employees=Employee::where('emp_joindate','!=','')

                ->get();

        }else{
            $employees=Employee::where('emp_joindate','!=','')
                ->where(function($query) use($id){
                    return $query->where('emp_seniorid',$id->suser_empid)
                        ->orWhere('emp_authperson',$id->suser_empid);
                })
                ->get();
            // dd(count($employees));
        }
        $probations = array();
        $counter=0;
        if($employees[0]){
            foreach ($employees as $key => $employee) {
                if(strtotime($employee->emp_joindate)){
                    $days=$this->days(date('Y-m-d',strtotime($employee->emp_joindate)),date('Y-m-d'));
                    if($days>=180){
                        $search=ProbationPeriodNotifications::where('emp_id',$employee->emp_id)->first();
                        if(!isset($search->id)){
                            $confirm_date=date('Y-m-d',strtotime(date("Y-m-d", strtotime($employee->emp_joindate)) . " +6 month"));
                            $employee->emp_confirmdate=$confirm_date;
                            $employee->save();
                            if(substr($confirm_date,0,4)==date('Y')){
                                $counter++;
                                $probations[$counter]=array([
                                    'emp_id' => $employee->emp_id,
                                    'emp_name' => $employee->emp_name,
                                    'emp_empid' => $employee->emp_empid,
                                    'emp_machineid' => $employee->emp_machineid,
                                    'join_date' => $employee->emp_joindate,
                                    'confirm_date' => $employee->emp_confirmdate,
                                ]);
                            }
                        }
                    }
                }
            }
        }
        // End probation employee list count

        if($id->suser_level=="1"){
            $department=DB::table('tbl_department')->get();
        }elseif($id->suser_level=="4"){
            $department=DB::table('tbl_department')->where('depart_id',$this->getDepartmentId($id->suser_empid))->get();
        }

        $employee=DB::table('tbl_employee')
            ->where('emp_id',$id->suser_empid)
            ->first();
        $holidayDate=DB::table('tbl_holiday')
            ->where('holiday_date','>=',date('Y-m-d'))
            ->orderBy('holiday_date','asc')
            ->limit(7)
            ->groupBy('holiday_date')
            ->get();
        $holiday=DB::table('tbl_holiday')
            ->where('holiday_date','>=',date('Y-m-d'))
            ->orderBy('holiday_date','asc')
            ->get();
        $notice=DB::table('tbl_notice')
            ->join('tbl_employee','tbl_notice.notice_added_by','=','tbl_employee.emp_id')
            ->where('tbl_notice.notice_publish_from','<=',date('Y-m-d'))
            ->where('tbl_notice.notice_publish_to','>=',date('Y-m-d'))
            ->where('tbl_notice.notice_status','1')
            ->orderBy('notice_id','desc')
            ->get(['tbl_notice.*','tbl_employee.emp_name as notice_added_by']);
        
        $chart=array();
        $chart[1]=array(
            'title' => 'Present',
            'valueField' => 'present',
        );
        $chart[2]=array(
            'title' => 'Late',
            'valueField' => 'late',
        );
        $chart[3]=array(
            'title' => 'Absent',
            'valueField' => 'absent',
        );
        $chart[4]=array(
            'title' => 'On Leave',
            'valueField' => 'onleave',
        );
        
        $dateRange=$this->getDateRange(date('Y-m-d', strtotime('-7 days')),date('Y-m-d'));
        foreach ($dateRange as $key => $date) {
            $twh=TotalWorkingHours::where(['date'=>$date])->get();
            $p=$twh->where('present',1)->count();
            $a=$twh->where('present',0)->count();
            $l=$twh->where('late_entry','!=','00:00:00')->count();
            $ol=DB::table('tbl_leaveapplication')
                ->where(DB::raw('substr(leave_start_date,1,10)'),'<=',$date)
                ->where(DB::raw('substr(leave_end_date,1,10)'),'>=',$date)
                ->where('leave_status',1)
                ->groupBy('leave_empid')
                ->count();

            $chart[1]['dates'][$date]=$p;
            $chart[2]['dates'][$date]=$l;
            $chart[3]['dates'][$date]=$a;
            $chart[4]['dates'][$date]=$ol;
        }

        $notification=ProbationPeriodNotifications::with(['employee','approver'])->where(['emp_id'=>$id->suser_empid,'status'=>0])->first();

        $leave_notification = Employee::
                                join('tbl_leaveapplication', 'tbl_leaveapplication.leave_empid', '=', 'tbl_employee.emp_id')
                                ->where('tbl_leaveapplication.leave_empid', $id->suser_empid)
                                ->where('tbl_leaveapplication.leave_status', 0)
                                ->count();

        $loan_notification = Employee::
                                join('tbl_loans', 'tbl_loans.emp_id', '=', 'tbl_employee.emp_id')
                                ->where('tbl_loans.emp_id', $id->suser_empid)
                                ->where('tbl_loans.flag', 0)
                                ->count();

        $osd_notification = Employee::
                                join('tbl_osdattendance', 'tbl_osdattendance.osd_done_by', '=', 'tbl_employee.emp_id')
                                ->where('tbl_osdattendance.osd_done_by', $id->suser_empid)
                                ->where('tbl_osdattendance.osd_status', 0)
                                ->count();



        if($id->suser_level=="1"){
        $total_remote_emp =DB::table('tbl_sysuser')
            ->join('tbl_employee','tbl_sysuser.suser_empid','=','tbl_employee.emp_id')
           // ->orWhere('tbl_employee.emp_id',$id->suser_empid)
            ->where('tbl_sysuser.suser_emptype','0')
            ->count();

        }else{
            $total_remote_emp =DB::table('tbl_sysuser')
                ->join('tbl_employee','tbl_sysuser.suser_empid','=','tbl_employee.emp_id')
                ->orWhere('tbl_employee.emp_id',$id->suser_empid)
                ->where('tbl_sysuser.suser_emptype','0')
                ->count();
        }


        if($id->suser_level=="1"){
           $total_office_emp=DB::table('tbl_sysuser')
            ->join('tbl_employee','tbl_sysuser.suser_empid','=','tbl_employee.emp_id')
           // ->orWhere('tbl_employee.emp_id',$id->suser_empid)
            ->where('tbl_sysuser.suser_emptype','1')
            ->count();
        }else{
            $total_office_emp=DB::table('tbl_sysuser')
                ->join('tbl_employee','tbl_sysuser.suser_empid','=','tbl_employee.emp_id')
                ->orWhere('tbl_employee.emp_id',$id->suser_empid)
                ->where('tbl_sysuser.suser_emptype','1')
                ->count();
        }

        $leaveapplication=$this->filterleaveApplicationEmployee('0','0','0','0','0','0','0','0')->count();
       // dd($leaveapplication);

        $osd_attendance=$this->filterOSDAttendanceEmployee('0','0','0','0','0','0','','')->count();

        $recent_jointed_employee = DB::table("tbl_employee")
            ->whereDate('emp_joindate', '>', Carbon::now()->subDays(30))
            ->count();

        $recent_exited_employee = DB::table("tbl_employee")
            ->where('emp_status',0 )
            ->whereDate('inactive_datetime', '>', Carbon::now()->subDays(30))
            ->count();
       // dd($recent_exited_employee);
        return view('Admin.dashboard.index',compact('id','mainlink','sublink','Adminminlink','adminsublink','employee','holidayDate','holiday','notice','chart','notification', 'leave_notification', 'loan_notification', 'osd_notification','probations','total_remote_emp','total_office_emp','leaveapplication','osd_attendance','recent_jointed_employee','recent_exited_employee'));
    }

    public function getChartData(Request $request,$emp_depart_id,$chart_count,$chart_id)
    {
        $chart = array(
            'chart_id' => $chart_id, 
            'chart_header_label' => $this->chartArray()[$chart_count]['chart_header_label'], 
        );
        $reportController=new reportController();
        $chartDataArray=$this->$chart_id($emp_depart_id,$reportController);
        return view('Admin.dashboard.chart_data',compact('chart','chart_count','chartDataArray'));
    }

    public function filterDashboard(Request $request)
    {
        $chartArray=$this->chartArray();
        $emp_depart_id=$request->emp_depart_id;
        return view('Admin.dashboard.filterDashboard',compact('chartArray','emp_depart_id'));
    }

    public function totalEmployee($emp_depart_id)
    {
        $totalEmployee=DB::table('tbl_employee')
            ->when($emp_depart_id>0, function ($query) use ($emp_depart_id) {
                return $query->where('tbl_employee.emp_depart_id', $emp_depart_id);
            })
            ->where('tbl_employee.emp_status','1')
            // ->where('tbl_employee.emp_id', '!=' ,'116')
            // ->where('tbl_employee.emp_machineid', '!=' ,'null')
            ->count('tbl_employee.emp_id');


        return $totalEmployee;
    }

    public function punchedEmployee($emp_depart_id,$start_date,$end_date)
    {
        $totalPunchedEmployee=DB::table('kqz_card')
            ->join('kqz_employee','kqz_card.EmployeeID','kqz_employee.EmployeeID')
            ->join('tbl_employee','kqz_employee.EmployeeCode','tbl_employee.emp_machineid')
            ->when($emp_depart_id>0, function ($query) use ($emp_depart_id) {
                return $query->where('tbl_employee.emp_depart_id', $emp_depart_id);
            })
            ->where(DB::raw('substr(kqz_card.CardTime,1,10)'),'>=',$start_date)
            ->where(DB::raw('substr(kqz_card.CardTime,1,10)'),'<=',$end_date)
            ->groupBy('kqz_card.EmployeeID')
            ->get(['kqz_card.EmployeeID','tbl_employee.*']);
        return$totalPunchedEmployee;
    }

    public function totalPunchedEmployee($emp_depart_id,$start_date,$end_date)
    {
        $totalPunchedEmployee=DB::table('kqz_card')
            ->join('kqz_employee','kqz_card.EmployeeID','kqz_employee.EmployeeID')
            ->join('tbl_employee','kqz_employee.EmployeeCode','tbl_employee.emp_machineid')
            ->when($emp_depart_id>0, function ($query) use ($emp_depart_id) {
                return $query->where('tbl_employee.emp_depart_id', $emp_depart_id);
            })
            ->where(DB::raw('substr(kqz_card.CardTime,1,10)'),'>=',$start_date)
            ->where(DB::raw('substr(kqz_card.CardTime,1,10)'),'<=',$end_date)
            ->groupBy('kqz_card.EmployeeID')
            ->get(['kqz_card.EmployeeID']);
        return count($totalPunchedEmployee);
    }

    public function department($emp_depart_id)
    {
        $department=DB::table('tbl_department')
            ->when($emp_depart_id>0, function ($query) use ($emp_depart_id) {
                return $query->where('tbl_department.depart_id', $emp_depart_id);
            })
            ->where('depart_status','1')
            ->get();
        return $department;
    }

    public function attendance($emp_depart_id,$reportController)
    {
        $totalEmployee=$this->totalEmployee($emp_depart_id);
        $presentToday=$this->totalPunchedEmployee($emp_depart_id,date('Y-m-d'),date('Y-m-d'));
        $absentToday=$totalEmployee-$presentToday;
        $inLeave=DB::table('tbl_leaveapplication')
            ->join('tbl_employee','tbl_leaveapplication.leave_empid','=','tbl_employee.emp_id')
            ->when($emp_depart_id>0, function ($query) use ($emp_depart_id) {
                return $query->where('tbl_employee.emp_depart_id', $emp_depart_id);
            })
            ->where(DB::raw('substr(tbl_leaveapplication.leave_start_date,1,10)'),'<=',date('Y-m-d'))
            ->where(DB::raw('substr(tbl_leaveapplication.leave_end_date,1,10)'),'>=',date('Y-m-d'))
            ->where('tbl_leaveapplication.leave_status','1')
            ->groupBy('tbl_leaveapplication.leave_empid')
            ->get(['tbl_leaveapplication.leave_empid']);

        $labels='"Total Employee","Present Today","Absent Today","In Leave"';
        $data=$totalEmployee.','.$presentToday.','.$absentToday.','.count($inLeave);
        return $chartDataArray = array(
            'labels' => $labels, 
            'data' => $data, 
            'backgroundColor' => $reportController->backgroundColor('4'), 
        );
    }

    public function monthlyLeave($emp_depart_id,$reportController)
    {
        $labels='';
        $data='';
        $leaveType=DB::table('tbl_leavetype')->where('li_status','1')->get();
        foreach ($leaveType as $lt) {
            if($lt->li_id=="2"){
                $leaveEmployee=DB::table('tbl_leaveapplication')
                    ->join('tbl_employee','tbl_leaveapplication.leave_empid','=','tbl_employee.emp_id')
                    ->when($emp_depart_id>0, function ($query) use ($emp_depart_id) {
                        return $query->where('tbl_employee.emp_depart_id', $emp_depart_id);
                    })
                    ->where(['tbl_leaveapplication.leave_note'=>'Short Leave Taken','tbl_leaveapplication.leave_status'=>'1'])
                    ->where(DB::raw('substr(tbl_leaveapplication.leave_start_date,1,7)'),'>=',date('Y-m'))
                    ->where(DB::raw('substr(tbl_leaveapplication.leave_end_date,1,7)'),'<=',date('Y-m'))
                    ->groupBy('tbl_leaveapplication.leave_empid')
                    ->get(['tbl_leaveapplication.leave_empid']);
                $labels.='"'.$this->getLeaveTypename($lt->li_id).'",';
                $data.=count($leaveEmployee).',';
            }else{
                $leaveEmployee=DB::table('tbl_leaveapplication')
                    ->join('tbl_employee','tbl_leaveapplication.leave_empid','=','tbl_employee.emp_id')
                    ->when($emp_depart_id>0, function ($query) use ($emp_depart_id) {
                        return $query->where('tbl_employee.emp_depart_id', $emp_depart_id);
                    })
                    ->where(['tbl_leaveapplication.leave_typeid'=>$lt->li_id,'tbl_leaveapplication.leave_status'=>'1'])
                    ->where(DB::raw('substr(tbl_leaveapplication.leave_start_date,1,7)'),'>=',date('Y-m'))
                    ->where(DB::raw('substr(tbl_leaveapplication.leave_end_date,1,7)'),'<=',date('Y-m'))
                    ->groupBy('tbl_leaveapplication.leave_empid')
                    ->get(['tbl_leaveapplication.leave_empid']);
                $labels.='"'.$this->getLeaveTypename($lt->li_id).'",';
                $data.=count($leaveEmployee).',';
            }
        }
        
        return $chartDataArray = array(
            'labels' => $labels, 
            'data' => $data, 
            'backgroundColor' => $reportController->backgroundColor('6'), 
        );
    }

    public function notPunchedInDays($emp_depart_id,$days)
    {
        $punchedInDays=$this->totalPunchedEmployee($emp_depart_id,date("Y-m-d",strtotime("-$days Days")),date("Y-m-d"));
        return $notPunchedInDays=$this->totalEmployee($emp_depart_id)-$punchedInDays;
    }

    public function joinningSeparation($emp_depart_id,$reportController)
    {
        $newjoinners=DB::table('tbl_employee')
            ->when($emp_depart_id>0, function ($query) use ($emp_depart_id) {
                return $query->where('tbl_employee.emp_depart_id', $emp_depart_id);
            })
            ->where('tbl_employee.emp_joindate','>=',date("Y-m-d",strtotime("-3 Months")))
            ->where('tbl_employee.emp_status','1')
            ->count('tbl_employee.emp_id');
        $newLeavers=DB::table('tbl_employee')
            ->when($emp_depart_id>0, function ($query) use ($emp_depart_id) {
                return $query->where('tbl_employee.emp_depart_id', $emp_depart_id);
            })
            ->where(DB::raw('substr(tbl_employee.emp_rejected_at,1,10)'),'>=',date("Y-m-d",strtotime("-3 Months")))
            ->where('tbl_employee.emp_status','0')
            ->count('tbl_employee.emp_id');
        $notPunchedIn15Days=$this->notPunchedInDays($emp_depart_id,'15');

        $labels='"New Joinners","New Leavers","Not Punched In 15 Days"';
        $data=$newjoinners.','.$newLeavers.','.$notPunchedIn15Days;
        return $chartDataArray = array(
            'labels' => $labels, 
            'data' => $data, 
            'backgroundColor' => $reportController->backgroundColor('3'), 
        );
    }

    public function absentMoreThan3Days($emp_depart_id,$reportController)
    {
        $labels='';
        $data='';
        $department=$this->department($emp_depart_id);
        foreach ($department as $d) {
            $labels.='"'.$d->depart_name.'",';
            $data.=$this->notPunchedInDays($d->depart_id,'3').',';
        }
        
        return $chartDataArray = array(
            'labels' => $labels, 
            'data' => $data, 
            'backgroundColor' => $reportController->backgroundColor(count($department)), 
        );
    }

    public function late($emp_depart_id,$days)
    {
        $totalPunchedEmployee=$this->totalPunchedEmployee($emp_depart_id,date("Y-m-d",strtotime("-$days Days")),date("Y-m-d"));
        $notLateMoreThan3Days=DB::table('kqz_card')
            ->join('kqz_employee','kqz_card.EmployeeID','=','kqz_employee.EmployeeID')
            ->join('tbl_employee','kqz_employee.EmployeeCode','=','tbl_employee.emp_machineid')
            ->join('tbl_shift','tbl_employee.emp_shiftid','=','tbl_shift.shift_id')
            ->when($emp_depart_id>0, function ($query) use ($emp_depart_id) {
                return $query->where('tbl_employee.emp_depart_id', $emp_depart_id);
            })
            ->where(DB::raw('substr(kqz_card.CardTime,12,8)'),'>',DB::raw('tbl_shift.shift_stime'))
            ->where(DB::raw('substr(kqz_card.CardTime,1,10)'),'>=',date("Y-m-d",strtotime("-$days Days")))
            ->where(DB::raw('substr(kqz_card.CardTime,1,10)'),'<=',date("Y-m-d"))
            ->groupBy('kqz_card.EmployeeID')
            ->get(['kqz_card.EmployeeID']);
        return $lateMoreThan3Days=$totalPunchedEmployee-count($notLateMoreThan3Days);
    }

    public function lateMoreThan3Days($emp_depart_id,$reportController)
    {
        $labels='';
        $data='';
        $department=$this->department($emp_depart_id);
        foreach ($department as $d) {
            $labels.='"'.$d->depart_name.'",';
            $data.=$this->late($d->depart_id,'3').',';
        }
        
        return $chartDataArray = array(
            'labels' => $labels, 
            'data' => $data, 
            'backgroundColor' => $reportController->backgroundColor(count($department)), 
        );
    }

    public function shift_info($EmployeeID)
    {
        $shift_info=$this->getCurrentShiftInfo($this->getEmployeeIDtoemp_id($EmployeeID),date('Y-m-d'));
        return $shift_info;
    }

    public function ot($emp_depart_id,$reportController)
    {
        $ot=0;
        $punchedEmployee=$this->punchedEmployee($emp_depart_id,date('Y-m-d'),date('Y-m-d'));
        foreach ($punchedEmployee as $pe) {
            $twh=$reportController->totalWorkingHours($pe,date('Y-m-d'),$this->shift_info($pe->EmployeeID));
            $ot+=$this->timeToHours($twh['twh']);
        }
        return $ot;
    }

    public function totalOvertime($emp_depart_id,$reportController)
    {
        $labels='';
        $data='';
        $department=$this->department($emp_depart_id);
        foreach ($department as $d) {
            $labels.='"'.$d->depart_name.'",';
            $data.=$this->ot($d->depart_id,$reportController).',';
        }
        
        return $chartDataArray = array(
            'labels' => $labels, 
            'data' => $data, 
            'backgroundColor' => $reportController->backgroundColor(count($department)),
        );
    }

    public function NCOT($emp_depart_id,$reportController,$otController)
    {
        $nc=date("H:i:s",strtotime('00:00:00'));
        $totalNC=0;
        $punchedEmployee=$this->punchedEmployee($emp_depart_id,date('Y-m-d'),date('Y-m-d'));
        foreach ($punchedEmployee as $pe) {
            $singleDayNC = strtotime($otController->singleDayNC($pe->EmployeeID,date('Y-m-d')))-strtotime("00:00:00");
            $nc = date("H:i:s",strtotime($nc)+$singleDayNC);
            $totalNC+=$this->timeToHours($nc);
        }
        return $totalNC;
    }

    public function totalNCOvertime($emp_depart_id,$reportController)
    {
        $otController=new otController();
        $labels='';
        $data='';
        $department=$this->department($emp_depart_id);
        foreach ($department as $d) {
            $labels.='"'.$d->depart_name.'",';
            $data.=$this->NCOT($d->depart_id,$reportController,$otController).',';
        }
        
        return $chartDataArray = array(
            'labels' => $labels, 
            'data' => $data, 
            'backgroundColor' => $reportController->backgroundColor(count($department)), 
        );
    }

    public function ncCompensatoryLeave($emp_depart_id,$reportController)
    {
        $labels='';
        $data='';
        $department=$this->department($emp_depart_id);
        foreach ($department as $d) {
            $labels.='"'.$d->depart_name.'",';
            $data.=$this->totalEmployee($d->depart_id).',';
        }
        
        return $chartDataArray = array(
            'labels' => $labels, 
            'data' => $data, 
            'backgroundColor' => $reportController->backgroundColor(count($department)), 
        );
    }

    public function getTicketInfo($ticket_depart_id)
    {
        $ticketRaised=DB::table('tbl_ticket')
            ->where(['ticket_parent_id'=>'0','ticket_status'=>'1'])
            ->when($ticket_depart_id>0, function ($query) use ($ticket_depart_id) {
                return $query->where('tbl_ticket.ticket_depart_id', $ticket_depart_id);
            })
            ->count('ticket_id');
        $solved=DB::table('tbl_ticket')
            ->where('ticket_parent_id','!=','0')
            ->when($ticket_depart_id>0, function ($query) use ($ticket_depart_id) {
                return $query->where('tbl_ticket.ticket_depart_id', $ticket_depart_id);
            })
            ->groupBy('ticket_parent_id')
            ->get(['ticket_id']);
        $solved=count($solved);
        $unsolved=$ticketRaised-$solved;
        return '<li style="padding: 5px 0"><b>Ticket Raised</b> : '.$ticketRaised.'</li> <li style="padding: 5px 0"><b style="color:green;"">Solved</b> : '.$solved.'</li> <li style="padding: 5px 0"><b style="color:red;">Unsolved</b> : '.$unsolved.'</li>';
    }

    public function getSuserEmpInfo($suser_empid)
    {
        $data=DB::table('tbl_employee')->where('emp_id',$suser_empid)->first();
        if(isset($data->emp_id)){
            if($data->emp_imgext!=""){
                return '<img class="img-circle" style="height:35px;width:40px;" src="'.URL::to('public/EmployeeImage').'/'.$suser_empid.'.'.$data->emp_imgext.'"/> '. $data->emp_name  .' <i class="fa fa-angle-down"></i>';
            }else{
                return '<img class="img-circle" style="height:350px;width:40px;" src="'.URL::to('public').'/male.jpg"/> '. $data->emp_name .'<i class="fa fa-angle-down"></i>';
            }
        }
    }

    public function calender()
    {
        $row=DB::table('tbl_holiday')->get();
        foreach($row as $holiday)
        {
            $data[] = array(
            'id'   => $holiday->holiday_id,
            'title'   => $holiday->holiday_description,
            'start'   => $holiday->holiday_date,
            );
        }

        return json_encode($data);
    }




    public function filterleaveApplicationEmployee($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type,$start_date,$end_date,$status)
    {
        $id =   Auth::guard('admin')->user();
        if($id->suser_level=="1"){
            return $leaveapplication=DB::table('tbl_leaveapplication')
                ->join('tbl_employee','tbl_leaveapplication.leave_empid','=','tbl_employee.emp_id')
                ->when($emp_depart_id>0, function($query) use ($emp_depart_id){
                    return $query->where('tbl_employee.emp_depart_id', $emp_depart_id);
                })
                ->when($emp_sdepart_id>0, function($query) use ($emp_sdepart_id){
                    return $query->where('tbl_employee.emp_sdepart_id', $emp_sdepart_id);
                })
                ->when($emp_desig_id>0, function($query) use ($emp_desig_id){
                    return $query->where('tbl_employee.emp_desig_id', $emp_desig_id);
                })
                ->when($emp_jlid>0, function($query) use ($emp_jlid){
                    return $query->where('tbl_employee.emp_jlid', $emp_jlid);
                })
                ->when($emp_type>0, function($query) use ($emp_type){
                    return $query->where('tbl_employee.emp_type', $emp_type);
                })
                ->when($start_date!="0", function($query) use ($start_date){
                    return $query->where(DB::raw('substr(tbl_leaveapplication.leave_start_date,1,10)'),'>=',$start_date);
                })
                ->when($end_date!="0", function($query) use ($end_date){
                    return $query->where(DB::raw('substr(tbl_leaveapplication.leave_end_date,1,10)'),'<=',$end_date);
                })
                ->when($status>0, function($query) use ($status){
                    return $query->where('tbl_leaveapplication.leave_status','>','0');
                })
                ->when($status<=0, function($query) use ($status){
                    return $query->where('tbl_leaveapplication.leave_status','0');
                })
                ->join('tbl_leavetype','tbl_leaveapplication.leave_typeid','=','tbl_leavetype.li_id')
                ->orderBy('tbl_leaveapplication.leave_id','desc')
                ->get(['tbl_leaveapplication.*','tbl_employee.emp_empid','tbl_employee.emp_id','tbl_employee.emp_name','tbl_leavetype.li_name']);
        }elseif($id->suser_level=="4"){
            return $leaveapplication=DB::table('tbl_leaveapplication')
                ->join('tbl_employee','tbl_leaveapplication.leave_empid','=','tbl_employee.emp_id')
                ->join('tbl_leavetype','tbl_leaveapplication.leave_typeid','=','tbl_leavetype.li_id')
                ->when($start_date!="0", function($query) use ($start_date){
                    return $query->where(DB::raw('substr(tbl_leaveapplication.leave_start_date,1,10)'),'>=',$start_date);
                })
                ->when($end_date!="0", function($query) use ($end_date){
                    return $query->where(DB::raw('substr(tbl_leaveapplication.leave_end_date,1,10)'),'<=',$end_date);
                })
                ->when($status>0, function($query) use ($status){
                    return $query->where('tbl_leaveapplication.leave_status','>','0');
                })
                ->when($status<=0, function($query) use ($status){
                    return $query->where('tbl_leaveapplication.leave_status','0');
                })
                ->where('tbl_employee.emp_seniorid',$id->suser_empid)
                ->orWhere('tbl_employee.emp_id',$id->suser_empid)
                ->orderBy('tbl_leaveapplication.leave_id','desc')
                ->get(['tbl_leaveapplication.*','tbl_employee.emp_empid','tbl_employee.emp_id','tbl_employee.emp_name','tbl_leavetype.li_name']);
        }elseif($id->suser_level=="2" or $id->suser_level=="3" or $id->suser_level=="5" or $id->suser_level=="6"){
            return $leaveapplication=DB::table('tbl_leaveapplication')
                ->join('tbl_employee','tbl_leaveapplication.leave_empid','=','tbl_employee.emp_id')
                ->where('tbl_employee.emp_id',$id->suser_empid)
                ->when($start_date!="0", function($query) use ($start_date){
                    return $query->where(DB::raw('substr(tbl_leaveapplication.leave_start_date,1,10)'),'>=',$start_date);
                })
                ->when($end_date!="0", function($query) use ($end_date){
                    return $query->where(DB::raw('substr(tbl_leaveapplication.leave_end_date,1,10)'),'<=',$end_date);
                })
                ->when($status>0, function($query) use ($status){
                    return $query->where('tbl_leaveapplication.leave_status','>','0');
                })
                ->when($status<=0, function($query) use ($status){
                    return $query->where('tbl_leaveapplication.leave_status','0');
                })
                ->orderBy('tbl_leaveapplication.leave_id','desc')
                ->join('tbl_leavetype','tbl_leaveapplication.leave_typeid','=','tbl_leavetype.li_id')
                ->get(['tbl_leaveapplication.*','tbl_employee.emp_empid','tbl_employee.emp_id','tbl_employee.emp_name','tbl_leavetype.li_name']);
        }
    }






    public function filterOSDAttendanceEmployee($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type,$emp_id,$start_date,$end_date)
    {
        $id =   Auth::guard('admin')->user();
        if($id->suser_level=="1"){
            return $osd_attendance=DB::table('tbl_osdattendance')
                ->join('tbl_employee','tbl_employee.emp_id','=','tbl_osdattendance.osd_done_by')
                ->when($emp_depart_id>0, function($query) use ($emp_depart_id){
                    return $query->where('tbl_employee.emp_depart_id', $emp_depart_id);
                })
                ->when($emp_sdepart_id>0, function($query) use ($emp_sdepart_id){
                    return $query->where('tbl_employee.emp_sdepart_id', $emp_sdepart_id);
                })
                ->when($emp_desig_id>0, function($query) use ($emp_desig_id){
                    return $query->where('tbl_employee.emp_desig_id', $emp_desig_id);
                })
                ->when($emp_type>0, function($query) use ($emp_type){
                    return $query->where('tbl_employee.emp_type', $emp_type);
                })
                ->when($emp_id>0, function($query) use ($emp_id){
                    return $query->where('tbl_employee.emp_id', $emp_id);
                })
                ->when($start_date!="", function($query) use ($start_date){
                    return $query->where('tbl_osdattendance.osd_date','>=',$start_date);
                })
                ->when($end_date!="", function($query) use ($end_date){
                    return $query->where('tbl_osdattendance.osd_date','>=',$end_date);
                })
                ->orderBy('tbl_osdattendance.osd_status','asc')
                ->orderBy('tbl_osdattendance.osd_id','desc')
                ->where('tbl_osdattendance.osd_status','0')
                ->get(['tbl_osdattendance.*','tbl_employee.*']);
        }else if($id->suser_level=="4"){
            return $osd_attendance=DB::table('tbl_osdattendance')
                ->join('tbl_employee','tbl_employee.emp_id','=','tbl_osdattendance.osd_done_by')
                ->where('tbl_employee.emp_seniorid',$id->suser_empid)
                ->orWhere('tbl_employee.emp_id',$id->suser_empid)
                ->when($start_date!="", function($query) use ($start_date){
                    return $query->where('tbl_osdattendance.osd_date','>=',$start_date);
                })
                ->when($end_date!="", function($query) use ($end_date){
                    return $query->where('tbl_osdattendance.osd_date','>=',$end_date);
                })
                ->orderBy('tbl_osdattendance.osd_status','asc')
                ->orderBy('tbl_osdattendance.osd_id','desc')
                ->where('tbl_osdattendance.osd_status','0')
                ->get(['tbl_osdattendance.*','tbl_employee.*']);
        }else if($id->suser_level=="2" or $id->suser_level=="3" or $id->suser_level=="5" or $id->suser_level=="6"){
            return $osd_attendance=DB::table('tbl_osdattendance')
                ->join('tbl_employee','tbl_employee.emp_id','=','tbl_osdattendance.osd_done_by')
                ->where('tbl_employee.emp_id',$id->suser_empid)
                ->when($start_date!="", function($query) use ($start_date){
                    return $query->where('tbl_osdattendance.osd_date','>=',$start_date);
                })
                ->when($end_date!="", function($query) use ($end_date){
                    return $query->where('tbl_osdattendance.osd_date','>=',$end_date);
                })
                ->orderBy('tbl_osdattendance.osd_status','asc')
                ->orderBy('tbl_osdattendance.osd_id','desc')
                ->where('tbl_osdattendance.osd_status','0')
                ->get(['tbl_osdattendance.*','tbl_employee.*']);
        }
    }
}



