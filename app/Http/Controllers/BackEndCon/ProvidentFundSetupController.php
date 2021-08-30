<?php

namespace App\Http\Controllers\BackEndCon;

use Illuminate\Http\Request;
use App\Http\Controllers\BackEndCon\Controller;
use Auth;
use App\ProvidentFundSetup;

class ProvidentFundSetupController extends Controller
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
        $ProvidentFundSetup=ProvidentFundSetup::find('1');
        if(!isset($ProvidentFundSetup->id)){
            $ProvidentFundSetup=false;
        }

        return view('Admin.providentFund.setup.index',compact('id','mainlink','sublink','Adminminlink','adminsublink','ProvidentFundSetup'));
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
        $this->validate($request,[
            'provident_fund'=>'required'
        ]);

        if($request->provident_fund=="1"){
            $this->validate($request,[
                'employee_percentage'=>'required',
                'company_percentage'=>'required'
            ]);
        }


        $ProvidentFundSetup=ProvidentFundSetup::find('1');
        $ProvidentFundSetup->fill($request->all())->save();
        if($ProvidentFundSetup){
            session()->flash('success','Provident Fund Setup has been updated.');
        }else{
            session()->flash('error','Something Went Wrong! Try Again');
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
