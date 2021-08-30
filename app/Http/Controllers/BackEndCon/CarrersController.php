<?php

namespace App\Http\Controllers\BackEndCon;

use Illuminate\Http\Request;
use App\Http\Controllers\BackEndCon\Controller;
use App\Recruitment\Job;
use App\Recruitment\Application;

class CarrersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $jobs=Job::where([['closing_date','>=',date('Y-m-d')],'inactive'=>0])->get();
        return view('Careers.jobs',compact('jobs'));
    }

    public function job($id)
    {
        $job=Job::find($id);
        return view('Careers.job',compact('job'));
    }

    public function jobApply($id)
    {
        if(!session()->get('jobseeker_login')){
            return redirect()->back();
        }
        $job=Job::find($id);
        return view('Careers.apply',compact('job'));
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
    public function store(Request $request,Application $application)
    {
        $this->validate($request,[
            'intro'=>'required'
        ]);

        $application->fill($request->all())->save();
        if($application){
            session()->flash('success','Congrats! You have successfully applied to this job!');
            return redirect('careers/job/'.$request->job_id);
        }else{
            session()->flash('error','Something went wrong!');
            return redirect()->back();
        }
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
