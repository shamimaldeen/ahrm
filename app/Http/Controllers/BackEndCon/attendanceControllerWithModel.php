<?php

namespace App\Http\Controllers\BackEndCon;

use Illuminate\Http\Request;
use App\Http\Controllers\BackEndCon\Controller;
use Auth;
use Session;
use DB;
use DateTime;

use App\Employee;
use App\Admin;
use App\Insurance;
use App\Salary;
use App\Designation;
use App\Department;
use App\SubDepartment;
use App\JobLocation;
use App\Country;
use App\Shift;
use App\DailyShift;
use App\EmployeeHistory;
use App\Priority;
use App\Card;
use App\OSDAttendance;
use App\LeaveApplication;
use App\Holiday;

class attendanceController extends Controller
{

public function filterAttendanceEmployee($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type,$emp_id)
{
    $id =   Auth::guard('admin')->user();
    if($id->suser_level=="1"){
        return $card=Employee::join('kqz_employee','tbl_employee.emp_machineid','=','kqz_employee.EmployeeCode')
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
                return $query->where('kqz_employee.EmployeeID', $emp_id);
            })
            ->groupBy('tbl_employee.emp_machineid')
            ->get([
                'kqz_employee.EmployeeID','tbl_employee.emp_id','tbl_employee.emp_empid','tbl_employee.emp_name','tbl_employee.emp_wknd','tbl_employee.emp_machineid'
            ]);
    }else if($id->suser_level=="4"){
        return $card=Employee::join('kqz_employee','tbl_employee.emp_machineid','=','kqz_employee.EmployeeCode')
            ->where('tbl_employee.emp_seniorid',$id->suser_empid)
            ->orWhere('tbl_employee.emp_id',$id->suser_empid)
            ->groupBy('tbl_employee.emp_machineid')
            ->get([
                'kqz_employee.EmployeeID','tbl_employee.emp_id','tbl_employee.emp_empid','tbl_employee.emp_name','tbl_employee.emp_wknd','tbl_employee.emp_machineid'
            ]);
    }else if($id->suser_level=="2" or $id->suser_level=="3" or $id->suser_level=="5" or $id->suser_level=="6"){
        return $card=Employee::join('kqz_employee','tbl_employee.emp_machineid','=','kqz_employee.EmployeeCode')
            ->groupBy('tbl_employee.emp_machineid')
            ->where('tbl_employee.emp_id',$id->suser_empid)
            ->get([
                'kqz_employee.EmployeeID','tbl_employee.emp_id','tbl_employee.emp_empid','tbl_employee.emp_name','tbl_employee.emp_wknd','tbl_employee.emp_machineid'
            ]);
    }
}

public function getAttendanceRow($emp,$date,$counter,$final_entry_time,$calculate_exit_time,$twh,$nc_hour,$id,$type)
{

    $data = array(
        'counter'=>$counter,
        'emp'=>$emp,
        'date'=>$date,
        'twh'=>$twh,
        'final_entry_time'=>$final_entry_time,
        'calculate_exit_time'=>$calculate_exit_time,
        'nc_hour'=>$nc_hour,
        'type'=>$type,
        'attendanceStatus'=>$this->attendanceStatus($emp,$date),
    );
    return $data;
}

public function entryCount($EmployeeID,$date)
{
    $entryCount=0;
    $card_count=Card::with(['entrydevice'])
                ->where('kqz_card.EmployeeID',$EmployeeID)
                ->where(DB::raw("substr(kqz_card.CardTime, 1, 10)"), '=',$date)
                ->count();
    /*$card_count=DB::table('kqz_card')
        ->join('kqz_devinfo','kqz_card.DevID','=','kqz_devinfo.DevID')
        ->where('kqz_card.EmployeeID',$EmployeeID)
        ->where('kqz_card.DevID','<','7')
        ->where('kqz_devinfo.DevType','1')
        ->where(DB::raw("substr(kqz_card.CardTime, 1, 10)"), '=',$date)
        ->count();*/
    $entryCount+=$card_count;
    $card_count=Card::with(['entryremotedevice'])
                ->where('kqz_card.EmployeeID',$EmployeeID)
                ->where(DB::raw("substr(kqz_card.CardTime, 1, 10)"), '=',$date)
                ->count();
    /*$card_count=DB::table('kqz_card')
        ->join('tbl_remotedevice','kqz_card.DevID','=','tbl_remotedevice.DevID')
        ->where('kqz_card.EmployeeID',$EmployeeID)
        ->where('kqz_card.DevID','>','6')
        ->where('tbl_remotedevice.DevType','1')
        ->where(DB::raw("substr(kqz_card.CardTime, 1, 10)"), '=',$date)
        ->count();*/
    $entryCount+=$card_count;
    return $entryCount;
}

public function exitCount($EmployeeID,$date)
{
    $exitCount=0;
    $card_count=Card::with(['exitdevice'])
                ->where('kqz_card.EmployeeID',$EmployeeID)
                ->where(DB::raw("substr(kqz_card.CardTime, 1, 10)"), '=',$date)
                ->count();
    /*$card_count=DB::table('kqz_card')
        ->join('kqz_devinfo','kqz_card.DevID','=','kqz_devinfo.DevID')
        ->where('kqz_card.EmployeeID',$EmployeeID)
        ->where('kqz_card.DevID','<','7')
        ->where('kqz_devinfo.DevType','0')
        ->where(DB::raw("substr(kqz_card.CardTime, 1, 10)"), '=',$date)
        ->count();*/
    $exitCount+=$card_count;
    $card_count=Card::with(['exitremotedevice'])
                ->where('kqz_card.EmployeeID',$EmployeeID)
                ->where(DB::raw("substr(kqz_card.CardTime, 1, 10)"), '=',$date)
                ->count();
    /*$card_count=DB::table('kqz_card')
        ->join('tbl_remotedevice','kqz_card.DevID','=','tbl_remotedevice.DevID')
        ->where('kqz_card.EmployeeID',$EmployeeID)
        ->where('kqz_card.DevID','>','6')
        ->where('tbl_remotedevice.DevType','0')
        ->where(DB::raw("substr(kqz_card.CardTime, 1, 10)"), '=',$date)
        ->count();*/
    $exitCount+=$card_count;
    return $exitCount;
}

public function attendanceStatus($emp,$date)
{
    $status='';
    if($this->checkPresent($emp->EmployeeID,$date)=="1"){
        $status.=$this->statusButton('P');
        if($this->entryCount($emp->EmployeeID,$date)=="0"){
            $status.=$this->statusButton('ENNF');
        }

        if($this->exitCount($emp->EmployeeID,$date)=="0"){
            $status.=$this->statusButton('EXNF');
        }
    }

    if($this->checkHoliday($date)=="1"){
        $status.=$this->statusButton('H');
    }elseif($this->checkWeekend($emp,$date)=="1"){
        $status.=$this->statusButton('W');
    }else{
        $checkLeave=$this->checkLeave($emp->emp_id,$date,$date);
        if(isset($checkLeave->leave_empid)){
            $status.=$this->statusButton('L&'.$this->checkLeave($emp->emp_id,$date,$date)->leave_typeid);
        }else{
            if($this->checkPresent($emp->EmployeeID,$date)=="0"){
                $status.=$this->statusButton('A');
            }
        }
    }

    if($this->totalOSDDuration($emp->emp_id,$date,$date)>0){
        $status.=$this->statusButton('OSD&'.$this->totalOSDDuration($emp->emp_id,$date,$date));
    }

    return $status;
}

public function attendanceShortStatus($emp,$date)
{
    $status='';
    if($this->checkPresent($emp->EmployeeID,$date)=="1"){
        $status.=$this->statusText('P');
        if($this->entryCount($emp->EmployeeID,$date)=="0"){
            $status.=$this->statusText('ENNF');
        }

        if($this->exitCount($emp->EmployeeID,$date)=="0"){
            $status.=$this->statusText('EXNF');
        }
    }else{
        if($this->checkHoliday($date)=="1"){
            $status.=$this->statusText('H');
        }elseif($this->checkWeekend($emp,$date)=="1"){
            $status.=$this->statusText('W');
        }else{
            $checkLeave=$this->checkLeave($emp->emp_id,$date,$date);
            if(isset($checkLeave->leave_empid)){
                $status.=$this->statusText('L&'.$this->checkLeave($emp->emp_id,$date,$date)->leave_typeid);
            }else{
                $status.=$this->statusText('A');
            }
        }
    }

    if($this->totalOSDDuration($emp->emp_id,$date,$date)>0){
        $status.=$this->statusText('OSD&'.$this->totalOSDDuration($emp->emp_id,$date,$date));
    }
    
    return $status;
}

public function checkHoliday($date)
{
    $holiday=Holiday::where(['holiday_date'=>$date,'holiday_status'=>'1'])->first();
    if(isset($holiday->holiday_date)){
        return '1';
    }else{
        return '0';
    }
}

public function checkWeekend($emp,$date)
{
    if($this->dayName($emp->emp_wknd)==date("D",strtotime($date))){
        return '1';
    }else{
        return '0';
    }
}

public function statusButton($type)
{
    $data='';
    if($type=="P"){
        $data.='<a class="btn btn-success btn-xs">  Present </a>';
    }elseif($type=="A"){
        $data.='<a class="btn btn-danger btn-xs">  Absent </a>';
    }elseif(explode('&',$type)[0]=="OSD"){
        $data.='<a class="btn btn-info btn-xs">  OSD ('.explode('&',$type)[1].') </a>';
    }elseif(explode('&',$type)[0]=="L"){
        $data.='<a class="btn btn-danger btn-xs">  '.$this->getLeaveTypename(explode('&',$type)[1]).' </a>';
    }elseif($type=="ENNF"){
        $data.='<a class="btn btn-danger btn-xs">  No Entry Found </a>';
    }elseif($type=="EXNF"){
        $data.='<a class="btn btn-danger btn-xs">  No Exit Found </a>';
    }elseif($type=="H"){
        $data.='<a class="btn btn-danger btn-xs">  Holiday </a>';
    }elseif($type=="W"){
        $data.='<a class="btn btn-danger btn-xs">  Weekend </a>';
    }
    return $data;
}

public function statusText($type)
{
    $data='';
    if($type=="P"){
        $data.='<a class="btn btn-success btn-xs"> P </a>';
    }elseif($type=="A"){
        $data.='<a class="btn btn-danger btn-xs"> A </a>';
    }elseif(explode('&',$type)[0]=="OSD"){
        $data.='<a class="btn btn-info btn-xs"> OSD </a>';
    }elseif(explode('&',$type)[0]=="L"){
        $data.='<a class="btn btn-danger btn-xs"> L </a>';
    }elseif($type=="ENNF"){
        $data.='<a class="btn btn-danger btn-xs"> NENF </a>';
    }elseif($type=="EXNF"){
        $data.='<a class="btn btn-danger btn-xs"> NEXF </a>';
    }elseif($type=="H"){
        $data.='<a class="btn btn-danger btn-xs"> H </a>';
    }elseif($type=="W"){
        $data.='<a class="btn btn-danger btn-xs"> W </a>';
    }
    return $data;
}

public function totalOSDDuration($osd_done_by,$start_date,$end_date)
{
    $total_osd=date("H:i:s",strtotime('00:00:00'));

    $totalOSDDuration=OSDAttendance::where([
            'osd_done_by'=>$osd_done_by,
            ['osd_date','>=',$start_date],
            ['osd_date','<=',$end_date],
            'osd_status'=>'1'
        ])
        ->get();

    if(isset($totalOSDDuration)){
        foreach ($totalOSDDuration as $osd) {
            $osd_duration = strtotime($osd->osd_duration)-strtotime("00:00:00");
            $total_osd = date("H:i:s",strtotime($total_osd)+$osd_duration);
        }
    }
    return $total_osd;
}

public function checkLeave($emp_id,$start_date,$end_date)
{
    $checkLeave=LeaveApplication::where([
        'leave_empid'=>$emp_id,
        [DB::raw('substr(leave_start_date,1,10)'),'<=',$start_date],
        [DB::raw('substr(leave_end_date,1,10)'),'>=',$start_date],
        'leave_status'=>'1'
        ])
        ->first();
    return $checkLeave;
}

public function attendance(){


    // echo "string"; exit();
    $id =   Auth::guard('admin')->user();
    $otController=new otController();
    $mainlink = $this->adminmainmenu();
    $sublink = $this->adminsubmenu();
    $Adminminlink = $this->adminlink();
    $adminsublink = $this->adminsublink();

    $designation=Designation::get();
    $department=Department::get();
    $joblocation=JobLocation::get();

    //todays all employee add (start)//
    $card=$this->filterAttendanceEmployee('0','0','0','0','0','0');
    //todays all employee add (end)//

    $data = array();

    $counter=0;
    //employee loop (start)//
    foreach ($card as $emp) {
    $counter++;
    unset($final_entry_time);
    unset($calculate_exit_time);
    
    $shift_info=$this->getCurrentShiftInfo($emp->emp_id,date('Y-m-d'));     
    if($this->checkPresent($emp->EmployeeID,date('Y-m-d'))=="1"){
        
        $count=$this->entryCount($emp->EmployeeID,date('Y-m-d'));
        $twh=date("H:i:s",strtotime('00:00:00'));

        for ($i=0; $i < $count; $i++) {

            if($i=="0"){
                //entry time calculation (start)//
                $entry_time=Card::with(['entrydevice'])
                    ->where([
                        'kqz_card.EmployeeID'=>$emp->EmployeeID,
                        [DB::raw("substr(kqz_card.CardTime, 1, 10)"), '=',date('Y-m-d')],
                    ])
                    ->orderBy('kqz_card.CardTime','ASC')
                    ->groupBy(DB::raw("substr(kqz_card.CardTime, 12, 5)"))
                    ->first(['kqz_card.CardTime']);
                if(!isset($entry_time->CardTime)){
                    $entry_time=Card::with(['entryremotedevice'])
                    ->where([
                        'kqz_card.EmployeeID'=>$emp->EmployeeID,
                        [DB::raw("substr(kqz_card.CardTime, 1, 10)"), '=',date('Y-m-d')]
                    ])
                    ->orderBy('kqz_card.CardTime','ASC')
                    ->groupBy(DB::raw("substr(kqz_card.CardTime, 12, 5)"))
                    ->first(['kqz_card.CardTime']);
                }
                if(isset($entry_time->CardTime)){
                    $final_entry_time=substr($entry_time->CardTime,11,8);
                    $calculate_entry_time=substr($entry_time->CardTime,11,8);
                    $calculate_entry_date=substr($entry_time->CardTime,0,10);
                    
                    $twh=date("H:i:s",strtotime('00:00:00'));
                }
                //entry time calculation (end)//
            }else{
                //entry time calculation (start)//
                if(isset($exit_time->CardTime)){
                    $entry_time=Card::with(['entrydevice'])
                        ->where([
                            'kqz_card.EmployeeID'=>$emp->EmployeeID,
                            ['kqz_card.CardTime','>',$exit_time->CardTime],
                            [DB::raw("substr(kqz_card.CardTime, 1, 10)"), '=',date('Y-m-d')]

                        ])
                        ->orderBy('kqz_card.CardTime','ASC')
                        ->groupBy(DB::raw("substr(kqz_card.CardTime, 12, 5)"))
                        ->first(['kqz_card.CardTime']);
                    if(!isset($entry_time->CardTime)){
                        $entry_time=Card::with(['entryremotedevice'])
                            ->where([
                                'kqz_card.EmployeeID'=>$emp->EmployeeID,
                                ['kqz_card.CardTime','>',$exit_time->CardTime],
                                [DB::raw("substr(kqz_card.CardTime, 1, 10)"), '=',date('Y-m-d')]
                            ])
                            ->orderBy('kqz_card.CardTime','ASC')
                            ->groupBy(DB::raw("substr(kqz_card.CardTime, 12, 5)"))
                            ->first(['kqz_card.CardTime']);
                    }
                    if(isset($entry_time->CardTime)){
                        $calculate_entry_time=substr($entry_time->CardTime,11,8);
                        $calculate_entry_date=substr($entry_time->CardTime,0,10);
                    }
                }
                //entry time calculation (end)//
            }

            //exit time calculation (start)//
            if(isset($entry_time->CardTime)){
                $exit_time=Card::with(['exitdevice'])
                    ->where([
                        'kqz_card.EmployeeID'=>$emp->EmployeeID,
                        ['kqz_card.CardTime','>',$entry_time->CardTime],
                        [DB::raw("substr(kqz_card.CardTime, 1, 10)"), '=',date('Y-m-d')]
                    ])
                    ->orderBy('kqz_card.CardTime','ASC')
                    ->groupBy(DB::raw("substr(kqz_card.CardTime, 12, 5)"))
                    ->first(['kqz_card.CardTime']);
                if(!isset($exit_time->CardTime)){
                    $exit_time=Card::with(['exitremotedevice'])
                    ->where([
                        'kqz_card.EmployeeID'=>$emp->EmployeeID,
                        ['kqz_card.CardTime','>',$entry_time->CardTime],
                        [DB::raw("substr(kqz_card.CardTime, 1, 10)"), '=',date('Y-m-d')]
                    ])
                    ->orderBy('kqz_card.CardTime','ASC')
                    ->groupBy(DB::raw("substr(kqz_card.CardTime, 12, 5)"))
                    ->first(['kqz_card.CardTime']);
                }
                if(!isset($exit_time->CardTime)){
                    $exit_time=Card::with(['exitdevice'])
                        ->where([
                            'kqz_card.EmployeeID'=>$emp->EmployeeID,
                            ['kqz_card.CardTime','>',$entry_time->CardTime],
                            [DB::raw("substr(kqz_card.CardTime, 1, 10)"), '=',date('Y-m-d',strtotime(date('Y-m-d').' +1 days'))]
                        ])
                        ->orderBy('kqz_card.CardTime','ASC')
                        ->groupBy(DB::raw("substr(kqz_card.CardTime, 12, 5)"))
                        ->first(['kqz_card.CardTime','kqz_devinfo.DevType']);
                    if(isset($exit_time->CardTime)){}else{
                        $exit_time=Card::with(['exitremotedevice'])
                        ->where([
                            'kqz_card.EmployeeID',$emp->EmployeeID,
                            ['kqz_card.CardTime','>',$entry_time->CardTime],
                            [DB::raw("substr(kqz_card.CardTime, 1, 10)"), '=',date('Y-m-d',strtotime(date('Y-m-d').' +1 days'))]
                        ])
                        ->orderBy('kqz_card.CardTime','ASC')
                        ->groupBy(DB::raw("substr(kqz_card.CardTime, 12, 5)"))
                        ->first(['kqz_card.CardTime','tbl_remotedevice.DevType']);
                    }
                    if(isset($exit_time->CardTime)){
                        if($exit_time->DevType=="1"){
                            unset($exit_time);
                        }
                    }
                }

                if(isset($exit_time->CardTime)){
                    $calculate_exit_time=substr($exit_time->CardTime,11,8);
                    $final_exit_time=substr($exit_time->CardTime,11,8);
                    $calculate_exit_date=substr($exit_time->CardTime,0,10);
                    
                    $to_time = new DateTime($calculate_exit_time);
                    $from_time = new DateTime($calculate_entry_time);
                    if(strtotime($calculate_exit_date)>strtotime($calculate_entry_date)){
                        $midnight_period=date("H:i:s",strtotime('00:00:00'));
                        $afternight_period=date("H:i:s",strtotime('00:00:00'));

                        $midnight= new DateTime('23:59:59');
                        $midnight_duration = $midnight->diff($from_time);
                        $midnight_period=$midnight_duration->format('%H:%I:%S');

                        $afternight= new DateTime('00:00:01');
                        $afternight_duration = $to_time->diff($afternight);
                        $afternight_period=$afternight_duration->format('%H:%I:%S');

                        $afternight_period=strtotime($afternight_period)-strtotime("00:00:00");
                        $period=date("H:i:s",strtotime($midnight_period)+$afternight_period);
                    }else{
                        $duration = $to_time->diff($from_time);
                        $period=$duration->format('%H:%I:%S');
                    }
                    
                    $time2 = strtotime($period)-strtotime("00:00:00");
                    $twh = date("H:i:s",strtotime($twh)+$time2);
                }else{
                    $calculate_exit_time='-';
                }
            }
            
            //exit time calculation (end)//
        }

        $total_osd = strtotime($this->totalOSDDuration($emp->emp_id,date('Y-m-d'),date('Y-m-d')))-strtotime('00:00:00');
        $twh = date("H:i:s",strtotime($twh)+$total_osd);
        $twh_for_ncot=$otController->holidayWeekendTWh($emp,date('Y-m-d'),$twh);
        

        // nc working hour calculation (start)//
        if(isset($shift_info->shift_type) && $shift_info->shift_type=="1"){
            if(substr($twh_for_ncot,0,2)>='10'){
                $to_time = new DateTime($twh_for_ncot);
                $from_time = new DateTime('10:00:00');
                $duration = $to_time->diff($from_time);
                if($duration->h>0){
                    $nc_hour=$duration->format('%H:%I:%S');
                }else{
                    if($duration->i>=30){
                        $nc_hour=$duration->format('%H:%I:%S');
                    }else{
                        $nc_hour='00:00:00';
                    }
                }
            }else{
                $nc_hour='00:00:00';
            }
        }else if(isset($shift_info->shift_type) && $shift_info->shift_type=="2"){
            if(substr($twh_for_ncot,0,2)>='11'){
                $to_time = new DateTime($twh_for_ncot);
                $from_time = new DateTime('11:00:00');
                $duration = $to_time->diff($from_time);
                if($duration->h>0){
                    $nc_hour=$duration->format('%H:%I:%S');
                }else{
                    if($duration->i>=30){
                        $nc_hour=$duration->format('%H:%I:%S');
                    }else{
                        $nc_hour='00:00:00';
                    }
                }
            }else{
                $nc_hour='00:00:00';
            }
        }else{
            $nc_hour='00:00:00';
        }

        // nc working hour calculation (end)//
        if(!isset($final_entry_time)){
            $final_entry_time=$this->attendanceShortStatus($emp,date('Y-m-d'));
        }

        if(!isset($calculate_exit_time)){
            $calculate_exit_time=$this->attendanceShortStatus($emp,date('Y-m-d'));
        }
        $data[$counter]=$this->getAttendanceRow($emp,date('Y-m-d'),$counter,$final_entry_time,$calculate_exit_time,$twh,$nc_hour,$id,'main');
    }elseif($this->checkPresent($emp->EmployeeID,date('Y-m-d'))=="0"){
        if(!isset($final_entry_time)){
            $final_entry_time=$this->attendanceShortStatus($emp,date('Y-m-d'));
        }

        if(!isset($calculate_exit_time)){
            $calculate_exit_time=$this->attendanceShortStatus($emp,date('Y-m-d'));
        }
        $data[$counter]=$this->getAttendanceRow($emp,date('Y-m-d'),$counter,$final_entry_time,$calculate_exit_time,$this->totalOSDDuration($emp->emp_id,date('Y-m-d'),date('Y-m-d')),'-',$id,'main');
    }
        
    }
    //employee loop (end)//

    return view('Admin.attendance.index',compact('id','mainlink','sublink','Adminminlink','adminsublink','joblocation','department','designation','data'));
}

static public function checkPresent($EmployeeID,$date)
{
    $checkPresent=Card::with(['entrydevice'])
        ->where([
            'kqz_card.EmployeeID'=>$EmployeeID,
            [DB::raw("substr(kqz_card.CardTime, 1, 10)"), '=',$date]
        ])
        ->get();
    if(isset($checkPresent[0])){
        return '1';
    }else{
        return '0';
    }
}

public function getAttendanceSummary($att_empid,$total_working_hour)
{
    $employee=DB::table('tbl_employee')
        ->join('kqz_employee','kqz_employee.EmployeeCode','=','tbl_employee.emp_machineid')
        ->where('emp_id',$att_empid)
        ->first(['tbl_employee.emp_name','tbl_employee.emp_empid','kqz_employee.EmployeeID']);
    echo '<table class="table table-bordered">';
    echo '<caption>Employee ID : <b>'.$employee->emp_empid.'</b> <br>Employee Name : <b>'.$employee->emp_name.'</b> <br>Date : <b>'.date('Y-m-d').'</b></caption>';
    echo '<thead><tr><th style="font-size: 12px">Date</th><th style="font-size: 12px">Punch Type</th><th style="font-size: 12px">Punch Time</th><th style="font-size: 12px">WorkStation </th><th style="font-size: 12px">Duration</th></tr></thead><tbody>';
   
    $shift_info=$this->getCurrentShiftInfo($att_empid,date('Y-m-d'));     
        //emp shift info (end)//
        
    $count=DB::table('kqz_card')
        ->join('kqz_devinfo','kqz_card.DevID','=','kqz_devinfo.DevID')
        ->where('kqz_card.EmployeeID',$employee->EmployeeID)
        ->where('kqz_card.DevID','<','7')
        ->where('kqz_devinfo.DevType','1')
        ->where(DB::raw("substr(kqz_card.CardTime, 1, 10)"), '=',date('Y-m-d'))
        ->count();
    if($count>0){}else{
        $count=DB::table('kqz_card')
        ->join('tbl_remotedevice','kqz_card.DevID','=','tbl_remotedevice.DevID')
        ->where('kqz_card.EmployeeID',$employee->EmployeeID)
        ->where('kqz_card.DevID','>','6')
        ->where('tbl_remotedevice.DevType','1')
        ->where(DB::raw("substr(kqz_card.CardTime, 1, 10)"), '=',date('Y-m-d'))
        ->count();
    }
    if($count>0){
        for ($i=0; $i < $count; $i++) {

            if($i=="0"){
                //entry time calculation (start)//
                $entry_time=DB::table('kqz_card')
                    ->join('kqz_devinfo','kqz_card.DevID','=','kqz_devinfo.DevID')
                    ->where('kqz_card.EmployeeID',$employee->EmployeeID)
                    ->where('kqz_card.DevID','<','7')
                    ->where('kqz_devinfo.DevType','1')
                    ->where(DB::raw("substr(kqz_card.CardTime, 1, 10)"), '=',date('Y-m-d'))
                    ->orderBy('kqz_card.CardTime','ASC')
                    ->groupBy(DB::raw("substr(kqz_card.CardTime, 12, 5)"))
                    ->first(['kqz_card.*','kqz_devinfo.DevName']);
                if(isset($entry_time->CardTime)){}else{
                    $entry_time=DB::table('kqz_card')
                    ->join('tbl_remotedevice','kqz_card.DevID','=','tbl_remotedevice.DevID')
                    ->where('kqz_card.EmployeeID',$employee->EmployeeID)
                    ->where('kqz_card.DevID','>','6')
                    ->where('tbl_remotedevice.DevType','1')
                    ->where(DB::raw("substr(kqz_card.CardTime, 1, 10)"), '=',date('Y-m-d'))
                    ->orderBy('kqz_card.CardTime','ASC')
                    ->groupBy(DB::raw("substr(kqz_card.CardTime, 12, 5)"))
                    ->first(['kqz_card.*','tbl_remotedevice.DevName']);
                }
                if(isset($entry_time->CardTime)){
                    $calculate_entry_time=substr($entry_time->CardTime,11,8);
                    $calculate_entry_date=substr($entry_time->CardTime,0,10);
                    $realtime_entry_time=substr($entry_time->CardTime,11,8);
                    
                    $twh=date("H:i:s",strtotime('00:00:00'));

                    echo '<tr>';
                    echo '<td style="font-size: 12px">'.substr($entry_time->CardTime, 0, 10).'</td>';
                    echo '<td style="font-size: 12px">Entry</td>';
                    echo '<td style="font-size: 12px">'.$realtime_entry_time.'</td>';
                    echo '<td style="font-size: 12px">'.$entry_time->DevName.'</td>';
                    echo '<td style="font-size: 12px"></td>';
                    echo '</tr>';
                }
                //entry time calculation (end)//
            }else{
                //entry time calculation (start)//
                if(isset($exit_time->CardTime)){
                $entry_time=DB::table('kqz_card')
                    ->join('kqz_devinfo','kqz_card.DevID','=','kqz_devinfo.DevID')
                    ->where('kqz_card.EmployeeID',$employee->EmployeeID)
                    ->where('kqz_card.DevID','<','7')
                    ->where('kqz_card.CardTime','>',$exit_time->CardTime)
                    ->where('kqz_devinfo.DevType','1')
                    ->where(DB::raw("substr(kqz_card.CardTime, 1, 10)"), '=',date('Y-m-d'))
                    ->orderBy('kqz_card.CardTime','ASC')
                    ->groupBy(DB::raw("substr(kqz_card.CardTime, 12, 5)"))
                    ->first(['kqz_card.*']);
                if(isset($entry_time->CardTime)){}else{
                    $entry_time=DB::table('kqz_card')
                    ->join('tbl_remotedevice','kqz_card.DevID','=','tbl_remotedevice.DevID')
                    ->where('kqz_card.EmployeeID',$employee->EmployeeID)
                    ->where('kqz_card.DevID','>','6')
                    ->where('kqz_card.CardTime','>',$exit_time->CardTime)
                    ->where('tbl_remotedevice.DevType','1')
                    ->where(DB::raw("substr(kqz_card.CardTime, 1, 10)"), '=',date('Y-m-d'))
                    ->orderBy('kqz_card.CardTime','ASC')
                    ->groupBy(DB::raw("substr(kqz_card.CardTime, 12, 5)"))
                    ->first(['kqz_card.*']);
                }
                    if(isset($entry_time->CardTime)){
                        $calculate_entry_time=substr($entry_time->CardTime,11,8);
                        $calculate_entry_date=substr($entry_time->CardTime,0,10);
                        $realtime_entry_time=substr($entry_time->CardTime,11,8);

                        echo '<tr>';
                        echo '<td style="font-size: 12px">'.substr($entry_time->CardTime, 0, 10).'</td>';
                        echo '<td style="font-size: 12px">Entry</td>';
                        echo '<td style="font-size: 12px">'.$realtime_entry_time.'</td>';
                        echo '<td style="font-size: 12px">'.$entry_time->DevName.'</td>';
                        echo '<td style="font-size: 12px"></td>';
                        echo '</tr>';
                    }
                }
                
                //entry time calculation (end)//
            }

            //exit time calculation (start)//
            if(isset($entry_time->CardTime)){
            $exit_time=DB::table('kqz_card')
                ->join('kqz_devinfo','kqz_card.DevID','=','kqz_devinfo.DevID')
                ->where('kqz_card.EmployeeID',$employee->EmployeeID)
                ->where('kqz_card.DevID','<','7')
                ->where('kqz_card.CardTime','>',$entry_time->CardTime)
                ->where('kqz_devinfo.DevType','0')
                ->where(DB::raw("substr(kqz_card.CardTime, 1, 10)"), '=',date('Y-m-d'))
                ->orderBy('kqz_card.CardTime','ASC')
                ->groupBy(DB::raw("substr(kqz_card.CardTime, 12, 5)"))
                ->first(['kqz_card.*','kqz_devinfo.*']);
            if(isset($exit_time->CardTime)){}else{
                $exit_time=DB::table('kqz_card')
                ->join('tbl_remotedevice','kqz_card.DevID','=','tbl_remotedevice.DevID')
                ->where('kqz_card.EmployeeID',$employee->EmployeeID)
                ->where('kqz_card.DevID','>','6')
                ->where('kqz_card.CardTime','>',$entry_time->CardTime)
                ->where('tbl_remotedevice.DevType','0')
                ->where(DB::raw("substr(kqz_card.CardTime, 1, 10)"), '=',date('Y-m-d'))
                ->orderBy('kqz_card.CardTime','ASC')
                ->groupBy(DB::raw("substr(kqz_card.CardTime, 12, 5)"))
                ->first(['kqz_card.*','tbl_remotedevice.*']);
            }
            if(isset($exit_time->CardTime)){}else{
                $exit_time=DB::table('kqz_card')
                    ->join('kqz_devinfo','kqz_card.DevID','=','kqz_devinfo.DevID')
                    ->where('kqz_card.EmployeeID',$employee->EmployeeID)
                    ->where('kqz_card.DevID','<','7')
                    ->where('kqz_card.CardTime','>',$entry_time->CardTime)
                    ->where(DB::raw("substr(kqz_card.CardTime, 1, 10)"), '=',date('Y-m-d',strtotime(date('Y-m-d').' +1 days')))
                    ->orderBy('kqz_card.CardTime','ASC')
                    ->groupBy(DB::raw("substr(kqz_card.CardTime, 12, 5)"))
                    ->first(['kqz_card.*','kqz_devinfo.*']);
                if(isset($exit_time->CardTime)){}else{
                    $exit_time=DB::table('kqz_card')
                    ->join('tbl_remotedevice','kqz_card.DevID','=','tbl_remotedevice.DevID')
                    ->where('kqz_card.EmployeeID',$employee->EmployeeID)
                    ->where('kqz_card.DevID','>','6')
                    ->where('kqz_card.CardTime','>',$entry_time->CardTime)
                    ->where(DB::raw("substr(kqz_card.CardTime, 1, 10)"), '=',date('Y-m-d',strtotime(date('Y-m-d').' +1 days')))
                    ->orderBy('kqz_card.CardTime','ASC')
                    ->groupBy(DB::raw("substr(kqz_card.CardTime, 12, 5)"))
                    ->first(['kqz_card.*','tbl_remotedevice.*']);
                }
                if(isset($exit_time->CardTime)){
                    if($exit_time->DevType=="1"){
                        unset($exit_time);
                    }
                }
            }
                if(isset($exit_time->CardTime)){
                    $calculate_exit_time=substr($exit_time->CardTime,11,8);
                    $calculate_exit_date=substr($exit_time->CardTime,0,10);

                    $to_time = new DateTime($calculate_exit_time);
                    $from_time = new DateTime($calculate_entry_time);

                    if(strtotime($calculate_exit_date)>strtotime($calculate_entry_date)){
                        $midnight_period=date("H:i:s",strtotime('00:00:00'));
                        $afternight_period=date("H:i:s",strtotime('00:00:00'));

                        $midnight= new DateTime('23:59:59');
                        $midnight_duration = $midnight->diff($from_time);
                        $midnight_period=$midnight_duration->format('%H:%I:%S');

                        $afternight= new DateTime('00:00:01');
                        $afternight_duration = $to_time->diff($afternight);
                        $afternight_period=$afternight_duration->format('%H:%I:%S');

                        $afternight_period=strtotime($afternight_period)-strtotime("00:00:00");
                        $period=date("H:i:s",strtotime($midnight_period)+$afternight_period);
                    }else{
                        $duration = $to_time->diff($from_time);
                        $period=$duration->format('%H:%I:%S');
                    }
                    
                    $time2 = strtotime($period)-strtotime("00:00:00");
                    $twh = date("H:i:s",strtotime($twh)+$time2);

                    echo '<tr>';
                    echo '<td style="font-size: 12px">'.substr($exit_time->CardTime, 0, 10).'</td>';
                    echo '<td style="font-size: 12px">Exit</td>';
                    echo '<td style="font-size: 12px">'.$calculate_exit_time.'</td>';
                    echo '<td style="font-size: 12px">'.$exit_time->DevName.'</td>';
                    echo '<td style="font-size: 12px">'.$twh.'</td>';
                    echo '</tr>';
                }else{
                    $calculate_exit_time='-';
                }
            }
            
            //exit time calculation (end)//
        }
    
    }

    echo '<tr><td colspan="4" style="text-align: right;">Total Working Hour :</td><td>'.$total_working_hour.'</td></tr></tbody>';
    echo '</table>';
}

public function getEmployee($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type)
{
    $data='<option value="0">Select</option>';

    $employee=DB::table('tbl_employee')
        ->join('kqz_employee','tbl_employee.emp_machineid','=','kqz_employee.EmployeeCode')
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
        ->groupBy('tbl_employee.emp_id')
        ->get(['kqz_employee.EmployeeID','tbl_employee.emp_name']);
    if(isset($employee[0])){
        foreach ($employee as $emp) {
            $data.='<option value="'.$emp->EmployeeID.'">'.$emp->emp_name.'</option>';
        }
    }
    return $data;
}

public function getRemoteEmployee($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type)
{
    $data='<option value="0">Select</option>';

    $employee=DB::table('tbl_employee')
        ->join('kqz_employee','tbl_employee.emp_machineid','=','kqz_employee.EmployeeCode')
        ->join('tbl_sysuser','tbl_employee.emp_id','=','tbl_sysuser.suser_empid')
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
        ->where('tbl_sysuser.suser_emptype','0')
        ->groupBy('tbl_employee.emp_id')
        ->get(['tbl_employee.*']);
    if(isset($employee[0])){
        foreach ($employee as $emp) {
            $data.='<option value="'.$emp->emp_id.'">'.$emp->emp_name.'</option>';
        }
    }
    return $data;
}

public function attendanceSearch($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type,$emp_id,$start_date,$end_date)
{
    $id =   Auth::guard('admin')->user();
    $mainlink = $this->adminmainmenu();
    $sublink = $this->adminsubmenu();
    $Adminminlink = $this->adminlink();
    $adminsublink = $this->adminsublink();
    $adminInfo = $this->adminInfo($id->suser_empid);
    $designation=Designation::get();
    $department=Department::get();
    $joblocation=JobLocation::get();

    //todays all employee add (start)//
    $employee=$this->filterAttendanceEmployee($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type,$emp_id);

    $data = array();   
    $counter=0;

    foreach ($employee as $emp) {

    $dateRange=$this->getDateRange($start_date,$end_date);
    //employee loop (start)//
    for($count=0;$count<count($dateRange);$count++) {
    unset($final_entry_time);
    unset($calculate_exit_time);
    $counter++;

    //emp shift info (start)//
    $shift_info=$this->getCurrentShiftInfo($emp->emp_id,$dateRange[$count]);      
    //emp shift info (end)//
    if($this->checkPresent($emp->EmployeeID,$dateRange[$count])=="1"){
        
        $card_count=$this->entryCount($emp->EmployeeID,$dateRange[$count]);
        $twh=date("H:i:s",strtotime('00:00:00'));

        for ($i=0; $i < $count; $i++) {

            if($i=="0"){
                //entry time calculation (start)//
                $entry_time=Card::with(['entrydevice'])
                    ->where([
                        'kqz_card.EmployeeID'=>$emp->EmployeeID,
                        [DB::raw("substr(kqz_card.CardTime, 1, 10)"), '=',$dateRange[$count]],
                    ])
                    ->orderBy('kqz_card.CardTime','ASC')
                    ->groupBy(DB::raw("substr(kqz_card.CardTime, 12, 5)"))
                    ->first(['kqz_card.CardTime']);
                if(!isset($entry_time->CardTime)){
                    $entry_time=Card::with(['entryremotedevice'])
                    ->where([
                        'kqz_card.EmployeeID'=>$emp->EmployeeID,
                        [DB::raw("substr(kqz_card.CardTime, 1, 10)"), '=',$dateRange[$count]]
                    ])
                    ->orderBy('kqz_card.CardTime','ASC')
                    ->groupBy(DB::raw("substr(kqz_card.CardTime, 12, 5)"))
                    ->first(['kqz_card.CardTime']);
                }
                if(isset($entry_time->CardTime)){
                    $final_entry_time=substr($entry_time->CardTime,11,8);
                    $calculate_entry_time=substr($entry_time->CardTime,11,8);
                    $calculate_entry_date=substr($entry_time->CardTime,0,10);
                    
                    $twh=date("H:i:s",strtotime('00:00:00'));
                }
                //entry time calculation (end)//
            }else{
                //entry time calculation (start)//
                if(isset($exit_time->CardTime)){
                    $entry_time=Card::with(['entrydevice'])
                        ->where([
                            'kqz_card.EmployeeID'=>$emp->EmployeeID,
                            ['kqz_card.CardTime','>',$exit_time->CardTime],
                            [DB::raw("substr(kqz_card.CardTime, 1, 10)"), '=',$dateRange[$count]]

                        ])
                        ->orderBy('kqz_card.CardTime','ASC')
                        ->groupBy(DB::raw("substr(kqz_card.CardTime, 12, 5)"))
                        ->first(['kqz_card.CardTime']);
                    if(!isset($entry_time->CardTime)){
                        $entry_time=Card::with(['entryremotedevice'])
                            ->where([
                                'kqz_card.EmployeeID'=>$emp->EmployeeID,
                                ['kqz_card.CardTime','>',$exit_time->CardTime],
                                [DB::raw("substr(kqz_card.CardTime, 1, 10)"), '=',$dateRange[$count]]
                            ])
                            ->orderBy('kqz_card.CardTime','ASC')
                            ->groupBy(DB::raw("substr(kqz_card.CardTime, 12, 5)"))
                            ->first(['kqz_card.CardTime']);
                    }
                    if(isset($entry_time->CardTime)){
                        $calculate_entry_time=substr($entry_time->CardTime,11,8);
                        $calculate_entry_date=substr($entry_time->CardTime,0,10);
                    }
                }
                //entry time calculation (end)//
            }

            //exit time calculation (start)//
            if(isset($entry_time->CardTime)){
                $exit_time=Card::with(['exitdevice'])
                    ->where([
                        'kqz_card.EmployeeID'=>$emp->EmployeeID,
                        ['kqz_card.CardTime','>',$entry_time->CardTime],
                        [DB::raw("substr(kqz_card.CardTime, 1, 10)"), '=',$dateRange[$count]]
                    ])
                    ->orderBy('kqz_card.CardTime','ASC')
                    ->groupBy(DB::raw("substr(kqz_card.CardTime, 12, 5)"))
                    ->first(['kqz_card.CardTime']);
                if(!isset($exit_time->CardTime)){
                    $exit_time=Card::with(['exitremotedevice'])
                    ->where([
                        'kqz_card.EmployeeID'=>$emp->EmployeeID,
                        ['kqz_card.CardTime','>',$entry_time->CardTime],
                        [DB::raw("substr(kqz_card.CardTime, 1, 10)"), '=',$dateRange[$count]]
                    ])
                    ->orderBy('kqz_card.CardTime','ASC')
                    ->groupBy(DB::raw("substr(kqz_card.CardTime, 12, 5)"))
                    ->first(['kqz_card.CardTime']);
                }
                if(!isset($exit_time->CardTime)){
                    $exit_time=Card::with(['exitdevice'])
                        ->where([
                            'kqz_card.EmployeeID'=>$emp->EmployeeID,
                            ['kqz_card.CardTime','>',$entry_time->CardTime],
                            [DB::raw("substr(kqz_card.CardTime, 1, 10)"), '=',date('Y-m-d',strtotime($dateRange[$count].' +1 days'))]
                        ])
                        ->orderBy('kqz_card.CardTime','ASC')
                        ->groupBy(DB::raw("substr(kqz_card.CardTime, 12, 5)"))
                        ->first(['kqz_card.CardTime']);
                    if(isset($exit_time->CardTime)){}else{
                        $exit_time=Card::with(['exitremotedevice'])
                        ->where([
                            'kqz_card.EmployeeID'=>$emp->EmployeeID,
                            ['kqz_card.CardTime','>'=>$entry_time->CardTime],
                            [DB::raw("substr(kqz_card.CardTime, 1, 10)"), '=',date('Y-m-d',strtotime($dateRange[$count].' +1 days'))]
                        ])
                        ->orderBy('kqz_card.CardTime','ASC')
                        ->groupBy(DB::raw("substr(kqz_card.CardTime, 12, 5)"))
                        ->first(['kqz_card.CardTime']);
                    }
                    if(isset($exit_time->CardTime)){
                        if(isset($exit_time->exitdevice->DevType) && $exit_time->exitdevice->DevType=="1"){
                            unset($exit_time);
                        }elseif(isset($exit_time->exitremotedevice->DevType) && $exit_time->exitremotedevice->DevType=="1"){
                        	unset($exit_time);
                        }
                    }
                }

                if(isset($exit_time->CardTime)){
                    $calculate_exit_time=substr($exit_time->CardTime,11,8);
                    $final_exit_time=substr($exit_time->CardTime,11,8);
                    $calculate_exit_date=substr($exit_time->CardTime,0,10);
                    
                    $to_time = new DateTime($calculate_exit_time);
                    $from_time = new DateTime($calculate_entry_time);
                    if(strtotime($calculate_exit_date)>strtotime($calculate_entry_date)){
                        $midnight_period=date("H:i:s",strtotime('00:00:00'));
                        $afternight_period=date("H:i:s",strtotime('00:00:00'));

                        $midnight= new DateTime('23:59:59');
                        $midnight_duration = $midnight->diff($from_time);
                        $midnight_period=$midnight_duration->format('%H:%I:%S');

                        $afternight= new DateTime('00:00:01');
                        $afternight_duration = $to_time->diff($afternight);
                        $afternight_period=$afternight_duration->format('%H:%I:%S');

                        $afternight_period=strtotime($afternight_period)-strtotime("00:00:00");
                        $period=date("H:i:s",strtotime($midnight_period)+$afternight_period);
                    }else{
                        $duration = $to_time->diff($from_time);
                        $period=$duration->format('%H:%I:%S');
                    }
                    
                    $time2 = strtotime($period)-strtotime("00:00:00");
                    $twh = date("H:i:s",strtotime($twh)+$time2);
                }else{
                    $calculate_exit_time='-';
                }
            }
            
            //exit time calculation (end)//
        }

        $total_osd = strtotime($this->totalOSDDuration($emp->emp_id,$dateRange[$count],$dateRange[$count]))-strtotime('00:00:00');
        $twh = date("H:i:s",strtotime($twh)+$total_osd);
        

        // nc working hour calculation (start)//
        if(isset($shift_info->shift_type) && $shift_info->shift_type=="1"){
            if(substr($twh,0,2)>='10'){
                $to_time = new DateTime($twh);
                $from_time = new DateTime('10:00:00');
                $duration = $to_time->diff($from_time);
                if($duration->h>0){
                    $nc_hour=$duration->format('%H:%I:%S');
                }else{
                    if($duration->i>=30){
                        $nc_hour=$duration->format('%H:%I:%S');
                    }else{
                        $nc_hour='00:00:00';
                    }
                }
            }else{
                $nc_hour='00:00:00';
            }
        }else if(isset($shift_info->shift_type) && $shift_info->shift_type=="2"){
            if(substr($twh,0,2)>='11'){
                $to_time = new DateTime($twh);
                $from_time = new DateTime('11:00:00');
                $duration = $to_time->diff($from_time);
                if($duration->h>0){
                    $nc_hour=$duration->format('%H:%I:%S');
                }else{
                    if($duration->i>=30){
                        $nc_hour=$duration->format('%H:%I:%S');
                    }else{
                        $nc_hour='00:00:00';
                    }
                }
            }else{
                $nc_hour='00:00:00';
            }
        }else{
            $nc_hour='00:00:00';
        }
        // nc working hour calculation (end)//
        if(!isset($final_entry_time)){
            $final_entry_time=$this->attendanceShortStatus($emp,$dateRange[$count]);
        }

        if(!isset($calculate_exit_time)){
            $calculate_exit_time=$this->attendanceShortStatus($emp,$dateRange[$count]);
        }
        $data[$counter]=$this->getAttendanceRow($emp,$dateRange[$count],$counter,$final_entry_time,$calculate_exit_time,$twh,$nc_hour,$id,'search');
    }elseif($this->checkPresent($emp->EmployeeID,$dateRange[$count])=="0"){
        if(!isset($final_entry_time)){
            $final_entry_time=$this->attendanceShortStatus($emp,$dateRange[$count]);
        }

        if(!isset($calculate_exit_time)){
            $calculate_exit_time=$this->attendanceShortStatus($emp,$dateRange[$count]);
        }
        $data[$counter]=$this->getAttendanceRow($emp,$dateRange[$count],$counter,$final_entry_time,$calculate_exit_time,$this->totalOSDDuration($emp->emp_id,$dateRange[$count],$dateRange[$count]),'-',$id,'search');
    }

    }

    }
    //employee loop (end)//

    return view('Admin.attendance.index',compact('id','mainlink','sublink','Adminminlink','adminsublink','joblocation','department','designation','emp_depart_id','emp_sdepart_id','emp_desig_id','emp_jlid','emp_type','start_date','end_date','data'));
}

public function getAttendanceSummarySearch($att_empid,$total_working_hour,$date)
{
    $employee=DB::table('tbl_employee')
        ->join('kqz_employee','kqz_employee.EmployeeCode','=','tbl_employee.emp_machineid')
        ->where('emp_id',$att_empid)
        ->first(['tbl_employee.emp_name','tbl_employee.emp_empid','kqz_employee.EmployeeID']);
    echo '<table class="table table-bordered">';
    echo '<caption>Employee ID : <b>'.$employee->emp_empid.'</b> <br>Employee Name : <b>'.$employee->emp_name.'</b><br>Date : <b>'.$date.'</b></caption>';
    echo '<thead><tr><th style="font-size: 12px">Date</th><th style="font-size: 12px">Punch Type</th><th style="font-size: 12px">Punch Time</th><th style="font-size: 12px">WorkStation </th><th style="font-size: 12px">Duration</th></tr></thead><tbody>';
    
    $shift_info=$this->getCurrentShiftInfo($att_empid,$date);      
        
    $count=DB::table('kqz_card')
        ->join('kqz_devinfo','kqz_card.DevID','=','kqz_devinfo.DevID')
        ->where('kqz_card.EmployeeID',$employee->EmployeeID)
        ->where('kqz_card.DevID','<','7')
        ->where('kqz_devinfo.DevType','1')
        ->where(DB::raw("substr(kqz_card.CardTime, 1, 10)"), '=',$date)
        ->count();
    if($count>0){}else{
        $count=DB::table('kqz_card')
        ->join('tbl_remotedevice','kqz_card.DevID','=','tbl_remotedevice.DevID')
        ->where('kqz_card.EmployeeID',$employee->EmployeeID)
        ->where('kqz_card.DevID','>','6')
        ->where('tbl_remotedevice.DevType','1')
        ->where(DB::raw("substr(kqz_card.CardTime, 1, 10)"), '=',$date)
        ->count();
    }
    if($count>0){
        for ($i=0; $i < $count; $i++) {

            if($i=="0"){
                //entry time calculation (start)//
                $entry_time=DB::table('kqz_card')
                    ->join('kqz_devinfo','kqz_card.DevID','=','kqz_devinfo.DevID')
                    ->where('kqz_card.EmployeeID',$employee->EmployeeID)
                    ->where('kqz_card.DevID','<','7')
                    ->where('kqz_devinfo.DevType','1')
                    ->where(DB::raw("substr(kqz_card.CardTime, 1, 10)"), '=',$date)
                    ->orderBy('kqz_card.CardTime','ASC')
                    ->groupBy(DB::raw("substr(kqz_card.CardTime, 12, 5)"))
                    ->first(['kqz_card.*','kqz_devinfo.DevName']);
                if(isset($entry_time->CardTime)){}else{
                    $entry_time=DB::table('kqz_card')
                    ->join('tbl_remotedevice','kqz_card.DevID','=','tbl_remotedevice.DevID')
                    ->where('kqz_card.EmployeeID',$employee->EmployeeID)
                    ->where('kqz_card.DevID','>','6')
                    ->where('tbl_remotedevice.DevType','1')
                    ->where(DB::raw("substr(kqz_card.CardTime, 1, 10)"), '=',$date)
                    ->orderBy('kqz_card.CardTime','ASC')
                    ->groupBy(DB::raw("substr(kqz_card.CardTime, 12, 5)"))
                    ->first(['kqz_card.*','tbl_remotedevice.DevName']);
                }
                if(isset($entry_time->CardTime)){
                    $calculate_entry_time=substr($entry_time->CardTime,11,8);
                    $calculate_entry_date=substr($entry_time->CardTime,0,10);
                    $realtime_entry_time=substr($entry_time->CardTime,11,8);

                    $twh=date("H:i:s",strtotime('00:00:00'));

                    echo '<tr>';
                    echo '<td style="font-size: 12px">'.substr($entry_time->CardTime, 0, 10).'</td>';
                    echo '<td style="font-size: 12px">Entry</td>';
                    echo '<td style="font-size: 12px">'.$realtime_entry_time.'</td>';
                    echo '<td style="font-size: 12px">'.$entry_time->DevName.'</td>';
                    echo '<td style="font-size: 12px"></td>';
                    echo '</tr>';
                }
                //entry time calculation (end)//
            }else{
                //entry time calculation (start)//
                if(isset($exit_time->CardTime)){
                $entry_time=DB::table('kqz_card')
                    ->join('kqz_devinfo','kqz_card.DevID','=','kqz_devinfo.DevID')
                    ->where('kqz_card.EmployeeID',$employee->EmployeeID)
                    ->where('kqz_card.DevID','<','7')
                    ->where('kqz_card.CardTime','>',$exit_time->CardTime)
                    ->where('kqz_devinfo.DevType','1')
                    ->where(DB::raw("substr(kqz_card.CardTime, 1, 10)"), '=',$date)
                    ->orderBy('kqz_card.CardTime','ASC')
                    ->groupBy(DB::raw("substr(kqz_card.CardTime, 12, 5)"))
                    ->first(['kqz_card.*']);
                if(isset($entry_time->CardTime)){}else{
                    $entry_time=DB::table('kqz_card')
                        ->join('tbl_remotedevice','kqz_card.DevID','=','tbl_remotedevice.DevID')
                        ->where('kqz_card.EmployeeID',$employee->EmployeeID)
                        ->where('kqz_card.DevID','>','6')
                        ->where('kqz_card.CardTime','>',$exit_time->CardTime)
                        ->where('tbl_remotedevice.DevType','1')
                        ->where(DB::raw("substr(kqz_card.CardTime, 1, 10)"), '=',$date)
                        ->orderBy('kqz_card.CardTime','ASC')
                        ->groupBy(DB::raw("substr(kqz_card.CardTime, 12, 5)"))
                        ->first(['kqz_card.*']);
                }
                    if(isset($entry_time->CardTime)){
                        $calculate_entry_time=substr($entry_time->CardTime,11,8);
                        $calculate_entry_date=substr($entry_time->CardTime,0,10);
                        $realtime_entry_time=substr($entry_time->CardTime,11,8);

                        echo '<tr>';
                        echo '<td style="font-size: 12px">'.substr($entry_time->CardTime, 0, 10).'</td>';
                        echo '<td style="font-size: 12px">Entry</td>';
                        echo '<td style="font-size: 12px">'.$realtime_entry_time.'</td>';
                        echo '<td style="font-size: 12px">'.$entry_time->DevName.'</td>';
                        echo '<td style="font-size: 12px"></td>';
                        echo '</tr>';
                    }
                }
                
                //entry time calculation (end)//
            }

            //exit time calculation (start)//
            if(isset($entry_time->CardTime)){
            $exit_time=DB::table('kqz_card')
                ->join('kqz_devinfo','kqz_card.DevID','=','kqz_devinfo.DevID')
                ->where('kqz_card.EmployeeID',$employee->EmployeeID)
                ->where('kqz_card.DevID','<','7')
                ->where('kqz_card.CardTime','>',$entry_time->CardTime)
                ->where('kqz_devinfo.DevType','0')
                ->where(DB::raw("substr(kqz_card.CardTime, 1, 10)"), '=',substr($entry_time->CardTime, 0, 10))
                ->orderBy('kqz_card.CardTime','ASC')
                ->groupBy(DB::raw("substr(kqz_card.CardTime, 12, 5)"))
                ->first(['kqz_card.*','kqz_devinfo.*']);
            if(isset($exit_time->CardTime)){}else{
                $exit_time=DB::table('kqz_card')
                ->join('tbl_remotedevice','kqz_card.DevID','=','tbl_remotedevice.DevID')
                ->where('kqz_card.EmployeeID',$employee->EmployeeID)
                ->where('kqz_card.DevID','>','6')
                ->where('kqz_card.CardTime','>',$entry_time->CardTime)
                ->where('tbl_remotedevice.DevType','0')
                ->where(DB::raw("substr(kqz_card.CardTime, 1, 10)"), '=',substr($entry_time->CardTime, 0, 10))
                ->orderBy('kqz_card.CardTime','ASC')
                ->groupBy(DB::raw("substr(kqz_card.CardTime, 12, 5)"))
                ->first(['kqz_card.*','tbl_remotedevice.*']);
            }
            if(isset($exit_time->CardTime)){}else{
                $exit_time=DB::table('kqz_card')
                    ->join('kqz_devinfo','kqz_card.DevID','=','kqz_devinfo.DevID')
                    ->where('kqz_card.EmployeeID',$employee->EmployeeID)
                    ->where('kqz_card.DevID','<','7')
                    ->where('kqz_card.CardTime','>',$entry_time->CardTime)
                    ->where(DB::raw("substr(kqz_card.CardTime, 1, 10)"), '=',date('Y-m-d',strtotime(substr($entry_time->CardTime, 0, 10).' +1 days')))
                    ->orderBy('kqz_card.CardTime','ASC')
                    ->groupBy(DB::raw("substr(kqz_card.CardTime, 12, 5)"))
                    ->first(['kqz_card.*','kqz_devinfo.*']);
                if(isset($exit_time->CardTime)){}else{
                    $exit_time=DB::table('kqz_card')
                    ->join('tbl_remotedevice','kqz_card.DevID','=','tbl_remotedevice.DevID')
                    ->where('kqz_card.EmployeeID',$employee->EmployeeID)
                    ->where('kqz_card.DevID','>','6')
                    ->where('kqz_card.CardTime','>',$entry_time->CardTime)
                    ->orderBy('kqz_card.CardTime','ASC')
                    ->groupBy(DB::raw("substr(kqz_card.CardTime, 12, 5)"))
                    ->where(DB::raw("substr(kqz_card.CardTime, 1, 10)"), '=',date('Y-m-d',strtotime(substr($entry_time->CardTime, 0, 10).' +1 days')))
                    ->first(['kqz_card.*','tbl_remotedevice.*']);
                }
                if(isset($exit_time->CardTime)){
                    if($exit_time->DevType=="1"){
                        unset($exit_time);
                    }
                }
            }
                if(isset($exit_time->CardTime)){
                    $calculate_exit_time=substr($exit_time->CardTime,11,8);
                    $calculate_exit_date=substr($exit_time->CardTime,0,10);

                    $to_time = new DateTime($calculate_exit_time);
                    $from_time = new DateTime($calculate_entry_time);
                    if(strtotime($calculate_exit_date)>strtotime($calculate_entry_date)){
                        $midnight_period=date("H:i:s",strtotime('00:00:00'));
                        $afternight_period=date("H:i:s",strtotime('00:00:00'));

                        $midnight= new DateTime('23:59:59');
                        $midnight_duration = $midnight->diff($from_time);
                        $midnight_period=$midnight_duration->format('%H:%I:%S');

                        $afternight= new DateTime('00:00:01');
                        $afternight_duration = $to_time->diff($afternight);
                        $afternight_period=$afternight_duration->format('%H:%I:%S');

                        $afternight_period=strtotime($afternight_period)-strtotime("00:00:00");
                        $period=date("H:i:s",strtotime($midnight_period)+$afternight_period);
                    }else{
                        $duration = $to_time->diff($from_time);
                        $period=$duration->format('%H:%I:%S');
                    }
                    
                    $time2 = strtotime($period)-strtotime("00:00:00");
                    $twh = date("H:i:s",strtotime($twh)+$time2);

                    echo '<tr>';
                    echo '<td style="font-size: 12px">'.substr($exit_time->CardTime, 0, 10).'</td>';
                    echo '<td style="font-size: 12px">Exit</td>';
                    echo '<td style="font-size: 12px">'.$calculate_exit_time.'</td>';
                    echo '<td style="font-size: 12px">'.$exit_time->DevName.'</td>';
                    echo '<td style="font-size: 12px">'.$twh.'</td>';
                    echo '</tr>';
                }else{
                    $calculate_exit_time='-';
                }
            }
            
            //exit time calculation (end)//
        }
    
    }

    echo '<tr><td colspan="4" style="text-align: right;">Total Working Hour :</td><td>'.$total_working_hour.'</td></tr></tbody>';
    echo '</table>';
}

public function attendanceAdd()
{
    $id =   Auth::guard('admin')->user();
    $mainlink = $this->adminmainmenu();
    $sublink = $this->adminsubmenu();
    $Adminminlink = $this->adminlink();
    $adminsublink = $this->adminsublink();
    $designation=DB::table('tbl_designation')->get();
    $department=DB::table('tbl_department')->get();
    $joblocation=DB::table('tbl_joblocation')->get();
    $kqz_devinfo=DB::table('kqz_devinfo')->get();

    return view('Admin.attendance.create',compact('id','mainlink','sublink','Adminminlink','adminsublink','designation','department','joblocation','kqz_devinfo'));
}

public function attendanceAddSubmit(Request $request)
{
    if($request->emp_id>0){
        if($request->DevID>0){
            if($request->CardTime_Date!="" && $request->CardTime_Time!=""){
                $search=DB::table('kqz_card')
                            ->where([
                            'EmployeeID'=>$request->emp_id,
                            'CardTime'=>$request->CardTime_Date.' '.$request->CardTime_Time.':00',
                            'CardTypeID'=>'0',
                            'DevID'=>$request->DevID
                        ])
                        ->first();
                        if(!isset($search->CardTime)){
                            $card=DB::table('kqz_card')->insert([
                                    'EmployeeID'=>$request->emp_id,
                                    'CardTime'=>$request->CardTime_Date.' '.$request->CardTime_Time.':00',
                                    'CardTypeID'=>'0',
                                    'DevID'=>$request->DevID,
                                    'FaceIDNo'=>' ',
                                ]);
                            if(isset($card)){
                                $this->empHistory($this->getEmployeeIDtoemp_id($request->emp_id),'Attendace Added As Punch Device : <b>'.$this->getDeviceName($request->DevID).'</b> , DateTime : <b>'.$request->CardTime_Date.' '.$request->CardTime_Time.':00'.'</b>');
                                Session::flash('success','Attendace Saved Successfully.');
                            }else{
                                Session::flash('error','Whoops! Something Went Wrong!.');
                            }
                        }else{
                            Session::flash('error','Already Exist!');
                        }
            }else{
                Session::flash('error','Whoops! Plaese Enter Attendance date & Time!.');
            }
        }else{
            Session::flash('error','Whoops! Choose A Device!.');
        }
    }else{
        Session::flash('error','Whoops! Choose An Employee!.');
    }

    return redirect('attendance/add');
}

public function attendanceCheckEdit(Request $request)
{
    if(count($request->att_id)>0){
        if(count($request->att_id)>1){
            return 'max';
        }else{
            return $request->att_id[0];
        }
    }else{
        return 'null';
    }
}

public function attendanceEdit($data){
    $id =   Auth::guard('admin')->user();
    $mainlink = $this->adminmainmenu();
    $sublink = $this->adminsubmenu();
    $Adminminlink = $this->adminlink();
    $adminsublink = $this->adminsublink();
    $joblocation=DB::table('kqz_devinfo')->get();
    $emp_id=explode('&',$data)[0];
    $date=explode('&',$data)[1];
    $employee=DB::table('tbl_employee')->where('emp_id',$emp_id)->first();
    $attendance=DB::table('kqz_card')
        ->join('kqz_employee','kqz_card.EmployeeID','=','kqz_employee.EmployeeID')
        ->join('tbl_employee','kqz_employee.EmployeeCode','=','tbl_employee.emp_machineid')
        ->where('tbl_employee.emp_id',$emp_id)
        ->where(DB::raw("substr(kqz_card.CardTime, 1, 10)"), '=',$date)
        ->groupBy(DB::raw("substr(kqz_card.CardTime, 12, 5)"))
        ->orderBy('kqz_card.CardTime','asc')
        ->get();
    return view('Admin.attendance.edit',compact('id','mainlink','sublink','Adminminlink','adminsublink','joblocation','employee','attendance','date'));
}

public function attendanceUpdate($CardID,$CardTime,$DevID)
{
    if(explode(':',$CardTime)[0]<=9){
        $CardTime='0'.$CardTime;
    }
    $card=DB::table('kqz_card')->where('CardID',$CardID)->first();
    $CardTime=substr($card->CardTime,0,11).$CardTime.':00';
    $update=DB::update("update `kqz_card` set `CardTime` = '".$CardTime."', `DevID` = '".$DevID."' where `kqz_card`.`CardID` = '".$CardID."'");
    if(isset($update)){
        $this->empHistory($this->getEmployeeIDtoemp_id($card->EmployeeID),'Attendace Update Punch Device From  :<b>'.$this->getDeviceName($card->DevID).'</b> to <b>'.$this->getDeviceName($DevID).'</b>, DateTime From : <b>'.$card->CardTime.'</b> to <b>'.$CardTime.'</b>');
    }
    return $update;
}

public function attendanceSingleDelete($CardID)
{
    $delete=DB::table('kqz_card')->where('CardID',$CardID)->delete();
    if($delete){
        return '1';
    }else{
        return '0';
    }
}

public function attendanceDelete(Request $request)
{
    if(count($request->att_id)>0){
        for ($i=0; $i < count($request->att_id) ; $i++) { 
            $delete_attendance=DB::table('tbl_attendance')->where('att_id',$request->att_id[$i])->delete();
        }
        if($delete_attendance){
            Session::flash('success','Attendace(s) Deleted Successfully.');
            return '1';
        }else{
            return '0';
        }
    }else{
        return 'null';
    }
}

public function ClockIn()
{
    $id =   Auth::guard('admin')->user();
    if($this->checkClock()=="0" or $this->checkClock()=="null"){
        $ClockIn=DB::table('tbl_clock')
            ->insert([
                'clock_empid'=>$id->suser_empid,
                'clock_time'=>date('Y-m-d H:i:s'),
                'clock_device'=>'7',
            ]);
        if($ClockIn){
            $this->empHistory($id->suser_empid,'Clock In Requested As Device : <b>'.$this->getRemoteDeviceName('7').'</b>, DateTime : <b>'.date('Y-m-d H:i:s').'</b>');
            return 'success///Clocked In!!///You Are Clocked In!!';
        }else{
            return 'error///Whoops!!///Something Went Wrong!!';
        }
    }else{
        return 'error///Whoops!!///You Are already Clocked In!!';
    }
}

public function ClockOut()
{
    $id =   Auth::guard('admin')->user();
    if($this->checkClock()=="1"){
        $Clockout=DB::table('tbl_clock')
            ->insert([
                'clock_empid'=>$id->suser_empid,
                'clock_time'=>date('Y-m-d H:i:s'),
                'clock_device'=>'8',
            ]);
        if($Clockout){
            $this->empHistory($id->suser_empid,'Clock Out Requested As Device : <b>'.$this->getRemoteDeviceName('8').'</b>, DateTime : <b>'.date('Y-m-d H:i:s').'</b>');
            return 'success///Clocked Out!!///You Are Clocked Out!!';
        }else{
            return 'error///Whoops!!///Something Went Wrong!!';
        }
    }else{
        return 'error///Whoops!!///You Must Clocked In First!!';
    }
}

public function filterRemoteAttendanceEmployee($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type,$emp_id,$start_date,$end_date)
{
    $id =   Auth::guard('admin')->user();
    if($id->suser_level=="1"){
        return $remoteAttendance=DB::table('tbl_clock')
            ->join('tbl_employee','tbl_clock.clock_empid','=','tbl_employee.emp_id')
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
            ->when($emp_id>0, function($query) use ($emp_id){
                return $query->where('tbl_employee.emp_id', $emp_id);
            })
            ->where(DB::raw("substr(tbl_clock.clock_time, 1, 10)"), '>=',$start_date)
            ->where(DB::raw("substr(tbl_clock.clock_time, 1, 10)"), '<=',$end_date)
            ->orderBy('tbl_clock.clock_time','DESC')
            ->groupBy('tbl_clock.clock_empid')
            ->get(['tbl_clock.*','tbl_employee.*']);
    }elseif($id->suser_level=="4"){
        return $remoteAttendance=DB::table('tbl_clock')
            ->join('tbl_employee','tbl_clock.clock_empid','=','tbl_employee.emp_id')
            ->where('tbl_employee.emp_seniorid',$id->suser_empid)
            ->orWhere('tbl_employee.emp_id',$id->suser_empid)
            ->where(DB::raw("substr(tbl_clock.clock_time, 1, 10)"), '>=',$start_date)
            ->where(DB::raw("substr(tbl_clock.clock_time, 1, 10)"), '<=',$end_date)
            ->orderBy('tbl_clock.clock_time','DESC')
            ->groupBy('tbl_clock.clock_empid')
            ->get(['tbl_clock.*','tbl_employee.*']);
    }elseif($id->suser_level=="2" or $id->suser_level=="3" or $id->suser_level=="5" or $id->suser_level=="6"){
        return $remoteAttendance=DB::table('tbl_clock')
            ->join('tbl_employee','tbl_clock.clock_empid','=','tbl_employee.emp_id')
            ->where(DB::raw("substr(tbl_clock.clock_time, 1, 10)"), '>=',$start_date)
            ->where(DB::raw("substr(tbl_clock.clock_time, 1, 10)"), '<=',$end_date)
            ->where('tbl_employee.emp_id',$id->suser_empid)
            ->orderBy('tbl_clock.clock_time','DESC')
            ->groupBy('tbl_clock.clock_empid')
            ->get(['tbl_clock.*','tbl_employee.*']);
    }
}

public function getRemoteAttendanceRow($remoteAttendance,$start_date,$end_date)
{
    $count=0;
    $data='';
    if(isset($remoteAttendance[0])){
        foreach ($remoteAttendance as $ra) {
            $count++;
            $data.='<tr class="gradeX" id="tr-'.$ra->clock_id.'">
                    <td id="hidden">
                        <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                            <input type="checkbox" class="checkboxes" value="'.$ra->clock_id.'&'.$start_date.'" name="att_id[]" />
                            <span></span>
                        </label>
                    </td>
                    <td>'.$count.'</td>';
            if($id->suser_level=="1"){
                $data.='<td>'.$ra->emp_machineid.'</td>';
            }
            $data.='<td>'.$ra->emp_machineid.'</td>
                    <td><a onclick="getRemoteAttendanceList(';
            $data.="'".$ra->emp_id."','".$start_date."','".$end_date."'";
            $data.=');">'.$ra->emp_name.'</a></td>
                  </tr>';
        }
    }

    return $data;
}

public function remoteAttendance()
{
    $id =   Auth::guard('admin')->user();
    $mainlink = $this->adminmainmenu();
    $sublink = $this->adminsubmenu();
    $Adminminlink = $this->adminlink();
    $adminsublink = $this->adminsublink();

    $designation=DB::table('tbl_designation')->get();
    $department=DB::table('tbl_department')->get();
    $joblocation=DB::table('tbl_joblocation')->get();

    $remoteAttendance=$this->filterRemoteAttendanceEmployee('0','0','0','0','0','0',date('Y-m-d'),date('Y-m-d'));

    $data=$this->getRemoteAttendanceRow($remoteAttendance,date('Y-m-d'),date('Y-m-d'));

    return view('Admin.attendance.remoteAttendance',compact('id','mainlink','sublink','Adminminlink','adminsublink','joblocation','department','designation','data'));

}

public function remoteAttendanceSearch($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type,$emp_id,$start_date,$end_date)
{
    $id =   Auth::guard('admin')->user();
    $mainlink = $this->adminmainmenu();
    $sublink = $this->adminsubmenu();
    $Adminminlink = $this->adminlink();
    $adminsublink = $this->adminsublink();
    $adminInfo = $this->adminInfo($id->suser_empid);

    $designation=DB::table('tbl_designation')->get();
    $department=DB::table('tbl_department')->get();
    $joblocation=DB::table('tbl_joblocation')->get();

    $remoteAttendance=$this->filterRemoteAttendanceEmployee($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type,$emp_id,$start_date,$end_date);

    $data=$this->getRemoteAttendanceRow($remoteAttendance,$start_date,$end_date);

    return view('Admin.attendance.remoteAttendance',compact('id','mainlink','sublink','Adminminlink','adminsublink','joblocation','department','designation','data','emp_depart_id','emp_sdepart_id','emp_desig_id','emp_jlid','emp_type','emp_id','start_date','end_date'));
}


public function getRemoteAttendanceList($emp_empid,$start_date,$end_date)
{
    $employee=DB::table('tbl_employee')
        ->join('kqz_employee','kqz_employee.EmployeeCode','=','tbl_employee.emp_machineid')
        ->where('tbl_employee.emp_id',$emp_empid)
        ->first(['tbl_employee.emp_name','tbl_employee.emp_empid','kqz_employee.EmployeeID']);
    $data='';
    $data.='<table class="table table-bordered">';
    if($start_date==$end_date){
        $data.='<caption>Employee ID : <b>'.$employee->emp_empid.'</b> <br>Employee Name : <b>'.$employee->emp_name.'</b><br>Date : <b>'.$start_date.'</b></caption>';
    }else{
        $data.='<caption>Employee ID : <b>'.$employee->emp_empid.'</b> <br>Employee Name : <b>'.$employee->emp_name.'</b><br>Date : From <b>'.$start_date.'</b> To <b>'.$end_date.'</b></caption>';
    }
    $data.='<thead><tr><th style="font-size: 12px">SL</th><th style="font-size: 12px">Clock Type</th><th style="font-size: 12px">Clock Date</th><th style="font-size: 12px">Clock Time</th><th style="font-size: 12px">WorkStation </th><th style="font-size: 12px">Action</th></tr></thead><tbody>';
    $tbl_clock=DB::table('tbl_clock')
        ->join('tbl_remotedevice','tbl_clock.clock_device','=','tbl_remotedevice.DevID')
        ->where(DB::raw("substr(tbl_clock.clock_time, 1, 10)"), '>=',$start_date)
        ->where(DB::raw("substr(tbl_clock.clock_time, 1, 10)"), '<=',$end_date)
        ->where('tbl_clock.clock_empid',$emp_empid)
        ->orderBy('tbl_clock.clock_time','ASC')
        ->get(['tbl_clock.*','tbl_remotedevice.DevName']);
    $count=0;
    if(isset($tbl_clock[0])){
        foreach ($tbl_clock as $clock) {
        $count++;
            $data.='<tr>';
            $data.='<td style="font-size: 12px">'.$count.'</td>';

            if($clock->clock_device=="7"){
                $data.='<td style="font-size: 12px">Clock In</td>';
            }elseif($clock->clock_device=="8"){
                $data.='<td style="font-size: 12px">Clock Out</td>';
            }
            $data.='<td style="font-size: 12px">'.substr($clock->clock_time,0,10).'</td>';
            $data.='<td style="font-size: 12px">'.substr($clock->clock_time,11,8).'</td>';
            $data.='<td style="font-size: 12px">'.$clock->DevName.'</td>';
            $data.='<td style="font-size: 12px"><a class="btn btn-xs btn-primary" id="confirm'.$clock->clock_id.'" onclick="ConfirmRemoteAttendance(';
            $data.="'".$clock->clock_id."'";
            $data.=');">Confirm</a><a class="btn btn-xs btn-danger" id="deny'.$clock->clock_id.'" onclick="DenyRemoteAttendance(';
            $data.="'".$clock->clock_id."'";
            $data.=');">Deny</a><span id="msg'.$clock->clock_id.'"></span>';
            $data.'</td>';
            $data.='</tr>'; 
        }
    }
    $data.='</tbody>';
    $data.='</table>';

    return $data;
}

public function remoteAttendanceConfirm($clock_id)
{
    $clock=DB::table('tbl_clock')
        ->join('tbl_employee','tbl_clock.clock_empid','=','tbl_employee.emp_id')
        ->join('kqz_employee','tbl_employee.emp_machineid','=','kqz_employee.EmployeeCode')
        ->where('tbl_clock.clock_id',$clock_id)
        ->first(['tbl_clock.*','kqz_employee.EmployeeID']);
    if(isset($clock->clock_id)){
        $card=DB::table('kqz_card')->insert([
            'EmployeeID'=>$clock->EmployeeID,
            'CardTime'=>$clock->clock_time,
            'CardTypeID'=>'0',
            'DevID'=>$clock->clock_device,
            'FaceIDNo'=>' ',
        ]);
        if(isset($card)){
            $this->empHistory($clock->clock_empid,'Remote Clock Request Confirmed As Device : <b>'.$this->getRemoteDeviceName($clock->clock_device).'</b>, DateTime : <b>'.$clock->clock_time.'</b>');
            $delete=DB::table('tbl_clock')->where('tbl_clock.clock_id',$clock_id)->delete();
            return '1';
        }else{
            return '0';
        }
    }else{
        return '0';
    }
}

public function remoteAttendanceDeny($clock_id)
{
    $clock=DB::table('tbl_clock')->where('tbl_clock.clock_id',$clock_id)->first();
    $deny=DB::table('tbl_clock')->where('tbl_clock.clock_id',$clock_id)->delete();
    if(isset($deny)){
        $this->empHistory($clock->clock_empid,'Remote Clock Request Denied As Device : <b>'.$this->getRemoteDeviceName($clock->clock_device).'</b>, DateTime : <b>'.$clock->clock_time.'</b>');
        return $deny;
    }else{
        return '0';
    }
}

public function filterOSDAttendanceEmployee($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type,$emp_id,$start_date,$end_date)
{
    $id =   Auth::guard('admin')->user();
    if($id->suser_level=="1"){
        return $osd_attendance=DB::table('tbl_osdattendance')
            ->join('tbl_employee','tbl_employee.emp_id','=','tbl_osdattendance.osd_done_by')
            ->join('kqz_employee','tbl_employee.emp_machineid','=','kqz_employee.EmployeeCode')
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
                return $query->where('kqz_employee.EmployeeID', $emp_id);
            })
            ->when($start_date!="", function($query) use ($start_date){
                return $query->where('tbl_osdattendance.osd_date','>=',$start_date);
            })
            ->when($end_date!="", function($query) use ($end_date){
                return $query->where('tbl_osdattendance.osd_date','>=',$end_date);
            })
            ->orderBy('tbl_osdattendance.osd_status','asc')
            ->orderBy('tbl_osdattendance.osd_id','desc')
            ->get(['tbl_osdattendance.*','tbl_employee.*']);
    }else if($id->suser_level=="4"){
        return $osd_attendance=DB::table('tbl_osdattendance')
            ->join('tbl_employee','tbl_employee.emp_id','=','tbl_osdattendance.osd_done_by')
            ->join('kqz_employee','tbl_employee.emp_machineid','=','kqz_employee.EmployeeCode')
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
            ->get(['tbl_osdattendance.*','tbl_employee.*']);
    }else if($id->suser_level=="2" or $id->suser_level=="3" or $id->suser_level=="5" or $id->suser_level=="6"){
       return $osd_attendance=DB::table('tbl_osdattendance')
            ->join('tbl_employee','tbl_employee.emp_id','=','tbl_osdattendance.osd_done_by')
            ->join('kqz_employee','tbl_employee.emp_machineid','=','kqz_employee.EmployeeCode')
            ->where('tbl_employee.emp_id',$id->suser_empid)
            ->when($start_date!="", function($query) use ($start_date){
                return $query->where('tbl_osdattendance.osd_date','>=',$start_date);
            })
            ->when($end_date!="", function($query) use ($end_date){
                return $query->where('tbl_osdattendance.osd_date','>=',$end_date);
            })
            ->orderBy('tbl_osdattendance.osd_status','asc')
            ->orderBy('tbl_osdattendance.osd_id','desc')
            ->get(['tbl_osdattendance.*','tbl_employee.*']);
    }
}

public function getOSDAttendanceRow($osd_attendance,$status)
{
    $data='';
    $count=0;
    if(isset($osd_attendance[0])){
        foreach ($osd_attendance as $osd) {
            if($osd->osd_status==$status){
                $count++;
                $data.='<tr class="gradeX" id="tr-'.$osd->osd_id.'">
                        <td id="hidden">
                            <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                <input type="checkbox" class="checkboxes" value="'.$osd->osd_id.'" name="osd_id[]" />
                                <span></span>
                            </label>
                        </td>
                        <td>'.$count.'</td>
                        <td>'.$osd->osd_uniquecode.'</td>
                        <td>'.$osd->emp_empid.'</td>
                        <td>'.$osd->emp_name.'</td>
                        <td>'.$osd->osd_date.'</td>
                        <td>'.$osd->osd_starttime.'</td>
                        <td>'.$osd->osd_endtime.'</td>
                        <td>'.$osd->osd_duration.'</td>
                        <td>'.$osd->osd_location.'</td>
                        <td>'.$osd->osd_description.'</td>
                        <td>';
                    if($osd->osd_status=="0"){
                        $data.='<a class="btn btn-warning btn-xs">Pending</a>';
                    }elseif($osd->osd_status=="1"){
                        $data.='<a class="btn btn-success btn-xs">Approved</a>';
                    }elseif($osd->osd_status=="2"){
                        $data.='<a class="btn btn-danger btn-xs">Denied</a>';
                    }
                $data.='</td>';
                $data.='</tr>';
            }
        }
    }

    return $data;
}

public function osdAttendance()
{
    $id =   Auth::guard('admin')->user();
    $mainlink = $this->adminmainmenu();
    $sublink = $this->adminsubmenu();
    $Adminminlink = $this->adminlink();
    $adminsublink = $this->adminsublink();

    $designation=DB::table('tbl_designation')->get();
    $department=DB::table('tbl_department')->get();
    $joblocation=DB::table('tbl_joblocation')->get();

    $osd_attendance=$this->filterOSDAttendanceEmployee('0','0','0','0','0','0','','');

    $data=$this->getOSDAttendanceRow($osd_attendance,'1');
    $data.=$this->getOSDAttendanceRow($osd_attendance,'2');
    $title='OSD Attendance Application';

    return view('Admin.attendance.osdAttendance',compact('id','mainlink','sublink','Adminminlink','adminsublink','joblocation','department','designation','data','title'));
}

public function osdAttendanceSearch($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type,$emp_id,$start_date,$end_date)
{
    $id =   Auth::guard('admin')->user();
    $mainlink = $this->adminmainmenu();
    $sublink = $this->adminsubmenu();
    $Adminminlink = $this->adminlink();
    $adminsublink = $this->adminsublink();
    $designation=DB::table('tbl_designation')->get();
    $department=DB::table('tbl_department')->get();
    $joblocation=DB::table('tbl_joblocation')->get();
    
    //todays all employee add (start)//
    $osd_attendance=$this->filterOSDAttendanceEmployee($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type,$emp_id,$start_date,$end_date);
    
    $data=$this->getOSDAttendanceRow($osd_attendance,'1');
    $data.=$this->getOSDAttendanceRow($osd_attendance,'2');
    $title='OSD Attendance Application';

    return view('Admin.attendance.osdAttendance',compact('id','mainlink','sublink','Adminminlink','adminsublink','joblocation','department','designation','data','emp_depart_id','emp_sdepart_id','emp_desig_id','emp_jlid','emp_type','start_date','end_date','title'));
}

public function pendingOSDAttendance()
{
    $id =   Auth::guard('admin')->user();
    $mainlink = $this->adminmainmenu();
    $sublink = $this->adminsubmenu();
    $Adminminlink = $this->adminlink();
    $adminsublink = $this->adminsublink();

    $designation=DB::table('tbl_designation')->get();
    $department=DB::table('tbl_department')->get();
    $joblocation=DB::table('tbl_joblocation')->get();

    $osd_attendance=$this->filterOSDAttendanceEmployee('0','0','0','0','0','0','','');

    $data=$this->getOSDAttendanceRow($osd_attendance,'0');
    $title='Pending OSD Attendance Application';

    return view('Admin.attendance.osdAttendance',compact('id','mainlink','sublink','Adminminlink','adminsublink','joblocation','department','designation','data','title'));
}

public function pendingOSDAttendanceSearch($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type,$emp_id,$start_date,$end_date)
{
    $id =   Auth::guard('admin')->user();
    $mainlink = $this->adminmainmenu();
    $sublink = $this->adminsubmenu();
    $Adminminlink = $this->adminlink();
    $adminsublink = $this->adminsublink();
    $designation=DB::table('tbl_designation')->get();
    $department=DB::table('tbl_department')->get();
    $joblocation=DB::table('tbl_joblocation')->get();
    
    //todays all employee add (start)//
    $osd_attendance=$this->filterOSDAttendanceEmployee($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type,$emp_id,$start_date,$end_date);
    
    $data=$this->getOSDAttendanceRow($osd_attendance,'0');
    $title='Pending OSD Attendance Application';

    return view('Admin.attendance.osdAttendance',compact('id','mainlink','sublink','Adminminlink','adminsublink','joblocation','department','designation','data','emp_depart_id','emp_sdepart_id','emp_desig_id','emp_jlid','emp_type','start_date','end_date','title'));
}

public function osdAttendanceRequestSubmit(Request $request)
{
    $id =   Auth::guard('admin')->user();
    $this->validate($request,[
        'osd_date'=>'required',
        'osd_starttime'=>'required',
        'osd_endtime'=>'required',
        'osd_location'=>'required',
    ]);
    $duration="00:00:00";
    if(explode(':',$request->osd_starttime)[0]<explode(':',$request->osd_endtime)[0]){
            $end_time = new DateTime($request->osd_endtime.':00');
            $start_time = new DateTime($request->osd_starttime.':00');
            $interval = $end_time->diff($start_time);
            $duration=$interval->format('%H:%I:%S');
    }elseif(explode(':',$request->osd_starttime)[0]==explode(':',$request->osd_endtime)[0]){
        if(explode(':',$request->osd_starttime)[1]<explode(':',$request->osd_endtime)[1]){
            $end_time = new DateTime($request->osd_endtime.':00');
            $start_time = new DateTime($request->osd_starttime.':00');
            $interval = $end_time->diff($start_time);
            $duration=$interval->format('%H:%I:%S');
        }else{
            Session::flash('error','Sorry! OSD Attendace End Time cannot be Earlier Then or Equal to Start Time.');
            return redirect('osd-attendance');
        }
    }else{
        Session::flash('error','Sorry! OSD Attendace Time Is Not Valid.');
        return redirect('osd-attendance');
    }

    /*$check=DB::table('tbl_osdattendance')->where(['osd_done_by'=>$id->suser_empid,'osd_date'=>$request->osd_date])->first();
    if(isset($check) && count($check)>0){
        Session::flash('error','Sorry! You Can Apply For OSD once in a day.');
        return redirect('osd-attendance');
    }else{*/
       $tbl_osdattendance=DB::table('tbl_osdattendance')
        ->insert([
            'osd_uniquecode'=>$this->osd_uniquecode(),
            'osd_done_by'=>$id->suser_empid,
            'osd_date'=>$request->osd_date,
            'osd_starttime'=>$request->osd_starttime,
            'osd_endtime'=>$request->osd_endtime,
            'osd_duration'=>$duration,
            'osd_location'=>$request->osd_location,
            'osd_description'=>$request->osd_description,
        ]);
        if(isset($tbl_osdattendance)){
            Session::flash('success','OSD Attendace Request has been submitted Successfully.');
            return redirect('osd-attendance');
        }else{
            Session::flash('error','Sorry! Something Went Wrong!');
            return redirect('osd-attendance');
        } 
    //}
}

public function osdAttendanceRequestApprove(Request $request)
{
    $id =   Auth::guard('admin')->user();
    if(count($request->osd_id)>0){
        $aprove_count=0;
        $error_count=0;
        $not_permitted=0;
        for ($i=0; $i < count($request->osd_id) ; $i++) {
            $check=DB::table('tbl_osdattendance')->where('osd_id',$request->osd_id[$i])->first();
            if($check->osd_status=="0"){
                if($this->permittedEmployee($check->osd_done_by)=="1"){
                    $aprove=DB::table('tbl_osdattendance')
                    ->where('osd_id',$request->osd_id[$i])
                    ->update(['osd_status'=>'1','osd_approved_by'=>$id->suser_empid]);
                    if($aprove){
                        $employee=DB::table('tbl_employee')->where('tbl_employee.emp_id',$check->osd_done_by)->first();
                        $this->empHistory($check->osd_done_by,'OSD Attendance Request has been Approved which is followed by- <br>OSD Date : <b>'.$check->osd_date.'</b><br>OSD Start Time : <b>'.$check->osd_starttime.'</b><br>OSD End Time : <b>'.$check->osd_endtime.'</b><br>OSD Duration : <b>'.$check->osd_duration.'</b><br>Location : <b>'.$check->osd_location.'</b><br>Description : <b>'.$check->osd_description.'</b>');
                        $aprove_count++;
                    }
                }else{
                    $not_permitted++;
                }
            }else{
                $error_count++;
            }

            if($aprove_count>0){
                Session::flash('success',$aprove_count.' OSD Attendance Request(s) Approved Successfully.');
            }

            if($error_count>0 or $not_permitted>0){
                $data='';
                if($error_count>0){
                    $data.=$error_count.' OSD Attendance Request(s) Cannot Be Approved. May Be These OSD Attendance Request(s) Approoved/Approved Previously.';
                }
                if($not_permitted>0){
                    $data.='And '.$not_permitted.' OSD Attendance Request(s) Cannot Be Approved because you cannot aprove your own OSD Attendance Request(s) or you are not permitted to aprove them.';
                }
                Session::flash('error',$data);
            }
        }
        return '1';
    }else{
        return 'null';
    }
}

public function osdAttendanceRequestDeny(Request $request)
{
    $id =   Auth::guard('admin')->user();
    if(count($request->osd_id)>0){
        $deny_count=0;
        $error_count=0;
        $not_permitted=0;
        for ($i=0; $i < count($request->osd_id) ; $i++) {
            $check=DB::table('tbl_osdattendance')->where('osd_id',$request->osd_id[$i])->first();
            if($check->osd_status=="0"){
                if($this->permittedEmployee($check->osd_done_by)=="1"){
                    $aprove=DB::table('tbl_osdattendance')
                    ->where('osd_id',$request->osd_id[$i])
                    ->update(['osd_status'=>'2','osd_approved_by'=>$id->suser_empid]);
                    if($aprove){
                        $employee=DB::table('tbl_employee')->where('tbl_employee.emp_id',$check->osd_done_by)->first();
                        $this->empHistory($check->osd_done_by,'OSD Attendance Request has been Denied which is followed by- <br>OSD Date : <b>'.$check->osd_date.'</b><br>OSD Start Time : <b>'.$check->osd_starttime.'</b><br>OSD End Time : <b>'.$check->osd_endtime.'</b><br>OSD Duration : <b>'.$check->osd_duration.'</b><br>Location : <b>'.$check->osd_location.'</b><br>Description : <b>'.$check->osd_description.'</b>');
                        $deny_count++;
                    }
                }else{
                    $not_permitted++;
                }
            }else{
                $error_count++;
            }

            if($deny_count>0){
                Session::flash('success',$deny_count.' OSD Attendance Request(s) Denied Successfully.');
            }

            if($error_count>0 or $not_permitted>0){
                $data='';
                if($error_count>0){
                    $data.=$error_count.' OSD Attendance Request(s) Cannot Be Denied. May Be These OSD Attendance Request(s) Approoved/Denied Previously.';
                }
                if($not_permitted>0){
                    $data.='And '.$not_permitted.' OSD Attendance Request(s) Cannot Be Denied because you cannot aprove your own OSD Attendance Request(s)';
                }
                Session::flash('error',$data);
            }
        }
        return '1';
    }else{
        return 'null';
    }
}

}