<?php

namespace App\Http\Controllers\BackEndCon;

use Illuminate\Http\Request;
use DB;
use Session;
use Auth;


class appmoduleController extends Controller
{
    public function index(){
      $id =   Auth::guard('admin')->user();
      $mainlink = $this->adminmainmenu();
      $sublink = $this->adminsubmenu();
      $Adminminlink = $this->adminlink();
      $adminsublink = $this->adminsublink();
      $mainmenu=DB::table('adminmainmenu')->get();
      return  view('Admin.developermenu.appmodule.appmodule',compact('mainlink','id','sublink','Adminminlink','adminsublink','mainmenu'));
    }

    public function getSubMenu($appm_menuid)
    {
      $submenu=DB::table('adminsubmenu')->where('mainmenuId',$appm_menuid)->get();
      if(isset($submenu) && count($submenu)>0){
        foreach ($submenu as $sm) {
          echo '<option value="'.$sm->id.'">'.$sm->submenuname.'</option>';
        }
      }else{
        echo '<option value="0">Without SubMenu</option>';
      }
    }

    public function appmoduleView()
    {
      $id =   Auth::guard('admin')->user();
      $mainlink = $this->adminmainmenu();
      $sublink = $this->adminsubmenu();
      $Adminminlink = $this->adminlink();
      $adminsublink = $this->adminsublink();
      $appmodule  = DB::table('tbl_appmodule')
            ->join('adminmainmenu','tbl_appmodule.appm_menuid','=','adminmainmenu.id')
            ->join('adminsubmenu','tbl_appmodule.appm_submenuid','=','adminsubmenu.id')
            ->where('tbl_appmodule.appm_submenuid','!=','0')
            ->get(['tbl_appmodule.*','adminmainmenu.Link_Name','adminsubmenu.submenuname']);
      
      $appmodule1  = DB::table('tbl_appmodule')
            ->join('adminmainmenu','tbl_appmodule.appm_menuid','=','adminmainmenu.id')
            ->where('tbl_appmodule.appm_submenuid','=','0')
            ->get(['tbl_appmodule.*','adminmainmenu.Link_Name']);
      return  view('Admin.developermenu.appmodule.appmoduleview',compact('appmodule','appmodule1','mainlink','id','sublink','Adminminlink','adminsublink'));
    }

    public function store(Request $request){
      
      $this->validate($request, [
          'appm_menuid' => 'required',
          'appm_submenuid' => 'required',
          'appm_name' => 'required',
      ]);

      if($request->appm_menuid!="0"){
        $insert = DB::table('tbl_appmodule')->insert([
              'appm_menuid' =>  $request->appm_menuid,
              'appm_submenuid' =>  $request->appm_submenuid,
              'appm_name' =>  $request->appm_name,
          ]);
        if($insert){
            Session::flash('success','Save Successfully');
        }else{
            Session::flash('error','Something Went Wrong!!');
        }
        return redirect()->route('appmodule');
      }else{
        Session::flash('error','Please Select MainMenu Name');
        return redirect()->route('appmodule');
      }
    }

    public function appmoduleedit(Request $request)
    {
      if(count($request->appm_id)=="0"){
        return 'null';
      }else if(count($request->appm_id)>1){
        return 'max';
      }else{
        return $request->appm_id[0];
      }
    }

    public function appmoduleeditview($appm_id)
    {
      $id =   Auth::guard('admin')->user();
      $mainlink = $this->adminmainmenu();
      $sublink = $this->adminsubmenu();
      $Adminminlink = $this->adminlink();
      $adminsublink = $this->adminsublink();
      $mainmenu=DB::table('adminmainmenu')->get();

      $appmodule=DB::table('tbl_appmodule')
            ->join('adminmainmenu','tbl_appmodule.appm_menuid','=','adminmainmenu.id')
            ->join('adminsubmenu','tbl_appmodule.appm_submenuid','=','adminsubmenu.id')
            ->where('tbl_appmodule.appm_id',$appm_id)
            ->first(['tbl_appmodule.*','adminmainmenu.Link_Name','adminsubmenu.submenuname']);
      if(isset($appmodule) && count($appmodule)>0){

      }else{
        $appmodule=DB::table('tbl_appmodule')
            ->join('adminmainmenu','tbl_appmodule.appm_menuid','=','adminmainmenu.id')
            ->where('tbl_appmodule.appm_id',$appm_id)
            ->first(['tbl_appmodule.*','adminmainmenu.Link_Name']);
      }
      return view('Admin.developermenu.appmodule.appmoduleedit',compact('appmodule','id','mainlink','sublink','Adminminlink','adminsublink','mainmenu'));
    }

    public function update(Request $request,$id){
      $this->validate($request, [
          'appm_menuid' => 'required',
          'appm_submenuid' => 'required',
          'appm_name' => 'required',
          'appm_status' => 'required',
      ]);

      if($request->appm_menuid!="0"){
        $update = DB::table('tbl_appmodule')->where('appm_id',$id)->update([
              'appm_menuid' =>  $request->appm_menuid,
              'appm_submenuid' =>  $request->appm_submenuid,
              'appm_name' =>  $request->appm_name,
              'appm_status' =>  $request->appm_status,
          ]);
        if($update){
            Session::flash('success','Update Successfully');
        }else{
            Session::flash('error','Something Went Wrong!!');
        }
        return redirect()->route('appmodule');
      }else{
        Session::flash('error','Please Select MainMenu Name');
        return redirect()->route('appmodule');
      }
    }

     public function Delete(Request $request){
      if(count($request->appm_id)>0){
        for ($i=0; $i < count($request->appm_id); $i++) { 
          $obj = DB::table('tbl_appmodule')->where('appm_id', '=', $request->appm_id[$i])->delete();
        }
        if($obj){
          Session::flash('success','App Module Deleted Successfully');
          return '1';
        }else{
          return '0';
        }
      }else{
        return 'null';
      }
    }
}
