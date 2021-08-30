<?php

namespace App\Http\Controllers\BackEndCon;

use Illuminate\Http\Request;
use App\Http\Controllers\BackEndCon\Controller;
use Auth;
use App\Style;
use App\Piece;
use App\Production;
use App\Employee;
use App\Payment;

class ProductionController extends Controller
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
        $productions=Production::with(['employee','piece'])->where('pro_status','0')->get();
        return view('Admin.contractual.production.index',compact('id','mainlink','sublink','Adminminlink','adminsublink','productions'));
    }

    static public function totalPayment($pro_id)
    {
        return Payment::where('pro_id',$pro_id)->sum('amount');
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
        $styles=Style::where('sty_status','1')->get();
        $employees=Employee::get(['emp_id','emp_name','emp_empid']);
        return view('Admin.contractual.production.create',compact('id','mainlink','sublink','Adminminlink','adminsublink','styles','employees'));
    }

    public function getPiece($sty_id)
    {
        $data='';
        $pieces=Piece::where('pi_styleid',$sty_id)->get();
        if(isset($pieces[0])){
            foreach ($pieces as $piece) {
                $data.='<option value="'.$piece->pi_id.'">'.$piece->pi_name.'</option>';
            }
        }
        return $data;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,Production $production)
    {
        $this->validate($request,[
            'pro_empid' => 'required',
            'pro_pi_id' => 'required',
            'pro_no_dz' => 'required',
            'pro_totalcost' => 'required',
            'pro_startdate' => 'required',
            'pro_enddate' => 'required',
        ]);
        $production->fill($request->all())->save();
        if($production){
            session()->flash('success','Job Created Successfully.');
        }else{
            session()->flash('error','Somthing went Wrong!');
        }
        return redirect()->back();
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

    public function complete(Request $request)
    {
        $production=Production::find($request->pro_id);
        $production->pro_status='1';
        $production->pro_completation_notes=$request->pro_completation_notes;
        $production->save();
        if($production){
            session()->flash('success','This Job has been completed successfully');
            return response()->json(["success"=>true]);
        }else{
            return response()->json(["success"=>false,"msg"=>"Something went wrong!"]);
        }
    }

    public function paymentList()
    {
        $id =   Auth::guard('admin')->user();
        $mainlink = $this->adminmainmenu();
        $sublink = $this->adminsubmenu();
        $Adminminlink = $this->adminlink();
        $adminsublink = $this->adminsublink();
        $productions=Production::with(['employee','piece'])->where('pro_status','1')->get();
        return view('Admin.contractual.pay.index',compact('id','mainlink','sublink','Adminminlink','adminsublink','productions'));
    }

    public function payment(Request $request,Payment $payment)
    {
        $production=Production::with(['piece'])->find($request->pro_id);
        $payment->fill($request->all());
        $payment->given_by=Auth::guard('admin')->user()->suser_empid;
        $payment->save();
        if($payment){
            if($this->totalPayment($request->pro_id)>=($production->piece->pi_price_dz*$production->pro_no_dz)){
                $production->pro_status='2';
                $production->save();
                if($production){
                    session()->flash('success','This Job has been paid successfully');
                    return response()->json(["success"=>true]);
                }else{
                    return response()->json(["success"=>false,"msg"=>"Something Went Wrong!"]);
                }
            }else{
                session()->flash('success','This Payment has been submitted successfully. Curent due of this job is : '.(($production->piece->pi_price_dz*$production->pro_no_dz)-$this->totalPayment($request->pro_id)));
                return response()->json(["success"=>true]);
            }
        }else{
            return response()->json(["success"=>false,"msg"=>"Something Went Wrong!"]);
        }
    }

    public function paymentHistory($pro_id)
    {
        $data='<table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>SL</th>
                        <th>Amount</th>
                        <th>Received By</th>
                        <th>Received At</th>
                    </tr>
                </thead>
                <tbody>';
        $payments=Payment::with(['givenby'])->where(['pro_id'=>$pro_id])->orderBy('id','desc')->get();
        $sl=0;
        $total=0;
        if(isset($payments[0])){
            foreach ($payments as $payment) {
                $sl++;
                $total+=$payment->amount;
                $data.='<tr>
                            <td>'.$sl.'</td>
                            <td class="text-right">'.$payment->amount.' BDT</td>
                            <td>'.$payment->givenby->emp_name.' ('.$payment->givenby->emp_empid.')</td>
                            <td>'.$payment->created_at.'</td>
                        </tr>';
            }
        }
                
        $data.='<tr>
                    <td class="text-right"><strong>Total :</strong></td>
                    <td class="text-right"><strong>'.$total.' BDT</strong></td>
                    <td colspan="2"></td>
                </tr>
                </tbody>
            </table>';
        return $data;
    }

    public function paidList()
    {
        $id =   Auth::guard('admin')->user();
        $mainlink = $this->adminmainmenu();
        $sublink = $this->adminsubmenu();
        $Adminminlink = $this->adminlink();
        $adminsublink = $this->adminsublink();
        $productions=Production::with(['employee','piece'])->where('pro_status','2')->get();
        return view('Admin.contractual.pay.paid',compact('id','mainlink','sublink','Adminminlink','adminsublink','productions'));
    }
}
