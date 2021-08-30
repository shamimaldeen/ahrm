<?php

namespace App\Http\Controllers\BackEndCon;

use Illuminate\Http\Request;
use App\Http\Controllers\BackEndCon\Controller;
use DB;
use Session;
use Auth;
class AdminSubMenuCon extends Controller
{
    public function index(){
          $id =   Auth::guard('admin')->user();
          $mainlink = $this->adminmainmenu();
          $sublink = $this->adminsubmenu();
          $Adminminlink = $this->adminlink();
          $adminsublink = $this->adminsublink();
        $mainmenu= DB::table('adminmainmenu') ->orderBy('serialNo', 'ASC')->get();
        return view('Admin.developermenu.SubMenu',compact('mainmenu','mainlink','id','sublink','Adminminlink','adminsublink'));
    }

    public function subMenuView()
    {
      $id =   Auth::guard('admin')->user();
      $mainlink = $this->adminmainmenu();
      $sublink = $this->adminsubmenu();
      $Adminminlink = $this->adminlink();
      $adminsublink = $this->adminsublink();
      $submenu = DB::table('adminsubmenu')
            ->join('adminmainmenu', 'adminmainmenu.id', '=', 'adminsubmenu.mainmenuId')
            ->select('adminsubmenu.*', 'adminmainmenu.Link_Name')
            ->orderBy('adminsubmenu.serialno', 'ASC')
            ->get();
    return view('Admin.developermenu.submenuview',compact('submenu','mainlink','id','sublink','Adminminlink','adminsublink'));

    }

    public function store(Request $request){
        $this->validate($request, [
            'mainmenuId' => 'required',
            'submenuname' => 'required|min:2',
            'serialno' => 'required',
            'routeName' => 'required',
        ]);

        $insert = DB::table('adminsubmenu')->insert([
                'mainmenuId' =>  $request->mainmenuId, 
                'submenuname' => $request->submenuname, 
                'serialno' => $request->serialno, 
                'routeName' => $request->routeName
            ]);
            if($insert){
                Session::flash('success','Save Success');
            }else{

                Session::flash('error','Something Went Wrong!!');
            }
        return redirect()->route('SubMenu');
    }

    public function submenuedit(Request $request)
    {
        if(count($request->id)=="0"){
            return 'null';
        }else if(count($request->id)>1){
            return 'max';
        }else{
            return $request->id[0];
        }
    }

    public function submenueditview($subid)
    {
        $id =   Auth::guard('admin')->user();
          $mainlink = $this->adminmainmenu();
          $sublink = $this->adminsubmenu();
          $Adminminlink = $this->adminlink();
          $adminsublink = $this->adminsublink();
          $submenu=DB::table('adminsubmenu')
            ->join('adminmainmenu', 'adminmainmenu.id', '=', 'adminsubmenu.mainmenuId')
            ->select('adminsubmenu.*', 'adminmainmenu.Link_Name')
            ->where('adminsubmenu.id',$subid)
            ->first();
        $mainmenu= DB::table('adminmainmenu') ->orderBy('serialNo', 'ASC')->get();
        return view('Admin.developermenu.submenueditview',compact('mainmenu','mainlink','id','sublink','Adminminlink','adminsublink','submenu'));
    }

    public function update(Request $request,$id){
        if($request->mainmenuId != "" && $request->serialno != "" && $request->routeName != "" && $request->submenuname != ""){
            $edit =DB::table('adminsubmenu')
                ->where('id', $id)
                ->update([
                    'mainmenuId' => $request->mainmenuId,
                    'serialno' => $request->serialno,
                    'routeName' => $request->routeName,
                    'submenuname' => $request->submenuname,
                    'status' => $request->status,
                ]);
            if($edit){
                Session::flash('success','Edit Success');
            }else{
                Session::flash('error','Something Went Wrong!!');
                return redirect('sub-menu/'.$id.'/edit');
            }
        }else{
             Session::flash('error','Please Fill up required fields');
             return redirect('sub-menu/'.$id.'/edit');
        }
        return redirect('sub-menu-view');
    }

     public function Delete(Request $request){
        if(count($request->id)=="0"){
            return 'null';
        }else{
            for ($i=0; $i < count($request->id); $i++) { 
               $obj = DB::table('adminsubmenu')->where('id',$request->id[$i])->delete();
            }
            if($obj){
                Session::flash('success','Submenu(s) Deleted Successfully');
                return '1';
            }else{
                return '0';
            }
        }
        
    }
}
