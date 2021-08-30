<?php

namespace App\Http\Controllers\BackEndCon;

use Illuminate\Http\Request;
use App\Http\Controllers\BackEndCon\Controller;
use Auth;
use App\Loan;
use App\LoanApprove;
use App\Month;
use App\PayrollSummery;
use App\Employee;
// use App\Month;
use DB;

class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function loan_certificate($emp_id, $loan_id)
    {
        // echo $loan_id; exit();
        // $loan =Loan::where()
        $loan_emp=Employee::where('emp_id', $emp_id)->first();
        $loan=Loan::where('id', $loan_id)->first();
        $loan_approve_paid=LoanApprove::where('loan_id', $loan_id)
        ->where('flag', 1)->sum('paid_amount');

        // return $loan; exit(); 

        return view('Admin.loan.loan_certificate', compact('loan_emp', 'loan', 'loan_approve_paid'));
    }
     public function loan_report()
    {
        // $id =   Auth::guard('admin')->user();

        // $loan = Loan::join('tbl_loanapprove', 'tbl_loanapprove.loan_id', '=', 'tbl_loans.id')
        //               ->get();

        // return $loan[0]->employee->emp_name; exit();

        // $projectDetails=ProjectDetails::find('1');
        // $tax = Payroll::orderBy('id', 'ASC')
        //             // ->join('tbl_emp')
        //             ->where('head_id', 7)
        //             ->where('amount','>' ,0)
        //                 ->get();

        // $id =   Auth::guard('admin')->user();
        // $mainlink = $this->adminmainmenu();
        // $sublink = $this->adminsubmenu();
        // $Adminminlink = $this->adminlink();
        // $adminsublink = $this->adminsublink();
        // if($id->suser_level=="1"){
        //     $Loan=Loan::with(['employee','loan_month'])->get();
        // }elseif($id->suser_level=="4"){
        //     $Loan=Loan::with(['employee','loan_month'])
        //         ->join('tbl_employee','tbl_loans.emp_id','=','tbl_employee.emp_id')
        //         ->where('tbl_employee.emp_seniorid',$id->suser_empid)
        //         ->orWhere('tbl_employee.emp_authperson',$id->suser_empid)
        //         ->get();
        // }else{
        //     $Loan=Loan::with(['employee','loan_month'])
        //         ->where('emp_id',$id->suser_empid)
        //         ->get();
        // }

        // // return view('Admin.loan.index',compact('id','mainlink','sublink','Adminminlink','adminsublink','Loan'));
        // return view('Admin.loan.loan_report', compact('tax', 'projectDetails', 'id','mainlink','sublink','Adminminlink','adminsublink','Loan'));
        $id =   Auth::guard('admin')->user();
        $mainlink = $this->adminmainmenu();
        $sublink = $this->adminsubmenu();
        $Adminminlink = $this->adminlink();
        $adminsublink = $this->adminsublink();
        if($id->suser_level=="1"){
            $Loan=Loan::with(['employee','loan_month', 'loanapprove'])
            ->where('flag', 1)
            ->get();
        }elseif($id->suser_level=="4"){
            $Loan=Loan::with(['employee','loan_month'])
                ->join('tbl_employee','tbl_loans.emp_id','=','tbl_employee.emp_id')
                ->where('tbl_employee.emp_seniorid',$id->suser_empid)
                ->orWhere('tbl_employee.emp_authperson',$id->suser_empid)
                ->where('tbl_loans.flag', 1)
                ->where('tbl_loans.after_installment', 0)
                ->get();
        }else{
            $Loan=Loan::with(['employee','loan_month'])
                ->where('emp_id',$id->suser_empid)
                ->where('tbl_loans.flag', 1)
                ->where('tbl_loans.after_installment', 0)
                ->get();
        }

        // return $Loan; exit();

        foreach ($Loan as $row => $key) {
            $loan_info_start = LoanApprove::
                         where('tbl_loanapprove.loan_id', $key->id)
                         ->join('tbl_months', 'tbl_months.id', '=', 'tbl_loanapprove.month')
                         ->Select('tbl_months.month', 'tbl_loanapprove.loan_id')
                         ->first();
            $loan_info_end = LoanApprove::orderBy('tbl_loanapprove.month', 'DESC')
                         ->where('tbl_loanapprove.loan_id', $key->id)
                         ->join('tbl_months', 'tbl_months.id', '=', 'tbl_loanapprove.month')
                         ->Select('tbl_months.month', 'tbl_loanapprove.loan_id')
                         ->first();
            // return $loan_info_start; 

        }

        
        // return $loan_info_start; 

        // foreach ($Loan as $row) {
        //     $loan_info = LoanApprove::
        //                  where('loan_id', $row->id)
        //                  ->get();

        //     return $loan_info; 
        // }
        $test=Loan::join('tbl_loanapprove', 'tbl_loanapprove.loan_id', '=', 'tbl_loans.id')
        ->select('tbl_loans.emp_id', 'tbl_loanapprove.month', 'tbl_loanapprove.id')
        ->get();

        foreach($test as $row){
            if(strlen($row->month)==1){
                $mon='0'.$row->month;}
            else{
                $mon=$row->month;
            }

            $demo=PayrollSummery::where('payroll_date_from', '2021-'.$mon.'-01')
            ->where('emp_id', $row->emp_id)
            ->where('advance','>' ,0)
            ->first();

            $loan = Loan::where('emp_id', $row->emp_id)
                        ->select('amount')
                        ->first();

            $loan_id = Loan::where('emp_id', $row->emp_id)
                        ->select('id')
                        ->first();

            if($loan_id){
                $month_count = LoanApprove::where('loan_id', $loan_id->id)
                ->where('after_installment', 0)
                ->count();


                if($month_count!=0){
                    $monthly_loan = $loan->amount/$month_count;
                }
                elseif($month_count==0)
                {
                    $monthly_loan=0;
                }
            }

            // return $demo->count(); exit();
            if($demo){
                DB::table('tbl_loanapprove')
                ->where('id', $row->id)
                ->where('month', $row->month)
                ->where('after_installment', 0)
                ->update(array('flag' => 1, 'paid_amount' => $demo->advance, 'monthly_installment' => $monthly_loan));


            }
            if(!$demo){
                DB::table('tbl_loanapprove')
                ->where('id', $row->id)
                ->where('month', $row->month)
                ->where('after_installment', 0)
                ->update(array('flag' => 0, 'paid_amount' => 0, 'monthly_installment' => $monthly_loan));


            }




        }
        // return '2021-'.$mon.'-01'; exit();
        $all_emp = Employee::where('emp_status', 1)->get();
        $all_month = Month::get();

        // return $all_emp; exit();
        
        
        $flag=0;

        

        return view('Admin.loan.loan_report',compact('id','mainlink','sublink','Adminminlink','adminsublink','Loan', 'loan_info_start', 'loan_info_end', 'all_emp', 'all_month', 'flag'));
    }

    public function loan_report_filter(Request $request)
    {
        // echo "string";
        // return $request; exit();

        $id =   Auth::guard('admin')->user();
        $mainlink = $this->adminmainmenu();
        $sublink = $this->adminsubmenu();
        $Adminminlink = $this->adminlink();
        $adminsublink = $this->adminsublink();
        if($id->suser_level=="1"){
            $Loan=Loan::with(['employee','loan_month', 'loanapprove'])
            ->where('flag', 1)
            ->where('emp_id', $request->emp_id)
            ->where('month', $request->month_id)
            ->get();
        }elseif($id->suser_level=="4"){
            $Loan=Loan::with(['employee','loan_month'])
                ->join('tbl_employee','tbl_loans.emp_id','=','tbl_employee.emp_id')
                ->where('tbl_employee.emp_seniorid',$id->suser_empid)
                ->orWhere('tbl_employee.emp_authperson',$id->suser_empid)
                ->where('tbl_loans.flag', 1)
                ->where('tbl_loans.after_installment', 0)
                ->where('tbl_loans.emp_id', $request->emp_id)
                 ->where('month', $request->month_id)
                ->get();
        }else{
            $Loan=Loan::with(['employee','loan_month'])
                ->where('emp_id',$id->suser_empid)
                ->where('tbl_loans.flag', 1)
                ->where('tbl_loans.after_installment', 0)
                ->where('tbl_loans.emp_id', $request->emp_id)
                 ->where('month', $request->month_id)
                ->get();
        }

        // return $Loan; exit();

        foreach ($Loan as $row => $key) {
            $loan_info_start = LoanApprove::
                         where('tbl_loanapprove.loan_id', $key->id)
                         ->join('tbl_months', 'tbl_months.id', '=', 'tbl_loanapprove.month')
                         ->Select('tbl_months.month', 'tbl_loanapprove.loan_id')
                         ->first();
            $loan_info_end = LoanApprove::orderBy('tbl_loanapprove.month', 'DESC')
                         ->where('tbl_loanapprove.loan_id', $key->id)
                         ->join('tbl_months', 'tbl_months.id', '=', 'tbl_loanapprove.month')
                         ->Select('tbl_months.month', 'tbl_loanapprove.loan_id')
                         ->first();
            // return $loan_info_start; 

        }

        
        // return $loan_info_start; 

        // foreach ($Loan as $row) {
        //     $loan_info = LoanApprove::
        //                  where('loan_id', $row->id)
        //                  ->get();

        //     return $loan_info; 
        // }
        $test=Loan::join('tbl_loanapprove', 'tbl_loanapprove.loan_id', '=', 'tbl_loans.id')
        ->select('tbl_loans.emp_id', 'tbl_loanapprove.month', 'tbl_loanapprove.id')
        ->get();

        foreach($test as $row){
            if(strlen($row->month)==1){
                $mon='0'.$row->month;}
            else{
                $mon=$row->month;
            }

            $demo=PayrollSummery::where('payroll_date_from', '2021-'.$mon.'-01')
            ->where('emp_id', $row->emp_id)
            ->where('advance','>' ,0)
            ->first();

            $loan = Loan::where('emp_id', $row->emp_id)
                        ->select('amount')
                        ->first();

            $loan_id = Loan::where('emp_id', $row->emp_id)
                        ->select('id')
                        ->first();

            if($loan_id){
                $month_count = LoanApprove::where('loan_id', $loan_id->id)
                ->where('after_installment', 0)
                ->count();


                if($month_count!=0){
                    $monthly_loan = $loan->amount/$month_count;
                }
                elseif($month_count==0)
                {
                    $monthly_loan=0;
                }
            }

            // return $demo->count(); exit();
            if($demo){
                DB::table('tbl_loanapprove')
                ->where('id', $row->id)
                ->where('month', $row->month)
                ->where('after_installment', 0)
                ->update(array('flag' => 1, 'paid_amount' => $demo->advance, 'monthly_installment' => $monthly_loan));


            }
            if(!$demo){
                DB::table('tbl_loanapprove')
                ->where('id', $row->id)
                ->where('month', $row->month)
                ->where('after_installment', 0)
                ->update(array('flag' => 0, 'paid_amount' => 0, 'monthly_installment' => $monthly_loan));


            }




        }
        // return '2021-'.$mon.'-01'; exit();
        $all_emp = Employee::where('emp_status', 1)->get();
        $all_month = Month::get();

        // return $all_emp; exit();
        
        $flag=1;

        $emp_filter=Employee::where('emp_id', $request->emp_id)->first();
        $month_filter=Month::where('id', $request->month_id)->first();


        

        return view('Admin.loan.loan_report',compact('id','mainlink','sublink','Adminminlink','adminsublink','Loan', 'loan_info_start', 'loan_info_end', 'all_emp', 'all_month', 'flag', 'emp_filter', 'month_filter'));
    }

    public function loan_report_print()
    {
        // $id =   Auth::guard('admin')->user();

        // $loan = Loan::join('tbl_loanapprove', 'tbl_loanapprove.loan_id', '=', 'tbl_loans.id')
        //               ->get();

        // return $loan[0]->employee->emp_name; exit();

        // $projectDetails=ProjectDetails::find('1');
        // $tax = Payroll::orderBy('id', 'ASC')
        //             // ->join('tbl_emp')
        //             ->where('head_id', 7)
        //             ->where('amount','>' ,0)
        //                 ->get();

        // $id =   Auth::guard('admin')->user();
        // $mainlink = $this->adminmainmenu();
        // $sublink = $this->adminsubmenu();
        // $Adminminlink = $this->adminlink();
        // $adminsublink = $this->adminsublink();
        // if($id->suser_level=="1"){
        //     $Loan=Loan::with(['employee','loan_month'])->get();
        // }elseif($id->suser_level=="4"){
        //     $Loan=Loan::with(['employee','loan_month'])
        //         ->join('tbl_employee','tbl_loans.emp_id','=','tbl_employee.emp_id')
        //         ->where('tbl_employee.emp_seniorid',$id->suser_empid)
        //         ->orWhere('tbl_employee.emp_authperson',$id->suser_empid)
        //         ->get();
        // }else{
        //     $Loan=Loan::with(['employee','loan_month'])
        //         ->where('emp_id',$id->suser_empid)
        //         ->get();
        // }

        // // return view('Admin.loan.index',compact('id','mainlink','sublink','Adminminlink','adminsublink','Loan'));
        // return view('Admin.loan.loan_report', compact('tax', 'projectDetails', 'id','mainlink','sublink','Adminminlink','adminsublink','Loan'));
        $id =   Auth::guard('admin')->user();
        $mainlink = $this->adminmainmenu();
        $sublink = $this->adminsubmenu();
        $Adminminlink = $this->adminlink();
        $adminsublink = $this->adminsublink();
        if($id->suser_level=="1"){
            $Loan=Loan::with(['employee','loan_month', 'loanapprove'])
            ->where('flag', 1)
            ->get();
        }elseif($id->suser_level=="4"){
            $Loan=Loan::with(['employee','loan_month'])
                ->join('tbl_employee','tbl_loans.emp_id','=','tbl_employee.emp_id')
                ->where('tbl_employee.emp_seniorid',$id->suser_empid)
                ->orWhere('tbl_employee.emp_authperson',$id->suser_empid)
                ->where('tbl_loans.flag', 1)
                ->get();
        }else{
            $Loan=Loan::with(['employee','loan_month'])
                ->where('emp_id',$id->suser_empid)
                ->where('tbl_loans.flag', 1)
                ->get();
        }

        // return $Loan; exit();

        foreach ($Loan as $row => $key) {
            $loan_info_start = LoanApprove::
                         where('tbl_loanapprove.loan_id', $key->id)
                         ->join('tbl_months', 'tbl_months.id', '=', 'tbl_loanapprove.month')
                         ->Select('tbl_months.month', 'tbl_loanapprove.loan_id')
                         ->first();
            $loan_info_end = LoanApprove::orderBy('tbl_loanapprove.month', 'DESC')
                         ->where('tbl_loanapprove.loan_id', $key->id)
                         ->join('tbl_months', 'tbl_months.id', '=', 'tbl_loanapprove.month')
                         ->Select('tbl_months.month', 'tbl_loanapprove.loan_id')
                         ->first();
            // return $loan_info_start; 

        }

        
        // return $loan_info_start; 

        // foreach ($Loan as $row) {
        //     $loan_info = LoanApprove::
        //                  where('loan_id', $row->id)
        //                  ->get();

        //     return $loan_info; 
        // }
        $test=Loan::join('tbl_loanapprove', 'tbl_loanapprove.loan_id', '=', 'tbl_loans.id')
        ->select('tbl_loans.emp_id', 'tbl_loanapprove.month', 'tbl_loanapprove.id')
        ->get();

        foreach($test as $row){
            if(strlen($row->month)==1){
                $mon='0'.$row->month;}
            else{
                $mon=$row->month;
            }

            $demo=PayrollSummery::where('payroll_date_from', '2021-'.$mon.'-01')
            ->where('emp_id', $row->emp_id)
            ->where('advance','>' ,0)
            ->first();

            // return $demo->count(); exit();
            if($demo){
                DB::table('tbl_loanapprove')
                ->where('id', $row->id)
                ->where('month', $row->month)
                ->where('after_installment', 0)
                ->update(array('flag' => 1));


            }
            if(!$demo){
                DB::table('tbl_loanapprove')
                ->where('id', $row->id)
                ->where('month', $row->month)
                ->where('after_installment', 0)
                ->update(array('flag' => 0));


            }




        }
        // return '2021-'.$mon.'-01'; exit();
        
        


        

        return view('Admin.loan.loan_print',compact('id','mainlink','sublink','Adminminlink','adminsublink','Loan', 'loan_info_start', 'loan_info_end'));
    }

    public function index()
    {
        $test=Loan::join('tbl_loanapprove', 'tbl_loanapprove.loan_id', '=', 'tbl_loans.id')
        ->select('tbl_loans.emp_id', 'tbl_loanapprove.month', 'tbl_loanapprove.id')
        ->get();

        foreach($test as $row){
            if(strlen($row->month)==1){
                $mon='0'.$row->month;}
            else{
                $mon=$row->month;
            }

            $demo=PayrollSummery::where('payroll_date_from', '2021-'.$mon.'-01')
            ->where('emp_id', $row->emp_id)
            ->where('advance','>' ,0)
            ->first();

            $loan = Loan::where('emp_id', $row->emp_id)
                        ->select('amount')
                        ->first();

            $loan_id = Loan::where('emp_id', $row->emp_id)
                        ->select('id')
                        ->first();

            if($loan_id){
                $month_count = LoanApprove::where('loan_id', $loan_id->id)
                 ->where('after_installment', 0)
                 ->count();


                if($month_count!=0){
                    $monthly_loan = $loan->amount/$month_count;
                }
                elseif($month_count==0)
                {
                    $monthly_loan=0;
                }
            }

            // return $demo->count(); exit();
            if($demo){
                DB::table('tbl_loanapprove')
                ->where('id', $row->id)
                ->where('month', $row->month)
                ->where('after_installment', 0)
                ->update(array('flag' => 1, 'paid_amount' => $demo->advance, 'monthly_installment' => $monthly_loan));


            }
            if(!$demo){
                DB::table('tbl_loanapprove')
                ->where('id', $row->id)
                ->where('month', $row->month)
                ->where('after_installment', 0)
                ->update(array('flag' => 0, 'paid_amount' => 0, 'monthly_installment' => $monthly_loan));


            }
        }
        $id =   Auth::guard('admin')->user();
        $mainlink = $this->adminmainmenu();
        $sublink = $this->adminsubmenu();
        $Adminminlink = $this->adminlink();
        $adminsublink = $this->adminsublink();
        if($id->suser_level=="1"){
            $Loan=Loan::with(['employee','loan_month'])->get();
        }elseif($id->suser_level=="4"){
            $Loan=Loan::with(['employee','loan_month'])
                ->join('tbl_employee','tbl_loans.emp_id','=','tbl_employee.emp_id')
                ->where('tbl_employee.emp_seniorid',$id->suser_empid)
                ->orWhere('tbl_employee.emp_authperson',$id->suser_empid)
                ->get();
        }else{
            $Loan=Loan::with(['employee','loan_month'])
                ->where('emp_id',$id->suser_empid)
                ->get();
        }

        return view('Admin.loan.index',compact('id','mainlink','sublink','Adminminlink','adminsublink','Loan'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $id =   Auth::guard('admin')->user();
        $mainlink = $this->adminmainmenu();
        $sublink = $this->adminsubmenu();
        $Adminminlink = $this->adminlink();
        $adminsublink = $this->adminsublink();
        $month=Month::get();

        return view('Admin.loan.apply',compact('id','mainlink','sublink','Adminminlink','adminsublink','month'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,Loan $loan)
    {
        $this->validate($request,[
            'purpose'=>'required',
            'amount'=>'required|min:0',
            'month'=>'required',
        ]);
        if(count(explode(' ',$request->purpose))<4){
            session()->flash('error','Please Write at least 5 words as purpose');
            return redirect()->back();
        }

        if($request->month<date('m')){
            session()->flash('error','You cannot apply loan for previous month.');
            return redirect()->back();
        }

        $loan->fill($request->all())->save();
        if(isset($loan)){
            session()->flash('success','Application for Loan Submitted successfully.');
        }else{
            session()->flash('error','Something Went Wrong!');
        }
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($loan_id)
    {
        $id =   Auth::guard('admin')->user();
        $mainlink = $this->adminmainmenu();
        $sublink = $this->adminsubmenu();
        $Adminminlink = $this->adminlink();
        $adminsublink = $this->adminsublink();
        $Loan=Loan::with(['employee','loan_month'])->find($loan_id);
        $employee_loan=Loan::where('emp_id',$Loan->employee->emp_id)->get();
        $loan_status = array(
            'total' => count($employee_loan), 
            'approved' => $employee_loan->where('flag','1')->count(), 
            'completed' => $employee_loan->where('flag','2')->count(), 
            'pending' => $employee_loan->where('flag','0')->count(), 
            'rejected' => $employee_loan->where('flag','3')->count(), 
        );

        $currentyear = [];
        $nextyear = [];
        $datanext = [];
        $totalmonth = $Loan->month+60;
        if($totalmonth > 12){
            $yearrest = 12-$Loan->month;
            $nextrest = 60-$yearrest;
            for ($c=0; $c < $yearrest; $c++) { 
                array_push($currentyear, $Loan->year);
            }
            // for ($n=0; $n < $nextrest; $n++) { 
            //     array_push($nextyear, $Loan->year+1);
            // }
            $j=1;
           // for ($n=0; $n < $nextrest; $n++) { 
                 for ($n=0; $n < 5; $n++) { 
                
                for ($k=1; $k <=12; $k++) { 
                    array_push($nextyear, $Loan->year+$j);
                }
                $j++;   
            }
            // array_push($nextyear, $Loan->year+5);
            $years = array_merge($currentyear,$nextyear);
            $datacurrent = Month::whereBetween('id', [$Loan->month+1, 12])->get();

            // for ($x=0; $x < ; $x++) { 
            //     # code...
            // }
            // for ($n=1; $n <= 12; $n++) { 
            //     # code...
            //      $datanext1 = Month::where('id', $n)->first();
            // }
           
             // return $datanext; exit();
            // $id=1;
            // $n=1;
        
            for ($a=1; $a <+$nextrest ; $a++) { 
               for ($m=1; $m <= 12; $m++) { 
                    $datanext1 = Month::where('id', $m)->first();
                    
                        array_push($datanext, $datanext1);
                
                }
                
                 
                 // $n++;
                 
            }
            // exit();
            // return $datanext; exit();
            $present = json_decode(json_encode($datacurrent));
            $next = json_decode(json_encode($datanext));
            $months = array_merge($present, $next);

            // return count($months); exit();
        }else{
            $years = [];
            for ($i=0; $i < 6; $i++) { 
                array_push($years, $Loan->year);
            }
            $months = Month::whereBetween('id', [$Loan->month+1, $totalmonth])->get();
        }

        $installments=LoanApprove::with(['install_month'])->where('loan_id',$Loan->id)
        ->where('after_installment', 0)
        ->get();
        $installments_after=LoanApprove::with(['install_month'])->where('loan_id',$Loan->id)
        ->where('after_installment', 1)
        ->get();
        $pendingInstallments=$installments->where('flag','0');
        $each_installment=0;
        $after_installment=0;
        if(count($installments)>0){
            $each_installment=$this->decimal($Loan->amount/count($installments));
        }
        if(count($installments_after)>0){
            $after_installment=$this->decimal($Loan->amount/count($installments_after));
        }
        return view('Admin.loan.loan',compact('id','mainlink','sublink','Adminminlink','adminsublink','Loan','loan_status','months','years','installments','pendingInstallments','each_installment', 'after_installment', 'installments_after', 'loan_id'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($loan_id)
    {
        $loan=Loan::find($loan_id);
        if(isset($loan->id) && $loan->flag>0){
            session()->flash('error','Approved/Completed/Rejected Application cannot be updated!');
            return redirect()->back();
        }

        $id =   Auth::guard('admin')->user();
        $mainlink = $this->adminmainmenu();
        $sublink = $this->adminsubmenu();
        $Adminminlink = $this->adminlink();
        $adminsublink = $this->adminsublink();
        $loan=Loan::find($loan_id);
        $month=Month::get();

        return view('Admin.loan.edit',compact('id','mainlink','sublink','Adminminlink','adminsublink','loan','month'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'purpose'=>'required',
            'amount'=>'required|min:0',
            'month'=>'required',
        ]);
        if(count(explode(' ',$request->purpose))<4){
            session()->flash('error','Please Write at least 5 words as purpose');
            return redirect()->back();
        }

        if($request->month<date('m')){
            session()->flash('error','You cannot apply loan for previous month.');
            return redirect()->back();
        }

        $loan=Loan::find($id);
        $loan->fill($request->all())->save();
        if(isset($loan)){
            session()->flash('success','Application for Loan Updated successfully.');
        }else{
            session()->flash('error','Something Went Wrong!');
        }
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $loan=Loan::find($id);
        if(isset($loan->id)){
            if($loan->flag>0){
                return response()->json(["success"=>false,"msg"=>"Approved/Completed/Rejected Application cannot be deleted!"]);
            }else{
                if($loan->delete()){
                    return response()->json(["success"=>true]);
                }else{
                    return response()->json(["success"=>false,"msg"=>"Something Went Wrong!"]);
                }
            }
        }else{
            return response()->json(["success"=>false,"msg"=>"Loan Application not found!"]);
        }
    }

    public function approve(Request $request,$id)
    {
        $months=count($request->months);
        if($months==0){
            session()->flash('error','Please Select Months for installments.');
            return redirect()->back();
        }
        $success=0;
        $Loan=Loan::find($id);
        foreach ($request->months as $month) {
            $loanApprove=LoanApprove::insert([
                'loan_id'=>$Loan->id,
                'month'=>explode('&',$month)[0],
                'year'=>explode('&',$month)[1],
                'approved_by'=> Auth::guard('admin')->user()->suser_empid,
            ]);
            if($loanApprove){
                $success++;
            }
        }
        if($months==$success){
            $Loan->flag='1';
            $Loan->save();

            $dayarray = array(
                '1' => 'One',
                '2' => 'Two',
                '3' => 'Three',
                '4' => 'Four',
                '5' => 'Five',
                '6' => 'Six'
            );
            session()->flash('success',"This loan has been approved for next $dayarray[$months] installment");
            return redirect()->back();
        }else{
            LoanApprove::where('loan_id',$loan->id)->delete();
            session()->flash('error',"Something Went Wrong! Tray Again");
            return redirect()->back();
        }
        
    }

    public function reject($id)
    {
        $loan=Loan::find($id);
        if(isset($loan->id)){
            if($loan->flag>0){
                return response()->json(["success"=>false,"msg"=>"Approved/Completed/Rejected Application cannot be rejected!"]);
            }else{
                $loan->flag='3';
                $loan->approved_by=Auth::guard('admin')->user()->suser_empid;
                if($loan->save()){
                    return response()->json(["success"=>true]);
                }else{
                    return response()->json(["success"=>false,"msg"=>"Something Went Wrong!"]);
                }
            }
        }else{
            return response()->json(["success"=>false,"msg"=>"Loan Application not found!"]);
        }
    }

    public function submitMoney(Request $request,$id)
    {
        $submit=LoanApprove::find($request->month)->update([
            'flag'=>'1',
            'received_by' => Auth::guard('admin')->user()->suser_empid
        ]);
        if(isset($submit)){
            $pendingInstallments=LoanApprove::where(['loan_id'=>$id,'flag'=>'0'])->get();
            if(count($pendingInstallments)==0){
                Loan::find($id)->update([
                    'flag'=>'2'
                ]);
            }
            session()->flash('success','Money Has been submitted successfully');
        }else{
            session()->flash('error','Something Went Wrong! Try Again.');
        }
        return redirect()->back();
    }

    public function info($id)
    {
        $loanApprove=LoanApprove::with(['loan','install_month','approve','receive'])->find($id);

        $installment=0;
        if(count($loanApprove->loan->loanapprove)>0){
            $installment=$this->decimal($loanApprove->loan->amount/count($loanApprove->loan->loanapprove));
        }
        return view('Admin.loan.info',compact('loanApprove','installment'));
    }
}
