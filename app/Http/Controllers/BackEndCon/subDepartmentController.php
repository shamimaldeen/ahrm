<?php

namespace App\Http\Controllers\BackEndCon;

use Illuminate\Http\Request;
use App\Http\Controllers\BackEndCon\Controller;
use Auth;
use Session;
use DB;

class subDepartmentController extends Controller
{

public function subDepartment(){
    $id =   Auth::guard('admin')->user();
    $mainlink = $this->adminmainmenu();
    $sublink = $this->adminsubmenu();
    $Adminminlink = $this->adminlink();
    $adminsublink = $this->adminsublink();

    $department=DB::table('tbl_department')->where('depart_status','1')->get();
    return view('Admin.settings.subDepartment.create',compact('id','mainlink','sublink','Adminminlink','adminsublink','department'));
}

public function subDepartmentAdd(Request $request)
{
    $this->validate($request,[
            'sdepart_departid'=>'required',
            'sdepart_name'=>'required',
        ]);
    $insert=DB::table('tbl_subdepartment')->insert([
            'sdepart_departid'=>$request->sdepart_departid,
            'sdepart_name'=>$request->sdepart_name,
        ]);
    if($insert){
        Session::flash('success','Sub Department Information Saved Successfully.');
    }else{
        Session::flash('error','Sorry!! Something Went Wrong!!');
    }
    return redirect('sub-department-view/create');
}

public function subDepartmentView(){
    $id =   Auth::guard('admin')->user();
    $mainlink = $this->adminmainmenu();
    $sublink = $this->adminsubmenu();
    $Adminminlink = $this->adminlink();
    $adminsublink = $this->adminsublink();
    $subDepartment=DB::table('tbl_subdepartment')
        ->join('tbl_department','tbl_subdepartment.sdepart_departid','=','tbl_department.depart_id')
        ->get(['tbl_subdepartment.*','tbl_department.depart_name']);
    return view('Admin.settings.subDepartment.view',compact('id','mainlink','sublink','Adminminlink','adminsublink','subDepartment'));
}

public function subDepartmentEdit(Request $request)
{
    if(count($request->sdepart_id)>0){
        if(count($request->sdepart_id)>1){
            return 'max';
        }else{
            return $request->sdepart_id[0];
        }
    }else{
        return 'null';
    }
}

public function subDepartmentEditView($sdepart_id)
{
    $id =   Auth::guard('admin')->user();
    $mainlink = $this->adminmainmenu();
    $sublink = $this->adminsubmenu();
    $Adminminlink = $this->adminlink();
    $adminsublink = $this->adminsublink();
    $department=DB::table('tbl_department')->where('depart_status','1')->get();
    $subDepartment=DB::table('tbl_subdepartment')
        ->join('tbl_department','tbl_subdepartment.sdepart_departid','=','tbl_department.depart_id')
        ->where('tbl_subdepartment.sdepart_id',$sdepart_id)
        ->first(['tbl_subdepartment.*','tbl_department.depart_name']);
    return view('Admin.settings.subDepartment.editView',compact('id','mainlink','sublink','Adminminlink','adminsublink','subDepartment','sdepart_id','department'));
}

public function subDepartmentUpdate(Request $request,$sdepart_id)
{
    $this->validate($request,[
            'sdepart_departid'=>'required',
            'sdepart_name'=>'required',
            'sdepart_status'=>'required',
        ]);
    $update=DB::table('tbl_subdepartment')
        ->where('sdepart_id',$sdepart_id)
        ->update([
            'sdepart_departid'=>$request->sdepart_departid,
            'sdepart_name'=>$request->sdepart_name,
            'sdepart_status'=>$request->sdepart_status,
        ]);
    if($update){
        Session::flash('success','Sub Department Information Updated Successfully.');
        return redirect('sub-department-view');
    }else{
        Session::flash('error','Sorry!! Something Went Wrong!!');
        return redirect('sub-department/'.$sdepart_id.'/edit');
    }
    
}

public function subDepartmentDelete(Request $request)
{
    if(count($request->sdepart_id)>0){
        for ($i=0; $i < count($request->sdepart_id) ; $i++) { 
            $delete_attendance=DB::table('tbl_subdepartment')->where('sdepart_id',$request->sdepart_id[$i])->delete();
        }
        if($delete_attendance){
            Session::flash('success','Sub-Department(s) Deleted Successfully.');
            return '1';
        }else{
            return '0';
        }
    }else{
        return 'null';
    }
}


}