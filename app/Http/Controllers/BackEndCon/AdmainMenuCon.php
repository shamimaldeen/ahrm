<?php

namespace App\Http\Controllers\BackEndCon;

use Illuminate\Http\Request;
use DB;
use Session;
use Auth;


class AdmainMenuCon extends Controller
{
    public function index(){
      $id =   Auth::guard('admin')->user();
      $mainlink = $this->adminmainmenu();
      $sublink = $this->adminsubmenu();
      $Adminminlink = $this->adminlink();
      $adminsublink = $this->adminsublink();
      return  view('Admin.developermenu.mainmenu',compact('mainlink','id','sublink','Adminminlink','adminsublink'));
    }

    public function mainMenuView()
    {
      $id =   Auth::guard('admin')->user();
      $mainlink = $this->adminmainmenu();
      $sublink = $this->adminsubmenu();
      $Adminminlink = $this->adminlink();
      $adminsublink = $this->adminsublink();
      $mainMenu  = DB::table('adminmainmenu')
            ->orderBy('serialNo', 'asc')
            ->get();
      return  view('Admin.developermenu.mainmenuview',compact('mainMenu','mainlink','id','sublink','Adminminlink','adminsublink'));
    }

    public function store(Request $request){
      $this->validate($request, [
          'MenuNameEn' => 'required|min:2',
          'serial' => 'required',
          'Route' => 'required',
          'icon' => 'required',
      ]);
      $insert = DB::table('adminmainmenu')->insert([
              'Link_Name' =>  $request->MenuNameEn, 
              'serialNo' => $request->serial, 
              'routeName' => $request->Route,
              'icon' => $request->icon
            ]);
        if($insert){
            Session::flash('success','Save Success');
        }else{
            Session::flash('error','Something Went Wrong!!');
        }
        return redirect()->route('MainMenu');
    }

    public function mainmenuedit(Request $request)
    {
      if(count($request->id)=="0"){
        return 'null';
      }else if(count($request->id)>1){
        return 'max';
      }else{
        return $request->id[0];
      }
    }

    public function mainmenueditview($mainmenuid)
    {
      $id =   Auth::guard('admin')->user();
      $mainlink = $this->adminmainmenu();
      $sublink = $this->adminsubmenu();
      $Adminminlink = $this->adminlink();
      $adminsublink = $this->adminsublink();
      $mainmenu=DB::table('adminmainmenu')->where('id', $mainmenuid)->first();
      return view('Admin.developermenu.mainmenuedit',compact('mainmenu','id','mainlink','sublink','Adminminlink','adminsublink'));
    }

    public function update(Request $request,$id){
      if($request->MenuNameEn != "" && $request->serial != "" && $request->Route != ""){
           $EditData =DB::table('adminmainmenu')
                        ->where('id', $id)
                        ->update(['Link_Name' => $request->MenuNameEn,
                            'serialNo' => $request->serial,
                            'routeName' => $request->Route,
                            'icon' => $request->icon,
                            'status' => $request->status
                        ]);
            if($EditData){
                Session::flash('success','Edit Success');
            }else{
                Session::flash('error','Something Went Wrong!!');
            }
        }else{
             Session::flash('error','Please Fill up required fields');
        }
       return redirect('main-menu-view');
    }

     public function Delete(Request $request){
      if(count($request->id)>0){
        for ($i=0; $i < count($request->id); $i++) { 
          $obj = DB::table('adminmainmenu')->where('id', '=', $request->id[$i])->delete();
        }
        if($obj){
          Session::flash('success','MainMenu Deleted Successfully');
          return '1';
        }else{
          return '0';
        }
      }else{
        return 'null';
      }
    }
}
