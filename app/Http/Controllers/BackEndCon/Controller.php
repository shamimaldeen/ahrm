<?php

namespace App\Http\Controllers\BackEndCon;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use DB;
use Auth;
use Session;
use Image;
use DateTime;
use DateInterval;
use DatePeriod;
use File;

use App\Employee;
use App\MainMenu;
use App\SubMenu;
use App\PriorityRole;
use App\AppModulePriority;
use App\Department;
use App\SubDepartment;
use App\Designation;
use App\JobLocation;
use App\Shift;
use App\DailyShift;
use App\KQZEmployee;
use App\Device;
use App\RemoteDevice;
use App\LeaveType;
use App\EmployeeHistory;
use App\Country;
use App\Priority;
use App\Salary;
use App\ProjectDetails;
use App\EmployeeTypes;

class Controller extends BaseController
{
    public function id()
    {
        $id =   Auth::guard('admin')->user();
        return $id;
    }

    public function adminInfo($suser_empid)
    {
        return Employee::find($suser_empid);
    }


    public function adminmainmenu()
    {
        $id =   Auth::guard('admin')->user();
        return $mainlink = DB::table('tbl_priorityrole')
           ->join('adminmainmenu', 'adminmainmenu.id', '=', 'tbl_priorityrole.prrole_menuid')
            ->select('tbl_priorityrole.*','adminmainmenu.*')
           ->groupBy('tbl_priorityrole.prrole_menuid')
           ->orderBy('adminmainmenu.serialNo', 'ASC')
            ->where('tbl_priorityrole.prrole_prid',$id->suser_level)
          ->get();
    }
    public function adminsubmenu()
    {
        $id =   Auth::guard('admin')->user();
        return $sublink = DB::table('tbl_priorityrole')
           ->join('adminsubmenu', 'adminsubmenu.id', '=', 'tbl_priorityrole.prrole_submenuid')
            ->select('tbl_priorityrole.*','adminsubmenu.*')
            ->orderBy('adminsubmenu.serialno', 'ASC')
            ->groupBy('tbl_priorityrole.prrole_submenuid')
            ->where('tbl_priorityrole.prrole_prid',$id->suser_level)
            ->get();
    }

    public function adminlink()
    {
        return MainMenu::orderBy('serialNo', 'ASC')->where('status', 1 )->get();
    }

    public function adminsublink()
    {
        return SubMenu::orderBy('serialno', 'ASC')->get();
    }
    public function mainmenu()
    {
        return MainMenu::orderBy('serialNo', 'ASC')->get();
    }

    public function submenu()
    {
        return SubMenu::orderBy('serialno', 'ASC')->get();
    }

    public function sendMail($to,$subject,$msg)
    {
        $projectDetails=ProjectDetails::find('1');
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= 'From: '.$projectDetails->project_name.' :: '.$projectDetails->company_name.'<no-reply@roopokar.com>' . "\r\n";
        mail($to,$subject,$msg,$headers);
    }

    static public function getSeniorName($emp_id)
    {
        $senior=Employee::find($emp_id);
        if(isset($senior->emp_empid)){
            return $senior->emp_name.' ('.$senior->emp_empid.')';
        }else{
            return '';
        }
    }

    public function emp_name($emp_id)
    {
        $employee=Employee::find($emp_id);
        if(isset($employee->emp_name)){
            return $employee->emp_name;
        }else{
            return '';
        }
    }

    public function emp_empid($emp_id)
    {
        $employee=Employee::find($emp_id);
        if(isset($employee->emp_empid)){
            return $employee->emp_empid;
        }else{
            return '';
        }
    }

    public function emp_id($emp_empid)
    {
        $employee=Employee::where(DB::raw('trim(emp_empid)'),trim($emp_empid));
        if(isset($employee->emp_id)){
            return $employee->emp_id;
        }else{
            return '';
        }
    }

    static public function getSeniorEmail($emp_id)
    {
        $senior=Employee::find($emp_id);
        if(isset($senior->emp_email)){
            return $senior->emp_email;
        }else{
            return '';
        }
    }

    static public function getAuthorName($emp_id)
    {
        $author=Employee::find($emp_id);
        if(isset($author->emp_empid)){
            return $author->emp_name.' ('.$author->emp_empid.')';
        }else{
            return '';
        }
    }

    static public function checkSubmenuExist($id)
    {
        $submenu=SubMenu::where('mainmenuId',$id)->get();
        if(isset($submenu[0])){
            return '1';
        }else{
            return '0';
        }
    }

    static public function checkMenuChecked($id,$pr_id)
    {
        $check=PriorityRole::where(['prrole_prid'=>$pr_id,'prrole_menuid'=>$id])->first();
        if(isset($check->prrole_prid)){
            return '1';
        }else{
            return '0';
        }
    }

    static public function checkSubmenuChecked($id,$pr_id)
    {
        $check=PriorityRole::where(['prrole_prid'=>$pr_id,'prrole_submenuid'=>$id])->first();
        if(isset($check->prrole_prid)){
            return '1';
        }else{
            return '0';
        }
    }

    static public function checkAppModuleChecked($id,$pr_id)
    {
        $check=AppModulePriority::where(['amp_prid'=>$pr_id,'amp_appmid'=>$id])->first();
        if(isset($check->amp_prid)){
            return '1';
        }else{
            return '0';
        }
    }

    static public function checkLinkPriority($route)
    {
        $id =   Auth::guard('admin')->user();
        
        if($id->id=="1000" or $route=="change-password" or $route=="Dashboard" or $route=="report-submit" or $route=="uploadPreview" or $route=="generate-day-wise-payroll-submit" or $route=="generate-month-wise-payroll-submit"){
            return '1';
        }

        $explode=explode('/',$route);
        if(count($explode)>=1){
            $checkmain=PriorityRole::
                join('adminmainmenu','tbl_priorityrole.prrole_menuid','=','adminmainmenu.id')
                ->where('adminmainmenu.routeName',$explode[0])
                ->where('tbl_priorityrole.prrole_prid',$id->suser_level)
                ->first();
            if(isset($checkmain->prrole_prid)){
                return '1';
            }else{
                $checksub=PriorityRole::
                    join('adminsubmenu','tbl_priorityrole.prrole_submenuid','=','adminsubmenu.id')
                    ->where('adminsubmenu.routeName',$explode[0])
                    ->where('tbl_priorityrole.prrole_prid',$id->suser_level)
                    ->first();
                if(isset($checksub->prrole_prid)){
                    return '1';
                }else{
                    Session::flash('error','Sorry! You might have entered an invalid Link or You might not have the permission to view this link.');
                    return '0';
                }
            }
        }else{
            $checkmain=PriorityRole::
                join('adminmainmenu','tbl_priorityrole.prrole_menuid','=','adminmainmenu.id')
                ->where('adminmainmenu.routeName',$route)
                ->where('tbl_priorityrole.prrole_prid',$id->suser_level)
                ->first();
            if(isset($checkmain->prrole_prid)){
                return '1';
            }else{
                $checksub=PriorityRole::
                    join('adminsubmenu','tbl_priorityrole.prrole_submenuid','=','adminsubmenu.id')
                    ->where('adminsubmenu.routeName',$route)
                    ->where('tbl_priorityrole.prrole_prid',$id->suser_level)
                    ->first();
                if(isset($checksub->prrole_prid)){
                    return '1';
                }else{
                    Session::flash('error','Sorry! You might have entered an invalid Link or You might not have the permission to view this link.');
                    return '0';
                }
            }
        }
    }

    static public function checkAppModulePriority($route,$appmodulename)
    {
        $explode=explode('/',$route);
        if(count($explode)>=1){
            $route=$explode[0];
        }
        
        $id =   Auth::guard('admin')->user();
        $checkmain=AppModulePriority::
            join('tbl_appmodule','tbl_appmodulepriority.amp_appmid','=','tbl_appmodule.appm_id')
            ->join('adminmainmenu','tbl_appmodule.appm_menuid','=','adminmainmenu.id')
            ->where(['tbl_appmodule.appm_name'=>$appmodulename,'adminmainmenu.routeName'=>$route,'tbl_appmodulepriority.amp_prid'=>$id->suser_level])
            ->first();
        if(isset($checkmain->amp_prid)){
            return '1';
        }else{
        $checksub=AppModulePriority::
            join('tbl_appmodule','tbl_appmodulepriority.amp_appmid','=','tbl_appmodule.appm_id')
            ->join('adminsubmenu','tbl_appmodule.appm_submenuid','=','adminsubmenu.id')
            ->where(['tbl_appmodule.appm_name'=>$appmodulename,'adminsubmenu.routeName'=>$route,'tbl_appmodulepriority.amp_prid'=>$id->suser_level])
            ->first();
            if(isset($checksub->amp_prid)){
                return '1';
            }else{
                return '0';
            }
        }
    }

    static public function getDepartmentId($emp_id)
    {
        $department=Employee::find($emp_id);
        if(isset($department->emp_depart_id)){
            return $department->emp_depart_id;
        }else{
            return '';
        }
    }

    static public function getDepartmentName($emp_depart_id)
    {
        $department=Department::find($emp_depart_id);
        if(isset($department->depart_name)){
            return $department->depart_name;
        }else{
            return '';
        }
    }

    static public function getSubDepartmentName($emp_sdepart_id)
    {
        $subdepartment=SubDepartment::find($emp_sdepart_id);
        if(isset($subdepartment->sdepart_name)){
            return $subdepartment->sdepart_name;
        }else{
            return '';
        }
    }

    static public function getDesignationName($emp_desig_id)
    {
        $designation=Designation::find($emp_desig_id);
        if(isset($designation->desig_name)){
            return $designation->desig_name.'&nbsp;&nbsp;'.$designation->desig_specification;
        }else{
            return '';
        }
    }

    static public function getTypeName($emp_type)
    {
        $type=EmployeeTypes::find($emp_type);
        if(isset($type->name)){
            return $type->name;
        }else{
            return '';
        }
    }

    static public function getOnlyDesignationName($emp_desig_id)
    {
        $designation=Designation::find($emp_desig_id);
        if(isset($designation->desig_name)){
            return $designation->desig_name;
        }else{
            return '';
        }
    }

    static public function getLocationName($emp_jlid)
    {
        $joblocation=JobLocation::find($emp_jlid);
        if(isset($joblocation->jl_name)){
            return $joblocation->jl_name;
        }else{
            return '';
        }
    }

    static public function getEmployeeType($emp_type)
    {
        $type=\App\EmployeeTypes::find($emp_type);
        if(isset($type->id)){
            return $type->name;
        }
    }

    static public function getShiftInfo($emp_shiftid)
    {
        $shift=Shift::find($emp_shiftid);
        if(isset($shift->shift_stime)){
            return date('g:i a',strtotime($shift->shift_stime)).' - '.date('g:i a',strtotime($shift->shift_etime));
        }else{
            return '';
        }
    }

    public function emp_shiftid($shift,$workhr)
    {
        if(empty($shift) || empty($workhr)){
            return '0';
        }
        $explode=explode('-',$shift);
        if(isset($explode)){
            $shift_stime=trim(explode('-',$shift)[0]);
            $shift_etime=trim(explode('-',$shift)[1]);
        }else{
            $shift_stime='09:00:00';
            $shift_etime='18:00:00';
        }
        
        $search=Shift::where(['shift_stime'=>$shift_stime,'shift_etime'=>$shift_etime])->first();
        if(isset($search->shift_id)){
            return $search->shift_id;
        }else{
            Shift::insert([
                'shift_type'=>$this->getWorkhr($workhr),
                'shift_stime'=>$shift_stime,
                'shift_etime'=>$shift_etime,
            ]);
            return Shift::max('shift_id');
        }
        
    }

    static public function getCurrentShiftInfo($emp_id,$date)
    {
        $tbl_dailyshift=DailyShift::
            where([['ds_date','<=',$date],'ds_empid'=>$emp_id])
            ->orderBy('ds_date','desc')
            ->first();
        if(isset($tbl_dailyshift->ds_date)){
            $shift=Shift::where('shift_id',$tbl_dailyshift->ds_shiftid)->first();
            if(isset($shift)){
                return $shift;
            }
        }
    }

    static public function getShifts($emp_workhr,$emp_shiftid)
    {
        $data='';
        $shift=Shift::where('shift_type',$emp_workhr)->orderBy('shift_stime','asc')->get();
        if(isset($shift)){
           foreach ($shift as $s) {
               if($s->shift_id==$emp_shiftid){
                $data.='<option value="'.$s->shift_id.'" selected="selected">'.$s->shift_stime.' - '.$s->shift_etime.'</option>';
               }else{
                $data.='<option value="'.$s->shift_id.'">'.$s->shift_stime.' - '.$s->shift_etime.'</option>';
               }
           }
        }
        return $data;
    }

    static public function getLineManagerDepartment($suser_empid)
    {
        $department=Employee::with(['department'])->find($suser_empid);
        if(isset($department->emp_depart_id)){
            return '<option value="'.$department->emp_depart_id.'">'.$department->department->depart_name.'</option>';
        }else{
            return '';
        }
    }

    static public function checkClock()
    {
        $id =   Auth::guard('admin')->user();
        $check=DB::table('tbl_clock')
           ->join('tbl_remotedevice','tbl_clock.clock_device','=','tbl_remotedevice.id')
           ->where('tbl_clock.clock_empid',$id->suser_empid)
           ->where(DB::raw("substr(tbl_clock.clock_time, 1, 11)"), '=',date('Y-m-d'))
           ->orderBy('tbl_clock.clock_id','DESC')
           ->first(['tbl_remotedevice.type']);
        if(isset($check->type)){
            return $check->type;
        }else{
            return 'null';
        }
    }

    static public function checkEnable()
    {
        $id =   Auth::guard('admin')->user();
        if($id->suser_status=="1"){
            return '1';
        }else{
            Session::flash('error','Whoops!! Your Account has been disabled by the authority. Please Contact with the authority to make it Enable Again. Thanks');
            return '0';
        }
    }

    static public function getCountryName($id)
    {
        $country=Country::find($id);
        if(isset($country->country_name)){
            return $country->country_name;
        }else{
            $country=Country::where('country_name',$id)->first(['id']);
            if(isset($country->id)){
                return $country->id;
            }else{
                return '-';
            }
        }
    }

    public function YesOrNo($value)
    {
        if($value=="1"){
            return 'Yes';
        }elseif($value=="2"){
            return 'No';
        }elseif($value=="Yes"){
            return '1';
        }elseif($value=="No"){
            return '2';
        }
    }

    static public function getWorkHour($value)
    {
        if($value=="1"){
            return '7 Hours (+1 hrs lunch)';
        }elseif($value=="2"){
            return '8 Hours (+1 hrs lunch)';
        }elseif($value=="3"){
            return '6 Hours';
        }
    }

    static public function getWorkhr($value)
    {
        if($value=="7"){
            return '1';
        }elseif($value=="8"){
            return '2';
        }
    }

    public function getDateRange($startDate, $endDate, $format = "Y-m-d")
    {
        $begin = new DateTime($startDate);
        $end = new DateTime($endDate);

        $interval = new DateInterval('P1D');
        $dateRange = new DatePeriod($begin, $interval, $end);

        $range = [];
        foreach ($dateRange as $date) {
            $range[] = $date->format($format);
        }
        array_push($range, $endDate);

        return $range;
    }

    // public function getMonth($mon)
    // {
    //     $begin = new DateTime($startDate);
    //     $end = new DateTime($endDate);

    //     $interval = new DateInterval('P1D');
    //     $dateRange = new DatePeriod($begin, $interval, $end);

    //     $range = [];
    //     foreach ($dateRange as $date) {
    //         $range[] = $date->format($format);
    //     }
    //     array_push($range, $endDate);

    //     return $range;
    // }

    public function days($start_date,$end_date)
    {
        $start_date=new DateTime('00:00:00 '.$start_date);
        $end_date=new DateTime('00:00:00 '.$end_date);
        $difference=$end_date->diff($start_date);
        return $difference->days+1;
    }

    function timeToHours($time)
    {
        $seconds=0;
        $explode = explode(':', $time);
        if (isset($explode[0]) && isset($explode[1]) && isset($explode[2])) {
            
            // extra code start
            $explode[0] = (int) $explode[0];
            $explode[1] = (int) $explode[1];
            $explode[2] = (int) $explode[2];
            //extra code end

            $seconds+=$explode[0] * 3600 + $explode[1] * 60 + $explode[2];

        }
        if($seconds<=0){
            $hours=0;
        }else{
            $hours=$seconds/3600;
        }
        return number_format((float)$hours, 2, '.', '');
    }

    static public function inWord($number) {

        $hyphen      = '-';
        $conjunction = ' and ';
        $separator   = ', ';
        $negative    = 'negative ';
        $decimal     = ' point ';
        $dictionary  = array(
            0                   => 'zero',
            1                   => 'one',
            2                   => 'two',
            3                   => 'three',
            4                   => 'four',
            5                   => 'five',
            6                   => 'six',
            7                   => 'seven',
            8                   => 'eight',
            9                   => 'nine',
            10                  => 'ten',
            11                  => 'eleven',
            12                  => 'twelve',
            13                  => 'thirteen',
            14                  => 'fourteen',
            15                  => 'fifteen',
            16                  => 'sixteen',
            17                  => 'seventeen',
            18                  => 'eighteen',
            19                  => 'nineteen',
            20                  => 'twenty',
            30                  => 'thirty',
            40                  => 'fourty',
            50                  => 'fifty',
            60                  => 'sixty',
            70                  => 'seventy',
            80                  => 'eighty',
            90                  => 'ninety',
            100                 => 'hundred',
            1000                => 'thousand',
            1000000             => 'million',
            1000000000          => 'billion',
            1000000000000       => 'trillion',
            1000000000000000    => 'quadrillion',
            1000000000000000000 => 'quintillion'
        );

        if (!is_numeric($number)) {
            return false;
        }

        if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
            // overflow
            trigger_error(
                'System only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
                E_USER_WARNING
            );
            return false;
        }

        if ($number < 0) {
            return $negative . Self::inWord(abs($number));
        }

        $string = $fraction = null;

        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
        }

        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens   = ((int) ($number / 10)) * 10;
                $units  = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds  = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= $conjunction . Self::inWord($remainder);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int) ($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = Self::inWord($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= Self::inWord($remainder);
                }
                break;
        }

        if (null !== $fraction && is_numeric($fraction)) {
            $string .= $decimal;
            $words = array();
            foreach (str_split((string) $fraction) as $number) {
                $words[] = $dictionary[$number];
            }
            $string .= implode(' ', $words);
        }

        return $string;
    }

    function hoursToTime($time)
    {
        return gmdate('H:i:s', floor($time * 3600));
    }

    public function getEmployeeIDtoemp_id($EmployeeID)
    {
        $employee=KQZEmployee::with(['employee'])->find($EmployeeID);
        if(isset($employee->emp_id)){
            return $employee->emp_id;
        }else{
            return '';
        }
    }

    public function getemp_idtoEmployeeID($emp_id)
    {
        $employee=Employee::with(['kqzemployee'])->find($emp_id);
        
        if(isset($employee->kqzemployee->EmployeeID)){
            return $employee->kqzemployee->EmployeeID;
        }else{
            return '';
        }
    }

    public function getDeviceName($id)
    {
        $Device=Device::find($id);
        if(isset($Device->name)){
            return $Device->name;
        }else{
            return '';
        }
    }

    public function getRemoteDeviceName($DevID)
    {
        $Device=RemoteDevice::find($DevID);
        if(isset($Device->DevName)){
            return $Device->DevName;
        }else{
            return '';
        }
    }

    public function getLeaveTypename($li_id)
    {
        $leave=LeaveType::find($li_id);
        if(isset($leave->li_name)){
            return $leave->li_name;
        }else{
            return '';
        }
    }

    public function empHistory($eh_empid,$eh_log)
    {
        $id =   Auth::guard('admin')->user();
        $empHistory=EmployeeHistory::insert([
            'eh_empid'=>$eh_empid,
            'eh_log'=>$eh_log,
            'eh_operator'=>$id->suser_empid,
            'eh_datetime'=>date('Y-m-d H:i:s'),
        ]);
    }

    public function uniquecode($length,$prefix,$max_field,$table)
    {
        $prefix_length=strlen($prefix);
        
        $max_id=DB::select("SELECT MAX(".$max_field.") AS ".$max_field." FROM ".$table." WHERE SUBSTR(".$max_field.",1,".$prefix_length.")='".$prefix."'");
        $only_id=substr($max_id[0]->$max_field,$prefix_length);
        $new=(int)($only_id);
        $new++;
        $number_of_zero=$length-$prefix_length-strlen($new);
        $zero=str_repeat("0", $number_of_zero);
        $made_id=$prefix.$zero.$new;
        return $made_id;
    }

    public function ticket_uniquecode()
    {
        $id_length=10;
        
        $year=substr(date('Y'),2,2);
        $month=date('m');
        $date=date('d');
        $prefix=$year.$month.$date;
        $max_id=DB::select("SELECT MAX(ticket_code) AS ticket_code FROM tbl_ticket WHERE SUBSTR(ticket_code,1,6)='".$prefix."'");
        $prefix_length=strlen($prefix);
        $only_id=substr($max_id[0]->ticket_code,$prefix_length);
        $new=(int)($only_id);
        $new++;
        $number_of_zero=$id_length-$prefix_length-strlen($new);
        $zero=str_repeat("0", $number_of_zero);
        $made_id=$prefix.$zero.$new;
        return $made_id;
    }

    public function getUsreRoleInfo($pr_id)
    {
        $buttonArray = array( 
            '1' =>'btn btn-primary btn-xs', 
            '2' =>'btn btn-success btn-xs', 
            '3' =>'btn btn-danger btn-xs', 
            '4' =>'btn btn-info btn-xs', 
            '5' =>'btn btn-default btn-xs', 
            '6' =>'btn btn-default btn-xs', 
        );
        $priority=Priority::find($pr_id);
        if(isset($priority->pr_id)){
            return '<a class="'.$buttonArray[$priority->pr_id].'">'.$priority->pr_name.'</a>';
        }else{
            return '';
        }
    }

    public function getAllFridaysOfAYear($year)
    {
        $fridays=[];
        $given_year = strtotime("1 January $year");
        $for_start = strtotime('Friday', $given_year);
        $for_end = strtotime('+1 year', $given_year);
        for ($i = $for_start; $i <= $for_end; $i = strtotime('+1 week', $i)) {
            $fridays[]=date('Y-m-d', $i);
        }
        return $fridays;
    }

    public function dayName($day)
    {
        $dayName = array(
            '1'=>'Fri', 
            '2'=>'Sat', 
            '3'=>'Sun', 
            '4'=>'Mon', 
            '5'=>'Tue', 
            '6'=>'Wed', 
            '7'=>'Thu', 
        );

        if(isset($day)){
            return $dayName[$day];
        }else{
            return $dayName;
        }
    }

    static public function weekend($day)
    {
        $dayName = array(
            '1'=>'Friday', 
            '2'=>'Saturday', 
            '3'=>'Sunday', 
            '4'=>'Monday', 
            '5'=>'Tuesday', 
            '6'=>'Wednesday', 
            '7'=>'Thursday', 
        );

        if(isset($day)){
            if(isset($dayName[$day])){
                return $dayName[$day];
            }
        }else{
            return '';
        }
    }

    public function weekendIndex($day)
    {
        $dayName = array(
            'Friday'=>'1', 
            'Saturday'=>'2', 
            'Sunday'=>'3', 
            'Monday'=>'4', 
            'Tuesday'=>'5', 
            'Wednesday'=>'6', 
            'Thursday'=>'7', 
        );

        if(isset($day)){
            if(!empty($day)){
                return $dayName[$day];
            }else{
                return '1';
            }
        }else{
            return $dayName;
        }
    }

    public function permittedEmployee($emp_id)
    {
        $id =   Auth::guard('admin')->user();
        if($id->suser_empid!=$emp_id){
            $search=Employee::find($emp_id);
            if($search->emp_id){
                if($id->suser_level=="1"){
                    return '1';
                }
                if($search->emp_seniorid==$id->suser_empid or $search->emp_authperson==$id->suser_empid){
                    return '1';
                }else{
                    return '0';
                }
            }else{
                return '0';
            }
        }else{
            return '0';
        }
    }

    static public function leaveHours($leaveApplication)
    {
        $emp_workhr=Employee::find($leaveApplication->leave_empid);
        if(isset($emp_workhr->emp_workhr)){
            if($emp_workhr->emp_workhr=="1"){
                $workhour=8;
            }elseif($emp_workhr->emp_workhr=="2"){
                $workhour=9;
            }

            return $total=round($leaveApplication->leave_day*$workhour);
        }
    }

    public function status($data)
    {
        if($data=="Active"){
            return '1';
        }elseif ($data=="Inactive") {
            return '0';
        }elseif ($data=="1") {
            return 'Active';
        }elseif ($data=="0") {
            return 'Inactive';
        }else{
            return '1';
        }
    }

    function taxCalculaTion($taxAbleIncome, $emp){
        $gender=Salary::where('emp_id',$emp->emp_id)->first(['gender']);
        if(isset($gender->gender)){
            if($gender->gender == "1"){
                $start = 250000;
            }elseif($gender->gender == "2"){
                $start = 300000;
            }
        }else{
            $start = 250000;
        }

        //$inv = $taxAbleIncome*(30/100);
        //$investAllowance = $inv*(15/100);
        $tax = 0; 
        $taxCalculaTionValue = $taxAbleIncome - $start;
        if($taxCalculaTionValue > 0){
            if($taxCalculaTionValue < 400000){ 
                $tax = $taxCalculaTionValue*(10/100);
            }elseif ($taxCalculaTionValue >= 400000 && $taxCalculaTionValue <= 500000) {
                $tax = $taxCalculaTionValue*(15/100);
            }elseif ($taxCalculaTionValue >= 500000 && $taxCalculaTionValue <= 600000) {
                 $tax = $taxCalculaTionValue*(20/100);
            }elseif ($taxCalculaTionValue >= 600000 && $taxCalculaTionValue <= 3000000) {
                $tax = $taxCalculaTionValue*(25/100);
            }elseif($taxCalculaTionValue >= 3000000) {
                $tax = $taxCalculaTionValue*(30/100);
            }
        }else{
            $tax = 0;
        }

        // if($taxCalculaTionValue > 0){
        //     if(($tax - $investAllowance) > 0){
        //         if(($tax - $investAllowance) < 500){
        //             $tax = 5000;
        //         }else{
        //             $tax -= $investAllowance;
        //         }
        //     }else{
        //         $tax = 0;
        //     }
        // }else{
        //     $tax = 0;
        // }
        return $tax;

    }

    static public function decimal($value)
    {
        return round(number_format((float)$value, 2, '.', ''));
    }
    
    static public function calculateColMd($mainmenuId,$adminsublink)
    {
        $colmd=0;
        if(isset($adminsublink) && count($adminsublink)){
            foreach ($adminsublink as $s) {
                if($s->routeName=="#" && $s->mainmenuId==$mainmenuId){
                    $colmd++;
                }
            }
        }
        if($colmd>0){
            return round(12/$colmd);
        }else{
            return '12';
        }
    }

    static public function previousLabelExist($showSubLink)
    {
        $previous=DB::table('adminsubmenu')
            ->where('mainmenuId',$showSubLink->mainmenuId)
            ->where('serialno','<',$showSubLink->serialno)
            ->get();
        if(isset($previous) && count($previous)>0){
            return '1';
        }else{
            return '0';
        }
    }

    static public function nextLabelExist($showSubLink)
    {
        $next=DB::table('adminsubmenu')
            ->where('mainmenuId',$showSubLink->mainmenuId)
            ->where('serialno','>',$showSubLink->serialno)
            ->get();
        if(isset($next) && count($next)>0){
            return '1';
        }else{
            return '0';
        }
    }   

    static public function attendanceStatusButton($status)
    {
        $Controller=new Controller();
        $button='';
        $explode=explode(',', $status);
        if(isset($explode[0])){
            foreach ($explode as $key => $value) {
                if($value!=""){
                    $button.=$Controller->statusButton($value);
                }
            }
        }

        return $button;
    }

    static public function attendanceLate($status)
    {
        $Controller=new Controller();
        $button='';
        $explode=explode(',', $status);
        if(isset($explode[0])){
            foreach ($explode as $key => $value) {
                if($value!=""){
                    // $button.=$Controller->statusButton($value);
                    // if($value=='P'){
                    //     $button = 'present';
                    // }else{
                    //     $button = 'null';
                    // }
                    if($explode[0]=="LA"){
                        $button = ' <strong> Late </strong>';
                    }else{
                        $button = '';
                    }


                }
            }
        }

        return $button;
    }

    static public function attendancePresent($status)
    {
        $Controller=new Controller();
        $button='';
        $explode=explode(',', $status);
        if(isset($explode[0])){
            foreach ($explode as $key => $value) {
                if($value!=""){
                    // $button.=$Controller->statusButton($value);
                    // if($value=='P'){
                    //     $button = 'present';
                    // }else{
                    //     $button = 'null';
                    // }
                    if($explode[0]=="P" || $explode[1]=="P"){
                        $button = '<strong>  Present </strong>';
                    }else{
                        $button = '';
                    }


                }
            }
        }

        return $button;
    }

    static public function attendanceLeave($status)
    {
        $Controller=new Controller();
        $button='';
        $explode=explode(',', $status);
        if(isset($explode[0])){
            foreach ($explode as $key => $value) {
                if($value!=""){
                    // $button.=$Controller->statusButton($value);
                    // if($value=='P'){
                    //     $button = 'present';
                    // }else{
                    //     $button = 'null';
                    // }
                    if(explode('&',$explode[0])[0]=="L" || explode('&',$explode[1])[0]=="L"){
                        $button = '<strong>  Leave </strong>';
                    }else{
                        $button = '';
                    }


                }
            }
        }

        return $button;
    }

    static public function attendanceNoexit($status)
    {
        $Controller=new Controller();
        $button='';
        $explode=explode(',', $status);
        if(isset($explode[0])){
            foreach ($explode as $key => $value) {
                if($value!=""){
                    // $button.=$Controller->statusButton($value);
                    // if($value=='P'){
                    //     $button = 'present';
                    // }else{
                    //     $button = 'null';
                    // }
                    if($explode[1]=="EXNF"){
                        $button = '<strong>  No Exit Found </strong>';
                    }else{
                        $button = '';
                    }


                }
            }
        }

        return $button;
    }
    static public function attendanceAbsent($status)
    {
        $Controller=new Controller();
        $button='';
        $explode=explode(',', $status);
        if(isset($explode[0])){
            foreach ($explode as $key => $value) {
                if($value!=""){
                    // $button.=$Controller->statusButton($value);
                    // if($value=='P'){
                    //     $button = 'present';
                    // }else{
                    //     $button = 'null';
                    // }
                    if($explode[0]=="A"){
                        $button = '<strong>  Absent </strong>';
                    }else{
                        $button = '';
                    }


                }
            }
        }

        return $button;
    }

    static public function attendanceStatusText($status)
    {
        $Controller=new Controller();
        $text='';
        $explode=explode(',', $status);
        if(isset($explode[0])){
            foreach ($explode as $key => $value) {
                if($value!=""){
                    $text.=$Controller->statusText($value);
                }
            }
        }

        return $text;
    }

    public function statusButton($type)
    {
        $Controller=new Controller();
        $data='';
        if($type=="P"){
            $data.='<a class="btn btn-success btn-xs" style="color:green;">  Present </a>';
        }elseif($type=="A"){
            $data.='<a class="btn btn-danger btn-xs" style="color:red;">  Absent </a>';
        }elseif(explode('&',$type)[0]=="OSD"){
            $data.='<a class="btn btn-info btn-xs">  OSD ('.explode('&',$type)[1].') </a>';
        }elseif(explode('&',$type)[0]=="L"){
            $data.='<a class="btn btn-danger btn-xs">  '.$Controller->getLeaveTypename(explode('&',$type)[1]).' </a>';
        }elseif($type=="ENNF"){
            $data.='<a class="btn btn-danger btn-xs">  No Entry Found </a>';
        }elseif($type=="EXNF"){
            $data.='<a class="btn btn-danger btn-xs" style="color:yellow;">  No Exit Found </a>';
        }elseif($type=="H"){
            $data.='<a class="btn btn-danger btn-xs">  Holiday </a>';
        }elseif($type=="W"){
            $data.='<a class="btn btn-danger btn-xs">  Weekend </a>';
        }elseif($type=="LA"){
            $data.='<a class="btn btn-primary btn-xs" style="color:blue;">  Late </a>';
        }
        return $data;
    }

    public function statusText($type)
    {
        $data='';
        if($type=="P"){
            $data.='<a class="btn btn-success btn-xs"> P </a>';
        }elseif($type=="A"){
            $data.='<a class="btn btn-danger btn-xs"> A </a>';
        }elseif(explode('&',$type)[0]=="OSD"){
            $data.='<a class="btn btn-info btn-xs"> OSD </a>';
        }elseif(explode('&',$type)[0]=="L"){
            $data.='<a class="btn btn-danger btn-xs"> L </a>';
        }elseif($type=="ENNF"){
            $data.='<a class="btn btn-danger btn-xs"> NENF </a>';
        }elseif($type=="EXNF"){
            $data.='<a class="btn btn-danger btn-xs"> NEXF </a>';
        }elseif($type=="H"){
            $data.='<a class="btn btn-danger btn-xs"> H </a>';
        }elseif($type=="W"){
            $data.='<a class="btn btn-danger btn-xs"> W </a>';
        }elseif($type=="LA"){
            $data.='<a class="btn btn-danger btn-xs"> LA </a>';
        }
        return $data;
    } 

    function fileInfo($file)
    {
        if(isset($file)){
            return $image = array(
                'name' => $file->getClientOriginalName(), 
                'type' => $file->getClientMimeType(), 
                'size' => $file->getClientSize(), 
                'width' => getimagesize($file)[0], 
                'height' => getimagesize($file)[1], 
                'extension' => $file->getClientOriginalExtension(), 
            );
        }else{
            return $image = array(
                'name' => '0', 
                'type' => '0', 
                'size' => '0', 
                'width' => '0', 
                'height' => '0', 
                'extension' => '0', 
            );
        }
        
    }

    function fileUpload($file,$destination,$name)
    {
        $upload=$file->move(public_path('/'.$destination), $name);
        return $upload;
    }

    public function fileMove($oldPath,$newPath)
    {
        $move = File::move($oldPath, $newPath);
        return $move;
    }

    function fileDelete($path)
    {
        if(file_exists(public_path('/'.$path))){
            $delete=unlink(public_path('/'.$path));
            return $delete;
        }
        return false;
    }

    
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
