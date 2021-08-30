<?php

namespace App\Http\Controllers\BackEndCon;

use Illuminate\Http\Request;
use App\Http\Controllers\BackEndCon\Controller;
use Auth;
use Session;
use DB;
use DateTime;
use Image;
use URL;
use App\EmployeeTypes;
use App\LeaveType;
use App\Http\Controllers\BackEndCon\attendanceController;

class leaveInfoController extends Controller
{
    
public function leaveInfo(){
    $id =   Auth::guard('admin')->user();
    $mainlink = $this->adminmainmenu();
    $sublink = $this->adminsubmenu();
    $Adminminlink = $this->adminlink();
    $adminsublink = $this->adminsublink();

    return view('Admin.settings.leaveInfo.create',compact('id','mainlink','sublink','Adminminlink','adminsublink'));
}

public function leaveInfoAdd(Request $request)
{
    $this->validate($request,[
            'li_name'=>'required|unique:tbl_leavetype',
            'li_qoutaday'=>'required',
        ]);
    $insert=DB::table('tbl_leavetype')->insert([
            'li_name'=>$request->li_name,
            'li_qoutaday'=>$request->li_qoutaday,
        ]);
    if($insert){
        Session::flash('success','Leave Type Saved Successfully.');
    }else{
        Session::flash('error','Sorry!! Something Went Wrong!!');
    }
    return redirect('leave-type-view/create');
}

public function leaveInfoView(){
    $id =   Auth::guard('admin')->user();
    $mainlink = $this->adminmainmenu();
    $sublink = $this->adminsubmenu();
    $Adminminlink = $this->adminlink();
    $adminsublink = $this->adminsublink();
    $leaveInfo=DB::table('tbl_leavetype')->get();
    return view('Admin.settings.leaveInfo.view',compact('id','mainlink','sublink','Adminminlink','adminsublink','leaveInfo'));
}

public function leaveInfoEdit(Request $request)
{
    if(count($request->li_id)>0){
        if(count($request->li_id)>1){
            return 'max';
        }else{
            return $request->li_id[0];
        }
    }else{
        return 'null';
    }
}

public function leaveInfoEditView($li_id)
{
    $id =   Auth::guard('admin')->user();
    $mainlink = $this->adminmainmenu();
    $sublink = $this->adminsubmenu();
    $Adminminlink = $this->adminlink();
    $adminsublink = $this->adminsublink();
    $leaveInfo=DB::table('tbl_leavetype')->where('li_id',$li_id)->first();
    return view('Admin.settings.leaveInfo.editView',compact('id','mainlink','sublink','Adminminlink','adminsublink','leaveInfo','li_id'));
}

public function leaveInfoUpdate(Request $request,$li_id)
{
    $this->validate($request,[
            'li_name'=>'required',
            'li_qoutaday'=>'required',
            'li_status'=>'required',
        ]);
    $update=DB::table('tbl_leavetype')
        ->where('li_id',$li_id)
        ->update([
            'li_name'=>$request->li_name,
            'li_qoutaday'=>$request->li_qoutaday,
            'li_status'=>$request->li_status,
        ]);
    if($update){
        Session::flash('success','Leave Type Updated Successfully.');
        return redirect('leave-type-view');
    }else{
        Session::flash('error','Sorry!! Something Went Wrong!!');
        return redirect('leave-type-view/'.$li_id.'/edit');
    }
    
}

public function leaveInfoDelete(Request $request)
{
    if(count($request->li_id)>0){
        for ($i=0; $i < count($request->li_id) ; $i++) { 
            $delete=DB::table('tbl_leavetype')->where('li_id',$request->li_id[$i])->delete();
        }
        if($delete){
            Session::flash('success','Leave Type(s) Deleted Successfully.');
            return '1';
        }else{
            return '0';
        }
    }else{
        return 'null';
    }
}

public function filterLeaveDataEmployee($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type)
{
    $id =   Auth::guard('admin')->user();
    if($id->suser_level=="1"){
        return $leave_employee=DB::table('tbl_employee')
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
            ->where('tbl_employee.emp_status','1')
            ->groupBy('tbl_employee.emp_machineid')
            ->orderBy('tbl_employee.emp_empid','ASC')
            ->get();
    }elseif($id->suser_level=="4"){
        return $leave_employee=DB::table('tbl_employee')
            ->where(['tbl_employee.emp_seniorid'=>$id->suser_empid,'tbl_leaveapplication.leave_status'=>'1'])
            ->orWhere('tbl_employee.emp_id',$id->suser_empid)
            ->groupBy('tbl_employee.emp_machineid')
            ->orderBy('tbl_employee.emp_name','ASC')
            ->get();
    }elseif($id->suser_level=="2" or $id->suser_level=="3" or $id->suser_level=="5" or $id->suser_level=="6"){
        return $leave_employee=DB::table('tbl_employee')
            ->where('tbl_employee.emp_id',$id->suser_empid)
            ->where('tbl_employee.emp_status','1')
            ->groupBy('tbl_employee.emp_machineid')
            ->orderBy('tbl_employee.emp_name','ASC')
            ->get();
    }
}

public function getLeaveDataRow($leave_employee,$leave_type,$start_date,$end_date)
{
    $data='';
    if(isset($leave_employee[0])){
      $c=0;
        foreach ($leave_employee as $le){
          $c++;
           $data.='<tr class="gradeX" id="tr-'.$le->emp_id.'">
              <td>'.$c.'</td>
              <td>'.$le->emp_empid.'</td>
              <td>'.$le->emp_name.'</td>
              <td>'.$this->getDepartmentName($le->emp_depart_id).'</td>
              <td>'.$this->getSubDepartmentName($le->emp_sdepart_id).'</td>';
                if($leave_type){
                  foreach ($leave_type as $lt){
                    if($this->checkLeaveTypeExist($le->emp_id,$lt->li_id,$start_date,$end_date)=="1"){
                        $data.='<td>'.$this->qoutaDay($lt->li_id,$lt->li_qoutaday).'</td>
                          <td>'.$this->leaveTaken($le->emp_id,$lt->li_id,$start_date,$end_date).'</td>
                          <td>'.$this->leaveRemain($le->emp_id,$lt->li_id,$this->qoutaDay($lt->li_id,$lt->li_qoutaday),$start_date,$end_date).'</td>';
                    }else{
                        $data.='<td>'.$this->qoutaDay($lt->li_id,$lt->li_qoutaday).'</td>
                          <td>0</td>
                          <td>'.$this->qoutaDay($lt->li_id,$lt->li_qoutaday).'</td>';
                    }
                  }
                }
            $data.='</tr>';
        }
    }

    return $data;
}

public function leaveData()
{
     $flag=0;
    $id =   Auth::guard('admin')->user();
    $mainlink = $this->adminmainmenu();
    $sublink = $this->adminsubmenu();
    $Adminminlink = $this->adminlink();
    $adminsublink = $this->adminsublink();

    $types=EmployeeTypes::orderBy('name','asc')->get();
    $designation=DB::table('tbl_designation')->get();
    $department=DB::table('tbl_department')->get();
    $joblocation=DB::table('tbl_joblocation')->get();
     $leave_type=LeaveType::
     where('li_id', 1)
     ->first();
    $leave_type1=DB::table('tbl_leavetype')->first();
    $l_id = 0;

    $all_leave= LeaveType::get();

    $leave_employee=$this->filterLeaveDataEmployee('0','0','0','0','0','0','0');
    $data=$this->getLeaveTypeDataRow('0',$leave_employee,$leave_type1,'0','0');

    return view('Admin.leave.index',compact('id','mainlink','sublink','Adminminlink','adminsublink','leave_employee','types','designation','department','joblocation','leave_type','leave_type1','data', 'l_id', 'all_leave', 'flag'));
}

public function leave_type_filter(Request $request)
{
    

    $flag=1;
    $id =   Auth::guard('admin')->user();
    $mainlink = $this->adminmainmenu();
    $sublink = $this->adminsubmenu();
    $Adminminlink = $this->adminlink();
    $adminsublink = $this->adminsublink();

    $types=EmployeeTypes::orderBy('name','asc')->get();
    $designation=DB::table('tbl_designation')->get();
    $department=DB::table('tbl_department')->get();
    $joblocation=DB::table('tbl_joblocation')->get();


     $leave_type=LeaveType::
     where('li_id', $request->leave_type_id)
     ->first();

     $leave_type1=LeaveType::
     where('li_id', $request->leave_type_id)
     ->first();

    

     $all_leave= LeaveType::get();

     // return $leave_type1; exit();

     $leave_employee=$this->filterLeaveDataEmployee('0','0','0','0','0','0','0');

    $data=$this->getLeaveTypeDataRow($leave_type->li_id,$leave_employee,$leave_type1,'0','0');

     

     // return $leave_type->li_id; exit();
     return view('Admin.leave.index', compact('id','mainlink','sublink','Adminminlink','adminsublink','leave_employee','types','designation','department','joblocation','leave_type','leave_type1', 'data', 'all_leave', 'flag'));
}

public function getLeaveTypeDataRow($leave_type_id,$leave_employee,$leave_type,$start_date,$end_date)
{
    
    $data='';
    if(isset($leave_employee[0])){
      $c=0;
        foreach ($leave_employee as $le){
          $c++;
           $data.='<tr class="gradeX" id="tr-'.$le->emp_id.'">
              <td>'.$c.'</td>
              <td>'.$le->emp_empid.'</td>
              <td>'.$le->emp_name.'</td>
              <td>'.$this->getDepartmentName($le->emp_depart_id).'</td>
              <td hidden>'.$this->getSubDepartmentName($le->emp_sdepart_id).'</td>';
                if($leave_type){
                  // foreach ($leave_type as $lt){
                    // if($this->checkLeaveTypeExist($le->emp_id,$leave_type->li_id,$start_date,$end_date)=="1"){
                        $data.='<td>'.$this->qoutaDay($leave_type->li_id,$leave_type->li_qoutaday).'</td>
                          <td>'.$this->leaveTaken($le->emp_id,$leave_type->li_id,$start_date,$end_date).'</td>
                          <td>'.$this->leaveRemain($le->emp_id,$leave_type->li_id,$this->qoutaDay($leave_type->li_id,$leave_type->li_qoutaday),$start_date,$end_date).'</td>';
                    // }
                  // }
                }
            $data.='</tr>';
        }
    }

    return $data;
}

public function leaveDataSearch($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type,$start_date,$end_date)
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
    $leave_type=DB::table('tbl_leavetype')->get();

    $leavedata = $this->leavedata();
    $replacement = $this->leavedata();
    $balance = $this->leavedata();
    

    $leave_employee=$this->filterLeaveDataEmployee($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type);
    $data=$this->getLeaveDataRow($leave_employee,$leave_type,$start_date,$end_date);

    return view('Admin.leave.index',compact('id','mainlink','sublink','Adminminlink','adminsublink','leavedata','types','designation','department','joblocation','leave_type','replacement','balance','emp_depart_id','emp_sdepart_id','emp_desig_id','emp_jlid','emp_type','start_date','end_date','data'));
}

public function checkLeaveTypeExist($leave_empid,$leave_typeid,$start_date,$end_date)
{
    $check=DB::table('tbl_leaveapplication')
        ->where(['leave_empid'=>$leave_empid,'leave_typeid'=>$leave_typeid])
        ->when($start_date!="0", function($query) use ($start_date){
            return $query->where(DB::raw('substr(tbl_leaveapplication.leave_start_date,1,10)'),'>=',$start_date);
        })
        ->when($end_date!="0", function($query) use ($end_date){
            return $query->orWhere(DB::raw('substr(tbl_leaveapplication.leave_end_date,1,10)'),'<=',$end_date);
        })
        ->get();
    if(isset($check[0])){
        return '1';
    }else{
        return '0';
    }
}

public function currentLeaveStatus()
{
    $id =   Auth::guard('admin')->user();
    $leave_type=DB::table('tbl_leavetype')->get();
    $data='';
    if(isset($leave_type[0])){
        foreach ($leave_type as $lt) {
            if($lt->li_id=="2" or $lt->li_id=="5" or $lt->li_id=="6"){
                $data.='<tr>
                        <td>'.$lt->li_name.'</td>
                        <td>-</td>
                        <td>'.$this->currentLeaveTaken($id->suser_empid,$lt->li_id).'</td>
                        <td>-</td>
                      </tr>';
            }else{
                $data.='<tr>
                        <td>'.$lt->li_name.'</td>
                        <td>'.$lt->li_qoutaday.'</td>
                        <td>'.$this->currentLeaveTaken($id->suser_empid,$lt->li_id).'</td>
                        <td>'.$this->currentLeaveRemain($id->suser_empid,$lt->li_id,$lt->li_qoutaday).'</td>
                      </tr>';
            }
        }
    }else{
        $data.='<tr>td>Nothing Found!</td></tr>';
    }

    return $data;
}

public function currentLeaveTaken($leave_empid,$leave_typeid)
{
    return $taken=DB::table('tbl_leaveapplication')
        ->join('tbl_employee','tbl_leaveapplication.leave_empid','=','tbl_employee.emp_id')
        ->where('tbl_leaveapplication.leave_typeid',$leave_typeid)
        ->where('tbl_leaveapplication.leave_empid',$leave_empid)
        ->where(DB::raw('substr(tbl_leaveapplication.leave_start_date,1,4)'),'>=',date('Y'))
        ->where(DB::raw('substr(tbl_leaveapplication.leave_end_date,1,4)'),'>=',date('Y'))
        ->where('tbl_leaveapplication.leave_status','<','2')
        ->sum('tbl_leaveapplication.leave_day');
}

public function currentLeaveRemain($leave_empid,$leave_typeid,$li_qoutaday)
{
    $taken=$this->currentLeaveTaken($leave_empid,$leave_typeid);
    return $remain=$li_qoutaday-$taken;
}

public function qoutaDay($li_id,$li_qoutaday)
{
    // if($li_id=="2" or $li_id=="5" or $li_id=="6"){
    //     return '0';
    // }else{
        return $li_qoutaday;
    // }
}

public function leaveTaken($leave_empid,$leave_typeid,$start_date,$end_date)
{
    if($leave_typeid=="2"){
    return $taken=DB::table('tbl_leaveapplication')
        ->join('tbl_employee','tbl_leaveapplication.leave_empid','=','tbl_employee.emp_id')
        ->where('tbl_leaveapplication.leave_note','Short Leave Taken')
        ->where('tbl_leaveapplication.leave_empid',$leave_empid)
        ->when($start_date!="0", function($query) use ($start_date){
            return $query->where(DB::raw('substr(tbl_leaveapplication.leave_start_date,1,10)'),'>=',$start_date);
        })
        ->when($end_date!="0", function($query) use ($end_date){
            return $query->where(DB::raw('substr(tbl_leaveapplication.leave_end_date,1,10)'),'<=',$end_date);
        })
        ->where('tbl_leaveapplication.leave_status','1')
        ->sum('tbl_leaveapplication.leave_day');
    }else{
    return $taken=DB::table('tbl_leaveapplication')
        ->join('tbl_employee','tbl_leaveapplication.leave_empid','=','tbl_employee.emp_id')
        ->where('tbl_leaveapplication.leave_typeid',$leave_typeid)
        ->where('tbl_leaveapplication.leave_empid',$leave_empid)
        ->when($start_date!="0", function($query) use ($start_date){
            return $query->where(DB::raw('substr(tbl_leaveapplication.leave_start_date,1,10)'),'>=',$start_date);
        })
        ->when($end_date!="0", function($query) use ($end_date){
            return $query->where(DB::raw('substr(tbl_leaveapplication.leave_end_date,1,10)'),'<=',$end_date);
        })
        ->where('tbl_leaveapplication.leave_status','1')
        ->sum('tbl_leaveapplication.leave_day');
    }
    
}

public function leaveRemain($leave_empid,$leave_typeid,$li_qoutaday,$start_date,$end_date)
{
    $taken=$this->leaveTaken($leave_empid,$leave_typeid,$start_date,$end_date);
    if($li_qoutaday=="0"){
        return '0';
    }else{
        return $remain=$li_qoutaday-$taken;
    }
}

public function halfDay($type)
{
    $data='';
    $id =   Auth::guard('admin')->user();
    $shift=$this->getCurrentShiftInfo($id->suser_empid,date('Y-m-d'));
    if($shift->shift_type=="1"){
        $halfDayHours='4 hours';
    }elseif($shift->shift_type=="2"){
        $halfDayHours='4 hours 30 minutes';
    }

    if($type=="1"){
        $data.=substr($shift->shift_stime,0,5);
        $data.='<->'.date("H:i", strtotime("+$halfDayHours", strtotime($shift->shift_stime)));
    }elseif($type=="2"){
        $data.=date("H:i", strtotime("+$halfDayHours", strtotime($shift->shift_stime)));
        $data.='<->'.substr($shift->shift_etime,0,5);
    }elseif($type=="0"){
        $data.='00:00<->00:00';
    }
    return $data;
}

public function getStartEndTime($request)
{
    if($request->leave_typeid=="2"){
        return $request->leave_start_time.'&'.$request->leave_end_time;
    }elseif($request->leave_typeid=="1" or $request->leave_typeid=="3" or $request->leave_typeid=="4" or $request->leave_typeid=="5"){
        $halfDay=$this->halfDay($request->leave_type);
        return explode('<->',$halfDay)[0].'&'.explode('<->',$halfDay)[1];
    }else{
        return '00:00&00:00';
    }
}

public function getBalance($leave_typeid,$leave_start_date,$leave_end_date,$leave_start_time,$leave_end_time)
{
    if($leave_typeid=="2" or $leave_typeid=="5" or $leave_typeid=="6"){
        return '';
    }
    $id =   Auth::guard('admin')->user();
    $qouta=DB::table('tbl_leavetype')->where('li_id',$leave_typeid)->first();
    $leave=DB::table('tbl_leaveapplication')->where(['leave_empid'=>$id->suser_empid,'leave_typeid'=>$leave_typeid])->where('tbl_leaveapplication.leave_status','<','2')->sum('leave_day');
    $balance=$qouta->li_qoutaday-$leave;
    $employee=DB::table('tbl_employee')->where('emp_id',$id->suser_empid)->first(['tbl_employee.emp_workhr']);
    if($employee->emp_workhr=="1"){
        $workhour=8;
    }elseif($employee->emp_workhr=="2"){
        $workhour=9;
    }

    if($leave_start_date!="0000-00-00" && $leave_end_date!="0000-00-00"){

        if($leave_typeid=="2"){
            if($leave_start_date==$leave_end_date){
                    if(strlen(explode(':',$leave_start_time)[0])=='1'){
                    $leave_start_time='0'.$leave_start_time;
                    }
                    if(strlen(explode(':',$leave_end_time)[0])=='1'){
                        $leave_end_time='0'.$leave_end_time;
                    }

                    $leave_end_time = new DateTime($leave_end_time.':00 '.$leave_start_date);
                    $leave_start_time = new DateTime($leave_start_time.':00 '.$leave_start_date);
                    $difference=$leave_end_time->diff($leave_start_time);
                    if($difference->h>0){
                        if($difference->i>0){
                            $min_to_hr=$difference->i/60;
                            $hour=$difference->h+$min_to_hr;
                            $leave_day=$hour/$workhour;
                            $leave_day=number_format((float)$leave_day, 2, '.', '');
                        }else{
                            $leave_day=$difference->h/$workhour;
                            $leave_day=number_format((float)$leave_day, 2, '.', '');
                        }
                    }else{
                        if($difference->i>0){
                            $min_to_hr=$difference->i/60;
                            $leave_day=$min_to_hr/$workhour;
                            $leave_day=number_format((float)$leave_day, 2, '.', '');
                        }else{
                            $leave_day=0;
                            $leave_day=number_format((float)$leave_day, 2, '.', '');
                        }
                    }
                    if($leave_day<=$balance){
                        if($balance-$leave_day<0){
                            return "Insufficient";
                        }else{
                            return $balance-$leave_day;
                        }
                    }else{
                        return "Invalid";
                    }
            }else{
                return "Invalid";
            }
        }else{
            if($leave_start_date==$leave_end_date && $leave_start_time!=$leave_end_time){
                    if(strlen(explode(':',$leave_start_time)[0])=='1'){
                    $leave_start_time='0'.$leave_start_time;
                    }
                    if(strlen(explode(':',$leave_end_time)[0])=='1'){
                        $leave_end_time='0'.$leave_end_time;
                    }

                    $leave_end_time = new DateTime($leave_end_time.':00 '.$leave_start_date);
                    $leave_start_time = new DateTime($leave_start_time.':00 '.$leave_start_date);
                    $difference=$leave_end_time->diff($leave_start_time);
                    if($difference->h>0){
                        if($difference->i>0){
                            $min_to_hr=$difference->i/60;
                            $hour=$difference->h+$min_to_hr;
                            $leave_day=$hour/$workhour;
                            $leave_day=number_format((float)$leave_day, 2, '.', '');
                        }else{
                            $leave_day=$difference->h/$workhour;
                            $leave_day=number_format((float)$leave_day, 2, '.', '');
                        }
                    }else{
                        if($difference->i>0){
                            $min_to_hr=$difference->i/60;
                            $leave_day=$min_to_hr/$workhour;
                            $leave_day=number_format((float)$leave_day, 2, '.', '');
                        }else{
                            $leave_day=0;
                            $leave_day=number_format((float)$leave_day, 2, '.', '');
                        }
                    }
                    if($leave_day<=$balance){
                        if($balance-$leave_day<0){
                            return "Insufficient";
                        }else{
                            return $balance-$leave_day;
                        }
                    }else{
                        return "Invalid";
                    }
            }else{
                $end_time = new DateTime('00:00:00 '.$leave_end_date);
                $start_time = new DateTime('00:00:00 '.$leave_start_date);
                $difference=$end_time->diff($start_time);
                $leave_day=$difference->days+1;
                if($leave_day<=$balance){
                    if($balance-$leave_day<0){
                        return "Insufficient";
                    }else{
                        return $balance-$leave_day;
                    }
                }else{
                    return "Invalid";
                }
            }
        }
    }else{
        if($balance<0){
            return "Insufficient";
        }else{
            return $balance;
        }
    }
}

public function differenceDays($request)
{
    $leave_typeid=$request->leave_typeid;
    $leave_start_date=$request->leave_start_date;
    $leave_end_date=$request->leave_end_date;
    $leave_start_time=explode('&',$this->getStartEndTime($request))[0];
    $leave_end_time=explode('&',$this->getStartEndTime($request))[1];
    $id =   Auth::guard('admin')->user();
    $employee=DB::table('tbl_employee')->where('emp_id',$id->suser_empid)->first(['tbl_employee.emp_workhr']);
    if($employee->emp_workhr=="1"){
        $workhour=8;
    }elseif($employee->emp_workhr=="2"){
        $workhour=9;
    }

    if($leave_start_date!="0000-00-00" && $leave_end_date!="0000-00-00"){

        if($leave_typeid=="2"){
            if($leave_start_date==$leave_end_date){
                if(strlen(explode(':',$leave_start_time)[0])=='1'){
                $leave_start_time='0'.$leave_start_time;
                }
                if(strlen(explode(':',$leave_end_time)[0])=='1'){
                    $leave_end_time='0'.$leave_end_time;
                }

                $leave_end_time = new DateTime($leave_end_time.':00 '.$leave_start_date);
                $leave_start_time = new DateTime($leave_start_time.':00 '.$leave_start_date);
                $difference=$leave_end_time->diff($leave_start_time);
                if($difference->h>0){
                    if($difference->i>0){
                        $min_to_hr=$difference->i/60;
                        $hour=$difference->h+$min_to_hr;
                        $leave_day=$hour/$workhour;
                        $leave_day=number_format((float)$leave_day, 2, '.', '');
                    }else{
                        $leave_day=$difference->h/$workhour;
                        $leave_day=number_format((float)$leave_day, 2, '.', '');
                    }
                }else{
                    if($difference->i>0){
                        $min_to_hr=$difference->i/60;
                        $leave_day=$min_to_hr/$workhour;
                        $leave_day=number_format((float)$leave_day, 2, '.', '');
                    }else{
                        $leave_day=0;
                        $leave_day=number_format((float)$leave_day, 2, '.', '');
                    }
                }
            }else{
                $leave_day=0;
            }
        }else{
            if($leave_start_date==$leave_end_date && $leave_start_time!=$leave_end_time){
                if(strlen(explode(':',$leave_start_time)[0])=='1'){
                $leave_start_time='0'.$leave_start_time;
                }
                if(strlen(explode(':',$leave_end_time)[0])=='1'){
                    $leave_end_time='0'.$leave_end_time;
                }

                $leave_end_time = new DateTime($leave_end_time.':00 '.$leave_start_date);
                $leave_start_time = new DateTime($leave_start_time.':00 '.$leave_start_date);
                $difference=$leave_end_time->diff($leave_start_time);
                $leave_day=0;
                if($difference->h>0){
                    if($difference->i>0){
                        $min_to_hr=$difference->i/60;
                        $hour=$difference->h+$min_to_hr;
                        $leave_day+=$hour/$workhour;
                        $leave_day=number_format((float)$leave_day, 2, '.', '');
                    }else{
                        $leave_day+=$difference->h/$workhour;
                        $leave_day=number_format((float)$leave_day, 2, '.', '');
                    }
                }else{
                    if($difference->i>0){
                        $min_to_hr=$difference->i/60;
                        $leave_day+=$min_to_hr/$workhour;
                        $leave_day=number_format((float)$leave_day, 2, '.', '');
                    }else{
                        $leave_day+=0;
                        $leave_day=number_format((float)$leave_day, 2, '.', '');
                    }
                }
            }else{
                $end_time = new DateTime('00:00:00 '.$leave_end_date);
                $start_time = new DateTime('00:00:00 '.$leave_start_date);
                $difference=$end_time->diff($start_time);
                $leave_day=$difference->days+1;
            }
        }
    return $leave_day;
    }else{
        return '0';
    }
}

public function dateChecker($leave_typeid,$leave_start_date,$leave_end_date,$leave_start_time,$leave_end_time)
{
    if($leave_typeid=="2"){
        if($leave_start_date==$leave_end_date){
            $a=explode(":",$leave_start_time);
            $b=explode(":",$leave_end_time);

            if($a[0]<$b[0]){
                return '1///';
            }else if($a[0]==$b[0]){
                if($a[1]<$b[1]){
                    return '1///';
                }else if($a[1]==$b[1]){
                    return '0/// Leave Duration Is Empty';
                }else if($a[1]>$b[1]){
                   return '0/// Leave Start Time Cannot be Later Then Leave End Time';
                }
            }else if($a[0]>$b[0]){
               return '0/// Leave Start Time Cannot be Later Then Leave End Time';
            }
        }else{
            return '0/// Leave Start Date and Leave End Date Must Be Same In Short Leave';
        }
    }else{
        $a=explode("-",$leave_start_date);
        $b=explode("-",$leave_end_date);
        if($a[0]<$b[0]){
            return '1///';
        }else if($a[0]==$b[0]){
            if($a[1]<$b[1]){
                return '1///';
            }else if($a[1]==$b[1]){
                if($a[2]<=$b[2]){
                    return '1///';
                }else if($a[2]>$b[2]){
                   return '0/// Leave Start Date Cannot be Later Then Leave End Date';
                }
            }else if($a[1]>$b[1]){
               return '0/// Leave Start Month Cannot be Later Then Leave End Month';
            }
        }else{
           return '0/// Leave Start Year Cannot be Later Then Leave End Year';
        }
    }
}

public function leaveApplicationSubmit(Request $request)
{
    // return $request; exit();
    $id =   Auth::guard('admin')->user();
    $this->validate($request,[
        'leave_typeid'=>'required',
        'leave_start_date'=>'required',
        'leave_start_time'=>'required',
        'leave_end_date'=>'required',
        'leave_end_time'=>'required',
        'leave_reason'=>'required',
    ]);
    if($request->hidden_balance == 'NaN'){
        Session::flash('error',$this->getLeaveTypename($request->leave_typeid).' No balance');
        return redirect('pending-leave-application'); exit();
    }

    if(explode("///",$this->dateChecker($request->leave_typeid,$request->leave_start_date,$request->leave_end_date,$request->leave_start_time,$request->leave_end_time))[0]=="0"){
        Session::flash('error',$this->getLeaveTypename($request->leave_typeid).' Start & End Date Is Not Valid');
        return redirect('pending-leave-application');
    }else{

    $difference_days=$this->differenceDays($request);
        if($difference_days>0){
       
            $employee=DB::table('tbl_employee')->where('tbl_employee.emp_id',$id->suser_empid)->first();
            if($request->leave_typeid=="1"){
                return $this->annualLeaveRequest($id,$request,$employee,$difference_days,'1');
            }elseif($request->leave_typeid=="2"){
                return $this->shortLeaveRequest($id,$request,$employee,$difference_days,'2');
            }elseif($request->leave_typeid=="3"){
                return $this->casualLeaveRequest($id,$request,$employee,$difference_days,'3');
            }elseif($request->leave_typeid=="4"){
                return $this->sickLeaveRequest($id,$request,$employee,$difference_days,'4');
            }elseif($request->leave_typeid=="5"){
                return $this->compensatoryLeaveRequest($id,$request,$employee,$difference_days,'5');
            }elseif($request->leave_typeid=="6"){
                return $this->matarnityLeaveRequest($id,$request,$employee,$difference_days,'6');
            }

        }else{
            Session::flash('error',$this->getLeaveTypename($request->leave_typeid).' Date And Time Is Not Valid.');
            return redirect('pending-leave-application');
        }
    }
}

public function checkLeaveDate($request)
{
    if($request->leave_typeid=="2"){
        if($request->leave_start_date==$request->leave_end_date){
            $a=explode(":",$request->leave_start_time);
            $b=explode(":",$request->leave_end_time);

            if($a[0]<$b[0]){

            }else if($a[0]==$b[0]){
                if($a[1]==$b[1]){
                    ession::flash('error','Leave Duration is Empty.');
                    return '0';
                }else if($a[1]<$b[1]){

                }else if($a[1]>$b[1]){
                    Session::flash('error','Leave Start Time Cannot be Later Then Leave End Time.');
                    return '0';
                }
            }else if($a[0]>$b[0]){
                Session::flash('error','Leave Start Time Cannot be Later Then Leave End Time.');
                return '0';
            }
        }else{
            Session::flash('error','Leave Start Date and Leave End Date Must Be Same In Short Leave.');
            return '0';
        }
    }else{
        $a=explode("-",$request->leave_start_date);
        $b=explode("-",$request->leave_end_date);
        if($a[0]<$b[0]){
            
        }else if($a[0]==$b[0]){
            if($a[1]<$b[1]){

            }else if($a[1]==$b[1]){
                if($a[2]<=$b[2]){
                    
                }else if($a[2]>$b[2]){
                    Session::flash('error','Leave Start Date Cannot be Later Then Leave End Date.');
                    return '0';
                }
            }else if($a[1]>$b[1]){
                Session::flash('error','Leave Start Month Cannot be Later Then Leave End Month.');
                return '0';
            }
        }else{
            Session::flash('error','Leave Start Year Cannot be Later Then Leave End Year.');
            return '0';
        }
    }
}

public function annualLeaveRequest($id,$request,$employee,$difference_days,$li_id)
{
    $leave_typeid=$request->leave_typeid;
    $leave_start_date=$request->leave_start_date;
    $leave_end_date=$request->leave_end_date;
    $leave_start_time=explode('&',$this->getStartEndTime($request))[0];
    $leave_end_time=explode('&',$this->getStartEndTime($request))[1];
    if(strlen(explode(':',$leave_start_time)[0])=='1'){
        $leave_start_time='0'.$leave_start_time;
    }
    if(strlen(explode(':',$leave_end_time)[0])=='1'){
        $leave_end_time='0'.$leave_end_time;
    }
    $balance=$this->getBalance($leave_typeid,$leave_start_date,$leave_end_date,$leave_start_time,$leave_end_time);
    if($balance>=0){
        $proceed=true;
    }else{
        $proceed=false;
    }
    if($proceed){

    if($this->checkLeaveDate($request)=="0"){
        return redirect('pending-leave-application');
    }else{
        $insert=DB::table('tbl_leaveapplication')->insert([
            'leave_empid'=>$id->suser_empid,
            'leave_typeid'=>$request->leave_typeid,
            'leave_start_date'=>$request->leave_start_date.' '.$leave_start_time.':00',
            'leave_end_date'=>$request->leave_end_date.' '.$leave_end_time.':00',
            'leave_day'=>$difference_days,
            'leave_reason'=>$request->leave_reason,
            'leave_requested_date'=>date('Y-m-d'),
        ]);
        if($insert){
            Session::flash('success',$this->getLeaveTypename($request->leave_typeid).' Application Submitted Successfully');
            /*$emp_email='You have submitted a leave application To '.$this->getSeniorName($employee->emp_seniorid).' which is followed by- <br><br>Leave Start Date : '.$request->leave_start_date.'<br>Leave End Date : '.$request->leave_end_date.'<br>Leave Duration : '.$difference_days.' Day(s)<br>Leave Reason : '.$request->leave_reason;
            $this->sendMail($id->email,'Leave Application Submitted to '.$this->getSeniorName($employee->emp_seniorid),$emp_email);
            $senior_email=$employee->emp_name.' has submitted a leave application which is followed by- <br><br>Leave Start Date : '.$request->leave_start_date.'<br>Leave End Date : '.$request->leave_end_date.'<br>Leave Duration : '.$difference_days.' Day(s)<br>Leave Reason : '.$request->leave_reason;
            $this->sendMail($this->getSeniorEmail($employee->emp_seniorid),'Leave Application Received From '.$employee->emp_name,$senior_email);*/
            $this->empHistory($id->suser_empid,'You Have submitted a <b>'.$this->getLeaveTypename($request->leave_typeid).'</b> application To <b>'.$this->getSeniorName($employee->emp_seniorid).'</b> which is followed by- <br>Leave Start Date : <b>'.$request->leave_start_date.'</b><br>Leave End Date : <b>'.$request->leave_end_date.'</b><br>Leave Duration : <b>'.$difference_days.'</b> Day(s)<br>Leave Reason : <b>'.$request->leave_reason.'</b>');
            return redirect('pending-leave-application');
        }
    }

    }else{
        Session::flash('error',$this->getLeaveTypename($request->leave_typeid).' Balance is insufficient');
        return redirect('pending-leave-application');
    }
}

public function shortLeaveDay($request,$workhour)
{
    $leave_day=0;
    if(strlen(explode(':',$request->leave_start_time)[0])=='1'){
        $request->leave_start_time='0'.$request->leave_start_time;
    }
    if(strlen(explode(':',$request->leave_end_time)[0])=='1'){
        $request->leave_end_time='0'.$request->leave_end_time;
    }

    $leave_end_time = new DateTime($request->leave_end_time.':00 '.$request->leave_end_date);
    $leave_start_time = new DateTime($request->leave_start_time.':00 '.$request->leave_start_date);
    $difference=$leave_end_time->diff($leave_start_time);
    if($difference->h>0){
        if($difference->i>0){
            $min_to_hr=$difference->i/60;
            $hour=$difference->h+$min_to_hr;
            $leave_day=$hour/$workhour;
            $leave_day=number_format((float)$leave_day, 2, '.', '');
        }else{
            $leave_day=$difference->h/$workhour;
            $leave_day=number_format((float)$leave_day, 2, '.', '');
        }
    }else{
        if($difference->i>0){
            $min_to_hr=$difference->i/60;
            $leave_day=$min_to_hr/$workhour;
            $leave_day=number_format((float)$leave_day, 2, '.', '');
        }else{
            $leave_day=0;
            $leave_day=number_format((float)$leave_day, 2, '.', '');
        }
    }

    return $leave_day;
}

public function searchShortLeave($id,$leave_day)
{
    $casualQouta=DB::table('tbl_leavetype')->where('li_id','3')->first(['li_qoutaday']);
    $casualLeaveTaken=DB::table('tbl_leaveapplication')
        ->join('tbl_employee','tbl_leaveapplication.leave_empid','=','tbl_employee.emp_id')
        ->where(['tbl_leaveapplication.leave_empid'=>$id->suser_empid,'tbl_leaveapplication.leave_typeid'=>'3'])
        ->where('tbl_leaveapplication.leave_status','<','2')
        ->where(DB::raw('substr(tbl_leaveapplication.leave_start_date,1,4)'),'>=',date('Y'))
        ->where(DB::raw('substr(tbl_leaveapplication.leave_end_date,1,4)'),'<=',date('Y'))
        ->sum('tbl_leaveapplication.leave_day');
    if(($casualQouta->li_qoutaday-$casualLeaveTaken)>=$leave_day){
        return '3';
    }else{
        $annualQouta=DB::table('tbl_leavetype')->where('li_id','1')->first(['li_qoutaday']);
        $annualLeaveTaken=DB::table('tbl_leaveapplication')
            ->join('tbl_employee','tbl_leaveapplication.leave_empid','=','tbl_employee.emp_id')
            ->where(['tbl_leaveapplication.leave_empid'=>$id->suser_empid,'tbl_leaveapplication.leave_typeid'=>'1'])
            ->where('tbl_leaveapplication.leave_status','<','2')
            ->where(DB::raw('substr(tbl_leaveapplication.leave_start_date,1,7)'),'>=',date('Y-m'))
            ->orWhere(DB::raw('substr(tbl_leaveapplication.leave_end_date,1,7)'),'<=',date('Y-m'))
            ->sum('tbl_leaveapplication.leave_day');
        if(($annualQouta->li_qoutaday -$annualLeaveTaken) >=$leave_day){
            return '1';
        }else{
            Session::flash('error','Sorry! You do not have sufficient leave balance in Casual Leave & Annual Leave');
            return '0';
        }
    }

}


public function shortLeaveRequest($id,$request,$employee,$difference_days,$li_id)
{
    if($employee->emp_workhr=="1"){
        $workhour=8;
    }elseif($employee->emp_workhr=="2"){
        $workhour=9;
    }
    if($this->checkLeaveDate($request)=="0"){
        return redirect('pending-leave-application');
    }else{
        if($request->leave_start_date==$request->leave_end_date){
            $leave_day=$this->shortLeaveDay($request,$workhour);
            if($leave_day>0){
            
            $leave_end_time = new DateTime($request->leave_end_time.':00 '.$request->leave_end_date);
            $leave_start_time = new DateTime($request->leave_start_time.':00 '.$request->leave_start_date);
            $difference=$leave_end_time->diff($leave_start_time);
            $hour=0;
            if($difference->h>0){
            $hour+=$difference->h;
            if($difference->i>0){
                $min_to_hr=$difference->i/60;
                $hour+=$min_to_hr;
            }
            }
            if($hour>3){
                Session::flash('error',$this->getLeaveTypename($request->leave_typeid).' can be Maximumof 3 hours.');
                return redirect('pending-leave-application');
            }
                $leave_typeid=$this->searchShortLeave($id,$leave_day);
                if($leave_typeid=="0"){
                    return redirect('pending-leave-application');
                }
                $insert=DB::table('tbl_leaveapplication')->insert([
                    'leave_empid'=>$id->suser_empid,
                    'leave_typeid'=>$leave_typeid,
                    'leave_start_date'=>$request->leave_start_date.' '.$request->leave_start_time.':00',
                    'leave_end_date'=>$request->leave_end_date.' '.$request->leave_end_time.':00',
                    'leave_day'=>$leave_day,
                    'leave_reason'=>$request->leave_reason,
                    'leave_note'=>'Short Leave Taken',
                    'leave_requested_date'=>date('Y-m-d'),
                ]);
                if($insert){
                    Session::flash('success',$this->getLeaveTypename($request->leave_typeid).' Application Submitted Successfully and '.$this->getLeaveTypename('2').' will be adjusted from '.$this->getLeaveTypename($leave_typeid));
                    /*$emp_email='You have submitted a leave application To '.$this->getSeniorName($employee->emp_seniorid).' which is followed by- <br><br>Leave Start Date : '.$request->leave_start_date.'<br>Leave End Date : '.$request->leave_end_date.'<br>Leave Duration : '.$leave_day.' Day(s)<br>Leave Reason : '.$request->leave_reason;
                    $this->sendMail($id->email,'Leave Application Submitted to '.$this->getSeniorName($employee->emp_seniorid),$emp_email);
                    $senior_email=$employee->emp_name.' has submitted a leave application which is followed by- <br><br>Leave Start Date : '.$request->leave_start_date.'<br>Leave End Date : '.$request->leave_end_date.'<br>Leave Duration : '.$leave_day.' Day(s)<br>Leave Reason : '.$request->leave_reason;
                    $this->sendMail($this->getSeniorEmail($employee->emp_seniorid),'Leave Application Received From '.$employee->emp_name,$senior_email);*/
                    $this->empHistory($id->suser_empid,'You have submitted a <b>'.$this->getLeaveTypename($request->leave_typeid).'</b> application To <b>'.$this->getSeniorName($employee->emp_seniorid).'</b> which is followed by- <br>Leave Start Date : <b>'.$request->leave_start_date.'</b><br>Leave End Date : <b>'.$request->leave_end_date.'</b><br>Leave Duration : <b>'.$difference_days.'</b> Day(s)<br>Leave Reason : <b>'.$request->leave_reason.'</b>');
                    return redirect('pending-leave-application');
                }
            }else{
                Session::flash('error',$this->getLeaveTypename($request->leave_typeid).' Time Is Not Valid');
                return redirect('pending-leave-application');
            }
        }else{
            Session::flash('error','Date Must Be Same In '.$this->getLeaveTypename($request->leave_typeid));
            return redirect('pending-leave-application');
        }
    }
}

public function casualLeaveRequest($id,$request,$employee,$difference_days,$li_id)
{
    $leave_typeid=$request->leave_typeid;
    $leave_start_date=$request->leave_start_date;
    $leave_end_date=$request->leave_end_date;
    $leave_start_time=explode('&',$this->getStartEndTime($request))[0];
    $leave_end_time=explode('&',$this->getStartEndTime($request))[1];
    if(strlen(explode(':',$leave_start_time)[0])=='1'){
        $leave_start_time='0'.$leave_start_time;
    }
    if(strlen(explode(':',$leave_end_time)[0])=='1'){
        $leave_end_time='0'.$leave_end_time;
    }
    $balance=$this->getBalance($leave_typeid,$leave_start_date,$leave_end_date,$leave_start_time,$leave_end_time);
    if($balance>=0){
        $proceed=true;
    }else{
        $proceed=false;
    }
    if($proceed){

    if($this->checkLeaveDate($request)=="0"){
        return redirect('pending-leave-application');
    }else{
        $insert=DB::table('tbl_leaveapplication')->insert([
            'leave_empid'=>$id->suser_empid,
            'leave_typeid'=>$request->leave_typeid,
            'leave_start_date'=>$request->leave_start_date.' '.$leave_start_time.':00',
            'leave_end_date'=>$request->leave_end_date.' '.$leave_end_time.':00',
            'leave_day'=>$difference_days,
            'leave_reason'=>$request->leave_reason,
            'leave_requested_date'=>date('Y-m-d'),
        ]);
        if($insert){
            Session::flash('success',$this->getLeaveTypename($request->leave_typeid).' Application Submitted Successfully');
            /*$emp_email='You have submitted a leave application To '.$this->getSeniorName($employee->emp_seniorid).' which is followed by- <br><br>Leave Start Date : '.$request->leave_start_date.'<br>Leave End Date : '.$request->leave_end_date.'<br>Leave Duration : '.$difference_days.' Day(s)<br>Leave Reason : '.$request->leave_reason;
            $this->sendMail($id->email,'Leave Application Submitted to '.$this->getSeniorName($employee->emp_seniorid),$emp_email);
            $senior_email=$employee->emp_name.' has submitted a leave application which is followed by- <br><br>Leave Start Date : '.$request->leave_start_date.'<br>Leave End Date : '.$request->leave_end_date.'<br>Leave Duration : '.$difference_days.' Day(s)<br>Leave Reason : '.$request->leave_reason;
            $this->sendMail($this->getSeniorEmail($employee->emp_seniorid),'Leave Application Received From '.$employee->emp_name,$senior_email);*/
            $this->empHistory($id->suser_empid,'You Have submitted a <b>'.$this->getLeaveTypename($request->leave_typeid).'</b> application To <b>'.$this->getSeniorName($employee->emp_seniorid).'</b> which is followed by- <br>Leave Start Date : <b>'.$request->leave_start_date.'</b><br>Leave End Date : <b>'.$request->leave_end_date.'</b><br>Leave Duration : <b>'.$difference_days.'</b> Day(s)<br>Leave Reason : <b>'.$request->leave_reason.'</b>');
            return redirect('pending-leave-application');
        }
    }

    }else{
        Session::flash('error',$this->getLeaveTypename($request->leave_typeid).' Balance is insufficient');
        return redirect('pending-leave-application');
    }
}

public function sickLeaveRequest($id,$request,$employee,$difference_days,$li_id)
{
    $leave_typeid=$request->leave_typeid;
    $leave_start_date=$request->leave_start_date;
    $leave_end_date=$request->leave_end_date;
    $leave_start_time=explode('&',$this->getStartEndTime($request))[0];
    $leave_end_time=explode('&',$this->getStartEndTime($request))[1];
    $balance=$this->getBalance($leave_typeid,$leave_start_date,$leave_end_date,$leave_start_time,$leave_end_time);
    if($balance>=0){
        $proceed=true;
    }else{
        $proceed=false;
    }
    if($proceed){

    if($this->checkLeaveDate($request)=="0"){
        return redirect('pending-leave-application');
    }else{
        
        if($difference_days>='3'){
            if($request->file('leave_docext') != ""){

            }else{
                Session::flash('error','Please Upload Medical Document. Medical Document is required for 3 or more days Sick leave');
                return redirect('pending-leave-application');
            }
        }
        
            $insert=DB::table('tbl_leaveapplication')->insert([
                'leave_empid'=>$id->suser_empid,
                'leave_typeid'=>$request->leave_typeid,
                'leave_start_date'=>$request->leave_start_date.' '.$leave_start_time.':00',
                'leave_end_date'=>$request->leave_end_date.' '.$leave_end_time.':00',
                'leave_day'=>$difference_days,
                'leave_reason'=>$request->leave_reason,
                'leave_requested_date'=>date('Y-m-d'),
            ]);

        if($difference_days>='3'){
            $getLastId=DB::table('tbl_leaveapplication')->where('leave_empid',$id->suser_empid)->orderBy('leave_id','DESC','LIMIT','1')->first();
            $file = $request->file('leave_docext');
            $ext=$file->getClientOriginalExtension();
            $destinationPath = base_path().'/public/leaveContent';
            $upload=$file->move($destinationPath,$getLastId->leave_id.'.'.$ext);
            if($upload){
                $updateimageextension=DB::table('tbl_leaveapplication')->where('leave_id',$getLastId->leave_id)->update(['leave_docext'=>$ext]);
            }
        }

            if(isset($insert) || isset($upload)){
                Session::flash('success',$this->getLeaveTypename($request->leave_typeid).' Application Submitted Successfully');
                /*$emp_email='You have submitted a leave application To '.$this->getSeniorName($employee->emp_seniorid).' which is followed by- <br><br>Leave Start Date : '.$request->leave_start_date.'<br>Leave End Date : '.$request->leave_end_date.'<br>Leave Duration : '.$difference_days.' Day(s)<br>Leave Reason : '.$request->leave_reason;
                $this->sendMail($id->email,'Leave Application Submitted to '.$this->getSeniorName($employee->emp_seniorid),$emp_email);
                $senior_email=$employee->emp_name.' has submitted a leave application which is followed by- <br><br>Leave Start Date : '.$request->leave_start_date.'<br>Leave End Date : '.$request->leave_end_date.'<br>Leave Duration : '.$difference_days.' Day(s)<br>Leave Reason : '.$request->leave_reason;
                $this->sendMail($this->getSeniorEmail($employee->emp_seniorid),'Leave Application Received From '.$employee->emp_name,$senior_email);*/
                $this->empHistory($id->suser_empid,'You have submitted a <b>'.$this->getLeaveTypename($request->leave_typeid).'</b> application To <b>'.$this->getSeniorName($employee->emp_seniorid).'</b> which is followed by- <br>Leave Start Date : <b>'.$request->leave_start_date.'</b><br>Leave End Date : <b>'.$request->leave_end_date.'</b><br>Leave Duration : <b>'.$difference_days.'</b> Day(s)<br>Leave Reason : <b>'.$request->leave_reason.'</b>');
                return redirect('pending-leave-application');
            }
    }

    }else{
        Session::flash('error',$this->getLeaveTypename($request->leave_typeid).' Balance is insufficient');
        return redirect('pending-leave-application');
    }
}

public function checkCompensatoryAttendance($id,$request,$employee,$li_id)
{
    $attendanceController=new attendanceController();
    $EmployeeID=$this->getemp_idtoEmployeeID($employee->emp_id);

    $leaveCount=0;
    $date='';
    $replacementDateRange=$this->getDateRange($request->leave_replacement_from_date,$request->leave_replacement_to_date);
    $leaveDateRange=$this->getDateRange($request->leave_start_date,$request->leave_end_date);
    for($count=0;$count<count($replacementDateRange);$count++) {
        if($attendanceController->checkPresent($EmployeeID,$replacementDateRange[$count])=="1"){
            if($attendanceController->checkHoliday($replacementDateRange[$count])=="1"){
                $leaveCount=$leaveCount+2;
            }else{
                $leaveCount++;
            }
        }else{
            $totalOSDDuration=DB::table('tbl_osdattendance')
                ->where('tbl_osdattendance.osd_done_by',$employee->emp_id)
                ->where('tbl_osdattendance.osd_date',$replacementDateRange[$count])
                ->where('tbl_osdattendance.osd_status','1')
                ->get();
            if(isset($totalOSDDuration[0])){
                if($attendanceController->checkHoliday($replacementDateRange[$count])=="1"){
                    $leaveCount=$leaveCount+2;
                }else{
                    $leaveCount++;
                }
            }else{
                $date.=$replacementDateRange[$count].', ';
            }
        }
    }

    if($leaveCount>=count($leaveDateRange)){
        return '1';
    }else{
        Session::flash('error','Sorry! '.$this->getLeaveTypename($li_id).' Request is not valid because you do not have attendance of those days : '.$date);
        return '0';
    }
}

public function compensatoryLeaveRequest($id,$request,$employee,$difference_days,$li_id)
{
    $leave_start_time=explode('&',$this->getStartEndTime($request))[0];
    $leave_end_time=explode('&',$this->getStartEndTime($request))[1];
    if($this->checkLeaveDate($request)=="0"){
        return redirect('pending-leave-application');
    }elseif($this->checkCompensatoryAttendance($id,$request,$employee,$li_id)=="0"){
        return redirect('pending-leave-application');
    }else{
        if($request->leave_replacement_from_date==""){
            Session::flash('error','Please Write Leave Against Date for '.$this->getLeaveTypename($request->leave_typeid));
            return redirect('pending-leave-application');
        }
        if($request->leave_replacement_from_date=="" && $request->leave_replacement_to_date==""){
            $leave_against='From : '.$request->leave_replacement_from_date.' To : '.$request->leave_replacement_to_date;
        }else{
            $leave_against=$request->leave_replacement_from_date;
        }
        $insert=DB::table('tbl_leaveapplication')->insert([
            'leave_empid'=>$id->suser_empid,
            'leave_typeid'=>$request->leave_typeid,
            'leave_start_date'=>$request->leave_start_date.' '.$leave_start_time.':00',
            'leave_end_date'=>$request->leave_end_date.' '.$leave_end_time.':00',
            'leave_day'=>$difference_days,
            'leave_reason'=>$request->leave_reason,
            'leave_replacement_date'=>$leave_against,
            'leave_requested_date'=>date('Y-m-d'),
        ]);
        if($insert){
            Session::flash('success',$this->getLeaveTypename($request->leave_typeid).' Application Submitted Successfully');
            /*$emp_email='You have submitted a leave application To '.$this->getSeniorName($employee->emp_seniorid).' which is followed by- <br><br>Leave Start Date : '.$request->leave_start_date.'<br>Leave End Date : '.$request->leave_end_date.'<br>Leave Duration : '.$difference_days.' Day(s)<br>Leave Reason : '.$request->leave_reason;
            $this->sendMail($id->email,'Leave Application Submitted to '.$this->getSeniorName($employee->emp_seniorid),$emp_email);
            $senior_email=$employee->emp_name.' has submitted a leave application which is followed by- <br><br>Leave Start Date : '.$request->leave_start_date.'<br>Leave End Date : '.$request->leave_end_date.'<br>Leave Duration : '.$difference_days.' Day(s)<br>Leave Reason : '.$request->leave_reason;
            $this->sendMail($this->getSeniorEmail($employee->emp_seniorid),'Leave Application Received From '.$employee->emp_name,$senior_email);*/
            $this->empHistory($id->suser_empid,'You have submitted a <b>'.$this->getLeaveTypename($request->leave_typeid).'</b> application To <b>'.$this->getSeniorName($employee->emp_seniorid).'</b> which is followed by- <br>Leave Start Date : <b>'.$request->leave_start_date.'</b><br>Leave End Date : <b>'.$request->leave_end_date.'</b><br>Leave Duration : <b>'.$difference_days.'</b> Day(s)<br>Leave Reason : <b>'.$request->leave_reason.'</b>');
            return redirect('pending-leave-application');
        }
    }
}

public function matarnityLeaveTaken($id,$li_id)
{
    $leaveTaken=DB::table('tbl_leaveapplication')
        ->join('tbl_employee','tbl_leaveapplication.leave_empid','=','tbl_employee.emp_id')
        ->where(['tbl_leaveapplication.leave_typeid'=>$li_id,'tbl_leaveapplication.leave_empid'=>$id->suser_empid])
        ->where('tbl_leaveapplication.leave_status','<','2')
        ->count(['tbl_leaveapplication.leave_id']);
    if($leaveTaken>=2){
        Session::flash('error','Sorry! You have already taken Maximum of Two '.$this->getLeaveTypename($li_id));
    }
    return $leaveTaken;
}

public function matarnityLeaveRequest($id,$request,$employee,$difference_days,$li_id)
{
    if($this->checkLeaveDate($request)=="0"){
        return redirect('pending-leave-application');
    }elseif($this->matarnityLeaveTaken($id,$li_id)>="2"){
        return redirect('pending-leave-application');
    }else{
        $insert=DB::table('tbl_leaveapplication')->insert([
            'leave_empid'=>$id->suser_empid,
            'leave_typeid'=>$request->leave_typeid,
            'leave_start_date'=>$request->leave_start_date.' 00:00:00',
            'leave_end_date'=>$request->leave_end_date.' 00:00:00',
            'leave_day'=>$difference_days,
            'leave_reason'=>$request->leave_reason,
            'leave_requested_date'=>date('Y-m-d'),
        ]);
        if($insert){
            Session::flash('success',$this->getLeaveTypename($request->leave_typeid).' Application Submitted Successfully');
            /*$emp_email='You have submitted a leave application To '.$this->getSeniorName($employee->emp_seniorid).' which is followed by- <br><br>Leave Start Date : '.$request->leave_start_date.'<br>Leave End Date : '.$request->leave_end_date.'<br>Leave Duration : '.$difference_days.' Day(s)<br>Leave Reason : '.$request->leave_reason;
            $this->sendMail($id->email,'Leave Application Submitted to '.$this->getSeniorName($employee->emp_seniorid),$emp_email);
            $senior_email=$employee->emp_name.' has submitted a leave application which is followed by- <br><br>Leave Start Date : '.$request->leave_start_date.'<br>Leave End Date : '.$request->leave_end_date.'<br>Leave Duration : '.$difference_days.' Day(s)<br>Leave Reason : '.$request->leave_reason;
            $this->sendMail($this->getSeniorEmail($employee->emp_seniorid),'Leave Application Received From '.$employee->emp_name,$senior_email);*/
            $this->empHistory($id->suser_empid,'You Have submitted a <b>'.$this->getLeaveTypename($request->leave_typeid).'</b> application To <b>'.$this->getSeniorName($employee->emp_seniorid).'</b> which is followed by- <br>Leave Start Date : <b>'.$request->leave_start_date.'</b><br>Leave End Date : <b>'.$request->leave_end_date.'</b><br>Leave Duration : <b>'.$difference_days.'</b> Day(s)<br>Leave Reason : <b>'.$request->leave_reason.'</b>');
            return redirect('pending-leave-application');
        }
    }
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

public function filterleaveApplicationEmployee_filter($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type, $emp_id,$start_date,$end_date,$status)
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
            ->where('tbl_employee.emp_id', $emp_id)
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

public function getLeaveApplicationRow($leaveapplication,$status)
{
    $id =   Auth::guard('admin')->user();

    $data='';
    if(isset($leaveapplication[0])){
    $c=0;
        foreach ($leaveapplication as $la){
        if($la->leave_status==$status){
        $c++;
         $data.='<tr class="gradeX" id="tr-'.$la->leave_id.'">
          <td style="text-align: center;">
                <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                    <input type="checkbox" class="checkboxes" value="'.$la->leave_id.'" name="leave_id[]" />
                    <span></span>
                </label>
            </td>
            <td>'.$c.'</td>
            <td>'.$la->leave_id.'</td>
            <td>'.$la->emp_empid.'</td>
            <td>'.$la->emp_name.'</td>
            <td>'.$la->li_name.'<br>';
            if($la->leave_empid==$id->suser_empid){
                if($la->leave_note=="Short Leave Taken" or $la->leave_typeid=="2"){
                    $data.='<a href="'.URl::to('printACopy').'/leaveApplication/'.$la->leave_id.'" target="_blank">Print a Copy</a>';
                }
            }
            $data.='</td>
            <td>'.date('d-m-Y', strtotime($la->leave_start_date )).'</td>
            <td>'.date('d-m-Y', strtotime($la->leave_end_date  )).'</td>
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
    }

    return $data;
}

public function leaveApplication(){

    $flag=0;
    $emp_id=0;

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

    $leave_type=DB::table('tbl_leavetype')->get();

    $qouta=DB::table('tbl_leavetype')->where('li_id','1')->first();
    $leave=DB::table('tbl_leaveapplication')->where(['leave_empid'=>$id->suser_empid,'leave_typeid'=>'1','leave_status'=>'1'])->sum('leave_day');
    $balance=$qouta->li_qoutaday-$leave;

    $leaveapplication=$this->filterleaveApplicationEmployee('0','0','0','0','0','0','0','1');
    $data=$this->getLeaveApplicationRow($leaveapplication,'1');
    $data.=$this->getLeaveApplicationRow($leaveapplication,'2');
    $current_leave_status=$this->currentLeaveStatus();
    $title='Leave Application List';


    return view('Admin.leave.leaveApplication',compact('id','mainlink','sublink','Adminminlink','adminsublink','types','designation','department','joblocation','data','leave_type','balance','current_leave_status','title', 'flag', 'emp_id'));
}

public function leave_application_print(){
    // echo "string"; exit();
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

    $leave_type=DB::table('tbl_leavetype')->get();

    $qouta=DB::table('tbl_leavetype')->where('li_id','1')->first();
    $leave=DB::table('tbl_leaveapplication')->where(['leave_empid'=>$id->suser_empid,'leave_typeid'=>'1','leave_status'=>'1'])->sum('leave_day');
    $balance=$qouta->li_qoutaday-$leave;

    $leaveapplication=$this->filterleaveApplicationEmployee('0','0','0','0','0','0','0','1');
    $data=$this->getLeaveApplicationRow($leaveapplication,'1');
    $data.=$this->getLeaveApplicationRow($leaveapplication,'2');
    $current_leave_status=$this->currentLeaveStatus();
    $title='Leave Application List';


    return view('Admin.leave.leave_print',compact('id','mainlink','sublink','Adminminlink','adminsublink','types','designation','department','joblocation','data','leave_type','balance','current_leave_status','title'));
}

public function pendingLeaveApplication(){
    $emp_id=0;
    $flag=0;
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

    $leave_type=DB::table('tbl_leavetype')->get();
    $qouta=DB::table('tbl_leavetype')->where('li_id','1')->first();
    $leave=DB::table('tbl_leaveapplication')->where(['leave_empid'=>$id->suser_empid,'leave_typeid'=>'1','leave_status'=>'1'])->sum('leave_day');

    $balance=$qouta->li_qoutaday-$leave;


    $leaveapplication=$this->filterleaveApplicationEmployee('0','0','0','0','0','0','0','0');
    //dd($leaveapplication);
    $data=$this->getLeaveApplicationRow($leaveapplication,'0');

    $current_leave_status=$this->currentLeaveStatus();
    //dd($current_leave_status);
    $title='Pending Leave Application List';

    return view('Admin.leave.leaveApplication',compact('id','mainlink','sublink','Adminminlink','adminsublink','types','designation','department','joblocation','data','leave_type','balance','current_leave_status','title', 'emp_id', 'flag'));
}

public function leaveApplicationSearch($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type,$start_date,$end_date)
{
    $emp_id=0;
    $flag=1;
    $id =   Auth::guard('admin')->user();
    $mainlink = $this->adminmainmenu();
    $sublink = $this->adminsubmenu();
    $Adminminlink = $this->adminlink();
    $adminsublink = $this->adminsublink();

    $types=EmployeeTypes::orderBy('name','asc')->get();
    $designation=DB::table('tbl_designation')->get();
    $department=DB::table('tbl_department')->get();
    $joblocation=DB::table('tbl_joblocation')->get();

    $leave_type=DB::table('tbl_leavetype')->get();

    $qouta=DB::table('tbl_leavetype')->where('li_id','1')->first();
    $leave=DB::table('tbl_leaveapplication')->where(['leave_empid'=>$id->suser_empid,'leave_status'=>'1'])->sum('leave_day');
    $balance=$qouta->li_qoutaday-$leave;

    $leaveapplication=$this->filterleaveApplicationEmployee($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type,$start_date,$end_date,'1');
    $data=$this->getLeaveApplicationRow($leaveapplication,'1');
    $data.=$this->getLeaveApplicationRow($leaveapplication,'2');
    $current_leave_status=$this->currentLeaveStatus();
    $title='Leave Application List';
    $replacement = 0;

    return view('Admin.leave.leaveApplication',compact('id','mainlink','sublink','Adminminlink','adminsublink','data','types','emp_depart_id','emp_sdepart_id','emp_desig_id','emp_jlid','emp_type','designation','department','joblocation','start_date','end_date','leave_type','replacement','balance','current_leave_status','title', 'emp_id'));
}

public function leave_application_filter(Request $request)
{
    // echo "string"; exit();

    // return $request; exit();
    $flag=1;
    // $emp_id=0;

    $emp_id = $request->emp;
    $start_date = $request->start_date;
    $end_date = $request->end_date;

    $id =   Auth::guard('admin')->user();
    $mainlink = $this->adminmainmenu();
    $sublink = $this->adminsubmenu();
    $Adminminlink = $this->adminlink();
    $adminsublink = $this->adminsublink();

    $types=EmployeeTypes::orderBy('name','asc')->get();
    $designation=DB::table('tbl_designation')->get();
    $department=DB::table('tbl_department')->get();
    $joblocation=DB::table('tbl_joblocation')->get();

    $leave_type=DB::table('tbl_leavetype')->get();

    $qouta=DB::table('tbl_leavetype')->where('li_id','1')->first();
    $leave=DB::table('tbl_leaveapplication')->where(['leave_empid'=>$id->suser_empid,'leave_status'=>'1'])->sum('leave_day');
    $balance=$qouta->li_qoutaday-$leave;

    $leaveapplication=$this->filterleaveApplicationEmployee_filter('0','0','0','0','0',$emp_id ,$start_date,$end_date,'1');
    $data=$this->getLeaveApplicationRow($leaveapplication,'1');
    $data.=$this->getLeaveApplicationRow($leaveapplication,'2');
    $current_leave_status=$this->currentLeaveStatus();
    $title='Leave Application List';
    $replacement = 0;
    // return $leaveapplication; exit();
    return view('Admin.leave.leaveApplication',compact('id','mainlink','sublink','Adminminlink','adminsublink','data','types','emp_depart_id','emp_sdepart_id','emp_desig_id','emp_jlid','emp_type','designation','department','joblocation','start_date','end_date','leave_type','replacement','balance','current_leave_status','title', 'flag', 'emp_id'));
}

public function pendingLeaveApplicationSearch($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type,$start_date,$end_date)
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

    $leave_type=DB::table('tbl_leavetype')->get();

    $qouta=DB::table('tbl_leavetype')->where('li_id','1')->first();
    $leave=DB::table('tbl_leaveapplication')->where(['leave_empid'=>$id->suser_empid,'leave_status'=>'1'])->sum('leave_day');
    $balance=$qouta->li_qoutaday-$leave;

    $leaveapplication=$this->filterleaveApplicationEmployee($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type,$start_date,$end_date,'0');
    $data=$this->getLeaveApplicationRow($leaveapplication,'0');
    $current_leave_status=$this->currentLeaveStatus();
    $title='Pending Leave Application List';

    $replacement = 0;

    return view('Admin.leave.leaveApplication',compact('id','mainlink','sublink','Adminminlink','adminsublink','data','types','emp_depart_id','emp_sdepart_id','emp_desig_id','emp_jlid','emp_type','designation','department','joblocation','start_date','end_date','leave_type','replacement','balance','current_leave_status','title'));
}


public function leaveAprove(Request $request){
    $id =   Auth::guard('admin')->user();
    if(count($request->leave_id)>0){
        $aprove_count=0;
        $error_count=0;
        $not_permitted=0;
        for ($i=0; $i < count($request->leave_id) ; $i++) {
            $check=DB::table('tbl_leaveapplication')->where('leave_id',$request->leave_id[$i])->first();
            if($check->leave_status=="0"){
                if($this->permittedEmployee($check->leave_empid)=="1"){
                    $aprove=DB::table('tbl_leaveapplication')
                    ->where('leave_id',$request->leave_id[$i])
                    ->update(['leave_status'=>'1','leave_approved_userid'=>$id->suser_empid,'leave_approved_date'=>date('Y-m-d')]);
                    if($aprove){
                        $employee=DB::table('tbl_employee')->where('tbl_employee.emp_id',$check->leave_empid)->first();
                        $this->empHistory($check->leave_empid,'A '.$this->getLeaveTypename($check->leave_typeid).' application has been Approved which was submitted To <b>'.$this->getSeniorName($employee->emp_seniorid).'</b> which is followed by- <br>Leave Start Date : <b>'.$check->leave_start_date.'</b><br>Leave End Date : <b>'.$check->leave_end_date.'</b><br>Leave Duration : <b>'.$check->leave_day.'</b> Day(s)<br>Leave Reason : <b>'.$check->leave_reason.'</b>');
                        $aprove_count++;
                    }
                }else{
                    $not_permitted++;
                }
            }else{
                $error_count++;
            }

            if($aprove_count>0){
                Session::flash('success',$aprove_count.' Leave Application(s) Approved Successfully.');
            }

            if($error_count>0 or $not_permitted>0){
                $data='';
                if($error_count>0){
                    $data.=$error_count.' Leave Application(s) Cannot Be Approved. May Be These Leave Application(s) Approoved/Denied Previously.';
                }
                if($not_permitted>0){
                    $data.='And '.$not_permitted.' Leave Application(s) Cannot Be Approved because you cannot aprove your own Leave Application(s)';
                }
                Session::flash('error',$data);
            }
            
        }
        return '1';
    }else{
        return 'null';
    }
}

public function leaveDeny(Request $request){
    $id =   Auth::guard('admin')->user();
    if(count($request->leave_id)>0){
        $aprove_count=0;
        $error_count=0;
        $not_permitted=0;
        for ($i=0; $i < count($request->leave_id) ; $i++) {
            $check=DB::table('tbl_leaveapplication')->where('leave_id',$request->leave_id[$i])->first();
            if($check->leave_status=="0"){
                if($this->permittedEmployee($check->leave_empid)=="1"){
                    $aprove=DB::table('tbl_leaveapplication')
                    ->where('leave_id',$request->leave_id[$i])
                    ->update(['leave_status'=>'2','leave_approved_userid'=>$id->suser_empid,'leave_approved_date'=>date('Y-m-d')]);
                    if($aprove){
                        $employee=DB::table('tbl_employee')->where('tbl_employee.emp_id',$check->leave_empid)->first();
                        $this->empHistory($check->leave_empid,'A '.$this->getLeaveTypename($check->leave_typeid).' application has been Denied which was submitted To <b>'.$this->getSeniorName($employee->emp_seniorid).'</b> which is followed by- <br>Leave Start Date : <b>'.$check->leave_start_date.'</b><br>Leave End Date : <b>'.$check->leave_end_date.'</b><br>Leave Duration : <b>'.$check->leave_day.'</b> Day(s)<br>Leave Reason : <b>'.$check->leave_reason.'</b>');
                        $aprove_count++;
                    }
                }else{
                    $not_permitted++;
                }
            }else{
                $error_count++;
            }

            if($aprove_count>0){
                Session::flash('success',$aprove_count.' Leave Application(s) Denied Successfully.');
            }

            if($error_count>0 or $not_permitted>0){
                $data='';
                if($error_count>0){
                    $data.=$error_count.' Leave Application(s) Cannot Be Denied. May Be These Leave Application(s) Approoved/Denied Previously.';
                }
                if($not_permitted>0){
                    $data.='And '.$not_permitted.' Leave Application(s) Cannot Be Denied because you cannot deny your own Leave Application(s)';
                }
                Session::flash('error',$data);
            }
        }
        return '1';
    }else{
        return 'null';
    }
}

public function leaveApplicationDelete(Request $request)
{
    $id =   Auth::guard('admin')->user();
    if(count($request->leave_id)>0){
        $approvecount=0;
        $denycount=0;
        $deletecount=0;
        $not_permitted=0;
        for ($i=0; $i < count($request->leave_id) ; $i++) {
            $check=DB::table('tbl_leaveapplication')->where('leave_id',$request->leave_id[$i])->first();
            if($check->leave_status=="1"){
                $approvecount++;
            }elseif($check->leave_status=="2"){
                $denycount++;
            }elseif($check->leave_status=="0"){
                if($id->suser_empid==$check->leave_empid){
                    $delete=DB::table('tbl_leaveapplication')->where('leave_id',$request->leave_id[$i])->delete();
                    if(isset($delete)){
                        $deletecount++;
                    }
                }else{
                    $not_permitted++;
                }
            }
        }

        if($approvecount>0 or $denycount>0 or $deletecount>0 or $not_permitted>0){
            if($approvecount>0 or $denycount>0 or $not_permitted>0){
                $data='';
                if($approvecount>0){
                    $data.=$approvecount.' leave Application cannot be deleted because they might be Approved already.';
                }

                if($denycount>0){
                    $data.='And '.$denycount.' leave Application cannot be deleted because they might be Denied already.';
                }

                if($not_permitted>0){
                    $data.=$not_permitted.' Leave Application(s) cannot be deleted.You can only delete your own Leave Application';
                }
                Session::flash('error',$data);
            }

            if($deletecount>0){
                Session::flash('success',$deletecount.' Leave Application(s) has been Deleted Successfully.');
            }
            return '1';
        }else{
            return '0';
        }
    }else{
        return 'null';
    }
}

public function printACopy($leave_id)
{
    $id =   Auth::guard('admin')->user();
    $leaveApplication=DB::table('tbl_leaveapplication')->where(['leave_id'=>$leave_id,'leave_empid'=>$id->suser_empid,['leave_status','!=','2']])->first();
    if(isset($leaveApplication)){
        if($leaveApplication->leave_note=="Short Leave Taken" or $leaveApplication->leave_typeid=="2"){
            $employee=DB::table('tbl_employee')->where('emp_id',$id->suser_empid)->first();
            return view('Admin.leave.printACopy',compact('leaveApplication','employee'));
        }else{
            echo '<script>window.close()</script>';
        }
    }else{
        echo '<script>window.close()</script>';
    }
}

}