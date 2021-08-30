<?php

namespace App\Http\Controllers\BackEndCon;

use Illuminate\Http\Request;
use App\Http\Controllers\BackEndCon\Controller;
use Auth;
use Session;
use DB;

class departmentController extends Controller
{

public function department(){
    
    $id =   Auth::guard('admin')->user();
    $mainlink = $this->adminmainmenu();
    $sublink = $this->adminsubmenu();
    $Adminminlink = $this->adminlink();
    $adminsublink = $this->adminsublink();

    return view('Admin.settings.department.create',compact('id','mainlink','sublink','Adminminlink','adminsublink'));
}

public function departmentAdd(Request $request)
{
    $this->validate($request,[
            'depart_name'=>'required|unique:tbl_department',
        ]);
    $insert=DB::table('tbl_department')->insert([
            'depart_name'=>$request->depart_name,
        ]);
    if($insert){
        Session::flash('success','Department Information Saved Successfully.');
    }else{
        Session::flash('error','Sorry!! Something Went Wrong!!');
    }
    return redirect('department-view/create');
}

public function departmentView(){
    $id =   Auth::guard('admin')->user();
    $mainlink = $this->adminmainmenu();
    $sublink = $this->adminsubmenu();
    $Adminminlink = $this->adminlink();
    $adminsublink = $this->adminsublink();
    $department=DB::table('tbl_department')->get();
    return view('Admin.settings.department.view',compact('id','mainlink','sublink','Adminminlink','adminsublink','department'));
}

public function departmentEdit(Request $request)
{
    if(count($request->depart_id)>0){
        if(count($request->depart_id)>1){
            return 'max';
        }else{
            return $request->depart_id[0];
        }
    }else{
        return 'null';
    }
}


public function departmentEditView($depart_id)
{
    $id =   Auth::guard('admin')->user();
    $mainlink = $this->adminmainmenu();
    $sublink = $this->adminsubmenu();
    $Adminminlink = $this->adminlink();
    $adminsublink = $this->adminsublink();
    $department=DB::table('tbl_department')->where('depart_id',$depart_id)->first();
    return view('Admin.settings.department.editView',compact('id','mainlink','sublink','Adminminlink','adminsublink','department','depart_id'));
}

public function departmentUpdate(Request $request,$depart_id)
{
    $this->validate($request,[
            'depart_name'=>'required',
            'depart_status'=>'required',
        ]);
    $update=DB::table('tbl_department')
        ->where('depart_id',$depart_id)
        ->update([
            'depart_name'=>$request->depart_name,
            'depart_status'=>$request->depart_status,
        ]);
    if($update){
        Session::flash('success','Department Information Updated Successfully.');
        return redirect('department-view');
    }else{
        Session::flash('error','Sorry!! Something Went Wrong!!');
        return redirect('department-view/'.$depart_id.'/edit');
    }
    
}

public function departmentDelete(Request $request)
{
    if(count($request->depart_id)>0){
        for ($i=0; $i < count($request->depart_id) ; $i++) { 
            $delete_attendance=DB::table('tbl_department')->where('depart_id',$request->depart_id[$i])->delete();
        }
        if($delete_attendance){
            Session::flash('success','Department(s) Deleted Successfully.');
            return '1';
        }else{
            return '0';
        }
    }else{
        return 'null';
    }
}

public function departmentDeleteByID($depart_id)
{
    $delete=DB::table('tbl_department')->where('depart_id',$depart_id)->delete();
   if($delete){
        Session::flash('success','Department Deleted Successfully.');
    }else{
        Session::flash('error','Whoops! Something Went Wrong!.');
    }
    return redirect('department-view');
}

public function assignHOD($depart_id)
{
    $id =   Auth::guard('admin')->user();
    $mainlink = $this->adminmainmenu();
    $sublink = $this->adminsubmenu();
    $Adminminlink = $this->adminlink();
    $adminsublink = $this->adminsublink();
    $employe=DB::table('tbl_employee')->where('emp_depart_id',$depart_id)->get();
    $HOD=DB::table('tbl_hod')->join('tbl_employee','tbl_employee.emp_id','=','tbl_hod.hod_empid')->get(['tbl_hod.*','tbl_employee.emp_name','tbl_employee.emp_empid']);
    $data=DB::table('tbl_hod')->where('hod_depart_id',$depart_id)->first();
    $department=DB::table('tbl_department')->where('depart_id',$depart_id)->first();
    return view('Admin.settings.department.HOD.index',compact('id','mainlink','sublink','Adminminlink','adminsublink','department','employe','HOD','depart_id','data'));
}

public function assignHODSubmit(Request $request,$depart_id)
{
    $search=DB::table('tbl_hod')->where('hod_depart_id',$depart_id)->first();
    if(isset($search)){
        $process=DB::table('tbl_hod')->where('hod_depart_id',$depart_id)->update([
            'hod_empid'=>$request->hod_empid,
            'hod_superior'=>$request->hod_superior,
            'hod_note'=>$request->hod_note,
        ]);
    }else{
        $process=DB::table('tbl_hod')->insert([
            'hod_depart_id'=>$depart_id,
            'hod_empid'=>$request->hod_empid,
            'hod_superior'=>$request->hod_superior,
            'hod_note'=>$request->hod_note,
        ]);
    }

    if(isset($process)){
        Session::flash('success','HOD Assigned Successfully.');
        return redirect('department-view');
    }else{
        Session::flash('error','Something Went Wrong!');
        return redirect()->back();
    }
}

static public function getHOD($depart_id)
{
    $Controller=new Controller();
     $HOD=DB::table('tbl_hod')->where('hod_depart_id',$depart_id)->first();
     if(isset($HOD)){
        return '<td>'.$Controller->getSeniorName($HOD->hod_empid).'</td><td>'.$Controller->getSeniorName($HOD->hod_superior).'</td><td>'.$HOD->hod_note.'</td>';
     }else{
        return '<td></td><td></td><td></td>';
     }
}


}