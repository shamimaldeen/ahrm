<?php

namespace App\Http\Controllers\BackEndCon;

use Illuminate\Http\Request;
use DB;
use Session;
use Auth;


class priorityController extends Controller
{
    public function index(){
      $id =   Auth::guard('admin')->user();
      $mainlink = $this->adminmainmenu();
      $sublink = $this->adminsubmenu();
      $Adminminlink = $this->adminlink();
      $adminsublink = $this->adminsublink();
      return  view('Admin.developermenu.priority.priority',compact('mainlink','id','sublink','Adminminlink','adminsublink'));
    }

    public function priorityView()
    {
      $id =   Auth::guard('admin')->user();
      $mainlink = $this->adminmainmenu();
      $sublink = $this->adminsubmenu();
      $Adminminlink = $this->adminlink();
      $adminsublink = $this->adminsublink();
      $priority  = DB::table('tbl_priority')
            ->get();
      return  view('Admin.developermenu.priority.priorityview',compact('priority','mainlink','id','sublink','Adminminlink','adminsublink'));
    }

    public function store(Request $request){
      
      $this->validate($request, [
          'pr_name' => 'required|unique:tbl_priority',
      ]);

      $insert = DB::table('tbl_priority')->insert([
              'pr_name' =>  $request->pr_name,
          ]);
        if($insert){
            Session::flash('success','Save Successfully');
        }else{
            Session::flash('error','Something Went Wrong!!');
        }
        return redirect()->route('priority');
    }

    public function priorityedit(Request $request)
    {
      if(count($request->pr_id)=="0"){
        return 'null';
      }else if(count($request->pr_id)>1){
        return 'max';
      }else{
        return $request->pr_id[0];
      }
    }

    public function priorityeditview($pr_id)
    {
      $id =   Auth::guard('admin')->user();
      $mainlink = $this->adminmainmenu();
      $sublink = $this->adminsubmenu();
      $Adminminlink = $this->adminlink();
      $adminsublink = $this->adminsublink();
      $priority=DB::table('tbl_priority')->where('pr_id', $pr_id)->first();
      return view('Admin.developermenu.priority.priorityedit',compact('priority','id','mainlink','sublink','Adminminlink','adminsublink'));
    }

    public function update(Request $request,$id){
      if($request->pr_name != ""){
           $EditData =DB::table('tbl_priority')
                        ->where('pr_id', $id)
                        ->update([
                          'pr_name' => $request->pr_name,
                          'pr_status' => $request->pr_status,
                        ]);
            if($EditData){
                Session::flash('success','Edit Successfully');
            }else{
                Session::flash('error','Something Went Wrong!!');
            }
        }else{
             Session::flash('error','Please Fill up required fields');
        }
       return redirect('priority-view');
    }

     public function Delete(Request $request){
      if(count($request->pr_id)>0){
        for ($i=0; $i < count($request->pr_id); $i++) { 
          $obj = DB::table('tbl_priority')->where('pr_id', '=', $request->pr_id[$i])->delete();
        }
        if($obj){
          Session::flash('success','Priority Deleted Successfully');
          return '1';
        }else{
          return '0';
        }
      }else{
        return 'null';
      }
    }
}
