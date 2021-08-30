<?php

namespace App\Http\Controllers\BackEndCon;

use Illuminate\Http\Request;
use App\Http\Controllers\BackEndCon\Controller;
use App\Http\Controllers\BackEndCon\ToolsController;
use Auth;
use App\Employee;
use App\TotalWorkinghours;
use App\WorkingDayAdjustment;

class WorkingDayAdjustmentController extends Controller
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
        if($id->suser_level=="1"){
            $adjustments=WorkingDayAdjustment::with('employee','examiner')->get();
        }elseif($id->suser_level=="4"){
            $adjustments=WorkingDayAdjustment::with('employee','examiner')
                ->join('tbl_employee','tbl_employee.emp_id','=','tbl_workingdayadjustment.emp_id')
                ->where(function($query){
                    return $query->where('tbl_employee.emp_seniorid',$id->suser_empid)
                                 ->orWhere('tbl_employee.emp_authperson',$id->suser_empid)
                                 ->orWhere('emp_id',$id->suser_empid);
                })
                ->get();
        }else{
            $adjustments=WorkingDayAdjustment::with('employee','examiner')->where('emp_id',$id->suser_empid)->get();
        }

        return view('Admin.workingDayAdjustment.index',compact('id','mainlink','sublink','Adminminlink','adminsublink','adjustments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Admin.workingDayAdjustment.apply');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,WorkingDayAdjustment $adjust)
    {
        $id =   Auth::guard('admin')->user();

        if(strtotime($request->to) < strtotime(date('Y-m-d'))){
            session()->flash('error','Adjustment Date is Invalid!');
            return redirect()->back();
        }

        $present=false;
        $Tools=new ToolsController();
        $employee=Employee::find($id->suser_empid);
            $twh=TotalWorkinghours::where(['emp_id'=>$id->suser_empid,'date'=>$request->for])->first();
            if(isset($twh) && $twh->present=="1"){
                if($Tools->checkHoliday($request->for)=="1" || $Tools->checkWeekend($employee,$request->for)=="1"){
                    $present=true;
                }
            }

        if(!$present){
            session()->flash('error','You dont have attendance on '.$request->for);
            return redirect()->back();
        }
        $adjust->fill($request->all());
        $adjust->emp_id=$id->suser_empid;
        $adjust->save();
        if($adjust){
            session()->flash('success','Adjustment day submitted successfully!');
            return redirect()->back();
        }
        session()->flash('error','Whoops!! Something went wrong!');
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
        $adjust=WorkingDayAdjustment::find($id);
        $adjust->fill($request->all());
        $adjust->examined_by=Auth::guard('admin')->user()->suser_empid;
        $adjust->save();
        if($adjust){
            if($request->status=="1"){
                return response()->json([
                    'success' => true,
                    'class' => 'btn-success',
                    'text' => 'Approved',
                ]);
            }elseif($request->status=="2"){
                return response()->json([
                    'success' => true,
                    'class' => 'btn-danger',
                    'text' => 'Denied'
                ]);
            }
        }

        return response()->json([
            'success' => false,
            'msg' => 'Something Went Wrong!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $adjust=WorkingDayAdjustment::find($id);
        if($adjust->status=="0"){
            $adjust->delete();
            if($adjust){
                return response()->json([
                    'success' => true,
                ]);
            }
            return response()->json([
                'success' => false,
                'msg' => 'Something Went Wrong!'
            ]);
        }

        return response()->json([
            'success' => false,
            'msg' => 'Cannot be Deleted!'
        ]);
    }
}
