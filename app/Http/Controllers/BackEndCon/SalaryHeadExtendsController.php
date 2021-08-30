<?php

namespace App\Http\Controllers\BackEndCon;

use Illuminate\Http\Request;
use App\SalaryHeadExtends;
use Auth;

class SalaryHeadExtendsController extends Controller
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
        $head=SalaryHeadExtends::get();

        return view('Admin.settings.salaryHeadExtends.view',compact('id','mainlink','sublink','Adminminlink','adminsublink','head'));
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

        return view('Admin.settings.salaryHeadExtends.create',compact('id','mainlink','sublink','Adminminlink','adminsublink'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,SalaryHeadExtends $head)
    {
        $id =   Auth::guard('admin')->user();
        $this->validate($request,[
            'head_name'=>'required',
            'head_unit_for_absent'=>'required',
            'head_percentage_for_basic'=>'required',
            'head_percentage_for_total'=>'required',
            'head_percentage_status'=>'required',
            'head_type'=>'required',
        ]);

        $head->fill($request->all());
        $head->head_last_updated_at=date('Y-m-d H:i:s');
        $head->head_last_updated_by=$id->suser_empid;
        $head->save();
        if($head){
            session()->flash('success','Extends Salary Head Added Successfully');
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
    public function edit($head_id)
    {
        $id =   Auth::guard('admin')->user();
        $mainlink = $this->adminmainmenu();
        $sublink = $this->adminsubmenu();
        $Adminminlink = $this->adminlink();
        $adminsublink = $this->adminsublink();
        $head=SalaryHeadExtends::find($head_id);
        if(!isset($head->head_id)){
            session()->flash('error','Data Not Found!');
            return redirect()->back();
        }
        return view('Admin.settings.salaryHeadExtends.editView',compact('id','mainlink','sublink','Adminminlink','adminsublink','head'));
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
        $admin =   Auth::guard('admin')->user();
        $this->validate($request,[
            'head_name'=>'required',
            'head_unit_for_absent'=>'required',
            'head_percentage_for_basic'=>'required',
            'head_percentage_for_total'=>'required',
            'head_percentage_status'=>'required',
            'head_type'=>'required',
        ]);

        $head=SalaryHeadExtends::find($id);
        $head->fill($request->all());
        $head->head_last_updated_at=date('Y-m-d H:i:s');
        $head->head_last_updated_by=$admin->suser_empid;
        $head->save();
        if($head){
            session()->flash('success','Extends Salary Head Updated Successfully');
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
        $head=SalaryHeadExtends::find($id)->delete();
        if($head){
            return response()->json(["success"=>true]);
        }else{
            return response()->json(["success"=>false,"msg"=>"Something Went Wrong!"]);
        }
    }
}
