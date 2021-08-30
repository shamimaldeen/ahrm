<?php

namespace App\Http\Controllers\BackEndCon;

use Illuminate\Http\Request;
use App\Http\Controllers\BackEndCon\Controller;
use App\Recruitment\Jobseeker;
use App\Recruitment\Application;
use Hash;

class JoinUsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(session()->get('jobseeker_login')){
            return redirect('careers');
        }
        return view('Careers.profile.joinus');
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
    public function store(Request $request,Jobseeker $jobseeker)
    {
        $this->validate($request,[
            'name'=>'required',
            'email'=>'required',
            'password'=>'required|min:6',
            'confirm_password'=>'required|min:6',
            'phone'=>'required',
            'sex'=>'required',
        ]);

        if($request->password==$request->confirm_password){
            $jobseeker->fill($request->all());
            $jobseeker->password=bcrypt($request->password);
            $jobseeker->save();
            if($jobseeker){
                session()->put('jobseeker_login',true);
                session()->put('jobseeker_id',$jobseeker->id);
                session()->flash('success','Congrates! You are now a member of ours. You can apply for our new jobs too.');
                return redirect('careers');
            }else{
                session()->flash('error','Something went wrong!');
            }
        }else{
            session()->flash('error','Password not matched!');
        }

        return redirect()->back();
    }

    public function login(Request $request)
    {
        $this->validate($request,[
            'email'=>'required',
            'password'=>'required',
        ]);
        $jobseeker=Jobseeker::where('email',trim($request->email))->first();
        if(isset($jobseeker->email)){
            if (Hash::check($request->password, $jobseeker->password)) {
                session()->put('jobseeker_login',true);
                session()->put('jobseeker_id',$jobseeker->id);
                return redirect('careers');
            }else{
                session()->flash('error','Sorry! Password is incorrect!');
            }
        }else{
            session()->flash('error','Sorry! Email is not exist!');
        }
        return redirect()->back();
        
    }

    public function logout()
    {
        session()->put('jobseeker_login');
        session()->forget('jobseeker_id');
        return redirect('careers');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $applications=Application::with(['job'])->where('jobseeker_id',$id)->get();
        return view('Careers.profile.applications',compact('applications'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $jobseeker=Jobseeker::find($id);
        return view('Careers.profile.resume-edit',compact('jobseeker'));
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
        $this->validate($request,[
            'name'=>'required',
            'email'=>'required',
            'phone'=>'required',
            'sex'=>'required',
        ]);

        $jobseeker=Jobseeker::find($id);
        $jobseeker->fill($request->all());
        $jobseeker->save();
        if($jobseeker){
            if($jobseeker->resume!=""){
                if(file_exists(base_path().'/public/jobseeker/resume/'.$jobseeker->resume)){
                    unlink(base_path().'/public/jobseeker/resume/'.$jobseeker->resume);
                }
            }
            $file=$request->file('file');
            $name=$jobseeker->id.'-'.$jobseeker->email.'-'.$jobseeker->phone.'.'.$file->getClientOriginalExtension();
            if($file->move(public_path('/jobseeker/resume/'), $name)){
                $jobseeker=Jobseeker::find($id);
                $jobseeker->resume=$name;
                $jobseeker->save();
            }
            session()->flash('success','Your Resume has been updated.');
            return redirect()->back();
        }else{
            session()->flash('error','Something went wrong!');
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
        //
    }

    public function applications()
    {
        if(session()->get('jobseeker_login')){
            return redirect('careers');
        }


    }
}
