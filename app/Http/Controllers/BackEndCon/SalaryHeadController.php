<?php

namespace App\Http\Controllers\BackEndCon;

use Illuminate\Http\Request;
use App\SalaryHead;
use Auth;

class SalaryHeadController extends Controller
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
        $head=SalaryHead::get();

        return view('Admin.settings.salaryHead.view',compact('id','mainlink','sublink','Adminminlink','adminsublink','head'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // echo "string"; exit();
        $id =   Auth::guard('admin')->user();
        $mainlink = $this->adminmainmenu();
        $sublink = $this->adminsubmenu();
        $Adminminlink = $this->adminlink();
        $adminsublink = $this->adminsublink();

        return view('Admin.settings.salaryHead.create',compact('id','mainlink','sublink','Adminminlink','adminsublink'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,SalaryHead $head)
    {
        $this->validate($request,[
            'head_name'=>'required',
            'head_type'=>'required',
            'other_head_type'=>'required',
            'head_percentage'=>'required',
            'head_taxable'=>'required',
        ]);
        if($request->head_taxexempt==""){
            $request->head_taxexempt=0;
        }
        $head->fill($request->all());
        $head->head_updated_at=date('Y-m-d H:i:s');
        $head->save();
        if($head){
            session()->flash('success','Salary Head Added Successfully');
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
        $head=SalaryHead::find($head_id);

        // return $head; exit();
        if(!isset($head->head_id)){
            session()->flash('error','Data Not Found!');
            return redirect()->back();
        }
        return view('Admin.settings.salaryHead.editView',compact('id','mainlink','sublink','Adminminlink','adminsublink','head'));
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
            'head_name'=>'required',
            'head_type'=>'required',
            'other_head_type'=>'required',
            'head_percentage'=>'required',
            'head_taxable'=>'required',
        ]);
        if($request->head_taxexempt==""){
            $request->head_taxexempt=0;
        }

        $head=SalaryHead::find($id);
        $head->fill($request->all());
        $head->head_updated_at=date('Y-m-d H:i:s');
        $head->save();
        if($head){
            session()->flash('success','Salary Head Updated Successfully');
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
        $head=SalaryHead::find($id)->delete();
        if($head){
            return response()->json(["success"=>true]);
        }else{
            return response()->json(["success"=>false,"msg"=>"Something Went Wrong!"]);
        }
    }
}
