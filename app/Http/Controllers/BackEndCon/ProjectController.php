<?php

namespace App\Http\Controllers\BackEndCon;

use Illuminate\Http\Request;
use App\Http\Controllers\BackEndCon\Controller;
use App\Project;
use App\Goal;
use Auth;

class ProjectController extends Controller
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
        if(isset($Goal[0])){
            $Project=Project::where('goal_id',$Goal[0]->id)->get();
        }else{
            $Project=false;
        }

        return view('Admin.performance.projects.view',compact('id','mainlink','sublink','Adminminlink','adminsublink','Project','Goal'));
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
        $Goal=Goal::get();
        return view('Admin.performance.projects.create',compact('id','mainlink','sublink','Adminminlink','adminsublink','Goal'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,Project $Project)
    {
        $this->validate($request,[
            'goal_id'=>'required',
            'project_name'=>'required',
            'project_amount'=>'required',
            'incentive_amount'=>'required',
            'start_date'=>'required',
            'end_date'=>'required',
            'status'=>'required'
        ]);
        $Project->fill($request->all())->save();
        if($Project){
            session()->flash('success','Project Stored Successfully');
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
    public function show($goal_id)
    {
        $id =   Auth::guard('admin')->user();
        $mainlink = $this->adminmainmenu();
        $sublink = $this->adminsubmenu();
        $Adminminlink = $this->adminlink();
        $adminsublink = $this->adminsublink();
        $Goal=Goal::get();
        $Project=Project::where('goal_id',$goal_id)->get();

        return view('Admin.performance.projects.view',compact('id','mainlink','sublink','Adminminlink','adminsublink','Project','Goal','goal_id'));
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
        $Project=Project::find($sk_id);
        $Goal=Goal::get();
        return view('Admin.performance.projects.edit',compact('id','mainlink','sublink','Adminminlink','adminsublink','Project','Goal'));
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
            'goal_id'=>'required',
            'project_name'=>'required',
            'project_amount'=>'required',
            'incentive_amount'=>'required',
            'start_date'=>'required',
            'end_date'=>'required',
            'status'=>'required'
        ]);
        $Project=Project::find($id);
        $Project->fill($request->all())->save();
        if($Project){
            session()->flash('success','Project Updated Successfully');
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
        $Project=Project::find($id)->delete();
        if($Project){
            return response()->json(["success"=>true]);
        }else{
            return response()->json(["success"=>false,'msg'=>'Something Went Wrong!']);
        }
    }
}
