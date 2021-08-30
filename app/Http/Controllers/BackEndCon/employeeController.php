<?php

namespace App\Http\Controllers\BackEndCon;

use Illuminate\Http\Request;
use App\Http\Requests\EmployeeRequest;
use App\Http\Controllers\BackEndCon\Controller;
use App\Http\Controllers\BackEndCon\leaveInfoController;
use Auth;
use Session;
use DB;
use Image;
use Hash;
use URL;
use App\Employee;
use App\EmployeeTypes;
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
use App\SalaryHead;
use App\Payroll;
use App\TotalWorkingHours;

use App\ProvidentFundSetup;
use App\PayrollSummery;
use App\Loan;
use App\ProvidentFund;
use App\Educations;
use App\BankCash;
use App\Bonus;
use Illuminate\Support\Carbon;

class employeeController extends Controller
{

    public function employee_id_card()
    {
            $id =   Auth::guard('admin')->user();
            $mainlink = $this->adminmainmenu();
            $sublink = $this->adminsubmenu();
            $Adminminlink = $this->adminlink();
            $adminsublink = $this->adminsublink();
            
            $types=EmployeeTypes::orderBy('name','asc')->get();
            $designation=Designation::get();
            $department=Department::get();
            $joblocation=JobLocation::get();

            if($id->suser_level=="1" or $id->suser_level=="3"){
                $employeeDetails=$this->filterEmployee('0','0','0','0','0');
            }else if($id->suser_level=="2" or $id->suser_level=="4" or $id->suser_level=="5" or $id->suser_level=="6"){
                return redirect('employee-details/'.$id->suser_empid.'/view');
            }

            $data=$this->getEmployeeRow($employeeDetails,$id);

            // return $data; exit();

            return view('Admin.employeeDetails.employee_id_card',compact('id','mainlink','sublink','Adminminlink','adminsublink','types','designation','department','joblocation','data'));
    }
    

public function empployee_info_print($emp_id){
    // echo "string"; exit();
    $id =   Auth::guard('admin')->user();
    $mainlink = $this->adminmainmenu();
    $sublink = $this->adminsubmenu();
    $Adminminlink = $this->adminlink();
    $adminsublink = $this->adminsublink();
    $adminInfo = $this->adminInfo($id->suser_empid);

    $types=EmployeeTypes::orderBy('name','asc')->get();
    $designation=Designation::get();
    $department=Department::get();
    $joblocation=JobLocation::get();
    
    $ifPermitted=$this->ifPermitted($id,$emp_id);
    // if($ifPermitted=="0"){
    //     Session::flash('error',"Sorry! You're not permitted to Edit this Employee Information.");
    //     return redirect('employee-details');
    // }

    $employeeDetails=Employee::with(['department','subdepartment','designation','joblocation','insurance','salary'])->find($emp_id);
    $head_date_of_execution=Payroll::where(['emp_id'=>$emp_id,['head_date_of_execution','<=',date('Y-m-d')]])->first(['head_date_of_execution']);

    if($head_date_of_execution){
    $payroll=Payroll::with(['head'])->where(['emp_id'=>$emp_id,'head_date_of_execution'=>$head_date_of_execution->head_date_of_execution])->get();
    
    }
    $bank_cash=Payroll::where('emp_id',$emp_id)->get();

    // if(!$bank_cash)
    // {
    //     $bank_cash=0;
    // }

    // return $bank_cash; exit();
    

    // return $payroll[1]->head->head_percentage; exit();
    $subdepartment=DB::table('tbl_subdepartment')->where('sdepart_departid',$employeeDetails->emp_depart_id)->orderBy('sdepart_name','asc')->get();
    $country=Country::get();
    $senioremployee=DB::table('tbl_employee')->join('tbl_sysuser','tbl_sysuser.suser_empid','=','tbl_employee.emp_id')->where('tbl_sysuser.suser_level','4')->orderBy('emp_name','asc')->get();
    $authperson=DB::table('tbl_employee')->join('tbl_sysuser','tbl_sysuser.suser_empid','=','tbl_employee.emp_id')->where('tbl_sysuser.suser_level','4')->orderBy('emp_name','asc')->get();

    if($head_date_of_execution){
    return view('Admin.employeeDetails.employee_info_print',compact('id','mainlink','sublink','Adminminlink','adminsublink','employeeDetails','types','designation','joblocation','department','country','senioremployee','authperson','subdepartment','head_date_of_execution','payroll', 'bank_cash'));
    }
    else{
        return view('Admin.employeeDetails.employee_info_print',compact('id','mainlink','sublink','Adminminlink','adminsublink','employeeDetails','types','designation','joblocation','department','country','senioremployee','authperson','subdepartment','payroll', 'bank_cash'));
    }
}

public function filterEmployee($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type)
{
    $id =   Auth::guard('admin')->user();
    if($id->suser_level=="1" or $id->suser_level=="3"){
        return $employeeDetails=Employee::with(['type','department','subdepartment','designation','joblocation'])
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
            ->orderBy('tbl_employee.emp_empid','ASC')
            ->get();
    }
}

public function myTeamMember($emp_seniorid)
{
    return $employeeDetails=Employee::with(['type','department','subdepartment','designation','joblocation'])
        ->where('tbl_employee.emp_seniorid',$emp_seniorid)
        ->where('tbl_employee.emp_status','1')
        ->orderBy('tbl_employee.emp_name','ASC')
        ->get();
}


public function getEmployeeRow($employeeDetails,$id)
{
    $data='';
    if(isset($employeeDetails[0])){
    $c=0;
        foreach ($employeeDetails as $ed){
        $c++;
         $data.='<tr class="gradeX" id="tr-'.$ed->emp_id.'">
            <td style="text-align: center">
                <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                    <input type="checkbox" class="checkboxes" value="'.$ed->emp_id.'" name="emp_id[]" />
                    <span></span>
                </label>
            </td>
            <td>'.$c.'</td>
            <td>'.$ed->emp_empid.'</td>';
        if($id->suser_level=="1"){
            $data.='<td>'.$ed->emp_machineid.'</td>';
        }
        $data.='<td><a href="'.URL::to('employee-details').'/'.$ed->emp_id.'/view">'.$ed->emp_name.'</a></td>
            <td>'.((isset($ed->designation->desig_name)) ? $ed->designation->desig_name : '' ).'</td>
            <td>'.((isset($ed->department->depart_name)) ? $ed->department->depart_name : '' ).'</td>
            <td>'.((isset($ed->subdepartment->sdepart_name)) ? $ed->subdepartment->sdepart_name : '' ).'</td>
             <td>'.$ed->shift->shift_stime.' To '.$ed->shift->shift_etime.'</td>
            <td>'.$ed->type->name.'</td>
            <td>'.$this->getSeniorName($ed->emp_seniorid).'</td>
          </tr>';
        }
    }
    return $data;
}

public function employeeDetails(){
    $id =   Auth::guard('admin')->user();
    $mainlink = $this->adminmainmenu();
    $sublink = $this->adminsubmenu();
    $Adminminlink = $this->adminlink();
    $adminsublink = $this->adminsublink();
    
    $types=EmployeeTypes::orderBy('name','asc')->get();
    $designation=Designation::get();
    $department=Department::get();
    $joblocation=JobLocation::get();

    if($id->suser_level=="1" or $id->suser_level=="3"){
        $employeeDetails=$this->filterEmployee('0','0','0','0','0');
    }else if($id->suser_level=="2" or $id->suser_level=="4" or $id->suser_level=="5" or $id->suser_level=="6"){
        return redirect('employee-details/'.$id->suser_empid.'/view');
    }

    $data=$this->getEmployeeRow($employeeDetails,$id);

    return view('Admin.employeeDetails.index',compact('id','mainlink','sublink','Adminminlink','adminsublink','types','designation','department','joblocation','data'));
}

public function employee_list_print(){

    // echo "string"; exit();
    $flag=0;

    $id =   Auth::guard('admin')->user();
    $mainlink = $this->adminmainmenu();
    $sublink = $this->adminsubmenu();
    $Adminminlink = $this->adminlink();
    $adminsublink = $this->adminsublink();
    
    $types=EmployeeTypes::orderBy('name','asc')->get();
    $designation=Designation::get();
    $department=Department::get();
    $joblocation=JobLocation::get();

    if($id->suser_level=="1" or $id->suser_level=="3"){
        $employeeDetails=$this->filterEmployee('0','0','0','0','0');
    }else if($id->suser_level=="2" or $id->suser_level=="4" or $id->suser_level=="5" or $id->suser_level=="6"){
        return redirect('employee-details/'.$id->suser_empid.'/view');
    }

    $data=$this->getEmployeeRow($employeeDetails,$id);

    return view('Admin.employeeDetails.employee_print',compact('id','mainlink','sublink','Adminminlink','adminsublink','types','designation','department','joblocation','data', 'flag'));
}


public function employeeDetailsSearch($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type)
{
    $id =   Auth::guard('admin')->user();
    $mainlink = $this->adminmainmenu();
    $sublink = $this->adminsubmenu();
    $Adminminlink = $this->adminlink();
    $adminsublink = $this->adminsublink();
    $adminInfo = $this->adminInfo($id->suser_empid);

    $types=EmployeeTypes::orderBy('name','asc')->get();
    $designation=Designation::get();
    $department=Department::get();
    $joblocation=JobLocation::get();

    if($id->suser_level=="1" or $id->suser_level=="3"){
        $employeeDetails=$this->filterEmployee($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type);
    }else if($id->suser_level=="2" or $id->suser_level=="4" or $id->suser_level=="5" or $id->suser_level=="6"){
        return redirect('employee-details/'.$id->suser_empid.'/view');
    }
    
    $data=$this->getEmployeeRow($employeeDetails,$id);
    $search=1;

    return view('Admin.employeeDetails.index',compact('id','mainlink','sublink','Adminminlink','adminsublink','types','designation','department','joblocation','emp_depart_id','emp_sdepart_id','emp_desig_id','emp_jlid','emp_type','data', 'search'));
}


public function employeeidcardSearch($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type)
{
    // echo "string"; exit();
    $id =   Auth::guard('admin')->user();
    $mainlink = $this->adminmainmenu();
    $sublink = $this->adminsubmenu();
    $Adminminlink = $this->adminlink();
    $adminsublink = $this->adminsublink();
    $adminInfo = $this->adminInfo($id->suser_empid);

    $types=EmployeeTypes::orderBy('name','asc')->get();
    $designation=Designation::get();
    $department=Department::get();
    $joblocation=JobLocation::get();

    if($id->suser_level=="1" or $id->suser_level=="3"){
        $employeeDetails=$this->filterEmployee($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type);
    }else if($id->suser_level=="2" or $id->suser_level=="4" or $id->suser_level=="5" or $id->suser_level=="6"){
        return redirect('employee-details/'.$id->suser_empid.'/view');
    }
    
    $data=$this->getEmployeeRow($employeeDetails,$id);
    $search=1;

    // return $employeeDetails; exit();


    return view('Admin.employeeDetails.employee_id_card_print',compact('id','mainlink','sublink','Adminminlink','adminsublink','types','designation','department','joblocation','emp_depart_id','emp_sdepart_id','emp_desig_id','emp_jlid','emp_type','data', 'search', 'employeeDetails'));
}

public function employeeDetailsSearch_print($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type)
{
    if($emp_jlid>0){
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
    $designation=Designation::get();
    $department=Department::get();
    $joblocation=JobLocation::get();

    if($id->suser_level=="1" or $id->suser_level=="3"){
        $employeeDetails=$this->filterEmployee($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type);
    }else if($id->suser_level=="2" or $id->suser_level=="4" or $id->suser_level=="5" or $id->suser_level=="6"){
        return redirect('employee-details/'.$id->suser_empid.'/view');
    }

    $data=$this->getEmployeeRow($employeeDetails,$id);
    $search=1;

    return view('Admin.employeeDetails.employee_print',compact('id','mainlink','sublink','Adminminlink','adminsublink','types','designation','department','joblocation','emp_depart_id','emp_sdepart_id','emp_desig_id','emp_jlid','emp_type','data', 'search', 'flag', 'empl_location'));
}
public function myTeam(){
    $id =   Auth::guard('admin')->user();
    $mainlink = $this->adminmainmenu();
    $sublink = $this->adminsubmenu();
    $Adminminlink = $this->adminlink();
    $adminsublink = $this->adminsublink();
    
    $employeeDetails=$this->myTeamMember($id->suser_empid);

    $data=$this->getEmployeeRow($employeeDetails,$id);
    $designation=Designation::get();
    $department=Department::get();
    $joblocation=JobLocation::get();

    return view('Admin.employeeDetails.myTeam',compact('id','mainlink','sublink','Adminminlink','adminsublink','designation','department','joblocation','data'));
}

public function employeeDetailsCheckView(Request $request)
{
    if(count($request->emp_id)>0){
        if(count($request->emp_id)>1){
            return 'max';
        }else{
            return $request->emp_id[0];
        }
    }else{
        return 'null';
    }
}

public function ifPermitted($id,$emp_id)
{
    if($id->suser_level=="1" or $id->suser_level=="3"){
        $check=Employee::find($emp_id);
        if(isset($check->emp_id)){
            return '1';
        }else{
            return '0';
        }
    }else if($id->suser_level=="4"){
        $check=Employee::where('tbl_employee.emp_seniorid',$id->suser_empid)
                ->orWhere(['tbl_employee.emp_authperson'=>$id->suser_empid,'tbl_employee.emp_id'=>$emp_id])
                ->first();
        if(isset($check->emp_id)){
            return '1';
        }else{
            return '0';
        }
    }else if($id->suser_level=="2" or $id->suser_level=="5" or $id->suser_level=="6"){
        if($emp_id==$id->suser_empid){
            return '1';
        }else{
            return '0';
        }
    }
}

public function employeeDetailsView($emp_id){
    $id =   Auth::guard('admin')->user();
    $mainlink = $this->adminmainmenu();
    $sublink = $this->adminsubmenu();
    $Adminminlink = $this->adminlink();
    $adminsublink = $this->adminsublink();
    $start_date=date('Y-m-d',strtotime('-7 days'));
    $end_date=date('Y-m-d');
    $designation=Designation::get();
    $department=Department::get();
    $joblocation=JobLocation::get();
    
    $ifPermitted=$this->ifPermitted($id,$emp_id);
    if($ifPermitted=="0"){
        Session::flash('error',"Sorry! You're not permitted to view this Employee Information.");
        return redirect('employee-details');
    }

    $employeeDetails=Employee::with(['department','subdepartment','designation','joblocation','insurance','salary','documents','education'])->find($emp_id);
    $head_date_of_execution=Payroll::where(['emp_id'=>$emp_id,['head_date_of_execution','<=',date('Y-m-d')]])->first(['head_date_of_execution']);

    if($head_date_of_execution){
    $payroll=Payroll::with(['head'])->where(['emp_id'=>$emp_id,'head_date_of_execution'=>$head_date_of_execution->head_date_of_execution])->get();
    }

    $attendances=TotalWorkingHours::with(['employee','shift'])
        ->whereBetween('date',[$start_date,$end_date])
        ->where('emp_id',$emp_id)
        ->get();

    $leaveInfoController=new leaveInfoController();
    $leave_type=DB::table('tbl_leavetype')->get();
    $leave_status='';
    if(isset($leave_type[0])){
        foreach ($leave_type as $lt) {
            if($lt->li_id=="2" or $lt->li_id=="5" or $lt->li_id=="6"){
                $leave_status.='<tr>
                        <td>'.$lt->li_name.'</td>
                        <td>-</td>
                        <td>'.$leaveInfoController->currentLeaveTaken($emp_id,$lt->li_id).'</td>
                        <td>-</td>
                      </tr>';
            }else{
                $leave_status.='<tr>
                        <td>'.$lt->li_name.'</td>
                        <td>'.$lt->li_qoutaday.'</td>
                        <td>'.$leaveInfoController->currentLeaveTaken($emp_id,$lt->li_id).'</td>
                        <td>'.$leaveInfoController->currentLeaveRemain($emp_id,$lt->li_id,$lt->li_qoutaday).'</td>
                      </tr>';
            }
        }
    }else{
        $leave_status.='<tr>td>Nothing Found!</td></tr>';
    }

    $attendance=false;

    $ProvidentSetup=false;
    $pfs=ProvidentFundSetup::find('1');
    if(isset($pfs->provident_fund) && $pfs->provident_fund=="1"){
        $ProvidentSetup=true;
    }

    $PayrollSummery=PayrollSummery::with(['employee','employee.designation','heads','heads.head','extends','extends.head'])
        ->where([
            ['payroll_date_from','>=',date('Y').'-01-01'],
            ['payroll_date_to','<=',date('Y').'-12-31'],
        ])
        ->where([
            'type'=>30
        ])
        ->where('emp_id',$emp_id)
        ->get();

    $heads=array();
    $gross_amount=array();
    $extendsqty=array();
    $extendsamount=array();

    if(isset($PayrollSummery[0])){
        foreach ($PayrollSummery as $key => $payroll){
            if(!isset($gross_amount[$payroll->emp_id])){
                $gross_amount[$payroll->emp_id]=0;
            }
            foreach ($payroll->heads as $key => $value) {
                if(!isset($heads[$value->head->head_id])){
                    $heads[$value->head->head_id]=0;
                }
                
                if($value->head->head_id!="5"){
                    $heads[$value->head->head_id]+=$value->head_amount;
                    $gross_amount[$payroll->emp_id]+=$value->head_amount;
                }else{
                    $heads[$value->head->head_id]+=($value->head_amount*$payroll->pa);
                    $gross_amount[$payroll->emp_id]+=($value->head_amount*$payroll->pa);
                }
            }

            foreach ($payroll->extends as $key => $value) {
                if(!isset($extendsqty[$value->head->head_id])){
                    $extendsqty[$value->head->head_id]=0;
                }
                if(!isset($extendsamount[$value->head->head_id])){
                    $extendsamount[$value->head->head_id]=0;
                }
                $extendsqty[$value->head->head_id]+=$value->head_quantity;
                $extendsamount[$value->head->head_id]+=$value->head_amount;
            }
        }
    }

    $loans=Loan::with(['employee','loan_month'])
                ->where('emp_id',$emp_id)
                ->get();

    $funds = ProvidentFund::where('emp_id',$emp_id)->where('year',date('Y'))->get();
    $bonus = Bonus::where('emp_id',$emp_id)->get();
    $fundsYears = ProvidentFund::where('emp_id',$emp_id)->groupBY('year')->get();

    // return $bonus; exit();
    $country=Country::get();

    if($head_date_of_execution){
    return view('Admin.employeeDetails.view',compact('id','mainlink','sublink','Adminminlink','adminsublink','employeeDetails','designation','joblocation','department','country','emp_id','head_date_of_execution','payroll','attendance','attendances','leave_status','start_date','end_date','ProvidentSetup','PayrollSummery','heads','gross_amount','extendsqty','extendsamount','loans','funds','fundsYears', 'bonus'));
    }
    else{
        return view('Admin.employeeDetails.view',compact('id','mainlink','sublink','Adminminlink','adminsublink','employeeDetails','designation','joblocation','department','country','emp_id','payroll','attendance','attendances','leave_status','start_date','end_date','ProvidentSetup','PayrollSummery','heads','gross_amount','extendsqty','extendsamount','loans','funds','fundsYears', 'bonus'));
    }
}


public function employeeDetailsViewAttendance($emp_id,$data){
    $start_date=explode('&',$data)[0];
    $end_date=explode('&',$data)[1];
    $id =   Auth::guard('admin')->user();
    $mainlink = $this->adminmainmenu();
    $sublink = $this->adminsubmenu();
    $Adminminlink = $this->adminlink();
    $adminsublink = $this->adminsublink();

    $designation=Designation::get();
    $department=Department::get();
    $joblocation=JobLocation::get();
    
    $ifPermitted=$this->ifPermitted($id,$emp_id);
    if($ifPermitted=="0"){
        Session::flash('error',"Sorry! You're not permitted to view this Employee Information.");
        return redirect('employee-details');
    }

    $employeeDetails=Employee::with(['department','subdepartment','designation','joblocation','insurance','salary','documents'])->find($emp_id);
    $head_date_of_execution=Payroll::where(['emp_id'=>$emp_id,['head_date_of_execution','<=',date('Y-m-d')]])->first(['head_date_of_execution']);
    $payroll=Payroll::with(['head'])->where(['emp_id'=>$emp_id,'head_date_of_execution'=>$head_date_of_execution->head_date_of_execution])->get();

    $attendances=TotalWorkingHours::with(['employee','shift'])
        ->whereBetween('date',[$start_date,$end_date])
        ->where('emp_id',$emp_id)
        ->get();

    $leaveInfoController=new leaveInfoController();
    $leave_type=DB::table('tbl_leavetype')->get();
    $leave_status='';
    if(isset($leave_type[0])){
        foreach ($leave_type as $lt) {
            if($lt->li_id=="2" or $lt->li_id=="5" or $lt->li_id=="6"){
                $leave_status.='<tr>
                        <td>'.$lt->li_name.'</td>
                        <td>-</td>
                        <td>'.$leaveInfoController->currentLeaveTaken($emp_id,$lt->li_id).'</td>
                        <td>-</td>
                      </tr>';
            }else{
                $leave_status.='<tr>
                        <td>'.$lt->li_name.'</td>
                        <td>'.$lt->li_qoutaday.'</td>
                        <td>'.$leaveInfoController->currentLeaveTaken($emp_id,$lt->li_id).'</td>
                        <td>'.$leaveInfoController->currentLeaveRemain($emp_id,$lt->li_id,$lt->li_qoutaday).'</td>
                      </tr>';
            }
        }
    }else{
        $leave_status.='<tr>td>Nothing Found!</td></tr>';
    }

    $attendance=true;

    return view('Admin.employeeDetails.view',compact('id','mainlink','sublink','Adminminlink','adminsublink','employeeDetails','designation','joblocation','department','country','emp_id','head_date_of_execution','payroll','attendance','attendances','leave_status','start_date','end_date'));
}

public function employeeDetailsCheckEdit(Request $request)
{
    if(count($request->emp_id)>0){
        if(count($request->emp_id)>1){
            return 'max';
        }else{
            return $request->emp_id[0];
        }
    }else{
        return 'null';
    }
}

public function employeeDetailsEdit($emp_id){
    $id =   Auth::guard('admin')->user();
    $mainlink = $this->adminmainmenu();
    $sublink = $this->adminsubmenu();
    $Adminminlink = $this->adminlink();
    $adminsublink = $this->adminsublink();
    $adminInfo = $this->adminInfo($id->suser_empid);

    $types=EmployeeTypes::orderBy('name','asc')->get();
    $designation=Designation::get();
    $department=Department::get();
    $joblocation=JobLocation::get();
    
    $ifPermitted=$this->ifPermitted($id,$emp_id);
    if($ifPermitted=="0"){
        Session::flash('error',"Sorry! You're not permitted to Edit this Employee Information.");
        return redirect('employee-details');
    }

    $employeeDetails=Employee::with(['department','subdepartment','designation','joblocation','insurance','salary'])->find($emp_id);
    $head_date_of_execution=Payroll::where(['emp_id'=>$emp_id,['head_date_of_execution','<=',date('Y-m-d')]])->first(['head_date_of_execution']);

    if($head_date_of_execution){
    $payroll=Payroll::with(['head'])
    ->join('tbl_salaryhead', 'tbl_salaryhead.head_id', '=', 'tbl_payroll.head_id')
    ->where('tbl_salaryhead.other_head_type', 0)
    ->where('tbl_salaryhead.head_type', 1)
    ->where(['tbl_payroll.emp_id'=>$emp_id,'tbl_payroll.head_date_of_execution'=>$head_date_of_execution->head_date_of_execution])->get();
    
    }
    $bank_cash=Payroll::where('emp_id',$emp_id)->get();

    // if(!$bank_cash)
    // {
    //     $bank_cash=0;
    // }

    // return $bank_cash; exit();
    

    // return $payroll[1]->head->head_percentage; exit();
    $subdepartment=DB::table('tbl_subdepartment')->where('sdepart_departid',$employeeDetails->emp_depart_id)->orderBy('sdepart_name','asc')->get();
    $country=Country::get();
    $senioremployee=DB::table('tbl_employee')->join('tbl_sysuser','tbl_sysuser.suser_empid','=','tbl_employee.emp_id')->where('tbl_sysuser.suser_level','4')->orderBy('emp_name','asc')->get();
    $authperson=DB::table('tbl_employee')->join('tbl_sysuser','tbl_sysuser.suser_empid','=','tbl_employee.emp_id')->where('tbl_sysuser.suser_level','4')->orderBy('emp_name','asc')->get();

    if($head_date_of_execution){
    return view('Admin.employeeDetails.edit',compact('id','mainlink','sublink','Adminminlink','adminsublink','employeeDetails','types','designation','joblocation','department','country','senioremployee','authperson','subdepartment','head_date_of_execution','payroll', 'bank_cash'));
    }
    else{
        return view('Admin.employeeDetails.edit',compact('id','mainlink','sublink','Adminminlink','adminsublink','employeeDetails','types','designation','joblocation','department','country','senioremployee','authperson','subdepartment','payroll', 'bank_cash'));
    }
}

public function employeeDetailsReject(Request $request)
{
    if(count($request->emp_id)>0){
        for ($i=0; $i < count($request->emp_id) ; $i++) {
            $employee=Employee::find($request->emp_id[$i]);
            $reject_employee=$employee->update(['emp_status'=>'0',
                'status_condition'=>'3',
                'emp_rejected_at'=>date('Y-m-d H:i:s')]);
            if($reject_employee){
            $this->empHistory($request->emp_id[$i],'Employee Has Been Rejected at '.date('Y-m-d H:i:s'));
            $update_sysuser=Admin::where('suser_empid',$request->emp_id[$i])->update(['suser_status'=>'0',
                'ststus_condition'=>'3']);
            }
        }
        if($reject_employee){
            Session::flash('success','Employee(s) Rejected Successfully.');
            return '1';
        }else{
            return '0';
        }
    }else{
        return 'null';
    }
}
public function employeeDetailsResign(Request $request)
{
    if(count($request->emp_id)>0){
        for ($i=0; $i < count($request->emp_id) ; $i++) {
            $employee=Employee::find($request->emp_id[$i]);
            $reject_employee=$employee->update(['emp_status'=>'0',
                'status_condition'=>'1',
                'inactive_datetime'=> Carbon::now()->format('Y-m-d')]);
            if($reject_employee){
            $this->empHistory($request->emp_id[$i],'Employee Has Been Resigned at '.Carbon::now()->format('Y-m-d'));
            $update_sysuser=Admin::where('suser_empid',$request->emp_id[$i])->update(['suser_status'=>'0',
                'status_condition'=>'1','inactive_datetime'=> Carbon::now()->format('Y-m-d')]);
            }
        }
        if($reject_employee){
            Session::flash('success','Employee(s) Resigned Successfully.');
            return '1';
        }else{
            return '0';
        }
    }else{
        return 'null';
    }
}
public function employeeDetailsSuspend(Request $request)
{
    if(count($request->emp_id)>0){
        for ($i=0; $i < count($request->emp_id) ; $i++) {
            $employee=Employee::find($request->emp_id[$i]);
            $reject_employee=$employee->update(['emp_status'=>'0',
                'status_condition'=>'2',
                'inactive_datetime'=> Carbon::now()->format('Y-m-d'),
                'emp_rejected_at'=>date('Y-m-d H:i:s')]);
            if($reject_employee){
            $this->empHistory($request->emp_id[$i],'Employee Has Been Suspended at '.date('Y-m-d H:i:s'));
            $update_sysuser=Admin::where('suser_empid',$request->emp_id[$i])->update(['suser_status'=>'0',
                'status_condition'=>'2', 'inactive_datetime'=> Carbon::now()->format('Y-m-d')]);
            }
        }
        if($reject_employee){
            Session::flash('success','Employee(s)Suspended Successfully.');
            return '1';
        }else{
            return '0';
        }
    }else{
        return 'null';
    }
}


public function employeeCreate(){
    $id =   Auth::guard('admin')->user();
    $mainlink = $this->adminmainmenu();
    $sublink = $this->adminsubmenu();
    $Adminminlink = $this->adminlink();
    $adminsublink = $this->adminsublink();
        
    $types=EmployeeTypes::orderBy('name','asc')->get();
    $designation=Designation::orderBy('desig_name','asc')->get();
    $department=Department::orderBy('depart_name','asc')->get();
    $joblocation=JobLocation::orderBy('jl_name','asc')->get();

    $country=Country::get();
    $SalaryHead=SalaryHead::get();
    return view('Admin.employeeDetails.create',compact('id','mainlink','sublink','Adminminlink','adminsublink','types','designation','department','joblocation','country','SalaryHead'));
}

public function getSubDepartment($emp_depart_id){
    $subdepartment=SubDepartment::where('sdepart_departid',$emp_depart_id)->get();
    if(isset($subdepartment[0])){
        foreach ($subdepartment as $sdepart) {
            echo '<option value="'.$sdepart->sdepart_id.'">'.$sdepart->sdepart_name.'</option>';
        }
    }else{
        return '<option value="0">Select Sub-Department</option>';
    }
}

public function getFilterSubDepartment($emp_depart_id){
    echo '<option value="0">Select</option>';
    $subdepartment=SubDepartment::where('sdepart_departid',$emp_depart_id)->get();
    if(isset($subdepartment[0])){
        foreach ($subdepartment as $sdepart) {
            echo '<option value="'.$sdepart->sdepart_id.'">'.$sdepart->sdepart_name.'</option>';
        }
    }
}

static public function getSeniorEmployee(){
    $senioremployee=Admin::with(['employee'])
        ->where('tbl_sysuser.suser_level','4')
        ->get();
    if(isset($senioremployee[0])){
        foreach ($senioremployee as $senior) {
            echo '<option value="'.$senior->employee->emp_id.'">'.$senior->employee->emp_name.' - '.$senior->employee->emp_empid.'</option>';
        }
    }else{
        return '';
    }
}

public function getAuthPerson(){
    $AuthPerson=Admin::with(['employee'])
        ->where('tbl_sysuser.suser_level','4')
        ->get();
    if(isset($AuthPerson[0])){
        foreach ($AuthPerson as $auth) {
            echo '<option value="'.$auth->employee->emp_id.'">'.$auth->employee->emp_name.' - '.$auth->employee->emp_empid.'</option>';
        }
    }else{
        return '';
    }
}

public function getSeniorEmployeeName($emp_seniorid){
    $senioremployee=Employee::where('emp_id',$emp_seniorid)->get();
    if($senioremployee){
        foreach ($senioremployee as $senior) {
            echo '<option value="'.$senior->emp_id.'">'.$senior->emp_name.'</option>';
        }
    }else{
        return '';
    }
}

public function getAuthPersonName($emp_authperson){
    $authperson=Employee::where('emp_id',$emp_authperson)->get();
    if($authperson){
        foreach ($authperson as $ap) {
            echo '<option value="'.$ap->emp_id.'">'.$ap->emp_name.'</option>';
        }
    }else{
        return '';
    }
}

public function getShift($emp_workhr){
    $shift=Shift::where('shift_type',$emp_workhr)->orderBy('shift_stime','asc')->get();
    if($shift){
        foreach ($shift as $sh) {
            echo '<option value="'.$sh->shift_id.'">'.$sh->shift_stime.' - '.$sh->shift_etime.'</option>';
        }
    }else{
        return '';
    }
}

public function getShiftForEdit($emp_workhr,$emp_shiftid){
    $shift=Shift::where('shift_type',$emp_workhr)->orderBy('shift_stime','asc')->get();
    if($shift){
        foreach ($shift as $sh) {
            if($sh->shift_id==$emp_shiftid){
                echo '<option value="'.$sh->shift_id.'" selected="selected">'.$sh->shift_stime.' - '.$sh->shift_etime.'</option>';
            }else{
                echo '<option value="'.$sh->shift_id.'">'.$sh->shift_stime.' - '.$sh->shift_etime.'</option>';
            }
        }
    }else{
        return '';
    }
}

public function employeeCreateSubmit(Request $request,Employee $employee)
{
    // return $request; exit();
    $this->validate($request,[
        'emp_empid'=>'required',
        'emp_name'=>'required',
        'emp_type'=>'required',
        'emp_phone'=>'required',
        //'emp_email'=>'required',
        //'emp_sfid'=>'required',
        //'emp_dob'=>'required',
        'emp_country'=>'required',
        'emp_desig_id'=>'required',
        'emp_depart_id'=>'required',
        // 'emp_sdepart_id'=>'required',
        //'emp_blgrp'=>'required',
        //'emp_education'=>'required',
        // 'emp_wknd'=>'required',
        // 'emp_vehicle'=>'required',
        'emp_workhr'=>'required',
        'emp_otent'=>'required',
        'emp_shiftid'=>'required',
        'emp_jlid'=>'required',
        //'emp_joindate'=>'required',
        //'emp_confirmdate'=>'required',
        'emp_machineid'=>'required',
        
    ]);
    $id =   Auth::guard('admin')->user();

    $employee->fill($request->all());
    $employee->emp_empid=str_replace(' ','',$request->emp_empid);
    $employee->emp_created_at=date('Y-m-d h:i:s');
    $employee->save();

    if($employee){
        $this->empInsurance($employee->emp_id,$request);
        $this->empEducation($employee->emp_id);
        $this->empSalary($employee->emp_id,$request);
        $this->empPayroll($employee->emp_id,$request);
        $this->empHistory($employee->emp_id,'Employee Information Created');
        
        if($request->file('emp_img') != ""){
            $fileExtension=$request->file('emp_img')->getClientOriginalExtension(); 
            $fileName =  $employee->emp_id.'.'.$fileExtension;
            $upload=Image::make( $request->file('emp_img')->getRealPath() )->save( base_path().'/public/EmployeeImage/'.$fileName);
            if($upload){
                $updateimageextension=Employee::find($employee->emp_id)
                    ->update([
                        'emp_imgext'=>$fileExtension
                    ]);
                $this->empHistory($employee->emp_id,'Employee Image Uploaded');
            }
        }

        $emp_id=$employee->emp_empid;
        if(strlen($emp_id)>0){
        $search=Admin::where(DB::raw("REPLACE(`email`,' ','')"),$emp_id)->first();
            if(isset($search->email)){

            }else{
                $explode=explode('-',$emp_id);
                if(isset($explode[1]) && $explode[1]!=""){
                    $email=$emp_id;
                    $password=$explode[1];
                    $insert=Admin::insert([
                            'suser_empid'=>$employee->emp_id,
                            'email'=>$email,
                            'password'=>bcrypt($password),
                            'suser_level'=>'5',
                        ]);
                    if($insert){
                        $this->empHistory($employee->emp_id,'System User Account Has Been Created');
                    }
                }else{
                    $email=$emp_id;
                    $password=substr($emp_id,6,10);
                    $insert=Admin::insert([
                            'suser_empid'=>$employee->emp_id,
                            'email'=>$email,
                            'password'=>bcrypt($password),
                            'suser_level'=>'5',
                        ]);
                    if($insert){
                        $this->empHistory($employee->emp_id,'System User Account Has Been Created');
                    }
                }
            }
        }

        $UpdateShift=DailyShift::insert([
                'ds_empid'=>$employee->emp_id,
                'ds_shiftid'=>$request->emp_shiftid,
                'ds_date'=>'2018-01-01',
                'ds_createdat'=>date('Y-m-d H:i:s'),
                'ds_createdby'=>$id->suser_empid,
            ]);
        if($UpdateShift){
            $this->empHistory($employee->emp_id,'Shift Created To : <b>'.$this->getShiftInfo($request->emp_shiftid).'</b>');
        }

        // emp_id added in education table as foreign key
        $em = Employee::orderBy('emp_id', 'DESC')->first();
        $ed = new Educations;
        $ed->emp_id = $em->emp_id; 
        $ed->save();

    
        Session::flash('success','Employee Information Saved Successfully.');
        return redirect('add-new-employee');
    }else{
        Session::flash('error','Sorry!! Something Went Wrong!! Try Again.');
        return redirect('add-new-employee');
    }
}

public function empInsurance($emp_id,$request)
{
    $Insurance=Insurance::find($emp_id);
    if(isset($Insurance)){
        $Insurance->fill($request->all());
        $Insurance->last_updated_at=date('Y-m-d H:i:s');
        $Insurance->save();
    }else{
        $Insurance=new Insurance();
        $Insurance->fill($request->all());
        $Insurance->emp_id=$emp_id;
        $Insurance->last_updated_at=date('Y-m-d H:i:s');
        $Insurance->save();
    }
}

public function empEducation($emp_id)
{
    $Insurance=Insurance::find($emp_id);
    if(!isset($Insurance)){
        $Insurance=new Insurance();
        $Insurance->emp_id=$emp_id;
        $Insurance->updated_by=Auth::guard('admin')->user()->suser_empid;
        $Insurance->save();
    }
}

public function empSalary($emp_id,$request)
{
    $id =   Auth::guard('admin')->user();
    $Salary=Salary::find($emp_id);
    if(isset($Salary)){
        $Salary->fill($request->all());
        $Salary->last_updated_at=date('Y-m-d H:i:s');
        $Salary->last_updated_by=$id->suser_empid;
        $Salary->save();
    }else{
        $Salary=new Salary();
        $Salary->fill($request->all());
        $Salary->emp_id=$emp_id;
        $Salary->last_updated_by=$id->suser_empid;
        $Salary->save();
    }
}

public function empEditPayroll($emp_id,$request)
{
    $id =   Auth::guard('admin')->user();
    $SalaryHead=SalaryHead::get();
    foreach ($SalaryHead as $head) {
        $amount=0;
        if(isset($request->salary[$head->head_id])){
            $amount=$request->salary[$head->head_id];
        }

        $bank_cash=0;
        if(isset($request->bank_cash[$head->head_id])){
            $bank_cash=$request->bank_cash[$head->head_id];
        }

        $search=Payroll::where([
            'head_date_of_execution'=>$request->head_date_of_execution,
            'emp_id'=>$emp_id,
            'head_id'=>$head->head_id])
        ->first();

        $search_bankcash=BankCash::where([
            // 'head_date_of_execution'=>$request->head_date_of_execution,
            'emp_id'=>$emp_id,
            'head_id'=>$head->head_id])
        ->first();

        if(isset($search->emp_id)){
            Payroll::where([
                'head_date_of_execution'=>$request->head_date_of_execution,
                'emp_id'=>$emp_id,
                'head_id'=>$head->head_id
            ])
            ->update([
                'amount'=>$amount,
                'bankcash_status'=>$bank_cash,
            ]);
        }else{
            Payroll::insert([
                'emp_id'=>$emp_id,
                'head_date_of_execution'=>$request->head_date_of_execution,
                'head_id'=>$head->head_id,
                'amount'=>$amount,
                'bankcash_status'=>$bank_cash,
            ]);
        }

        if(isset($search_bankcash->emp_id)){
            BankCash::where([
                // 'head_date_of_execution'=>$request->head_date_of_execution,
                'emp_id'=>$emp_id,
                'head_id'=>$head->head_id
            ])
            ->update([
                'status'=>$bank_cash,
            ]);
        }else{
            BankCash::insert([
                'emp_id'=>$emp_id,
                // 'head_date_of_execution'=>$request->head_date_of_execution,
                'head_id'=>$head->head_id,
                'status'=>$bank_cash,
            ]);
        }
    }
}

public function empPayroll($emp_id,$request)
{
    $id =   Auth::guard('admin')->user();
    $SalaryHead=SalaryHead::get();
    
    $salary=array();
    $salary[1]=0;
    $salary[2]=0;
    $salary[3]=0;
    $salary[4]=0;

    $gross=$request->gross_salary;
    if($gross=="" || $gross<0){
        $gross=0;
    }
    if($gross>0){
        $salary[1]=$gross*(40/100);
        $salary[2]=$gross*(40/100);
        $salary[3]=$gross*(10/100);
        $salary[4]=$gross*(10/100);
    }

    $SalaryHead=SalaryHead::get();
    foreach ($SalaryHead as $key => $head) {
        $amount=0;
        if(isset($salary[$head->head_id])){
            $amount=$salary[$head->head_id];
        }
        Payroll::insert([
            'emp_id' => $emp_id,
            'head_date_of_execution' => $request->head_date_of_execution,
            'head_id' => $head->head_id,
            'amount' => $this->decimal($amount),
        ]);
    }
}


public function filedsArray($key=false)
{
    $array=array(
        'emp_empid' => 'Employee ID',
        'emp_name' => 'Employee Name',
        'emp_type' => 'Employee Type',
        'emp_phone' => 'Phone No',
        'emp_sfid' => 'SF ID',
        'emp_dob' => 'Date of Birth',
        'emp_country' => 'Country',
        'emp_desig_id' => 'Designation',
        'emp_depart_id' => 'Department',
        'emp_sdepart_id' => 'Sub-Department',
        'emp_seniorid' => 'Senior Person',
        'emp_authperson' => 'Authorized Person',
        'emp_blgrp' => 'Blood Group',
        'emp_education' => 'Education',
        'emp_wknd' => 'Weekend',
        'emp_vehicle' => 'Vehicle Allowance',
        'emp_workhr' => 'Working Hour',
        'emp_otent' => 'OT Entitlement',
        'emp_shiftid' => 'Shift',
        'emp_email' => 'Email',
        'emp_cicoid' => 'CICO ID',
        'emp_jlid' => 'Job Location',
        'emp_joindate' => 'joinning Date',
        'emp_confirmdate' => 'Confirmation Date',
        'emp_workhistoryfrom' => 'History From',
        'emp_emjcontact' => 'Emergency Contact',
        'emp_crntaddress' => 'Current Address',
        'emp_prmntaddress' => 'Permanent Address',
        'emp_imgext' => 'Image',
        'emp_machineid' => 'Machine ID',
        'emp_nid' => 'NID',
        'emp_status' => 'Status',
        'self_member_id' => 'Self Member ID',
        'effective_date' => ' Insurance Effective Date',
        'spouse_member_id' => 'Spouse Member ID',
        'spouse_name' => 'Spouse Name',
        'spouse_dob' => 'Spouse Date of Birth',
        'spouse_start_date' => 'Spouse Insurance Start from',
        'spouse_end_date' => 'Spouse Insurance upto',
        'child1_member_id' => 'Child-1 Member ID',
        'child1_name' => 'Child-1 Name',
        'child1_dob' => 'Child-1 Date of Birth',
        'child1_start_date' => 'Child-1 Insurance Start from',
        'child1_end_date' => 'Child-1 Insurance upto',
        'child2_member_id' => 'Child-2 Name',
        'child2_name' => 'Child-2 Date of Birth',
        'child2_dob' => 'Child-2 Date of Birth',
        'child2_start_date' => 'Child-2 Insurance Start from',
        'child2_end_date' => 'Child-2 Insurance upto',
        'tin_no' => 'TIN No',
        'grade' => 'Grade',
        'bank_account' => 'Bank Account',
        'bu_code' => 'BU Code',
        'category' => 'Category',
        'ten_steps' => 'Ten Steps',
        'gender' => 'Gender',
        'basic_salary' => 'Basic Salary',
        'house_rent' => 'House rent',
        'medical' => 'Medical',
        'living' => 'Living',
        'conv' => 'Conveince',
        'special' => 'Special Allowance',
        'others' => 'Others Allowance',
    );

    if($key){
        return $array[$key];
    }

    return $array;
}

public function log($request,$array,$type)
{
    $log='';
    $count=0;
    foreach ($request->all() as $field => $value) {
        if(array_key_exists($field,$array)){
            $check=$this->checkLog($request->emp_id,$field,$value,$type)->getData();
            if($check->success){
                $log.=$check->log.'<br>';
            }
        }
    }

    return $log;
}

public function checkLog($emp_id,$old,$new,$type)
{
    if($type=="1"){
        $source=Employee::find($emp_id);
    }elseif($type=="2"){
        $source=Insurance::find($emp_id);
    }elseif($type=="3"){
        $source=Salary::find($emp_id);
    }

    if(isset($source->$old)){
        if(trim($source->$old)!=trim($new)){
            return response()->json([
                'success' => true,
                'log' => $this->logText($old,$source->$old,$new),
            ]);
        }else{
            return response()->json([
                'success' => false,
            ]);
        }
    }else{
        return response()->json([
            'success' => false,
        ]);
    }
}

public function logText($field,$old,$new)
{
    if($field=="emp_type"){
        $change_from=$this->getEmployeeType($old);
        $changed_to=$this->getEmployeeType($new);
    }elseif($field=="emp_country"){
        $change_from=$this->getCountryName($old);
        $changed_to=$this->getCountryName($new);
    }elseif($field=="emp_desig_id"){
        $change_from=$this->getDesignationName($old);
        $changed_to=$this->getDesignationName($new);
    }elseif($field=="emp_depart_id"){
        $change_from=$this->getDepartmentName($old);
        $changed_to=$this->getDepartmentName($new);
    }elseif($field=="emp_sdepart_id"){
        $change_from=$this->getSubDepartmentName($old);
        $changed_to=$this->getSubDepartmentName($new);
    }elseif($field=="emp_seniorid"){
        $change_from=$this->getSeniorName($old);
        $changed_to=$this->getSeniorName($new);
    }elseif($field=="emp_authperson"){
        $change_from=$this->getSeniorName($old);
        $changed_to=$this->getSeniorName($new);
    }elseif($field=="emp_wknd"){
        $change_from=$this->weekend($old);
        $changed_to=$this->weekend($new);
    }elseif($field=="emp_vehicle"){
        $change_from=$this->YesOrNo($old);
        $changed_to=$this->YesOrNo($new);
    }elseif($field=="emp_workhr"){
        $change_from=$this->getWorkHour($old);
        $changed_to=$this->getWorkHour($new);
    }elseif($field=="emp_otent"){
        $change_from=$this->YesOrNo($old);
        $changed_to=$this->YesOrNo($new);
    }elseif($field=="emp_shiftid"){
        $change_from=$this->getShiftInfo($old);
        $changed_to=$this->getShiftInfo($new);
    }elseif($field=="emp_jlid"){
        $change_from=$this->getLocationName($old);
        $changed_to=$this->getLocationName($new);
    }elseif($field=="emp_status"){
        $change_from=$this->status($old);
        $changed_to=$this->status($new);
    }else{
        $change_from=$old;
        $changed_to=$new;
    }

    return '<strong>'.$this->filedsArray($field).'</strong> has been changed from : <strong>'.$change_from.'</strong> to : <strong>'.$changed_to.'</strong>';
}

public function employeeDetailsEditSubmit(Request $request)
{
    // return $request; exit();
    $id =   Auth::guard('admin')->user();
    $employee=Employee::find($request->emp_id);

    $log='';
    $log.=$this->log($request,$this->filedsArray(),1);
    $log.=$this->log($request,$this->filedsArray(),2);
    $log.=$this->log($request,$this->filedsArray(),3);

    $employee->fill($request->all());
    
    if($id->suser_level=="1" or $id->suser_level=="3"){
        $employee->emp_empid=str_replace(' ','',$request->emp_empid);
    }

    $employee->emp_updated_at=date('Y-m-d h:i:s');
    $employee->save();

    $this->empInsurance($request->emp_id,$request);
    if($id->suser_level=="1"){
        $this->empSalary($request->emp_id,$request);
        $this->empEditPayroll($request->emp_id,$request);
    }

    if($request->file('emp_img') != ""){
        $fileExtension=$request->file('emp_img')->getClientOriginalExtension(); 
        $fileName =  $request->emp_id.'.'.$fileExtension;
        $upload=Image::make( $request->file('emp_img')->getRealPath() )->save( base_path().'/public/EmployeeImage/'.$fileName);
        if($upload){
            $log.='Employee Image Updated.';
            $updateimageextension=$employee->update(['emp_imgext'=>$fileExtension]);
        }
    }

    if(isset($employee) || isset($upload)){
        if(strlen($log)>5){
            $empHistory=EmployeeHistory::insert([
                'eh_empid'=>$request->emp_id,
                'eh_log'=>$log,
                'eh_operator'=>$id->suser_empid,
                'eh_datetime'=>date('Y-m-d H:i:s'),
            ]);
        }
        Session::flash('success','Employee Information Updated Successfully.');
    }else{
        Session::flash('error','Sorry!! No Information been changed!!');
    }

    return redirect()->back();
}


public function employeeDetailsHistory($emp_id)
{
    $id =   Auth::guard('admin')->user();
    $mainlink = $this->adminmainmenu();
    $sublink = $this->adminsubmenu();
    $Adminminlink = $this->adminlink();
    $adminsublink = $this->adminsublink();
    $adminInfo = $this->adminInfo($id->suser_empid);

    $ifPermitted=$this->ifPermitted($id,$emp_id);
    if($ifPermitted=="0"){
        Session::flash('error',"Sorry! You're not permitted to view history of this Employee Information.");
        return redirect('employee-details');
    }

    $employee=Employee::find($emp_id);
    $employeeHistory=EmployeeHistory::with(['operator'])->where('eh_empid',$emp_id)->orderBy('eh_datetime','DESC')->get();

    return view('Admin.employeeDetails.history',compact('id','mainlink','sublink','Adminminlink','adminsublink','employee','employeeHistory'));
}


public function createSystemUser()
{
    $id =   Auth::guard('admin')->user();
    $mainlink = $this->adminmainmenu();
    $sublink = $this->adminsubmenu();
    $Adminminlink = $this->adminlink();
    $adminsublink = $this->adminsublink();
    $designation=Designation::get();
    $department=Department::get();
    $priority=Priority::where('pr_status','1')->get();

    return view('Admin.employeeDetails.createSystemUser',compact('id','mainlink','sublink','Adminminlink','adminsublink','department','designation','priority'));
}

public function getEmployee($emp_depart_id,$emp_sdepart_id,$emp_desig_id)
{
    $data='<option value="0">Select Employee</option>';

    $employee=Employee::
        when($emp_depart_id>0, function($query) use ($emp_depart_id){
            return $query->where('tbl_employee.emp_depart_id', $emp_depart_id);
        })
        ->when($emp_sdepart_id>0, function($query) use ($emp_sdepart_id){
            return $query->where('tbl_employee.emp_sdepart_id', $emp_sdepart_id);
        })
        ->when($emp_desig_id>0, function($query) use ($emp_desig_id){
            return $query->where('tbl_employee.emp_desig_id', $emp_desig_id);
        })
        ->groupBy('tbl_employee.emp_id')
        ->get();
    if(isset($employee[0])){
        foreach ($employee as $emp) {
            $data.='<option value="'.$emp->emp_id.'">'.$emp->emp_name.'</option>';
        }
    }
    return $data;
}

public function getEmployeeEmail($emp_empid)
{
    $empid=Employee::find($emp_empid);
    if(isset($empid->emp_empid)){
        return str_replace(' ','',$empid->emp_empid);
    }else{
        return '';
    }
}

public function createSystemUserSubmit(Request $request)
{
    if($request->suser_empid!="0"){

    $this->validate($request,[
        'suser_empid'=>'required|unique:tbl_sysuser',
        'suser_emptype'=>'required',
        'email'=>'required|unique:tbl_sysuser',
        'password'=>'required',
    ]);

    if(strlen($request->password)>=2){
        $insert=Admin::insert([
                'suser_empid'=>$request->suser_empid,
                'suser_emptype'=>$request->suser_emptype,
                'email'=>trim($request->email),
                'password'=>bcrypt($request->password),
                'suser_level'=>$request->suser_level,
            ]);
        if($insert){
            $this->empHistory($request->suser_empid,'System User Account Has Been Created');
            Session::flash('success','System User Created Successfully.');
            return redirect('create-system-user');
        }else{
            Session::flash('error','Something Went Wrong!!');
            return redirect('create-system-user');
        }
    }else{
        Session::flash('error','Sorry!! Password must be at least 2 Characters.');
        return redirect('create-system-user');
    }

    }else{
        Session::flash('error','Whopps!! Please Choose An Employee.');
        return redirect('create-system-user');
    }

}

public function filterSystemUser($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type)
{
    $id =   Auth::guard('admin')->user();
    if($id->suser_level=="1" or $id->suser_level=="2" or $id->suser_level=="3"){
        return $employeeDetails=DB::table('tbl_sysuser')
            ->join('tbl_employee','tbl_sysuser.suser_empid','=','tbl_employee.emp_id')
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
            ->orderBy('tbl_employee.emp_empid','ASC')
            ->get(['tbl_sysuser.*','tbl_employee.*']);
    }else if($id->suser_level=="4"){
        return $employeeDetails=DB::table('tbl_sysuser')
            ->join('tbl_employee','tbl_sysuser.suser_empid','=','tbl_employee.emp_id')
            ->where('tbl_employee.emp_seniorid',$id->suser_empid)
            ->orWhere('tbl_employee.emp_id',$id->suser_empid)
            ->where('tbl_employee.emp_status','1')
            ->orderBy('tbl_employee.emp_empid','ASC')
            ->get(['tbl_sysuser.*','tbl_employee.*']);
    }

}

public function getSystemUserRow($systemUser,$id)
{
    $data='';
    if(isset($systemUser[0])){
    $c=0;
        foreach ($systemUser as $ed){
        $c++;
        $data.='<tr class="gradeX" id="tr-'.$ed->emp_id.'">
            <td style="text-align: center">
                <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                    <input type="checkbox" class="checkboxes" value="'.$ed->emp_id.'" name="emp_id[]" />
                    <span></span>
                </label>
            </td>
            <td>'.$c.'</td>';
        if($id->suser_level=="1"){
            $data.='<td>'.$ed->emp_machineid.'</td>';
        }
        $data.='<td><a href="'.URL::to('employee-details').'/'.$ed->emp_id.'/view">'.$ed->emp_name.'</a></td>
                <td>'.$ed->email.'</td>';
        if($id->suser_level=="1"){
            $data.='<td><a class="btn btn-xs btn-success" href="'.URL::to('/').'/viewas/'.$ed->id.'"><i class="fa fa-eye"></i>&nbsp;View As</a></td>';
            $data.='<td><a class="btn btn-xs btn-primary" onclick="setDefaultPassword(';
                    $data.="'".$ed->id."'";
            $data.=');">Set Default Password</a><span id="msg'.$ed->id.'"></span></td>';
        }
        $data.='<td>'.$this->getUsreRoleInfo($ed->suser_level).'</td>';
        if($id->suser_level=="1"){
            $data.='<td><a class="btn btn-xs btn-success" onclick="changeUserPriorityLevel(';
            $data.="'".$ed->id."'";
            $data.=');">Change Role</a></td>';
        }
        $data.='<td>';
                if($ed->suser_emptype=="1"){
                    $data.='<a class="btn btn-xs btn-success">Office</a>';
                }elseif($ed->suser_emptype=="0"){
                    $data.='<a class="btn btn-xs btn-primary">Remote</a>';
                }
        $data.='</td><td>';
                if($ed->suser_status=="1"){
                    $data.='<a class="btn btn-xs btn-success">Enabled</a>';
                }elseif($ed->suser_status=="0"){
                    $data.='<a class="btn btn-xs btn-danger">Disabled</a>';
                }
        $data.='</td>
                <td>'.$this->getDesignationName($ed->emp_desig_id).'</td>
                <td>'.$this->getDepartmentName($ed->emp_depart_id).'</td>
                <td>'.$this->getSubDepartmentName($ed->emp_sdepart_id).'</td>
                <td>'.$this->getEmployeeType($ed->emp_type).'</td>
          </tr>';
        }
    }

    return $data;
}

public function viewSystemUser()
{
    $id =   Auth::guard('admin')->user();
    $mainlink = $this->adminmainmenu();
    $sublink = $this->adminsubmenu();
    $Adminminlink = $this->adminlink();
    $adminsublink = $this->adminsublink();

    $types=EmployeeTypes::orderBy('name','asc')->get();
    $designation=Designation::get();
    $department=Department::get();
    $joblocation=JobLocation::get();

    if($id->suser_level=="1" or $id->suser_level=="2" or $id->suser_level=="3" or $id->suser_level=="4"){
        $systemUser=$this->filterSystemUser('0','0','0','0','0');
    }else if($id->suser_level=="5" or $id->suser_level=="6"){
        return redirect('employee-details/'.$id->suser_empid.'/view');
    }

    $data=$this->getSystemUserRow($systemUser,$id);
    //dd($data);
    return view('Admin.employeeDetails.viewSystemUser',compact('id','mainlink','sublink','Adminminlink','adminsublink','types','department','designation','data'));

}

public function systemUserSearch($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type)
{
    $id =   Auth::guard('admin')->user();
    $mainlink = $this->adminmainmenu();
    $sublink = $this->adminsubmenu();
    $Adminminlink = $this->adminlink();
    $adminsublink = $this->adminsublink();
    
    $types=EmployeeTypes::orderBy('name','asc')->get();
    $designation=Designation::get();
    $department=Department::get();
    $joblocation=JobLocation::get();

    if($id->suser_level=="1" or $id->suser_level=="2" or $id->suser_level=="3" or $id->suser_level=="4"){
        $systemUser=$this->filterSystemUser($emp_depart_id,$emp_sdepart_id,$emp_desig_id,$emp_jlid,$emp_type);
    }else if($id->suser_level=="5" or $id->suser_level=="6"){
        return redirect('employee-details/'.$id->suser_empid.'/view');
    }
    
    $data=$this->getSystemUserRow($systemUser,$id);

    return view('Admin.employeeDetails.viewSystemUser',compact('id','mainlink','sublink','Adminminlink','adminsublink','types','department','designation','joblocation','emp_depart_id','emp_sdepart_id','emp_desig_id','emp_jlid','emp_type','data'));
}


public function systemUserEnable(Request $request)
{
    if(count($request->emp_id)>0){
        for ($i=0; $i < count($request->emp_id) ; $i++) { 
            $enable_employee=DB::table('tbl_sysuser')->where('suser_empid',$request->emp_id[$i])->update(['suser_status'=>'1']);
            if($enable_employee){
                $this->empHistory($request->emp_id[$i],'System User Has Been Enabled');
            }
        }
        if($enable_employee){
            Session::flash('success','System User(s) Enabled Successfully.');
            return '1';
        }else{
            return '0';
        }
    }else{
        return 'null';
    }
}

public function systemUserdisable(Request $request)
{
    if(count($request->emp_id)>0){
        for ($i=0; $i < count($request->emp_id) ; $i++) { 
            $disable_employee=DB::table('tbl_sysuser')->where('suser_empid',$request->emp_id[$i])->update(['suser_status'=>'0']);
            if($disable_employee){
                $this->empHistory($request->emp_id[$i],'System User Has Been Disabled');
            }
        }
        if($disable_employee){
            Session::flash('success','System User(s) Disabled Successfully.');
            return '1';
        }else{
            return '0';
        }
    }else{
        return 'null';
    }
}

public function makeSystemUserOfficeAttendant(Request $request)
{
    if(count($request->emp_id)>0){
        for ($i=0; $i < count($request->emp_id) ; $i++) { 
            $change=DB::table('tbl_sysuser')->where('suser_empid',$request->emp_id[$i])->update(['suser_emptype'=>'1']);
            if($change){
                $this->empHistory($request->emp_id[$i],'System User Has Been Changed To Office Attendant');
            }
        }
        if($change){
            Session::flash('success','System User(s) Made as Office Attendant Successfully.');
            return '1';
        }else{
            return '0';
        }
    }else{
        return 'null';
    }
}

public function makeSystemUserRemoteAttendant(Request $request)
{
    if(count($request->emp_id)>0){
        for ($i=0; $i < count($request->emp_id) ; $i++) { 
            $change=DB::table('tbl_sysuser')->where('suser_empid',$request->emp_id[$i])->update(['suser_emptype'=>'0']);
            if($change){
                $this->empHistory($request->emp_id[$i],'System User Has Been Changed To Remote Attendant');
            }
        }
        if($change){
            Session::flash('success','System User(s) Made as Remote Attendant Successfully.');
            return '1';
        }else{
            return '0';
        }
    }else{
        return 'null';
    }
}

public function viewas($userid)
{
    $id =   Auth::guard('admin')->user();
    if($id->suser_level=="1"){
        if(Auth::guard('admin')->loginUsingId($userid)){
            return redirect('/');
        }else{
            return redirect('returntohome');
        }
    }else{
        return redirect('returntohome');
    }
}

public function changePassword()
{
    $id =   Auth::guard('admin')->user();
    $mainlink = $this->adminmainmenu();
    $sublink = $this->adminsubmenu();
    $Adminminlink = $this->adminlink();
    $adminsublink = $this->adminsublink();

    return view('Admin.employeeDetails.changePassword',compact('id','mainlink','sublink','Adminminlink','adminsublink'));
}

public function changePasswordSubmit(Request $request)
{
    $id =   Auth::guard('admin')->user();
    
    if($request->oldPass!=""){
        if(Hash::check($request->oldPass, $id->password)){
            if($request->newPass!=""){
                if($request->newPass==$request->confirmPass){
                    $update=DB::table('tbl_sysuser')
                        ->where('suser_empid',$id->suser_empid)
                        ->update(['password'=>bcrypt($request->newPass)]);
                    if(isset($update)){
                        $this->empHistory($id->suser_empid,'Password Has been changed.');
                        Session::flash('success','Welcome! Your Password Changed Successfully.');
                    }else{
                        Session::flash('error','Sorry! Something Went Wrong! Try Again.');
                    }
                }else{
                    Session::flash('error','Sorry! Your New Password Does not Matched!');
                }
            }else{
                Session::flash('error','Sorry! Please enter a new password!');
            }
        }else{
            Session::flash('error','Sorry! Your Current Password Does not Matched!');
        }

    }else{
        Session::flash('error','Sorry! Please enter your current password!');
    }
    return redirect('change-password');
}

public function setDefaultPassword($id)
{
    $user=DB::table('tbl_sysuser')->where('id',$id)->first();
    $explode=explode('-',$user->email);
    if(isset($explode[1]) && $explode[1]!=""){
        $email=$user->email;
        $password=$explode[1];
        $update=DB::table('tbl_sysuser')
            ->where('id',$id)
            ->update([
                'password'=>bcrypt($password),
            ]);
        if(isset($update)){
            $this->empHistory($user->suser_empid,'Password has been changed to default : '.$password);
            return '<font style="color:green;font-size:10px">New Password : '.$password.'</font>';
        }else{
            return '<font style="color:red;font-size:10px">Sorry! Password has not been changed!</font>';
        }
    }else{
        $email=$user->email;
        $password=substr($email,6,10);
        $update=DB::table('tbl_sysuser')
            ->where('id',$id)
            ->update([
                'password'=>bcrypt($password),
            ]);
        if(isset($update)){
            $this->empHistory($user->suser_empid,'Password has been changed to default : '.$password);
            return '<font style="color:green;font-size:10px">New Password : '.$password.'</font>';
        }else{
            return '<font style="color:red;font-size:10px">Sorry! Password has not been changed!</font>';
        }      
    }
}

    public function changeUserPriorityLevel($id)
    {
        $employee=DB::table('tbl_sysuser')
            ->join('tbl_employee','tbl_sysuser.suser_empid','=','tbl_employee.emp_id')
            ->where('tbl_sysuser.id',$id)
            ->first(['tbl_employee.emp_name','tbl_employee.emp_empid','tbl_employee.emp_machineid','tbl_sysuser.suser_level']);
        $priority=DB::table('tbl_priority')->where('pr_status','1')->get();
        return view('Admin.employeeDetails.changeUserPriorityLevel',compact('id','employee','priority'));
    }

    public function changeUserPriorityLevelSubmit(Request $request)
    {
        $employee=DB::table('tbl_sysuser')
            ->where('tbl_sysuser.id',$request->id)
            ->first();
        $old_priority=DB::table('tbl_priority')
            ->join('tbl_sysuser','tbl_priority.pr_id','=','tbl_sysuser.suser_level')
            ->where('tbl_sysuser.id',$request->id)
            ->first(['tbl_priority.pr_name']);
        $new_priority=DB::table('tbl_priority')
            ->where('tbl_priority.pr_id',$request->suser_level)
            ->first(['tbl_priority.pr_name']);
        $changeUserPriorityLevel=DB::table('tbl_sysuser')
            ->where('tbl_sysuser.id',$request->id)
            ->update([
                'suser_level'=>$request->suser_level,
            ]);
        if(isset($changeUserPriorityLevel)){
            $msg='User Priority Level has been changed from <b>'.$old_priority->pr_name.'</b> to <b>'.$new_priority->pr_name.'</b>';
            $this->empHistory($employee->suser_empid,$msg);
            Session::flash('success','User Priority Level has been changed from '.$old_priority->pr_name.' to '.$new_priority->pr_name);
            return redirect('view-system-user');
        }else{
            Session::flash('error','Sorry! Nothing Has been Changed.');
            return redirect('view-system-user');
        }
    }

    public function pendingForgetPasswordRequest()
    {
        $id =   Auth::guard('admin')->user();
        $mainlink = $this->adminmainmenu();
        $sublink = $this->adminsubmenu();
        $Adminminlink = $this->adminlink();
        $adminsublink = $this->adminsublink();

        if($id->suser_level=="1" or $id->suser_level=="3"){
            $pendingForgetPasswordRequest=DB::table('tbl_forgetpassword')
                ->join('tbl_employee','tbl_forgetpassword.suser_empid','=','tbl_employee.emp_id')
                ->join('tbl_sysuser','tbl_employee.emp_id','=','tbl_sysuser.suser_empid')
                ->where('tbl_forgetpassword.fp_status','0')
                ->orderBy('tbl_forgetpassword.submitted_at','DESC')
                ->get(['tbl_employee.*','tbl_sysuser.*']);
        }else if($id->suser_level=="4"){
            $pendingForgetPasswordRequest=DB::table('tbl_forgetpassword')
                ->join('tbl_employee','tbl_forgetpassword.suser_empid','=','tbl_employee.emp_id')
                ->join('tbl_sysuser','tbl_employee.emp_id','=','tbl_sysuser.suser_empid')
                ->where('tbl_forgetpassword.submitted_to',$id->suser_empid)
                ->where('tbl_forgetpassword.fp_status','0')
                ->orderBy('tbl_forgetpassword.submitted_at','DESC')
                ->get(['tbl_employee.*','tbl_sysuser.*']);
        }

        $data='';
        if(isset($pendingForgetPasswordRequest[0])){
        $c=0;
            foreach ($pendingForgetPasswordRequest as $ed){
            $c++;
            $data.='<tr class="gradeX" id="tr-'.$ed->emp_id.'">
                <td>'.$c.'</td>';
            $data.='<td><a href="'.URL::to('employee-details').'/'.$ed->emp_id.'/view">'.$ed->emp_name.'</a></td>
                    <td>'.$ed->email.'</td>
                    <td><a class="btn btn-xs btn-primary" onclick="return setDefaultPassword();" href="'.URL::to('set-default-password').'/'.$ed->emp_id.'/reset">Set Default Password</a><span id="msg'.$ed->id.'"></span></td>';
            $data.='<td>'.$this->getDesignationName($ed->emp_desig_id).'</td>
                    <td>'.$this->getDepartmentName($ed->emp_depart_id).'</td>
                    <td>'.$this->getSubDepartmentName($ed->emp_sdepart_id).'</td>
              </tr>';
            }
        }

        return view('Admin.employeeDetails.pendingForgetPasswordRequest',compact('id','mainlink','sublink','Adminminlink','adminsublink','data'));
    }

    public function setDefaultPasswordFromRequest($suser_empid)
    {
        $user=DB::table('tbl_sysuser')->where('suser_empid',$suser_empid)->first();
        $explode=explode('-',$user->email);
        if(isset($explode[1]) && $explode[1]!=""){
            $email=$user->email;
            $password=$explode[1];
            $update=DB::table('tbl_sysuser')
                ->where('suser_empid',$suser_empid)
                ->update([
                    'password'=>bcrypt($password),
                ]);
            if(isset($update)){
                $search=DB::table('tbl_forgetpassword')->where('suser_empid',$suser_empid)->first();
                $this->empHistory($user->suser_empid,'Password has been changed to default : '.$password.' by '.$this->getSeniorName($search->submitted_to));
                $delete=DB::table('tbl_forgetpassword')->where('suser_empid',$suser_empid)->delete();
                Session::flash('success','Password has been changed to default : '.$password.' for '.$this->getSeniorName($suser_empid));
            }else{
                Session::flash('error','Something Went Wrong! Try Again');
            }
        }else{
            $email=$user->email;
            $password=substr($email,6,10);
            $update=DB::table('tbl_sysuser')
                ->where('suser_empid',$suser_empid)
                ->update([
                    'password'=>bcrypt($password),
                ]);
            if(isset($update)){
                $search=DB::table('tbl_forgetpassword')->where('suser_empid',$suser_empid)->first();
                $this->empHistory($user->suser_empid,'Password has been changed to default : '.$password.' by '.$this->getSeniorName($search->submitted_to));
                $delete=DB::table('tbl_forgetpassword')->where('suser_empid',$suser_empid)->delete();
                Session::flash('success','Password has been changed to default : '.$password.' for '.$this->getSeniorName($suser_empid));
            }else{
                Session::flash('error','Something Went Wrong! Try Again');
            }     
        }
        return redirect('pending-forget-password-request');
    }

    public function employeeDetailsEmployeeType()
    {
        if($this->checkAppModulePriority('employee-details','Update Employee Type')=="1"){
            $types=EmployeeTypes::where('status',1)->orderBy('name','asc')->get();
            return view('Admin.employeeDetails.changeType',compact('types'));
        }
    }

    public function employeeDetailsEmployeeShift()
    {
        if($this->checkAppModulePriority('employee-details','Update Employee Type')=="1"){
            $types=Shift::where('shift_status',1)->orderBy('shift_id','asc')->get();
            return view('Admin.employeeDetails.changeShift',compact('types'));
        }
    }

    public function employeeDetailsUpdateEmployeeType(Request $request,$type)
    {
        //return $request->all();
        $success=0;
        if(isset($request->emp_id[0])){
            foreach ($request->emp_id as $key => $emp_id) {
                $update=Employee::where('emp_id',$emp_id)->update([
                    'emp_type' => $type
                ]);
                if($update){
                    $success++;
                }
            }
        }else{
           return response()->json([
                'success' => false,
                'msg' => 'Please Choose Employee'
            ]); 
        }

        if($success>0){
            $emp_type=EmployeeTypes::find($type);
            session()->flash('success',$success." employee's type has been updated to ".$emp_type->name);
            return response()->json([
                'success' => true
            ]);
        }

        return response()->json([
            'success' => false,
            'msg' => 'Nothing has changed!'
        ]);
    }

    public function employeeDetailsUpdateEmployeeShift(Request $request,$type)
    {
        // return $request; exit();
        $search=Shift::where('shift_id', $type)->first();
        $success=0;
        if(isset($request->emp_id[0])){
            foreach ($request->emp_id as $key => $emp_id) {
                $update=Employee::where('emp_id',$emp_id)->update([
                    'emp_shiftid' => $type,
                    'emp_workhr'  => $search->shift_type
                ]);
                if($update){
                    $success++;
                }
            }
        }else{
           return response()->json([
                'success' => false,
                'msg' => 'Please Choose Employee'
            ]); 
        }

        if($success>0){
            $emp_type=Shift::where('emp_workhr', $type);
            session()->flash('success',$success." employee's Shift has been updated  ");
            return response()->json([
                'success' => true
            ]);
        }

        return response()->json([
            'success' => false,
            'msg' => 'Nothing has changed!'
        ]);
    }
    

    public function employeeDetailsEducation($emp_id)
    {
        $education=Educations::find($emp_id);
        return view('Admin.employeeDetails.education',compact('education'));
    }

    public function employeeDetailsEducationSubmit(Request $request,$emp_id)
    {
        $education=Educations::find($emp_id);
        $education->fill($request->all());
        $education->updated_by=Auth::guard('admin')->user()->suser_empid;
        $education->save();
        if($education){
            return response()->json([
                'success' => true,
            ]);
        }

        return response()->json([
            'success' => false,
            'msg' => 'Something Went Wrong!'
        ]);
    }
}