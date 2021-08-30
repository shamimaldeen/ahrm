<?php

namespace App\Http\Controllers\BackEndCon;

use Illuminate\Http\Request;
use App\Http\Controllers\BackEndCon\Controller;
use Auth;
use Session;
use DB;
use DateTime;
use Image;
use App\Device;
use App\RemoteDevice;

class DevInfoController extends Controller
{

public function DevInfo(){
    $id =   Auth::guard('admin')->user();
    $mainlink = $this->adminmainmenu();
    $sublink = $this->adminsubmenu();
    $Adminminlink = $this->adminlink();
    $adminsublink = $this->adminsublink();

    return view('Admin.settings.device.create',compact('id','mainlink','sublink','Adminminlink','adminsublink'));
}

public function DevInfoAdd(Request $request,Device $device)
{
    $this->validate($request,[
            'name'=>'required|unique:tbl_device',
            'type'=>'required',
        ]);
    $device->fill($request->all())->save();
    if($device){
        Session::flash('success','Device Information Saved Successfully.');
    }else{
        Session::flash('error','Sorry!! Something Went Wrong!!');
    }
    return redirect()->back();
}

public function DevInfoView(){
    $id =   Auth::guard('admin')->user();
    $mainlink = $this->adminmainmenu();
    $sublink = $this->adminsubmenu();
    $Adminminlink = $this->adminlink();
    $adminsublink = $this->adminsublink();
    $devices=Device::get();
    $remotedevices=RemoteDevice::get();
    return view('Admin.settings.device.index',compact('id','mainlink','sublink','Adminminlink','adminsublink','devices','remotedevices'));
}

public function DevInfoEdit(Request $request)
{
    if(count($request->id)>0){
        if(count($request->id)>1){
            return 'max';
        }else{
            return $request->id[0];
        }
    }else{
        return 'null';
    }
}

public function DevInfoEditView($dev_id)
{
    $id =   Auth::guard('admin')->user();
    $mainlink = $this->adminmainmenu();
    $sublink = $this->adminsubmenu();
    $Adminminlink = $this->adminlink();
    $adminsublink = $this->adminsublink();
    $device=Device::find($dev_id);
    return view('Admin.settings.device.edit',compact('id','mainlink','sublink','Adminminlink','adminsublink','device'));
}

public function DevInfoUpdate(Request $request,$id)
{
    $this->validate($request,[
            'name'=>'required',
            'type'=>'required',
        ]);
    $device=Device::find($id);
    $device->fill($request->all())->save();
    if($device){
        Session::flash('success','Device Info Updated Successfully.');
    }else{
        Session::flash('error','Sorry!! Something Went Wrong!!');
    }
    return redirect()->back();
    
}

public function DevInfoDelete(Request $request)
{
    if(count($request->id)>0){
        for ($i=0; $i < count($request->id) ; $i++) { 
            $device=Device::find($request->id[$i]);
            $device->delete();
        }
        if($device){
            Session::flash('success','Device(s) Deleted Successfully.');
            return '1';
        }else{
            return '0';
        }
    }else{
        return 'null';
    }
}

}