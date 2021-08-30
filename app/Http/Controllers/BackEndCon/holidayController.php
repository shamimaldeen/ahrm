<?php

namespace App\Http\Controllers\BackEndCon;

use Illuminate\Http\Request;
use App\Http\Controllers\BackEndCon\Controller;
use Auth;
use Session;
use DB;
use DateTime;
use URL;

class holidayController extends Controller
{

public function generateHoliday($year)
{
    $description='Friday (Weekend Holiday)';
    $fridays=$this->getAllFridaysOfAYear($year);
    for ($i=0; $i < count($fridays) ; $i++) { 
        $check=DB::table('tbl_holiday')->where(['holiday_date'=>$fridays[$i],'holiday_type'=>'1'])->first();
        if(isset($check) && count($check)>0){
            echo '<span style="color:red">'.$fridays[$i].' Already Exists<br>';
        }else{
            $insert=DB::table('tbl_holiday')->insert([
                'holiday_date'=>$fridays[$i],
                'holiday_type'=>'1',
                'holiday_description'=>$description,
            ]);
        }
        
        if(isset($insert)){
            echo '<span style="color:green">'.$fridays[$i].' added as Holiday<br>';
        }   
    }

}

public function holiday(){
    $id =   Auth::guard('admin')->user();
    $mainlink = $this->adminmainmenu();
    $sublink = $this->adminsubmenu();
    $Adminminlink = $this->adminlink();
    $adminsublink = $this->adminsublink();

    return view('Admin.settings.holiday.create',compact('id','mainlink','sublink','Adminminlink','adminsublink'));
}

public function holidayAdd(Request $request)
{
    $this->validate($request,[
        'holiday_date'=>'required',
        'holiday_description'=>'required',
    ]);

    /*if($request->holiday_type=="1"){
        $check=DB::table('tbl_holiday')->where(['holiday_date'=>$request->holiday_date,'holiday_type'=>$request->holiday_type])->first();
        if(isset($check) && count($check)>0){
            Session::flash('error','Holiday Information Already Exists as Friday,(Weekend Holiday).');
        }else{
            $insert=DB::table('tbl_holiday')
            ->insert([
                'holiday_date'=>$request->holiday_date,
                'holiday_type'=>$request->holiday_type,
                'holiday_description'=>$request->holiday_description,
            ]);
            if(isset($insert)){
                Session::flash('success','Holiday Information Saved Successfully.');
            }else{
                Session::flash('error','Sorry!! Something Went Wrong!!');
            }
        }
    }else{*/
        $insert=DB::table('tbl_holiday')
        ->insert([
            'holiday_date'=>$request->holiday_date,
            'holiday_description'=>$request->holiday_description,
        ]);
        if(isset($insert)){
            Session::flash('success','Holiday Information Saved Successfully.');
        }else{
            Session::flash('error','Sorry!! Something Went Wrong!!');
        }
    //}
    
    return redirect('holiday-view/create');
}

public function holidayView(){
    $id =   Auth::guard('admin')->user();
    $mainlink = $this->adminmainmenu();
    $sublink = $this->adminsubmenu();
    $Adminminlink = $this->adminlink();
    $adminsublink = $this->adminsublink();
    $holiday=DB::table('tbl_holiday')->orderBy('holiday_date','asc')->get();
    return view('Admin.settings.holiday.view',compact('id','mainlink','sublink','Adminminlink','adminsublink','holiday'));
}

public function holidayEdit(Request $request)
{
    if(count($request->holiday_id)>0){
        if(count($request->holiday_id)>1){
            return 'max';
        }else{
            return $request->holiday_id[0];
        }
    }else{
        return 'null';
    }
}

public function holidayEditView($holiday_id)
{
    $id =   Auth::guard('admin')->user();
    $mainlink = $this->adminmainmenu();
    $sublink = $this->adminsubmenu();
    $Adminminlink = $this->adminlink();
    $adminsublink = $this->adminsublink();
    $holiday=DB::table('tbl_holiday')->where('holiday_id',$holiday_id)->first();
    return view('Admin.settings.holiday.editView',compact('id','mainlink','sublink','Adminminlink','adminsublink','holiday','holiday_id'));
}

public function holidayUpdate(Request $request,$holiday_id)
{
    $this->validate($request,[
        'holiday_date'=>'required',
        'holiday_description'=>'required',
        'holiday_status'=>'required',
    ]);

    /*if($request->holiday_type=="1"){
        $check=DB::table('tbl_holiday')->where(['holiday_date'=>$request->holiday_date,'holiday_type'=>$request->holiday_type])->first();
        if(isset($check) && count($check)>0){
            Session::flash('error','Holiday Information Already Exists as Friday,(Weekend Holiday).');
        }else{
            $update=DB::table('tbl_holiday')
                ->where('tbl_holiday.holiday_id',$holiday_id)
            ->update([
                'holiday_date'=>$request->holiday_date,
                'holiday_type'=>$request->holiday_type,
                'holiday_description'=>$request->holiday_description,
                'holiday_status'=>$request->holiday_status,
            ]);
            if(isset($update)){
                Session::flash('success','Holiday Information Updated Successfully.');
            }else{
                Session::flash('error','Sorry!! Something Went Wrong!!');
            }
        }
    }else{*/
        $update=DB::table('tbl_holiday')
        ->where('tbl_holiday.holiday_id',$holiday_id)
        ->update([
            'holiday_date'=>$request->holiday_date,
            'holiday_description'=>$request->holiday_description,
            'holiday_status'=>$request->holiday_status,
        ]);
        if(isset($update)){
            Session::flash('success','Holiday Information Updated Successfully.');
        }else{
            Session::flash('error','Sorry!! Something Went Wrong!!');
        }
    //}
    
    return redirect('holiday-view/'.$holiday_id.'/edit');
}


public function holidayDelete(Request $request)
{
    if(count($request->holiday_id)>0){
        for ($i=0; $i < count($request->holiday_id) ; $i++) { 
            $delete_attendance=DB::table('tbl_holiday')->where('holiday_id',$request->holiday_id[$i])->delete();
        }
        if($delete_attendance){
            Session::flash('success','Holiday(s) Deleted Successfully.');
            return '1';
        }else{
            return '0';
        }
    }else{
        return 'null';
    }
}

}