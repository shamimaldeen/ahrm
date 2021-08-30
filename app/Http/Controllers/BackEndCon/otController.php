<?php

namespace App\Http\Controllers\BackEndCon;

use Illuminate\Http\Request;
use App\Http\Controllers\BackEndCon\Controller;
use Auth;
use Session;
use DB;
use DateTime;
use Mail;

use App\Holiday;
use App\Employee;
use App\EmployeeTypes;
use App\TotalWorkingHours;

class otController extends Controller
{

public function filterOTEmployee($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type,$emp_id)
{
   $id =   Auth::guard('admin')->user();
   if($id->suser_level=="1"){
        return $employee=DB::table('tbl_employee')
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
                return $query->where('tbl_checkinout.emp_id', $emp_id);
            })
            ->orderBy('tbl_employee.emp_name','asc')
            ->get(['emp_id','emp_name','emp_empid','emp_machineid']);
    }elseif($id->suser_level=="4"){
        return $employee=DB::table('tbl_employee')
            ->where('tbl_employee.emp_seniorid',$id->suser_empid)
            ->orWhere('tbl_employee.emp_id',$id->suser_empid)
            ->orderBy('tbl_employee.emp_name','asc')
            ->get(['emp_id','emp_name','emp_empid','emp_machineid']);
    }elseif($id->suser_level=="2" or $id->suser_level=="3" or $id->suser_level=="5" or $id->suser_level=="6"){
        return $employee=DB::table('tbl_employee')
            ->where('tbl_employee.emp_id',$id->suser_empid)
            ->orderBy('tbl_employee.emp_name','asc')
            ->get(['emp_id','emp_name','emp_empid','emp_machineid']);
    } 
}

public function getOTRow($emp,$counter,$id,$ot,$date)
{
    $data='';
    $data.='<tr class="gradeX" id="tr-'.$emp->emp_id.'">
            <td id="hidden">
                <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                    <input type="checkbox" class="checkboxes" value="'.$emp->emp_id.'&'.$date.'" name="ot_id[]" />
                    <span></span>
                </label>
            </td>
            <td>'.$counter.'</td>
            <td>'.$emp->emp_empid.'</td>';
    if($id->suser_level=="1"){
        $data.='<td>'.$emp->emp_machineid.'</td>';
    }
    $data.='<td>'.$emp->emp_name.'</td>
            <td>'.$date.'</a>';
    $data.='</td>';
            // <td>'.$total_osd.'</td>
            // <td>'.$twh.'</td>
    $data.='<td>'.$ot.'</td>
          </tr>';
    return $data;
}

public function ot(){
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
    $lmEmployee=DB::table('tbl_employee')
        ->where('emp_seniorid',$id->suser_empid)
        ->where('emp_status','1')
        ->orderBy('emp_name','asc')
        ->get();
    
    //todays all employee add (start)//
    $employee=$this->filterOTEmployee('0','0','0','0','0','0');
    //todays all employee add (end)//

    $data='';
    $counter=0;
    foreach ($employee as $emp) {
        $counter++;
        // $shift_info=$this->getCurrentShiftInfo($emp->emp_id,date('Y-m-d'));
        // $totalWorkingHours=TotalWorkingHours::where(['emp_id'=>$emp->emp_id,'date'=>date('Y-m-d')])->first();
        // $twh='00:00:00';
        // $ot='00:00:00';
        // $osd='00:00:00';
        // if(isset($totalWorkingHours->id)){
        //     $twh=$totalWorkingHours->twh;
        //     $ot=$this->otHour($shift_info,$totalWorkingHours->twh);
        //     $osd=$totalWorkingHours->total_osd;
        // }
        // $data.=$this->getOTRow($emp,$counter,$id,$osd,$twh,$ot,date('Y-m-d'));
        $assign=DB::table('tbl_otapplication')->where([
            'otapp_empid'=>$emp->emp_id,
            'otapp_perdate'=>date('Y-m-d')
        ])->first();
        $ot=0;
        if(isset($assign->otapp_id)){
            $ot=$assign->otapp_totalhrs;
        }
        $data.=$this->getOTRow($emp,$counter,$id,$ot,date('Y-m-d'));
    }
    
    return view('Admin.ot.index',compact('id','mainlink','sublink','Adminminlink','adminsublink','data','types','designation','department','joblocation','lmEmployee'));
}


public function otHour($shift_info,$twh)
{
    $ot_hour='00:00:00';
    if($shift_info->shift_type=="1"){
        if(substr($twh,0,2)>='7'){
            $to_time = new DateTime($twh);
            $total_from_time = new DateTime('08:00:00');
            $total_duration = $to_time->diff($total_from_time);
            if($total_duration->h>0){
                $ot_hour=$total_duration->format('%H:%I:%S');
            }else{
                if($total_duration->i>=30){
                    $ot_hour=$total_duration->format('%H:%I:%S');
                }
            }
        }
    }else if($shift_info->shift_type=="2"){
        if(substr($twh,0,2)>='8'){
            $to_time = new DateTime($twh);
            $total_from_time = new DateTime('09:00:00');
            $total_duration = $to_time->diff($total_from_time);
            if($total_duration->h>0){
                $ot_hour=$total_duration->format('%H:%I:%S');
            }else{
                if($total_duration->i>=30){
                    $ot_hour=$total_duration->format('%H:%I:%S');
                }
            }
        }
    }

    return $ot_hour;
}

public function otSearch($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type,$emp_id,$start_date,$end_date)
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
    $lmEmployee=DB::table('tbl_employee')
        ->where('emp_seniorid',$id->suser_empid)
        ->where('emp_status','1')
        ->orderBy('emp_name','asc')
        ->get();
    
    //todays all employee add (start)//
    $employee=$this->filterOTEmployee($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type,$emp_id);
    //todays all employee add (end)//

    $data='';
    $counter=0;
    foreach ($employee as $emp) {
    $dateRange=$this->getDateRange($start_date,$end_date);
        for($count=0;$count<count($dateRange);$count++) {
            $counter++;
            // $shift_info=$this->getCurrentShiftInfo($emp->emp_id,$dateRange[$count]);
            // $totalWorkingHours=TotalWorkingHours::where(['emp_id'=>$emp->emp_id,'date'=>$dateRange[$count]])->first();
            // $twh='00:00:00';
            // $ot='00:00:00';
            // $osd='00:00:00';
            // if(isset($totalWorkingHours->id)){
            //     $twh=$totalWorkingHours->twh;
            //     $ot=$this->otHour($shift_info,$totalWorkingHours->twh);
            //     $osd=$totalWorkingHours->total_osd;
            // }
            // $data.=$this->getOTRow($emp,$counter,$id,$osd,$twh,$ot,$dateRange[$count]);
            $assign=DB::table('tbl_otapplication')->where([
                'otapp_empid'=>$emp->emp_id,
                'otapp_perdate'=>$dateRange[$count]
            ])->first();
            $ot=0;
            if(isset($assign->otapp_id)){
                $ot=$assign->otapp_totalhrs;
            }
            $data.=$this->getOTRow($emp,$counter,$id,$ot,$dateRange[$count]);
        }
    }

    return view('Admin.ot.index',compact('id','mainlink','sublink','Adminminlink','adminsublink','data','types','designation','department','joblocation','emp_depart_id','emp_sdepart_id','emp_desig_id','emp_jlid','emp_type','start_date','end_date','lmEmployee'));
}

public function holidayWeekendTWh($emp,$date,$twh)
{
    if($this->timeToHours($twh)<=0){
        return $twh;
    }
    //if($this->checkHoliday($date)=="1" or $this->checkWeekend($emp,$date)=="1"){
    if($this->checkHoliday($date)=="1"){
        $twh = strtotime($twh)-strtotime("00:00:00");
        $twh = date("H:i:s",strtotime($twh)+$twh);
    }
    return $twh;
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
    if(isset($emp->emp_wknd)){
        if($this->dayName($emp->emp_wknd)==date("D",strtotime($date))){
            return '1';
        }else{
            return '0';
        }
    }else{
        return '0';
    }
    
}

public function filterOTApplicationEmployee($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type,$emp_id,$start_date,$end_date,$status,$type)
{
    $id =   Auth::guard('admin')->user();
    if($id->suser_level=="1"){
        return $otapplication=DB::table('tbl_otapplication')
            ->join('tbl_employee','tbl_otapplication.otapp_empid','=','tbl_employee.emp_id')
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
            ->when($status>0, function($query) use ($status){
                return $query->where('tbl_otapplication.otapp_status','>','0');
            })
            ->when($status<=0, function($query) use ($status){
                return $query->where('tbl_otapplication.otapp_status','0');
            })
            ->when($start_date!="0" && $end_date!="0", function($query) use ($start_date,$end_date){
                return $query->whereBetween('tbl_otapplication.otapp_perdate', [$start_date, $end_date]);
            })
            ->where('tbl_otapplication.otapp_type',$type)
            ->orderBy('tbl_otapplication.otapp_reqdate','DESC')
            ->get(['tbl_otapplication.*','tbl_employee.emp_empid','tbl_employee.emp_id','tbl_employee.emp_name']);
    }elseif($id->suser_level=="4"){
        return $otapplication=DB::table('tbl_otapplication')
            ->join('tbl_employee','tbl_otapplication.otapp_empid','=','tbl_employee.emp_id')
            ->where('tbl_employee.emp_seniorid',$id->suser_empid)
            ->orWhere('tbl_employee.emp_id',$id->suser_empid)
            ->when($start_date!="0" && $end_date!="0", function($query) use ($start_date,$end_date){
                return $query->whereBetween('tbl_otapplication.otapp_perdate', [$start_date, $end_date]);
            })
            ->when($status>0, function($query) use ($status){
                return $query->where('tbl_otapplication.otapp_status','>','0');
            })
            ->when($status<=0, function($query) use ($status){
                return $query->where('tbl_otapplication.otapp_status','0');
            })
            ->where('tbl_otapplication.otapp_type',$type)
            ->orderBy('tbl_otapplication.otapp_reqdate','DESC')
            ->get(['tbl_otapplication.*','tbl_employee.emp_empid','tbl_employee.emp_id','tbl_employee.emp_name']);
    }else{
        return $otapplication=DB::table('tbl_otapplication')
            ->join('tbl_employee','tbl_otapplication.otapp_empid','=','tbl_employee.emp_id')
            ->where('tbl_employee.emp_id',$id->suser_empid)
            ->when($start_date!="0" && $end_date!="0", function($query) use ($start_date,$end_date){
                return $query->whereBetween('tbl_otapplication.otapp_perdate', [$start_date, $end_date]);
            })
            ->when($status>0, function($query) use ($status){
                return $query->where('tbl_otapplication.otapp_status','>','0');
            })
            ->when($status<=0, function($query) use ($status){
                return $query->where('tbl_otapplication.otapp_status','0');
            })
            ->where('tbl_otapplication.otapp_type',$type)
            ->orderBy('tbl_otapplication.otapp_reqdate','DESC')
            ->get(['tbl_otapplication.*','tbl_employee.emp_empid','tbl_employee.emp_id','tbl_employee.emp_name']);
    }
}

public function getOTApplicationRow($otapplication,$status)
{
    $counter=0;
    $data='';
    if(isset($otapplication[0])){
        foreach ($otapplication as $oa){
            if($oa->otapp_status==$status){
            $counter++;
            $data.='<tr class="gradeX" id="tr-'.$oa->otapp_id.'">
                <td>
                    <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                        <input type="checkbox" class="checkboxes" value="'.$oa->otapp_id.'" name="otapp_id[]" />
                        <span></span>
                    </label>
                </td>
                <td>'.$counter.'</td>
                <td>'.$oa->emp_empid.'</td>
                <td>'.$oa->emp_name.'</td>
                <td>'.$oa->otapp_reqdate.'</td>
                <td>'.$oa->otapp_perdate.'</td>
                <td>'.$oa->otapp_totalhrs.'</td>
                <td>';
                  if($oa->otapp_status=="0"){
                    $data.='<a class="btn btn-warning btn-xs">Pending</a>';
                  }elseif($oa->otapp_status=="1"){
                    $data.='<a class="btn btn-success btn-xs">Aprooved</a>';
                  }elseif($oa->otapp_status=="2"){
                    $data.='<a class="btn btn-danger btn-xs">Denied</a>';
                  }
                $data.='</td>';
                if($oa->otapp_type=="1"){
                    $data.='<td><a class="btn btn-xs btn-primary">'.$this->getSeniorName($oa->otapp_empid).'</a></td>';
                    $data.='<td><a class="btn btn-xs btn-success">'.$this->getSeniorName($oa->otapp_assignedby).'</a></td>';
                }
              $data.='</tr>';
            }
        }
    }
    return $data;
}

public function otApplication(){
    $id =   Auth::guard('admin')->user();
    $mainlink = $this->adminmainmenu();
    $sublink = $this->adminsubmenu();
    $Adminminlink = $this->adminlink();
    $adminsublink = $this->adminsublink();

    $types=EmployeeTypes::orderBy('name','asc')->get();
    $designation=DB::table('tbl_designation')->get();
    $department=DB::table('tbl_department')->get();
    $joblocation=DB::table('tbl_joblocation')->get();

    $otapplication=$this->filterOTApplicationEmployee('0','0','0','0','0','0','0','0','1','0');

    $data=$this->getOTApplicationRow($otapplication,'1');
    $data.=$this->getOTApplicationRow($otapplication,'2');
    $title="OT Application List";
    return view('Admin.ot.otApplication',compact('id','mainlink','sublink','Adminminlink','adminsublink','data','types','designation','department','joblocation','title'));
}

public function otApplicationSearch($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type,$emp_id,$start_date,$end_date)
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

    $otapplication=$this->filterOTApplicationEmployee($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type,$emp_id,$start_date,$end_date,'1','0');

    $data=$this->getOTApplicationRow($otapplication,'1');
    $data.=$this->getOTApplicationRow($otapplication,'2');
    $title="OT Application List";
    return view('Admin.ot.otApplication',compact('id','mainlink','sublink','Adminminlink','adminsublink','data','types','designation','department','joblocation','emp_depart_id','emp_sdepart_id','emp_desig_id','emp_jlid','emp_type','start_date','end_date','title'));
}

public function pendingOTApplication(){
    $id =   Auth::guard('admin')->user();
    $mainlink = $this->adminmainmenu();
    $sublink = $this->adminsubmenu();
    $Adminminlink = $this->adminlink();
    $adminsublink = $this->adminsublink();

    $types=EmployeeTypes::orderBy('name','asc')->get();
    $designation=DB::table('tbl_designation')->get();
    $department=DB::table('tbl_department')->get();
    $joblocation=DB::table('tbl_joblocation')->get();

    $otapplication=$this->filterOTApplicationEmployee('0','0','0','0','0','0','0','0','0','0');

    $data=$this->getOTApplicationRow($otapplication,'0');
    $title="Pending OT Application List";
    return view('Admin.ot.otApplication',compact('id','mainlink','sublink','Adminminlink','adminsublink','data','types','designation','department','joblocation','title'));
}

public function pendingOTApplicationSearch($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type,$emp_id,$start_date,$end_date)
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

    $otapplication=$this->filterOTApplicationEmployee($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type,$emp_id,$start_date,$end_date,'0','0');

    $data=$this->getOTApplicationRow($otapplication,'0');
    $title="Pending OT Application List";
    return view('Admin.ot.otApplication',compact('id','mainlink','sublink','Adminminlink','adminsublink','data','types','designation','department','joblocation','emp_depart_id','emp_sdepart_id','emp_desig_id','emp_jlid','emp_type','start_date','end_date','title'));
}

public function assignedOTApplication(){
    $id =   Auth::guard('admin')->user();
    $mainlink = $this->adminmainmenu();
    $sublink = $this->adminsubmenu();
    $Adminminlink = $this->adminlink();
    $adminsublink = $this->adminsublink();

    $types=EmployeeTypes::orderBy('name','asc')->get();
    $designation=DB::table('tbl_designation')->get();
    $department=DB::table('tbl_department')->get();
    $joblocation=DB::table('tbl_joblocation')->get();

    $otapplication=$this->filterOTApplicationEmployee('0','0','0','0','0','0','0','0','1','1');

    $data=$this->getOTApplicationRow($otapplication,'1');
    $title="Assigned OT Application List";
    if($id->suser_level=="1"){
        $employees=Employee::where('emp_status',1)->get();
    }else{
        $employees=Employee::where(function($query) use($id){
            return $query->where('emp_seniorid',$id->suser_empid)
                         ->orWhere('emp_authperson',$id->suser_empid);
        })
        ->where('emp_status',1)
        ->get();
    }
    
    return view('Admin.ot.otApplication',compact('id','mainlink','sublink','Adminminlink','adminsublink','data','types','designation','department','joblocation','title','employees'));
}

public function assignedOTApplicationSearch($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type,$emp_id,$start_date,$end_date)
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

    $otapplication=$this->filterOTApplicationEmployee($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type,$emp_id,$start_date,$end_date,'1','1');

    $data=$this->getOTApplicationRow($otapplication,'1');
    $title="Assigned OT Application List";

    if($id->suser_level=="1"){
        $employees=Employee::where('emp_status',1)->get();
    }else{
        $employees=Employee::where(function($query) use($id){
            return $query->where('emp_seniorid',$id->suser_empid)
                         ->orWhere('emp_authperson',$id->suser_empid);
        })
        ->where('emp_status',1)
        ->get();
    }
    return view('Admin.ot.otApplication',compact('id','mainlink','sublink','Adminminlink','adminsublink','data','types','designation','department','joblocation','emp_depart_id','emp_sdepart_id','emp_desig_id','emp_jlid','emp_type','start_date','end_date','title','employees'));
}

public function pendingAssignedOTApplication(){
    $id =   Auth::guard('admin')->user();
    $mainlink = $this->adminmainmenu();
    $sublink = $this->adminsubmenu();
    $Adminminlink = $this->adminlink();
    $adminsublink = $this->adminsublink();

    $types=EmployeeTypes::orderBy('name','asc')->get();
    $designation=DB::table('tbl_designation')->get();
    $department=DB::table('tbl_department')->get();
    $joblocation=DB::table('tbl_joblocation')->get();

    $otapplication=$this->filterOTApplicationEmployee('0','0','0','0','0','0','0','0','1','0');

    $data=$this->getOTApplicationRow($otapplication,'0');
    $title="Pending Assigned OT Application List";
    return view('Admin.ot.otApplication',compact('id','mainlink','sublink','Adminminlink','adminsublink','data','types','designation','department','joblocation','title'));
}

public function pendingAssignedOTApplicationSearch($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type,$emp_id,$start_date,$end_date)
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

    $otapplication=$this->filterOTApplicationEmployee($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type,$emp_id,$start_date,$end_date,'1','1');

    $data=$this->getOTApplicationRow($otapplication,'1');
    $data.=$this->getOTApplicationRow($otapplication,'2');
    $title="Assigned OT Application List";
    return view('Admin.ot.otApplication',compact('id','mainlink','sublink','Adminminlink','adminsublink','data','types','designation','department','joblocation','emp_depart_id','emp_sdepart_id','emp_desig_id','emp_jlid','emp_type','start_date','end_date','title'));
}

public function otRequestSubmit(Request $request){
    $id =   Auth::guard('admin')->user();
    $this->validate($request,[
        'otapp_perdate'=>'required',
        'otapp_fromtime'=>'required',
        'otapp_totime'=>'required',
    ]);

    $to_time = new DateTime($request->otapp_totime.':00 0000-00-00');
    $from_time = new DateTime($request->otapp_fromtime.':00 0000-00-00');
    $difference = $to_time->diff($from_time);
    $check='';
    if($difference->h>=0){
        if($difference->h==0){
            if($difference->i>0){
                $check="success";
            }else{
                $check="error";
            }
        }else if($difference->h>0){
            if($difference->i>=0){
                $check="success";
            }else{
                $check="error";
            }
        }
    }else{
        $check="error";
    }

    if($check=="success"){

        if($difference->h<10){
            if($difference->i<10){
            $total_ot_time='0'.$difference->h.':0'.$difference->i;
            }else{
            $total_ot_time='0'.$difference->h.':'.$difference->i;  
            }
        }else{
            if($difference->i<10){
            $total_ot_time=$difference->h.':0'.$difference->i;
            }else{
            $total_ot_time=$difference->h.':'.$difference->i;  
            }
        }

        $insert=DB::table('tbl_otapplication')
            ->insert([
                'otapp_empid'=>$id->suser_empid,
                'otapp_reqdate'=>date('Y-m-d'),
                'otapp_perdate'=>$request->otapp_perdate,
                'otapp_fromtime'=>$request->otapp_fromtime,
                'otapp_totime'=>$request->otapp_totime,
                'otapp_totalhrs'=>$total_ot_time,
                'otapp_type'=>'0',
            ]);
            if($insert){
                Session::flash('success','OT Request Submitted Successfully.');
                $employee=DB::table('tbl_employee')->where('tbl_employee.emp_id',$id->suser_empid)->first();
                /*$msg_senior='<h3>Dear Sir/Madam,</h3><br>Due to assigninment/work, I need to perform the duties after my normal woring hour. <br>In this regard, kindly allow me to work for over time. Approximate time require for perform the job which is followed by-<br><br><br> OT Requested Day : '.Date('Y-m-d').' ,<br><br> OT Perform Day : '.$request->otapp_perdate.' ,<br><br> OT From : '.$request->otapp_fromtime.' to '.$request->otapp_totime.' ,<br><br> OT Duration is '.$total_ot_time.'<br><br>Regards,<br>'.$employee->emp_name;
                $msg_employee='OT Request Submitted To - '.$this->getSeniorName($employee->emp_seniorid).',which is followed by-<br><br><br> OT Requested Day : '.Date('Y-m-d').' ,<br><br> OT Perform Day : '.$request->otapp_perdate.' ,<br><br> OT From : '.$request->otapp_fromtime.' to '.$request->otapp_totime.' ,<br><br> OT Duration is '.$total_ot_time;
                $this->sendMail($id->email,'OT Request Submitted to '.$this->getSeniorName($employee->emp_seniorid),$msg_employee);
                $this->sendMail($this->getSeniorEmail($employee->emp_seniorid),'An OT Request Has Received from '.$employee->emp_name,$msg_senior);*/
                $this->empHistory($id->suser_empid,'OT Request Submitted To - <b>'.$this->getSeniorName($employee->emp_seniorid).'</b>,which is followed by-<br> OT Requested Day : <b>'.Date('Y-m-d').'</b> ,<br> OT Perform Day : <b>'.$request->otapp_perdate.'</b> ,<br> OT From : <b>'.$request->otapp_fromtime.'</b> to <b>'.$request->otapp_totime.'</b> ,<br> OT Duration is <b>'.$total_ot_time.'</b>');
                return redirect('ot-application');
            }else{
                Session::flash('error','Whoops!! Something Went Wrong!! Try Again.');
                return redirect('ot-application');
            }
    }elseif ($check=="error") {
        Session::flash('error','OT Time Is Not Valid');
        return redirect('ot-application');
    }else{
        Session::flash('error','OT Time Is Not Valid');
        return redirect('ot-application');
    }
}

public function otAprove(Request $request){
     $id =   Auth::guard('admin')->user();
    if(count($request->otapp_id)>0){
        $aprove_count=0;
        $error_count=0;
        $not_permitted=0;
        for ($i=0; $i < count($request->otapp_id) ; $i++) {
            $check=DB::table('tbl_otapplication')->where('otapp_id',$request->otapp_id[$i])->first();
            if($check->otapp_status=="0"){
                if($this->permittedEmployee($check->otapp_empid)=="1"){
                    $aprove=DB::table('tbl_otapplication')
                    ->where('otapp_id',$request->otapp_id[$i])
                    ->update(['otapp_status'=>'1','otapp_appdt'=>date('Y-m-d h:i:s'),'otapp_appuserid'=>$id->suser_empid]);
                    if($aprove){
                        $employee=DB::table('tbl_employee')->where('tbl_employee.emp_id',$check->otapp_empid)->first();
                        $this->empHistory($check->otapp_empid,'OT Request Has Been Approved which was Submitted To - <b>'.$this->getSeniorName($employee->emp_seniorid).'</b>,followed by-<br> OT Requested Day : <b>'.$check->otapp_reqdate.'</b> ,<br> OT Perform Day : <b>'.$check->otapp_perdate.'</b> ,<br> OT From : <b>'.$check->otapp_fromtime.'</b> to <b>'.$check->otapp_totime.'</b> ,<br> OT Duration is <b>'.$check->otapp_totalhrs.'</b>');
                        $aprove_count++;
                    }
                }else{
                    $not_permitted++;
                }
            }else{
                $error_count++;
            }

            if($aprove_count>0){
                Session::flash('success',$aprove_count.' OT Application(s) Approved Successfully.');
            }

            if($error_count>0 or $not_permitted>0){
                $data='';
                if($error_count>0){
                    $data.=$error_count.' OT Application(s) Cannot Be Approved. May Be These OT Application(s) Approoved/Denied Previously.';
                }
                if($not_permitted>0){
                    $data.='And '.$not_permitted.' OT Application(s) Cannot Be Approved because you cannot aprove your own OT Application(s)';
                }
                Session::flash('error',$data);
            }
        }
        return '1';
    }else{
        return 'null';
    }
    
}

public function otDeny(Request $request){
     $id =   Auth::guard('admin')->user();
    if(count($request->otapp_id)>0){
        $deny_count=0;
        $error_count=0;
        $not_permitted=0;
        for ($i=0; $i < count($request->otapp_id) ; $i++) {
            $check=DB::table('tbl_otapplication')->where('otapp_id',$request->otapp_id[$i])->first();
            if($check->otapp_status=="0"){
                if($this->permittedEmployee($check->otapp_empid)=="1"){
                    $deny=DB::table('tbl_otapplication')
                    ->where('otapp_id',$request->otapp_id[$i])
                    ->update(['otapp_status'=>'2','otapp_appdt'=>date('Y-m-d h:i:s'),'otapp_appuserid'=>$id->suser_empid]);
                    if($deny){
                        $employee=DB::table('tbl_employee')->where('tbl_employee.emp_id',$check->otapp_empid)->first();
                        $this->empHistory($check->otapp_empid,'OT Request Has Been Denied which was Submitted To - <b>'.$this->getSeniorName($employee->emp_seniorid).'</b>,followed by-<br> OT Requested Day : <b>'.$check->otapp_reqdate.'</b> ,<br> OT Perform Day : <b>'.$check->otapp_perdate.'</b> ,<br> OT From : <b>'.$check->otapp_fromtime.'</b> to <b>'.$check->otapp_totime.'</b> ,<br>OT Duration is <b>'.$check->otapp_totalhrs.'</b>');
                        $deny_count++;
                    }
                }else{
                    $not_permitted++;
                }
            }else{
                $error_count++;
            }

            if($deny_count>0){
                Session::flash('success',$deny_count.' OT Application(s) Denied Successfully.');
            }

            if($error_count>0 or $not_permitted>0){
                $data='';
                if($error_count>0){
                    $data.=$error_count.' OT Application(s) Cannot Be Denied. May Be These OT Application(s) Approoved/Denied Previously.';
                }
                if($not_permitted>0){
                    $data.='And '.$not_permitted.' OT Application(s) Cannot Be Denied because you cannot deny your own OT Application(s)';
                }
                Session::flash('error',$data);
            }
        }
        return '1';
    }else{
        return 'null';
    }
}

public function getSubDepartment($emp_depart_id)
{
    echo '<option value="0">Select Sub-Department</option>';
    $subdepartment=DB::table('tbl_subdepartment')->where('sdepart_departid',$emp_depart_id)->get();
    if($subdepartment){
        foreach ($subdepartment as $sdepart) {
            echo '<option value="'.$sdepart->sdepart_id.'">'.$sdepart->sdepart_name.'</option>';
        }
    }else{
        echo '';
    }
}


public function getOTEmployee($emp_depart_id,$emp_sdepart_id,$emp_desig_id)
{
    echo '<option value="0">Select Employee</option>';

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
        ->get();

    if($employee){
        foreach ($employee as $emp) {
            echo '<option value="'.$emp->emp_id.'">'.$emp->emp_name.' ('.$emp->emp_empid.')</option>';
        }
    }
}

public function getOTEmployeeForLM()
{
    $id =   Auth::guard('admin')->user();
    echo '<option value="0">Select Employee</option>';

    $employee=DB::table('tbl_employee')
        ->where('emp_seniorid',$id->suser_empid)
        ->orderBy('emp_name','asc')
        ->get();

    if($employee){
        foreach ($employee as $emp) {
            echo '<option value="'.$emp->emp_id.'">'.$emp->emp_name.' ('.$emp->emp_empid.')</option>';
        }
    }
}

public function otApplicationAssignSubmit(Request $request)
{
    $id =   Auth::guard('admin')->user();
    $this->validate($request,[
        'otapp_perdate'=>'required',
        // 'otapp_totime'=>'required',
    ]);

    // $to_time = new DateTime($request->otapp_totime.':00 0000-00-00');
    // $from_time = new DateTime($request->otapp_fromtime.':00 0000-00-00');
    // $difference = $to_time->diff($from_time);
    // $check='';
    // if($difference->h>=0){
    //     if($difference->h==0){
    //         if($difference->i>0){
    //             $check="success";
    //         }else{
    //             $check="error";
    //         }
    //     }else if($difference->h>0){
    //         if($difference->i>=0){
    //             $check="success";
    //         }else{
    //             $check="error";
    //         }
    //     }
    // }else{
    //     $check="error";
    // }

    // if($check=="success"){

    //     if($difference->h<10){
    //         if($difference->i<10){
    //         $total_ot_time='0'.$difference->h.':0'.$difference->i;
    //         }else{
    //         $total_ot_time='0'.$difference->h.':'.$difference->i;  
    //         }
    //     }else{
    //         if($difference->i<10){
    //         $total_ot_time=$difference->h.':0'.$difference->i;
    //         }else{
    //         $total_ot_time=$difference->h.':'.$difference->i;  
    //         }
    //     }

    if(count($request->employees)>0){
        foreach ($request->employees as $key => $employee) {
            if(isset($request->hours[$employee]) && !empty($request->hours[$employee]) && $request->hours[$employee]>0){
                $search=DB::table('tbl_otapplication')->where([
                    'otapp_empid' => $employee,
                    'otapp_perdate' => $request->otapp_perdate
                ])->first();
                if(isset($search->otapp_id)){
                    $assign=DB::table('tbl_otapplication')
                        ->where('otapp_id',$search->otapp_id)
                        ->update([
                            'otapp_reqdate'=>date('Y-m-d'),
                            'otapp_perdate'=>$request->otapp_perdate,
                            'otapp_totalhrs'=>$request->hours[$employee],
                            'otapp_status'=>'1',
                            'otapp_appdt'=>date('Y-m-d H:i:s'),
                            'otapp_appuserid'=>$id->suser_empid,
                            'otapp_type'=>'1',
                            'otapp_assignedby'=>$id->suser_empid,
                        ]);
                }else{
                    $assign=DB::table('tbl_otapplication')
                        ->insert([
                            'otapp_empid'=>$employee,
                            'otapp_reqdate'=>date('Y-m-d'),
                            'otapp_perdate'=>$request->otapp_perdate,
                            'otapp_fromtime'=>date('H:i:s'),
                            'otapp_totime'=>date('H:i:s'),
                            'otapp_totalhrs'=>$request->hours[$employee],
                            'otapp_status'=>'1',
                            'otapp_appdt'=>date('Y-m-d H:i:s'),
                            'otapp_appuserid'=>$id->suser_empid,
                            'otapp_type'=>'1',
                            'otapp_assignedby'=>$id->suser_empid,
                        ]);
                }
            }
        }
    }

    session()->flash('success','OT Hours has been assigned successfully');
    return redirect()->back();
    // }elseif ($check=="error") {
    //     Session::flash('error','OT Time Is Not Valid');
    //     return redirect('ot-application');
    // }else{
    //     Session::flash('error','OT Time Is Not Valid');
    //     return redirect('ot-application');
    // }
}

public function otDelete(Request $request)
{
    $id =   Auth::guard('admin')->user();
    if(count($request->otapp_id)>0){
        $aprovecount=0;
        $deletecount=0;
        for ($i=0; $i < count($request->otapp_id) ; $i++) {
            $check=DB::table('tbl_otapplication')->where('otapp_id',$request->otapp_id[$i])->first();
            if($check->otapp_status=="1"){
                $aprovecount++;
            }elseif($check->otapp_status=="0"){
                $delete=DB::table('tbl_otapplication')->where('otapp_id',$request->otapp_id[$i])->delete();
                if(isset($delete)){
                    $deletecount++;
                }
            }
        }

        if($aprovecount>0 or $deletecount>0){
            if($aprovecount>0){
                Session::flash('error',$aprovecount.' OT Application cannot be deleted because they might be Approved already.');
            }

            if($deletecount>0){
                Session::flash('success',$deletecount.' OT Application(s) has been Deleted Successfully.');
            }
            return '1';
        }else{
            return '0';
        }
    }else{
        return 'null';
    }
}

}