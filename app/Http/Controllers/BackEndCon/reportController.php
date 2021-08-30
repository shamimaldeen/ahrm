<?php

namespace App\Http\Controllers\BackEndCon;

use Illuminate\Http\Request;
use App\Http\Controllers\BackEndCon\Controller;
use App\Salary;
use App\Insurance;
use App\TotalWorkingHours;
use App\EmployeeTypes;
use App\Payroll;
use App\PayrollSummery;
use App\Employee;
use Auth;
use Session;
use DB;
use Route;
use URL;
use DateTime;
use App\Http\Controllers\BackEndCon\attendanceController;
use App\Http\Controllers\BackEndCon\otController;
use App\Http\Controllers\BackEndCon\leaveInfoController;

class reportController extends Controller
{

    public function tax_report_general()
    {
        $tax = PayrollSummery::orderBy('id', 'ASC')
                    // ->join('tbl_emp')
                    ->where('payroll_date_from', '2021-04-01')
                    ->where('payroll_date_to', '2021-04-30')
                    ->get();
        // return $tax; exit();
        
        return view('Admin.report.tax_report', compact('tax'));
    }

public function index(){
    $id =   Auth::guard('admin')->user();
    $mainlink = $this->adminmainmenu();
    $sublink = $this->adminsubmenu();
    $Adminminlink = $this->adminlink();
    $adminsublink = $this->adminsublink();

    return view('Admin.report.index',compact('id','mainlink','sublink','Adminminlink','adminsublink'));
}

public function reportEmployee($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type)
{
    $data='<option value="0">Select</option>';
    //dd($data);


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
        ->groupBy('tbl_employee.emp_machineid')
        ->get(['tbl_employee.emp_id','tbl_employee.emp_machineid','tbl_employee.emp_name']);


    if(isset($employee[0])){
        foreach ($employee as $emp) {
            $data.='<option value="'.$emp->emp_id.'">'.$emp->emp_name.'</option>';
        }
    }
    return $data;
}

public function searchEmployee($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type,$emp_empid)
{
    $data='';

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
        ->where('tbl_employee.emp_empid','like', '%'.trim($emp_empid).'%')
        ->groupBy('tbl_employee.emp_machineid')
        ->get(['tbl_employee.emp_id','tbl_employee.emp_machineid','tbl_employee.emp_name']);
    if(isset($employee[0])){
        foreach ($employee as $emp) {
            $data.='<option value="'.$emp->emp_id.'">'.$emp->emp_name.'</option>';
        }
    }
    if(strlen($data)>1){
        return $data;
    }else{
        return '0';
    }
}

public function getReport($title_name){
    $id =   Auth::guard('admin')->user();
    $mainlink = $this->adminmainmenu();
    $sublink = $this->adminsubmenu();
    $Adminminlink = $this->adminlink();
    $adminsublink = $this->adminsublink();
    $types=EmployeeTypes::orderBy('name','asc')->get();
    $designation=DB::table('tbl_designation')->get();
    $department=DB::table('tbl_department')->get();
    $joblocation=DB::table('tbl_joblocation')->get();
    $titleArray = array(
        'employee-details' => 'Employee Details Report', 
        'attendance' => 'Attendance Report', 
        'totalWorkingHour' => 'Total Working Hour Report', 
        'delay' => 'Delay Entry Report', 
        'late' => 'Late Exit Report', 
        'ot' => 'OT Report', 
        'nc-ot' => 'NC OT Report', 
        'shift' => 'Shift Report', 
        'leave-application' => 'Leave Application report', 
        'hr-tickets' => 'HR Tickets Report', 
        'no-show-no-entry' => 'No Show/No Entry Report',
        'exited-employee-archive' => 'Exited Employee Archive Report', 
        'annual-leave' => 'Annual Leave Report', 
        'short-leave' => 'Short Leave Report',
        'casual-leave' => 'Casual Leave Report',
        'compensatory-leave' => 'Compensatory Leave Report',
        'sick-leave' => 'Sick Leave Report',
        'food' => 'Food Report',
        'leave-taken-status' => 'Leave Taken Status Report',
        'employee-insurance-data' => 'Employee Insurance Data Report',
        );
    $title=$titleArray[$title_name];

  //  dd($title);
    if(isset($title)){
        $link=explode('/',(str_replace(url('/'), '', url()->current())))[2];
        $report='';
        if($id->suser_level=="3" && $title_name!="attendance"){
            return redirect('report');
        }
       
        return view('Admin.report.report',compact('id','mainlink','sublink','Adminminlink','adminsublink','types','designation','department','joblocation','title','link','report'));
    }else{
        return redirect('report');
    }
}

public function getStartDate($dateRange)
{
    $dateRange=explode(' - ',$dateRange);
    $start_date=explode('/',$dateRange[0]);
    return $start_date=$start_date[2].'-'.$start_date[1].'-'.$start_date[0];
}

public function getEndDate($dateRange)
{
    $dateRange=explode(' - ',$dateRange);
    $end_date=explode('/',$dateRange[1]);
    return $end_date=$end_date[2].'-'.$end_date[1].'-'.$end_date[0];
}

public function getDepartment($emp_depart_id,$emp_id)
{
    return $department=DB::table('tbl_department')
        ->join('tbl_employee','tbl_employee.emp_depart_id','=','tbl_department.depart_id')
        ->when($emp_depart_id>0, function($query) use ($emp_depart_id){
            return $query->where('tbl_department.depart_id', $emp_depart_id);
        })
        ->when($emp_id>0, function($query) use ($emp_id){
            return $query->where('tbl_employee.emp_id', $emp_id);
        })
        ->groupBy('tbl_department.depart_id')
        ->get();
}

public function getReportTitle($var,$page_title)
{
    $emp_depart_id=$var['emp_depart_id'];
    $emp_sdepart_id=$var['emp_sdepart_id'];
    $emp_desig_id=$var['emp_desig_id'];
    $emp_jlid=$var['emp_jlid'];
    $emp_type=$var['emp_type'];
    $emp_id=$var['emp_id'];
    $start_date=$var['start_date'];
    $end_date=$var['end_date'];

    $title = array(
        '1' => 'Department : ', 
        '2' => 'Sub-Department : ', 
        '3' => 'Designation : ', 
        '4' => 'Jpb Location : ', 
        '5' => 'Employee Type : ', 
        '6' => 'Employee Name : ', 
    );

    $var[1]=$this->getDepartmentName($emp_depart_id);
    $var[2]=$this->getSubDepartmentName($emp_sdepart_id);
    $var[3]=$this->getDesignationName($emp_desig_id);
    $var[4]=$this->getLocationName($emp_jlid);
    $var[5]=$this->getEmployeeType($emp_type);
    $var[6]=$this->getSeniorName($emp_id);

    $count=0;
    $data='';
    $data.=$page_title.' <br><br><font style="font-weight:normal;font-size:14px">';
    for ($i=1; $i <=6 ; $i++) {
        if(strlen($var[$i])>1){
            $count++;
            if($count=="1"){
                $data.=$title[$i].$var[$i];
            }else{
                $data.=' , '.$title[$i].$var[$i];
            }
        }
    }

    if($start_date==$end_date){
        $data.=' ( Date : '.$start_date.' )';
    }else{
        $data.=' ( Date From : '.$start_date.' To : '.$end_date.' )</font>';
    }

    return $data;
}

public function backgroundColor($count)
{
    $backgroundColor = array(
        '0' => 'rgba(255, 99, 132, 0.2)', 
        '1' => 'rgba(255, 159, 64, 0.2)', 
        '2' => 'rgba(255, 99, 132, 0.2)', 
        '3' => 'rgba(255, 205, 86, 0.2)', 
        '4' => 'rgba(75, 192, 192, 0.2)', 
        '5' => 'rgba(54, 162, 235, 0.2)', 
        '6' => 'rgba(153, 102, 255, 0.2)', 
    );

    $chart_backgroundColor='';
    for ($i=0; $i <=$count ; $i++) {
        if($i<7){
            $chart_backgroundColor.='"'.$backgroundColor[$i].'",';
        }else{
            $chart_backgroundColor.='"'.$backgroundColor[$i%7].'",';
        }
    }

    return $chart_backgroundColor;
}

public function borderColor($count)
{
    $borderColor = array(
        '0' => 'rgb(201, 203, 207)', 
        '1' => 'rgb(255, 99, 132)', 
        '2' => 'rgb(255, 159, 64)', 
        '3' => 'rgb(255, 205, 86)', 
        '4' => 'rgb(75, 192, 192)', 
        '5' => 'rgb(54, 162, 235)', 
        '6' => 'rgb(153, 102, 255)', 
    );

    $chart_borderColor='';
    for ($i=0; $i <=$count ; $i++) {
        if($i<7){
            $chart_borderColor.='"'.$borderColor[$i].'",';
        }else{
            $chart_borderColor.='"'.$borderColor[$i%7].'",';
        }
    }

    return $chart_borderColor;
}

public function reportSubmit(Request $request)
{
    // return $request; exit();
    // return $request->dateRange; exit();
    $condition=$request->status_condition;

    $id =   Auth::guard('admin')->user();
    $mainlink = $this->adminmainmenu();
    $sublink = $this->adminsubmenu();
    $Adminminlink = $this->adminlink();
    $adminsublink = $this->adminsublink();
    $types=EmployeeTypes::orderBy('name','asc')->get();
    $designation=DB::table('tbl_designation')->get();
    $department=DB::table('tbl_department')->get();
    $joblocation=DB::table('tbl_joblocation')->get();
    $titleArray = array(
        'employee-details' => 'Employee Details Report', 
        'attendance' => 'Attendance Report', 
        'totalWorkingHour' => 'Total Working Hour Report', 
        'delay' => 'Delay Entry Report', 
        'late' => 'Late Exit Report', 
        'ot' => 'OT Report', 
        'nc-ot' => 'NC OT Report', 
        'shift' => 'Shift Report', 
        'leave-application' => 'Leave Application report', 
        'hr-tickets' => 'HR Tickets Report', 
        'no-show-no-entry' => 'No Show/No Entry Report',
        'exited-employee-archive' => 'Exited Employee Archive Report', 
        'annual-leave' => 'Annual Leave Report', 
        'short-leave' => 'Short Leave Report',
        'casual-leave' => 'Casual Leave Report',
        'compensatory-leave' => 'Compensatory Leave Report',
        'sick-leave' => 'Sick Leave Report',
        'food' => 'Food Report',
        'leave-taken-status' => 'Leave Taken Status Report',
        'employee-insurance-data' => 'Employee Insurance Data Report'
        );
    $title=$titleArray[$request->link];

    $var=array(
        'link' => $request->link, 
        'emp_depart_id' => $request->emp_depart_id, 
        'emp_sdepart_id' => $request->emp_sdepart_id, 
        'emp_desig_id' => $request->emp_desig_id, 
        'emp_jlid' => $request->emp_jlid, 
        'emp_type' => $request->emp_type, 
        'emp_id' => $request->emp_id, 
        'status_condition' => $request->status_condition, 
        'inactive_datetime' => $request->inactive_datetime,
        'start_date' => $request->start_date,
        'end_date' => $request->end_date, 
    );

    //dd($var);

    $functionArray=array(
        'employee-details' => 'employeeDetailsReport', 
        'attendance' => 'attendanceReport', 
        'totalWorkingHour' => 'totalWorkingHourReport', 
        'delay' => 'delayEntryReport', 
        'late' => 'lateExitReport', 
        'ot' => 'OTReport', 
        'nc-ot' => 'NCOTReport', 
        'shift' => 'shiftReport', 
        'leave-application' => 'leaveApplicationReport', 
        'hr-tickets' => 'HRTicketsReport', 
        'no-show-no-entry' => 'NoShowNoEntryReport', 
        'exited-employee-archive' => 'exitedEmployeeArchiveReport', 
        'annual-leave' => 'annualLeaveReport', 
        'short-leave' => 'shortLeaveReport',
        'casual-leave' => 'casualLeaveReport',
        'compensatory-leave' => 'compensatoryLeaveReport',
        'sick-leave' => 'sickLeaveReport',
        'food' => 'foodReport',
        'leave-taken-status' => 'leaveTakenStatusReport',
        'employee-insurance-data' => 'employeeInsuranceDataReport',
    );


    $report=$this->randerLink($functionArray[$request->link],$var);

   // dd($report);
    $link=$request->link;
    $start_date=$var['start_date'];
    $end_date=$var['end_date'];
    return view('Admin.report.report',compact('id','mainlink','sublink','Adminminlink','adminsublink','types','designation','department','joblocation','title','link','report','var', 'start_date', 'end_date', 'condition'));
}

public function randerLink($link,$var)
{
   return $this->$link($var);
}

public function totalEmployee($var,$d,$emp_status)
{
    $emp_depart_id=$var['emp_depart_id']; 
    $emp_sdepart_id=$var['emp_sdepart_id']; 
    $emp_desig_id=$var['emp_desig_id']; 
    $emp_jlid=$var['emp_jlid']; 
    $emp_type=$var['emp_type']; 
    $emp_id=$var['emp_id'];

    return $totalEmployee=DB::table('tbl_employee')
        ->where(['emp_depart_id'=>$d->depart_id,'emp_status'=>$emp_status])
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
        ->count();
}

public function employeeDetailsReport($var)
{
    $emp_depart_id=$var['emp_depart_id']; 
    $emp_sdepart_id=$var['emp_sdepart_id']; 
    $emp_desig_id=$var['emp_desig_id']; 
    $emp_jlid=$var['emp_jlid']; 
    $emp_type=$var['emp_type']; 
    $emp_id=$var['emp_id'];

    $thead='<tr>
            <th>SL</th>
            <th>Employee ID</th>
            <th>Employee Name</th>
            <th>Employee Type</th>
            <th>Phone No</th>
            <th>Date Of Birth</th>
            <th>Country</th>
            <th>Designation</th>
            <th>Department</th>
            <th>Sub-Department</th>
            <th>Blood Group</th>
            <th>Education</th>
            <th>Weekend</th>
            <th>Vehicle</th>
            <th>Work Hour</th>
            <th>OT Entitlement</th>
            <th>Shift</th>
            <th>E-Mail</th>
            <th>Location</th>
            <th>Join Date</th>
            <th>Confirm Date</th>
            <th>Emergency Contact</th>
            <th>Present Address</th>
            <th>Permanent Address</th>
            <th>Face ID</th>
            <th>TIIN No</th>
            <th>BU Code</th>
            <th>NID No.</th>
            <th>N+1 Name</th>
            <th>N+1 ID</th>
            <th>Auth Person</th>
            <th>Auth Person ID</th>
            <th>Status</th>
          </tr>';

    $employeeDetails=DB::table('tbl_employee')
        ->join('tbl_salary','tbl_employee.emp_id','=','tbl_salary.emp_id')
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
        ->where('tbl_employee.emp_status','1')
        ->orderBy('tbl_employee.emp_name','ASC')
        ->get(['tbl_employee.*','tbl_salary.tin_no','tbl_salary.bu_code']);

    $data='';
    if(isset($employeeDetails[0])){
    $c=0;
        foreach ($employeeDetails as $ed){
        $c++;
         $data.='<tr>
            <td>'.$c.'</td>
            <td>'.str_replace(' ','',$ed->emp_empid).'</td>
            <td><a href="'.URL::to('employee-details').'/'.$ed->emp_id.'/view">'.$ed->emp_name.'</a></td>
            <td>'.$this->getTypeName($ed->emp_type).'</td>
            <td>'.$ed->emp_phone.'</td>
            <td>'.(($ed->emp_dob!="1970-01-01") ? $ed->emp_dob : '').'</td>
            <td>'.$this->getCountryName($ed->emp_country).'</td>
            <td>'.$this->getOnlyDesignationName($ed->emp_desig_id).'</td>
            <td>'.$this->getDepartmentName($ed->emp_depart_id).'</td>
            <td>'.$this->getSubDepartmentName($ed->emp_sdepart_id).'</td>
            <td>'.$ed->emp_blgrp.'</td>
            <td>'.$ed->emp_education.'</td>
            <td>'.$this->weekend($ed->emp_wknd).'</td>
            <td>'.$this->YesOrNo($ed->emp_vehicle).'</td>
            <td>'.explode(' ',$this->getWorkHour($ed->emp_workhr))[0].'</td>
            <td>'.$this->YesOrNo($ed->emp_otent).'</td>
            <td>'.$this->getShiftInfo($ed->emp_shiftid).'</td>
            <td>'.$ed->emp_email.'</td>
            <td>'.$this->getLocationName($ed->emp_jlid).'</td>
            <td>'.$ed->emp_joindate.'</td>
            <td>'.$ed->emp_confirmdate.'</td>
            <td>'.$ed->emp_emjcontact.'</td>
            <td>'.$ed->emp_crntaddress.'</td>
            <td>'.$ed->emp_prmntaddress.'</td>
            <td>'.$ed->emp_machineid.'</td>
            <td>'.$ed->tin_no.'</td>
            <td>'.$ed->bu_code.'</td>
            <td>'.$ed->emp_nid.'</td>
            <td>'.$this->emp_name($ed->emp_seniorid).'</td>
            <td>'.$this->emp_empid($ed->emp_seniorid).'</td>
            <td>'.$this->emp_name($ed->emp_authperson).'</td>
            <td>'.$this->emp_empid($ed->emp_authperson).'</td>
            <td>'.$this->status($ed->emp_status).'</td>
          </tr>';
        }
    }

    
    $chart_num_datasets=2;
    $chart_datasets = array(
        '1' => 'Employee Chart',
    );
    $chart_levels_array=Array();
    $chart_data_array=Array();
    $chart_backgroundColor_array=Array();
    $chart_borderColor_array=Array();

    for ($i=0; $i < $chart_num_datasets ; $i++) {
        $chart_levels='';
        $chart_data='';
        $department=$this->getDepartment($var['emp_depart_id'],$var['emp_id']);
        foreach ($department as $d) {
            $total=$this->totalEmployee($var,$d,'1');
            $chart_levels.='"'.$d->depart_name.'"'.',';
            $chart_data.=$total.',';
        }

        array_push($chart_levels_array, $i,$chart_levels);
        array_push($chart_data_array, $i,$chart_data);
        array_push($chart_backgroundColor_array,$i,$this->backgroundColor(count($department)));
        array_push($chart_borderColor_array,$i,$this->borderColor(count($department)));
    }

    $info=array(
        'chart_show'=>true,
        'page_title'=>$this->getReportTitle($var,'Employee Details Report'),
        'chart_short_title'=>'Employee Details Chart',
        'chart_title'=>$this->getReportTitle($var,'Employee Details Chart'),
        'chart_num_datasets'=>$chart_num_datasets,
        'chart_datasets'=>$chart_datasets,
        'chart_levels'=>$chart_levels_array,
        'chart_data'=>$chart_data_array,
        'chart_backgroundColor'=>$chart_backgroundColor_array,
        'chart_borderColor'=>$chart_borderColor_array,
    );

    return view('Admin.report.view',compact('thead','data','info'));
}


public function filterAttendanceEmployee($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type,$emp_id)
{
    return $card=DB::table('tbl_employee')
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
        ->groupBy('tbl_employee.emp_machineid')
        ->get(['tbl_employee.*']);
}

public function totalWorkingHours($emp,$date,$shift_info)
{
    $Attendance=new attendanceController();
    
    $card_count=DB::table('kqz_card')
        ->join('kqz_devinfo','kqz_card.DevID','=','kqz_devinfo.DevID')
        ->where('kqz_card.EmployeeID',$emp->EmployeeID)
        ->where('kqz_card.DevID','<','7')
        ->where('kqz_devinfo.DevType','1')
        ->where(DB::raw("substr(kqz_card.CardTime, 1, 10)"), '=',$date)
        ->count();
    if($card_count>0){}else{
        $card_count=DB::table('kqz_card')
        ->join('tbl_remotedevice','kqz_card.DevID','=','tbl_remotedevice.DevID')
        ->where('kqz_card.EmployeeID',$emp->EmployeeID)
        ->where('kqz_card.DevID','>','6')
        ->where('tbl_remotedevice.DevType','1')
        ->where(DB::raw("substr(kqz_card.CardTime, 1, 10)"), '=',$date)
        ->count();
    }
    $twh=date("H:i:s",strtotime('00:00:00'));

    for ($i=0; $i < $card_count; $i++) {

        if($i=="0"){
            //entry time calculation (start)//
            $entry_time=DB::table('kqz_card')
                ->join('kqz_devinfo','kqz_card.DevID','=','kqz_devinfo.DevID')
                ->where('kqz_card.EmployeeID',$emp->EmployeeID)
                ->where('kqz_card.DevID','<','7')
                ->where('kqz_devinfo.DevType','1')
                ->where(DB::raw("substr(kqz_card.CardTime, 1, 10)"), '=',$date)
                ->orderBy('kqz_card.CardTime','ASC')
                ->groupBy(DB::raw("substr(kqz_card.CardTime, 12, 5)"))
                ->first(['kqz_card.*']);
            if(isset($entry_time->CardTime)){}else{
                $entry_time=DB::table('kqz_card')
                ->join('tbl_remotedevice','kqz_card.DevID','=','tbl_remotedevice.DevID')
                ->where('kqz_card.EmployeeID',$emp->EmployeeID)
                ->where('kqz_card.DevID','>','6')
                ->where('tbl_remotedevice.DevType','1')
                ->where(DB::raw("substr(kqz_card.CardTime, 1, 10)"), '=',$date)
                ->orderBy('kqz_card.CardTime','ASC')
                ->groupBy(DB::raw("substr(kqz_card.CardTime, 12, 5)"))
                ->first(['kqz_card.*']);
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
            $entry_time=DB::table('kqz_card')
                ->join('kqz_devinfo','kqz_card.DevID','=','kqz_devinfo.DevID')
                ->where('kqz_card.EmployeeID',$emp->EmployeeID)
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
                ->where('kqz_card.EmployeeID',$emp->EmployeeID)
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
                }
            }
            
            //entry time calculation (end)//
        }

        //exit time calculation (start)//
        if(isset($entry_time->CardTime)){
            $exit_time=DB::table('kqz_card')
                ->join('kqz_devinfo','kqz_card.DevID','=','kqz_devinfo.DevID')
                ->where('kqz_card.EmployeeID',$emp->EmployeeID)
                ->where('kqz_card.DevID','<','7')
                ->where('kqz_card.CardTime','>',$entry_time->CardTime)
                ->where('kqz_devinfo.DevType','0')
                ->where(DB::raw("substr(kqz_card.CardTime, 1, 10)"), '=',$date)
                ->orderBy('kqz_card.CardTime','ASC')
                ->groupBy(DB::raw("substr(kqz_card.CardTime, 12, 5)"))
                ->first(['kqz_card.*']);
            if(isset($exit_time->CardTime)){}else{
                $exit_time=DB::table('kqz_card')
                ->join('tbl_remotedevice','kqz_card.DevID','=','tbl_remotedevice.DevID')
                ->where('kqz_card.EmployeeID',$emp->EmployeeID)
                ->where('kqz_card.DevID','>','6')
                ->where('kqz_card.CardTime','>',$entry_time->CardTime)
                ->where('tbl_remotedevice.DevType','0')
                ->where(DB::raw("substr(kqz_card.CardTime, 1, 10)"), '=',$date)
                ->orderBy('kqz_card.CardTime','ASC')
                ->groupBy(DB::raw("substr(kqz_card.CardTime, 12, 5)"))
                ->first(['kqz_card.*']);
            }
            if(isset($exit_time->CardTime)){}else{
                $exit_time=DB::table('kqz_card')
                    ->join('kqz_devinfo','kqz_card.DevID','=','kqz_devinfo.DevID')
                    ->where('kqz_card.EmployeeID',$emp->EmployeeID)
                    ->where('kqz_card.DevID','<','7')
                    ->where('kqz_card.CardTime','>',$entry_time->CardTime)
                    ->where(DB::raw("substr(kqz_card.CardTime, 1, 10)"), '=',date('Y-m-d',strtotime($date.' +1 days')))
                    ->orderBy('kqz_card.CardTime','ASC')
                    ->groupBy(DB::raw("substr(kqz_card.CardTime, 12, 5)"))
                    ->first(['kqz_card.*','kqz_devinfo.DevType']);
                if(isset($exit_time->CardTime)){}else{
                    $exit_time=DB::table('kqz_card')
                    ->join('tbl_remotedevice','kqz_card.DevID','=','tbl_remotedevice.DevID')
                    ->where('kqz_card.EmployeeID',$emp->EmployeeID)
                    ->where('kqz_card.DevID','>','6')
                    ->where('kqz_card.CardTime','>',$entry_time->CardTime)
                    ->where(DB::raw("substr(kqz_card.CardTime, 1, 10)"), '=',date('Y-m-d',strtotime($date.' +1 days')))
                    ->orderBy('kqz_card.CardTime','ASC')
                    ->groupBy(DB::raw("substr(kqz_card.CardTime, 12, 5)"))
                    ->first(['kqz_card.*','tbl_remotedevice.DevType']);
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
            }
        }
        
        //exit time calculation (end)//
    }

    if(!isset($final_entry_time)){
        $final_entry_time=$Attendance->attendanceShortStatus($emp,$date);
    }

    if(!isset($calculate_exit_time)){
        $calculate_exit_time=$Attendance->attendanceShortStatus($emp,$date);
        $twh='00:00:00';
    }

    $osd = $Attendance->totalOSDDuration($emp->emp_id,$date,$date);
    $total_osd = strtotime($Attendance->totalOSDDuration($emp->emp_id,$date,$date))-strtotime('00:00:00');
    $twh = date("H:i:s",strtotime($twh)+$total_osd);
    
    $night=0;    
    if(strtotime($final_entry_time)>=strtotime('20:00:00') && strtotime($twh)>=strtotime('04:00:00'))
    {
        $night++;
    }

    $data = array(
        'total_osd' => $osd, 
        'twh' => $twh, 
        'final_entry_time' => $final_entry_time, 
        'delay' => '00:00:00', 
        'calculate_exit_time' => $calculate_exit_time, 
        'late' => '00:00:00', 
        'night' => $night, 
    );
    return $data;
}

public function present($var,$d)
{
    $emp_depart_id=$var['emp_depart_id'];
    $emp_sdepart_id=$var['emp_sdepart_id'];
    $emp_desig_id=$var['emp_desig_id'];
    $emp_jlid=$var['emp_jlid'];
    $emp_type=$var['emp_type'];
    $emp_id=$var['emp_id'];
    $start_date=$var['start_date'];
    $end_date=$var['end_date'];

    $present=TotalWorkingHours::where('present',1)
                ->where('date','<=',$start_date)
                ->where('date','>=',$start_date)
                ->count();
    return $present;
}

public function absent($var,$d)
{
    $start_date=$var['start_date'];
    $end_date=$var['end_date'];

    $absent=TotalWorkingHours::where('present',0)
                ->where('date','<=',$start_date)
                ->where('date','>=',$start_date)
                ->count();
    return $absent;
}

public function leave($var,$d)
{
    $emp_depart_id=$var['emp_depart_id'];
    $emp_sdepart_id=$var['emp_sdepart_id'];
    $emp_desig_id=$var['emp_desig_id'];
    $emp_jlid=$var['emp_jlid'];
    $emp_type=$var['emp_type'];
    $emp_id=$var['emp_id'];
    $start_date=$var['start_date'];
    $end_date=$var['end_date'];

    $leave=DB::table('tbl_leaveapplication')
        ->join('tbl_employee','tbl_leaveapplication.leave_empid','=','tbl_employee.emp_id')
        ->where(['tbl_employee.emp_depart_id'=>$d->depart_id,'emp_status'=>'1','leave_status'=>'1'])
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
        ->where(DB::raw("substr(tbl_leaveapplication.leave_start_date, 1, 10)"), '>=',$start_date)
        ->where(DB::raw("substr(tbl_leaveapplication.leave_end_date, 1, 10)"), '<=',$end_date)
        ->groupBy('tbl_leaveapplication.leave_empid')
        ->get(['tbl_leaveapplication.leave_empid']);
    return count($leave);
}

public function attendanceReport($var)
{
    $emp_depart_id=$var['emp_depart_id'];
    $emp_sdepart_id=$var['emp_sdepart_id'];
    $emp_desig_id=$var['emp_desig_id'];
    $emp_jlid=$var['emp_jlid'];
    $emp_type=$var['emp_type'];
    $emp_id=$var['emp_id'];
    $start_date=$var['start_date'];
    $end_date=$var['end_date'];

    $thead='<tr>
              <th></th>
              <th>SL</th>
              <th>Employee ID</th>
              <th>Face ID</th>
              <th>Employee Name</th>
              <th>Date</th>
              <th>Shift</th>
              <th>Entry Time</th>
              <th>Exit Time</th>
              <th>Delayed Entry</th>
              <th>Late Exit</th>
              <th>Total Working Hour</th>
              <th>Night</th>
              <th>Status</th>
              <th>Generated At</th>
            </tr>';

    $Controller=new Controller();
    $employees=$this->filterAttendanceEmployee($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type,$emp_id);

    $employeeArray = array();
    if(isset($employees[0])){
        if(isset($employees[0])){
            foreach ($employees as $key => $employee) {
                array_push($employeeArray,$employee->emp_id);
            }
        }
    }
    
    $dateRange=$this->getDateRange($start_date,$end_date);
    $attendances=TotalWorkingHours::with(['employee','shift'])
    ->whereIn('date',$dateRange)
    ->whereIn('emp_id',$employeeArray)
    ->orderBy('emp_id','asc')
    ->get();

    $data='';
    if(isset($attendances[0])){
        foreach ($attendances as $key => $attendance) {
            $data.='<tr class="gradeX" id="tr-'.$attendance->emp_id.'">
                <td></td>
                <td>'.($key+1).'</td>
                <td>'.$attendance->employee->emp_empid.'</td>
                <td>'.$attendance->employee->emp_machineid.'</td>
                <td>'.$attendance->employee->emp_name.'</td>
                <td>'.$attendance->date.'</td>
                <td>';
        if(isset($attendance->shift)){
            $data.=$attendance->shift->shift_stime.' to '.$attendance->shift->shift_etime;
        }
            $data.='</td>
                <td>';
        if(count(explode(',',$attendance->entry_time))>1){
            $data.=$Controller->attendanceStatusText($attendance->entry_time);
        }elseif($attendance->entry_time!="" && $attendance->entry_time!="00:00:00"){
            $data.=date('g:i a',strtotime($attendance->entry_time));
        }
            $data.='
                </td>
                <td>';
        if(count(explode(',',$attendance->exit_time))>1){
            $data.=$Controller->attendanceStatusText($attendance->exit_time);
        }elseif($attendance->exit_time!="" && $attendance->exit_time!="00:00:00"){
            $data.=date('g:i a',strtotime($attendance->exit_time));
        }
            $data.='
                </td>
                <td>'.$attendance->late_entry.'</td>
                <td>'.$attendance->early_exit.'</td>
                <td>'.$attendance->twh.'</td>
                <td>'.$attendance->night.'</td>
                <td>'.$Controller->attendanceStatusButton($attendance->status).'</td>
                <td>'.date('Y-m-d g:i a',strtotime($attendance->generated_at)).'</td>
              </tr>';
        }
    }

    $chart_num_datasets=8;
    $chart_datasets = array(
        '1' => 'Total Employee',
        '3' => 'Present',
        '5' => 'Absent',
        '7' => 'Leave',
    );
    $chart_levels_array=Array();
    $chart_data_array=Array();
    $chart_backgroundColor_array=Array();
    $chart_borderColor_array=Array();

    for ($i=0; $i < $chart_num_datasets ; $i++) {
        $chart_levels='';
        $chart_data='';
        $department=$this->getDepartment($var['emp_depart_id'],$var['emp_id']);
        foreach ($department as $d) {
            if($i=="0"){
                $total=$this->totalEmployee($var,$d,'1');
                $chart_levels.='"'.$d->depart_name.'"'.',';
                $chart_data.=$total.',';
            }elseif($i=="1"){
                $present=$this->present($var,$d);
                
                $chart_levels.='"'.$d->depart_name.'"'.',';
                $chart_data.=$present.',';
            }elseif($i=="2"){
                $absent=$this->absent($var,$d);

                $chart_levels.='"'.$d->depart_name.'"'.',';
                $chart_data.=$absent.',';
            }elseif($i=="3"){
                $leave=$this->leave($var,$d);

                $chart_levels.='"'.$d->depart_name.'"'.',';
                $chart_data.=$leave.',';
            }
        }

        array_push($chart_levels_array, $i,$chart_levels);
        array_push($chart_data_array, $i,$chart_data);
        array_push($chart_backgroundColor_array,$i,$this->backgroundColor(count($department)));
        array_push($chart_borderColor_array,$i,$this->borderColor(count($department)));
    }

    $info=array(
        'chart_show'=>true,
        'page_title'=>$this->getReportTitle($var,'Attendance Report'),
        'chart_short_title'=>'Attendance Chart',
        'chart_title'=>$this->getReportTitle($var,'Attendance Chart'),
        'chart_num_datasets'=>$chart_num_datasets,
        'chart_datasets'=>$chart_datasets,
        'chart_levels'=>$chart_levels_array,
        'chart_data'=>$chart_data_array,
        'chart_backgroundColor'=>$chart_backgroundColor_array,
        'chart_borderColor'=>$chart_borderColor_array,
    );
    return view('Admin.report.view',compact('thead','data','info'));
}

public function totalWorkingHourReport($var)
{
    $Controller=new Controller();
    $emp_depart_id=$var['emp_depart_id'];
    $emp_sdepart_id=$var['emp_sdepart_id'];
    $emp_desig_id=$var['emp_desig_id'];
    $emp_jlid=$var['emp_jlid'];
    $emp_type=$var['emp_type'];
    $emp_id=$var['emp_id'];
    $start_date=$var['start_date'];
    $end_date=$var['end_date'];
    $dateRange=$this->getDateRange($start_date,$end_date);

    $thead='<tr>
              <th>SL</th>
              <th>Employee ID</th>
              <th>Face ID</th>
              <th>Employee Name</th>';
    foreach ($dateRange as $date) {
        $thead.='<th>Date</th>
              <th>TWH (Time)</th>
              <th>TWH (Hour)</th>';
    }
    $thead.='<th>Total Working Hour (In Hour)</th>
             </tr>';

    $employees=$this->filterAttendanceEmployee($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type,$emp_id);
    $employeeArray = array();
    if(isset($employees[0])){
        if(isset($employees[0])){
            foreach ($employees as $key => $employee) {
                array_push($employeeArray,$employee->emp_id);
            }
        }
    }

    $data='';   
    foreach ($employees as $key => $employee) {
        $total_working_hour=0;
        $data.='<tr>
            <td>'.($key+1).'</td>
            <td>'.$employee->emp_empid.'</td>
            <td>'.$employee->emp_machineid.'</td>
            <td>'.$employee->emp_name.'</td>';

        foreach ($dateRange as $datekey => $date) {
            $data.='<td>'.$date.'</td>';

            $totalWorkingHours=TotalWorkingHours::where([
                    'emp_id'=>$employee->emp_id,
                    'date'=>$date
                ])->first();
            if(isset($totalWorkingHours->id)){
                    $working_hour=$this->timeToHours($totalWorkingHours->twh)+$this->timeToHours($totalWorkingHours->total_osd);
                    if($working_hour<=0){
                        $data.='<td>'.$Controller->attendanceStatusText($totalWorkingHours->status).'</td><td>0.00</td>';
                    }else{
                        $data.='<td>'.$totalWorkingHours->twh.'</td><td>'.$this->timeToHours($totalWorkingHours->twh).'</td>';
                    }
                    $total_working_hour+=$working_hour;
            }else{
                $data.='<td><a class="btn btn-xs btn-danger">NG</a></td>
                        <td><a class="btn btn-xs btn-danger">NG</a></td>';
            }
        }

        $data.='<td>'.$total_working_hour.'</td>
                </tr>';
    }

    $chart_num_datasets=8;
    $chart_datasets = array(
        '1' => 'Total Employee',
        '3' => 'Present',
        '5' => 'Absent',
        '7' => 'Leave',
    );
    $chart_levels_array=Array();
    $chart_data_array=Array();
    $chart_backgroundColor_array=Array();
    $chart_borderColor_array=Array();

    for ($i=0; $i < $chart_num_datasets ; $i++) {
        $chart_levels='';
        $chart_data='';
        $department=$this->getDepartment($var['emp_depart_id'],$var['emp_id']);
        foreach ($department as $d) {
            if($i=="0"){
                $total=$this->totalEmployee($var,$d,'1');
                $chart_levels.='"'.$d->depart_name.'"'.',';
                $chart_data.=$total.',';
            }elseif($i=="1"){
                $present=$this->present($var,$d);
                
                $chart_levels.='"'.$d->depart_name.'"'.',';
                $chart_data.=$present.',';
            }elseif($i=="2"){
                $absent=$this->absent($var,$d);

                $chart_levels.='"'.$d->depart_name.'"'.',';
                $chart_data.=$absent.',';
            }elseif($i=="3"){
                $leave=$this->leave($var,$d);

                $chart_levels.='"'.$d->depart_name.'"'.',';
                $chart_data.=$leave.',';
            }
        }

        array_push($chart_levels_array, $i,$chart_levels);
        array_push($chart_data_array, $i,$chart_data);
        array_push($chart_backgroundColor_array,$i,$this->backgroundColor(count($department)));
        array_push($chart_borderColor_array,$i,$this->borderColor(count($department)));
    }

    $info=array(
        'chart_show'=>true,
        'page_title'=>$this->getReportTitle($var,'Attendance Report'),
        'chart_short_title'=>'Attendance Chart',
        'chart_title'=>$this->getReportTitle($var,'Attendance Chart'),
        'chart_num_datasets'=>$chart_num_datasets,
        'chart_datasets'=>$chart_datasets,
        'chart_levels'=>$chart_levels_array,
        'chart_data'=>$chart_data_array,
        'chart_backgroundColor'=>$chart_backgroundColor_array,
        'chart_borderColor'=>$chart_borderColor_array,
    );
    return view('Admin.report.view',compact('thead','data','info'));
}

public function delayOrLateOfficeOrRemote($var,$d,$type,$attendanceType)
{
    $emp_depart_id=$var['emp_depart_id'];
    $emp_sdepart_id=$var['emp_sdepart_id'];
    $emp_desig_id=$var['emp_desig_id'];
    $emp_jlid=$var['emp_jlid'];
    $emp_type=$var['emp_type'];
    $emp_id=$var['emp_id'];
    $start_date=$var['start_date'];
    $end_date=$var['end_date'];

    return 0;
}


public function delayEntryReport($var)
{
    $Controller=new Controller();
    $emp_depart_id=$var['emp_depart_id'];
    $emp_sdepart_id=$var['emp_sdepart_id'];
    $emp_desig_id=$var['emp_desig_id'];
    $emp_jlid=$var['emp_jlid'];
    $emp_type=$var['emp_type'];
    $emp_id=$var['emp_id'];
    $start_date=$var['start_date'];
    $end_date=$var['end_date'];
    $dateRange=$this->getDateRange($start_date,$end_date);

    $thead='<tr>
              <th>SL</th>
              <th>Employee ID</th>
              <th>Face ID</th>
              <th>Employee Name</th>';
    foreach ($dateRange as $date) {
        $thead.='<th>Date</th>
              <th>Entry Time</th>
              <th>Late</th>';
    }
    $thead.='<th>Total Late</th>
             </tr>';

    $employees=$this->filterAttendanceEmployee($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type,$emp_id);
    $employeeArray = array();
    if(isset($employees[0])){
        if(isset($employees[0])){
            foreach ($employees as $key => $employee) {
                array_push($employeeArray,$employee->emp_id);
            }
        }
    }

    $data='';   
    foreach ($employees as $key => $employee) {
        $total_late=0;
        $data.='<tr>
            <td>'.($key+1).'</td>
            <td>'.$employee->emp_empid.'</td>
            <td>'.$employee->emp_machineid.'</td>
            <td>'.$employee->emp_name.'</td>';

        foreach ($dateRange as $datekey => $date) {
            $data.='<td>'.$date.'</td>';

            $totalWorkingHours=TotalWorkingHours::where([
                    'emp_id'=>$employee->emp_id,
                    'date'=>$date
                ])->first();
            if(isset($totalWorkingHours->id)){
                    $working_hour=$this->timeToHours($totalWorkingHours->twh)+$this->timeToHours($totalWorkingHours->total_osd);
                    $late=$this->timeToHours($totalWorkingHours->late_entry);
                    if($working_hour<=0){
                        $data.='<td>'.$Controller->attendanceStatusText($totalWorkingHours->status).'</td><td>'.$Controller->attendanceStatusText($totalWorkingHours->status).'</td>';
                    }else{
                        $data.='<td>'.date('g:i a',strtotime($totalWorkingHours->entry_time)).'</td><td>'.$totalWorkingHours->late_entry.'</td>';
                    }
                    $total_late+=$late;
            }else{
                $data.='<td><a class="btn btn-xs btn-danger">NG</a></td>
                        <td><a class="btn btn-xs btn-danger">NG</a></td>';
            }
        }

        $data.='<td>'.$total_late.'</td>
                </tr>';
    }


    $chart_num_datasets=2;
    $chart_datasets = array(
        '1' => 'Delay Entry',
    );
    $chart_levels_array=Array();
    $chart_data_array=Array();
    $chart_backgroundColor_array=Array();
    $chart_borderColor_array=Array();

    for ($i=0; $i < $chart_num_datasets ; $i++) {
        $chart_levels='';
        $chart_data='';
        $department=$this->getDepartment($var['emp_depart_id'],$var['emp_id']);
        foreach ($department as $d) {
            $office=$this->delayOrLateOfficeOrRemote($var,$d,'1','1');
            $remote=$this->delayOrLateOfficeOrRemote($var,$d,'1','0');
            $chart_levels.='"'.$d->depart_name.'"'.',';
            $chart_data.=$office+$remote.',';
        }

        array_push($chart_levels_array, $i,$chart_levels);
        array_push($chart_data_array, $i,$chart_data);
        array_push($chart_backgroundColor_array,$i,$this->backgroundColor(count($department)));
        array_push($chart_borderColor_array,$i,$this->borderColor(count($department)));
    }

    $info=array(
        'chart_show'=>false,
        'page_title'=>$this->getReportTitle($var,'Delay Entry Report'),
        'chart_short_title'=>'Delay Entry Chart',
        'chart_title'=>$this->getReportTitle($var,'Delay Entry Chart'),
        'chart_num_datasets'=>$chart_num_datasets,
        'chart_datasets'=>$chart_datasets,
        'chart_levels'=>$chart_levels_array,
        'chart_data'=>$chart_data_array,
        'chart_backgroundColor'=>$chart_backgroundColor_array,
        'chart_borderColor'=>$chart_borderColor_array,
    );

    return view('Admin.report.view',compact('thead','data','info'));
}

public function lateExitReport($var)
{
    $Controller=new Controller();
    $emp_depart_id=$var['emp_depart_id'];
    $emp_sdepart_id=$var['emp_sdepart_id'];
    $emp_desig_id=$var['emp_desig_id'];
    $emp_jlid=$var['emp_jlid'];
    $emp_type=$var['emp_type'];
    $emp_id=$var['emp_id'];
    $start_date=$var['start_date'];
    $end_date=$var['end_date'];
    $dateRange=$this->getDateRange($start_date,$end_date);

    $thead='<tr>
              <th>SL</th>
              <th>Employee ID</th>
              <th>Face ID</th>
              <th>Employee Name</th>';
    foreach ($dateRange as $date) {
        $thead.='<th>Date</th>
              <th>Exit Time</th>
              <th>Early Leave</th>';
    }
    $thead.='<th>Total Early Leave</th>
             </tr>';

    $employees=$this->filterAttendanceEmployee($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type,$emp_id);
    $employeeArray = array();
    if(isset($employees[0])){
        if(isset($employees[0])){
            foreach ($employees as $key => $employee) {
                array_push($employeeArray,$employee->emp_id);
            }
        }
    }

    $data='';   
    foreach ($employees as $key => $employee) {
        $total_early_leave=0;
        $data.='<tr>
            <td>'.($key+1).'</td>
            <td>'.$employee->emp_empid.'</td>
            <td>'.$employee->emp_machineid.'</td>
            <td>'.$employee->emp_name.'</td>';

        foreach ($dateRange as $datekey => $date) {
            $data.='<td>'.$date.'</td>';

            $totalWorkingHours=TotalWorkingHours::where([
                    'emp_id'=>$employee->emp_id,
                    'date'=>$date
                ])->first();
            if(isset($totalWorkingHours->id)){
                    $working_hour=$this->timeToHours($totalWorkingHours->twh)+$this->timeToHours($totalWorkingHours->total_osd);
                    $early_exit=$this->timeToHours($totalWorkingHours->early_exit);
                    if($working_hour<=0){
                        $data.='<td>'.$Controller->attendanceStatusText($totalWorkingHours->status).'</td><td>'.$Controller->attendanceStatusText($totalWorkingHours->status).'</td>';
                    }else{
                        $data.='<td>'.date('g:i a',strtotime($totalWorkingHours->entry_time)).'</td><td>'.$totalWorkingHours->early_exit.'</td>';
                    }
                    $total_early_leave+=$early_exit;
            }else{
                $data.='<td><a class="btn btn-xs btn-danger">NG</a></td>
                        <td><a class="btn btn-xs btn-danger">NG</a></td>';
            }
        }

        $data.='<td>'.$total_early_leave.'</td>
                </tr>';
    }
    
    $chart_num_datasets=2;
    $chart_datasets = array(
        '1' => 'Late Exit',
    );
    $chart_levels_array=Array();
    $chart_data_array=Array();
    $chart_backgroundColor_array=Array();
    $chart_borderColor_array=Array();

    for ($i=0; $i < $chart_num_datasets ; $i++) {
        $chart_levels='';
        $chart_data='';
        $department=$this->getDepartment($var['emp_depart_id'],$var['emp_id']);
        foreach ($department as $d) {
            $office=$this->delayOrLateOfficeOrRemote($var,$d,'0','1');
            $remote=$this->delayOrLateOfficeOrRemote($var,$d,'0','0');
            $chart_levels.='"'.$d->depart_name.'"'.',';
            $chart_data.=$office+$remote.',';
        }

        array_push($chart_levels_array, $i,$chart_levels);
        array_push($chart_data_array, $i,$chart_data);
        array_push($chart_backgroundColor_array,$i,$this->backgroundColor(count($department)));
        array_push($chart_borderColor_array,$i,$this->borderColor(count($department)));
    }

    $info=array(
        'chart_show'=>false,
        'page_title'=>$this->getReportTitle($var,'Late Exit Report'),
        'chart_short_title'=>'Late Exit Chart',
        'chart_title'=>$this->getReportTitle($var,'Late Exit Chart'),
        'chart_num_datasets'=>$chart_num_datasets,
        'chart_datasets'=>$chart_datasets,
        'chart_levels'=>$chart_levels_array,
        'chart_data'=>$chart_data_array,
        'chart_backgroundColor'=>$chart_backgroundColor_array,
        'chart_borderColor'=>$chart_borderColor_array,
    );

    return view('Admin.report.view',compact('thead','data','info'));
}

public function filterOTEmployee($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type,$emp_id,$start_date,$end_date)
{
    $employee=DB::table('kqz_card')
        ->join('kqz_employee','kqz_card.EmployeeID','=','kqz_employee.EmployeeID')
        ->join('tbl_employee','kqz_employee.EmployeeCode','=','tbl_employee.emp_machineid')
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
        ->where(DB::raw("substr(kqz_card.CardTime, 1, 10)"), '>=',$start_date)
        ->where(DB::raw("substr(kqz_card.CardTime, 1, 10)"), '<=',$end_date)
        ->groupBy('kqz_card.EmployeeID')
        ->orderBy('tbl_employee.emp_name','asc')
        ->get(['kqz_card.*','tbl_employee.*','kqz_employee.*']);
    return $employee;
}

public function totalOT($var,$d)
{
    $emp_depart_id=$var['emp_depart_id'];
    $emp_sdepart_id=$var['emp_sdepart_id'];
    $emp_desig_id=$var['emp_desig_id'];
    $emp_jlid=$var['emp_jlid'];
    $emp_type=$var['emp_type'];
    $emp_id=$var['emp_id'];
    $start_date=$var['start_date'];
    $end_date=$var['end_date'];
    $ot=date("H:i:s",strtotime('00:00:00'));
    $otController=new otController;

    $card=DB::table('kqz_card')
        ->join('kqz_employee','kqz_card.EmployeeID','=','kqz_employee.EmployeeID')
        ->join('tbl_employee','kqz_employee.EmployeeCode','=','tbl_employee.emp_machineid')
        ->where('tbl_employee.emp_depart_id', $d->depart_id)
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
        ->where(DB::raw("substr(kqz_card.CardTime, 1, 10)"), '>=',$start_date)
        ->where(DB::raw("substr(kqz_card.CardTime, 1, 10)"), '<=',$end_date)
        ->groupBy(DB::raw("substr(kqz_card.CardTime, 1, 10)"))
        ->orderBy('kqz_card.CardTime','asc')
        ->get(['kqz_card.EmployeeID','kqz_card.CardTime','tbl_employee.*']);
    //employee loop (start)//
    foreach ($card as $emp) {
        $singleDayOT = strtotime($otController->singleDayOT($emp->EmployeeID,substr($emp->CardTime,0,10)))-strtotime("00:00:00");
        $ot = date("H:i:s",strtotime($ot)+$singleDayOT);
    }

    return $ot;
}

public function OTReport($var)
{
    $Controller=new Controller();
    $emp_depart_id=$var['emp_depart_id'];
    $emp_sdepart_id=$var['emp_sdepart_id'];
    $emp_desig_id=$var['emp_desig_id'];
    $emp_jlid=$var['emp_jlid'];
    $emp_type=$var['emp_type'];
    $emp_id=$var['emp_id'];
    $start_date=$var['start_date'];
    $end_date=$var['end_date'];
    $dateRange=$this->getDateRange($start_date,$end_date);

    $thead='<tr>
              <th>SL</th>
              <th>Employee ID</th>
              <th>Face ID</th>
              <th>Employee Name</th>';
    foreach ($dateRange as $date) {
        $thead.='<th>Date</th>
              <th>OT (Hours)</th>';
    }
    $thead.='<th>Total OT (Hours)</th>
             </tr>';

    $employees=$this->filterAttendanceEmployee($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type,$emp_id);
    $employeeArray = array();
    if(isset($employees[0])){
        if(isset($employees[0])){
            foreach ($employees as $key => $employee) {
                array_push($employeeArray,$employee->emp_id);
            }
        }
    }

    $data='';
    $grand_ot_total=0;   
    foreach ($employees as $key => $employee) {
        $total_ot=0;
        $data.='<tr>
            <td>'.($key+1).'</td>
            <td>'.$employee->emp_empid.'</td>
            <td>'.$employee->emp_machineid.'</td>
            <td>'.$employee->emp_name.'</td>';

        foreach ($dateRange as $datekey => $date) {
            $data.='<td>'.$date.'</td>';

            $assign=DB::table('tbl_otapplication')->where([
                'otapp_empid'=>$employee->emp_id,
                'otapp_perdate'=>$date
            ])->first();
            $ot=0;
            if(isset($assign->otapp_id)){
                $ot=$assign->otapp_totalhrs;
            }
            $total_ot+=$ot;
            $grand_ot_total+=$ot;
            $data.='<td>'.$ot.'</td>';
            // $totalWorkingHours=TotalWorkingHours::where([
            //         'emp_id'=>$employee->emp_id,
            //         'date'=>$date
            //     ])->first();
            // if(isset($totalWorkingHours->id)){
            //         $working_hour=$this->timeToHours($totalWorkingHours->twh)+$this->timeToHours($totalWorkingHours->total_osd);
            //         $ot=$this->timeToHours($totalWorkingHours->ot);
            //         if($working_hour<=0){
            //             $data.='<td>'.$Controller->attendanceStatusText($totalWorkingHours->status).'</td><td>'.$Controller->attendanceStatusText($totalWorkingHours->status).'</td>';
            //         }else{
            //             $data.='<td>'.$totalWorkingHours->twh.'</td><td>'.$totalWorkingHours->ot.'</td>';
            //         }
            //         $total_ot+=$ot;
            //         $grand_ot_total+=$ot;
            // }else{
            //     $data.='<td><a class="btn btn-xs btn-danger">NG</a></td>
            //             <td><a class="btn btn-xs btn-danger">NG</a></td>';
            // }
        }

        $data.='<td>'.$total_ot.'</td>
                </tr>';
    }
    
    $chart_num_datasets=2;
    $chart_datasets = array(
        '1' => 'Total OT Hours',
    );
    $chart_levels_array=Array();
    $chart_data_array=Array();
    $chart_backgroundColor_array=Array();
    $chart_borderColor_array=Array();

    for ($i=0; $i < $chart_num_datasets ; $i++) {
        $chart_levels='';
        $chart_data='';
        $department=$this->getDepartment($var['emp_depart_id'],$var['emp_id']);
        foreach ($department as $d) {
            $ot=$grand_ot_total;
            $chart_levels.='"'.$d->depart_name.'"'.',';
            $chart_data.=$ot.',';
        }

        array_push($chart_levels_array, $i,$chart_levels);
        array_push($chart_data_array, $i,$chart_data);
        array_push($chart_backgroundColor_array,$i,$this->backgroundColor(count($department)));
        array_push($chart_borderColor_array,$i,$this->borderColor(count($department)));
    }

    $info=array(
        'chart_show'=>true,
        'page_title'=>$this->getReportTitle($var,'Total OT Report'),
        'chart_short_title'=>'Total OT Chart',
        'chart_title'=>$this->getReportTitle($var,'Total OT Chart'),
        'chart_num_datasets'=>$chart_num_datasets,
        'chart_datasets'=>$chart_datasets,
        'chart_levels'=>$chart_levels_array,
        'chart_data'=>$chart_data_array,
        'chart_backgroundColor'=>$chart_backgroundColor_array,
        'chart_borderColor'=>$chart_borderColor_array,
    );
    return view('Admin.report.view',compact('thead','data','info'));
}

public function totalNCOT($var,$d)
{
    $emp_depart_id=$var['emp_depart_id'];
    $emp_sdepart_id=$var['emp_sdepart_id'];
    $emp_desig_id=$var['emp_desig_id'];
    $emp_jlid=$var['emp_jlid'];
    $emp_type=$var['emp_type'];
    $emp_id=$var['emp_id'];
    $start_date=$var['start_date'];
    $end_date=$var['end_date'];
    $nc=date("H:i:s",strtotime('00:00:00'));
    $otController=new otController;

    $card=DB::table('kqz_card')
        ->join('kqz_employee','kqz_card.EmployeeID','=','kqz_employee.EmployeeID')
        ->join('tbl_employee','kqz_employee.EmployeeCode','=','tbl_employee.emp_machineid')
        ->where('tbl_employee.emp_depart_id', $d->depart_id)
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
        ->where(DB::raw("substr(kqz_card.CardTime, 1, 10)"), '>=',$start_date)
        ->where(DB::raw("substr(kqz_card.CardTime, 1, 10)"), '<=',$end_date)
        ->groupBy(DB::raw("substr(kqz_card.CardTime, 1, 10)"))
        ->orderBy('kqz_card.CardTime','asc')
        ->get(['kqz_card.EmployeeID','kqz_card.CardTime','tbl_employee.*']);
    //employee loop (start)//
    foreach ($card as $emp) {
        $singleDayNC = strtotime($otController->singleDayNC($emp->EmployeeID,substr($emp->CardTime,0,10)))-strtotime("00:00:00");
        $nc = date("H:i:s",strtotime($nc)+$singleDayNC);
    }

    return $nc;
}

public function NCOTReport($var)
{
    $emp_depart_id=$var['emp_depart_id'];
    $emp_sdepart_id=$var['emp_sdepart_id'];
    $emp_desig_id=$var['emp_desig_id'];
    $emp_jlid=$var['emp_jlid'];
    $emp_type=$var['emp_type'];
    $emp_id=$var['emp_id'];
    $start_date=$var['start_date'];
    $end_date=$var['end_date'];

    $thead='<tr>
              <th></th>
              <th>SL</th>
              <th>Employee ID</th>
              <th>Face ID</th>
              <th>Employee Name</th>
              <th>Date</th>
              <th>Total Working Hours </th>
              <th>Total OT </th>
              <th>NC OT (2 Hrs daily)</th>
              <th>NC OT (12 Hrs Weekly</th>
              <th>NC OT (48 Hrs Monthly</th>
            </tr>';
    $otController=new otController();

    //todays all employee add (start)//
    $employee=$this->filterOTEmployee($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type,$emp_id,$start_date,$end_date);
    //todays all employee add (end)//

    $data='';
    $counter=0;
    foreach ($employee as $emp) {

    $card=DB::table('kqz_card')
        ->join('kqz_employee','kqz_card.EmployeeID','=','kqz_employee.EmployeeID')
        ->join('tbl_employee','kqz_employee.EmployeeCode','=','tbl_employee.emp_machineid')
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
        ->where('kqz_card.EmployeeID', $emp->EmployeeID)
        ->where(DB::raw("substr(kqz_card.CardTime, 1, 10)"), '>=',$start_date)
        ->where(DB::raw("substr(kqz_card.CardTime, 1, 10)"), '<=',$end_date)
        ->groupBy(DB::raw("substr(kqz_card.CardTime, 1, 10)"))
        ->orderBy('kqz_card.CardTime','asc')
        ->get(['kqz_card.EmployeeID','kqz_card.CardTime','tbl_employee.*']);
    //employee loop (start)//
    foreach ($card as $emp) {
    $counter++;
        //emp shift info (start)//
        $shift_info=$this->getCurrentShiftInfo($emp->emp_id,substr($emp->CardTime,0,10));      
        //emp shift info (end)//
        
        $twh=$this->totalWorkingHours($emp,substr($emp->CardTime,0,10),$shift_info);
        $twh['twh']=$otController->holidayWeekendTWh($emp,substr($emp->CardTime,0,10),$twh['twh']);
        // nc working hour calculation (start)//
        if(isset($shift_info) && $shift_info->shift_type=="1"){
            if(substr($twh['twh'],0,2)>='8'){

                $to_time = new DateTime($twh['twh']);
                
                $total_from_time = new DateTime('08:00:00');
                $total_duration = $to_time->diff($total_from_time);
                if($total_duration->h>0){
                    $total_hour=$total_duration->format('%H:%I:%S');
                }else{
                    if($total_duration->i>=30){
                        $total_hour=$total_duration->format('%H:%I:%S');
                    }else{
                        $total_hour='00:00:00';
                    }
                }

            if(substr($twh['twh'],0,2)>='10'){
                $nc_from_time = new DateTime('10:00:00');
                $nc_duration = $to_time->diff($nc_from_time);
                 if($total_duration->h>=2){
                    if($nc_duration->h>0){
                        $nc_hour=$nc_duration->format('%H:%I:%S');
                    }else{
                        if($nc_duration->i>=0){
                            $nc_hour=$nc_duration->format('%H:%I:%S');
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

            }else{
                $total_hour='00:00:00';
                $nc_hour='00:00:00';
            }
        }else if(isset($shift_info) && $shift_info->shift_type=="2"){
            if(substr($twh['twh'],0,2)>='9'){
                $to_time = new DateTime($twh['twh']);
                
                $total_from_time = new DateTime('09:00:00');
                $total_duration = $to_time->diff($total_from_time);
                if($total_duration->h>0){
                    $total_hour=$total_duration->format('%H:%I:%S');
                }else{
                    if($total_duration->i>=30){
                        $total_hour=$total_duration->format('%H:%I:%S');
                    }else{
                        $total_hour='00:00:00';
                    }
                }

            if(substr($twh['twh'],0,2)>='11'){
                $nc_from_time = new DateTime('11:00:00');
                $nc_duration = $to_time->diff($nc_from_time);
                 if($total_duration->h>=2){
                    if($nc_duration->h>0){
                        $nc_hour=$nc_duration->format('%H:%I:%S');
                    }else{
                        if($nc_duration->i>=0){
                            $nc_hour=$nc_duration->format('%H:%I:%S');
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

            }else{
                $total_hour='00:00:00';
                $nc_hour='00:00:00';
            }
        }else{
            $total_hour='00:00:00';
            $nc_hour='00:00:00';
        }
        // nc working hour calculation (end)//
        $week = new DateTime(substr($emp->CardTime,0,10).' 7 days ago');
        $month = new DateTime(substr($emp->CardTime,0,10).' 30 days ago');
        $week=$week->format('Y-m-d');
        $month=$month->format('Y-m-d');
        $seconds= strtotime($nc_hour) - strtotime('00:00:00');
        if($seconds>0){
            $data.='<tr class="gradeX" id="tr-'.$emp->emp_id.'">
                <td></td>
                <td>'.$counter.'</td>
                <td>'.$emp->emp_empid.'</td>
                <td>'.$emp->emp_machineid.'</td>
                <td>'.$emp->emp_name.'</td>
                <td>'.substr($emp->CardTime,0,10).'</a>';
            $data.='</td>
                    <td>'.$twh['twh'].'</td>
                    <td>'.$total_hour.'</td>
                    <td>'.$nc_hour.'</td>
                    <td>'.$otController->weeklyNC($emp->emp_id,$week,substr($emp->CardTime,0,10)).'</td>
                    <td>'.$otController->monthlyNC($emp->emp_id,$month,substr($emp->CardTime,0,10)).'</td>
                  </tr>';
        }
    }

    }
    //employee loop (end)//
    /*$chart_num_datasets=2;
    $chart_datasets = array(
        '1' => 'Total NC OT Hours',
    );
    $chart_levels_array=Array();
    $chart_data_array=Array();
    $chart_backgroundColor_array=Array();
    $chart_borderColor_array=Array();

    for ($i=0; $i < $chart_num_datasets ; $i++) {
        $chart_levels='';
        $chart_data='';
        $department=$this->getDepartment($var['emp_depart_id'],$var['emp_id']);
        foreach ($department as $d) {
            $nc=$this->totalNCOT($var,$d);
            $nc=$this->timeToHours($nc);
            $chart_levels.='"'.$d->depart_name.'"'.',';
            $chart_data.=$nc.',';
        }

        array_push($chart_levels_array, $i,$chart_levels);
        array_push($chart_data_array, $i,$chart_data);
        array_push($chart_backgroundColor_array,$i,$this->backgroundColor(count($department)));
        array_push($chart_borderColor_array,$i,$this->borderColor(count($department)));
    }*/

    $info=array(
        'chart_show'=>false,
        'page_title'=>$this->getReportTitle($var,'Total NC OT Report'),
        /*'chart_short_title'=>'Total NC OT Chart',
        'chart_title'=>$this->getReportTitle($var,'Total NC OT Chart'),
        'chart_num_datasets'=>$chart_num_datasets,
        'chart_datasets'=>$chart_datasets,
        'chart_levels'=>$chart_levels_array,
        'chart_data'=>$chart_data_array,
        'chart_backgroundColor'=>$chart_backgroundColor_array,
        'chart_borderColor'=>$chart_borderColor_array,*/
    );
    return view('Admin.report.view',compact('thead','data','info'));
}

public function filterShiftWiseEmployee($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type,$emp_id)
{
    return $shiftData=DB::table('tbl_employee')
        ->join('tbl_shift','tbl_employee.emp_shiftid','=','tbl_shift.shift_id')
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
        ->get(['tbl_employee.*','tbl_shift.shift_type']);
}

public function shiftWiseEmployee($var,$d,$emp_workhr)
{
    $emp_depart_id=$var['emp_depart_id'];
    $emp_sdepart_id=$var['emp_sdepart_id'];
    $emp_desig_id=$var['emp_desig_id'];
    $emp_jlid=$var['emp_jlid'];
    $emp_type=$var['emp_type'];
    $emp_id=$var['emp_id'];
    $start_date=$var['start_date'];
    $end_date=$var['end_date'];

    $employee=DB::table('tbl_employee')
        ->where('tbl_employee.emp_depart_id', $d->depart_id)
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
        ->where('tbl_employee.emp_workhr',$emp_workhr)
        ->count('tbl_employee.emp_id');
    return $employee;
}

public function shiftReport($var)
{
    $emp_depart_id=$var['emp_depart_id'];
    $emp_sdepart_id=$var['emp_sdepart_id'];
    $emp_desig_id=$var['emp_desig_id'];
    $emp_jlid=$var['emp_jlid'];
    $emp_type=$var['emp_type'];
    $emp_id=$var['emp_id'];
    $start_date=$var['start_date'];
    $end_date=$var['end_date'];

    $thead='<tr>
              <th>
              </th>
              <th>SL</th>
              <th>Employee ID</th>
              <th>Face ID</th>
              <th>Employee Name</th>
              <th>Department</th>
              <th>Shift Type</th>
              <th>Shift Time</th>
            </tr>';

    $shiftData=$this->filterShiftWiseEmployee($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type,$emp_id);

    $data='';
    if(isset($shiftData[0])){
      $c=0;
        foreach ($shiftData as $sd){
        $c++;
        $data.='<tr class="gradeX" id="tr-'.$sd->emp_id.'">
            <td></td>
            <td>'.$c.'</td>
            <td>'.$sd->emp_empid.'</td>
            <td>'.$sd->emp_machineid.'</td>
            <td><a href="'.URL::to('employee-details').'/'.$sd->emp_id.'/view">'.$sd->emp_name.'</a></td>
            <td>'.$this->getDepartmentName($sd->emp_depart_id).'</td>
            <td>';
            if($sd->shift_type=="1"){
                $data.='7 Hours (+1 hr lunch)';
            }elseif($sd->shift_type=="2"){
                 $data.='8 Hours (+1 hr lunch)';
            }
            $data.='</td>
            <td>'.$this->getShiftInfo($sd->emp_shiftid).'</td>
          </tr>';
        }
    }

    $chart_num_datasets=4;
    $chart_datasets = array(
        '1' => '7 Hours(+1 hrs lunch)',
        '3' => '8 Hours(+1 hrs lunch)',
    );
    $chart_levels_array=Array();
    $chart_data_array=Array();
    $chart_backgroundColor_array=Array();
    $chart_borderColor_array=Array();

    for ($i=0; $i < $chart_num_datasets ; $i++) {
        $chart_levels='';
        $chart_data='';
        $department=$this->getDepartment($var['emp_depart_id'],$var['emp_id']);
        foreach ($department as $d) {
            if($i=="0"){
                $employee=$this->shiftWiseEmployee($var,$d,'1');
            }elseif($i=="1"){
                $employee=$this->shiftWiseEmployee($var,$d,'2');
            }
            $chart_levels.='"'.$d->depart_name.'"'.',';
            $chart_data.=$employee.',';
        }

        array_push($chart_levels_array, $i,$chart_levels);
        array_push($chart_data_array, $i,$chart_data);
        array_push($chart_backgroundColor_array,$i,$this->backgroundColor(count($department)));
        array_push($chart_borderColor_array,$i,$this->borderColor(count($department)));
    }

    $info=array(
        'chart_show'=>true,
        'page_title'=>$this->getReportTitle($var,'Shift Wise Employee Report'),
        'chart_short_title'=>'Shift Wise Employee Chart',
        'chart_title'=>$this->getReportTitle($var,'Shift Wise Employee Chart'),
        'chart_num_datasets'=>$chart_num_datasets,
        'chart_datasets'=>$chart_datasets,
        'chart_levels'=>$chart_levels_array,
        'chart_data'=>$chart_data_array,
        'chart_backgroundColor'=>$chart_backgroundColor_array,
        'chart_borderColor'=>$chart_borderColor_array,
    );
    return view('Admin.report.view',compact('thead','data','info'));
}

public function filterleaveApplicationEmployee($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type,$emp_id,$start_date,$end_date)
{
    return $leaveapplication=DB::table('tbl_leaveapplication')
        ->join('tbl_employee','tbl_leaveapplication.leave_empid','=','tbl_employee.emp_id')
        ->join('tbl_leavetype','tbl_leaveapplication.leave_typeid','=','tbl_leavetype.li_id')
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
        ->where(DB::raw('substr(tbl_leaveapplication.leave_start_date,1,10)'),'>=',$start_date)
        ->where(DB::raw('substr(tbl_leaveapplication.leave_end_date,1,10)'),'<=',$end_date)
        ->get(['tbl_leaveapplication.*','tbl_employee.emp_empid','tbl_employee.emp_id','tbl_employee.emp_name','tbl_leavetype.li_name']);
}


public function leaveApplicationCount($var,$d,$leave_status)
{
    $emp_depart_id=$var['emp_depart_id'];
    $emp_sdepart_id=$var['emp_sdepart_id'];
    $emp_desig_id=$var['emp_desig_id'];
    $emp_jlid=$var['emp_jlid'];
    $emp_type=$var['emp_type'];
    $emp_id=$var['emp_id'];
    $start_date=$var['start_date'];
    $end_date=$var['end_date'];

    return $leaveapplication=DB::table('tbl_leaveapplication')
        ->join('tbl_employee','tbl_leaveapplication.leave_empid','=','tbl_employee.emp_id')
        ->where('tbl_employee.emp_depart_id', $d->depart_id)
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
        ->where(DB::raw('substr(tbl_leaveapplication.leave_start_date,1,10)'),'>=',$start_date)
        ->where(DB::raw('substr(tbl_leaveapplication.leave_end_date,1,10)'),'<=',$end_date)
        ->where('tbl_leaveapplication.leave_status',$leave_status)
        ->count(['tbl_leaveapplication.leave_id']);
}

public function leaveApplicationReport($var)
{
    $emp_depart_id=$var['emp_depart_id'];
    $emp_sdepart_id=$var['emp_sdepart_id'];
    $emp_desig_id=$var['emp_desig_id'];
    $emp_jlid=$var['emp_jlid'];
    $emp_type=$var['emp_type'];
    $emp_id=$var['emp_id'];
    $start_date=$var['start_date'];
    $end_date=$var['end_date'];

    $thead='<tr>
              <th></th>
              <th>SL</th>
              <th>Employee ID</th>
              <th>Employee Name</th>
              <th>Leave Type</th>
              <th>Start Date</th>
              <th>End Date</th>
              <th>Total Days</th>
              <th>Reason</th>
              <th>Attachments</th>
              <th>Leave Against</th>
              <th>Notes</th>
              <th>Status</th>
            </tr>';

    $leaveapplication=$this->filterleaveApplicationEmployee($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type,$emp_id,$start_date,$end_date);

    $data='';

      if(isset($leaveapplication[0])){
      $c=0;
        foreach ($leaveapplication as $la){
        $c++;
         $data.='<tr class="gradeX" id="tr-'.$la->leave_id.'">
          <td></td>
            <td>'.$c.'</td>
            <td>'.$la->emp_empid.'</td>
            <td>'.$la->emp_name.'</td>
            <td>'.$la->li_name.'</td>
            <td>'.$la->leave_start_date.'</td>
            <td>'.$la->leave_end_date.'</td>
            <td>'.$la->leave_day.'</td>
            <td>'.$la->leave_reason.'</td>
            <td>';
            if($la->leave_typeid=="4" && $la->leave_docext!=""){
                $data.='<a href="'.URl::to('public').'/leaveContent/'.$la->leave_id.'.'.$la->leave_docext.'" target="_blank">'.$la->leave_id.'.'.$la->leave_docext.'</a>';
            }
            $data.='</td>
            <td>'.$la->leave_replacement_date.'</td>
            <td>'.$la->leave_note.'</td>
            <td>';
            if($la->leave_status=="0"){
              $data.='<a class="btn btn-warning btn-xs">Pending</a>';
              }elseif($la->leave_status=="1"){
              $data.='<a class="btn btn-success btn-xs">Aprooved</a>';
              }elseif($la->leave_status=="2"){
              $data.='<a class="btn btn-danger btn-xs">Denied</a>';
              }
            $data.='</td>
            
          </tr>';
         }
      }

    $chart_num_datasets=6;
    $chart_datasets = array(
        '1' => 'Approved',
        '3' => 'Pending',
        '5' => 'Denied',
    );
    $chart_levels_array=Array();
    $chart_data_array=Array();
    $chart_backgroundColor_array=Array();
    $chart_borderColor_array=Array();

    for ($i=0; $i < $chart_num_datasets ; $i++) {
        $chart_levels='';
        $chart_data='';
        $department=$this->getDepartment($var['emp_depart_id'],$var['emp_id']);
        foreach ($department as $d) {
            if($i=="0"){
                $approved=$this->leaveApplicationCount($var,$d,'1');
                $chart_levels.='"'.$d->depart_name.'"'.',';
                $chart_data.=$approved.',';
            }elseif($i=="1"){
                $pending=$this->leaveApplicationCount($var,$d,'0');
                $chart_levels.='"'.$d->depart_name.'"'.',';
                $chart_data.=$pending.',';
            }elseif($i=="1"){
                $denied=$this->leaveApplicationCount($var,$d,'2');
                $chart_levels.='"'.$d->depart_name.'"'.',';
                $chart_data.=$denied.',';
            }
        }

        array_push($chart_levels_array, $i,$chart_levels);
        array_push($chart_data_array, $i,$chart_data);
        array_push($chart_backgroundColor_array,$i,$this->backgroundColor(count($department)));
        array_push($chart_borderColor_array,$i,$this->borderColor(count($department)));
    }

    $info=array(
        'chart_show'=>true,
        'page_title'=>$this->getReportTitle($var,'Leave Application Report'),
        'chart_short_title'=>'Leave Application Chart',
        'chart_title'=>$this->getReportTitle($var,'Leave Application Chart'),
        'chart_num_datasets'=>$chart_num_datasets,
        'chart_datasets'=>$chart_datasets,
        'chart_levels'=>$chart_levels_array,
        'chart_data'=>$chart_data_array,
        'chart_backgroundColor'=>$chart_backgroundColor_array,
        'chart_borderColor'=>$chart_borderColor_array,
    );
    return view('Admin.report.view',compact('thead','data','info'));
}

public function HRTicketsReport($var)
{
    return redirect('report');
}


public function noShowNoEntryCount($var,$d,$type)
{
    $emp_depart_id=$var['emp_depart_id'];
    $emp_sdepart_id=$var['emp_sdepart_id'];
    $emp_desig_id=$var['emp_desig_id'];
    $emp_jlid=$var['emp_jlid'];
    $emp_type=$var['emp_type'];
    $emp_id=$var['emp_id'];
    $start_date=$var['start_date'];
    $end_date=$var['end_date'];

    $Attendance=new attendanceController();
    $noEntry=0;
    $noShow=0;
    $dateRange=$this->getDateRange($start_date,$end_date);
    $employee=$this->filterAttendanceEmployee($d->depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type,$emp_id);

    if($type=="1"){
        return count($employee);
    }elseif($type=="2"){
        //employee loop (start)//
        foreach ($employee as $emp) {
            for($count=0;$count<count($dateRange);$count++) {
                if($Attendance->checkPresent($emp->EmployeeID,$dateRange[$count])=="1"){
        
                }else{
                    $noEntry++;
                }
            }
        }
        //employee loop (end)//
        return $noEntry;
    }elseif($type=="3"){
        //employee loop (start)//
        foreach ($employee as $emp) {
            for($count=0;$count<count($dateRange);$count++) {
                if($Attendance->checkPresent($emp->EmployeeID,$dateRange[$count])=="1"){
                    
                    $card_count=DB::table('kqz_card')
                        ->join('kqz_devinfo','kqz_card.DevID','=','kqz_devinfo.DevID')
                        ->where('kqz_card.EmployeeID',$emp->EmployeeID)
                        ->where('kqz_card.DevID','<','7')
                        ->where('kqz_devinfo.DevType','1')
                        ->where(DB::raw("substr(kqz_card.CardTime, 1, 10)"), '=',$dateRange[$count])
                        ->count();
                    if($card_count>0){}else{
                        $card_count=DB::table('kqz_card')
                        ->join('tbl_remotedevice','kqz_card.DevID','=','tbl_remotedevice.DevID')
                        ->where('kqz_card.EmployeeID',$emp->EmployeeID)
                        ->where('kqz_card.DevID','>','6')
                        ->where('tbl_remotedevice.DevType','1')
                        ->where(DB::raw("substr(kqz_card.CardTime, 1, 10)"), '=',$dateRange[$count])
                        ->count();
                    }
                    
                    if($card_count>0){}else{
                        $noShow++;
                    }
                }
            }
        }
        //employee loop (end)//
        return $noShow;
    }
}

public function NoShowNoEntryReport($var)
{
    $emp_depart_id=$var['emp_depart_id'];
    $emp_sdepart_id=$var['emp_sdepart_id'];
    $emp_desig_id=$var['emp_desig_id'];
    $emp_jlid=$var['emp_jlid'];
    $emp_type=$var['emp_type'];
    $emp_id=$var['emp_id'];
    $start_date=$var['start_date'];
    $end_date=$var['end_date'];

    $thead='<tr>
              <th></th>
              <th>SL</th>
              <th>Employee ID</th>
              <th>Face ID</th>
              <th>Employee Name</th>
              <th>Date</th>
              <th>Status</th>
            </tr>';

    $Attendance=new attendanceController();
    //todays all employee add (start)//
    $employee=$this->filterAttendanceEmployee($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type,$emp_id);
    $dateRange=$this->getDateRange($start_date,$end_date);

    $data='';   
    $counter=0;

    foreach ($employee as $emp) {
    //employee loop (start)//
    for($count=0;$count<count($dateRange);$count++) {
    $counter++;
    

    if($Attendance->checkPresent($emp->EmployeeID,$dateRange[$count])=="1"){
        
            $card_count=DB::table('kqz_card')
                ->join('kqz_devinfo','kqz_card.DevID','=','kqz_devinfo.DevID')
                ->where('kqz_card.EmployeeID',$emp->EmployeeID)
                ->where('kqz_card.DevID','<','7')
                ->where('kqz_devinfo.DevType','1')
                ->where(DB::raw("substr(kqz_card.CardTime, 1, 10)"), '=',$dateRange[$count])
                ->count();
            if($card_count>0){}else{
                $card_count=DB::table('kqz_card')
                ->join('tbl_remotedevice','kqz_card.DevID','=','tbl_remotedevice.DevID')
                ->where('kqz_card.EmployeeID',$emp->EmployeeID)
                ->where('kqz_card.DevID','>','6')
                ->where('tbl_remotedevice.DevType','1')
                ->where(DB::raw("substr(kqz_card.CardTime, 1, 10)"), '=',$dateRange[$count])
                ->count();
            }
            
            if($card_count>0){}else{
                $data.='<tr class="gradeX" id="tr-'.$emp->emp_id.'">
                    <td></td>
                    <td>'.$counter.'</td>
                    <td>'.$emp->emp_empid.'</td>
                    <td>'.$emp->emp_machineid.'</td>
                    <td>'.$emp->emp_name.'</td>
                    <td>'.$dateRange[$count].'</td>
                    <td><a class="btn btn-xs btn-danger">Device MisMatched!</a></td>
                  </tr>';
            }
    }else{
        $data.='<tr class="gradeX" id="tr-'.$emp->emp_id.'">
            <td></td>
            <td>'.$counter.'</td>
            <td>'.$emp->emp_empid.'</td>
            <td>'.$emp->emp_machineid.'</td>
            <td>'.$emp->emp_name.'</td>
            <td>'.$dateRange[$count].'</td>
            <td><a class="btn btn-xs btn-info">No Entry</a></td>
          </tr>';
    }

    }
    }
    //employee loop (end)//

    $chart_num_datasets=6;
    $chart_datasets = array(
        '1' => 'Total Employee',
        '3' => 'No Entry',
        '5' => 'Device Mismatched',
    );
    $chart_levels_array=Array();
    $chart_data_array=Array();
    $chart_backgroundColor_array=Array();
    $chart_borderColor_array=Array();

    for ($i=0; $i < $chart_num_datasets ; $i++) {
        $chart_levels='';
        $chart_data='';
        $department=$this->getDepartment($var['emp_depart_id'],$var['emp_id']);
        foreach ($department as $d) {
            $value=$this->noShowNoEntryCount($var,$d,$i+1);
            $chart_levels.='"'.$d->depart_name.'"'.',';
            $chart_data.=$value.',';
        }

        array_push($chart_levels_array, $i,$chart_levels);
        array_push($chart_data_array, $i,$chart_data);
        array_push($chart_backgroundColor_array,$i,$this->backgroundColor(count($department)));
        array_push($chart_borderColor_array,$i,$this->borderColor(count($department)));
    }

    $info=array(
        'chart_show'=>true,
        'page_title'=>$this->getReportTitle($var,'No Entry/Device Mismatched Report'),
        'chart_short_title'=>'No Entry/Device Mismatched Chart',
        'chart_title'=>$this->getReportTitle($var,'No Entry/Device Mismatched Chart'),
        'chart_num_datasets'=>$chart_num_datasets,
        'chart_datasets'=>$chart_datasets,
        'chart_levels'=>$chart_levels_array,
        'chart_data'=>$chart_data_array,
        'chart_backgroundColor'=>$chart_backgroundColor_array,
        'chart_borderColor'=>$chart_borderColor_array,
    );
    return view('Admin.report.view',compact('thead','data','info'));
}

public function change_status($id)
{
    $emp=Employee::find($id);

    $emp->emp_status = 1;
    $emp->status_condition = 0;

    $emp->save();
    Session::flash('success','Employee Joined Successfully.');
    return redirect('report/exited-employee-archive/view');
}

public function exitedEmployeeArchiveReport($var)
{
    $emp_depart_id=$var['emp_depart_id']; 
    $emp_sdepart_id=$var['emp_sdepart_id']; 
    $emp_desig_id=$var['emp_desig_id']; 
    $emp_jlid=$var['emp_jlid']; 
    $emp_type=$var['emp_type']; 
    $emp_id=$var['emp_id'];
    $status_condition=$var['status_condition'];

    if($var['status_condition']==1 || $var['status_condition']==0){
    $thead='<tr>
            <th></th>
            <th>SL</th>
            <th>Location</th>
            <th>Employee ID</th>
            <th>Face ID</th>
            <th>Employee Name</th>
            <th>Designation</th>
            <th>Department</th>
            <th hidden>Sub-Department</th>
            <th hidden>Employee Type</th>
            <th>Senior Employee</th>
            <th>Inactive Date</th>
          </tr>';
      }

    if($var['status_condition']==2)
    {
        $thead='<tr>
            <th></th>
            <th>SL</th>
            <th>Location</th>
            <th>Employee ID</th>
            <th>Face ID</th>
            <th>Employee Name</th>
            <th>Designation</th>
            <th>Department</th>
            <th hidden>Sub-Department</th>
            <th hidden>Employee Type</th>
            <th>Senior Employee</th>
            <th>Inactive Date</th>
            <th>Status</th>
          </tr>';
    }

    $employeeDetails=DB::table('tbl_employee')
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
        ->when($status_condition>0, function($query) use ($status_condition){
            return $query->where('tbl_employee.status_condition', $status_condition);
        })
        ->where('tbl_employee.emp_status','0')
        ->where('tbl_employee.emp_id','!=','116')
        ->orderBy('tbl_employee.emp_name','ASC')
        ->get(['tbl_employee.*']);

    $data='';
    if(isset($employeeDetails[0])){
    $c=0;
        foreach ($employeeDetails as $ed){
        $c++;
        if($var['status_condition']==1 || $var['status_condition']==0){
         $data.='<tr class="gradeX" id="tr-'.$ed->emp_id.'">
            <td></td>
            <td>'.$c.'</td>
            <td>'.$this->getLocationName($ed->emp_jlid).'</td>
            <td>'.$ed->emp_empid.'</td>
            <td>'.$ed->emp_machineid.'</td>
            <td><a href="'.URL::to('employee-details').'/'.$ed->emp_id.'/history">'.$ed->emp_name.'</a></td>
            <td>'.$this->getDesignationName($ed->emp_desig_id).'</td>
            <td>'.$this->getDepartmentName($ed->emp_depart_id).'</td>
            <td hidden>'.$this->getSubDepartmentName($ed->emp_sdepart_id).'</td>
            <td hidden>';
            if($ed->emp_type=="1"){
                $data.='Permanent';
            }elseif($ed->emp_type=="2"){
                $data.='Contractual';
            }
        $data.='</td>
            <td>'.$this->getSeniorName($ed->emp_seniorid).'</td>
            <td>'.$ed->inactive_datetime.'</td>
          </tr>';
      }

          if($var['status_condition']==2)
          {
            $sa='change-status';
            $data.='<tr class="gradeX" id="tr-'.$ed->emp_id.'">
            <td></td>
            <td>'.$c.'</td>
            <td>'.$this->getLocationName($ed->emp_jlid).'</td>
            <td>'.$ed->emp_empid.'</td>
            <td>'.$ed->emp_machineid.'</td>
            <td><a href="'.URL::to('employee-details').'/'.$ed->emp_id.'/history">'.$ed->emp_name.'</a></td>
            <td>'.$this->getDesignationName($ed->emp_desig_id).'</td>
            <td>'.$this->getDepartmentName($ed->emp_depart_id).'</td>
            <td hidden>'.$this->getSubDepartmentName($ed->emp_sdepart_id).'</td>
            <td hidden>';
            if($ed->emp_type=="1"){
                $data.='Permanent';
            }elseif($ed->emp_type=="2"){
                $data.='Contractual';
            }
        $data.='</td>
            <td>'.$this->getSeniorName($ed->emp_seniorid).'</td>
             <td>'.$ed->inactive_datetime.'</td>
            <td>'.'<a type="submit" class="btn btn-success" href="/pfi/change-status/'.$ed->emp_id.'">Join</a>'.'</td>
          </tr>';
          }
        }
    }

    $chart_num_datasets=2;
    $chart_datasets = array(
        '1' => 'Exited Employee',
    );
    $chart_levels_array=Array();
    $chart_data_array=Array();
    $chart_backgroundColor_array=Array();
    $chart_borderColor_array=Array();

    for ($i=0; $i < $chart_num_datasets ; $i++) {
        $chart_levels='';
        $chart_data='';
        $department=$this->getDepartment($var['emp_depart_id'],$var['emp_id']);
        foreach ($department as $d) {
            $total=$this->totalEmployee($var,$d,'0');
            $chart_levels.='"'.$d->depart_name.'"'.',';
            $chart_data.=$total.',';
        }

        array_push($chart_levels_array, $i,$chart_levels);
        array_push($chart_data_array, $i,$chart_data);
        array_push($chart_backgroundColor_array,$i,$this->backgroundColor(count($department)));
        array_push($chart_borderColor_array,$i,$this->borderColor(count($department)));
    }

    $info=array(
        'chart_show'=>true,
        'page_title'=>$this->getReportTitle($var,'Exited Employee Archive Report'),
        'chart_short_title'=>'Exited Employee Archive Chart',
        'chart_title'=>$this->getReportTitle($var,'Exited Employee Archive Chart'),
        'chart_num_datasets'=>$chart_num_datasets,
        'chart_datasets'=>$chart_datasets,
        'chart_levels'=>$chart_levels_array,
        'chart_data'=>$chart_data_array,
        'chart_backgroundColor'=>$chart_backgroundColor_array,
        'chart_borderColor'=>$chart_borderColor_array,
    );

    return view('Admin.report.view',compact('thead','data','info'));
}

public function filterLeaveDataEmployee($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type,$emp_id,$start_date,$end_date,$leave_typeid)
{
    return $leave_employee=DB::table('tbl_leaveapplication')
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
        ->when($emp_id>0, function($query) use ($emp_id){
            return $query->where('tbl_employee.emp_id', $emp_id);
        })
        ->when($start_date!="0", function($query) use ($start_date){
            return $query->where('tbl_leaveapplication.leave_requested_date','>=',$start_date);
        })
        ->when($end_date!="0", function($query) use ($end_date){
            return $query->where('tbl_leaveapplication.leave_requested_date','<=',$end_date);
        })
        ->where('tbl_leaveapplication.leave_status','1')
        ->when($leave_typeid!="0", function($query) use ($leave_typeid){
            return $query->where('tbl_leaveapplication.leave_typeid',$leave_typeid);
        })
        ->groupBy('tbl_leaveapplication.leave_empid')
        ->orderBy('tbl_employee.emp_name','ASC')
        ->get(['tbl_leaveapplication.*','tbl_employee.*']);
}

public function filterShortLeaveDataEmployee($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type,$emp_id,$start_date,$end_date,$note)
{
    return $leave_employee=DB::table('tbl_leaveapplication')
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
        ->when($emp_id>0, function($query) use ($emp_id){
            return $query->where('tbl_employee.emp_id', $emp_id);
        })
        ->when($start_date!="0", function($query) use ($start_date){
            return $query->where('tbl_leaveapplication.leave_requested_date','>=',$start_date);
        })
        ->when($end_date!="0", function($query) use ($end_date){
            return $query->where('tbl_leaveapplication.leave_requested_date','<=',$end_date);
        })
        ->where('tbl_leaveapplication.leave_status','1')
        ->where('tbl_leaveapplication.leave_note',$note)
        ->groupBy('tbl_leaveapplication.leave_empid')
        ->orderBy('tbl_employee.emp_name','ASC')
        ->get(['tbl_leaveapplication.*','tbl_employee.*']);
}

public function annualLeaveReport($var)
{
    $emp_depart_id=$var['emp_depart_id'];
    $emp_sdepart_id=$var['emp_sdepart_id'];
    $emp_desig_id=$var['emp_desig_id'];
    $emp_jlid=$var['emp_jlid'];
    $emp_type=$var['emp_type'];
    $emp_id=$var['emp_id'];
    $start_date=$var['start_date'];
    $end_date=$var['end_date'];
    $lt=DB::table('tbl_leavetype')->where('li_id','1')->first();
    
    $thead='<tr>
              <th></th>
              <th>SL</th>
              <th>Employee ID</th>
              <th>Employee Name</th>
              <th>Department</th>
              <th>Sub-Department</th>
              <th>Annual Leave Qouta</th>
              <th>Annual Leave Taken</th>
              <th>Remaining Annual Leave</th>
            </tr>';

    $leaveInfoController=new leaveInfoController();

    $leave_employee=$this->filterLeaveDataEmployee($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type,$emp_id,$start_date,$end_date,$lt->li_id);

    $data='';
    if(isset($leave_employee[0])){
      $c=0;
        foreach ($leave_employee as $le){
          $c++;
           $data.='<tr class="gradeX" id="tr-'.$le->leave_empid.'">
              <td></td>
              <td>'.$c.'</td>
              <td>'.$le->emp_empid.'</td>
              <td>'.$le->emp_name.'</td>
              <td>'.$this->getDepartmentName($le->emp_depart_id).'</td>
              <td>'.$this->getSubDepartmentName($le->emp_sdepart_id).'</td>';
                if($lt){
                    if($leaveInfoController->checkLeaveTypeExist($le->leave_empid,$lt->li_id,$start_date,$end_date)=="1"){
                        $data.='<td>'.$lt->li_qoutaday.'</td>
                          <td>'.$leaveInfoController->leaveTaken($le->leave_empid,$lt->li_id,$start_date,$end_date).'</td>
                          <td>'.$leaveInfoController->leaveRemain($le->leave_empid,$lt->li_id,$lt->li_qoutaday,$start_date,$end_date).'</td>';
                    }else{
                        $data.='<td>'.$lt->li_qoutaday.'</td>
                          <td>0</td>
                          <td>'.$lt->li_qoutaday.'</td>';
                    }
                }
            $data.='</tr>';
        }
    }

    $chart_num_datasets=4;
    $chart_datasets = array(
        '1' => 'Total Employee',
        '3' => 'Annual Leave',
    );
    $chart_levels_array=Array();
    $chart_data_array=Array();
    $chart_backgroundColor_array=Array();
    $chart_borderColor_array=Array();

    for ($i=0; $i < $chart_num_datasets ; $i++) {
        $chart_levels='';
        $chart_data='';
        $department=$this->getDepartment($var['emp_depart_id'],$var['emp_id']);
        foreach ($department as $d) {
            if($i=="0"){
                $total=$this->totalEmployee($var,$d,'1');
                $chart_levels.='"'.$d->depart_name.'"'.',';
                $chart_data.=$total.',';
            }elseif($i=="1"){
                $inLeave=$this->inLeave($var,$d,$lt->li_id);
                $chart_levels.='"'.$d->depart_name.'"'.',';
                $chart_data.=$inLeave.',';
            }
        }

        array_push($chart_levels_array, $i,$chart_levels);
        array_push($chart_data_array, $i,$chart_data);
        array_push($chart_backgroundColor_array,$i,$this->backgroundColor(count($department)));
        array_push($chart_borderColor_array,$i,$this->borderColor(count($department)));
    }

    $info=array(
        'chart_show'=>true,
        'page_title'=>$this->getReportTitle($var,'Annual Leave Report'),
        'chart_short_title'=>'Annual Leave Chart',
        'chart_title'=>$this->getReportTitle($var,'Annual Leave Chart'),
        'chart_num_datasets'=>$chart_num_datasets,
        'chart_datasets'=>$chart_datasets,
        'chart_levels'=>$chart_levels_array,
        'chart_data'=>$chart_data_array,
        'chart_backgroundColor'=>$chart_backgroundColor_array,
        'chart_borderColor'=>$chart_borderColor_array,
    );

    return view('Admin.report.view',compact('thead','data','info'));
}

public function shortLeaveDuration($leave_empid,$leave_typeid,$start_date,$end_date,$leave_note)
{
    $shortLeaveDuration=DB::table('tbl_leaveapplication')
        ->where(['tbl_leaveapplication.leave_empid'=>$leave_empid,'tbl_leaveapplication.leave_note'=>$leave_note,'tbl_leaveapplication.leave_status'=>'1'])
        ->when($leave_typeid!="", function($query) use ($leave_typeid){
            return $query->where('tbl_leaveapplication.leave_typeid', $leave_typeid);
        })
        ->where(DB::raw('substr(tbl_leaveapplication.leave_start_date,1,10)'),'>=',$start_date)
        ->where(DB::raw('substr(tbl_leaveapplication.leave_end_date,1,10)'),'<=',$end_date)
        ->sum('tbl_leaveapplication.leave_day');
    return $shortLeaveDuration;
}

public function shortLeaveDurationForChart($var,$d,$leave_typeid,$leave_note)
{
    $emp_depart_id=$var['emp_depart_id'];
    $emp_sdepart_id=$var['emp_sdepart_id'];
    $emp_desig_id=$var['emp_desig_id'];
    $emp_jlid=$var['emp_jlid'];
    $emp_type=$var['emp_type'];
    $emp_id=$var['emp_id'];
    $start_date=$var['start_date'];
    $end_date=$var['end_date'];

    $shortLeaveDuration=DB::table('tbl_leaveapplication')
        ->join('tbl_employee','tbl_leaveapplication.leave_empid','=','tbl_employee.emp_id')
        ->where(['tbl_leaveapplication.leave_note'=>$leave_note,'tbl_leaveapplication.leave_status'=>'1'])
        ->where('tbl_employee.emp_depart_id', $d->depart_id)
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
        ->when($leave_typeid!="", function($query) use ($leave_typeid){
            return $query->where('tbl_leaveapplication.leave_typeid', $leave_typeid);
        })
        ->where(DB::raw('substr(tbl_leaveapplication.leave_start_date,1,10)'),'>=',$start_date)
        ->where(DB::raw('substr(tbl_leaveapplication.leave_end_date,1,10)'),'<=',$end_date)
        ->sum('tbl_leaveapplication.leave_day');
    return $shortLeaveDuration;
}

public function shortLeaveReport($var)
{
    $emp_depart_id=$var['emp_depart_id'];
    $emp_sdepart_id=$var['emp_sdepart_id'];
    $emp_desig_id=$var['emp_desig_id'];
    $emp_jlid=$var['emp_jlid'];
    $emp_type=$var['emp_type'];
    $emp_id=$var['emp_id'];
    $start_date=$var['start_date'];
    $end_date=$var['end_date'];
    
    $thead='<tr>
              <th></th>
              <th>SL</th>
              <th>Employee ID</th>
              <th>Employee Name</th>
              <th>Department</th>
              <th>Sub-Department</th>
              <th>Short Leave Duration</th>
              <th>Short Leave Adjusted as Casual Leave</th>
              <th>Short Leave Adjusted as Annual Leave</th>
            </tr>';

    $leaveInfoController=new leaveInfoController();

    $leave_employee=$this->filterShortLeaveDataEmployee($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type,$emp_id,$start_date,$end_date,'Short Leave Taken');

    $data='';
    if(isset($leave_employee[0])){
      $c=0;
        foreach ($leave_employee as $le){
          $c++;
           $data.='<tr class="gradeX" id="tr-'.$le->leave_empid.'">
              <td></td>
              <td>'.$c.'</td>
              <td>'.$le->emp_empid.'</td>
              <td>'.$le->emp_name.'</td>
              <td>'.$this->getDepartmentName($le->emp_depart_id).'</td>
              <td>'.$this->getSubDepartmentName($le->emp_sdepart_id).'</td>
              <td>'.$this->shortLeaveDuration($le->emp_id,'',$start_date,$end_date,'Short Leave Taken').'</td>
              <td>'.$this->shortLeaveDuration($le->emp_id,'3',$start_date,$end_date,'Short Leave Taken').'</td>
              <td>'.$this->shortLeaveDuration($le->emp_id,'1',$start_date,$end_date,'Short Leave Taken').'</td>';
            $data.='</tr>';
        }
    }

    $chart_num_datasets=6;
    $chart_datasets = array(
        '1' => 'Total Short Leave',
        '3' => 'Short Leave Adjusted as Casual Leave',
        '5' => 'Short Leave Adjusted as Annual Leave',
    );
    $chart_levels_array=Array();
    $chart_data_array=Array();
    $chart_backgroundColor_array=Array();
    $chart_borderColor_array=Array();

    for ($i=0; $i < $chart_num_datasets ; $i++) {
        $chart_levels='';
        $chart_data='';
        $department=$this->getDepartment($var['emp_depart_id'],$var['emp_id']);
        foreach ($department as $d) {
            if($i=="0"){
                $total=$this->shortLeaveDurationForChart($var,$d,'','Short Leave Taken');
                $chart_levels.='"'.$d->depart_name.'"'.',';
                $chart_data.=$total.',';
            }elseif($i=="1"){
                $inLeave=$this->shortLeaveDurationForChart($var,$d,'3','Short Leave Taken');
                $chart_levels.='"'.$d->depart_name.'"'.',';
                $chart_data.=$inLeave.',';
            }elseif($i=="2"){
                $inLeave=$this->shortLeaveDurationForChart($var,$d,'1','Short Leave Taken');
                $chart_levels.='"'.$d->depart_name.'"'.',';
                $chart_data.=$inLeave.',';
            }
        }

        array_push($chart_levels_array, $i,$chart_levels);
        array_push($chart_data_array, $i,$chart_data);
        array_push($chart_backgroundColor_array,$i,$this->backgroundColor(count($department)));
        array_push($chart_borderColor_array,$i,$this->borderColor(count($department)));
    }

    $info=array(
        'chart_show'=>true,
        'page_title'=>$this->getReportTitle($var,'Short Leave Report'),
        'chart_short_title'=>'Short Leave Chart',
        'chart_title'=>$this->getReportTitle($var,'Short Leave Chart'),
        'chart_num_datasets'=>$chart_num_datasets,
        'chart_datasets'=>$chart_datasets,
        'chart_levels'=>$chart_levels_array,
        'chart_data'=>$chart_data_array,
        'chart_backgroundColor'=>$chart_backgroundColor_array,
        'chart_borderColor'=>$chart_borderColor_array,
    );

    return view('Admin.report.view',compact('thead','data','info'));
}

public function inLeave($var,$d,$leave_typeid)
{
    $emp_depart_id=$var['emp_depart_id'];
    $emp_sdepart_id=$var['emp_sdepart_id'];
    $emp_desig_id=$var['emp_desig_id'];
    $emp_jlid=$var['emp_jlid'];
    $emp_type=$var['emp_type'];
    $emp_id=$var['emp_id'];
    $start_date=$var['start_date'];
    $end_date=$var['end_date'];

    $leave_employee=$this->filterLeaveDataEmployee($d->depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type,$emp_id,$start_date,$end_date,$leave_typeid);
    return count($leave_employee);
}

public function casualLeaveReport($var)
{
    $emp_depart_id=$var['emp_depart_id'];
    $emp_sdepart_id=$var['emp_sdepart_id'];
    $emp_desig_id=$var['emp_desig_id'];
    $emp_jlid=$var['emp_jlid'];
    $emp_type=$var['emp_type'];
    $emp_id=$var['emp_id'];
    $start_date=$var['start_date'];
    $end_date=$var['end_date'];
    $lt=DB::table('tbl_leavetype')->where('li_id','3')->first();
    
    $thead='<tr>
              <th></th>
              <th>SL</th>
              <th>Employee ID</th>
              <th>Employee Name</th>
              <th>Department</th>
              <th>Sub-Department</th>
              <th>Casual Leave Qouta</th>
              <th>Casual Leave Taken</th>
              <th>Remaining Casual Leave</th>
            </tr>';

    $leaveInfoController=new leaveInfoController();

    $leave_employee=$this->filterLeaveDataEmployee($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type,$emp_id,$start_date,$end_date,$lt->li_id);

    $data='';
    if(isset($leave_employee[0])){
      $c=0;
        foreach ($leave_employee as $le){
          $c++;
           $data.='<tr class="gradeX" id="tr-'.$le->leave_empid.'">
              <td></td>
              <td>'.$c.'</td>
              <td>'.$le->emp_empid.'</td>
              <td>'.$le->emp_name.'</td>
              <td>'.$this->getDepartmentName($le->emp_depart_id).'</td>
              <td>'.$this->getSubDepartmentName($le->emp_sdepart_id).'</td>';
                if($lt){
                    if($leaveInfoController->checkLeaveTypeExist($le->leave_empid,$lt->li_id,$start_date,$end_date)=="1"){
                        $data.='<td>'.$lt->li_qoutaday.'</td>
                          <td>'.$leaveInfoController->leaveTaken($le->leave_empid,$lt->li_id,$start_date,$end_date).'</td>
                          <td>'.$leaveInfoController->leaveRemain($le->leave_empid,$lt->li_id,$lt->li_qoutaday,$start_date,$end_date).'</td>';
                    }else{
                        $data.='<td>'.$lt->li_qoutaday.'</td>
                          <td>0</td>
                          <td>'.$lt->li_qoutaday.'</td>';
                    }
                }
            $data.='</tr>';
        }
    }

    $chart_num_datasets=4;
    $chart_datasets = array(
        '1' => 'Total Employee',
        '3' => 'Casual Leave',
    );
    $chart_levels_array=Array();
    $chart_data_array=Array();
    $chart_backgroundColor_array=Array();
    $chart_borderColor_array=Array();

    for ($i=0; $i < $chart_num_datasets ; $i++) {
        $chart_levels='';
        $chart_data='';
        $department=$this->getDepartment($var['emp_depart_id'],$var['emp_id']);
        foreach ($department as $d) {
            if($i=="0"){
                $total=$this->totalEmployee($var,$d,'1');
                $chart_levels.='"'.$d->depart_name.'"'.',';
                $chart_data.=$total.',';
            }elseif($i=="1"){
                $inLeave=$this->inLeave($var,$d,$lt->li_id);
                $chart_levels.='"'.$d->depart_name.'"'.',';
                $chart_data.=$inLeave.',';
            }
        }

        array_push($chart_levels_array, $i,$chart_levels);
        array_push($chart_data_array, $i,$chart_data);
        array_push($chart_backgroundColor_array,$i,$this->backgroundColor(count($department)));
        array_push($chart_borderColor_array,$i,$this->borderColor(count($department)));
    }

    $info=array(
        'chart_show'=>true,
        'page_title'=>$this->getReportTitle($var,'Casual Leave Report'),
        'chart_short_title'=>'Casual Leave Chart',
        'chart_title'=>$this->getReportTitle($var,'Casual Leave Chart'),
        'chart_num_datasets'=>$chart_num_datasets,
        'chart_datasets'=>$chart_datasets,
        'chart_levels'=>$chart_levels_array,
        'chart_data'=>$chart_data_array,
        'chart_backgroundColor'=>$chart_backgroundColor_array,
        'chart_borderColor'=>$chart_borderColor_array,
    );

    return view('Admin.report.view',compact('thead','data','info'));
}

public function compensatoryLeaveReport($var)
{
    $emp_depart_id=$var['emp_depart_id'];
    $emp_sdepart_id=$var['emp_sdepart_id'];
    $emp_desig_id=$var['emp_desig_id'];
    $emp_jlid=$var['emp_jlid'];
    $emp_type=$var['emp_type'];
    $emp_id=$var['emp_id'];
    $start_date=$var['start_date'];
    $end_date=$var['end_date'];
    $lt=DB::table('tbl_leavetype')->where('li_id','5')->first();
    
    $thead='<tr>
              <th></th>
              <th>SL</th>
              <th>Employee ID</th>
              <th>Employee Name</th>
              <th>Department</th>
              <th>Sub-Department</th>
              <th>Compensatory Leave Qouta</th>
              <th>Compensatory Leave Taken</th>
              <th>Remaining Compensatory Leave</th>
            </tr>';

    $leaveInfoController=new leaveInfoController();

    $leave_employee=$this->filterLeaveDataEmployee($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type,$emp_id,$start_date,$end_date,$lt->li_id);

    $data='';
    if(isset($leave_employee[0])){
      $c=0;
        foreach ($leave_employee as $le){
          $c++;
           $data.='<tr class="gradeX" id="tr-'.$le->leave_empid.'">
              <td></td>
              <td>'.$c.'</td>
              <td>'.$le->emp_empid.'</td>
              <td>'.$le->emp_name.'</td>
              <td>'.$this->getDepartmentName($le->emp_depart_id).'</td>
              <td>'.$this->getSubDepartmentName($le->emp_sdepart_id).'</td>';
                if($lt){
                    if($leaveInfoController->checkLeaveTypeExist($le->leave_empid,$lt->li_id,$start_date,$end_date)=="1"){
                        $data.='<td>'.$lt->li_qoutaday.'</td>
                          <td>'.$leaveInfoController->leaveTaken($le->leave_empid,$lt->li_id,$start_date,$end_date).'</td>
                          <td>'.$leaveInfoController->leaveRemain($le->leave_empid,$lt->li_id,$lt->li_qoutaday,$start_date,$end_date).'</td>';
                    }else{
                        $data.='<td>'.$lt->li_qoutaday.'</td>
                          <td>0</td>
                          <td>'.$lt->li_qoutaday.'</td>';
                    }
                }
            $data.='</tr>';
        }
    }

    $chart_num_datasets=4;
    $chart_datasets = array(
        '1' => 'Total Employee',
        '3' => 'Compensatory Leave',
    );
    $chart_levels_array=Array();
    $chart_data_array=Array();
    $chart_backgroundColor_array=Array();
    $chart_borderColor_array=Array();

    for ($i=0; $i < $chart_num_datasets ; $i++) {
        $chart_levels='';
        $chart_data='';
        $department=$this->getDepartment($var['emp_depart_id'],$var['emp_id']);
        foreach ($department as $d) {
            if($i=="0"){
                $total=$this->totalEmployee($var,$d,'1');
                $chart_levels.='"'.$d->depart_name.'"'.',';
                $chart_data.=$total.',';
            }elseif($i=="1"){
                $inLeave=$this->inLeave($var,$d,$lt->li_id);
                $chart_levels.='"'.$d->depart_name.'"'.',';
                $chart_data.=$inLeave.',';
            }
        }

        array_push($chart_levels_array, $i,$chart_levels);
        array_push($chart_data_array, $i,$chart_data);
        array_push($chart_backgroundColor_array,$i,$this->backgroundColor(count($department)));
        array_push($chart_borderColor_array,$i,$this->borderColor(count($department)));
    }

    $info=array(
        'chart_show'=>true,
        'page_title'=>$this->getReportTitle($var,'Compensatory Leave Report'),
        'chart_short_title'=>'Compensatory Leave Chart',
        'chart_title'=>$this->getReportTitle($var,'Compensatory Leave Chart'),
        'chart_num_datasets'=>$chart_num_datasets,
        'chart_datasets'=>$chart_datasets,
        'chart_levels'=>$chart_levels_array,
        'chart_data'=>$chart_data_array,
        'chart_backgroundColor'=>$chart_backgroundColor_array,
        'chart_borderColor'=>$chart_borderColor_array,
    );

    return view('Admin.report.view',compact('thead','data','info'));
}

public function sickLeaveReport($var)
{
    $emp_depart_id=$var['emp_depart_id'];
    $emp_sdepart_id=$var['emp_sdepart_id'];
    $emp_desig_id=$var['emp_desig_id'];
    $emp_jlid=$var['emp_jlid'];
    $emp_type=$var['emp_type'];
    $emp_id=$var['emp_id'];
    $start_date=$var['start_date'];
    $end_date=$var['end_date'];
    $lt=DB::table('tbl_leavetype')->where('li_id','4')->first();
    
    $thead='<tr>
              <th></th>
              <th>SL</th>
              <th>Employee ID</th>
              <th>Employee Name</th>
              <th>Department</th>
              <th>Sub-Department</th>
              <th>Sick Leave Qouta</th>
              <th>Sick Leave Taken</th>
              <th>Remaining Sick Leave</th>
            </tr>';

    $leaveInfoController=new leaveInfoController();

    $leave_employee=$this->filterLeaveDataEmployee($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type,$emp_id,$start_date,$end_date,$lt->li_id);

    $data='';
    if(isset($leave_employee[0])){
      $c=0;
        foreach ($leave_employee as $le){
          $c++;
           $data.='<tr class="gradeX" id="tr-'.$le->leave_empid.'">
              <td></td>
              <td>'.$c.'</td>
              <td>'.$le->emp_empid.'</td>
              <td>'.$le->emp_name.'</td>
              <td>'.$this->getDepartmentName($le->emp_depart_id).'</td>
              <td>'.$this->getSubDepartmentName($le->emp_sdepart_id).'</td>';
                if($lt){
                    if($leaveInfoController->checkLeaveTypeExist($le->leave_empid,$lt->li_id,$start_date,$end_date)=="1"){
                        $data.='<td>'.$lt->li_qoutaday.'</td>
                          <td>'.$leaveInfoController->leaveTaken($le->leave_empid,$lt->li_id,$start_date,$end_date).'</td>
                          <td>'.$leaveInfoController->leaveRemain($le->leave_empid,$lt->li_id,$lt->li_qoutaday,$start_date,$end_date).'</td>';
                    }else{
                        $data.='<td>'.$lt->li_qoutaday.'</td>
                          <td>0</td>
                          <td>'.$lt->li_qoutaday.'</td>';
                    }
                }
            $data.='</tr>';
        }
    }

    $chart_num_datasets=4;
    $chart_datasets = array(
        '1' => 'Total Employee',
        '3' => 'Sick Leave',
    );
    $chart_levels_array=Array();
    $chart_data_array=Array();
    $chart_backgroundColor_array=Array();
    $chart_borderColor_array=Array();

    for ($i=0; $i < $chart_num_datasets ; $i++) {
        $chart_levels='';
        $chart_data='';
        $department=$this->getDepartment($var['emp_depart_id'],$var['emp_id']);
        foreach ($department as $d) {
            if($i=="0"){
                $total=$this->totalEmployee($var,$d,'1');
                $chart_levels.='"'.$d->depart_name.'"'.',';
                $chart_data.=$total.',';
            }elseif($i=="1"){
                $inLeave=$this->inLeave($var,$d,$lt->li_id);
                $chart_levels.='"'.$d->depart_name.'"'.',';
                $chart_data.=$inLeave.',';
            }
        }

        array_push($chart_levels_array, $i,$chart_levels);
        array_push($chart_data_array, $i,$chart_data);
        array_push($chart_backgroundColor_array,$i,$this->backgroundColor(count($department)));
        array_push($chart_borderColor_array,$i,$this->borderColor(count($department)));
    }

    $info=array(
        'chart_show'=>true,
        'page_title'=>$this->getReportTitle($var,'Sick Leave Report'),
        'chart_short_title'=>'Sick Leave Chart',
        'chart_title'=>$this->getReportTitle($var,'Sick Leave Chart'),
        'chart_num_datasets'=>$chart_num_datasets,
        'chart_datasets'=>$chart_datasets,
        'chart_levels'=>$chart_levels_array,
        'chart_data'=>$chart_data_array,
        'chart_backgroundColor'=>$chart_backgroundColor_array,
        'chart_borderColor'=>$chart_borderColor_array,
    );

    return view('Admin.report.view',compact('thead','data','info'));
}

public function leaveTakenStatusReport($var)
{
    $emp_depart_id=$var['emp_depart_id'];
    $emp_sdepart_id=$var['emp_sdepart_id'];
    $emp_desig_id=$var['emp_desig_id'];
    $emp_jlid=$var['emp_jlid'];
    $emp_type=$var['emp_type'];
    $emp_id=$var['emp_id'];
    $start_date=$var['start_date'];
    $end_date=$var['end_date'];
    $leave_type=DB::table('tbl_leavetype')->get();
    
    $thead='';
    $thead.='<tr>
              <th></th>
              <th>SL</th>
              <th>Employee ID</th>
              <th>Employee Name</th>
              <th>Department</th>
              <th>Sub-Department</th>';
            if(isset($leave_type)){
                foreach ($leave_type as $lt){
                    $thead.='<th>'.$this->getLeaveTypename($lt->li_id).' Taken</th>';
                }
            }
    $thead.='</tr>';

    $leaveInfoController=new leaveInfoController();

    $leave_employee=$this->filterLeaveDataEmployee($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type,$emp_id,$start_date,$end_date,'0');

    $data='';
    if(isset($leave_employee[0])){
      $c=0;
        foreach ($leave_employee as $le){
          $c++;
           $data.='<tr class="gradeX" id="tr-'.$le->leave_empid.'">
              <td></td>
              <td>'.$c.'</td>
              <td>'.$le->emp_empid.'</td>
              <td>'.$le->emp_name.'</td>
              <td>'.$this->getDepartmentName($le->emp_depart_id).'</td>
              <td>'.$this->getSubDepartmentName($le->emp_sdepart_id).'</td>';
                if($leave_type){
                  foreach ($leave_type as $lt){
                    if($leaveInfoController->checkLeaveTypeExist($le->leave_empid,$lt->li_id,$start_date,$end_date)=="1"){
                        $data.='<td>'.$leaveInfoController->leaveTaken($le->leave_empid,$lt->li_id,$start_date,$end_date).'</td>';
                    }else{
                        $data.='<td>0</td>';
                    }
                  }
                }
            $data.='</tr>';
        }
    }

    $chart_num_datasets=4;
    $chart_datasets = array(
        '1' => 'Total Employee',
        '3' => 'In Leave',
    );
    $chart_levels_array=Array();
    $chart_data_array=Array();
    $chart_backgroundColor_array=Array();
    $chart_borderColor_array=Array();

    for ($i=0; $i < $chart_num_datasets ; $i++) {
        $chart_levels='';
        $chart_data='';
        $department=$this->getDepartment($var['emp_depart_id'],$var['emp_id']);
        foreach ($department as $d) {
            if($i=="0"){
                $total=$this->totalEmployee($var,$d,'1');
                $chart_levels.='"'.$d->depart_name.'"'.',';
                $chart_data.=$total.',';
            }elseif($i=="1"){
                $inLeave=0;
                $chart_levels.='"'.$d->depart_name.'"'.',';
                $chart_data.=$inLeave.',';
            }
        }

        array_push($chart_levels_array, $i,$chart_levels);
        array_push($chart_data_array, $i,$chart_data);
        array_push($chart_backgroundColor_array,$i,$this->backgroundColor(count($department)));
        array_push($chart_borderColor_array,$i,$this->borderColor(count($department)));
    }

    $info=array(
        'chart_show'=>true,
        'page_title'=>$this->getReportTitle($var,'Leave Taken Status Report'),
        'chart_short_title'=>'Leave Taken Status Chart',
        'chart_title'=>$this->getReportTitle($var,'Leave Taken Status Chart'),
        'chart_num_datasets'=>$chart_num_datasets,
        'chart_datasets'=>$chart_datasets,
        'chart_levels'=>$chart_levels_array,
        'chart_data'=>$chart_data_array,
        'chart_backgroundColor'=>$chart_backgroundColor_array,
        'chart_borderColor'=>$chart_borderColor_array,
    );

    return view('Admin.report.view',compact('thead','data','info'));
}

public function food($var,$d,$foodType)
{
    $emp_depart_id=$var['emp_depart_id'];
    $emp_sdepart_id=$var['emp_sdepart_id'];
    $emp_desig_id=$var['emp_desig_id'];
    $emp_jlid=$var['emp_jlid'];
    $emp_type=$var['emp_type'];
    $emp_id=$var['emp_id'];
    $start_date=$var['start_date'];
    $end_date=$var['end_date'];

    $lunch=0;
    $tiffin=0;
    $dinner=0;
    $night=0;
    $employee=$this->filterAttendanceEmployee($d->depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type,$emp_id);
    $Attendance=new attendanceController();
    //todays all employee add (start)//
    $data='';   
    $counter=0;

    foreach ($employee as $emp) {
    $dateRange=$this->getDateRange($start_date,$end_date);
    //employee loop (start)//
    for($count=0;$count<count($dateRange);$count++) {
    $counter++;

    //emp shift info (start)//
    $shift_info=$this->getCurrentShiftInfo($emp->emp_id,$dateRange[$count]);      
    //emp shift info (end)//
    if($Attendance->checkPresent($emp->EmployeeID,$dateRange[$count])=="1"){
        if($shift_info){
            $twh=$this->totalWorkingHours($emp,$dateRange[$count],$shift_info);

            
            $hours=$this->timeToHours($twh['twh']);
            if($hours>=3.95){
                $lunch++;
            }

            if($hours>=9.95){
                $tiffin++;
            }

            if($hours>=11.95){
                $dinner++;
            }

            if($twh["night"]>0){
                $night=$twh["night"];
            }
        }
    }
    }
    }

    if($foodType=="0"){
        return $lunch;
    }elseif($foodType=="1"){
        return $tiffin;
    }elseif($foodType=="2"){
        return $dinner;
    }elseif($foodType=="3"){
        return $night;
    }
}

public function foodReport($var)
{
    $emp_depart_id=$var['emp_depart_id'];
    $emp_sdepart_id=$var['emp_sdepart_id'];
    $emp_desig_id=$var['emp_desig_id'];
    $emp_jlid=$var['emp_jlid'];
    $emp_type=$var['emp_type'];
    $emp_id=$var['emp_id'];
    $start_date=$var['start_date'];
    $end_date=$var['end_date'];
    $dateRange=$this->getDateRange($start_date,$end_date);

    $thead='<tr>
              <th></th>
              <th>SL</th>
              <th>Employee ID</th>
              <th>Face ID</th>
              <th>Employee Name</th>';
    foreach ($dateRange as $date) {
    $thead.='<th>Date</th>
            <th>Lunch</th>
            <th>Tiffin</th>
            <th>Dinner</th>
            <th>Night</th>';
    }
              
    $thead.='<th>Total Lunch</th>
             <th>Total Tiffin</th>
             <th>Total Dinner</th>
             <th>Total Night</th>
            </tr>';

    //todays all employee add (start)//
    $employee=$this->filterAttendanceEmployee($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type,$emp_id);

    $data='';   
    $counter=0;
    $Attendance=new attendanceController();
    foreach ($employee as $emp) {
        $counter++;
        $total_lunch=0;
        $total_tiffin=0;
        $total_dinner=0;
        $total_night=0;
        $data.='<tr>
                <td></td>
                <td>'.$counter.'</td>
                <td>'.$emp->emp_empid.'</td>
                <td>'.$emp->emp_machineid.'</td>
                <td>'.$emp->emp_name.'</td>';

        //employee loop (start)//
        foreach ($dateRange as $date) {
            //emp shift info (start)//
            $shift_info=$this->getCurrentShiftInfo($emp->emp_id,$date);      
            //emp shift info (end)//
            $twh=$this->totalWorkingHours($emp,$date,$shift_info);

            $lunch=0;
            $tiffin=0;
            $dinner=0;
            $hours=$this->timeToHours($twh['twh']);
            $night=0;
            if($hours>=3.95){
                $lunch++;
                $total_lunch++;
            }

            if($hours>=9.95){
                $tiffin++;
                $total_tiffin++;
            }

            if($hours>=11.95){
                $dinner++;
                $total_dinner++;
            }

            if($twh["night"]>0){
                $night=$twh["night"];
                $total_night+=$twh["night"];
            }

            if($hours<=0){
                if($Attendance->entryCount($emp->EmployeeID,$date)>0){
                    $lunch++;
                    $total_lunch++;
                }
            }

            $data.='<td>'.$date.'</td>
                    <td>'.$lunch.'</td>
                    <td>'.$tiffin.'</td>
                    <td>'.$dinner.'</td>
                    <td>'.$night.'</td>';
        }

        $data.='<td>'.$total_lunch.'</td>
                <td>'.$total_tiffin.'</td>
                <td>'.$total_dinner.'</td>
                <td>'.$total_night.'</td>
                </tr>';
    }
    //employee loop (end)//

    /*$chart_num_datasets=6;
    $chart_datasets = array(
        '1' => 'Lunch',
        '3' => 'Tiffin',
        '5' => 'Dinner',
    );
    $chart_levels_array=Array();
    $chart_data_array=Array();
    $chart_backgroundColor_array=Array();
    $chart_borderColor_array=Array();

    for ($i=0; $i < $chart_num_datasets ; $i++) {
        $chart_levels='';
        $chart_data='';
        $department=$this->getDepartment($var['emp_depart_id'],$var['emp_id']);
        foreach ($department as $d) {
            $food=$this->food($var,$d,$i);
            $chart_levels.='"'.$d->depart_name.'"'.',';
            $chart_data.=$food.',';
        }

        array_push($chart_levels_array, $i,$chart_levels);
        array_push($chart_data_array, $i,$chart_data);
        array_push($chart_backgroundColor_array,$i,$this->backgroundColor(count($department)));
        array_push($chart_borderColor_array,$i,$this->borderColor(count($department)));
    }*/

    $info=array(
        'chart_show'=>false,
        'page_title'=>$this->getReportTitle($var,'Food Report'),
        /*'chart_short_title'=>'Food Chart',
        'chart_title'=>$this->getReportTitle($var,'Food Chart'),
        'chart_num_datasets'=>$chart_num_datasets,
        'chart_datasets'=>$chart_datasets,
        'chart_levels'=>$chart_levels_array,
        'chart_data'=>$chart_data_array,
        'chart_backgroundColor'=>$chart_backgroundColor_array,
        'chart_borderColor'=>$chart_borderColor_array,*/
    );

    return view('Admin.report.view',compact('thead','data','info'));
}

public function employeeInsuranceDataReport($var)
{
    $emp_depart_id=$var['emp_depart_id'];
    $emp_sdepart_id=$var['emp_sdepart_id'];
    $emp_desig_id=$var['emp_desig_id'];
    $emp_jlid=$var['emp_jlid'];
    $emp_type=$var['emp_type'];
    $emp_id=$var['emp_id'];

    $thead='<tr>
              <th></th>
              <th>SL</th>
              <th>Employee ID</th>
              <th>Face ID</th>
              <th>Employee Name</th>
              <th>Self Member ID</th>
              <th>Effective Date</th>
              <th>Spouse Memeber ID</th>
              <th>Name</th>
              <th>Date of Birth</th>
              <th>Start Date</th>
              <th>End Date</th>
              <th>Child-1 ID</th>
              <th>Name</th>
              <th>Date of Birth</th>
              <th>Start Date</th>
              <th>End Date</th>
              <th>Child-2 ID</th>
              <th>Name</th>
              <th>Date of Birth</th>
              <th>Start Date</th>
              <th>End Date</th>
              <th>Last Updated At</th>
            </tr>';

    $Insurance=Insurance::
        join('tbl_employee','tbl_insurance.emp_id','=','tbl_employee.emp_id')
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
        ->where('tbl_employee.emp_status','1')
        ->orderBy('tbl_employee.emp_name','ASC')
        ->get(['tbl_employee.*','tbl_insurance.*']);

    $data='';
    if(isset($Insurance[0])){
    $c=0;
        foreach ($Insurance as $ed){
        $c++;
         $data.='<tr>
            <td></td>
            <td>'.$c.'</td>
            <td>'.str_replace(' ','',$ed->emp_empid).'</td>
            <td>'.$ed->emp_machineid.'</td>
            <td><a href="'.URL::to('employee-details').'/'.$ed->emp_id.'/view">'.$ed->emp_name.'</a></td>
            <td>'.$ed->self_member_id.'</td>
            <td>'.$ed->effective_date.'</td>
            <td>'.$ed->spouse_member_id.'</td>
            <td>'.$ed->spouse_name.'</td>
            <td>'.$ed->spouse_dob.'</td>
            <td>'.$ed->spouse_start_date.'</td>
            <td>'.$ed->spouse_end_date.'</td>
            <td>'.$ed->child1_member_id.'</td>
            <td>'.$ed->child1_name.'</td>
            <td>'.$ed->child1_dob.'</td>
            <td>'.$ed->child1_start_date.'</td>
            <td>'.$ed->child1_end_date.'</td>
            <td>'.$ed->child2_member_id.'</td>
            <td>'.$ed->child2_name.'</td>
            <td>'.$ed->child2_dob.'</td>
            <td>'.$ed->child2_start_date.'</td>
            <td>'.$ed->child2_end_date.'</td>
            <td>'.$ed->last_updated_at.'</td>
          </tr>';
        }
    }
    //employee loop (end)//

    /*$chart_num_datasets=6;
    $chart_datasets = array(
        '1' => 'Lunch',
        '3' => 'Tiffin',
        '5' => 'Dinner',
    );
    $chart_levels_array=Array();
    $chart_data_array=Array();
    $chart_backgroundColor_array=Array();
    $chart_borderColor_array=Array();

    for ($i=0; $i < $chart_num_datasets ; $i++) {
        $chart_levels='';
        $chart_data='';
        $department=$this->getDepartment($var['emp_depart_id'],$var['emp_id']);
        foreach ($department as $d) {
            $food=$this->food($var,$d,$i);
            $chart_levels.='"'.$d->depart_name.'"'.',';
            $chart_data.=$food.',';
        }

        array_push($chart_levels_array, $i,$chart_levels);
        array_push($chart_data_array, $i,$chart_data);
        array_push($chart_backgroundColor_array,$i,$this->backgroundColor(count($department)));
        array_push($chart_borderColor_array,$i,$this->borderColor(count($department)));
    }*/

    $info=array(
        'chart_show'=>false,
        'page_title'=>$this->getReportTitle($var,'Food Report'),
        /*'chart_short_title'=>'Food Chart',
        'chart_title'=>$this->getReportTitle($var,'Food Chart'),
        'chart_num_datasets'=>$chart_num_datasets,
        'chart_datasets'=>$chart_datasets,
        'chart_levels'=>$chart_levels_array,
        'chart_data'=>$chart_data_array,
        'chart_backgroundColor'=>$chart_backgroundColor_array,
        'chart_borderColor'=>$chart_borderColor_array,*/
    );

    return view('Admin.report.view',compact('thead','data','info'));
}

}