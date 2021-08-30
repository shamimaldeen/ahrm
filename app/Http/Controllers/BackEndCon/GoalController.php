<?php

namespace App\Http\Controllers\BackEndCon;

use Illuminate\Http\Request;
use App\Http\Controllers\BackEndCon\Controller;
use App\Goal;
use Auth;

class GoalController extends Controller
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
        $Goal=Goal::get();

        return view('Admin.performance.goals.view',compact('id','mainlink','sublink','Adminminlink','adminsublink','Goal'));
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

        return view('Admin.performance.goals.create',compact('id','mainlink','sublink','Adminminlink','adminsublink'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,Goal $Goal)
    {
        $this->validate($request,[
            'title'=>'required',
            'year'=>'required'
        ]);
        $Goal->fill($request->all())->save();
        if($Goal){
            session()->flash('success','Goal Stored Successfully');
        }else{
            session()->flash('error','Something Went Wrong!!');
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
    public function edit($sk_id)
    {
        $id =   Auth::guard('admin')->user();
        $mainlink = $this->adminmainmenu();
        $sublink = $this->adminsubmenu();
        $Adminminlink = $this->adminlink();
        $adminsublink = $this->adminsublink();
        $Goal=Goal::find($sk_id);
        return view('Admin.performance.goals.edit',compact('id','mainlink','sublink','Adminminlink','adminsublink','Goal'));
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
            'title'=>'required',
            'year'=>'required',
            'status'=>'required'
        ]);
        $Goal=Goal::find($id);
        $Goal->fill($request->all());
        $Goal->updated_at=date('Y-m-d H:i:s');
        $Goal->save();
        if($Goal){
            session()->flash('success','Goal Updated Successfully');
        }else{
            session()->flash('error','Something Went Wrong!!');
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
        $Goal=Goal::find($id)->delete();
        if($Goal){
            return response()->json(["success"=>true]);
        }else{
            return response()->json(["success"=>false,'msg'=>'Something Went Wrong!']);
        }
    }
}
