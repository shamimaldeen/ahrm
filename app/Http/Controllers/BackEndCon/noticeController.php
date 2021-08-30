<?php

namespace App\Http\Controllers\BackEndCon;

use Illuminate\Http\Request;
use App\Http\Controllers\BackEndCon\Controller;
use Auth;
use Session;
use DB;
use DateTime;
use Image;
use App\Notice;
use App\Department;

class noticeController  extends Controller
{

public function notice(){
    $id =   Auth::guard('admin')->user();
    $mainlink = $this->adminmainmenu();
    $sublink = $this->adminsubmenu();
    $Adminminlink = $this->adminlink();
    $adminsublink = $this->adminsublink();

    $department=DB::table('tbl_department')->get();
    return view('Admin.settings.notice.create',compact('id','mainlink','sublink','Adminminlink','adminsublink','department'));
}

public function getEmployeeForNotice($depart_id)
{
    $data='';
    $employee=DB::table('tbl_employee')->where('emp_depart_id',$depart_id)->get();
    if(isset($employee[0])){
        foreach ($employee as $emp) {
            $data.='<option value="'.$emp->emp_id.'">'.$emp->emp_name.' ('.$emp->emp_empid.')</option>';
        }
    }
    return $data;
}

public function noticeAdd(Request $request)
{
    $id =   Auth::guard('admin')->user();
    $this->validate($request,[
            'notice_title'=>'required',
            'notice_desc'=>'required',
            'department'=>'required',
            'notice_publish_from'=>'required',
            'notice_publish_to'=>'required',
        ]);
    $insert=DB::table('tbl_notice')->insert([
            'notice_title'=>$request->notice_title,
            'notice_desc'=>$request->notice_desc,
            'notice_department'=>$request->department,
            'notice_employee'=>json_encode($request->employee),
            'notice_added_by'=>$id->suser_empid,
            'notice_publish_from'=>$request->notice_publish_from,
            'notice_publish_to'=>$request->notice_publish_to,
            'notice_created_at'=>date('Y-m-d h:i:s'),
        ]);
    if($insert){
        Session::flash('success','Notice Published Successfully.');
    }else{
        Session::flash('error','Sorry!! Something Went Wrong!!');
    }
    return redirect('notice-board-view/create');
}

public function noticeEMail(Request $request)
{
    $id =   Auth::guard('admin')->user();
    $this->validate($request,[
            'department'=>'required',
            'subject'=>'required',
            'message'=>'required',
        ]);
    $success=0;
    $error=0;
    $data='';
    if(count($request->employee)>0){
        foreach ($request->employee as $emp_id) {
            $email=DB::table('tbl_employee')->where('emp_id',$emp_id)->first(['emp_email']);
            if(isset($email->emp_email)){
                if($this->sendMail($email->emp_email,$request->subject,$request->message)){
                    $success++;
                }else{
                    $error++;
                }   
            }else{
                $error++;
            }
        }
    }else{
        $employee=DB::table('tbl_employee')->where('emp_depart_id',$request->department)->get(['emp_email']);
        if(isset($employee[0])){
            foreach ($employee as $emp) {
                if(isset($emp->emp_email)){
                    if($this->sendMail($emp->emp_email,$request->subject,$request->message)){
                        $success++;
                    }else{
                        $error++;
                    }   
                }else{
                    $error++;
                }
            }
        }else{
            Session::flash('error','Sorry!! The Department you choose has no employee!');
            return redirect('notice-board-view/create');
        }
    }

    if($success>0){
        Session::flash('success','Mail has been sent to '.$success.'  employee(s)');
    }
    if($error>0){
        Session::flash('error','Mail could not be sent to '.$error.' employee due to error!');
    }
    return redirect('notice-board-view/create');
}

public function noticeSMS(Request $request)
{
    $id =   Auth::guard('admin')->user();
    $this->validate($request,[
            'department'=>'required',
            'subject'=>'required',
            'message'=>'required',
        ]);
    $success=0;
    $error=0;
    $data='';
    if(count($request->employee)>0){
        foreach ($request->employee as $emp_id) {
            $email=DB::table('tbl_employee')->where('emp_id',$emp_id)->first(['emp_email']);
            if(isset($email->emp_email)){
                if($this->sendMail($email->emp_email,$request->subject,$request->message)){
                    $success++;
                }else{
                    $error++;
                }   
            }else{
                $error++;
            }
        }
    }else{
        $employee=DB::table('tbl_employee')->where('emp_depart_id',$request->department)->get(['emp_email']);
        if(isset($employee[0])){
            foreach ($employee as $emp) {
                if(isset($emp->emp_email)){
                    if($this->sendMail($emp->emp_email,$request->subject,$request->message)){
                        $success++;
                    }else{
                        $error++;
                    }   
                }else{
                    $error++;
                }
            }
        }else{
            Session::flash('error','Sorry!! The Department you choose has no employee!');
            return redirect('notice-board-view/create');
        }
    }

    if($success>0){
        Session::flash('success','Mail has been sent to '.$success.'  employee(s)');
    }
    if($error>0){
        Session::flash('error','Mail could not be sent to '.$error.' employee due to error!');
    }
    return redirect('notice-board-view/create');
}

public function noticeView(){
    $id =   Auth::guard('admin')->user();
    $mainlink = $this->adminmainmenu();
    $sublink = $this->adminsubmenu();
    $Adminminlink = $this->adminlink();
    $adminsublink = $this->adminsublink();
    $notice = Notice::with(['addedby','department'])->get();
    return view('Admin.settings.notice.view',compact('id','mainlink','sublink','Adminminlink','adminsublink','notice'));
}

static public function getEmployeeList($notice_id)
{
    $data='';
    $notice=Notice::with(['department'])->find($notice_id);
    if(count(json_decode($notice->notice_employee, true))){
        $employee = json_decode($notice->notice_employee, true);
        foreach ($employee as $emp) {
            $search=DB::table('tbl_employee')->where('emp_id',$emp)->first(['emp_name','emp_empid']);
            if(isset($search->emp_name)){
                $data.=$search->emp_name.' ('.$search->emp_empid.') ,';
            }
        }
    }else{
        $data.='Selected for all Employee of Department : '.$notice->department->depart_name;
    }
    return $data;
}

public function noticeEdit(Request $request)
{
    if(count($request->notice_id)>0){
        if(count($request->notice_id)>1){
            return 'max';
        }else{
            return $request->notice_id[0];
        }
    }else{
        return 'null';
    }
}

public function noticeEditView($notice_id)
{
    $id =   Auth::guard('admin')->user();
    $mainlink = $this->adminmainmenu();
    $sublink = $this->adminsubmenu();
    $Adminminlink = $this->adminlink();
    $adminsublink = $this->adminsublink();
    $notice=DB::table('tbl_notice')->where('notice_id',$notice_id)->first();
    return view('Admin.settings.notice.editView',compact('id','mainlink','sublink','Adminminlink','adminsublink','notice','notice_id'));
}

public function noticeUpdate(Request $request,$notice_id)
{
    $this->validate($request,[
            'notice_title'=>'required',
            'notice_desc'=>'required',
            'notice_publish_from'=>'required',
            'notice_publish_to'=>'required',
            'notice_status'=>'required',
        ]);
    $update=DB::table('tbl_notice')
        ->where('notice_id',$notice_id)
        ->update([
            'notice_title'=>$request->notice_title,
            'notice_desc'=>$request->notice_desc,
            'notice_publish_from'=>$request->notice_publish_from,
            'notice_publish_to'=>$request->notice_publish_to,
            'notice_status'=>$request->notice_status,
            'notice_updated_at'=>date('Y-m-d h:i:s'),
        ]);
    if($update){
        Session::flash('success','Notice Updated Successfully.');
        return redirect('notice-board-view');
    }else{
        Session::flash('error','Sorry!! Something Went Wrong!!');
        return redirect('notice-board-view/'.$notice_id.'/edit');
    }
    
}

public function noticeDelete(Request $request)
{
    if(count($request->notice_id)>0){
        for ($i=0; $i < count($request->notice_id) ; $i++) { 
            $delete=DB::table('tbl_notice')->where('notice_id',$request->notice_id[$i])->delete();
        }
        if($delete){
            Session::flash('success','Notice(s) Deleted Successfully.');
            return '1';
        }else{
            return '0';
        }
    }else{
        return 'null';
    }
}

}