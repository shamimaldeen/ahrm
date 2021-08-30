<?php

namespace App\Http\Controllers\BackEndCon;

use Illuminate\Http\Request;
use App\Http\Controllers\BackEndCon\Controller;
use App\Country;
use Auth;
use DB;


class CountryController extends Controller
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
       // $countries =Country::get();
        $countries = DB::table('tbl_country')->get();
        //dd($countries);

        return view('Admin.performance.country.view',compact('id','mainlink','sublink','Adminminlink','adminsublink','countries'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $id =   Auth::guard('admin')->user();
        $mainlink = $this->adminmainmenu();
        $sublink = $this->adminsubmenu();
        $Adminminlink = $this->adminlink();
        $adminsublink = $this->adminsublink();

        return view('Admin.performance.country.create',compact('id','mainlink','sublink','Adminminlink','adminsublink'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,Country $country )
    {
       // dd($request->all());

        $country->fill($request->all())->save();
        if($country){
            session()->flash('success',' Stored Successfully');
        }else{
            session()->flash('error','Something Went Wrong!!');
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
    public function edit($val_id)
    {
        $id =   Auth::guard('admin')->user();
        $mainlink = $this->adminmainmenu();
        $sublink = $this->adminsubmenu();
        $Adminminlink = $this->adminlink();
        $adminsublink = $this->adminsublink();
        $countries=Country::find($val_id);
        return view('Admin.performance.country.edit',compact('id','mainlink','sublink','Adminminlink','adminsublink','countries'));
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

        $countries=Country::find($id);
        $countries->fill($request->all())->save();
        if($countries){
            session()->flash('success',' Updated Successfully');
        }else{
            session()->flash('error','Something Went Wrong!!');
        }
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $countries=Country::find($id)->delete();
        if($countries){
            return response()->json(["success"=>true]);
        }else{
            return response()->json(["success"=>false,'msg'=>'Something Went Wrong!']);
        }
    }
}
