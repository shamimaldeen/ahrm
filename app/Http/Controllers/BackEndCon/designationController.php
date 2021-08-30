<?php

namespace App\Http\Controllers\BackEndCon;

use Illuminate\Http\Request;
use App\Http\Controllers\BackEndCon\Controller;
use Auth;
use Session;
use DB;

class designationController extends Controller
{

public function designation(){
    $id =   Auth::guard('admin')->user();
    $mainlink = $this->adminmainmenu();
    $sublink = $this->adminsubmenu();
    $Adminminlink = $this->adminlink();
    $adminsublink = $this->adminsublink();

    return view('Admin.settings.designation.create',compact('id','mainlink','sublink','Adminminlink','adminsublink'));
}

public function designationAdd(Request $request)
{
    $this->validate($request,[
            'desig_name'=>'required|unique:tbl_designation',
        ]);
    $insert=DB::table('tbl_designation')->insert([
            'desig_name'=>$request->desig_name,
            'desig_specification'=>$request->desig_specification,
        ]);
    if($insert){
        Session::flash('success','Designation Information Saved Successfully.');
    }else{
        Session::flash('error','Sorry!! Something Went Wrong!!');
    }
    return redirect('designation-view/create');
}

public function designationView(){
    $id =   Auth::guard('admin')->user();
    $mainlink = $this->adminmainmenu();
    $sublink = $this->adminsubmenu();
    $Adminminlink = $this->adminlink();
    $adminsublink = $this->adminsublink();
    $designation=DB::table('tbl_designation')->get();
    $allowance = 0;
    
    return view('Admin.settings.designation.view',compact('id','mainlink','sublink','Adminminlink','adminsublink','designation','allowance'));
}

public function designationEdit(Request $request)
{
    if(count($request->desig_id)>0){
        if(count($request->desig_id)>1){
            return 'max';
        }else{
            return $request->desig_id[0];
        }
    }else{
        return 'null';
    }
}

public function designationEditView($desig_id)
{
    $id =   Auth::guard('admin')->user();
    $mainlink = $this->adminmainmenu();
    $sublink = $this->adminsubmenu();
    $Adminminlink = $this->adminlink();
    $adminsublink = $this->adminsublink();
    $designation=DB::table('tbl_designation')->where('desig_id',$desig_id)->first();
    return view('Admin.settings.designation.editView',compact('id','mainlink','sublink','Adminminlink','adminsublink','designation','desig_id'));
}

public function designationUpdate(Request $request,$desig_id)
{
    $this->validate($request,[
            'desig_name'=>'required',
            'desig_status'=>'required',
        ]);
    $update=DB::table('tbl_designation')
        ->where('desig_id',$desig_id)
        ->update([
            'desig_name'=>$request->desig_name,
            'desig_specification'=>$request->desig_specification,
            'desig_status'=>$request->desig_status,
        ]);
    if($update){
        Session::flash('success','Designation Information Updated Successfully.');
        return redirect('designation-view');
    }else{
        Session::flash('error','Sorry!! Something Went Wrong!!');
        return redirect('designation-view/'.$desig_id.'/edit');
    }
    
}

public function designationDelete(Request $request)
{
    if(count($request->desig_id)>0){
        for ($i=0; $i < count($request->desig_id) ; $i++) { 
            $delete_attendance=DB::table('tbl_designation')->where('desig_id',$request->desig_id[$i])->delete();
        }
        if($delete_attendance){
            Session::flash('success','Designation(s) Deleted Successfully.');
            return '1';
        }else{
            return '0';
        }
    }else{
        return 'null';
    }
}

}