<?php

namespace App\Http\Controllers\BackEndCon;

use Illuminate\Http\Request;
use App\Http\Controllers\BackEndCon\Controller;
use Auth;
use Session;
use DB;
use DateTime;
use App\Setup;
use App\TotalWorkingHours;
use App\Device;
use App\Employee;
use App\EmployeeTypes;
use App\JobLocation;

class attendanceController extends Controller
{
    public function late_application()
    {
        return view('Admin.attendance.late_application');
    }

    public function emp_late_application($emp_id)
    {
        // return $emp_id; exit();
        $employee = Employee::where('emp_id', $emp_id)->first();
        return view('Admin.attendance.late_application', compact('emp_id', 'employee'));
    }

    public function date_wise_employee_entry()
    {
        // $d='2021-05-08 14:23:08';
        // $da= substr($d,11,18);
        // return $da; exit();


        $id =   Auth::guard('admin')->user();
    $otController=new otController();
    $mainlink = $this->adminmainmenu();
    $sublink = $this->adminsubmenu();
    $Adminminlink = $this->adminlink();
    $adminsublink = $this->adminsublink();

    $today = date('Y-m-d');

    $types=EmployeeTypes::orderBy('name','asc')->get();
    $designation=DB::table('tbl_designation')->get();
    $department=DB::table('tbl_department')->get();
    $joblocation=DB::table('tbl_joblocation')->get();
    
    $attendances=Null;

    $employee=Employee::get();
    // $checkinout=Employee::join('tbl_checkinout', 'tbl_checkinout.emp_id', '=', 'tbl_employee.emp_machineid')
    // ->get();

    return view('Admin.attendance.entry',compact('id','mainlink','sublink','Adminminlink','adminsublink','types','joblocation','department','designation','attendances', 'today', 'employee'));
    }

    public function date_wise_entry_submit(Request $request)
    { 
        // return $request; exit();
        $this->validate($request,[
        'emp_id'=>'required',
       
        
    ]);
    $emp=$request->emp_id;
    $s_date=$request->start_date;
    $e_date=$request->end_date;
    $emp_nam= Employee::where('emp_machineid', $emp)->first();

    // return $emp_nam; exit();

    $id =   Auth::guard('admin')->user();
    $otController=new otController();
    $mainlink = $this->adminmainmenu();
    $sublink = $this->adminsubmenu();
    $Adminminlink = $this->adminlink();
    $adminsublink = $this->adminsublink();

    $today = date('Y-m-d');

    $types=EmployeeTypes::orderBy('name','asc')->get();
    $designation=DB::table('tbl_designation')->get();
    $department=DB::table('tbl_department')->get();
    $joblocation=DB::table('tbl_joblocation')->get();
    
    $attendances=Null;

    $employee=Employee::get();
        // echo "string";
        // return $request;
        // $filter_date=$request->start_date; 
        $filter=Employee::join('tbl_checkinout', 'tbl_checkinout.emp_id', '=', 'tbl_employee.emp_machineid')
        ->join('tbl_device', 'tbl_device.id', '=', 'tbl_checkinout.device')
        ->where('tbl_checkinout.emp_id', $request->emp_id)
        ->where(DB::raw("substr(tbl_checkinout.check_time, 1, 11)"), '>=' ,$request->start_date)
        ->where(DB::raw("substr(tbl_checkinout.check_time, 1, 11)"), '<=' ,$request->end_date)

        ->get();

       return view('Admin.attendance.entry',compact('id','mainlink','sublink','Adminminlink','adminsublink','types','joblocation','department','designation','attendances', 'today', 'employee','filter', 's_date', 'e_date' ,'emp', 'emp_nam'));
    }

public function filterAttendanceEmployee($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type,$emp_id)
{
    $id =   Auth::guard('admin')->user();
    if($id->suser_level=="1"){
        $search=DB::table('tbl_employee')
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
            ->where('tbl_employee.emp_status', 1)
            ->whereNotNull('tbl_employee.emp_machineid')
            ->groupBy('tbl_employee.emp_machineid')
            ->get(['tbl_employee.emp_id']);
    }else if($id->suser_level=="4"){
        $search=DB::table('tbl_employee')
            ->where('tbl_employee.emp_seniorid',$id->suser_empid)
            ->orWhere('tbl_employee.emp_id',$id->suser_empid)
            ->groupBy('tbl_employee.emp_machineid')
            ->get(['tbl_employee.emp_id']);
    }else if($id->suser_level=="2" or $id->suser_level=="3" or $id->suser_level=="5" or $id->suser_level=="6"){
        $search=DB::table('tbl_employee')
            ->groupBy('tbl_employee.emp_machineid')
            ->where('tbl_employee.emp_id',$id->suser_empid)
            ->get(['tbl_employee.emp_id']);
    }

    $employees = array();
    if(isset($search[0])){
        foreach ($search as $key => $employee) {
            array_push($employees,$employee->emp_id);
        }
    }

    return $employees;
}


public function monthwiseAttendanceEmployee($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type,$emp_id)
{
    $id =   Auth::guard('admin')->user();
    if($id->suser_level=="1"){
        $search=DB::table('tbl_employee')
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
            ->where('tbl_employee.emp_status', 1)
            ->whereNotNull('tbl_employee.emp_machineid')
            ->groupBy('tbl_employee.emp_machineid')
            ->get(['tbl_employee.emp_id']);
    }else if($id->suser_level=="4"){
        $search=DB::table('tbl_employee')
            ->where('tbl_employee.emp_seniorid',$id->suser_empid)
            ->orWhere('tbl_employee.emp_id',$id->suser_empid)
            ->groupBy('tbl_employee.emp_machineid')
            ->get(['tbl_employee.emp_id']);
    }else if($id->suser_level=="2" or $id->suser_level=="3" or $id->suser_level=="5" or $id->suser_level=="6"){
        $search=DB::table('tbl_employee')
            ->groupBy('tbl_employee.emp_machineid')
            ->where('tbl_employee.emp_id',$id->suser_empid)
            ->get(['tbl_employee.emp_id']);
    }

    $employees = array();
    if(isset($search[0])){
        foreach ($search as $key => $employee) {
            array_push($employees,$employee->emp_id);
        }
    }

    return $employees;
}

public function attendance(){
    $id =   Auth::guard('admin')->user();
    $otController=new otController();
    $mainlink = $this->adminmainmenu();
    $sublink = $this->adminsubmenu();
    $Adminminlink = $this->adminlink();
    $adminsublink = $this->adminsublink();

    $today = date('Y-m-d');

    $types=EmployeeTypes::orderBy('name','asc')->get();
    $designation=DB::table('tbl_designation')->get();
    $department=DB::table('tbl_department')->get();
    $joblocation=DB::table('tbl_joblocation')->get();
    
    $attendances=TotalWorkingHours::with(['employee','shift'])
        ->where('tbl_totalworkinghours.date',date('Y-m-d'))
        ->whereIn('tbl_totalworkinghours.emp_id',$this->filterAttendanceEmployee('0','0','0','0','0','0'))
        ->join('tbl_employee', 'tbl_employee.emp_id', '=', 'tbl_totalworkinghours.emp_id')
        ->orderBy('tbl_employee.emp_empid')
        ->get();

        $flag=0;

    return view('Admin.attendance.index',compact('id','mainlink','sublink','Adminminlink','adminsublink','types','joblocation','department','designation','attendances', 'today', 'flag'));
}


public function month_wise_attendance(){
    $id =   Auth::guard('admin')->user();
    $otController=new otController();
    $mainlink = $this->adminmainmenu();
    $sublink = $this->adminsubmenu();
    $Adminminlink = $this->adminlink();
    $adminsublink = $this->adminsublink();

    $today = date('Y-m-d');

    $types=EmployeeTypes::orderBy('name','asc')->get();
    $designation=DB::table('tbl_designation')->get();
    $department=DB::table('tbl_department')->get();
    $joblocation=DB::table('tbl_joblocation')->get();
    
    $attendances=TotalWorkingHours::with(['employee','shift'])
        ->where('tbl_totalworkinghours.date',date('Y-m-d'))
        ->whereIn('tbl_totalworkinghours.emp_id',$this->monthwiseAttendanceEmployee('0','0','0','0','0','0'))
        ->join('tbl_employee', 'tbl_employee.emp_id', '=', 'tbl_totalworkinghours.emp_id')
        ->orderBy('tbl_employee.emp_empid')
        ->get();

        $flag=0;

    return view('Admin.attendance.month_wise_attendance',compact('id','mainlink','sublink','Adminminlink','adminsublink','types','joblocation','department','designation','attendances', 'today', 'flag'));
}

public function attendance_report_print()
{
     $id =   Auth::guard('admin')->user();
    $otController=new otController();
    $mainlink = $this->adminmainmenu();
    $sublink = $this->adminsubmenu();
    $Adminminlink = $this->adminlink();
    $adminsublink = $this->adminsublink();

    $today = date('d-m-Y');

    $types=EmployeeTypes::orderBy('name','asc')->get();
    $designation=DB::table('tbl_designation')->get();
    $department=DB::table('tbl_department')->get();
    $joblocation=DB::table('tbl_joblocation')->get();
    
    $attendances=TotalWorkingHours::with(['employee','shift'])
        ->where('tbl_totalworkinghours.date',date('Y-m-d'))
        ->whereIn('tbl_totalworkinghours.emp_id',$this->filterAttendanceEmployee('0','0','0','0','0','0'))
        ->join('tbl_employee', 'tbl_employee.emp_id', '=', 'tbl_totalworkinghours.emp_id')
        ->orderBy('tbl_employee.emp_empid')
        ->get();
        $flag=0;

    return view('Admin.attendance.print',compact('id','mainlink','sublink','Adminminlink','adminsublink','types','joblocation','department','designation','attendances', 'today', 'flag'));
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
        ->get(['tbl_employee.emp_id','tbl_employee.emp_name','tbl_employee.emp_empid']);
    if(isset($employee[0])){
        foreach ($employee as $emp) {
            $data.='<option value="'.$emp->emp_id.'">'.$emp->emp_name.'('.$emp->emp_empid.')</option>';
        }
    }
    return $data;
}

public function getRemoteEmployee($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type)
{
    $data='<option value="0">Select</option>';

    $employee=DB::table('tbl_employee')
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
        ->get(['tbl_employee.emp_id','tbl_employee.emp_name','tbl_employee.emp_empid']);
    if(isset($employee[0])){
        foreach ($employee as $emp) {
            $data.='<option value="'.$emp->emp_id.'">'.$emp->emp_name.' ('.$emp->emp_empid.')</option>';
        }
    }
    return $data;
}

public function attendanceSearch($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type,$emp_id,$start_date,$end_date)
{
    if ($emp_jlid>0) {
        $flag=1;
    }else{
        $flag=0;
    }

    $empl_location=JobLocation::where('jl_id', $emp_jlid)->first();

    $id =   Auth::guard('admin')->user();
    $mainlink = $this->adminmainmenu();
    $sublink = $this->adminsubmenu();
    $Adminminlink = $this->adminlink();
    $adminsublink = $this->adminsublink();
    $adminInfo = $this->adminInfo($id->suser_empid);

    $types=EmployeeTypes::orderBy('name','asc')->get();
    $designation=DB::table('tbl_designation')->get();
    $department=DB::table('tbl_department')->get();
    $joblocation=DB::table('tbl_joblocation')->get();

    $dateRange=$this->getDateRange($start_date,$end_date);
    $attendances=TotalWorkingHours::with(['employee','shift'])
        ->whereIn('date',$dateRange)
        ->whereIn('emp_id',$this->filterAttendanceEmployee($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type,$emp_id))
        ->get();

    return view('Admin.attendance.index',compact('id','mainlink','sublink','Adminminlink','adminsublink','types','joblocation','department','designation','emp_depart_id','emp_sdepart_id','emp_desig_id','emp_jlid','emp_type','start_date','end_date','attendances', 'emp_id', 'flag', 'empl_location'));
}


public function monthwiseattendanceSearch($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type,$emp_id,$start_date,$end_date, $mon)
{
    // echo "string"; exit();
    // return $emp_id; exit();
    if ($emp_jlid>0) {
        $flag=1;
    }else{
        $flag=0;
    }

    $empl_location=JobLocation::where('jl_id', $emp_jlid)->first();

    $id =   Auth::guard('admin')->user();
    $mainlink = $this->adminmainmenu();
    $sublink = $this->adminsubmenu();
    $Adminminlink = $this->adminlink();
    $adminsublink = $this->adminsublink();
    $adminInfo = $this->adminInfo($id->suser_empid);

    $types=EmployeeTypes::orderBy('name','asc')->get();
    $designation=DB::table('tbl_designation')->get();
    $department=DB::table('tbl_department')->get();
    $joblocation=DB::table('tbl_joblocation')->get();

    // $dateRange=$this->getMonth($mon);
    $attendances=TotalWorkingHours::with(['employee','shift'])
        // ->whereIn('date',$dateRange)
        ->where('date','2021-'.$mon.'-01')
        ->whereIn('emp_id',$this->monthwiseAttendanceEmployee($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type,$emp_id))
        ->get();
    // return $attendances; exit();

    return view('Admin.attendance.month_wise_att_print',compact('id','mainlink','sublink','Adminminlink','adminsublink','types','joblocation','department','designation','emp_depart_id','emp_sdepart_id','emp_desig_id','emp_jlid','emp_type','start_date','end_date','attendances', 'emp_id', 'flag', 'empl_location', 'mon'));
}

public function attendanceSearch_print($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type,$emp_id,$start_date,$end_date)
{
    if ($emp_jlid>0) {
        $flag=1;
    }else{
        $flag=0;
    }

    $empl_location=JobLocation::where('jl_id', $emp_jlid)->first();
    // return $emp_jlid; exit();
    $id =   Auth::guard('admin')->user();
    $mainlink = $this->adminmainmenu();
    $sublink = $this->adminsubmenu();
    $Adminminlink = $this->adminlink();
    $adminsublink = $this->adminsublink();
    $adminInfo = $this->adminInfo($id->suser_empid);

    $types=EmployeeTypes::orderBy('name','asc')->get();
    $designation=DB::table('tbl_designation')->get();
    $department=DB::table('tbl_department')->get();
    $joblocation=DB::table('tbl_joblocation')->get();

    $dateRange=$this->getDateRange($start_date,$end_date);
    $attendances=TotalWorkingHours::with(['employee','shift'])
        ->whereIn('date',$dateRange)
        ->whereIn('emp_id',$this->filterAttendanceEmployee($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type,$emp_id))
        ->get();

    return view('Admin.attendance.print',compact('id','mainlink','sublink','Adminminlink','adminsublink','types','joblocation','department','designation','emp_depart_id','emp_sdepart_id','emp_desig_id','emp_jlid','emp_type','start_date','end_date','attendances', 'emp_id', 'flag', 'empl_location'));
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
                    ->first(['kqz_card.*','kqz_devinfo.DevName']);
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
                        ->first(['kqz_card.*','tbl_remotedevice.DevName']);
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

    $types=EmployeeTypes::orderBy('name','asc')->get();
    $designation=DB::table('tbl_designation')->get();
    $department=DB::table('tbl_department')->get();
    $joblocation=DB::table('tbl_joblocation')->get();
    $devices=Device::get();

    return view('Admin.attendance.create',compact('id','mainlink','sublink','Adminminlink','adminsublink','types','designation','department','joblocation','devices'));
}

public function attendanceAddSubmit(Request $request)
{
    if($request->emp_id>0){
        if($request->device>0){
            if($request->check_time_date!="" && $request->check_time_time!=""){
                $employee=Employee::find($request->emp_id);
                $search=DB::table('tbl_checkinout')
                            ->where([
                            'branch'=>1,
                            'emp_id'=>$employee->emp_machineid,
                            'device'=>$request->device,
                            'check_time'=>$request->check_time_date.' '.$request->check_time_time.':00',
                        ])
                        ->first();
                        if(!isset($search->check_time)){
                            $card=DB::table('tbl_checkinout')->insert([
                                    'branch'=>1,
                                    'emp_id'=>$employee->emp_machineid,
                                    'device'=>$request->device,
                                    'check_time'=>$request->check_time_date.' '.$request->check_time_time.':00',
                                ]);
                            if(isset($card)){
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
    $attendance=DB::table('tbl_checkinout')
        ->join('tbl_employee','tbl_employee.emp_machineid','=','tbl_checkinout.emp_id')
        ->where('tbl_employee.emp_id',$emp_id)
        ->where(DB::raw("substr(tbl_checkinout.check_time, 1, 10)"), '=',$date)
        ->groupBy(DB::raw("substr(tbl_checkinout.check_time, 12, 5)"))
        ->orderBy('tbl_checkinout.check_time','asc')
        ->get();
    return view('Admin.attendance.edit',compact('id','mainlink','sublink','Adminminlink','adminsublink','joblocation','employee','attendance','date'));
}

public function attendanceUpdate($id,$check_time,$device)
{
    if(explode(':',$check_time)[0]<=9){
        $check_time='0'.$check_time;
    }
    $checkinout=DB::table('tbl_checkinout')->where('id',$id)->first();
    $check_time=substr($checkinout->check_time,0,11).$check_time.':00';
    $update=DB::update("update `tbl_checkinout` set `check_time` = '".$check_time."', `device` = '".$device."' where `tbl_checkinout`.`id` = '".$id."'");
    if(isset($update)){
        $this->empHistory($checkinout->emp_id.'Attendace Update Punch Device From  :<b>'.$this->getDeviceName($checkinout->device).'</b> to <b>'.$this->getDeviceName($device).'</b>, DateTime From : <b>'.$card->check_time.'</b> to <b>'.$check_time.'</b>');
    }
    return $update;
}

public function attendanceSingleDelete($id)
{
    $delete=DB::table('tbl_checkinout')->where('id',$id)->delete();
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
    $id =   Auth::guard('admin')->user();
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

    $types=EmployeeTypes::orderBy('name','asc')->get();
    $designation=DB::table('tbl_designation')->get();
    $department=DB::table('tbl_department')->get();
    $joblocation=DB::table('tbl_joblocation')->get();

    $remoteAttendance=$this->filterRemoteAttendanceEmployee('0','0','0','0','0','0',date('Y-m-d'),date('Y-m-d'));

    $data=$this->getRemoteAttendanceRow($remoteAttendance,date('Y-m-d'),date('Y-m-d'));

    return view('Admin.attendance.remoteAttendance',compact('id','mainlink','sublink','Adminminlink','adminsublink','types','joblocation','department','designation','data'));

}

public function remoteAttendanceSearch($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type,$emp_id,$start_date,$end_date)
{
    $id =   Auth::guard('admin')->user();
    $mainlink = $this->adminmainmenu();
    $sublink = $this->adminsubmenu();
    $Adminminlink = $this->adminlink();
    $adminsublink = $this->adminsublink();
    $adminInfo = $this->adminInfo($id->suser_empid);

    $types=EmployeeTypes::orderBy('name','asc')->get();
    $designation=DB::table('tbl_designation')->get();
    $department=DB::table('tbl_department')->get();
    $joblocation=DB::table('tbl_joblocation')->get();

    $remoteAttendance=$this->filterRemoteAttendanceEmployee($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type,$emp_id,$start_date,$end_date);

    $data=$this->getRemoteAttendanceRow($remoteAttendance,$start_date,$end_date);

    return view('Admin.attendance.remoteAttendance',compact('id','mainlink','sublink','Adminminlink','adminsublink','types','joblocation','department','designation','data','emp_depart_id','emp_sdepart_id','emp_desig_id','emp_jlid','emp_type','emp_id','start_date','end_date'));
}


public function getRemoteAttendanceList($emp_empid,$start_date,$end_date)
{
    $employee=DB::table('tbl_employee')
        ->where('tbl_employee.emp_id',$emp_empid)
        ->first(['tbl_employee.emp_name','tbl_employee.emp_empid']);
    $data='';
    $data.='<table class="table table-bordered">';
    if($start_date==$end_date){
        $data.='<caption>Employee ID : <b>'.$employee->emp_empid.'</b> <br>Employee Name : <b>'.$employee->emp_name.'</b><br>Date : <b>'.$start_date.'</b></caption>';
    }else{
        $data.='<caption>Employee ID : <b>'.$employee->emp_empid.'</b> <br>Employee Name : <b>'.$employee->emp_name.'</b><br>Date : From <b>'.$start_date.'</b> To <b>'.$end_date.'</b></caption>';
    }
    $data.='<thead><tr><th style="font-size: 12px">SL</th><th style="font-size: 12px">Clock Type</th><th style="font-size: 12px">Clock Date</th><th style="font-size: 12px">Clock Time</th><th style="font-size: 12px">WorkStation </th><th style="font-size: 12px">Action</th></tr></thead><tbody>';
    $tbl_clock=DB::table('tbl_clock')
        ->join('tbl_remotedevice','tbl_clock.clock_device','=','tbl_remotedevice.id')
        ->where(DB::raw("substr(tbl_clock.clock_time, 1, 10)"), '>=',$start_date)
        ->where(DB::raw("substr(tbl_clock.clock_time, 1, 10)"), '<=',$end_date)
        ->where('tbl_clock.clock_empid',$emp_empid)
        ->orderBy('tbl_clock.clock_time','ASC')
        ->get(['tbl_clock.*','tbl_remotedevice.name']);
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
            $data.='<td style="font-size: 12px">'.$clock->name.'</td>';
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
        ->where('tbl_clock.clock_id',$clock_id)
        ->first(['tbl_clock.*','tbl_employee.emp_machineid']);
    if(isset($clock->clock_id)){
        $card=DB::table('tbl_checkinout')->insert([
            'branch'=>1,
            'check_time'=>$clock->clock_time,
            'emp_id'=>$clock->emp_machineid,
            'device'=>$clock->clock_device,
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

    $types=EmployeeTypes::orderBy('name','asc')->get();
    $designation=DB::table('tbl_designation')->get();
    $department=DB::table('tbl_department')->get();
    $joblocation=DB::table('tbl_joblocation')->get();

    $osd_attendance=$this->filterOSDAttendanceEmployee('0','0','0','0','0','0','','');

    $data=$this->getOSDAttendanceRow($osd_attendance,'1');
    $data.=$this->getOSDAttendanceRow($osd_attendance,'2');
    $title='OSD Attendance Application';

    return view('Admin.attendance.osdAttendance',compact('id','mainlink','sublink','Adminminlink','adminsublink','types','joblocation','department','designation','data','title'));
}

public function osdAttendanceSearch($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type,$emp_id,$start_date,$end_date)
{
    $id =   Auth::guard('admin')->user();
    $mainlink = $this->adminmainmenu();
    $sublink = $this->adminsubmenu();
    $Adminminlink = $this->adminlink();
    $adminsublink = $this->adminsublink();

    $types=EmployeeTypes::orderBy('name','asc')->get();
    $designation=DB::table('tbl_designation')->get();
    $department=DB::table('tbl_department')->get();
    $joblocation=DB::table('tbl_joblocation')->get();
    
    //todays all employee add (start)//
    $osd_attendance=$this->filterOSDAttendanceEmployee($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type,$emp_id,$start_date,$end_date);
    
    $data=$this->getOSDAttendanceRow($osd_attendance,'1');
    $data.=$this->getOSDAttendanceRow($osd_attendance,'2');
    $title='OSD Attendance Application';

    return view('Admin.attendance.osdAttendance',compact('id','mainlink','sublink','Adminminlink','adminsublink','types','joblocation','department','designation','data','emp_depart_id','emp_sdepart_id','emp_desig_id','emp_jlid','emp_type','start_date','end_date','title'));
}

public function pendingOSDAttendance()
{
    $id =   Auth::guard('admin')->user();
    $mainlink = $this->adminmainmenu();
    $sublink = $this->adminsubmenu();
    $Adminminlink = $this->adminlink();
    $adminsublink = $this->adminsublink();

    $types=EmployeeTypes::orderBy('name','asc')->get();
    $designation=DB::table('tbl_designation')->get();
    $department=DB::table('tbl_department')->get();
    $joblocation=DB::table('tbl_joblocation')->get();

    $osd_attendance=$this->filterOSDAttendanceEmployee('0','0','0','0','0','0','','');

    $data=$this->getOSDAttendanceRow($osd_attendance,'0');

    $title='Pending OSD Attendance Application';

    return view('Admin.attendance.osdAttendance',compact('id','mainlink','sublink','Adminminlink','adminsublink','types','joblocation','department','designation','data','title'));
}

public function pendingOSDAttendanceSearch($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type,$emp_id,$start_date,$end_date)
{
    $id =   Auth::guard('admin')->user();
    $mainlink = $this->adminmainmenu();
    $sublink = $this->adminsubmenu();
    $Adminminlink = $this->adminlink();
    $adminsublink = $this->adminsublink();

    $types=EmployeeTypes::orderBy('name','asc')->get();
    $designation=DB::table('tbl_designation')->get();
    $department=DB::table('tbl_department')->get();
    $joblocation=DB::table('tbl_joblocation')->get();
    
    //todays all employee add (start)//
    $osd_attendance=$this->filterOSDAttendanceEmployee($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type,$emp_id,$start_date,$end_date);
    
    $data=$this->getOSDAttendanceRow($osd_attendance,'0');
    $title='Pending OSD Attendance Application';

    return view('Admin.attendance.osdAttendance',compact('id','mainlink','sublink','Adminminlink','adminsublink','types','joblocation','department','designation','data','emp_depart_id','emp_sdepart_id','emp_desig_id','emp_jlid','emp_type','start_date','end_date','title'));
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
            'osd_uniquecode'=>$this->uniquecode(15,'OSD'.date('Y-'),'osd_uniquecode','tbl_osdattendance'),
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