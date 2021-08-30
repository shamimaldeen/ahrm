<?php

namespace App\Http\Controllers\BackEndCon;

use Illuminate\Http\Request;
use DB;
use Session;
use Auth;


class userPriorityController extends Controller
{
    public function index(){
      $id =   Auth::guard('admin')->user();
      $mainlink = $this->adminmainmenu();
      $sublink = $this->adminsubmenu();
      $Adminminlink = $this->adminlink();
      $adminsublink = $this->adminsublink();

      $priority=DB::table('tbl_priority')->where('pr_status','1')->get();
      return  view('Admin.userPriority.userPriority',compact('mainlink','id','sublink','Adminminlink','adminsublink','priority'));
    }

    public function getAppModuleView($pr_id)
    {
      // echo "string"; exit();
      $mainmenu=DB::table('adminmainmenu')->orderBy('serialNo', 'asc')->where('status',1)->get();
      $submenu=DB::table('adminsubmenu')->orderBy('serialno', 'asc')->where('status',1)->get();
      $appmodule  = DB::table('tbl_appmodule')
            ->join('adminmainmenu','tbl_appmodule.appm_menuid','=','adminmainmenu.id')
            ->join('adminsubmenu','tbl_appmodule.appm_submenuid','=','adminsubmenu.id')
            ->where('tbl_appmodule.appm_submenuid','!=','0')
            ->get(['tbl_appmodule.*','adminmainmenu.Link_Name','adminsubmenu.submenuname']);
      
      $appmodule1  = DB::table('tbl_appmodule')
            ->join('adminmainmenu','tbl_appmodule.appm_menuid','=','adminmainmenu.id')
            ->where('tbl_appmodule.appm_submenuid','=','0')
            ->get(['tbl_appmodule.*','adminmainmenu.Link_Name']);
      $menupriorityrole    = '';
      $submenupriorityrole = '';
      $appmodulepriority   = '';
      return view('Admin.userPriority.getAppModuleView',compact('mainmenu','submenu','appmodule','appmodule1','menupriorityrole','submenupriorityrole','appmodulepriority','pr_id'));
    }

    public function store(Request $request){
      if(count($request->prrole_menuid) > 0){
          $delete=DB::table('tbl_priorityrole')->where('prrole_prid',$request->prrole_prid)->delete();
          $delete=DB::table('tbl_appmodulepriority')->where('amp_prid',$request->prrole_prid)->delete();
          
          if(count($request->prrole_submenuid) > 0){
            for($ii=0; $ii<count($request->prrole_submenuid); $ii++){
                $explode=explode('and',$request->prrole_submenuid[$ii]);
                $role = DB::table('tbl_priorityrole')
                    ->insert([
                      'prrole_prid'=>$request->prrole_prid,
                      'prrole_menuid'=>$explode[0],
                      'prrole_submenuid' => $explode[1], 
                    ]);
            }
          }

          if(count($request->apm_appmid) > 0){
            for($ii=0; $ii<count($request->apm_appmid); $ii++){
                $role = DB::table('tbl_appmodulepriority')
                    ->insert([
                      'amp_prid'=>$request->prrole_prid,
                      'amp_appmid'=>$request->apm_appmid[$ii],
                    ]);
            }
          }

          Session::flash('success','User Priority Level Updated Successfully');

      }else{
        Session::flash('error','Please Select Some MainMenu');
      }

      return redirect('user-priority-level');  
    }
}
