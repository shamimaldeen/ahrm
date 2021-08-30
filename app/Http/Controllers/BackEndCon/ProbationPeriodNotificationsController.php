<?php

namespace App\Http\Controllers\BackEndCon;

use Illuminate\Http\Request;
use App\Http\Controllers\BackEndCon\Controller;
use Auth;
use App\Employee;
use App\ProbationPeriodNotifications;

class ProbationPeriodNotificationsController extends Controller
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
        if($id->suser_level=="1"){
            $employees=Employee::where('emp_joindate','!=','')->get();

        }else{
            $employees=Employee::where('emp_joindate','!=','')
                ->where(function($query) use($id){
                    return $query->where('emp_seniorid',$id->suser_empid)
                                 ->orWhere('emp_authperson',$id->suser_empid);
                })
                ->get();
           // dd(count($employees));
        }


        $probations = array();
        $counter=0;
        if($employees[0]){
            foreach ($employees as $key => $employee) {
                if(strtotime($employee->emp_joindate)){
                    $days=$this->days(date('Y-m-d',strtotime($employee->emp_joindate)),date('Y-m-d'));
                    if($days>=180){
                        $search=ProbationPeriodNotifications::where('emp_id',$employee->emp_id)->first();
                        if(!isset($search->id)){
                            $confirm_date=date('Y-m-d',strtotime(date("Y-m-d", strtotime($employee->emp_joindate)) . " +6 month"));
                            $employee->emp_confirmdate=$confirm_date;
                            $employee->save();
                            if(substr($confirm_date,0,4)==date('Y')){
                                $counter++;
                                $probations[$counter]=array([
                                    'emp_id' => $employee->emp_id,
                                    'emp_name' => $employee->emp_name,
                                    'emp_empid' => $employee->emp_empid,
                                    'emp_machineid' => $employee->emp_machineid,
                                    'join_date' => $employee->emp_joindate,
                                    'confirm_date' => $employee->emp_confirmdate,
                                ]);
                            }
                        }
                    }
                }
            }
        }

      //  dd(count($probations));

        return view('Admin.probationPeriodNot.index',compact('id','mainlink','sublink','Adminminlink','adminsublink','probations'));
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
    public function store(Request $request,ProbationPeriodNotifications $not)
    {
        $not->fill($request->all());
        $not->approved_by=Auth::guard('admin')->user()->suser_empid;
        $not->save();
        if($not){
            return response()->json([
                'success' => true,
                'msg' => 'Probation Period End notification successfully sent to this employee'
            ]);
        }

        return response()->json([
            'success' => false,
            'msg' => 'Something went wrong! Please Try again.'
        ]);
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
    public function edit($emp_id)
    {
        $id =   Auth::guard('admin')->user();
        if($emp_id==$id->suser_empid){
            $update=ProbationPeriodNotifications::where('emp_id',$emp_id)->update(["status"=>1]);
            if(!$update){
                session()->flash('error','Something Went Wrong!');
            }
            return redirect()->back();
        }

        return redirect('returntohome');
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
