<?php

namespace App\Http\Controllers\BackEndCon;

use Illuminate\Http\Request;
use App\Http\Controllers\BackEndCon\Controller;
use Auth;
use App\SwitchIncentive;

class SwitchIncentiveCalculationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $id =   Auth::guard('admin')->user();
        $mainlink = $this->adminmainmenu();
        $sublink = $this->adminsubmenu();
        $Adminminlink = $this->adminlink();
        $adminsublink = $this->adminsublink();
        $SwitchIncentive=SwitchIncentive::orderBy('id','desc')->first();
        return view('Admin.settings.switchIncentive.index',compact('id','mainlink','sublink','Adminminlink','adminsublink','SwitchIncentive'));
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $SwitchIncentive=SwitchIncentive::orderBy('id','desc')->first();
        if(isset($SwitchIncentive->incentive)){
            $SwitchIncentive->fill($request->all())->save();
            if($SwitchIncentive){
                session()->flash('success','Incentive Calculation Switched Successfully.');
            }else{
                session()->flash('error','Something Went Wrong!');
            }
        }else{
            $SwitchIncentive=new SwitchIncentive();
            $SwitchIncentive->fill($request->all())->save();
            if($SwitchIncentive){
                session()->flash('success','Incentive Calculation Switched Successfully.');
            }else{
                session()->flash('error','Something Went Wrong!');
            }
        }

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
