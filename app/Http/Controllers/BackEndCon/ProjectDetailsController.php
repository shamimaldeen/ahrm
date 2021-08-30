<?php

namespace App\Http\Controllers\BackEndCon;

use Illuminate\Http\Request;
use Auth;
use App\ProjectDetails;

class ProjectDetailsController extends Controller
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
        $projectDetails=ProjectDetails::find('1');
        // return $id; exit();
        return view('Admin.developermenu.projectDetails.index',compact('id','mainlink','sublink','Adminminlink','adminsublink','projectDetails'));
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
        $this->validate($request,[
            'project_name'=>'required',
            'project_address'=>'required',
            'project_contact'=>'required',
        ]);

        $ProjectDetails=ProjectDetails::find('1');
        $ProjectDetails->fill($request->all())->save();

        $logo = $request->file('project_logo');
        $icon = $request->file('project_icon');
        $path = base_path().'/public/projectDetails';

        if($logo!=""){
            $logoname = $ProjectDetails->project_id.'-logo-'.date('YmdHis').'.'.$logo->getClientOriginalExtension();
            $logoupload = $logo->move($path,$logoname);
            if($logoupload){
                if($ProjectDetails->project_logo!="" && file_exists(base_path().'/public/projectDetails/'.$ProjectDetails->project_logo)){
                    unlink(base_path().'/public/projectDetails/'.$ProjectDetails->project_logo);
                }
                ProjectDetails::where('project_id','1')->update([
                    'project_logo'=>$logoname
                ]);
            }
        }

        if($icon!=""){
            $iconname = $ProjectDetails->project_id.'-icon-'.date('YmdHis').'.'.$icon->getClientOriginalExtension();
            $iconupload = $icon->move($path,$iconname);
            if($iconupload){
                if($ProjectDetails->project_logo!="" && file_exists(base_path().'/public/projectDetails/'.$ProjectDetails->project_icon)){
                    unlink(base_path().'/public/projectDetails/'.$ProjectDetails->project_icon);
                }
                ProjectDetails::where('project_id','1')->update([
                    'project_icon'=>$iconname
                ]);
            }
        }

        if($ProjectDetails){
            session()->flash('success','Project Details Updated Successfully.');
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
