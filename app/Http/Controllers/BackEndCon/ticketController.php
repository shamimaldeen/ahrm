<?php

namespace App\Http\Controllers\BackEndCon;

use Illuminate\Http\Request;
use App\Http\Controllers\BackEndCon\Controller;
use Auth;
use Session;
use DB;

class ticketController extends Controller
{

public function addNewTicket(){
    $id =   Auth::guard('admin')->user();
    $mainlink = $this->adminmainmenu();
    $sublink = $this->adminsubmenu();
    $Adminminlink = $this->adminlink();
    $adminsublink = $this->adminsublink();

    return view('Admin.ticket.create',compact('id','mainlink','sublink','Adminminlink','adminsublink'));
}

public function addTicket(Request $request)
{
    $id =   Auth::guard('admin')->user();
    $this->validate($request,[
            'ticket_topic'=>'required',
            'ticket_desc'=>'required',
        ]);
    $insert=DB::table('tbl_ticket')->insert([
            'ticket_code'=>$this->ticket_uniquecode(),
            'ticket_topic'=>$request->ticket_topic,
            'ticket_desc'=>$request->ticket_desc,
            'ticket_submitted_by'=>$id->suser_empid,
            'ticket_submitted_at'=>date('Y-m-d H:i:s'),
            'ticket_parent_id'=>'0',
            'ticket_depart_id'=>$this->getDepartmentId($id->suser_empid),
        ]);
    if($insert){
        Session::flash('success','New Ticket Submitted Successfully.');
    }else{
        Session::flash('error','Sorry!! Something Went Wrong!!');
    }
    return redirect('add-new-ticket');
}

public function allTickets(){
    $id =   Auth::guard('admin')->user();
    $mainlink = $this->adminmainmenu();
    $sublink = $this->adminsubmenu();
    $Adminminlink = $this->adminlink();
    $adminsublink = $this->adminsublink();
    if($id->suser_level=="1" or $id->suser_level=="2"){
        $ticket=DB::table('tbl_ticket')
            ->where(['tbl_ticket.ticket_status'=>'1','tbl_ticket.ticket_parent_id'=>'0'])
            ->orderBy('tbl_ticket.ticket_submitted_at','desc')
            ->get(['tbl_ticket.*']);
    }elseif($id->suser_level=="4"){
        $ticket=DB::table('tbl_ticket')
            ->join('tbl_employee','tbl_ticket.ticket_submitted_by','=','tbl_employee.emp_id')
            ->where(['tbl_ticket.ticket_status'=>'1','tbl_ticket.ticket_parent_id'=>'0'])
            ->orWhere(['tbl_employee.emp_id'=>$id->suser_empid])
            ->orWhere(['tbl_employee.emp_seniorid'=>$id->suser_empid])
            ->orderBy('tbl_ticket.ticket_submitted_at','desc')
            ->get(['tbl_ticket.*']);
    }elseif($id->suser_level=="3" or $id->suser_level=="5" or $id->suser_level=="6") {
        $ticket=DB::table('tbl_ticket')
            ->where('tbl_ticket.ticket_submitted_by',$id->suser_empid)
            ->where(['tbl_ticket.ticket_status'=>'1','tbl_ticket.ticket_parent_id'=>'0'])
            ->orderBy('tbl_ticket.ticket_submitted_at','desc')
            ->get(['tbl_ticket.*']);
    }
    
    return view('Admin.ticket.view',compact('id','mainlink','sublink','Adminminlink','adminsublink','ticket'));
}

static public function numberOfSolution($ticket_id)
{
    return $numberOfSolution=DB::table('tbl_ticket')->where('ticket_parent_id',$ticket_id)->count('ticket_id');
}

public function ticketEdit(Request $request)
{
    $id =   Auth::guard('admin')->user();
    if(count($request->ticket_id)>0){
        if(count($request->ticket_id)>1){
            return 'max';
        }else{
            $search=DB::table('tbl_ticket')->where(['ticket_id'=>$request->ticket_id[0],'ticket_submitted_by'=>$id->suser_empid])->first();
            if(isset($search) && count($search)){
                return $request->ticket_id[0];
            }else{
                return 'denied';
            }
        }
    }else{
        return 'null';
    }
}


public function ticketEditView($ticket_id)
{
    $id =   Auth::guard('admin')->user();
    $mainlink = $this->adminmainmenu();
    $sublink = $this->adminsubmenu();
    $Adminminlink = $this->adminlink();
    $adminsublink = $this->adminsublink();
    $ticket=DB::table('tbl_ticket')->where('ticket_id',$ticket_id)->first();
    if(isset($ticket)){
        if($ticket->ticket_submitted_by!=$id->suser_empid){
            Session::flash('error','Sorry! You cannot Edit this Ticket because it has not been Submitted by you.');
            return redirect('all-tickets');
        }
    }
    return view('Admin.ticket.editView',compact('id','mainlink','sublink','Adminminlink','adminsublink','ticket','ticket_id'));
}

public function ticketUpdate(Request $request,$ticket_id)
{
    $id =   Auth::guard('admin')->user();
    $this->validate($request,[
            'ticket_topic'=>'required',
            'ticket_desc'=>'required',
        ]);
    $update=DB::table('tbl_ticket')
        ->where('ticket_id',$ticket_id)
        ->update([
            'ticket_topic'=>$request->ticket_topic,
            'ticket_desc'=>$request->ticket_desc,
            'ticket_updated_at'=>date('Y-m-d H:i:s'),
            'ticket_depart_id'=>$this->getDepartmentId($id->suser_empid),
        ]);
    if($update){
        Session::flash('success','Ticket Updated Successfully.');
    }else{
        Session::flash('error','Sorry!! Something Went Wrong!!');
    }
    return redirect('add-new-ticket/'.$ticket_id.'/edit');
    
}

public function ticketDelete(Request $request)
{
    if(count($request->ticket_id)>0){
        for ($i=0; $i < count($request->ticket_id) ; $i++) { 
            $delete=DB::table('tbl_ticket')->where('ticket_id',$request->ticket_id[$i])->delete();
        }
        if($delete){
            Session::flash('success','Ticket(s) Deleted Successfully.');
            return '1';
        }else{
            return '0';
        }
    }else{
        return 'null';
    }
}

public function ticketSolution($ticket_id)
{
    $id =   Auth::guard('admin')->user();
    $mainlink = $this->adminmainmenu();
    $sublink = $this->adminsubmenu();
    $Adminminlink = $this->adminlink();
    $adminsublink = $this->adminsublink();
    $ticket=DB::table('tbl_ticket')
        ->where(['ticket_id'=>$ticket_id,'ticket_status'=>'1','ticket_parent_id'=>'0'])
        ->first();
    $solution=DB::table('tbl_ticket')
        ->where(['ticket_parent_id'=>$ticket_id])
        ->orderBy('ticket_submitted_at','asc')
        ->get();
    return view('Admin.ticket.solution',compact('id','mainlink','sublink','Adminminlink','adminsublink','ticket','solution'));
}

public function ticketSolutionSubmit(Request $request,$ticket_id)
{
    $id =   Auth::guard('admin')->user();
    $this->validate($request,[
            'ticket_topic'=>'required',
            'ticket_desc'=>'required',
        ]);
    $insert=DB::table('tbl_ticket')->insert([
            'ticket_code'=>$this->ticket_uniquecode(),
            'ticket_topic'=>$request->ticket_topic,
            'ticket_desc'=>$request->ticket_desc,
            'ticket_submitted_by'=>$id->suser_empid,
            'ticket_submitted_at'=>date('Y-m-d H:i:s'),
            'ticket_parent_id'=>$ticket_id,
            'ticket_depart_id'=>$this->getDepartmentId($id->suser_empid),
        ]);
    if($insert){
        Session::flash('success','New Ticket Submitted Successfully.');
    }else{
        Session::flash('error','Sorry!! Something Went Wrong!!');
    }
    return redirect('add-new-ticket/'.$ticket_id.'/solution');
}

}