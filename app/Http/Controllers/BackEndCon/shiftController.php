<?php

namespace App\Http\Controllers\BackEndCon;

use Illuminate\Http\Request;
use App\Http\Controllers\BackEndCon\Controller;
use Auth;
use Session;
use DB;
use DateTime;
use URL;
use App\EmployeeTypes;
use App\Employee;
use App\Shift;

class shiftController extends Controller
{

public function shift(){
    $id =   Auth::guard('admin')->user();
    $mainlink = $this->adminmainmenu();
    $sublink = $this->adminsubmenu();
    $Adminminlink = $this->adminlink();
    $adminsublink = $this->adminsublink();

    return view('Admin.settings.shift.create',compact('id','mainlink','sublink','Adminminlink','adminsublink'));
}

public function shiftAdd(Request $request)
{
    // return $request; exit();
    $shift_start=substr($request->shift_stime,0,2);
    $shift_end=substr($request->shift_etime,0,2);
    $difference= (int)($shift_end)-(int)($shift_start);
    // return $difference; exit();

    if($request->shift_type==1){
        $hour=7;
    }
    elseif($request->shift_type==2){
        $hour=8;
    }
    elseif($request->shift_type==3){
        $hour=6;
    }
    elseif($request->shift_type==4){
        $hour=5;
    }
    elseif($request->shift_type==5){
        $hour=4;
    }
    elseif($request->shift_type==6){
        $hour=3;
    }
    elseif($request->shift_type==7){
        $hour=9;
    }
    elseif($request->shift_type==8){
        $hour=10;
    }
    elseif($request->shift_type==9){
        $hour=11;
    }
    elseif($request->shift_type=10){
        $hour=12;
    }




    if($hour!=$difference)
    {
        Session::flash('error','Shift type and Time Difference Do Not Matched.');
        return back();
    }

    $uniq_check=Shift::where('shift_stime', $request->shift_stime)
    ->where('shift_etime', $request->shift_etime)
    ->first();

    if($uniq_check){
        Session::flash('error','Shift Already Exist.');
        return back();
    }


    $this->validate($request,[
            'shift_type'=>'required',
            'shift_stime'=>'required',
            'shift_etime'=>'required',
        ]);

    $shift_etime = new DateTime($request->shift_etime.':00');
    $shift_stime = new DateTime($request->shift_stime.':00');
    $difference=$shift_etime->diff($shift_stime);
    if($request->shift_type=="1"){
        $insert=DB::table('tbl_shift')->insert([
                'shift_type'=>$request->shift_type,
                'shift_stime'=>$request->shift_stime,
                'shift_etime'=>$request->shift_etime,
            ]);
            if(isset($insert)){
                Session::flash('success','Shift Information Saved Successfully.');
            }else{
                Session::flash('error','Sorry!! Something Went Wrong!!');
            }
        /*if($difference->h==8){
            
        }else if($difference->h>8){
            Session::flash('error','Shift Duration : '.$difference->h.' Hours '.$difference->i.' Minutes is More then 8 Hours Shift Duration');
        }else if($difference->h<8){
            Session::flash('error','Shift Duration : '.$difference->h.' Hours '.$difference->i.' Minutes is Less then 8 Hours Shift Duration');
        }*/
    }else if($request->shift_type=="2"){
        $insert=DB::table('tbl_shift')->insert([
                'shift_type'=>$request->shift_type,
                'shift_stime'=>$request->shift_stime,
                'shift_etime'=>$request->shift_etime,
            ]);
            if(isset($insert)){
                Session::flash('success','Shift Information Saved Successfully.');
            }else{
                Session::flash('error','Sorry!! Something Went Wrong!!');
            }
        /*if($difference->h==9){
            
            
        }else if($difference->h>9){
            Session::flash('error','Shift Duration : '.$difference->h.' Hours '.$difference->i.' Minutes is More then 9 Hours Shift Duration');
        }else if($difference->h<9){
            Session::flash('error','Shift Duration : '.$difference->h.' Hours '.$difference->i.' Minutes is Less then 9 Hours Shift Duration');
        }*/
    }else{
        $insert=DB::table('tbl_shift')->insert([
                'shift_type'=>$request->shift_type,
                'shift_stime'=>$request->shift_stime,
                'shift_etime'=>$request->shift_etime,
            ]);
            if(isset($insert)){
                Session::flash('success','Shift Information Saved Successfully.');
            }else{
                Session::flash('error','Sorry!! Something Went Wrong!!');
            }
    }
    
    return redirect('shift-view/create');
}

public function shiftView(){
    $id =   Auth::guard('admin')->user();
    $mainlink = $this->adminmainmenu();
    $sublink = $this->adminsubmenu();
    $Adminminlink = $this->adminlink();
    $adminsublink = $this->adminsublink();
    $shift=DB::table('tbl_shift')->get();
    return view('Admin.settings.shift.view',compact('id','mainlink','sublink','Adminminlink','adminsublink','shift'));
}

public function shiftEdit(Request $request)
{
    if(count($request->shift_id)>0){
        if(count($request->shift_id)>1){
            return 'max';
        }else{
            return $request->shift_id[0];
        }
    }else{
        return 'null';
    }
}

public function shiftEditView($shift_id)
{
    $id =   Auth::guard('admin')->user();
    $mainlink = $this->adminmainmenu();
    $sublink = $this->adminsubmenu();
    $Adminminlink = $this->adminlink();
    $adminsublink = $this->adminsublink();
    $shift=DB::table('tbl_shift')->where('shift_id',$shift_id)->first();
    return view('Admin.settings.shift.editView',compact('id','mainlink','sublink','Adminminlink','adminsublink','shift','shift_id'));
}

public function shiftUpdate(Request $request,$shift_id)
{
    // return $request; exit();
    $shift_start=substr($request->shift_stime,0,2);
    $shift_end=substr($request->shift_etime,0,2);
    $dif=(int)($shift_end)-(int)($shift_start);
    // return $difference; exit();

    if($request->shift_type==1){
        $hour=7;
    }
    elseif($request->shift_type==2){
        $hour=8;
    }
    elseif($request->shift_type==3){
        $hour=6;
    }
    elseif($request->shift_type==4){
        $hour=5;
    }
    elseif($request->shift_type==5){
        $hour=4;
    }
    elseif($request->shift_type==6){
        $hour=3;
    }
    elseif($request->shift_type==7){
        $hour=9;
    }
    elseif($request->shift_type==8){
        $hour=10;
    }
    elseif($request->shift_type==9){
        $hour=11;
    }
    elseif($request->shift_type=10){
        $hour=12;
    }




    if($hour!=$dif)
    {
        Session::flash('error','Shift type and Time Difference Do Not Matched.');
        return redirect('shift/'.$shift_id.'/edit');
    }

    $uniq_check=Shift::where('shift_stime', $request->shift_stime)
    ->where('shift_etime', $request->shift_etime)
    ->first();

    // if($uniq_check){
    //     Session::flash('error','Shift Already Exist.');
    //     return back();
    // }

    $this->validate($request,[
            'shift_type'=>'required',
            'shift_stime'=>'required',
            'shift_etime'=>'required',                       
            'shift_status'=>'required',
        ]);
    // $shift_etime = new DateTime($request->shift_etime.':00 0000-00-00');
    // $shift_stime = new DateTime($request->shift_stime.':00 0000-00-00');
    // $difference=$shift_etime->diff($shift_stime);
    // if($request->shift_type=="1"){
    //     if($difference->h==7){
    //         $update=DB::table('tbl_shift')
    //             ->where('shift_id',$shift_id)
    //             ->update([
    //                 'shift_type'=>$request->shift_type,
    //                 'shift_stime'=>$request->shift_stime,
    //                 'shift_etime'=>$request->shift_etime,
    //                 'shift_status'=>$request->shift_status,
    //             ]);
    //         if($update){
    //             Session::flash('success','Shift Information Updated Successfully.');
    //             return redirect('shift-view');
    //         }else{
    //             Session::flash('error','Sorry!! Something Went Wrong!!');
    //             return redirect('shift/'.$shift_id.'/edit');
    //         }
    //     }else if($difference->h>7){
    //         Session::flash('error','Shift Duration : '.$difference->h.' Hours '.$difference->i.' Minutes is More then 7 Hours Shift Duration');
    //         return redirect('shift/'.$shift_id.'/edit');
    //     }else if($difference->h<7){
    //         Session::flash('error','Shift Duration : '.$difference->h.' Hours '.$difference->i.' Minutes is Less then 7 Hours Shift Duration');
    //         return redirect('shift/'.$shift_id.'/edit');
    //     }
    // }else if($request->shift_type=="2"){
    //     if($difference->h==8){
    //         $update=DB::table('tbl_shift')
    //             ->where('shift_id',$shift_id)
    //             ->update([
    //                 'shift_type'=>$request->shift_type,
    //                 'shift_stime'=>$request->shift_stime,
    //                 'shift_etime'=>$request->shift_etime,
    //                 'shift_status'=>$request->shift_status,
    //             ]);
    //         if($update){
    //             Session::flash('success','Shift Information Updated Successfully.');
    //             return redirect('shift-view');
    //         }else{
    //             Session::flash('error','Sorry!! Something Went Wrong!!');
    //             return redirect('shift/'.$shift_id.'/edit');
    //         }
    //     }else if($difference->h>8){
    //         Session::flash('error','Shift Duration : '.$difference->h.' Hours '.$difference->i.' Minutes is More then 8 Hours Shift Duration');
    //         return redirect('shift/'.$shift_id.'/edit');
    //     }else if($difference->h<8){
    //         Session::flash('error','Shift Duration : '.$difference->h.' Hours '.$difference->i.' Minutes is Less then 8 Hours Shift Duration');
    //         return redirect('shift-view/'.$shift_id.'/edit');
    //     }
    // }else{
        $update=DB::table('tbl_shift')
                ->where('shift_id',$shift_id)
                ->update([
                    'shift_type'=>$request->shift_type,
                    'shift_stime'=>$request->shift_stime,
                    'shift_etime'=>$request->shift_etime,
                    'shift_status'=>$request->shift_status,
                ]);
            if($update){
                Session::flash('success','Shift Information Updated Successfully.');
                return redirect('shift-view');
            }

    // }
}


public function shiftDelete(Request $request)
{
    if(count($request->shift_id)>0){
        for ($i=0; $i < count($request->shift_id) ; $i++) { 
            $delete_attendance=DB::table('tbl_shift')->where('shift_id',$request->shift_id[$i])->delete();
        }
        if($delete_attendance){
            Session::flash('success','Shift(s) Deleted Successfully.');
            return '1';
        }else{
            return '0';
        }
    }else{
        return 'null';
    }
}

public function filterShiftWiseEmployee($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type)
{
    $id =   Auth::guard('admin')->user();
    if($id->suser_level=="1"){
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
            ->where('tbl_employee.emp_status', 1)
            ->get(['tbl_employee.*','tbl_shift.shift_type']);
    }elseif($id->suser_level=="4"){
        return $shiftData=DB::table('tbl_employee')
            ->join('tbl_shift','tbl_employee.emp_shiftid','=','tbl_shift.shift_id')
            ->where('tbl_employee.emp_seniorid',$id->suser_empid)
            ->get(['tbl_employee.*','tbl_shift.shift_type']);
    }elseif($id->suser_level=="2" or $id->suser_level=="3" or $id->suser_level=="5" or $id->suser_level=="6"){
        return $shiftData=DB::table('tbl_employee')
            ->join('tbl_shift','tbl_employee.emp_shiftid','=','tbl_shift.shift_id')
            ->where('tbl_employee.emp_id',$id->suser_empid)
            ->get(['tbl_employee.*','tbl_shift.shift_type']);
    }
}

public function getShiftWiseEmployeeRow($shiftData,$id)
{
    $data='';
    if(isset($shiftData[0])){
      $c=0;
        foreach ($shiftData as $sd){
        $c++;
        // if($this->getCurrentShiftInfo($sd->emp_id,date('Y-m-d'))){
        //     $shift_info=$this->getShiftInfo($this->getCurrentShiftInfo($sd->emp_id,date('Y-m-d'))->shift_id);
        // }else{
        //     $shift_info='-';
        // }
        $shift_info= Employee::join('tbl_shift', 'tbl_shift.shift_id', '=', 'tbl_employee.emp_shiftid')
        ->where('tbl_employee.emp_id', $sd->emp_id)
        ->first();


        $data.='<tr class="gradeX" id="tr-'.$sd->emp_id.'">
            <td>'.$c.'</td>
            <td>'.$sd->emp_empid.'</td>';
        if($id->suser_level=="1"){
            $data.='<td>'.$sd->emp_machineid.'</td>';
        }
        $data.='<td><a href="'.URL::to('employee-details').'/'.$sd->emp_id.'/view">'.$sd->emp_name.'</a></td>
            <td>'.$this->getDepartmentName($sd->emp_depart_id).'</td>
            <td>';
            if($sd->shift_type=="1"){
                $data.='7 Hours';
            }elseif($sd->shift_type=="2"){
                 $data.='8 Hours';
            }elseif($sd->shift_type=="3"){
                 $data.='6 Hours';
            }
            $data.='</td>
            <td>'.$shift_info->shift_stime.'-'.$shift_info->shift_etime.'</td><td hidden>';
            if($this->checkAppModulePriority('shift-wise-employee-list','Shift Update')=="1"){
                $data.='<a class="btn btn-xs btn-primary" onclick="ShiftUpdate('.$sd->emp_id.');">Update Shift</a>';
            }
            $data.'</td>
          </tr>';
        }
    }

    return $data;
}

public function shiftWiseEmployeeList()
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

    $shiftData=$this->filterShiftWiseEmployee('0','0','0','0','0');

    $data=$this->getShiftWiseEmployeeRow($shiftData,$id);

    // return $data; exit();

    return view('Admin.settings.shift.shift',compact('id','mainlink','sublink','Adminminlink','adminsublink','types','designation','department','joblocation','data'));
}

public function shiftWiseEmployeeListSearch($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type)
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

    $shiftData=$this->filterShiftWiseEmployee($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type);
    
    $data=$this->getShiftWiseEmployeeRow($shiftData,$id);

    return view('Admin.settings.shift.shift',compact('id','mainlink','sublink','Adminminlink','adminsublink','types','designation','department','joblocation','emp_depart_id','emp_sdepart_id','emp_desig_id','emp_jlid','emp_type','data'));
}

public function getShiftUpdate($emp_id)
{
    $info=DB::table('tbl_employee')
        ->where('emp_id',$emp_id)
        ->first(['emp_id','emp_workhr','emp_shiftid']);
    return view('Admin.settings.shift.shiftUpdate',compact('info'));
}

public function UpdateShift(Request $request,$emp_id)
{
    $id =   Auth::guard('admin')->user();
    $start_date=$request->start_date;
    $end_date=$request->end_date;
    $cycle=0;
    $success=0;
    $success_msg='';
    $error=0;
    $error_msg='';

    $days=0;
    for($i=0;$i<count($request->days);$i++){
        $days+=$request->days[$i];
    }

    $start = new DateTime($start_date);
    $end = new DateTime($end_date);
    $difference = $start->diff($end);
    $difference->format('%R%a days');

    $count=1;
    if($difference->days>0){
        $count=$difference->days/$days;
    }
    
    for($c=0;$c<$count;$c++){
        for($i=0;$i<count($request->emp_shiftid);$i++){
            $cycle++;
            if($cycle==1){
                $ds_date=$start_date;
            }else{
                if($i==0){
                    $ds_date=date('Y-m-d',strtotime($ds_date.' +'.$request->days[0].' days'));
                }else{
                    $ds_date=date('Y-m-d',strtotime($ds_date.' +'.$request->days[$i-1].' days'));
                }
            }

            $search=DB::table('tbl_dailyshift')
                ->where([
                    'ds_empid'=>$emp_id,
                    'ds_date'=>$ds_date,
                ])
                ->first();
            if(isset($search->ds_empid)){
                $Shift=DB::table('tbl_dailyshift')
                    ->where([
                        'ds_empid'=>$emp_id,
                        'ds_date'=>$ds_date,
                    ])
                    ->update([
                        'ds_shiftid'=>$request->emp_shiftid[$i],
                        'ds_createdat'=>date('Y-m-d H:i:s'),
                        'ds_createdby'=>$id->suser_empid,
                    ]);
            }else{
                $Shift=DB::table('tbl_dailyshift')
                    ->insert([
                        'ds_empid'=>$emp_id,
                        'ds_shiftid'=>$request->emp_shiftid[$i],
                        'ds_date'=>$ds_date,
                        'ds_createdat'=>date('Y-m-d H:i:s'),
                        'ds_createdby'=>$id->suser_empid,
                    ]);
            }

            if($Shift){
                $success++;
            }else{
                $error++;
            }
        }
    }

    if($success>0){
        $success_msg.='Shift generated for '.$success.' dates successfully';
    }

    if($error>0){
        $error_msg.='Shift could not be generated for '.$error.' dates';
    }

    return response()->json([
        "success"=>$success,
        "success_msg"=>$success_msg,
        "error"=>$error,
        "error_msg"=>$error_msg,
    ]);
}

public function getshiftWiseEmployeeSummaryRow($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type)
{
    $tbl_shift=DB::table('tbl_shift')
        ->join('tbl_employee','tbl_employee.emp_shiftid','=','tbl_shift.shift_id')
        ->groupBy('tbl_shift.shift_id')
        ->get();

    $data='';
    if(isset($tbl_shift[0])){
        $c=0;
        foreach ($tbl_shift as $ts) {
            $c++;
            $employee=DB::table('tbl_shift')
                ->join('tbl_employee','tbl_employee.emp_shiftid','=','tbl_shift.shift_id')
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
                ->where('tbl_shift.shift_id',$ts->shift_id)
                ->count('tbl_employee.emp_id');

            $data.='<tr class="gradeX" id="tr-'.$ts->shift_id.'">
                        <td>'.$c.'</td>
                        <td>';
            if($ts->shift_type=="1"){
                $data.='7 Hours (+1 hrs Lunch)';
            }elseif($ts->shift_type=="2"){
                $data.='8 Hours (+1 hrs Lunch)';
            }elseif($ts->shift_type=="3"){
                $data.='6 Hours';
            }

            $data.='</td>
                    <td>'.$ts->shift_stime.' - '.$ts->shift_etime.'</td>
                    <td>'.$employee.'</td>
                    </tr>';
        }
    }

    return $data;
}

public function shiftWiseEmployeeSummary()
{
    $id =   Auth::guard('admin')->user();
    $mainlink = $this->adminmainmenu();
    $sublink = $this->adminsubmenu();
    $Adminminlink = $this->adminlink();
    $adminsublink = $this->adminsublink();

    $designation=DB::table('tbl_designation')->get();
    $department=DB::table('tbl_department')->get();
    $joblocation=DB::table('tbl_joblocation')->get();
    
    $data=$this->getshiftWiseEmployeeSummaryRow('0','0','0','0','0');

    return view('Admin.settings.shift.shiftSummary',compact('id','mainlink','sublink','Adminminlink','adminsublink','designation','department','joblocation','data'));
}

public function shiftWiseEmployeeSummarySearch($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type)
{
    $id =   Auth::guard('admin')->user();
    $mainlink = $this->adminmainmenu();
    $sublink = $this->adminsubmenu();
    $Adminminlink = $this->adminlink();
    $adminsublink = $this->adminsublink();

    $designation=DB::table('tbl_designation')->get();
    $department=DB::table('tbl_department')->get();
    $joblocation=DB::table('tbl_joblocation')->get();

    $data=$this->getshiftWiseEmployeeSummaryRow($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type);

    return view('Admin.settings.shift.shiftSummary',compact('id','mainlink','sublink','Adminminlink','adminsublink','designation','department','joblocation','emp_depart_id','emp_sdepart_id','emp_desig_id','emp_jlid','emp_type','data'));
}

}