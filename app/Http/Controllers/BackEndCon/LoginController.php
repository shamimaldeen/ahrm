<?php

namespace App\Http\Controllers\BackEndCon;

use Illuminate\Http\Request;
use App\Http\Controllers\BackEndCon\Controller;

use App\Http\Requests;
use Auth;
use DB;
use Session;
use App\ProjectDetails;


class LoginController extends Controller
{
    public function index(){
        $project=ProjectDetails::find('1');
        return view('Login.new',compact('project'));
    }

    public function chek(Request $request){
        if($request->email!="" && $request->password!=""){
        $email=str_replace(' ','',$request->email);
        $search=DB::table('tbl_sysuser')->where(DB::raw("REPLACE(`email`,' ','')"),$email)->first();
        if(isset($search)){
            $credentials=["email"=>$email,"password"=>$request->password];
            if (Auth::guard('admin')->attempt($credentials)) 
            {
                DB::table('tbl_sysuser')->where(DB::raw("REPLACE(`email`,' ','')"),$email)->update(['suser_last_login'=>date('Y-m-d h:i:s')]);
                return redirect()->intended('Dashboard');
            }else{
                Session::flash('error','Oppps! Your User ID and Password does not matched. Please try again. Thanks');
                return redirect('login');
            }
        }else{
            Session::flash('error','Your account is yet to be created. Contact your local HR for account creation. Thanks');
            return redirect('login');
        }
        }else{
            Session::flash('error','Oops!! Please Write Your User ID and Password. Thanks');
            return redirect('login');
        }
    }

    public function forgetPasswordRequest(Request $request)
    {
        $employeeSearch=DB::table('tbl_employee')->where(DB::raw('trim(emp_empid)'),$request->emp_empid)->first();
        if(isset($employeeSearch)){
            $systemUserSearch=DB::table('tbl_sysuser')->where(['suser_empid'=>$employeeSearch->emp_id])->first();
            if(isset($systemUserSearch)){
                $searchPendingRequest=DB::table('tbl_forgetpassword')->where(['suser_empid'=>$systemUserSearch->suser_empid,'fp_status'=>'0'])->first();
                if(isset($searchPendingRequest)){
                    return '0<->Your previous Forget Password request is still pending.Please Wait.)';
                }else{
                    $submitNewRequest=DB::table('tbl_forgetpassword')
                        ->insert([
                            'suser_empid'=>$systemUserSearch->suser_empid,
                            'submitted_to'=>$employeeSearch->emp_seniorid,
                            'submitted_at'=>date('Y-m-d H:i:s'),
                        ]);
                    if(isset($submitNewRequest)){
                        return '1<->A forget password request has been submitted to your line manager - '.$this->getseniorName($employeeSearch->emp_seniorid).'. Wait till he/she change your password to default.';
                    }else{
                        return '0<->Sorry! Something Went Wrong. Please Try again.';
                    }
                }
            }else{
                return '0<->You Do not have any System User Account with this Employee ID - ('.$request->emp_empid.')';
            }
        }else{
            return '0<->No Emplyee Found with this Employee ID - ('.$request->emp_empid.')';
        }
    }
}
