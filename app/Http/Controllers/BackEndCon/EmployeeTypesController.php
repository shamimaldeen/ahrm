<?php

namespace App\Http\Controllers\BackEndCon;

use Illuminate\Http\Request;
use App\Http\Controllers\BackEndCon\Controller;
use Auth;
use App\EmployeeTypes;
use App\NightShiftAllowance;

class EmployeeTypesController extends Controller
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

        $types=EmployeeTypes::with(['creator'])->get();

        $allowance = array();
        foreach ($types as $key => $type) {
            $night=NightShiftAllowance::where('emp_type',$type->id)->orderBy('execution_date','desc')->orderBy('execution_date','desc')->orderBy('id','desc')->first();
            if(isset($night->allowance)){
                $allowance[$type->id] = array(
                    'allowance' => $night->allowance,
                    'execution_date' => $night->execution_date
                );
            }else{
                NightShiftAllowance::insert([
                    'emp_type' => $type->id,
                    'allowance' => 0,
                    'execution_date' => '2019-01-01',
                    'assigned_by' => $id->suser_empid,
                ]);
                $allowance[$type->emp_type] = array(
                    'allowance' => 0,
                    'execution_date' => date('Y-m-d')
                );
            }
        }

        return view('Admin.settings.types.index',compact('id','mainlink','sublink','Adminminlink','adminsublink','types','allowance'));
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

        return view('Admin.settings.types.create',compact('id','mainlink','sublink','Adminminlink','adminsublink'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,EmployeeTypes $type)
    {
        $this->validate($request,[
            'name' => 'required',
        ]);

        $type->fill($request->all());
        $type->created_by=Auth::guard('admin')->user()->suser_empid;
        $type->save();
        if($type){
            session()->flash('success','Type Added Successfully');
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
    public function edit($t_id)
    {
        $id =   Auth::guard('admin')->user();
        $mainlink = $this->adminmainmenu();
        $sublink = $this->adminsubmenu();
        $Adminminlink = $this->adminlink();
        $adminsublink = $this->adminsublink();

        $type=EmployeeTypes::find($t_id);

        return view('Admin.settings.types.edit',compact('id','mainlink','sublink','Adminminlink','adminsublink','type'));
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
            'name' => 'required',
            'status' => 'required',
        ]);

        $type=EmployeeTypes::find($id);
        $type->fill($request->all());
        $type->created_by=Auth::guard('admin')->user()->suser_empid;
        $type->save();
        if($type){
            session()->flash('success','Type updated Successfully');
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
        //
    }

    public function updateNightShiftAllowance(Request $request,NightShiftAllowance $night)
    {
        $night->fill($request->all());
        $night->execution_date=date('Y-m-d');
        $night->assigned_by=Auth::guard('admin')->user()->suser_empid;
        $night->save();
        if($night){
            return response()->json([
                'success' => true,
                'allowance' => $night->allowance,
                'execution_date' => $night->execution_date,
                'msg' => 'Night shift allowance updated successfully.'
            ]);
        }

        return response()->json([
            'success' => false,
            'msg' => 'Something Went Wrong!'
        ]);
    }

    public function nightShiftAllowanceHistory($emp_type)
    {
        $allowance=NightShiftAllowance::with(['assigned'])->where('emp_type',$emp_type)->orderBy('execution_date','desc')->orderBy('id','desc')->get();
        return view('Admin.settings.types.history',compact('allowance'));
    }
}
