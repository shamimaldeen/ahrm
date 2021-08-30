<?php

namespace App\Http\Controllers\BackEndCon;

use Illuminate\Http\Request;
use App\Http\Controllers\BackEndCon\Controller;
use Auth;
use Session;
use DB;

class jobLocationController extends Controller
{

public function jobLocation(){
    $id =   Auth::guard('admin')->user();
    $mainlink = $this->adminmainmenu();
    $sublink = $this->adminsubmenu();
    $Adminminlink = $this->adminlink();
    $adminsublink = $this->adminsublink();

    return view('Admin.settings.jobLocation.create',compact('id','mainlink','sublink','Adminminlink','adminsublink'));
}

public function jobLocationAdd(Request $request)
{
    $this->validate($request,[
            'jl_name'=>'required|unique:tbl_joblocation',
        ]);
    $insert=DB::table('tbl_joblocation')->insert([
            'jl_name'=>$request->jl_name,
        ]);
    if($insert){
        Session::flash('success','Job Location Information Saved Successfully.');
    }else{
        Session::flash('error','Sorry!! Something Went Wrong!!');
    }
    return redirect('job-location-view/create');
}

public function jobLocationView(){
    $id =   Auth::guard('admin')->user();
    $mainlink = $this->adminmainmenu();
    $sublink = $this->adminsubmenu();
    $Adminminlink = $this->adminlink();
    $adminsublink = $this->adminsublink();
    $jobLocation=DB::table('tbl_joblocation')->get();
    return view('Admin.settings.jobLocation.view',compact('id','mainlink','sublink','Adminminlink','adminsublink','jobLocation'));
}

public function jobLocationEdit(Request $request)
{
    if(count($request->jl_id)>0){
        if(count($request->jl_id)>1){
            return 'max';
        }else{
            return $request->jl_id[0];
        }
    }else{
        return 'null';
    }
}

public function jobLocationEditView($jl_id)
{
    $id =   Auth::guard('admin')->user();
    $mainlink = $this->adminmainmenu();
    $sublink = $this->adminsubmenu();
    $Adminminlink = $this->adminlink();
    $adminsublink = $this->adminsublink();
    $jobLocation=DB::table('tbl_joblocation')->where('jl_id',$jl_id)->first();
    return view('Admin.settings.jobLocation.editView',compact('id','mainlink','sublink','Adminminlink','adminsublink','jobLocation','jl_id'));
}

public function jobLocationUpdate(Request $request,$jl_id)
{
    $this->validate($request,[
            'jl_name'=>'required',
            'jl_status'=>'required',
        ]);
    $update=DB::table('tbl_joblocation')
        ->where('jl_id',$jl_id)
        ->update([
            'jl_name'=>$request->jl_name,
            'jl_status'=>$request->jl_status,
        ]);
    if($update){
        Session::flash('success','Job Location Information Updated Successfully.');
        return redirect('job-location-view');
    }else{
        Session::flash('error','Sorry!! Something Went Wrong!!');
        return redirect('job-location-view/'.$jl_id.'/edit');
    }
    
}

public function jobLocationDelete(Request $request)
{
    if(count($request->jl_id)>0){
        for ($i=0; $i < count($request->jl_id) ; $i++) { 
            $delete=DB::table('tbl_joblocation')->where('jl_id',$request->jl_id[$i])->delete();
        }
        if($delete){
            Session::flash('success','Job Location(s) Deleted Successfully.');
            return '1';
        }else{
            return '0';
        }
    }else{
        return 'null';
    }
}

}