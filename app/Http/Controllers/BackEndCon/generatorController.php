<?php

namespace App\Http\Controllers\BackEndCon;

use Illuminate\Http\Request;
use App\Http\Controllers\BackEndCon\Controller;
use Auth;
use Session;
use DB;
use Image;
use Hash;
use URL;

class generatorController extends Controller
{

public function generator($option)
{
    if($option=="developer-mode-on"){
        $on=DB::table('tbl_setup')
            ->where('id','1')
            ->update([
                'developer_mode'=>'1',
            ]);
        if(isset($on)){
            return redirect('/');
        }else{
            echo '<h1 style="color:red">Not Worked!</h1>';
        }
    }

    if($option=="developer-mode-off"){
        $off=DB::table('tbl_setup')
            ->where('id','1')
            ->update([
                'developer_mode'=>'0',
            ]);
        if(isset($off)){
            return redirect('/');
        }else{
            echo '<h1 style="color:red">Not Worked!</h1>';
        }
    }

    $developer_mode=DB::table('tbl_setup')->where('id','1')->first(['developer_mode']);
    if($developer_mode->developer_mode=="1"){        
        
        if($option=="assign-employee"){
            $departments=\App\Department::get();
            foreach ($departments as $key => $department) {
                $lm=DB::table('tbl_sysuser')
                    ->join('tbl_employee','tbl_sysuser.suser_empid','=','tbl_employee.emp_id')
                    ->where('tbl_sysuser.suser_level',4)
                    ->where('tbl_employee.emp_depart_id',$department->depart_id)
                    ->first(['tbl_employee.emp_id']);
                if(isset($lm->emp_id)){
                    DB::table('tbl_employee')->where('emp_depart_id',$department->depart_id)->where('emp_id','!=',$lm->emp_id)->update([
                        'emp_seniorid' => $lm->emp_id,
                        'emp_authperson' => $lm->emp_id,
                    ]);

                    DB::table('tbl_employee')->where('emp_id',$lm->emp_id)->update([
                        'emp_seniorid' => 116,
                        'emp_authperson' => 116,
                    ]);

                    $search=DB::table('tbl_hod')->where(['hod_depart_id' => $department->depart_id])->first();
                    if(isset($search->hod_id)){
                        DB::table('tbl_hod')->where(['hod_depart_id' => $department->depart_id])
                        ->update([
                            'hod_empid' => $lm->emp_id,
                            'hod_superior' => $lm->emp_id,
                            'hod_note' => 'Example',
                        ]);
                    }else{
                        DB::table('tbl_hod')->insert([
                            'hod_depart_id' => $department->depart_id,
                            'hod_empid' => $lm->emp_id,
                            'hod_superior' => $lm->emp_id,
                            'hod_note' => 'Example',
                        ]);
                    }
                }

            }

        }

        if($option=="generate-machine-id"){
            $machineid=0;
            $employees=\App\Employee::get();
            foreach ($employees as $key => $employee) {
                $machineid++;
                $employee->emp_machineid=$machineid;
                $employee->save();
            }

        }

        if($option=="generate-attendance-data"){
            $employees=\App\Employee::get();
            foreach ($employees as $key => $employee) {
                $dateRange=$this->getDateRange('2019-06-01','2019-06-30');
                foreach ($dateRange as $datekey => $date) {
                    $intInTime=$date." 06:".rand(0,15).":00";
                    $in_time=date('Y-m-d H:i:s', strtotime("+".($datekey)." minute", strtotime($intInTime)));

                    $intOutTime=$date." 14:".rand(0,15).":00";
                    $out_time=date('Y-m-d H:i:s', strtotime("+".($datekey)." minute", strtotime($intOutTime)));

                    DB::table('tbl_checkinout')->insert([
                        'branch'=>1,
                        'device'=>5,
                        'emp_id'=>$employee->emp_machineid,
                        'check_time'=>$in_time
                    ]);

                    DB::table('tbl_checkinout')->insert([
                        'branch'=>1,
                        'device'=>6,
                        'emp_id'=>$employee->emp_machineid,
                        'check_time'=>$out_time
                    ]);
                }
            }
        }

        if($option=="generate-systemuser"){
            $employee=DB::table('tbl_employee')->get();
            foreach ($employee as $emp) {
            $emp_id=str_replace(' ','',$emp->emp_empid);
            if(strlen($emp_id)>0){
            $search=DB::table('tbl_sysuser')->where('email',$emp_id)->first();
            if(isset($search) && count($search)>0){

            }else{
                $explode=explode('-',$emp_id);
                if(isset($explode[1]) && $explode[1]!=""){
                    $email=$emp_id;
                    $password=$explode[1];
                    $insert=DB::table('tbl_sysuser')
                        ->insert([
                            'suser_empid'=>$emp->emp_id,
                            'email'=>$email,
                            'password'=>bcrypt($password),
                            'suser_level'=>'5',
                        ]);
                    if(isset($insert)){
                        echo $emp->emp_name.'</b>,<br></b>,<br>';
                    }else{
                        echo 'error'.'</b>,<br></b>,<br>';
                    }
                }else{
                    $email=$emp_id;
                    $password=substr($emp_id,6,10);
                    $insert=DB::table('tbl_sysuser')
                        ->insert([
                            'suser_empid'=>$emp->emp_id,
                            'email'=>$email,
                            'password'=>bcrypt($password),
                            'suser_level'=>'5',
                        ]);
                    if(isset($insert)){
                        echo $emp->emp_name.'</b>,<br></b>,<br>';
                    }else{
                        echo 'error'.'</b>,<br></b>,<br>';
                    }      
                }
            }
            }
            }
        }

        if($option=="generate-depart-desig"){
            $employee=DB::table('tbl_employee')->get();
            foreach ($employee as $emp) {
                if($emp->emp_id>770){
                    if(strlen($emp->emp_desig_id)>=3){
                        $tbl_designation=DB::table('tbl_designation')->where('desig_name','LIKE','%'.$emp->emp_desig_id.'%')->first();
                        if(isset($tbl_designation)){
                            $update=DB::table('tbl_employee')->where('emp_id',$emp->emp_id)->update([
                                'emp_desig_id'=>$tbl_designation->desig_id,
                            ]);
                        }
                    }

                    if(strlen($emp->emp_depart_id)>=3){
                        $tbl_department=DB::table('tbl_department')->where('depart_name','LIKE','%'.$emp->emp_depart_id.'%')->first();
                        if(isset($tbl_department)){
                            $update=DB::table('tbl_employee')->where('emp_id',$emp->emp_id)->update([
                                'emp_depart_id'=>$tbl_department->depart_id,
                            ]);
                        }
                    }
                }
            }
        }

        if($option=="generate-kqz_employee"){
            $employee=DB::table('tbl_employee')->where('emp_id','>','770')->orderBy('emp_id','asc')->get();
            for ($EmployeeID=0; $EmployeeID < count($employee); $EmployeeID++) { 
                $insert=DB::table('kqz_employee')
                    ->insert([
                        'EmployeeID'=>$EmployeeID+1086,
                        'BrchID'=>'1',
                        'BrchName'=>'1',
                        'EmployeeCode'=>$employee[$EmployeeID]->emp_machineid,
                        'EmployeeName'=>$employee[$EmployeeID]->emp_name,
                    ]);
                if($insert){
                    echo '<b style="color:green">'.$employee[$EmployeeID]->emp_name.' '.($employee[$EmployeeID]->emp_machineid).'-Success</b><br>';
                }else{
                    echo '<b style="color:red">'.$employee[$EmployeeID]->emp_name.' '.($employee[$EmployeeID]->emp_machineid).'-errro</b><br>';
                }
            }
        }

        if($option=="mising-in-kqz_employee"){
            $data='<table cellpadding="0" cellspacing="0" border="1" align="center" style="text-align:center"><caption>Missing in the kqz_employee list</caption><tr><th>SL</th><th>Employee ID</th><th>Employee Name</th><th>Machine ID</th></tr>';
            $employee=DB::table('tbl_employee')->get();
            $count=0;
            foreach ($employee as $emp) {
                $search=DB::table('kqz_employee')->where('EmployeeCode',$emp->emp_machineid)->first();
                if(isset($search) && count($search)>0){

                }else{
                    $data.='<tr><td>'.$count++.'</td><td>'.trim($emp->emp_empid).'</td><td>'.$emp->emp_name.'</td><td>'.$emp->emp_machineid.'</td></tr>';
                }
            }

            $data.='</table>';

            echo $data;
        }

        if($option=="developer-option-open"){
            $update=DB::table('tbl_sysuser')
                ->where('id','1771')
                ->update(['id'=>'1000']);
            if($update){
                return redirect('login');
            }else{
                echo '<h1 style="color:red">Not Worked!</h1>';
            }
        }

        if($option=="developer-option-close"){
            $update=DB::table('tbl_sysuser')
                ->where('id','1000')
                ->update(['id'=>'1771']);
            if($update){
                return redirect('login');
            }else{
                echo '<h1 style="color:red">Not Worked!</h1>';
            }
        }

        if(explode('&',$option)[0]=="loginUsingId"){
            $employee=DB::table('tbl_employee')
                ->join('tbl_sysuser','tbl_employee.emp_id','=','tbl_sysuser.suser_empid')
                ->where(DB::raw('trim(tbl_employee.emp_empid)'),explode('&',$option)[1])
                ->orWhere('tbl_sysuser.suser_empid',explode('&',$option)[1])
                ->first(['tbl_sysuser.id']);
            if(isset($employee)){
                if(Auth::guard('admin')->loginUsingId($employee->id)){
                    return redirect('/');
                }
            }else{
                echo '<strong style="color:red;text-align:center">System User Not Found For - '.explode('&',$option)[1].'</strong>';
            }
        }

        if(explode('&',$option)[0]=="deleteEmployee"){
            $employee=DB::table('tbl_employee')
                ->where(DB::raw('trim(tbl_employee.emp_empid)'),explode('&',$option)[1])
                ->first(['tbl_employee.emp_id']);
            if(isset($employee)){
                $tbl_employee=DB::table('tbl_employee')->where('emp_id',$employee->emp_id)->delete();
                $tbl_sysuser=DB::table('tbl_sysuser')->where('suser_empid',$employee->emp_id)->delete();
                if($tbl_employee){
                    echo '<strong style="color:green;text-align:center">Employee Information Deleted For - '.explode('&',$option)[1].'</strong><br>';
                }
                if($tbl_sysuser){
                    echo '<strong style="color:green;text-align:center">System User Deleted For - '.explode('&',$option)[1].'</strong>';
                }
            }else{
                echo '<strong style="color:red;text-align:center">System User Not Found For - '.explode('&',$option)[1].'</strong>';
            }
        }

        if($option=="lineManagerUpdate"){
            
            $oldcount=0;
            if(isset($oldLM)){
                foreach ($oldLM as $user) {
                    $check=DB::table('tbl_employee')->where('emp_seniorid',$user->suser_empid)->orWhere('emp_authperson',$user->suser_empid)->count();
                    
                    if($check>0){}else{
                        $update=DB::table('tbl_sysuser')
                            ->where('id',$user->id)
                            ->update([
                                'suser_level'=>'5'
                            ]);
                        if($update){
                            $oldcount++;
                        }
                    }
                }
            }

            $users=DB::table('tbl_sysuser')
                ->where('suser_level','!=','1')
                ->where('suser_level','!=','2')
                ->where('suser_level','!=','3')
                ->get();
            $count=0;
            if(isset($users)){
                foreach ($users as $user) {
                    $check=DB::table('tbl_employee')->where('emp_seniorid',$user->suser_empid)->orWhere('emp_authperson',$user->suser_empid)->count();
                    
                    if($check>0){
                        $update=DB::table('tbl_sysuser')
                            ->where('id',$user->id)
                            ->update([
                                'suser_level'=>'4'
                            ]);
                        if($update){
                            $count++;
                        }
                    }
                }
            }

            echo $count.' LM Found & Updated<br>'.$oldcount.' LM Removed';
        }

        if($option=="dailyShiftGenerate"){
            $tbl_employee=DB::table('tbl_employee')->get(['emp_id','emp_shiftid']);
            $empty_count=0;
            $notempty_count=0;
            $success_count=0;
            $error_count=0;
            foreach ($tbl_employee as $emp) {
                if($emp->emp_shiftid==""){
                    $empty_count++;
                    $search=DB::table('tbl_dailyshift')->where(['ds_empid'=>$emp->emp_id])->first();
                    if(!isset($search)){
                        $tbl_dailyshift=DB::table('tbl_dailyshift')
                            ->insert([
                                'ds_empid'=>$emp->emp_id,
                                'ds_shiftid'=>'1',
                                'ds_date'=>'2019-01-01',
                                'ds_createdat'=>date('Y-m-d H:i:s'),
                                'ds_createdby'=>'116',
                            ]);
                        if(isset($tbl_dailyshift)){
                            $success_count++;
                        }else{
                            $error_count++;
                        }
                    }
                }else{
                    $notempty_count++;
                    $search=DB::table('tbl_dailyshift')->where(['ds_empid'=>$emp->emp_id])->first();
                    if(!isset($search)){
                        $tbl_dailyshift=DB::table('tbl_dailyshift')
                            ->insert([
                                'ds_empid'=>$emp->emp_id,
                                'ds_shiftid'=>$emp->emp_shiftid,
                                'ds_date'=>'2018-01-01',
                                'ds_createdat'=>date('Y-m-d H:i:s'),
                                'ds_createdby'=>'116',
                            ]);
                        if(isset($tbl_dailyshift)){
                            $success_count++;
                        }else{
                            $error_count++;
                        }
                    }
                }
            }

            echo count($tbl_employee).' employee found!<br>'.$empty_count.' empty employee shift found!<br>'.$notempty_count.' employee has shift!<br>'.$success_count.' employee shift stored!<br>'.$error_count.' error found!';
        }

        if($option=="shift-insurance-data"){
            $employee=DB::table('tbl_employee')->get();
            $success_count=0;
            $error_count=0;
            foreach ($employee as $emp) {
                $insert=DB::table('tbl_insurance')->insert([
                    'emp_id'=>$emp->emp_id,
                    'spouse_name'=>$emp->emp_spname,
                    'child1_name'=>$emp->emp_child1,
                    'child2_name'=>$emp->emp_child2,
                ]);
                if(isset($insert)){
                    $success_count++;
                }else{
                    $error_count++;
                }
            }
            echo count($employee).' employee found!<br><br>'.$success_count.' employee Insurance data has been transferred!<br>'.$error_count.' error found!';
        }

        if($option=="generate-education-data"){
            $employee=DB::table('tbl_employee')->get();
            $success_count=0;
            $error_count=0;
            foreach ($employee as $emp) {
                $search=DB::table('tbl_educations')->where([
                    'emp_id'=>$emp->emp_id,
                ])->first();
                if(!isset($search->emp_id)){
                    $insert=DB::table('tbl_educations')->insert([
                        'emp_id'=>$emp->emp_id,
                        'updated_by' => Auth::guard('admin')->user()->suser_empid
                    ]);
                    if(isset($insert)){
                        $success_count++;
                    }else{
                        $error_count++;
                    }
                }
            }
            echo count($employee).' employee found!<br><br>'.$success_count.' employee Education data has been generated!<br>'.$error_count.' error found!';
        }

        if($option=="generate-salary-data"){
            $employee=DB::table('tbl_employee')->get();
            $success_count=0;
            $error_count=0;
            foreach ($employee as $emp) {
                $search=DB::table('tbl_salary')->where([
                    'emp_id'=>$emp->emp_id
                ])->first();
                if(!isset($search->emp_id)){
                    $insert=DB::table('tbl_salary')->insert([
                        'emp_id'=>$emp->emp_id
                    ]);
                    if(isset($insert)){
                        $success_count++;
                    }
                }else{
                    $error_count++;
                }
            }
            echo count($employee).' employee found!<br><br>'.$success_count.' employee Salary data has been transferred!<br>'.$error_count.' Already Exists!';
        }

        if($option=="trimmer"){
            $employee=DB::table('tbl_employee')->get();
            $success_count=0;
            $error_count=0;
            foreach ($employee as $emp) {
                $update=DB::table('tbl_employee')
                ->where('emp_id',$emp->emp_id)
                ->update([
                    'emp_empid'=>str_replace(' ','',$emp->emp_empid)
                ]);
                $email=DB::table('tbl_sysuser')
                ->where('suser_empid',$emp->emp_id)->first();
                if(isset($email)){
                    $update2=DB::table('tbl_sysuser')
                    ->where('suser_empid',$email->suser_empid)
                    ->update([
                        'email'=>str_replace(' ','',$email->email)
                    ]);
                }
                if(isset($update) && isset($update2)){
                    $success_count++;
                }else{
                    $error_count++;
                }
            }
            echo count($employee).' employee found!<br><br>'.$success_count.' employee ID has been Trimmed!<br>'.$error_count.' error found!';
        }

        if($option=="generate-dummy-payroll"){
            $employee=DB::table('tbl_employee')->orderBy('emp_id','asc')->get();
            $success_count=0;
            $error_count=0;
            foreach ($employee as $emp) {
                if($emp->emp_id>1){
                    $SalaryHead=DB::table('tbl_salaryhead')->where('head_id',6)->get();
                    foreach ($SalaryHead as $head) {
                        $amount=DB::table('tbl_payroll')->where([['emp_id','<',$emp->emp_id],'head_id' => $head->head_id])->orderBy('id','desc')->first();
                        $upload=DB::table('tbl_payroll')->insert([
                            'emp_id' => $emp->emp_id,
                            'head_date_of_execution' => '2019-01-01',
                            'head_id' => $head->head_id,
                            'amount' => 0,
                            'updated_at' => date('Y-m-d H:i:s'),
                            'updated_by' => '116',
                        ]);
                        if($upload){
                            $success_count++;
                        }else{
                            $error_count++;
                        }
                    }
                }
            }
            echo count($employee).' employee found!<br><br>'.$success_count.' head added!<br>'.$error_count.' error found!';
        }
    }else{
        echo '<h1 style="color:red">Developer Mode is off!</h1>';
    }

}

}