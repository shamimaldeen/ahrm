<?php

namespace App\Http\Controllers\BackEndCon;

use Illuminate\Http\Request;
use Auth;
use DB;
use App\Http\Controllers\BackEndCon\attendanceController;
use App\Http\Controllers\BackEndCon\ToolsController;
use App\Http\Controllers\BackEndCon\reportController;
use App\Employee;
use App\EmployeeTypes;
use App\Salary;
use App\SalaryHead;
use App\Payroll;
use App\PayrollSummery;
use App\PayrollSummeryHeads;
use App\PayrollSummeryExtends;
use App\ProjectDetails;
use App\SalaryHeadExtends;
use App\ProvidentFundSetup;
use App\ProvidentFund;
use App\TotalWorkingHours;
use App\Month;
use App\Loan;
use App\LoanApprove;
use App\Mobilebill;
use App\Setup;
use App\Bonus;
use App\Taxchallan;
use App\ContractualPayroll;
use DateTime;

class PayrollController extends Controller
{
    public function tax_report_challan()
    {
        $id =   Auth::guard('admin')->user();
        $mainlink = $this->adminmainmenu();
        $sublink = $this->adminsubmenu();
        $Adminminlink = $this->adminlink();
        $adminsublink = $this->adminsublink();
        $types=EmployeeTypes::where('status',1)->get();
        $payment=false;
        $months=Month::get();
        $month=date('m');

        return view('Admin.payroll.monthWise.tax_report_challan',compact('id','mainlink','sublink','Adminminlink','adminsublink','types','payment','months','month'));
    }
    public function generated_payroll_list()
    {
        $payroll=DB::table('tbl_payrollsummery')
        ->select('payroll_date_from')
        ->distinct('payroll_date_from')
        ->get();
        // return $payroll; exit();

        $id =   Auth::guard('admin')->user();
        $mainlink = $this->adminmainmenu();
        $sublink = $this->adminsubmenu();
        $Adminminlink = $this->adminlink();
        $adminsublink = $this->adminsublink();
        $types=EmployeeTypes::where('status',1)->get();
        $payment=false;
        $months=Month::get();
        $month=date('m');

        return view('Admin.payroll.monthWise.payroll_list',compact('id','mainlink','sublink','Adminminlink','adminsublink','types','payment','months','month', 'payroll'));

        // return view('Admin.payroll.monthWise.payroll_list', compact('payroll'));
    }

    public function payroll_delete($date)
    {
        $id =   Auth::guard('admin')->user();
        $mainlink = $this->adminmainmenu();
        $sublink = $this->adminsubmenu();
        $Adminminlink = $this->adminlink();
        $adminsublink = $this->adminsublink();

        $employee='';
        // $emp_id=explode('&',$data)[4];
        // if($emp_id!="0"){
        //     $employee=$this->emp_name($emp_id).' ('.$this->emp_empid($emp_id).')';
        // }

        return view('Admin.payroll.monthWise.delete_payroll',compact('id','mainlink','sublink','Adminminlink','adminsublink','employee', 'date'));
    }

    public function payroll_delete_submit(Request $request)
    {

        PayrollSummery::where('payroll_date_from', $request->date)->delete();
        return redirect('generated-payroll-list');
    }

    public function tax_certificate($emp_id)
    {
        // return $emp_id; exit();
        // $date = strtotime('2010-01-01 -1 year');
        $date         = strtotime("-1 year", time());
        $last_year    =  date('Y-06-30', $date);
        $present_year = date('Y-07-01');

        // return $present_year; exit();


        $a=PayrollSummery::where('emp_id', $emp_id)
        ->where('payroll_date_to','>', $last_year)
        ->where('payroll_date_from','<',  $present_year)
        ->get();
        $emp_tax=Employee::where('emp_id', $emp_id)->first();

        $tax_challan= Taxchallan::
        where('year','<=',date('Y'))
        ->where('year','>=',$last_year)
        ->where('emp_id', $emp_id)
        ->get();

        // return $tax_challan; exit();
        return view('Admin.payroll.monthWise.tax_certificate', compact('a', 'emp_tax', 'tax_challan', 'last_year', 'present_year'));
    }
    public function monthWiseCertificate($data)
    {
        $emp_id=explode('&',$data)[0];
        $payroll_date_from=explode('&',$data)[1];
        $payroll_date_to=explode('&',$data)[2];
        $employee=Employee::with(['designation','department'])->find($emp_id);
        $PayrollSummery=PayrollSummery::with(['heads','heads.head','extends','extends.head'])->where([
            'emp_id'=>$emp_id,
            'payroll_date_from'=>$payroll_date_from,
            'payroll_date_to'=>$payroll_date_to,
        ])
        ->first();
        // return $PayrollSummery; exit();
        $ProvidentSetup=$this->ProvidentSetup();
        $projectDetails=ProjectDetails::find('1');
        return view('Admin.payroll.monthWise.certificate',compact('employee','payroll_date_from','payroll_date_to','PayrollSummery','projectDetails','ProvidentSetup'));
    }

    
    public function dayWisePayment()
    {
        $id =   Auth::guard('admin')->user();
        $mainlink = $this->adminmainmenu();
        $sublink = $this->adminsubmenu();
        $Adminminlink = $this->adminlink();
        $adminsublink = $this->adminsublink();
        $designation=DB::table('tbl_designation')->get();
        $department=DB::table('tbl_department')->get();
        $joblocation=DB::table('tbl_joblocation')->get();
        $payment=false;
        return view('Admin.payroll.dayWise.generate',compact('id','mainlink','sublink','Adminminlink','adminsublink','designation','department','joblocation','payment','page_title'));
    }

    public function dayWisePaymentSubmit(Request $request)
    {
        $id =   Auth::guard('admin')->user();
        $mainlink = $this->adminmainmenu();
        $sublink = $this->adminsubmenu();
        $Adminminlink = $this->adminlink();
        $adminsublink = $this->adminsublink();
        $designation=DB::table('tbl_designation')->get();
        $department=DB::table('tbl_department')->get();
        $joblocation=DB::table('tbl_joblocation')->get();
        $SalaryHead=SalaryHead::get();
        $SalaryHeadExtends=SalaryHeadExtends::where('head_status','1')->get();
        $ProvidentSetup=$this->ProvidentSetup();

        $var=$this->inputData($request,1);
        if(explode('&',$this->checkPayrollDate($var,1))[0]=="0"){
            $data=$this->checkPayrollDate($var,1);
            return redirect('generate-day-wise-payroll/'.$data.'/delete');
        }

        $employee=$this->getSalaryEmployee($var);
        $page_title=$this->getPayrollTitle($var,'Payroll',1);
        $payment=$this->paymentCalculation($employee,$var,$page_title,$SalaryHead,$SalaryHeadExtends,$ProvidentSetup);
        if(count($payment)==0){
            session()->flash('error','Payroll could not generated. Try Again.');
            return redirect('generate-day-wise-payroll');
        }
        $this->PayrollSummerySave($id,$payment,$var,1);

        return view('Admin.payroll.dayWise.generate',compact('id','mainlink','sublink','Adminminlink','adminsublink','designation','department','joblocation','payment','page_title','SalaryHead','SalaryHeadExtends','ProvidentSetup'));
    }

    public function dayWiseDeletePayroll($data)
    {
        $id =   Auth::guard('admin')->user();
        $mainlink = $this->adminmainmenu();
        $sublink = $this->adminsubmenu();
        $Adminminlink = $this->adminlink();
        $adminsublink = $this->adminsublink();

        return view('Admin.payroll.dayWise.delete',compact('id','mainlink','sublink','Adminminlink','adminsublink','data'));
    }

    public function dayWiseDeletePayrollSubmit($data)
    {
        $delete=PayrollSummery::where(['payroll_date_from'=>explode('&',$data)[1],'payroll_date_to'=>explode('&',$data)[2]])->where('type',1)->delete();
        if($delete){
            session()->flash('success','Previously generated payroll from '.explode('&',$data)[1].' to '.explode('&',$data)[2].' has been removed. Now you can regenerate it.');
            return redirect('generate-day-wise-payroll');
        }else{
            session()->flash('error','Something went wrong! Try again.');
            return redirect('generate-day-wise-payroll/'.$data.'/delete');
        }

    }

    public function dayWisePaySlip($data)
    {
        $emp_id=explode('&',$data)[0];
        $payroll_date_from=explode('&',$data)[1];
        $payroll_date_to=explode('&',$data)[2];
        $salaryDays=$this->days($payroll_date_from,$payroll_date_to);
        $employee=Employee::with(['designation','department'])->find($emp_id);
        $PayrollSummery=PayrollSummery::with(['extends','extends.head'])->where([
            'emp_id'=>$emp_id,
            'payroll_date_from'=>$payroll_date_from,
            'payroll_date_to'=>$payroll_date_to,
        ])
        ->first();
        $payroll=Payroll::with(['head'])
        ->where([
            'emp_id'=>$emp_id,
            'head_date_of_execution'=>$PayrollSummery->date_of_execution
        ])
        ->get();
        $ProvidentSetup=$this->ProvidentSetup();
        $projectDetails=ProjectDetails::find('1');
        return view('Admin.payroll.dayWise.payslip',compact('employee','payroll_date_from','payroll_date_to','PayrollSummery','payroll','salaryDays','projectDetails','ProvidentSetup'));
    }

    public function dayWiseViewGeneratedPayrolls()
    {
        $id =   Auth::guard('admin')->user();
        $mainlink = $this->adminmainmenu();
        $sublink = $this->adminsubmenu();
        $Adminminlink = $this->adminlink();
        $adminsublink = $this->adminsublink();
        $PayrollSummery=false;
        return view('Admin.payroll.dayWise.payrollsummery',compact('id','mainlink','sublink','Adminminlink','adminsublink','PayrollSummery'));
    }

    public function dayWiseViewGeneratedPayrollsSubmit(Request $request)
    {
        $id =   Auth::guard('admin')->user();
        $mainlink = $this->adminmainmenu();
        $sublink = $this->adminsubmenu();
        $Adminminlink = $this->adminlink();
        $adminsublink = $this->adminsublink();
        $ProvidentSetup=$this->ProvidentSetup();
        $var=$this->inputData($request,1);
        $start_date=$var['start_date'];
        $end_date=$var['end_date'];
        $salaryDays=$this->days($start_date,$end_date);
        $page_title=$this->getPayrollTitle($var,'Generated Payroll Summery',1);
        $PayrollSummery=PayrollSummery::with(['employee','extends','extends.head'])->where([
            ['payroll_date_from','<=',$start_date],
            ['payroll_date_to','>=',$end_date]
        ])
        ->when($id->suser_level!="1", function($query) use($id){
            return $query->where(function($query) use($id){
                $query->where('emp_id', $id->suser_empid);
            });
        })
        ->where('type',1)
        ->get();
        $SalaryHead=SalaryHead::get();
        return view('Admin.payroll.dayWise.payrollsummery',compact('id','mainlink','sublink','Adminminlink','adminsublink','page_title','PayrollSummery','salaryDays','SalaryHead','ProvidentSetup'));
    }


    public function monthWisePayment()
    {
        $id =   Auth::guard('admin')->user();
        $mainlink = $this->adminmainmenu();
        $sublink = $this->adminsubmenu();
        $Adminminlink = $this->adminlink();
        $adminsublink = $this->adminsublink();
        $types=EmployeeTypes::where('status',1)->get();
        $payment=false;
        $months=Month::get();
        $month=date('m');

        return view('Admin.payroll.monthWise.generate',compact('id','mainlink','sublink','Adminminlink','adminsublink','types','payment','months','month'));
    }

    public function generateContractualPayroll()
    {
        // echo "string"; exit();
        $id =   Auth::guard('admin')->user();
        $mainlink = $this->adminmainmenu();
        $sublink = $this->adminsubmenu();
        $Adminminlink = $this->adminlink();
        $adminsublink = $this->adminsublink();
        $types=EmployeeTypes::where('status',1)->get();
        $payment=false;
        $months=Month::get();
        $month=date('m');

        return view('Admin.payroll.monthWise.generate_contractual',compact('id','mainlink','sublink','Adminminlink','adminsublink','types','payment','months','month'));
    }

    public function monthWiseMobileBill()
    {
        $id =   Auth::guard('admin')->user();
        $mainlink = $this->adminmainmenu();
        $sublink = $this->adminsubmenu();
        $Adminminlink = $this->adminlink();
        $adminsublink = $this->adminsublink();
        $types=EmployeeTypes::where('status',1)->get();
        $payment=false;
        $months=Month::get();
        $month=date('m');

        return view('Admin.payroll.monthWise.generate_mobilebill',compact('id','mainlink','sublink','Adminminlink','adminsublink','types','payment','months','month'));
    }

     public function monthWiseBonus()
    {
        // echo "string"; exit();
        $id =   Auth::guard('admin')->user();
        $mainlink = $this->adminmainmenu();
        $sublink = $this->adminsubmenu();
        $Adminminlink = $this->adminlink();
        $adminsublink = $this->adminsublink();
        $types=EmployeeTypes::where('status',1)->get();
        $payment=false;
        $months=Month::get();
        $month=date('m');
        $bonus_type=0;

        return view('Admin.payroll.monthWise.generate_bonus',compact('id','mainlink','sublink','Adminminlink','adminsublink','types','payment','months','month', 'bonus_type'));
    }

    public function getEmployee($type)
    {
        $employees=Employee::where('emp_type',$type)
            ->groupBy('tbl_employee.emp_id')
            ->whereNotIn('emp_id',[116])
            ->orderBy('emp_name','asc')
            ->get();

        if(isset($employees[0])){
            return response()->json([
                'success' => true,
                'employees' => $employees
            ]);
        }

        return response()->json([
            'success' => false
        ]);
    }

    public function monthWisePaymentSubmit(Request $request)
    {
        $id =   Auth::guard('admin')->user();
        $mainlink = $this->adminmainmenu();
        $sublink = $this->adminsubmenu();
        $Adminminlink = $this->adminlink();
        $adminsublink = $this->adminsublink();
        $types=EmployeeTypes::where('status',1)->get();
        $SalaryHead=SalaryHead::where('head_status','1')->get();
        $SalaryHeadAddition=$SalaryHead->where('head_type',1);

        // return $SalaryHeadAddition; exit();
        $SalaryHeadDeduction=$SalaryHead->where('head_type',0);
        $SalaryHeadExtends=SalaryHeadExtends::where('head_status','1')->get();
        $SalaryHeadExtendsAddition=$SalaryHeadExtends->where('head_type',1);
        $SalaryHeadExtendsDeduction=$SalaryHeadExtends->where('head_type',0);
        $ProvidentSetup=$this->ProvidentSetup();
        $var=$this->inputData($request,30);
        if(explode('&',$this->checkPayrollDate($var,30))[0]=="0"){
            $data=$this->checkPayrollDate($var,30);
            return redirect('generate-month-wise-payroll/'.$data.'/delete');
        }

        $employee=$this->getSalaryEmployee($var);
        $page_title=$this->getPayrollTitle($var,'Month Wise Payroll',30);
        $payment=$this->monthWisePaymentCalculation($employee,$var,$page_title,$SalaryHead,$SalaryHeadExtends,$ProvidentSetup,$request->month);

        
        // return $payment; exit();

        if(count($payment)==0){
            session()->flash('error','Payroll could not generated. Try Again.');
            return redirect('generate-month-wise-payroll');
        }
        session()->put('monthWiseVar',$var);
        session()->put('type',30);
        //$this->PayrollSummerySave($id,$payment,$var,30);
        $months=Month::get();
        $type=$request->type;
        $month=$request->month;
        $year=$request->year;
        return view('Admin.payroll.monthWise.generate',compact('id','mainlink','sublink','Adminminlink','adminsublink','designation','department','joblocation','payment','page_title','SalaryHead','SalaryHeadAddition','SalaryHeadDeduction','SalaryHeadExtends','SalaryHeadExtendsAddition','SalaryHeadExtendsDeduction','ProvidentSetup','months','month','year','types','type', 'emp'));
    }

    public function contractualPayrollSubmit(Request $request)
    {
        // echo "string"; exit();
        // return $request; exit();
        $req_date=$request->year.'-'.$request->month.'-'.'01';
        // return $req_date; exit();
        $id =   Auth::guard('admin')->user();
        $mainlink = $this->adminmainmenu();
        $sublink = $this->adminsubmenu();
        $Adminminlink = $this->adminlink();
        $adminsublink = $this->adminsublink();
        $types=EmployeeTypes::where('status',1)->get();
        $SalaryHead=SalaryHead::where('head_status','1')->get();
        $SalaryHeadAddition=$SalaryHead->where('head_type',1);

        // return $SalaryHeadAddition; exit();
        $SalaryHeadDeduction=$SalaryHead->where('head_type',0);
        $SalaryHeadExtends=SalaryHeadExtends::where('head_status','1')->get();
        $SalaryHeadExtendsAddition=$SalaryHeadExtends->where('head_type',1);
        $SalaryHeadExtendsDeduction=$SalaryHeadExtends->where('head_type',0);
        $ProvidentSetup=$this->ProvidentSetup();
        $var=$this->inputData($request,30);
        // if(explode('&',$this->checkPayrollDate($var,30))[0]=="0"){
        //     $data=$this->checkPayrollDate($var,30);
        //     return redirect('generate-month-wise-payroll/'.$data.'/delete');
        // }

        $month_check = ContractualPayroll::where('payroll_date_from', $req_date)->first();

        // return $month_check->payroll_date_from; exit();
        if($month_check)
        {
            session()->flash('error','Already Generated For this Month.');
            return redirect('contractual-payroll-delete/'.$req_date);
        }

        $employee=$this->getSalaryEmployee($var);
        $page_title=$this->getPayrollTitle($var,'Month Wise Payroll',30);
        $payment=$this->monthWisePaymentCalculation($employee,$var,$page_title,$SalaryHead,$SalaryHeadExtends,$ProvidentSetup,$request->month);

        
        // return $payment; exit();

        if(count($payment)==0){
            session()->flash('error','Payroll could not generated. Try Again.');
            return redirect('generate-month-wise-payroll');
        }
        session()->put('monthWiseVar',$var);
        session()->put('type',30);
        //$this->PayrollSummerySave($id,$payment,$var,30);
        $months=Month::get();
        $type=$request->type;
        $month=$request->month;
        $year=$request->year;
        return view('Admin.payroll.monthWise.generate_contractual',compact('id','mainlink','sublink','Adminminlink','adminsublink','designation','department','joblocation','payment','page_title','SalaryHead','SalaryHeadAddition','SalaryHeadDeduction','SalaryHeadExtends','SalaryHeadExtendsAddition','SalaryHeadExtendsDeduction','ProvidentSetup','months','month','year','types','type', 'emp'));
    }

    public function monthWiseMobilebillSubmit(Request $request)
    {
        // echo "string"; exit();
        // return $request; exit();
        $id =   Auth::guard('admin')->user();
        $mainlink = $this->adminmainmenu();
        $sublink = $this->adminsubmenu();
        $Adminminlink = $this->adminlink();
        $adminsublink = $this->adminsublink();
        $types=EmployeeTypes::where('status',1)->get();
        $SalaryHead=SalaryHead::where('head_status','1')->get();
        $SalaryHeadAddition=$SalaryHead->where('head_type',1);

        // return $SalaryHeadAddition; exit();
        $SalaryHeadDeduction=$SalaryHead->where('head_type',0);
        $SalaryHeadExtends=SalaryHeadExtends::where('head_status','1')->get();
        $SalaryHeadExtendsAddition=$SalaryHeadExtends->where('head_type',1);
        $SalaryHeadExtendsDeduction=$SalaryHeadExtends->where('head_type',0);
        $ProvidentSetup=$this->ProvidentSetup();
        // $var['type'] = 0;
        $var=$this->inputData($request,30);
        // if(explode('&',$this->checkPayrollDate($var,30))[0]=="0"){
        //     $data=$this->checkPayrollDate($var,30);
            // return redirect('generate-month-wise-payroll/'.$data.'/delete');
        // }

        $month_check = Mobilebill::where('month', $request->month)->first();
        if($month_check)
        {
            session()->flash('error','Already Generated For this Month.');
            return redirect('generate-month-wise-mobile-bill-delete/'.$request->month);
        }

        $employee=$this->getMobileEmployee($var);
       
        $page_title=$this->getPayrollTitle($var,'Month Wise Mobile Bill',30);
        $payment=$this->monthWisePaymentCalculation($employee,$var,$page_title,$SalaryHead,$SalaryHeadExtends,$ProvidentSetup,$request->month);

        $emp_mobile= Employee::where('emp_officecontact', '>', 0)
        ->where('emp_allocamount', '>', 0)
        ->get();
        // return $payment; exit();

        if(count($payment)==0){
            session()->flash('error','Payroll could not generated. Try Again.');
            return redirect('generate-month-wise-payroll');
        }
        session()->put('monthWiseVar',$var);
        session()->put('type',30);
        //$this->PayrollSummerySave($id,$payment,$var,30);
        $months=Month::get();
        $type=$request->type;
        $month=$request->month;
        $year=$request->year;
        return view('Admin.payroll.monthWise.generate_mobilebill',compact('id','mainlink','sublink','Adminminlink','adminsublink','designation','department','joblocation','payment','page_title','SalaryHead','SalaryHeadAddition','SalaryHeadDeduction','SalaryHeadExtends','SalaryHeadExtendsAddition','SalaryHeadExtendsDeduction','ProvidentSetup','months','month','year','types','type', 'emp', 'emp_mobile'));
    }

    public function generateTaxReportChallanSubmit(Request $request)
    {
        // echo "string"; exit();
        // return $request; exit();
        $id =   Auth::guard('admin')->user();
        $mainlink = $this->adminmainmenu();
        $sublink = $this->adminsubmenu();
        $Adminminlink = $this->adminlink();
        $adminsublink = $this->adminsublink();
        $types=EmployeeTypes::where('status',1)->get();
        $SalaryHead=SalaryHead::where('head_status','1')->get();
        $SalaryHeadAddition=$SalaryHead->where('head_type',1);

        // return $SalaryHeadAddition; exit();
        $SalaryHeadDeduction=$SalaryHead->where('head_type',0);
        $SalaryHeadExtends=SalaryHeadExtends::where('head_status','1')->get();
        $SalaryHeadExtendsAddition=$SalaryHeadExtends->where('head_type',1);
        $SalaryHeadExtendsDeduction=$SalaryHeadExtends->where('head_type',0);
        $ProvidentSetup=$this->ProvidentSetup();
        // $var['type'] = 0;
        $var=$this->inputData($request,30);
        // if(explode('&',$this->checkPayrollDate($var,30))[0]=="0"){
        //     $data=$this->checkPayrollDate($var,30);
            // return redirect('generate-month-wise-payroll/'.$data.'/delete');
        // }

        $month_check = Taxchallan::where('month', $request->month)->first();
        if($month_check)
        {
            session()->flash('error','Already Generated For this Month.');
            return redirect('generate-month-wise-tax-challan-delete/'.$request->month);
        }

        $employee=$this->getTaxChallanEmployee($var);
       
        $page_title=$this->getPayrollTitle($var,'Month Wise Tax Challan',30);
        $payment=$this->monthWisePaymentCalculation($employee,$var,$page_title,$SalaryHead,$SalaryHeadExtends,$ProvidentSetup,$request->month);

        $emp_mobile= Employee::where('emp_officecontact', '>', 0)
        ->where('emp_allocamount', '>', 0)
        ->get();
        // return $payment; exit();

        if(count($payment)==0){
            session()->flash('error','Payroll could not generated. Try Again.');
            return redirect('generate-month-wise-payroll');
        }
        session()->put('monthWiseVar',$var);
        session()->put('type',30);
        //$this->PayrollSummerySave($id,$payment,$var,30);
        $months=Month::get();
        $type=$request->type;
        $month=$request->month;
        $year=$request->year;
        return view('Admin.payroll.monthWise.tax_report_challan',compact('id','mainlink','sublink','Adminminlink','adminsublink','designation','department','joblocation','payment','page_title','SalaryHead','SalaryHeadAddition','SalaryHeadDeduction','SalaryHeadExtends','SalaryHeadExtendsAddition','SalaryHeadExtendsDeduction','ProvidentSetup','months','month','year','types','type', 'emp', 'emp_mobile'));
    }

     public function monthWiseBonusSubmit(Request $request)
    {
        // echo "string"; exit();
        // return $request; exit();
        $bonus_type=$request->bonus_type;
        $id =   Auth::guard('admin')->user();
        $mainlink = $this->adminmainmenu();
        $sublink = $this->adminsubmenu();
        $Adminminlink = $this->adminlink();
        $adminsublink = $this->adminsublink();
        $types=EmployeeTypes::where('status',1)->get();
        $SalaryHead=SalaryHead::where('head_status','1')->get();
        $SalaryHeadAddition=$SalaryHead->where('head_type',1);

        // return $SalaryHeadAddition; exit();
        $SalaryHeadDeduction=$SalaryHead->where('head_type',0);
        $SalaryHeadExtends=SalaryHeadExtends::where('head_status','1')->get();
        $SalaryHeadExtendsAddition=$SalaryHeadExtends->where('head_type',1);
        $SalaryHeadExtendsDeduction=$SalaryHeadExtends->where('head_type',0);
        $ProvidentSetup=$this->ProvidentSetup();
        // $var['type'] = 0;
        $var=$this->inputData($request,30);
        // if(explode('&',$this->checkPayrollDate($var,30))[0]=="0"){
        //     $data=$this->checkPayrollDate($var,30);
            // return redirect('generate-month-wise-payroll/'.$data.'/delete');
        // }

        $month_check = Bonus::where('month', $request->month)->first();
        if($month_check)
        {
            session()->flash('error','Already Generated For this Month.');
            return redirect('generate-month-wise-bonus-delete/'.$request->month);
        }

        $employee=$this->getMobileEmployee($var);
        $bonus_employee=$this->getBonusEmployee($var);
        $page_title=$this->getPayrollTitle($var,'Month Wise Mobile Bill',30);
        $payment=$this->monthWisePaymentCalculation($bonus_employee,$var,$page_title,$SalaryHead,$SalaryHeadExtends,$ProvidentSetup,$request->month);

        $emp_mobile= Employee::
        get();
        // return $payment; exit();

        if(count($payment)==0){
            session()->flash('error','Payroll could not generated. Try Again.');
            return redirect('generate-month-wise-payroll');
        }
        session()->put('monthWiseVar',$var);
        session()->put('type',30);
        //$this->PayrollSummerySave($id,$payment,$var,30);
        $months=Month::get();
        $type=$request->type;
        $month=$request->month;
        $year=$request->year;
        return view('Admin.payroll.monthWise.generate_bonus',compact('id','mainlink','sublink','Adminminlink','adminsublink','designation','department','joblocation','payment','page_title','SalaryHead','SalaryHeadAddition','SalaryHeadDeduction','SalaryHeadExtends','SalaryHeadExtendsAddition','SalaryHeadExtendsDeduction','ProvidentSetup','months','month','year','types','type', 'emp', 'emp_mobile', 'bonus_type'));
    }

    //delete month wise mobile bill
    public function monthWiseMobileBillDelete($month)
    {
        $id =   Auth::guard('admin')->user();
        $mainlink = $this->adminmainmenu();
        $sublink = $this->adminsubmenu();
        $Adminminlink = $this->adminlink();
        $adminsublink = $this->adminsublink();

        $employee='';
        // $emp_id=explode('&',$data)[4];
        // if($emp_id!="0"){
        //     $employee=$this->emp_name($emp_id).' ('.$this->emp_empid($emp_id).')';
        // }

        return view('Admin.payroll.monthWise.delete_mobilebill',compact('id','mainlink','sublink','Adminminlink','adminsublink','employee', 'month'));
    }

     public function contractualPayrollDelete($date)
    {
        $id =   Auth::guard('admin')->user();
        $mainlink = $this->adminmainmenu();
        $sublink = $this->adminsubmenu();
        $Adminminlink = $this->adminlink();
        $adminsublink = $this->adminsublink();

        $employee='';
        // $emp_id=explode('&',$data)[4];
        // if($emp_id!="0"){
        //     $employee=$this->emp_name($emp_id).' ('.$this->emp_empid($emp_id).')';
        // }

        return view('Admin.payroll.monthWise.delete_contractual',compact('id','mainlink','sublink','Adminminlink','adminsublink','employee', 'date'));
    }

    public function monthWiseTaxChallanDelete($month)
    {
        $id =   Auth::guard('admin')->user();
        $mainlink = $this->adminmainmenu();
        $sublink = $this->adminsubmenu();
        $Adminminlink = $this->adminlink();
        $adminsublink = $this->adminsublink();

        $employee='';
        // $emp_id=explode('&',$data)[4];
        // if($emp_id!="0"){
        //     $employee=$this->emp_name($emp_id).' ('.$this->emp_empid($emp_id).')';
        // }

        return view('Admin.payroll.monthWise.delete_taxchallan',compact('id','mainlink','sublink','Adminminlink','adminsublink','employee', 'month'));
    }

     public function monthWiseBonusDelete($month)
    {
        $id =   Auth::guard('admin')->user();
        $mainlink = $this->adminmainmenu();
        $sublink = $this->adminsubmenu();
        $Adminminlink = $this->adminlink();
        $adminsublink = $this->adminsublink();

        $employee='';
        // $emp_id=explode('&',$data)[4];
        // if($emp_id!="0"){
        //     $employee=$this->emp_name($emp_id).' ('.$this->emp_empid($emp_id).')';
        // }

        return view('Admin.payroll.monthWise.delete_bonus',compact('id','mainlink','sublink','Adminminlink','adminsublink','employee', 'month'));
    }

    public function mobileBillDeleteSubmit(Request $request)
    {
        // return $request;
        Mobilebill::where('month', $request->month)->delete();
        return redirect('generate-month-wise-mobile-bill');
    }

    public function contractualDeleteSubmit(Request $request)
    {
        // return $request;
        ContractualPayroll::where('payroll_date_from', $request->date)->delete();
        return redirect('generate-contractual-payroll');
    }

    public function taxchallanDeleteSubmit(Request $request)
    {
        // return $request;
        Taxchallan::where('month', $request->month)->delete();
        return redirect('tax-report-challan');
    }

    public function bonusDeleteSubmit(Request $request)
    {
        // return $request;
        Bonus::where('month', $request->month)->delete();
        return redirect('generate-month-wise-bonus');
    }

    public function monthWisePaymentCalculate(Request $request,$emp_id)
    {
        $addition=0;
        $deduction=0;
        $salary=0;
        $additionheads=array();
        $deductionheads=array();
        $headExtendsQuantity=array();
        $SalaryHeadExtends=SalaryHeadExtends::where('head_status','1')->get();
        $Employee=Employee::with(['type','type.currentNightShiftAllowance'])->find($emp_id);
        $allowance=0;
        if(isset($Employee->type)){
            if(isset($Employee->type->currentNightShiftAllowance)){
                $allowance=$Employee->type->currentNightShiftAllowance->allowance;
            }   
        }

        if(isset($request->additionheads[$emp_id])){
            $additionheads=$request->additionheads[$emp_id];
        }
        if(isset($request->deductionheads[$emp_id])){
            $deductionheads=$request->deductionheads[$emp_id];
        }
        if(isset($request->headExtendsQuantity[$emp_id])){
            $headExtendsQuantity=$request->headExtendsQuantity[$emp_id];
        }

        

        $tax=$request->tax[$emp_id];

        $b_bonus=0;
       
        if(isset($request->b_bonus[$emp_id])){
           $b_bonus=$request->b_bonus[$emp_id];
        }
        $fitr_bonus=0;
       
        if(isset($request->fitr_bonus[$emp_id])){
           $fitr_bonus=$request->fitr_bonus[$emp_id];
        }
         $adha_bonus=0;
       
        if(isset($request->adha_bonus[$emp_id])){
           $adha_bonus=$request->adha_bonus[$emp_id];
        }
        $pa=1;
        if(isset($request->pa[$emp_id])){
            $pa=$request->pa[$emp_id];
        }

        $pf=0;
        if(isset($request->pf[$emp_id])){
            $pf=$request->pf[$emp_id];
        }

        $basic=0;
        if(isset($request->basic[$emp_id])){
            $basic=$request->basic[$emp_id];
        }

        $perDayBasic=0;
        if(isset($request->perDayBasic[$emp_id])){
            $perDayBasic=$request->perDayBasic[$emp_id];
        }

        $perDaySalary=0;
        if(isset($request->perDaySalary[$emp_id])){
            $perDaySalary=$request->perDaySalary[$emp_id];
        }

        //return $perDayBasic.'  '.$perDaySalary;

        if(count($additionheads)){
            foreach ($additionheads as $key => $head) {
                if($key!=5){
                    $addition+=$head;
                }else{
                    // $addition+=($head*$pa);
                     $addition+=$head;
                }
            }
        }

        if(count($deductionheads)){
            foreach ($deductionheads as $key => $head) {
                $deduction+=$head;
            }
        }

        if(count($headExtendsQuantity)){
            if(isset($SalaryHeadExtends[0])){
                foreach ($SalaryHeadExtends as $key => $headExtends) {
                    $amount=0;
                    if($headExtends->head_id=="1" || $headExtends->head_id=="2"){
                        if(isset($headExtendsQuantity[$headExtends->head_id])){
                            $qty=$headExtendsQuantity[$headExtends->head_id]/$headExtends->head_unit_for_absent;
                            if($qty>0){
                                if($qty<=10){
                                    $amount=$qty*$perDayBasic;
                                }else{
                                    $amount=$qty*$perDaySalary;
                                }
                            }
                        }
                    }elseif($headExtends->head_id=="3"){
                        if(isset($headExtendsQuantity[$headExtends->head_id])){
                            // if($headExtends->head_percentage_status=="1"){
                            //     $amount=$this->decimal(($basic*($headExtends->head_percentage_for_basic/100) ) * $headExtendsQuantity[$headExtends->head_id] * 2);
                            // }elseif($headExtends->head_percentage_status=="2"){
                            //     $amount=$this->decimal(($basic*($headExtends->head_percentage_for_total/100) ) * $headExtendsQuantity[$headExtends->head_id] * 2);
                            // }
                            $amount=$this->decimal(($basic/240)*$headExtendsQuantity[$headExtends->head_id]*2);
                        }
                    }

                    if($headExtends->head_type=="1"){
                        $addition+=$amount;
                    }elseif ($headExtends->head_type=="0") {
                        $deduction+=$amount;
                    }
                }
            }
        }

        $addition+=$request->due[$emp_id];
        $addition+=$b_bonus+$fitr_bonus+$adha_bonus;
        $addition+=$request->nights[$emp_id]*$allowance;
        $deduction+=$request->mobilebill[$emp_id]+$request->advance[$emp_id]+$tax+$pf;

        return response()->json([
            'addition' => $this->decimal($addition),
            'deduction' => $this->decimal($deduction),
            'salary' => $this->decimal($addition-$deduction),
        ]);
    }

    public function monthWisePaymentSave(Request $request)
    {
        // return $request->all(); exit();
        $id =   Auth::guard('admin')->user();
        $var=session()->get('monthWiseVar');
        $heads=SalaryHead::where('head_status',1)->get();
        $extends=SalaryHeadExtends::where('head_status',1)->get();

        foreach ($request->employees as $key => $employee) {
            $emp=Employee::with(['type','type.currentNightShiftAllowance'])->find($employee);
            $allowance=0;
            if(isset($emp->type)){
                if(isset($emp->type->currentNightShiftAllowance)){
                    $allowance=$emp->type->currentNightShiftAllowance->allowance;
                }
            }
            $addition=0;
            $deduction=0;
            $provident_fund=0;
            $nights=0;
            $night_allowance=0;
            $due=0;
            $advance=0;
            $mobilebill=0;
            $tax=0;
            $original_tax=0;
            $salary=0;
            $pa=1;

            $b_bonus=0;
            $fitr_bonus=0;
            $adha_bonus=0;

            if(isset($request->original_tax[$employee])){
                $original_tax=$request->original_tax[$employee];
            } 
            if(isset($request->b_bonus[$employee])){
                $b_bonus=$request->b_bonus[$employee];
            }  
            if(isset($request->fitr_bonus[$employee])){
                $fitr_bonus=$request->fitr_bonus[$employee];
            } 
            if(isset($request->adha_bonus[$employee])){
                $adha_bonus=$request->adha_bonus[$employee];
            } 

            if(isset($request->nights[$employee])){
                $nights=$request->nights[$employee];
            }
            
            $night_allowance=$nights*$allowance;
            
            if(isset($request->due[$employee])){
                $due=$request->due[$employee];
            }
            if(isset($request->advance[$employee])){
                $advance=$request->advance[$employee];
            }
            if(isset($request->mobilebill[$employee])){
                $mobilebill=$request->mobilebill[$employee];
            }
            if(isset($request->tax[$employee])){
                $tax=$request->tax[$employee];
            }
            if(isset($request->addition[$employee])){
                $addition=$request->addition[$employee];
            }
            if(isset($request->deduction[$employee])){
                $deduction=$request->deduction[$employee];
            }
            if(isset($request->pf[$employee])){
                $provident_fund=$request->pf[$employee];
            }
            if(isset($request->salary[$employee])){
                $salary=$request->salary[$employee];
            }
            if(isset($request->pa[$employee])){
                $pa=$request->pa[$employee];
            }

            //after installment start
            $month_num=substr($var['start_date'], 5, -3);
            $y=substr($var['start_date'], 0, 4);

            // return $y; exit();

            if(substr($month_num, 0,1) == 0){
                $month_id=substr($month_num,1);
            }else{
                $month_id=$month_num;
            }


            $is_loan = Loan::where('emp_id', $employee)
            ->where('flag', 1)
            ->first();
               
            if($is_loan ){
            $is_after_installment = LoanApprove::where('loan_id', $is_loan->id)
            ->where('after_installment', 1)->count();
            }else{
                 $is_after_installment = null;
            }

            if($is_loan ){
            $installment_con=LoanApprove::where('loan_id', $is_loan->id)
            ->where('after_installment', 0)
            // ->where('flag', 0)
            ->count();

            $after_installment_con=LoanApprove::where('loan_id', $is_loan->id)
            ->where('flag', 1)
            ->count();


            // return $b; exit();

            if($after_installment_con >= $installment_con){
                $entry = LoanApprove::insert([

                    'loan_id' => $is_loan->id,
                    'month'   => $month_id,
                    'year'    => $y,
                    'flag'    => 1,
                    'after_installment' => 1,
                    'paid_amount'       => $advance,
                    'monthly_installment' => 0,
                ]);
            }
        }
            //after installment end

            $PayrollSummery=PayrollSummery::insertGetId([
                'emp_id'=>$employee,
                'emp_type'=>$request->type,
                'payroll_date_from'=>$var['start_date'],
                'payroll_date_to'=>$var['end_date'],
                'pa'=>$pa,
                'nights'=>$nights,
                'night_allowance'=>$night_allowance,
                'due'=>$due,
                'b_bonus'=>$b_bonus,
                'fitr_bonus'=>$fitr_bonus,
                'adha_bonus'=>$adha_bonus,
                'advance'=>$advance,
                'mobilebill'=>$mobilebill,
                'tax'=>$tax,
                'original_tax'=>$original_tax,
                'provident_fund'=>$provident_fund,
                'addition'=>$addition,
                'deduction'=>$deduction,
                'salary'=>$salary,
                'generated_at'=>date('Y-m-d H:i:s'),
                'updated_at'=>date('Y-m-d H:i:s'),
                'updated_count'=>'1',
                'generated_by'=>$id->suser_empid,
                'updated_by'=>$id->suser_empid,
                'type'=>session()->get('type'),
            ]);

            if(isset($heads[0])){
                foreach ($heads as $key => $head) {
                    $amount=0;
                    if(isset($request->additionheads[$employee][$head->head_id])){
                        $amount=$request->additionheads[$employee][$head->head_id];
                    }elseif(isset($request->deductionheads[$employee][$head->head_id])){
                        $amount=$request->deductionheads[$employee][$head->head_id];
                    }
                    PayrollSummeryHeads::insert([
                        'ps_id'=>$PayrollSummery,
                        'head_id'=>$head->head_id,
                        'head_amount'=>$amount,
                    ]);
                }
            }

            if(isset($extends[0])){
                foreach ($extends as $key => $head) {
                    $qty=0;
                    $amount=0;
                    $basic=$request->basic[$employee];
                    $perDayBasic=$request->perDayBasic[$employee];
                    $perDaySalary=$request->perDaySalary[$employee];

                    $qty=0;
                    if(isset($request->headExtendsQuantity[$employee][$head->head_id])){
                        $qty=$request->headExtendsQuantity[$employee][$head->head_id];
                    }
                    // old
                    if ($head->head_id=="1"){
                        if($qty>0){
                            $qty=$qty/$head->head_unit_for_absent;
                        }
                        // if($head->head_percentage_status=="1"){
                        //     $absentAmount=($perDayBasic*$absent)*($head->head_percentage_for_basic/100);
                        // }elseif($head->head_percentage_status=="2"){
                        //     $absentAmount=($perDaySalary*$absent)*($head->head_percentage_for_total/100);
                        // }
                        if($qty<=10){
                            $amount=($perDayBasic*$qty)*($head->head_percentage_for_basic/100);
                        }elseif($qty>10){
                            $amount=($perDaySalary*$qty)*($head->head_percentage_for_total/100);
                        }
                    } elseif ($head->head_id=="2"){
                        $lateqty=$qty;
                        if($qty>0){
                            $lateqty=$qty/$head->head_unit_for_absent;
                        }
                        // if($head->head_percentage_status=="1"){
                        //     $lateAmount=($perDayBasic*$late)*($head->head_percentage_for_basic/100);
                        // }elseif($head->head_percentage_status=="2"){
                        //     $lateAmount=($perDaySalary*$late)*($head->head_percentage_for_total/100);
                        // }
                        if($lateqty<=10){
                            $amount=($perDayBasic*$lateqty)*($head->head_percentage_for_basic/100);
                        }elseif($lateqty>10){
                            $amount=($perDaySalary*$lateqty)*($head->head_percentage_for_total/100);
                        }
                    } elseif ($head->head_id=="3") {
                        $amount=$this->decimal(($basic/240)*$qty*2);
                        // if($head->head_percentage_status=="1"){
                        //     $amount=$this->decimal(($perDayBasic*($head->head_percentage_for_basic/100) ) * $qty * 2);
                        // }elseif($head->head_percentage_status=="2"){
                        //     $amount=$this->decimal(($perDayBasic*($head->head_percentage_for_total/100) ) * $qty * 2);
                        // }
                    }
                    //old

                    // if(isset($request->headExtendsQuantity[$employee][$head->head_id])){
                    //     $qty=$request->headExtendsQuantity[$employee][$head->head_id];
                    // }
                    // if(isset($request->additionheadExtendsAmount[$employee][$head->head_id])){
                    //     $amount=$request->additionheadExtendsAmount[$employee][$head->head_id];
                    // }elseif(isset($request->deductionheadExtendsAmount[$employee][$head->head_id])){
                    //     $amount=$request->deductionheadExtendsAmount[$employee][$head->head_id];
                    // }
                    PayrollSummeryExtends::insert([
                        'ps_id'=>$PayrollSummery,
                        'head_id'=>$head->head_id,
                        'head_quantity'=>$qty,
                        'head_amount'=>$amount,
                    ]);
                }
            }
        }

        session()->forget('monthWiseVar');
        session()->forget('type');
        session()->flash('success','Payroll saved successfully.');
        return redirect('view-generated-month-wise-payrolls');
    }

    public function contractualPaymentSave(Request $request)
    {
        // echo "string"; exit();
        // return $request; exit();
        $id =   Auth::guard('admin')->user();
        $var=session()->get('monthWiseVar');
        $heads=SalaryHead::where('head_status',1)->get();
        $extends=SalaryHeadExtends::where('head_status',1)->get();

        foreach ($request->employees as $key => $employee) {
            $emp=Employee::with(['type','type.currentNightShiftAllowance'])->find($employee);
            $allowance=0;
            if(isset($emp->type)){
                if(isset($emp->type->currentNightShiftAllowance)){
                    $allowance=$emp->type->currentNightShiftAllowance->allowance;
                }
            }
            $addition=0;
            $deduction=0;
            $provident_fund=0;
            $nights=0;
            $night_allowance=0;
            $due=0;
            $advance=0;
            $mobilebill=0;
            $tax=0;
            $original_tax=0;
            $salary=0;
            $pa=1;

            $b_bonus=0;
            $fitr_bonus=0;
            $adha_bonus=0;

            $basic=0;
            $gross=0;
            $conv=0;
            $net_paid=0;
            $target_percent=0;
            $targeted_income=0;
            $net_income=0;
            $percent_achivement=0;
            $balance=0;
            $other_deduction=0;
            $difference=0;

            $net_pay=0;
            $prev_cal=0;
            $net_pay_with_prev=0;
            $remarks=0;


            if(isset($request->basic[$employee])){
                $basic=$request->basic[$employee];
            } 
            if(isset($request->gross[$employee])){
                $gross=$request->gross[$employee];
            }
            if(isset($request->conv[$employee])){
                $conv=$request->conv[$employee];
            }
            if(isset($request->net_paid[$employee])){
                $net_paid=$request->net_paid[$employee];
            }
            if(isset($request->target_percent[$employee])){
                $target_percent=$request->target_percent[$employee];
            }
            if(isset($request->targeted_income[$employee])){
                $targeted_income=$request->targeted_income[$employee];
            }
            if(isset($request->net_income[$employee])){
                $net_income=$request->net_income[$employee];
            }
            if(isset($request->percent_achivement[$employee])){
                $percent_achivement=$request->percent_achivement[$employee];
            }
            if(isset($request->balance[$employee])){
                $balance=$request->balance[$employee];
            }
            if(isset($request->other_deduction[$employee])){
                $other_deduction=$request->other_deduction[$employee];
            }
            if(isset($request->net_pay[$employee])){
                $net_pay=$request->net_pay[$employee];
            }
            if(isset($request->prev_cal[$employee])){
                $prev_cal=$request->prev_cal[$employee];
            } 
            if(isset($request->net_pay_with_prev[$employee])){
                $net_pay_with_prev=$request->net_pay_with_prev[$employee];
            } 
            if(isset($request->remarks[$employee])){
                $remarks=$request->remarks[$employee];
            } 



            if(isset($request->original_tax[$employee])){
                $original_tax=$request->original_tax[$employee];
            } 
            if(isset($request->b_bonus[$employee])){
                $b_bonus=$request->b_bonus[$employee];
            }  
            if(isset($request->fitr_bonus[$employee])){
                $fitr_bonus=$request->fitr_bonus[$employee];
            } 
            if(isset($request->adha_bonus[$employee])){
                $adha_bonus=$request->adha_bonus[$employee];
            } 

            if(isset($request->nights[$employee])){
                $nights=$request->nights[$employee];
            }
            
            $night_allowance=$nights*$allowance;
            
            if(isset($request->due[$employee])){
                $due=$request->due[$employee];
            }
            if(isset($request->advance[$employee])){
                $advance=$request->advance[$employee];
            }
            if(isset($request->mobilebill[$employee])){
                $mobilebill=$request->mobilebill[$employee];
            }
            if(isset($request->tax[$employee])){
                $tax=$request->tax[$employee];
            }
            if(isset($request->addition[$employee])){
                $addition=$request->addition[$employee];
            }
            if(isset($request->deduction[$employee])){
                $deduction=$request->deduction[$employee];
            }
            if(isset($request->pf[$employee])){
                $provident_fund=$request->pf[$employee];
            }
            if(isset($request->salary[$employee])){
                $salary=$request->salary[$employee];
            }
            if(isset($request->pa[$employee])){
                $pa=$request->pa[$employee];
            }

            //after installment start
            $month_num=substr($var['start_date'], 5, -3);
            $y=substr($var['start_date'], 0, 4);

            // return $y; exit();

            if(substr($month_num, 0,1) == 0){
                $month_id=substr($month_num,1);
            }else{
                $month_id=$month_num;
            }


            $is_loan = Loan::where('emp_id', $employee)
            ->where('flag', 1)
            ->first();
               
            if($is_loan ){
            $is_after_installment = LoanApprove::where('loan_id', $is_loan->id)
            ->where('after_installment', 1)->count();
            }else{
                 $is_after_installment = null;
            }

            if($is_loan ){
            $installment_con=LoanApprove::where('loan_id', $is_loan->id)
            ->where('after_installment', 0)
            // ->where('flag', 0)
            ->count();

            $after_installment_con=LoanApprove::where('loan_id', $is_loan->id)
            ->where('flag', 1)
            ->count();


            // return $b; exit();

            // if($after_installment_con >= $installment_con){
            //     // $entry = LoanApprove::insert([

            //     //     'loan_id' => $is_loan->id,
            //     //     'month'   => $month_id,
            //     //     'year'    => $y,
            //     //     'flag'    => 1,
            //     //     'after_installment' => 1,
            //     //     'paid_amount'       => $advance,
            //     //     'monthly_installment' => 0,
            //     // ]);
            // }
        }
            //after installment end

            $PayrollSummery=ContractualPayroll::insertGetId([
                'emp_id'=>$employee,
                'emp_type'=>$request->type,
                'payroll_date_from'=>$var['start_date'],
                'payroll_date_to'=>$var['end_date'],
                // 'pa'=>$pa,
                // 'nights'=>$nights,
                // 'night_allowance'=>$night_allowance,
                // 'due'=>$due,
                // 'b_bonus'=>$b_bonus,
                // 'fitr_bonus'=>$fitr_bonus,
                // 'adha_bonus'=>$adha_bonus,
                'basic'=>$basic,
                'gross'=>$gross,
                'conv'=>$conv,
                'net_paid'=>$net_paid,
                'target_percent'=>$target_percent,
                'targeted_income'=>$targeted_income,
                'net_income'=>$net_income,
                'percent_achivement'=>$percent_achivement,
                'balance'=>$balance,
                'other_deduction'=>$other_deduction,

                
                'net_pay'=>$net_pay,
                'prev_cal'=>$prev_cal,
                'net_pay_with_prev'=>$net_pay_with_prev,
                'remarks'=>$remarks,



                'advance'=>$advance,
                'mobilebill'=>$mobilebill,
                'tax'=>$tax,
                'original_tax'=>$original_tax,
                'provident_fund'=>$provident_fund,
                // 'addition'=>$addition,
                // 'deduction'=>$deduction,
                // 'salary'=>$salary,
                'generated_at'=>date('Y-m-d H:i:s'),
                'updated_at'=>date('Y-m-d H:i:s'),
                'updated_count'=>'1',
                'generated_by'=>$id->suser_empid,
                'updated_by'=>$id->suser_empid,
                'type'=>session()->get('type'),
            ]);

            if(isset($heads[0])){
                foreach ($heads as $key => $head) {
                    $amount=0;
                    if(isset($request->additionheads[$employee][$head->head_id])){
                        $amount=$request->additionheads[$employee][$head->head_id];
                    }elseif(isset($request->deductionheads[$employee][$head->head_id])){
                        $amount=$request->deductionheads[$employee][$head->head_id];
                    }
                    // PayrollSummeryHeads::insert([
                    //     'ps_id'=>$PayrollSummery,
                    //     'head_id'=>$head->head_id,
                    //     'head_amount'=>$amount,
                    // ]);
                }
            }

            if(isset($extends[0])){
                foreach ($extends as $key => $head) {
                    $qty=0;
                    $amount=0;
                    $basic=$request->basic[$employee];
                    $perDayBasic=$request->perDayBasic[$employee];
                    $perDaySalary=$request->perDaySalary[$employee];

                    $qty=0;
                    if(isset($request->headExtendsQuantity[$employee][$head->head_id])){
                        $qty=$request->headExtendsQuantity[$employee][$head->head_id];
                    }
                    // old
                    if ($head->head_id=="1"){
                        if($qty>0){
                            $qty=$qty/$head->head_unit_for_absent;
                        }
                        // if($head->head_percentage_status=="1"){
                        //     $absentAmount=($perDayBasic*$absent)*($head->head_percentage_for_basic/100);
                        // }elseif($head->head_percentage_status=="2"){
                        //     $absentAmount=($perDaySalary*$absent)*($head->head_percentage_for_total/100);
                        // }
                        if($qty<=10){
                            $amount=($perDayBasic*$qty)*($head->head_percentage_for_basic/100);
                        }elseif($qty>10){
                            $amount=($perDaySalary*$qty)*($head->head_percentage_for_total/100);
                        }
                    } elseif ($head->head_id=="2"){
                        $lateqty=$qty;
                        if($qty>0){
                            $lateqty=$qty/$head->head_unit_for_absent;
                        }
                        // if($head->head_percentage_status=="1"){
                        //     $lateAmount=($perDayBasic*$late)*($head->head_percentage_for_basic/100);
                        // }elseif($head->head_percentage_status=="2"){
                        //     $lateAmount=($perDaySalary*$late)*($head->head_percentage_for_total/100);
                        // }
                        // if($lateqty<=10){
                        //     $amount=($perDayBasic*$lateqty)*($head->head_percentage_for_basic/100);
                        // }elseif($lateqty>10){
                        //     $amount=($perDaySalary*$lateqty)*($head->head_percentage_for_total/100);
                        // }
                    // } elseif ($head->head_id=="3") {
                    //     $amount=$this->decimal(($basic/240)*$qty*2);
                    //     // if($head->head_percentage_status=="1"){
                    //     //     $amount=$this->decimal(($perDayBasic*($head->head_percentage_for_basic/100) ) * $qty * 2);
                    //     // }elseif($head->head_percentage_status=="2"){
                    //     //     $amount=$this->decimal(($perDayBasic*($head->head_percentage_for_total/100) ) * $qty * 2);
                    //     // }
                    // }
                    //old

                    // if(isset($request->headExtendsQuantity[$employee][$head->head_id])){
                    //     $qty=$request->headExtendsQuantity[$employee][$head->head_id];
                    // }
                    // if(isset($request->additionheadExtendsAmount[$employee][$head->head_id])){
                    //     $amount=$request->additionheadExtendsAmount[$employee][$head->head_id];
                    // }elseif(isset($request->deductionheadExtendsAmount[$employee][$head->head_id])){
                    //     $amount=$request->deductionheadExtendsAmount[$employee][$head->head_id];
                    // }
                    // PayrollSummeryExtends::insert([
                    //     'ps_id'=>$PayrollSummery,
                    //     'head_id'=>$head->head_id,
                    //     'head_quantity'=>$qty,
                    //     'head_amount'=>$amount,
                    // ]);
                }
            }
        }
    }

        session()->forget('monthWiseVar');
        session()->forget('type');
        session()->flash('success','Contractual Payroll saved successfully.');
        return redirect('generate-contractual-payroll');
    }

    public function monthWiseMobilebillSave(Request $request)
    {
        // return $request->all(); exit();
        $id =   Auth::guard('admin')->user();
        $var=session()->get('monthWiseVar');
        $heads=SalaryHead::where('head_status',1)->get();
        $extends=SalaryHeadExtends::where('head_status',1)->get();

        foreach ($request->employees as $key => $employee) {
            
            
            
            $month=0;
            $mobile_bill=0;
            
            
            if(isset($request->month)){
                $month=$request->month;
            }
            if(isset($request->Mobilebill[$employee])){
                $mobile_bill=$request->Mobilebill[$employee];
            }

            $mobilebill=Mobilebill::insertGetId([
                'emp_id'=>$employee,
                'month'=>$month,
                'mobile_bill'=>$mobile_bill,
            
            ]);

            

            
        }

        session()->forget('monthWiseVar');
        session()->forget('type');
        session()->flash('success','Mobile Bill saved successfully.');
        return redirect('generate-month-wise-mobile-bill');
    }

    public function monthWiseTaxChallanSave(Request $request)
    {
        // echo "string"; exit();
        // return $request->year; exit();
        $id =   Auth::guard('admin')->user();
        $var=session()->get('monthWiseVar');
        $heads=SalaryHead::where('head_status',1)->get();
        $extends=SalaryHeadExtends::where('head_status',1)->get();

        foreach ($request->employees as $key => $employee) {
            
            
            
            $month=0;
            $year=0;
            $mobile_bill=0;
            
            
            if(isset($request->month)){
                $month=$request->month;
            }
            if(isset($request->year)){
                $year=$request->year;
            }
            if(isset($request->Mobilebill[$employee])){
                $mobile_bill=$request->Mobilebill[$employee];
            }

            $mobilebill=Taxchallan::insertGetId([
                'emp_id'=>$employee,
                'month'=>$month,
                'year'=>$year,
                'challan_no'=>$mobile_bill,
            
            ]);

            

            
        }

        session()->forget('monthWiseVar');
        session()->forget('type');
        session()->flash('success','Tax Challan saved successfully.');
        return redirect('tax-report-challan');
    }

     public function monthWiseBonusSave(Request $request)
    {
        // return $request->b_bonus; exit();
        // echo "string"; exit();
        $id =   Auth::guard('admin')->user();
        $var=session()->get('monthWiseVar');
        $heads=SalaryHead::where('head_status',1)->get();
        $extends=SalaryHeadExtends::where('head_status',1)->get();

        foreach ($request->employees as $key => $employee) {
            
            
            
            $month=0;
            $b_bonus=0;
            $fitr_bonus=0;
            $adha_bonus=0;
            
            
            if(isset($request->month)){
                $month=$request->month;
            }
            if(isset($request->b_bonus[$employee])){
                $b_bonus=$request->b_bonus[$employee];
            }
            if(isset($request->fitr_bonus[$employee])){
                $fitr_bonus=$request->fitr_bonus[$employee];
            }
            if(isset($request->adha_bonus[$employee])){
                $adha_bonus=$request->adha_bonus[$employee];
            }

            $mobilebill=Bonus::insertGetId([
                'emp_id'=>$employee,
                'month'=>$month,
                'b_bonus'=>$b_bonus,
                'fitr_bonus'=>$fitr_bonus,
                'adha_bonus'=>$adha_bonus,
            
            ]);

            

            
        }

        session()->forget('monthWiseVar');
        session()->forget('type');
        session()->flash('success','Bonus saved successfully.');
        return redirect('generate-month-wise-bonus');
    }

    public function mobile_bill_report()
    {
        $id =   Auth::guard('admin')->user();
        $mainlink = $this->adminmainmenu();
        $sublink = $this->adminsubmenu();
        $Adminminlink = $this->adminlink();
        $adminsublink = $this->adminsublink();
        $months=Month::get();
        $employee_types = EmployeeTypes::orderBy('id', 'ASC')
                            ->get();
        $month=date('m');
        return view('Admin.payroll.monthWise.mobilebill_report',compact('id','mainlink','sublink','Adminminlink','adminsublink','months','month', 'employee_types'));
    }

     public function bonus_report()
    {
        // echo "string"; exit();
        $id =   Auth::guard('admin')->user();
        $mainlink = $this->adminmainmenu();
        $sublink = $this->adminsubmenu();
        $Adminminlink = $this->adminlink();
        $adminsublink = $this->adminsublink();
        $months=Month::get();
        $employee_types = EmployeeTypes::orderBy('id', 'ASC')
                            ->get();
        $month=date('m');
        return view('Admin.payroll.monthWise.bonus_report',compact('id','mainlink','sublink','Adminminlink','adminsublink','months','month', 'employee_types'));
    }

    public function monthWiseDeletePayroll($data)
    {
        $id =   Auth::guard('admin')->user();
        $mainlink = $this->adminmainmenu();
        $sublink = $this->adminsubmenu();
        $Adminminlink = $this->adminlink();
        $adminsublink = $this->adminsublink();

        $employee='';
        $emp_id=explode('&',$data)[4];
        if($emp_id!="0"){
            $employee=$this->emp_name($emp_id).' ('.$this->emp_empid($emp_id).')';
        }

        return view('Admin.payroll.monthWise.delete',compact('id','mainlink','sublink','Adminminlink','adminsublink','data','employee'));
    }

    public function monthWiseDeletePayrollSubmit($data)
    {
        $mon = explode('&',$data)[1];
        $month= substr($mon, 5, -3);

        // return $month; exit();

        if(substr($mon, 5, -4) == 0){
            $delete_mon = substr($mon, 6, -3);
        }else{
            $delete_mon = $month;
        }

        // return $delete_mon; exit();

        $id = explode('&',$data)[4];

        $loan_delete= Loan::where('emp_id', $id)
        ->where('flag', 1)->first();

        if($loan_delete){
        $loanapprove_delete = LoanApprove::where('loan_id', $loan_delete->id)
        ->where('after_installment', 1)
        ->where('month', $delete_mon)
        ->first();

        if($loanapprove_delete){
            $del = LoanApprove::where([
                'loan_id' => $loan_delete->id,
                'month'   => $delete_mon,
            ])
            ->delete();
        }

        }

        $delete=PayrollSummery::where([
                'payroll_date_from'=>explode('&',$data)[1],
                'payroll_date_to'=>explode('&',$data)[2],
                'emp_type'=>explode('&',$data)[3],
                'type'=>30,
            ])
            ->when(explode('&',$data)[4]!="0",function($query) use($data){
                return $query->where('emp_id',explode('&',$data)[4]);
            })
            ->delete();
        if($delete){
            session()->flash('success','Previously generated payroll from '.explode('&',$data)[1].' to '.explode('&',$data)[2].' of '.$this->emp_name($data[4]).' has been removed. Now you can regenerate it.');
            return redirect('generate-month-wise-payroll');
        }else{
            session()->flash('error','Something went wrong! Try again.');
            return redirect('generate-month-wise-payroll/'.$data.'/delete');
        }

    }

    public function monthWisePaySlip($data)
    {
        $emp_id=explode('&',$data)[0];
        $payroll_date_from=explode('&',$data)[1];
        $payroll_date_to=explode('&',$data)[2];
        $employee=Employee::with(['designation','department'])->find($emp_id);
        $PayrollSummery=PayrollSummery::with(['heads','heads.head','extends','extends.head'])->where([
            'emp_id'=>$emp_id,
            'payroll_date_from'=>$payroll_date_from,
            'payroll_date_to'=>$payroll_date_to,
        ])
        ->first();
        // return $PayrollSummery; exit();
        $ProvidentSetup=$this->ProvidentSetup();
        $projectDetails=ProjectDetails::find('1');
        return view('Admin.payroll.monthWise.payslip',compact('employee','payroll_date_from','payroll_date_to','PayrollSummery','payroll','salaryDays','projectDetails','ProvidentSetup'));
    }

    public function contractualPaySlip($data)
    {
        // echo "string"; exit();
        $emp_id=explode('&',$data)[0];
        $payroll_date_from=explode('&',$data)[1];
        $payroll_date_to=explode('&',$data)[2];
        $employee=Employee::with(['designation','department'])->find($emp_id);
        $PayrollSummery=ContractualPayroll::with(['heads','heads.head','extends','extends.head'])->where([
            'emp_id'=>$emp_id,
            'payroll_date_from'=>$payroll_date_from,
            'payroll_date_to'=>$payroll_date_to,
        ])
        ->first();
        // return $PayrollSummery; exit();
        $ProvidentSetup=$this->ProvidentSetup();
        $projectDetails=ProjectDetails::find('1');
        return view('Admin.payroll.monthWise.contractual_payslip',compact('employee','payroll_date_from','payroll_date_to','PayrollSummery','payroll','salaryDays','projectDetails','ProvidentSetup'));
    }

    public function monthWiseViewGeneratedPayrolls()
    {
        $id =   Auth::guard('admin')->user();
        $mainlink = $this->adminmainmenu();
        $sublink = $this->adminsubmenu();
        $Adminminlink = $this->adminlink();
        $adminsublink = $this->adminsublink();
        $types=EmployeeTypes::where('status',1)->get();
        $PayrollSummery=false;
        $months=Month::get();
        $month=date('m');
        return view('Admin.payroll.monthWise.payrollsummery',compact('id','mainlink','sublink','Adminminlink','adminsublink','PayrollSummery','months','types','month'));
    }

    public function viewContractualPayrolls()
    {
        $id =   Auth::guard('admin')->user();
        $mainlink = $this->adminmainmenu();
        $sublink = $this->adminsubmenu();
        $Adminminlink = $this->adminlink();
        $adminsublink = $this->adminsublink();
        $types=EmployeeTypes::where('status',1)->get();
        $PayrollSummery=false;
        $months=Month::get();
        $month=date('m');
        return view('Admin.payroll.monthWise.view_contractual_payroll',compact('id','mainlink','sublink','Adminminlink','adminsublink','PayrollSummery','months','types','month'));
    }

    public function payrollsummery_print()
    {
        $id =   Auth::guard('admin')->user();
        $mainlink = $this->adminmainmenu();
        $sublink = $this->adminsubmenu();
        $Adminminlink = $this->adminlink();
        $adminsublink = $this->adminsublink();
        $types=EmployeeTypes::where('status',1)->get();
        $PayrollSummery=false;
        $months=Month::get();
        $month=date('m');
        return view('Admin.payroll.monthWise.payrollsummery_print',compact('id','mainlink','sublink','Adminminlink','adminsublink','PayrollSummery','months','types','month'));
    }


    public function tax_report()
    {
        $id =   Auth::guard('admin')->user();
        $mainlink = $this->adminmainmenu();
        $sublink = $this->adminsubmenu();
        $Adminminlink = $this->adminlink();
        $adminsublink = $this->adminsublink();
        $types=EmployeeTypes::where('status',1)->get();
        $PayrollSummery=false;
        $months=Month::get();
        $month=date('m');
        return view('Admin.payroll.monthWise.tax',compact('id','mainlink','sublink','Adminminlink','adminsublink','PayrollSummery','months','types','month'));
    }
    public function monthWiseViewGeneratedTaxSubmit(Request $request)
    {
        // return $request; exit();
        $type_emp=$request->type;
        $empl=$request->emp_id;
        $mon_emp=$request->month;
        $year_emp=$request->year;


        $id =   Auth::guard('admin')->user();
        $mainlink = $this->adminmainmenu();
        $sublink = $this->adminsubmenu();
        $Adminminlink = $this->adminlink();
        $adminsublink = $this->adminsublink();
        $types=EmployeeTypes::where('status',1)->get();
        $ProvidentSetup=$this->ProvidentSetup();
        $var=$this->inputData($request,30);
        $start_date=$var['start_date'];
        $end_date=$var['end_date'];
        $page_title=$this->getPayrollTitle($var,'Month Wise Generated Income Tax',30);
        $PayrollSummery=PayrollSummery::with(['employee','employee.designation','heads','heads.head','extends','extends.head'])
        ->where([
            ['payroll_date_from','<=',$start_date],
            ['payroll_date_to','>=',$end_date],
        ])
        ->when($id->suser_level!="1", function($query) use($id){
            return $query->where(function($query) use($id){
                $query->where('emp_id', $id->suser_empid);
            });
        })
        ->where([
            'emp_type'=> $request->type,
            'type'=>30
        ])
        ->whereNotIn('emp_id',[116])
        ->when($var['emp_id']!="0",function($query) use($var){
            return $query->where('emp_id',$var['emp_id']);
        })
        ->get();

        $heads=array();
        $gross_amount=array();
        $extendsqty=array();
        $extendsamount=array();

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
                    if($value->head->head_id<5){
                        $gross_amount[$payroll->emp_id]+=$value->head_amount;
                    }
                }else{
                    $heads[$value->head->head_id]+=($value->head_amount*$payroll->pa);
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

        $payrollmonth=Month::find((int)(explode('-',$start_date)[1]));
        $months=Month::get();
        $type=$request->type;
        $month=$request->month;
        $year=$request->year;
        if($month>1){
            $prevmonth=$month-1;
            $prevyear=$year;
        }else{
            $prevmonth=12;
            $prevyear=$year-1;
        }
        if($prevmonth<10){
            $prevmonth='0'.$prevmonth;
        }
        $header = array(
            'type' => $this->getTypeName($type), 
            'prevmonth' => date('F',strtotime($prevyear.'-'.$prevmonth)),
            'prevyear' => $prevyear,
            'month' => date('F',strtotime($month)),
            'year' => $year
        );
        $emp=Employee::where('emp_id', 1963)->first();

        

         $tax=$this->taxCalculaTion('450000',$emp);

         $a=$tax/12;

         // return $PayrollSummery; exit();

        return view('Admin.payroll.monthWise.tax',compact('id','mainlink','sublink','Adminminlink','adminsublink','page_title','PayrollSummery','ProvidentSetup','payrollmonth','months','month','year','types','type','heads','gross_amount','extendsqty','extendsamount','header', 'type_emp', 'empl', 'mon_emp', 'year_emp','a'));
    }

     public function tax_print(Request $request)
    {
        // return $request; exit();
        $type_emp=$request->type;
        $empl=$request->emp_id;
        $mon_emp=$request->month;
        $year_emp=$request->year;


        $id =   Auth::guard('admin')->user();
        $mainlink = $this->adminmainmenu();
        $sublink = $this->adminsubmenu();
        $Adminminlink = $this->adminlink();
        $adminsublink = $this->adminsublink();
        $types=EmployeeTypes::where('status',1)->get();
        $ProvidentSetup=$this->ProvidentSetup();
        $var=$this->inputData($request,30);
        $start_date=$var['start_date'];
        $end_date=$var['end_date'];
        $page_title=$this->getPayrollTitle($var,'Month Wise Generated Income Tax',30);
        $PayrollSummery=PayrollSummery::with(['employee','employee.designation','heads','heads.head','extends','extends.head'])
        ->where([
            ['payroll_date_from','<=',$start_date],
            ['payroll_date_to','>=',$end_date],
        ])
        ->when($id->suser_level!="1", function($query) use($id){
            return $query->where(function($query) use($id){
                $query->where('emp_id', $id->suser_empid);
            });
        })
        ->where([
            'emp_type'=> $request->type,
            'type'=>30
        ])
        ->whereNotIn('emp_id',[116])
        ->when($var['emp_id']!="0",function($query) use($var){
            return $query->where('emp_id',$var['emp_id']);
        })
        ->get();

        $heads=array();
        $gross_amount=array();
        $extendsqty=array();
        $extendsamount=array();

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
                    if($value->head->head_id<5){
                        $gross_amount[$payroll->emp_id]+=$value->head_amount;
                    }
                }else{
                    $heads[$value->head->head_id]+=($value->head_amount*$payroll->pa);
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

        $payrollmonth=Month::find((int)(explode('-',$start_date)[1]));
        $months=Month::get();
        $type=$request->type;
        $month=$request->month;
        $year=$request->year;
        if($month>1){
            $prevmonth=$month-1;
            $prevyear=$year;
        }else{
            $prevmonth=12;
            $prevyear=$year-1;
        }
        if($prevmonth<10){
            $prevmonth='0'.$prevmonth;
        }
        $header = array(
            'type' => $this->getTypeName($type), 
            'prevmonth' => date('F',strtotime($prevyear.'-'.$prevmonth)),
            'prevyear' => $prevyear,
            'month' => date('F',strtotime($month)),
            'year' => $year
        );

        return view('Admin.payroll.monthWise.tax_print',compact('id','mainlink','sublink','Adminminlink','adminsublink','page_title','PayrollSummery','ProvidentSetup','payrollmonth','months','month','year','types','type','heads','gross_amount','extendsqty','extendsamount','header', 'type_emp', 'empl', 'mon_emp', 'year_emp'));
    }

    public function monthWiseViewGeneratedPayrollsSubmit(Request $request)
    {
        // return $request; exit();
        $emp=$request->emp_id;

        $id =   Auth::guard('admin')->user();
        $mainlink = $this->adminmainmenu();
        $sublink = $this->adminsubmenu();
        $Adminminlink = $this->adminlink();
        $adminsublink = $this->adminsublink();
        $types=EmployeeTypes::where('status',1)->get();
        $ProvidentSetup=$this->ProvidentSetup();
        $var=$this->inputData($request,30);
        $start_date=$var['start_date'];
        $end_date=$var['end_date'];
        $page_title=$this->getPayrollTitle($var,'Month Wise Generated Payroll Summery',30);
        $PayrollSummery=PayrollSummery::with(['employee','employee.designation','heads','heads.head','extends','extends.head'])
        ->where([
            ['payroll_date_from','<=',$start_date],
            ['payroll_date_to','>=',$end_date],
        ])
        ->when($id->suser_level!="1", function($query) use($id){
            return $query->where(function($query) use($id){
                $query->where('emp_id', $id->suser_empid);
            });
        })
        ->where([
            'emp_type'=> $request->type,
            'type'=>30
        ])
        ->whereNotIn('emp_id',[116])
        ->when($var['emp_id']!="0",function($query) use($var){
            return $query->where('emp_id',$var['emp_id']);
        })
        ->get();

        $heads=array();
        $gross_amount=array();
        $extendsqty=array();
        $extendsamount=array();

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
                    if($value->head->head_id<5){
                        $gross_amount[$payroll->emp_id]+=$value->head_amount;
                    }
                }else{
                    $heads[$value->head->head_id]+=($value->head_amount*$payroll->pa);
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

        $payrollmonth=Month::find((int)(explode('-',$start_date)[1]));
        $months=Month::get();
        $type=$request->type;
        $month=$request->month;
        $year=$request->year;
        if($month>1){
            $prevmonth=$month-1;
            $prevyear=$year;
        }else{
            $prevmonth=12;
            $prevyear=$year-1;
        }
        if($prevmonth<10){
            $prevmonth='0'.$prevmonth;
        }
        $header = array(
            'type' => $this->getTypeName($type), 
            'prevmonth' => date('F',strtotime($prevyear.'-'.$prevmonth)),
            'prevyear' => $prevyear,
            'month' => date('F',strtotime($month)),
            'year' => $year
        );

        return view('Admin.payroll.monthWise.payrollsummery',compact('id','mainlink','sublink','Adminminlink','adminsublink','page_title','PayrollSummery','ProvidentSetup','payrollmonth','months','month','year','types','type','heads','gross_amount','extendsqty','extendsamount','header', 'emp'));
    }


    public function contractualPayrollsSubmit(Request $request)
    {
        // echo "string"; exit();
        // return $request; exit();
        $emp=$request->emp_id;

        $id =   Auth::guard('admin')->user();
        $mainlink = $this->adminmainmenu();
        $sublink = $this->adminsubmenu();
        $Adminminlink = $this->adminlink();
        $adminsublink = $this->adminsublink();
        $types=EmployeeTypes::where('status',1)->get();
        $ProvidentSetup=$this->ProvidentSetup();
        $var=$this->inputData($request,30);
        $start_date=$var['start_date'];
        $end_date=$var['end_date'];
        $page_title=$this->getPayrollTitle($var,'Month Wise Generated Payroll Summery',30);
        $PayrollSummery=ContractualPayroll::with(['employee','employee.designation','heads','heads.head','extends','extends.head'])
        ->where([
            ['payroll_date_from','<=',$start_date],
            ['payroll_date_to','>=',$end_date],
        ])
        ->when($id->suser_level!="1", function($query) use($id){
            return $query->where(function($query) use($id){
                $query->where('emp_id', $id->suser_empid);
            });
        })
        ->where([
            'emp_type'=> $request->type,
            'type'=>30
        ])
        ->whereNotIn('emp_id',[116])
        ->when($var['emp_id']!="0",function($query) use($var){
            return $query->where('emp_id',$var['emp_id']);
        })
        ->get();

        // return $PayrollSummery; exit();

        $heads=array();
        $gross_amount=array();
        $extendsqty=array();
        $extendsamount=array();

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
                    if($value->head->head_id<5){
                        $gross_amount[$payroll->emp_id]+=$value->head_amount;
                    }
                }else{
                    $heads[$value->head->head_id]+=($value->head_amount*$payroll->pa);
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

        $payrollmonth=Month::find((int)(explode('-',$start_date)[1]));
        $months=Month::get();
        $type=$request->type;
        $month=$request->month;
        $year=$request->year;
        if($month>1){
            $prevmonth=$month-1;
            $prevyear=$year;
        }else{
            $prevmonth=12;
            $prevyear=$year-1;
        }
        if($prevmonth<10){
            $prevmonth='0'.$prevmonth;
        }
        $header = array(
            'type' => $this->getTypeName($type), 
            'prevmonth' => date('F',strtotime($prevyear.'-'.$prevmonth)),
            'prevyear' => $prevyear,
            'month' => date('F',strtotime($month)),
            'year' => $year
        );

        return view('Admin.payroll.monthWise.view_contractual_payroll',compact('id','mainlink','sublink','Adminminlink','adminsublink','page_title','PayrollSummery','ProvidentSetup','payrollmonth','months','month','year','types','type','heads','gross_amount','extendsqty','extendsamount','header', 'emp'));
    }

    public function payrollsummery_print_submit(Request $request)
    {
        // return $request; exit();
        $id =   Auth::guard('admin')->user();
        $mainlink = $this->adminmainmenu();
        $sublink = $this->adminsubmenu();
        $Adminminlink = $this->adminlink();
        $adminsublink = $this->adminsublink();
        $types=EmployeeTypes::where('status',1)->get();
        $ProvidentSetup=$this->ProvidentSetup();
        $var=$this->inputData($request,30);
        $start_date=$var['start_date'];
        $end_date=$var['end_date'];
        $page_title=$this->getPayrollTitle($var,'Month Wise Generated Payroll Summery',30);
        $PayrollSummery=PayrollSummery::with(['employee','employee.designation','heads','heads.head','extends','extends.head'])
        ->where([
            ['payroll_date_from','<=',$start_date],
            ['payroll_date_to','>=',$end_date],
        ])
        ->when($id->suser_level!="1", function($query) use($id){
            return $query->where(function($query) use($id){
                $query->where('emp_id', $id->suser_empid);
            });
        })
        ->where([
            'emp_type'=> $request->type,
            'type'=>30
        ])
        ->whereNotIn('emp_id',[116])
        ->when($var['emp_id']!="0",function($query) use($var){
            return $query->where('emp_id',$var['emp_id']);
        })
        ->get();

        $heads=array();
        $gross_amount=array();
        $extendsqty=array();
        $extendsamount=array();

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
                    if($value->head->head_id<5){
                        $gross_amount[$payroll->emp_id]+=$value->head_amount;
                    }
                }else{
                    $heads[$value->head->head_id]+=($value->head_amount*$payroll->pa);
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

        $payrollmonth=Month::find((int)(explode('-',$start_date)[1]));
        $months=Month::get();
        $type=$request->type;
        $month=$request->month;
        $year=$request->year;
        if($month>1){
            $prevmonth=$month-1;
            $prevyear=$year;
        }else{
            $prevmonth=12;
            $prevyear=$year-1;
        }
        if($prevmonth<10){
            $prevmonth='0'.$prevmonth;
        }
        $header = array(
            'type' => $this->getTypeName($type), 
            'prevmonth' => date('F',strtotime($prevyear.'-'.$prevmonth)),
            'prevyear' => $prevyear,
            'month' => date('F',strtotime($month)),
            'year' => $year
        );

        return view('Admin.payroll.monthWise.payrollsummery_print',compact('id','mainlink','sublink','Adminminlink','adminsublink','page_title','PayrollSummery','ProvidentSetup','payrollmonth','months','month','year','types','type','heads','gross_amount','extendsqty','extendsamount','header'));
    }

    public function bankCashPaymentsReport()
    {
        // $id =   Auth::guard('admin')->user();
        // $mainlink = $this->adminmainmenu();
        // $sublink = $this->adminsubmenu();
        // $Adminminlink = $this->adminlink();
        // $adminsublink = $this->adminsublink();
        // $months=Month::get();
        // $employee_types = EmployeeTypes::orderBy('id', 'ASC')
        //                     ->get();
        // $month=date('m');
        // return view('Admin.payroll.monthWise.getReport',compact('id','mainlink','sublink','Adminminlink','adminsublink','months','month', 'employee_types'));
         $id =   Auth::guard('admin')->user();
        $mainlink = $this->adminmainmenu();
        $sublink = $this->adminsubmenu();
        $Adminminlink = $this->adminlink();
        $adminsublink = $this->adminsublink();
        $types=EmployeeTypes::where('status',1)->get();
        $PayrollSummery=false;
        $months=Month::get();
        $month=date('m');
        return view('Admin.payroll.monthWise.bank_report',compact('id','mainlink','sublink','Adminminlink','adminsublink','PayrollSummery','months','types','month'));
    }

    // public function tax_report()
    // {
    //     $id =   Auth::guard('admin')->user();
    //     $mainlink = $this->adminmainmenu();
    //     $sublink = $this->adminsubmenu();
    //     $Adminminlink = $this->adminlink();
    //     $adminsublink = $this->adminsublink();
    //     $months=Month::get();
    //     $employee_types = EmployeeTypes::orderBy('id', 'ASC')
    //                         ->get();
    //     $month=date('m');
    //     return view('Admin.report.tax_report',compact('id','mainlink','sublink','Adminminlink','adminsublink','months','month', 'employee_types'));
    // }

    public function getBankPaymentsReport(Request $request)
    {
        // return $request; exit();
        $type_emp=$request->type;
        $empl=$request->emp_id;
        $mon_emp=$request->month;
        $year_emp=$request->year;
        $report_emp=$request->report;

        $id =   Auth::guard('admin')->user();
        $mainlink = $this->adminmainmenu();
        $sublink = $this->adminsubmenu();
        $Adminminlink = $this->adminlink();
        $adminsublink = $this->adminsublink();
        $types=EmployeeTypes::where('status',1)->get();
        $ProvidentSetup=$this->ProvidentSetup();
        $var=$this->inputData($request,30);
        $start_date=$var['start_date'];
        $end_date=$var['end_date'];
        $page_title=$this->getPayrollTitle($var,'Month Wise Bank & Cash Summery',30);
        $PayrollSummery=PayrollSummery::with(['employee','employee.designation','heads','heads.head','extends','extends.head'])
        ->where([
            ['payroll_date_from','<=',$start_date],
            ['payroll_date_to','>=',$end_date],
        ])
        ->when($id->suser_level!="1", function($query) use($id){
            return $query->where(function($query) use($id){
                $query->where('emp_id', $id->suser_empid);
            });
        })
        ->where([
            'emp_type'=> $request->type,
            'type'=>30
        ])
        ->whereNotIn('emp_id',[116])
        ->when($var['emp_id']!="0",function($query) use($var){
            return $query->where('emp_id',$var['emp_id']);
        })
        ->get();

        $heads=array();
        $gross_amount=array();
        $extendsqty=array();
        $extendsamount=array();

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
                    if($value->head->head_id<5){
                        $gross_amount[$payroll->emp_id]+=$value->head_amount;
                    }
                }else{
                    $heads[$value->head->head_id]+=($value->head_amount*$payroll->pa);
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

        $payrollmonth=Month::find((int)(explode('-',$start_date)[1]));
        $months=Month::get();
        $type=$request->type;
        $month=$request->month;
        $year=$request->year;
        if($month>1){
            $prevmonth=$month-1;
            $prevyear=$year;
        }else{
            $prevmonth=12;
            $prevyear=$year-1;
        }
        if($prevmonth<10){
            $prevmonth='0'.$prevmonth;
        }
        $header = array(
            'type' => $this->getTypeName($type), 
            'prevmonth' => date('F',strtotime($prevyear.'-'.$prevmonth)),
            'prevyear' => $prevyear,
            'month' => date('F',strtotime($month)),
            'year' => $year
        );

        $report=$request->report;

        return view('Admin.payroll.monthWise.bank_summery',compact('id','mainlink','sublink','Adminminlink','adminsublink','page_title','PayrollSummery','ProvidentSetup','payrollmonth','months','month','year','types','type','heads','gross_amount','extendsqty','extendsamount','header', 'report', 'type_emp', 'empl', 'mon_emp', 'year_emp', 'report_emp'));
    }

    public function bank_cash_print(Request $request)
    {
        // return $request; exit();
        $type_emp=$request->type;
        $empl=$request->emp_id;
        $mon_emp=$request->month;
        $year_emp=$request->year;
        $report_emp=$request->report;


        $id =   Auth::guard('admin')->user();
        $mainlink = $this->adminmainmenu();
        $sublink = $this->adminsubmenu();
        $Adminminlink = $this->adminlink();
        $adminsublink = $this->adminsublink();
        $types=EmployeeTypes::where('status',1)->get();
        $ProvidentSetup=$this->ProvidentSetup();
        $var=$this->inputData($request,30);
        $start_date=$var['start_date'];
        $end_date=$var['end_date'];
        $page_title=$this->getPayrollTitle($var,'Month Wise Bank & Cash Summery',30);
        $PayrollSummery=PayrollSummery::with(['employee','employee.designation','heads','heads.head','extends','extends.head'])
        ->where([
            ['payroll_date_from','<=',$start_date],
            ['payroll_date_to','>=',$end_date],
        ])
        ->when($id->suser_level!="1", function($query) use($id){
            return $query->where(function($query) use($id){
                $query->where('emp_id', $id->suser_empid);
            });
        })
        ->where([
            'emp_type'=> $request->type,
            'type'=>30
        ])
        ->whereNotIn('emp_id',[116])
        ->when($var['emp_id']!="0",function($query) use($var){
            return $query->where('emp_id',$var['emp_id']);
        })
        ->get();

        $heads=array();
        $gross_amount=array();
        $extendsqty=array();
        $extendsamount=array();

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
                    if($value->head->head_id<5){
                        $gross_amount[$payroll->emp_id]+=$value->head_amount;
                    }
                }else{
                    $heads[$value->head->head_id]+=($value->head_amount*$payroll->pa);
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

        $payrollmonth=Month::find((int)(explode('-',$start_date)[1]));
        $months=Month::get();
        $type=$request->type;
        $month=$request->month;
        $year=$request->year;
        if($month>1){
            $prevmonth=$month-1;
            $prevyear=$year;
        }else{
            $prevmonth=12;
            $prevyear=$year-1;
        }
        if($prevmonth<10){
            $prevmonth='0'.$prevmonth;
        }
        $header = array(
            'type' => $this->getTypeName($type), 
            'prevmonth' => date('F',strtotime($prevyear.'-'.$prevmonth)),
            'prevyear' => $prevyear,
            'month' => date('F',strtotime($month)),
            'year' => $year
        );

        $report=$request->report;

        return view('Admin.payroll.monthWise.bank_cash_print',compact('id','mainlink','sublink','Adminminlink','adminsublink','page_title','PayrollSummery','ProvidentSetup','payrollmonth','months','month','year','types','type','heads','gross_amount','extendsqty','extendsamount','header', 'report' , 'type_emp', 'empl', 'mon_emp', 'year_emp', 'report_emp'));
    }


    public function getBankCashPaymentsReport($type,$report,$month,$year)
    {
        $id =   Auth::guard('admin')->user();
        $payrollmonth=Month::find($month);
        $ProvidentSetup=$this->ProvidentSetup();
        $start_date=$year.'-'.$month.'-01';
        $end_date=$year.'-'.$month.'-'.$payrollmonth->days;

        $type_array = array();
        $typename='';
        // if($type=="1"){
        //     $type_array = array('6','8');
        //     $typename='Head Office Officers and Staffs';
        // }elseif($type=="0"){
        //     $type_array = array('1','2','3','4','5');
        //     $typename='Factory Officers and Staffs';
        // }
        if($type=="9"){
            $type_array = array('9');
            // $typename='Head Office Officers and Staffs';
        }elseif($type=="10"){
            $type_array = array('10');
            // $typename='Factory Officers and Staffs';
        }
        elseif($type=="11"){
            $type_array = array('11');
            // $typename='Factory Officers and Staffs';
        }
        elseif($type=="12"){
            $type_array = array('12');
            // $typename='Factory Officers and Staffs';
        }
        elseif($type=="13"){
            $type_array = array('13');
            // $typename='Factory Officers and Staffs';
        }
        elseif($type=="14"){
            $type_array = array('14');
            // $typename='Factory Officers and Staffs';
        }

        $employees = array();
        if($report=="1"){
            $search=Salary::get(['emp_id','bank_account']);
            if(isset($search[0])){
                foreach ($search as $key => $emp) {
                    if(strlen($emp->bank_account)>1){
                        array_push($employees,$emp->emp_id);
                    }
                }
            }
        }else{
            $search=Salary::get(['emp_id','bank_account']);
            if(isset($search[0])){
                foreach ($search as $key => $emp) {
                    if(strlen($emp->bank_account)<1){
                        array_push($employees,$emp->emp_id);
                    }
                }
            }
        }


        $PayrollSummery=PayrollSummery::with(['employee','employee.designation','employee.salary','heads','heads.head','extends','extends.head'])
        ->where([
            ['payroll_date_from','<=',$start_date],
            ['payroll_date_to','>=',$end_date],
        ])
        // ->when($id->suser_level!="1", function($query) use($id){
        //     return $query->where(function($query) use($id){
        //         $query->where('emp_id', $id->suser_empid);
        //     });
        // })
        ->when(count($employees), function($query) use($employees){
            return $query->whereIn('emp_id',$employees);
        })
        ->where([
            'type'=>30
        ])
        ->whereIn('emp_type',$type_array)
        ->get();


        

        



        // return $bank_payment; exit();
        return view('Admin.payroll.monthWise.report',compact('PayrollSummery','ProvidentSetup','payrollmonth','type','typename','month','year','report'));
    }

    public function getMobilebillReport($type,$report,$month,$year)
    {
        $id =   Auth::guard('admin')->user();
        $payrollmonth=Month::find($month);
        $ProvidentSetup=$this->ProvidentSetup();
        $start_date=$year.'-'.$month.'-01';
        $end_date=$year.'-'.$month.'-'.$payrollmonth->days;

        $type_array = array();
        $typename='';
        // if($type=="1"){
        //     $type_array = array('6','8');
        //     $typename='Head Office Officers and Staffs';
        // }elseif($type=="0"){
        //     $type_array = array('1','2','3','4','5');
        //     $typename='Factory Officers and Staffs';
        // }
        if($type=="9"){
            $type_array = array('9');
            // $typename='Head Office Officers and Staffs';
        }elseif($type=="10"){
            $type_array = array('10');
            // $typename='Factory Officers and Staffs';
        }
        elseif($type=="11"){
            $type_array = array('11');
            // $typename='Factory Officers and Staffs';
        }
        elseif($type=="12"){
            $type_array = array('12');
            // $typename='Factory Officers and Staffs';
        }
        elseif($type=="13"){
            $type_array = array('13');
            // $typename='Factory Officers and Staffs';
        }
        elseif($type=="14"){
            $type_array = array('14');
            // $typename='Factory Officers and Staffs';
        }

        $employees = array();
        if($report=="1"){
            $search=Salary::get(['emp_id','bank_account']);
            if(isset($search[0])){
                foreach ($search as $key => $emp) {
                    if(strlen($emp->bank_account)>1){
                        array_push($employees,$emp->emp_id);
                    }
                }
            }
        }else{
            $search=Salary::get(['emp_id','bank_account']);
            if(isset($search[0])){
                foreach ($search as $key => $emp) {
                    if(strlen($emp->bank_account)<1){
                        array_push($employees,$emp->emp_id);
                    }
                }
            }
        }


        $PayrollSummery=PayrollSummery::with(['employee','employee.designation','employee.salary','heads','heads.head','extends','extends.head'])
        ->where([
            ['payroll_date_from','<=',$start_date],
            ['payroll_date_to','>=',$end_date],
        ])
        // ->when($id->suser_level!="1", function($query) use($id){
        //     return $query->where(function($query) use($id){
        //         $query->where('emp_id', $id->suser_empid);
        //     });
        // })
        ->when(count($employees), function($query) use($employees){
            return $query->whereIn('emp_id',$employees);
        })
        ->where([
            'type'=>30
        ])
        // ->whereIn('emp_type',$type_array)
        ->get();
        // return $PayrollSummery; exit();
        $mobile_report = Mobilebill::where('tbl_mobilebill.month', $month)
                    ->join('tbl_employee', 'tbl_employee.emp_id', '=', 'tbl_mobilebill.emp_id')
                    ->where('tbl_employee.emp_officecontact', '>', 0)
                    ->where('tbl_employee.emp_allocamount', '>', 0)
                    ->get();


        return view('Admin.payroll.monthWise.mobilebill_print',compact('PayrollSummery','ProvidentSetup','payrollmonth','type','typename','month','year','report', 'mobile_report'));
    }


    public function getBonusReport($type,$report,$bonus_type,$month,$year)
    {
        // echo "string"; exit();
        // return $bonus_type; exit();
        $id =   Auth::guard('admin')->user();
        $payrollmonth=Month::find($month);
        $ProvidentSetup=$this->ProvidentSetup();
        $start_date=$year.'-'.$month.'-01';
        $end_date=$year.'-'.$month.'-'.$payrollmonth->days;

        $type_array = array();
        $typename='';
        // if($type=="1"){
        //     $type_array = array('6','8');
        //     $typename='Head Office Officers and Staffs';
        // }elseif($type=="0"){
        //     $type_array = array('1','2','3','4','5');
        //     $typename='Factory Officers and Staffs';
        // }
        if($type=="9"){
            $type_array = array('9');
            // $typename='Head Office Officers and Staffs';
        }elseif($type=="10"){
            $type_array = array('10');
            // $typename='Factory Officers and Staffs';
        }
        elseif($type=="11"){
            $type_array = array('11');
            // $typename='Factory Officers and Staffs';
        }
        elseif($type=="12"){
            $type_array = array('12');
            // $typename='Factory Officers and Staffs';
        }
        elseif($type=="13"){
            $type_array = array('13');
            // $typename='Factory Officers and Staffs';
        }
        elseif($type=="14"){
            $type_array = array('14');
            // $typename='Factory Officers and Staffs';
        }

        $employees = array();
        if($report=="1"){
            $search=Salary::get(['emp_id','bank_account']);
            if(isset($search[0])){
                foreach ($search as $key => $emp) {
                    if(strlen($emp->bank_account)>1){
                        array_push($employees,$emp->emp_id);
                    }
                }
            }
        }else{
            $search=Salary::get(['emp_id','bank_account']);
            if(isset($search[0])){
                foreach ($search as $key => $emp) {
                    if(strlen($emp->bank_account)<1){
                        array_push($employees,$emp->emp_id);
                    }
                }
            }
        }


        $PayrollSummery=PayrollSummery::with(['employee','employee.designation','employee.salary','heads','heads.head','extends','extends.head'])
        ->where([
            ['payroll_date_from','<=',$start_date],
            ['payroll_date_to','>=',$end_date],
        ])
        // ->when($id->suser_level!="1", function($query) use($id){
        //     return $query->where(function($query) use($id){
        //         $query->where('emp_id', $id->suser_empid);
        //     });
        // })
        ->when(count($employees), function($query) use($employees){
            return $query->whereIn('emp_id',$employees);
        })
        ->where([
            'type'=>30
        ])
        // ->whereIn('emp_type',$type_array)
        ->get();
        // return $PayrollSummery; exit();
        $mobile_report = Bonus::where('tbl_bonus.month', $month)
                    ->join('tbl_employee', 'tbl_employee.emp_id', '=', 'tbl_bonus.emp_id')

                    // ->where('tbl_employee.emp_officecontact', '>', 0)
                    // ->where('tbl_employee.emp_allocamount', '>', 0)
                    ->get();
        // return $mobile_report; exit();
        $mon=Month::where('id', $month)->first();

        return view('Admin.payroll.monthWise.bonus_print',compact('PayrollSummery','ProvidentSetup','payrollmonth','type','typename','month','year','report', 'mobile_report', 'bonus_type', 'mon'));
    }

    public function checkPayrollDate($var,$type)
    {
        $start_date=$var['start_date'];
        $end_date=$var['end_date'];
        $emp_type=$var['type'];
        $emp_id=$var['emp_id'];
        $search=PayrollSummery::where(function($query) use($start_date,$end_date){
                return $query->where([
                            ['payroll_date_from','>=',$start_date],
                            ['payroll_date_from','<=',$end_date]
                        ])
                        ->orWhere([
                            ['payroll_date_from','<=',$start_date],
                            ['payroll_date_to','>=',$end_date]
                        ])->orWhere([
                            ['payroll_date_to','>=',$start_date],
                            ['payroll_date_to','<=',$end_date]
                        ]);
            })
            ->where('emp_type',$emp_type)
            ->when($emp_id!="0",function($query) use($emp_id){
                return $query->where('emp_id',$emp_id);
            })
            ->where('type',$type)
            ->get();
        if(isset($search[0])){
            return '0&'.$search[0]->payroll_date_from.'&'.$search[0]->payroll_date_to.'&'.$emp_type.'&'.$emp_id;
        }else{
            return '1';
        }

    }

    public function PayrollSummerySave($id,$payment,$var,$type)
    {
        $start_date=$var['start_date'];
        $end_date=$var['end_date'];
        foreach ($payment as $payroll) {
            $PayrollSummery=PayrollSummery::insertGetId([
                'emp_id'=>$payroll['emp_id'],
                'date_of_execution'=>$payroll['date_of_execution'],
                'payroll_date_from'=>$var['start_date'],
                'payroll_date_to'=>$var['end_date'],
                'addition'=>$payroll['addition'],
                'deduction'=>$payroll['deduction'],
                'tax'=>$payroll['tax'],
                'provident_fund'=>$payroll['provident-fund'],
                'salary'=>$payroll['salary'],
                'generated_at'=>date('Y-m-d H:i:s'),
                'updated_at'=>date('Y-m-d H:i:s'),
                'updated_count'=>'1',
                'generated_by'=>$id->suser_empid,
                'updated_by'=>$id->suser_empid,
                'type'=>$type,
            ]);
            if(count($payroll['extends'])){
                foreach ($payroll['extends'] as $key => $value) {
                    PayrollSummeryExtends::insert([
                        'ps_id'=>$PayrollSummery,
                        'head_id'=>$key,
                        'head_quantity'=>$value,
                        'head_amount'=>$payroll['extendsAmount'][$key],
                    ]);
                }
            }
        }
    }

    public function getPayrollTitle($var,$title,$days)
    {
        $data='';
        $start_date=$var['start_date'];
        $end_date=$var['end_date'];
        $data.=$title.' <br><br><font style="font-weight:normal;font-size:14px">';
        if($days==0){
            if($start_date==$end_date){
                $data.=' ( Date : '.$start_date.' )</font>';
            }else{
                $data.=' ( Date From : '.$start_date.' To : '.$end_date.' )</font>';
            }
        }elseif($days==30){
            $data.=date('F, Y',strtotime($start_date));
        }

        $data.=' of '.$var['typename'];

        if($var['emp_id']!="0"){
            $data.='. Employee '.$this->emp_name($var['emp_id']).' ('.$this->emp_empid($var['emp_id']).')';
        }

        return $data;
    }

    public function inputData($request,$type,$key=false)
    {
        if($type=="1"){
            $reportController=new reportController();
            $var=array(
                'start_date' => $reportController->getStartDate($request->dateRange), 
                'end_date' => $reportController->getEndDate($request->dateRange), 
                'type' => $request->type, 
                'emp_id' => $request->emp_id, 
                'typename' => $this->getEmployeeType($request->type), 
            );
        }elseif($type=="30"){
            $month=Month::find($request->month);
            $var=array(
                'start_date' => date("Y-m-d", strtotime("first day of ".$month->month)), 
                'end_date' => date("Y-m-d", strtotime("last day of ".$month->month)),
                'type' => $request->type, 
                'emp_id' => $request->emp_id, 
                'typename' => $this->getEmployeeType($request->type), 
            );
        }
        
        if($key){
            return $var[$key];
        }else{
            return $var;
        }
    }

    public function getSalaryEmployee($var)
    {
        return $employee=Employee::with(['designation','type','type.currentNightShiftAllowance'])
            ->groupBy('tbl_employee.emp_id')
            ->whereNotIn('emp_id',[116])
            ->where('emp_type',$var['type'])
            ->when($var['emp_id']!="0",function($query) use($var){
                return $query->where('emp_id',$var['emp_id']);
            })
            ->orderBy('emp_name','asc')
            ->get();
    }
    public function getMobileEmployee($var)
    {
        return $employee=Employee::with(['designation','type','type.currentNightShiftAllowance'])
            ->groupBy('tbl_employee.emp_id')
            ->whereNotIn('emp_id',[116])
            // ->where('emp_type',$var['type'])
            ->where('emp_officecontact','!=', null)
            // ->where('emp_allocamount','>', 0)
            // ->when($var['emp_id']!="0",function($query) use($var){
            //     return $query->where('emp_id',$var['emp_id']);
            // })
            ->orderBy('emp_name','asc')
            ->get();
    }

     public function getTaxChallanEmployee($var)
    {
        return $employee=Employee::with(['designation','type','type.currentNightShiftAllowance'])
            ->groupBy('tbl_employee.emp_id')
            ->whereNotIn('emp_id',[116])
            ->where('emp_type',$var['type'])
            // ->where('emp_officecontact','!=', null)
            // ->where('emp_allocamount','>', 0)
            // ->when($var['emp_id']!="0",function($query) use($var){
            //     return $query->where('emp_id',$var['emp_id']);
            // })
            ->orderBy('emp_name','asc')
            ->get();
    }

    public function getBonusEmployee($var)
    {
        return $employee=Employee::with(['designation','type','type.currentNightShiftAllowance'])
            ->groupBy('tbl_employee.emp_id')
            ->whereNotIn('emp_id',[116])
            ->where('emp_type',$var['type'])
            // ->where('emp_officecontact','!=', null)
            // ->where('emp_allocamount','>', 0)
            // ->when($var['emp_id']!="0",function($query) use($var){
            //     return $query->where('emp_id',$var['emp_id']);
            // })
            ->orderBy('emp_name','asc')
            ->get();
    }

    public function getExecutionDate($start_date,$end_date)
    {
        $payroll=Payroll::where('head_date_of_execution','<=',$start_date)->orWhere('head_date_of_execution','<=',$end_date)
            ->orderBy('head_date_of_execution','desc')
            ->first(['head_date_of_execution']);
        if(isset($payroll->head_date_of_execution)){
            return response()->json(["success"=>true,"date"=>$payroll->head_date_of_execution]);
        }else{
            return response()->json(["success"=>false,"msg"=>"Payroll Data not found for your date range (".$start_date." to ".$end_date.")"]);
        }
    }

    public function otCalculation($dateRange,$emp_id)
    {
        // $otTotalHour=0;

        // foreach ($dateRange as $date) {
        //     $ot='00:00:00';
        //     $totalWorkingHour=TotalWorkingHours::where(['emp_id'=>$emp_machineid,'date'=>$date])->first();
        //     if(isset($totalWorkingHour->id)){
        //         $ot=$totalWorkingHour->ot;
        //     }
        //     $otTotalHour+=$this->timeToHours($ot);
        //     $assign=DB::table('tbl_otapplication')->where([
        //         'otapp_empid'=>$emp->emp_id,
        //         'otapp_perdate'=>date('Y-m-d')
        //     ])->first();
        //     $ot=0;
        //     if(isset($assign->otapp_id)){
        //         $ot=$assign->otapp_totalhrs;
        //     }
        // }
        
        // return $otTotalHour;

        return $otTotalHour=DB::table('tbl_otapplication')->where([
                'otapp_empid'=>$emp_id
            ])
            ->whereIn('otapp_perdate',$dateRange)
            ->sum('otapp_totalhrs');
    }

    public function paymentCalculation($employee,$var,$page_title,$SalaryHead,$SalaryHeadExtends,$ProvidentSetup)
    {
        $data = array();
        $start_date=$var['start_date'];
        $end_date=$var['end_date'];
        $dateRange=$this->getDateRange($start_date,$end_date);
        $salaryDays=$this->days($start_date,$end_date);
        $executionDate=$this->getExecutionDate($start_date,$end_date)->getData();
        if($executionDate->success){
            $counter=0;
            foreach ($employee as $emp) {
                $data[$emp->emp_id]=$this->salaryCalculation($counter++,$emp,$executionDate,$SalaryHead,$SalaryHeadExtends,$salaryDays,$dateRange,$var,$ProvidentSetup);
            }
        }else{
            session()->flash('error',$executionDate->msg);
        }

        return $data;
    }

    public function monthWisePaymentCalculation($employee,$var,$page_title,$SalaryHead,$SalaryHeadExtends,$ProvidentSetup, $month)
    {
        $data = array();
        $start_date=$var['start_date'];
        $end_date=$var['end_date'];
        $dateRange=$this->getDateRange($start_date,$end_date);
        $executionDate=$this->getExecutionDate($start_date,$end_date)->getData();
        if($executionDate->success){
            $counter=0;
            foreach ($employee as $emp) {
                $data[$emp->emp_id]=$this->monthWiseSalaryCalculation($counter++,$emp,$executionDate,$SalaryHead,$SalaryHeadExtends,$dateRange,$var,$ProvidentSetup,$month);
            }
        }else{
            session()->flash('error',$executionDate->msg);
        }

        return $data;
    }

    public function ProvidentSetup()
    {
        $ProvidentFundSetup=ProvidentFundSetup::find('1');
        if(isset($ProvidentFundSetup->provident_fund) && $ProvidentFundSetup->provident_fund=="1"){
            return true;
        }

        return false;
    }

    public function nightAllowance($emp_id,$start_date,$end_date)
    {
        return TotalWorkingHours::where('emp_id',$emp_id)
                                ->where([
                                    ['date','>=',$start_date],
                                    ['date','<=',$end_date]
                                ])
                                ->sum('night');
    }

    public function salaryCalculation($counter,$emp,$executionDate,$SalaryHead,$SalaryHeadExtends,$salaryDays,$dateRange,$var,$ProvidentSetup)
    {
        $data = array();
        $addition=0;
        $deduction=0;
        $taxable=0;
        $data[$emp->emp_id] = array(
            'counter' => $counter, 
            'emp_id' => $emp->emp_id,
            'emp_empid' => $emp->emp_empid,
            'emp_machineid' => $emp->emp_machineid, 
            'emp_name' => $emp->emp_name, 
            'date_of_execution' => $executionDate->date, 
            'payroll_date_from' => $var['start_date'], 
            'payroll_date_to' => $var['end_date'], 
        );
        $perDayBasic=0;
        foreach ($SalaryHead as $head) {
            $payrollData=Payroll::where([
                'emp_id'=>$emp->emp_id,
                'head_date_of_execution'=>$executionDate->date,
                'head_id'=>$head->head_id
                ])
                ->first(['amount']);
            if(isset($payrollData->amount)){
                if($head->head_id=="1"){
                    $perDayBasic=(($payrollData->amount*12.17)/365);
                }
                $data[$emp->emp_id][$head->head_id]=$payrollData->amount;
                if($head->head_type=="1"){
                    $addition+=$payrollData->amount;
                }elseif($head->head_type=="0"){
                    $deduction+=$payrollData->amount;
                }
                if($head->head_taxable=="1"){
                    if($payrollData->amount*12.17>=$head->head_taxexempt){
                        $taxable+=$head->head_taxexempt;
                    }else{
                        $taxable+=$payrollData->amount*12.17;
                    }
                }
            }
        }

        $fund=0;
        if($ProvidentSetup){
            $month=explode('-',$var['start_date'])[1];
            if($month<10){
                $month=substr($month,1,1);
            }
            $year=explode('-',$var['start_date'])[0];
            $ProvidentFund=ProvidentFund::where(['month'=>$month,'year'=>$year,'emp_id'=>$emp->emp_id])->first();
            if(isset($ProvidentFund->employee_amount)){
                $fund=$ProvidentFund->employee_amount;
            }
            $deduction+=$fund;
        }
        $data[$emp->emp_id]["provident-fund"]=$fund;

        $tax=$this->taxCalculaTion($taxable,$emp);
        //$tax=0;
        if($tax<=0){
            $tax=0;
        //}elseif($tax>0 && $tax<=5000){
            //$tax=5000;
            //$tax=($tax/365)*$salaryDays;
        }else{
            $tax=($tax/365)*$salaryDays;
        }
        
        $addition=(($addition*12.17)/365)*$salaryDays;
        $deduction=(($deduction*12.17)/365)*$salaryDays;
        $data[$emp->emp_id]['taxable']=$this->decimal($taxable);
        $data[$emp->emp_id]['tax']=$this->decimal($tax);
        $data[$emp->emp_id]['taxable']=0;
        $data[$emp->emp_id]['tax']=0;

        $perDaySalary=$this->decimal(($addition-($deduction+$tax))/$salaryDays);
        $extendsAddition=0;
        $extendsDeduction=0;

        foreach ($SalaryHeadExtends as $head) {
            if ($head->head_id=="1"){
                $absent=$this->countAbsent($emp,$dateRange);
                if($absent>0){
                    $absent=$absent/$head->head_unit_for_absent;
                }
                // if($head->head_percentage_status=="1"){
                //     $absentAmount=($perDayBasic*$absent)*($head->head_percentage_for_basic/100);
                // }elseif($head->head_percentage_status=="2"){
                //     $absentAmount=($perDaySalary*$absent)*($head->head_percentage_for_total/100);
                // }
                if($absent<=10){
                    $absentAmount=($perDayBasic*$absent)*($head->head_percentage_for_basic/100);
                }elseif($absent>10){
                    $absentAmount=($perDaySalary*$absent)*($head->head_percentage_for_total/100);
                }
                $data[$emp->emp_id]['extends'][$head->head_id]=$absent;
                $data[$emp->emp_id]['extendsAmount'][$head->head_id]=$this->decimal($absentAmount);
                if($head->head_type=="1"){
                    $extendsAddition+=$absentAmount;
                }elseif ($head->head_type=="0") {
                    $extendsDeduction+=$absentAmount;
                }
            } elseif ($head->head_id=="2"){
                $late=$this->countlate($emp->emp_machineid,$dateRange);
                if($late>0){
                    $late=$late/$head->head_unit_for_absent;
                }
                // if($head->head_percentage_status=="1"){
                //     $lateAmount=($perDayBasic*$late)*($head->head_percentage_for_basic/100);
                // }elseif($head->head_percentage_status=="2"){
                //     $lateAmount=($perDaySalary*$late)*($head->head_percentage_for_total/100);
                // }
                if($late<=10){
                    $lateAmount=($perDayBasic*$late)*($head->head_percentage_for_basic/100);
                }elseif($late>10){
                    $lateAmount=($perDaySalary*$late)*($head->head_percentage_for_total/100);
                }
                $data[$emp->emp_id]['extends'][$head->head_id]=$late;
                $data[$emp->emp_id]['extendsAmount'][$head->head_id]=$this->decimal($lateAmount);
                if($head->head_type=="1"){
                    $extendsAddition+=$lateAmount;
                }elseif ($head->head_type=="0") {
                    $extendsDeduction+=$lateAmount;
                }
            } elseif ($head->head_id=="3") {
                $otHour=$this->otCalculation($dateRange,$emp->emp_machineid);
                if($head->head_percentage_status=="1"){
                    $otAmount=(($perDayBasic*($head->head_percentage_for_basic/100))/240)*$otHour*2;
                }elseif($head->head_percentage_status=="2"){
                    $otAmount=(($perDayBasic*($head->head_percentage_for_total/100))/240)*$otHour*2;
                }
                $data[$emp->emp_id]['extends'][$head->head_id]=$otHour;
                $data[$emp->emp_id]['extendsAmount'][$head->head_id]=$this->decimal($otAmount);
                if($head->head_type=="1"){
                    $extendsAddition+=$otAmount;
                }elseif ($head->head_type=="0") {
                    $extendsDeduction+=$otAmount;
                }
            }
        }

        $data[$emp->emp_id]['addition']=$this->decimal($addition);
        $data[$emp->emp_id]['deduction']=$this->decimal($deduction+$this->decimal($tax));
        $data[$emp->emp_id]['salary']=$this->decimal(($addition-($deduction+$this->decimal($tax)))+($extendsAddition-$extendsDeduction));

        return $data[$emp->emp_id];
    }


    public function monthWiseSalaryCalculation($counter,$emp,$executionDate,$SalaryHead,$SalaryHeadExtends,$dateRange,$var,$ProvidentSetup,$month)
    {
        $month=explode('-',$var['start_date'])[1];
        if($month<10){
            $month=substr($month,1,1);
        }
        $year=explode('-',$var['start_date'])[0];
        $monthInfo=Month::find($month);


        $prev_target_month = $month-1;
        $prev_month_check=ContractualPayroll::where('payroll_date_from', $year.'-'.$prev_target_month.'-'.'01')->first();
        if($prev_month_check){
            
            $prev_targeted_income = ContractualPayroll::where('payroll_date_from', $year.'-'.$prev_target_month.'-'.'01')->first();

            $prev_target =$prev_targeted_income->targeted_income;
            $prev_net    =$prev_targeted_income->net_income;
            $difference  =$prev_target-$prev_net;
            // $data[$emp->emp_id]['dif'] =$difference;

        }else{
           $difference = 0;
           // $data[$emp->emp_id]['dif'] =$difference;
        }

        $mobilebill = Mobilebill::where('emp_id', $emp->emp_id)
                                ->where('month', $month)
                                ->first();

        if($mobilebill){
            if($mobilebill->mobile_bill > $emp->emp_allocamount){
                $monthly_mblbill = $mobilebill->mobile_bill -$emp->emp_allocamount;
            }else{
                $monthly_mblbill = 0;
            }
        }else{
            $monthly_mblbill = 0;
       // $mobilebill->mobile_bill = 0;
        }
        // if($monthly_mblbill < 0){
        //     $monthly_mblbill = 0;
        // }

        $data = array();
        $addition=0;
        $deduction=0;
        $taxable=0;
        
        if($mobilebill){
        $data[$emp->emp_id] = array(
            'counter' => $counter, 
            'emp_id' => $emp->emp_id,
            'emp_desig_id' => $emp->designation->desig_name,
            'emp_officecontact' => $emp->emp_officecontact,
            'emp_allocamount' => $emp->emp_allocamount,
            'emp_mobilebill' => $mobilebill->mobile_bill,
            'emp_empid' => $emp->emp_empid,
            'emp_machineid' => $emp->emp_machineid, 
            'emp_name' => $emp->emp_name, 
            'date_of_execution' => $executionDate->date, 
            'payroll_date_from' => $var['start_date'], 
            'payroll_date_to' => $var['end_date'], 
        );
        }else{
            $data[$emp->emp_id] = array(
            'counter' => $counter, 
            'emp_id' => $emp->emp_id,
            'emp_desig_id' => $emp->designation->desig_name,
            'emp_allocamount' => $emp->emp_allocamount,
            'emp_officecontact' => $emp->emp_officecontact,
            
            'emp_empid' => $emp->emp_empid,
            'emp_machineid' => $emp->emp_machineid, 
            'emp_name' => $emp->emp_name, 
            'date_of_execution' => $executionDate->date, 
            'payroll_date_from' => $var['start_date'], 
            'payroll_date_to' => $var['end_date'], 
        );
        }

        $perDayBasic=0;
        $basic=0;
        foreach ($SalaryHead as $head) {
            $payrollData=Payroll::where([
                'emp_id'=>$emp->emp_id,
                'head_date_of_execution'=>$executionDate->date,
                'head_id'=>$head->head_id
                ])
                ->first(['amount']);
            if(isset($payrollData->amount)){
                if($head->head_id=="1"){
                    $perDayBasic=$payrollData->amount/$monthInfo->days;
                    $basic=$payrollData->amount;
                }
                $data[$emp->emp_id][$head->head_id]=$payrollData->amount;
                // if($head->head_id!="5"){
                    if($head->head_type=="1"){
                        $addition+=$payrollData->amount;
                    }elseif($head->head_type=="0"){
                        $deduction+=$payrollData->amount;
                    }
                // }
                if($head->head_taxable=="1"){
                    // if($payrollData->amount*12.17>=$head->head_taxexempt){
                    //     $taxable+=$head->head_taxexempt;
                    // }else{
                        $taxable+=$payrollData->amount*12;
                    // }
                }

                // $taxable+=$head->head_taxexempt;
                // $taxable+=$payrollData->amount*12.17;
                
            }else{
                $data[$emp->emp_id][$head->head_id]=0;
            }

        }

        $nights=$this->nightAllowance($emp->emp_id,$var['start_date'],$var['end_date']);
        // $night_allowance=0;
        // if(isset($emp->type)){
        //     if(isset($emp->type->currentNightShiftAllowance)){
        //         $night_allowance=$this->decimal($nights*$emp->type->currentNightShiftAllowance->allowance);
        //     }
        // }
        
        $data[$emp->emp_id]['nights']=$nights;
        // $data[$emp->emp_id]['night_allowance']=$night_allowance;

        $fund=0;
        if($ProvidentSetup){
            $ProvidentFund=ProvidentFund::where(['month'=>$month,'year'=>$year,'emp_id'=>$emp->emp_id])->first();
            if(isset($ProvidentFund->employee_amount)){
                $fund=$ProvidentFund->employee_amount;
            }
            $deduction+=$fund;
        }
        $data[$emp->emp_id]["provident-fund"]=$fund;

        $tax=$this->taxCalculaTion($taxable,$emp);
        //$tax=0;
        if($tax<=0){
            $tax=0;
        //}elseif($tax>0 && $tax<=5000){
            //$tax=5000;
            //$tax=($tax/12.17);
        //}else{
           
        }else{
             $tax=($tax/12);
        }
        // $tax =1000;

        $check=Employee::where('emp_id', $emp->emp_id)->first();
        if($check->tax_allow==0)
        {
            $tax=0;
        }
        
        $data[$emp->emp_id]['taxable']=$this->decimal($taxable);
        $data[$emp->emp_id]['tax']=$this->decimal($tax);
        //$data[$emp->emp_id]['taxable']=0;
        //$data[$emp->emp_id]['tax']=0;

        //bonus
        $baishakhy_bonus_head=Setup::where('b_bonus','>' ,0)
        // ->where('other_head_type', 1)
        ->first();

        $fitr_bonus_head=Setup::where('fitr_bonus','>' ,0)
        // ->where('other_head_type', 1)
        ->first();

         $adha_bonus_head=Setup::where('adha_bonus','>' ,0)
        // ->where('other_head_type', 1)
        ->first();

        $bonus_payroll=Payroll::where('head_id', 1)
        ->where('emp_id', $emp->emp_id)->first();

        if($baishakhy_bonus_head){
             $baishakhy_bonus=($baishakhy_bonus_head->b_bonus*$bonus_payroll->amount)/100;
             $data[$emp->emp_id]['baishakhy_bonus']=$this->decimal($baishakhy_bonus);
        }else{
            $baishakhy_bonus=0;
            $data[$emp->emp_id]['baishakhy_bonus']=$this->decimal(0);
        }

        if($fitr_bonus_head){
             $fitr_bonus=($fitr_bonus_head->fitr_bonus*$bonus_payroll->amount)/100;
             $data[$emp->emp_id]['fitr_bonus']=$this->decimal($fitr_bonus);
        }else{
            $fitr_bonus=0;
            $data[$emp->emp_id]['fitr_bonus']=$this->decimal(0);
        }

        if($adha_bonus_head){
             $adha_bonus=($adha_bonus_head->adha_bonus*$bonus_payroll->amount)/100;
             $data[$emp->emp_id]['adha_bonus']=$this->decimal($adha_bonus);
        }else{
            $adha_bonus=0;
            $data[$emp->emp_id]['adha_bonus']=$this->decimal(0);
        }


        $contractual_employee=Employee::where('emp_id', $emp->emp_id)
        ->first();

        if($contractual_employee){
            $minimum_salary=$contractual_employee->min_salary;
            $target_percent=$contractual_employee->target_percent;
            $data[$emp->emp_id]['min_salary']=$minimum_salary;
            $data[$emp->emp_id]['target_percent']=$target_percent;
        }else{
            $minimum_salary=0;
            $target_percent=0;
            $data[$emp->emp_id]['min_salary']     =$minimum_salary;
            $data[$emp->emp_id]['target_percent'] =$target_percent;
        }

        $contractual_basic=Payroll::where('emp_id', $emp->emp_id)
        ->where('head_id', 1)
        ->first();
        $contractual_conv=Payroll::where('emp_id', $emp->emp_id)
        ->where('head_id', 4)
        ->first();

        if($contractual_basic){
            $gross= $contractual_basic->amount*($target_percent/100);
            $data[$emp->emp_id]['gross']=$gross;
        }



        if($contractual_conv){
         $targeted_income= (($gross+$contractual_conv->amount)*$target_percent)/100 +$difference;
         $data[$emp->emp_id]['targeted_income']=$targeted_income;
        }else{
            $targeted_income=0;
            $data[$emp->emp_id]['targeted_income']=$targeted_income;
        }

        $data[$emp->emp_id]['dif'] =$difference;



        // $data[$emp->emp_id]['net_income']=50000;

        // loan calculation start
        $loan = Loan::where('emp_id', $emp->emp_id)
                        ->select('amount')
                        ->first();

        $loan_id = Loan::where('emp_id', $emp->emp_id)
                        ->select('id')
                        ->first();

        if($loan_id){
            $month_count = LoanApprove::where('loan_id', $loan_id->id)->count();


            if($month_count!=0){
                $monthly_loan = $loan->amount/$month_count;
            }
            elseif($month_count==0)
            {
                $monthly_loan=0;
            }

            $month_check = LoanApprove::where('loan_id', $loan_id->id)->first();

            $req_month_check = LoanApprove::where('loan_id', $loan_id->id)
                            ->where('month', $month)
                            ->first();

            if($req_month_check)
            {
            $data[$emp->emp_id]['loan']=$monthly_loan;
            }
            else
            {
                $monthly_loan = 0;
                $data[$emp->emp_id]['loan']=0;
            }
             // if($month_check->month == $month)
             // {
             //    $data[$emp->emp_id]['loan']=$monthly_loan;
             // }
             // else{
             //   $data[$emp->emp_id]['loan']=0;
             // }



            //     {
            //     $data[$emp->emp_id]['loan']=$monthly_loan;
            //     }
            // foreach($month_check as $row)
            // {
            //     if($row->month == 5)
            //     {
            //     $data[$emp->emp_id]['loan']=$monthly_loan;
            //     }
            //     else{
            //         $data[$emp->emp_id]['loan']=0;
            //     }
            // }
            // $data[$emp->emp_id]['loan']=$monthly_loan;
         }
        else{
            $monthly_loan = 0;
            $data[$emp->emp_id]['loan']=0;
        }
        //loan calculation end
        //pending loan calculation start
        $pending_loan = Loan::where('emp_id', $emp->emp_id)
                        ->where('flag', 1)
                        ->select('id')
                        ->first();
        if($pending_loan){
            $is_pending_month = LoanApprove::where('loan_id', $loan_id->id)
                                        ->where('flag', 0)
                                        ->count();

            if($is_pending_month == 0){
                $sum_paid = LoanApprove::where('loan_id', $pending_loan->id)
                  ->where('flag', 1)
                  ->sum('paid_amount');
                $total_loan = Loan::where('emp_id', $emp->emp_id)
                        ->where('flag', 1)
                        ->first();
                $pending_amount = $total_loan->amount - $sum_paid;

                

                $data[$emp->emp_id]['loan']=$pending_amount;


            }else{
                if($req_month_check)
                {
                    $data[$emp->emp_id]['loan']=$monthly_loan;
                }
                else
                {
                    $monthly_loan = 0;
                    $data[$emp->emp_id]['loan']=0;
                }
            }
        }
        //pending loan calculation end

        

        // $mobilebill = PayrollSummery::where('emp_id', $emp->emp_id)


        $perDaySalary=$this->decimal(($addition-($deduction+$tax))/$monthInfo->days);
        $extendsAddition=0;
        $extendsDeduction=0;

        $data[$emp->emp_id]["basic"]=$basic;
        $data[$emp->emp_id]["perDayBasic"]=$perDayBasic;
        $data[$emp->emp_id]["perDaySalary"]=$perDaySalary;

        foreach ($SalaryHeadExtends as $head) {
            if ($head->head_id=="1"){
                $absent=$this->countAbsent($emp,$dateRange);
                if($absent>0){
                    $absent=$absent/$head->head_unit_for_absent;
                }
                // if($head->head_percentage_status=="1"){
                //     $absentAmount=($perDayBasic*$absent)*($head->head_percentage_for_basic/100);
                // }elseif($head->head_percentage_status=="2"){
                //     $absentAmount=($perDaySalary*$absent)*($head->head_percentage_for_total/100);
                // }
                if($absent<=10){
                    $absentAmount=($perDayBasic*$absent)*($head->head_percentage_for_basic/100);
                }elseif($absent>10){
                    $absentAmount=($perDaySalary*$absent)*($head->head_percentage_for_total/100);
                }
                $data[$emp->emp_id]['extends'][$head->head_id]=$absent;
                $data[$emp->emp_id]['extendsAmount'][$head->head_id]=$this->decimal($absentAmount);
                if($head->head_type=="1"){
                    $extendsAddition+=$absentAmount;
                }elseif ($head->head_type=="0") {
                    $extendsDeduction+=$absentAmount;
                }
            } elseif ($head->head_id=="2"){
                $late=$this->countlate($emp->emp_machineid,$dateRange);
                if($late>0){
                    $late=$late/$head->head_unit_for_absent;
                }
                // if($head->head_percentage_status=="1"){
                //     $lateAmount=($perDayBasic*$late)*($head->head_percentage_for_basic/100);
                // }elseif($head->head_percentage_status=="2"){
                //     $lateAmount=($perDaySalary*$late)*($head->head_percentage_for_total/100);
                // }
                if($late<=10){
                    $lateAmount=($perDayBasic*$late)*($head->head_percentage_for_basic/100);
                }elseif($late>10){
                    $lateAmount=($perDaySalary*$late)*($head->head_percentage_for_total/100);
                }
                $data[$emp->emp_id]['extends'][$head->head_id]=$late;
                $data[$emp->emp_id]['extendsAmount'][$head->head_id]=$this->decimal($lateAmount);
                if($head->head_type=="1"){
                    $extendsAddition+=$lateAmount;
                }elseif ($head->head_type=="0") {
                    $extendsDeduction+=$lateAmount;
                }
            } elseif ($head->head_id=="3") {
                $otHour=$this->otCalculation($dateRange,$emp->emp_id);
                // if($head->head_percentage_status=="1"){
                //     $otAmount=$this->decimal(($perDayBasic*($head->head_percentage_for_basic/100) ) * $otHour * 2);
                // }elseif($head->head_percentage_status=="2"){
                //     $otAmount=$this->decimal(($perDayBasic*($head->head_percentage_for_total/100) ) * $otHour * 2);
                // }
                $otAmount=$this->decimal(($basic/240)*$otHour*2);
                $data[$emp->emp_id]['extends'][$head->head_id]=$otHour;
                $data[$emp->emp_id]['extendsAmount'][$head->head_id]=$this->decimal($otAmount);
                if($head->head_type=="1"){
                    $extendsAddition+=$otAmount;
                }elseif ($head->head_type=="0") {
                    $extendsDeduction+=$otAmount;
                }
            }
        }

        $extendsDeduction+=$monthly_loan;
        $extendsDeduction+=$monthly_mblbill;

        $data[$emp->emp_id]['addition']=$this->decimal($addition)+$data[$emp->emp_id]['baishakhy_bonus']+$data[$emp->emp_id]['fitr_bonus']+ $data[$emp->emp_id]['adha_bonus'];
        $data[$emp->emp_id]['deduction']=$this->decimal($deduction+$this->decimal($tax)+$extendsDeduction);
        $data[$emp->emp_id]['salary']=$this->decimal(($addition-($deduction+$this->decimal($tax)))+($extendsAddition-$extendsDeduction))+$data[$emp->emp_id]['baishakhy_bonus']+$data[$emp->emp_id]['fitr_bonus']+ $data[$emp->emp_id]['adha_bonus'];

        return $data[$emp->emp_id];
    }

    public function countAbsent($emp,$dateRange)
    {
        $ToolsController=new ToolsController();
        $absent=0;
        foreach ($dateRange as $date) {
            $checkLeave=$ToolsController->checkLeave($emp->emp_id,$date,$date);
            $totalWorkingHour=TotalWorkingHours::where(['emp_id'=>$emp->emp_id,'date'=>$date])->first();
            $present=0;
            if(isset($totalWorkingHour->id)){
                $present=$totalWorkingHour->present;
            }
            if($present=="0" && $ToolsController->checkHoliday($date)=="0" && $ToolsController->checkWeekend($emp,$date)=="0" && !isset($checkLeave->leave_empid)){
                $absent++;
            }
        }

        return $absent;
    }

    public function countLate($emp_machineid,$dateRange)
    {
        $late=0;
        foreach ($dateRange as $date) {
            $totalWorkingHour=TotalWorkingHours::where(['emp_id'=>$emp_machineid,'date'=>$date])->first();
            if(isset($totalWorkingHour->id) && $this->timeToHours($totalWorkingHour->late)>0){
                $late++;
            }
        }

        return $late;
    }
}
