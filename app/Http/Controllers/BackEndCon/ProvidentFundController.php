<?php

namespace App\Http\Controllers\BackEndCon;

use Illuminate\Http\Request;
use App\Http\Controllers\BackEndCon\Controller;
use Auth;
use App\ProvidentFundSetup;
use App\ProvidentFund;
use App\ProvidentRefund;
use App\Employee;
use App\Month;
use App\SalaryHead;
use App\Payroll;

class ProvidentFundController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $id =   Auth::guard('admin')->user();
        $mainlink = $this->adminmainmenu();
        $sublink = $this->adminsubmenu();
        $Adminminlink = $this->adminlink();
        $adminsublink = $this->adminsublink();

        $refundPending = ProvidentRefund::where('status','0')->count();
        $refundApproved = ProvidentRefund::where('status','1')->count();
        $refundRejected = ProvidentRefund::where('status','2')->count();
        $refundWithdrawn = ProvidentRefund::where('status','3')->count();
        $month=date('m');
        $year=date('Y');
        $funds = ProvidentFund::where(['year'=>$year,'month'=>(int)$month])->with(['employee'])->orderBy('month','asc')->get();

        $total_fund=$this->total_fund();
        $Month=Month::get();
        return view('Admin.providentFund.index',compact('id','mainlink','sublink','Adminminlink','adminsublink','funds','total_fund','refundPending','refundApproved','refundRejected','refundWithdrawn','Month','month','year'));
    }

    public function search($month,$year)
    {
        $id =   Auth::guard('admin')->user();
        $mainlink = $this->adminmainmenu();
        $sublink = $this->adminsubmenu();
        $Adminminlink = $this->adminlink();
        $adminsublink = $this->adminsublink();

        $refundPending = ProvidentRefund::where('status','0')->count();
        $refundApproved = ProvidentRefund::where('status','1')->count();
        $refundRejected = ProvidentRefund::where('status','2')->count();
        $refundWithdrawn = ProvidentRefund::where('status','3')->count();
        $funds = ProvidentFund::where(['year'=>$year,'month'=>$month])->with('employee')->orderBy('month','asc')->get();

        $total_fund=$this->total_fund();
        $Month=Month::get();
        return view('Admin.providentFund.index',compact('id','mainlink','sublink','Adminminlink','adminsublink','funds','total_fund','refundPending','refundApproved','refundRejected','refundWithdrawn','Month','month','year'));
    }

    public function total_fund()
    {
        $record = ProvidentFund::all();
        $totalFemployee = $record->sum("employee_amount");
        $totalFcompany = $record->sum("company_amount");
        $total = $totalFemployee+$totalFcompany;

        $totalWithDrawnData = ProvidentRefund::where('status', '=', 3)->get();

        $withdrawn = $totalWithDrawnData->sum('approved_amount');
        $totalAfterWithdrawn = $total-$withdrawn;

        $data = '<table class="table table-bordered table-hover table-striped"><thead><tr><th width="45%">From employee <small>(All time)</small></th><th style="text-align: right;">'.$totalFemployee.' Taka</th></tr><tr><th>From company contribution to employee <small>(All time)</small></th><th style="text-align: right;">'.$totalFcompany.' Taka</th></tr><tr><th>Total <small>(All time)</small></th><th style="text-align: right;">'.$total.' Taka</th></tr><tr><th>Total withdrawn <small>(All time)</small></th><th style="text-align: right;">'.$withdrawn.' Taka</th></tr><tr><th>Total after withdrawn <small>(All time)</small></th><th style="text-align: right;">'.$totalAfterWithdrawn.' Taka</th></tr></thead></table>';
        return $data;
    }

    public function generate()
    {
        $id =   Auth::guard('admin')->user();
        $mainlink = $this->adminmainmenu();
        $sublink = $this->adminsubmenu();
        $Adminminlink = $this->adminlink();
        $adminsublink = $this->adminsublink();
        $Month=Month::get();
        return view('Admin.providentFund.generate',compact('id','mainlink','sublink','Adminminlink','adminsublink','Month'));
    }

    public function generateFund(Request $request)
    {
        $month=$request->month;
        $year=$request->year;
        $search=ProvidentFund::where(['month'=>$month,'year'=>$year])->get();
        if(isset($search[0])){
            $id =   Auth::guard('admin')->user();
            $mainlink = $this->adminmainmenu();
            $sublink = $this->adminsubmenu();
            $Adminminlink = $this->adminlink();
            $adminsublink = $this->adminsublink();
            $Month=$Month=Month::find($month);
            return view('Admin.providentFund.regenerate',compact('id','mainlink','sublink','Adminminlink','adminsublink','Month','year'));
        }else{

            $Employees=Employee::where('pf',1)->get(['emp_id']);
            $Basic=SalaryHead::find('1');
            $ProvidentFundSetup=ProvidentFundSetup::find('1');
            if($month<10){
                $date=$year.'-0'.$month.'-01';
            }else{
                $date=$year.'-'.$month.'-01';
            }
            
            $success=0;
            $error=0;
            foreach ($Employees as $emp) {
                $executionDate=Payroll::where([['head_date_of_execution','<=',$date],'emp_id'=>$emp->emp_id])->orderBy('head_date_of_execution','desc')->first();
                if(isset($executionDate->head_date_of_execution)){
                    $basicAllowance=Payroll::where([
                        'emp_id'=>$emp->emp_id,
                        'head_date_of_execution'=>$executionDate->head_date_of_execution,
                        'head_id'=>$Basic->head_id
                        ])
                        ->first(['amount']);
                    $employee_amount=0;
                    $company_amount=0;
                    if(isset($basicAllowance->amount)){
                        if($ProvidentFundSetup->employee_percentage>0){
                            $employee_amount=$this->decimal($basicAllowance->amount*($ProvidentFundSetup->employee_percentage/100));
                        }

                        if($ProvidentFundSetup->company_percentage>0){
                            $company_amount=$this->decimal($basicAllowance->amount*($ProvidentFundSetup->company_percentage/100));
                        }
                    }

                    $ProvidentFund=ProvidentFund::insert([
                        'emp_id' => $emp->emp_id,
                        'year' => $year,
                        'month' => $month,
                        'employee_amount' => $employee_amount,
                        'company_amount' => $company_amount,
                    ]);

                    if($ProvidentFund){
                        $success++;
                    }else{
                        $error++;
                    }
                }else{
                    $error++;
                }
            }

            if($success>0){
                session()->flash('success',$success." Employee's Provident fund has been generated successfully");
            }

            if($error>0){
                session()->flash('error',$error." Employee's Provident fund could not be generated due to error!");
            }

            return redirect()->back();
        }
    }

    public function delete($month,$year)
    {
        $delete=ProvidentFund::where(['month'=>$month,'year'=>$year])->delete();
        if($delete){
            session()->flash('success','Previously generated Provident Fund calculation has been Removed. Now you can re-generate it.');
            return redirect('provident-fund/generate/fund');
        }else{
            session()->flash('error','Whoops! Something went wrong! try again.');
            return redirect()->back();
        }
    }

    public function providentFundEmployee()
    {
        $id =   Auth::guard('admin')->user();
        $mainlink = $this->adminmainmenu();
        $sublink = $this->adminsubmenu();
        $Adminminlink = $this->adminlink();
        $adminsublink = $this->adminsublink();
        $employees=Employee::with(['designation','department'])->where('pf',1)->get();

        return view('Admin.providentFund.employees',compact('id','mainlink','sublink','Adminminlink','adminsublink','refund','funds','employees'));
    }

    public function provident_fund_print($emp_id)
    {
        $id =   Auth::guard('admin')->user();
        $mainlink = $this->adminmainmenu();
        $sublink = $this->adminsubmenu();
        $Adminminlink = $this->adminlink();
        $adminsublink = $this->adminsublink();
        $employee=Employee::with(['designation','department'])->find($emp_id);
        
        $providentFunds = ProvidentFund::where('emp_id', '=', $emp_id)->get();
        $employeTotal = $providentFunds->sum("employee_amount");
        $compnayTotal = $providentFunds->sum("company_amount");
        $totalWithDrawnData = ProvidentRefund::where('emp_id', '=', $emp_id)->where('status', '=', 3)->get();
        $totalWithDrawn = $totalWithDrawnData->sum('approved_amount');
        $from  = ProvidentFund::with(['provident_month'])->where('emp_id', '=', $emp_id)->orderBy('id', 'ASC')->first();
        $to = ProvidentFund::with(['provident_month'])->where('emp_id', '=', $emp_id)->orderBy('id', 'DESC')->first();
        $funds = ProvidentFund::where('emp_id', '=', $emp_id)->get();
        $years = ProvidentFund::where('emp_id', '=', $emp_id)->groupBY('year')->get();

        return view('Admin.providentFund.print',compact('id','mainlink','sublink','Adminminlink','adminsublink','refund','funds','employee','providentFunds','employeTotal','compnayTotal','totalWithDrawn','from','to','providentFundsYear','years'));
    }

    public function providentFundEmployeeDetails($emp_id)
    {
        $id =   Auth::guard('admin')->user();
        $mainlink = $this->adminmainmenu();
        $sublink = $this->adminsubmenu();
        $Adminminlink = $this->adminlink();
        $adminsublink = $this->adminsublink();
        $employee=Employee::with(['designation','department'])->find($emp_id);
        
        $providentFunds = ProvidentFund::where('emp_id', '=', $emp_id)->get();
        $employeTotal = $providentFunds->sum("employee_amount");
        $compnayTotal = $providentFunds->sum("company_amount");
        $totalWithDrawnData = ProvidentRefund::where('emp_id', '=', $emp_id)->where('status', '=', 3)->get();
        $totalWithDrawn = $totalWithDrawnData->sum('approved_amount');
        $from  = ProvidentFund::with(['provident_month'])->where('emp_id', '=', $emp_id)->orderBy('id', 'ASC')->first();
        $to = ProvidentFund::with(['provident_month'])->where('emp_id', '=', $emp_id)->orderBy('id', 'DESC')->first();
        $funds = ProvidentFund::where('emp_id', '=', $emp_id)->get();
        $years = ProvidentFund::where('emp_id', '=', $emp_id)->groupBY('year')->get();

        return view('Admin.providentFund.employeeView',compact('id','mainlink','sublink','Adminminlink','adminsublink','refund','funds','employee','providentFunds','employeTotal','compnayTotal','totalWithDrawn','from','to','providentFundsYear','years'));
    }

    public function apply(Request $request)
    {
        $id =   Auth::guard('admin')->user();

        $pending = ProvidentRefund::where('emp_id', '=', $id->suser_empid)->where('status', '!=', 1)->count();
        if($pending){
            session()->flash('error',"You already have a pending request. You can only apply again after it's approved or getting rejected by the authority");
            return redirect()->back();
        }

        $this->validate($request, [
            'purpose' => 'required|min:10|max:250',
        ]);

        $withdrawn = new ProvidentRefund;
        $data = $request->all();
        $withdrawn->fill($data);

        $withdrawn->emp_id = $id->suser_empid;
        $withdrawn->apply_date = date('Y-m-d');
        $withdrawn->purpose = $request->purpose;
        $withdrawn->requested_amount = $request->requested_amount;
        $withdrawn->save();
        if($withdrawn){
            session()->flash('success',"Provident Fund withdraw request applied successfully.");
        }else{
            session()->flash('error',"Whoops! Something Went Wrong!");
        }

        return redirect()->back();
    }

    public function pendingRefund()
    {
        $id =   Auth::guard('admin')->user();
        $mainlink = $this->adminmainmenu();
        $sublink = $this->adminsubmenu();
        $Adminminlink = $this->adminlink();
        $adminsublink = $this->adminsublink();
        $refunds = ProvidentRefund::with(['employee'])->where('status', '=', 0)->get();

        return view('Admin.providentFund.pendingRefund',compact('id','mainlink','sublink','Adminminlink','adminsublink','refunds'));
    }

    public function approvedRefund()
    {
        $id =   Auth::guard('admin')->user();
        $mainlink = $this->adminmainmenu();
        $sublink = $this->adminsubmenu();
        $Adminminlink = $this->adminlink();
        $adminsublink = $this->adminsublink();
        $refunds = ProvidentRefund::with(['employee'])->where('status', '=', 1)->get();

        return view('Admin.providentFund.approvedRefund',compact('id','mainlink','sublink','Adminminlink','adminsublink','refunds'));
    }

    public function withdrawnRefund()
    {
        $id =   Auth::guard('admin')->user();
        $mainlink = $this->adminmainmenu();
        $sublink = $this->adminsubmenu();
        $Adminminlink = $this->adminlink();
        $adminsublink = $this->adminsublink();
        $refunds = ProvidentRefund::with(['employee'])->where('status', '=', 3)->get();

        return view('Admin.providentFund.withdrawnRefund',compact('id','mainlink','sublink','Adminminlink','adminsublink','refunds'));
    }

    public function rejectedRefund()
    {
        $id =   Auth::guard('admin')->user();
        $mainlink = $this->adminmainmenu();
        $sublink = $this->adminsubmenu();
        $Adminminlink = $this->adminlink();
        $adminsublink = $this->adminsublink();
        $refunds = ProvidentRefund::with(['employee'])->where('status', '=', 2)->get();

        return view('Admin.providentFund.rejectedRefund',compact('id','mainlink','sublink','Adminminlink','adminsublink','refunds'));
    }

    public function approve(Request $request)
    {
        $refund=ProvidentRefund::find($request->refund_id);
        if($refund->status!="0"){
            return response()->json([
                "success"=>false,
                "msg"=>"You Cannot Approve a Approved or Rejected Request."
            ]);
        }

        if($request->approved_amount<0 || $request->approved_amount>$refund->requested_amount){
            return response()->json([
                "success"=>false,
                "msg"=>"Approve amount cannot be less than 0 or grater than Requested amount!"
            ]);
        }

        $refund->approved_date=date('Y-m-d');
        $refund->approved_amount=$request->approved_amount;
        $refund->status=1;
        $refund->save();
        if($refund){
            return response()->json([
                "success"=>true,
                "msg"=>"Provident fund withdraw application approved successfully"
            ]);
        }else{
            return response()->json([
                "success"=>false,
                "msg"=>"Something went wrong!"
            ]);
        }
    }

    public function reject(Request $request)
    {
        $refund=ProvidentRefund::find($request->refund_id);
        if($refund->status!="0"){
            return response()->json([
                "success"=>false,
                "msg"=>"You Cannot Reject a Approved or Rejected Request."
            ]);
        }

        if(empty($request->reason_of_rejection)){
            return response()->json([
                "success"=>false,
                "msg"=>"Please Write Reajon of Rejection!"
            ]);
        }

        $refund->approved_date=date('Y-m-d');
        $refund->reason_of_rejection=$request->reason_of_rejection;
        $refund->status=2;
        $refund->save();
        if($refund){
            return response()->json([
                "success"=>true,
                "msg"=>"Provident fund withdraw application rejected successfully"
            ]);
        }else{
            return response()->json([
                "success"=>false,
                "msg"=>"Something went wrong!"
            ]);
        }
    }

    public function withdraw(Request $request)
    {
        $refund=ProvidentRefund::find($request->refund_id);
        if($refund->status!="1"){
            return response()->json([
                "success"=>false,
                "msg"=>"You Cannot withdraw a Rejected Request."
            ]);
        }

        if(empty($request->coopon_number)){
            return response()->json([
                "success"=>false,
                "msg"=>"Please Write Coopon Number!"
            ]);
        }

        $refund->coopon_number=$request->coopon_number;
        $refund->status=3;
        $refund->save();
        if($refund){
            return response()->json([
                "success"=>true,
                "msg"=>"Provident fund withdrawn successfully"
            ]);
        }else{
            return response()->json([
                "success"=>false,
                "msg"=>"Something went wrong!"
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
