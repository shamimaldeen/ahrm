<?php

namespace App\Http\Controllers\BackEndCon;

use Illuminate\Http\Request;
use App\Http\Controllers\BackEndCon\Controller;
use Auth;
use App\Style;

class StyleController extends Controller
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
        $styles=Style::with(['employee'])->get();

        return view('Admin.contractual.style.index',compact('id','mainlink','sublink','Adminminlink','adminsublink','styles'));
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

        return view('Admin.contractual.style.create',compact('id','mainlink','sublink','Adminminlink','adminsublink'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,Style $style)
    {
        $this->validate($request,[
            'sty_desc'=>'required'
        ]);
        $style->fill($request->all());
        $style->sty_code=$this->uniquecode('10','OR','sty_code','tbl_style');
        $style->sty_empid=Auth::guard('admin')->user()->suser_empid;
        $style->save();
        if($style){
            session()->flash('success','Style Stored Successfully.');
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
    public function edit($sty_id)
    {
        $id =   Auth::guard('admin')->user();
        $mainlink = $this->adminmainmenu();
        $sublink = $this->adminsubmenu();
        $Adminminlink = $this->adminlink();
        $adminsublink = $this->adminsublink();
        $style=Style::find($sty_id);
        return view('Admin.contractual.style.edit',compact('id','mainlink','sublink','Adminminlink','adminsublink','style'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $sty_id)
    {
        $this->validate($request,[
            'sty_desc'=>'required'
        ]);
        $style=Style::find($sty_id);
        $style->fill($request->all());
        $style->sty_empid=Auth::guard('admin')->user()->suser_empid;
        $style->save();
        if($style){
            session()->flash('success','Style Updated Successfully.');
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
        $style=Style::find($id)->delete();
        if($style){
            return response()->json(["success"=>true]);
        }else{
            return response()->json(["success"=>false,"msg"=>"Something Went Wrong!"]);
        }
    }
}
