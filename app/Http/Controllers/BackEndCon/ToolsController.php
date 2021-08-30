<?php

namespace App\Http\Controllers\BackEndCon;

use Illuminate\Http\Request;
use App\Http\Controllers\BackEndCon\Controller;
use App\Setup;
use App\Shift;
use App\TotalWorkingHours;
use App\WorkingDayAdjustment;
use DB;
use DateTime;

class ToolsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $dateRange=$this->getDateRange('2019-04-17','2019-04-20');
        // $employees=DB::table('tbl_employee')->get();
        // foreach ($employees as $key => $employee) {
        //     foreach ($dateRange as $key => $date) {
        //         $this->show($date);
        //     }
        // }
        
        return $this->show(date('Y-m-d'));
    }

    public function checkPresent($emp_machineid,$date)
    {
        $setup=Setup::first();
        $absent=$setup->absent_time;
        $late_absent=$date.' '.$absent;

        $checkPresent=DB::table('tbl_checkinout')
            ->join('tbl_device','tbl_checkinout.device','=','tbl_device.id')
            ->where('tbl_checkinout.emp_id',$emp_machineid)
            ->where('tbl_checkinout.check_time','<',$late_absent)
            // ->where('tbl_device.id','<','7')
            ->where('tbl_device.type','1')
            ->where(DB::raw("substr(tbl_checkinout.check_time, 1, 10)"), '=',$date)
            ->orderBy('tbl_checkinout.check_time','asc')
            ->get();
        if(isset($checkPresent[0])){
            return '1';
        }else{
            $checkPresent=DB::table('tbl_checkinout')
                ->join('tbl_remotedevice','tbl_checkinout.id','=','tbl_remotedevice.id')
                ->where(DB::raw('substr(tbl_checkinout.emp_id,4,100)'),$emp_machineid)
                ->where('tbl_remotedevice.id','>','6')
                ->where('tbl_remotedevice.type','1')
                ->where(DB::raw("substr(tbl_checkinout.check_time, 1, 10)"), '=',$date)
                ->orderBy('tbl_checkinout.check_time','asc')
                ->get();
            if(isset($checkPresent[0])){
                return '1';
            }else{
                return '0';
            }
        }
    }

    public function entryCount($emp_machineid,$date)
    {
        $entryCount=0;
        $card_count=DB::table('tbl_checkinout')
            ->join('tbl_device','tbl_checkinout.device','=','tbl_device.id')
            ->where('tbl_checkinout.emp_id',$emp_machineid)
            // ->where('tbl_checkinout.device','<','7')
            ->where('tbl_device.type','1')
            ->where(DB::raw("substr(tbl_checkinout.check_time, 1, 10)"), '=',$date)
            ->count();
        $entryCount+=$card_count;
        $card_count=DB::table('tbl_checkinout')
            ->join('tbl_remotedevice','tbl_checkinout.device','=','tbl_remotedevice.id')
            ->where('tbl_checkinout.emp_id',$emp_machineid)
            ->where('tbl_checkinout.device','>','6')
            ->where('tbl_remotedevice.type','1')
            ->where(DB::raw("substr(tbl_checkinout.check_time, 1, 10)"), '=',$date)
            ->count();
        $entryCount+=$card_count;
        return $entryCount;
    }

    public function firstIn($emp_machineid,$date)
    {
        $entry=DB::table('tbl_checkinout')
            ->join('tbl_device','tbl_checkinout.device','=','tbl_device.id')
            ->where('tbl_checkinout.emp_id',$emp_machineid)
            // ->where('tbl_checkinout.device','<','7')
            ->where(DB::raw("substr(tbl_checkinout.check_time, 1, 10)"), '=',$date)
            ->groupBy(DB::raw("substr(tbl_checkinout.check_time, 12, 5)"))
            ->orderBy('check_time','asc')
            ->first(['tbl_checkinout.check_time']);
        if(!isset($entry)){
            $entry=DB::table('tbl_checkinout')
                ->join('tbl_remotedevice','tbl_checkinout.device','=','tbl_remotedevice.id')
                ->where('tbl_checkinout.emp_id',$emp_machineid)
                ->where('tbl_checkinout.device','>','6')
                ->where(DB::raw("substr(tbl_checkinout.check_time, 1, 10)"), '=',$date)
                ->groupBy(DB::raw("substr(tbl_checkinout.check_time, 12, 5)"))
                ->orderBy('check_time','asc')
                ->first(['tbl_checkinout.check_time']);
        }
        if(isset($entry->check_time)){
            return substr($entry->check_time,11,8);
        }else{
            return "00:00:00";
        }
    }

    public function lastOut($emp_machineid,$date,$final_entry_time)
    {
        $entry=DB::table('tbl_checkinout')
            ->join('tbl_device','tbl_checkinout.device','=','tbl_device.id')
            ->where('tbl_checkinout.emp_id',$emp_machineid)
            // ->where('tbl_checkinout.device','<','7')
            ->where(DB::raw("substr(tbl_checkinout.check_time, 1, 10)"), '=',$date)
            ->groupBy(DB::raw("substr(tbl_checkinout.check_time, 12, 5)"))
            ->orderBy('tbl_checkinout.check_time','desc')
            ->first(['tbl_checkinout.check_time']);
        if(!isset($entry)){
            $entry=DB::table('tbl_checkinout')
                ->join('tbl_remotedevice','tbl_checkinout.device','=','tbl_remotedevice.id')
                ->where('tbl_checkinout.emp_id',$emp_machineid)
                ->where('tbl_checkinout.device','>','6')
                ->where(DB::raw("substr(tbl_checkinout.check_time, 1, 10)"), '=',$date)
                ->groupBy(DB::raw("substr(tbl_checkinout.check_time, 12, 5)"))
                ->orderBy('tbl_checkinout.check_time','desc')
                ->first(['tbl_checkinout.check_time']);
        }
        if(isset($entry->check_time)){
            if(substr($entry->check_time,11,5)!=substr($final_entry_time,0,5)){
                return substr($entry->check_time,11,8);
            }
            return "00:00:00";
        }else{
            return "00:00:00";
        }
    }

    public function exitCount($emp_machineid,$date)
    {
        $exitCount=0;
        $card_count=DB::table('tbl_checkinout')
            ->join('tbl_device','tbl_checkinout.device','=','tbl_device.id')
            ->where('tbl_checkinout.emp_id',$emp_machineid)
            // ->where('tbl_checkinout.device','<','7')
            ->where('tbl_device.type','1')
            ->where(DB::raw("substr(tbl_checkinout.check_time, 1, 10)"), '=',$date)
            ->count();
        $exitCount+=$card_count;
        $card_count=DB::table('tbl_checkinout')
            ->join('tbl_remotedevice','tbl_checkinout.device','=','tbl_remotedevice.id')
            ->where('tbl_checkinout.emp_id',$emp_machineid)
            ->where('tbl_checkinout.device','>','6')
            ->where('tbl_remotedevice.type','1')
            ->where(DB::raw("substr(tbl_checkinout.check_time, 1, 10)"), '=',$date)
            ->count();
        $exitCount+=$card_count;
        return $exitCount;
    }

    public function adjustment($emp_id,$for)
    {
        return WorkingDayAdjustment::where(['emp_id'=>$emp_id,'for'=>$for])->first();
    }

    public function attendanceStatus($emp,$date,$setup)
    {
        $emp_machineid=$emp->emp_machineid;
        $shift_info=$this->getCorrectShiftInfo($emp,$setup,$date);
        $final_entry_time=$this->firstIn($emp_machineid,$date);

        $status='';
        if($this->checkPresent($emp->emp_machineid,$date)=="1"){
           
            if($this->lateEntry($shift_info->shift_stime,$final_entry_time,15)!="00:00:00")
            {
                $status.='LA,';
            }
            $status.='P,';
            if($setup->attendance_type=='1'){
                if($this->entryCount($emp->emp_machineid,$date)=="0"){
                    $status.='ENNF,';
                }

                if($this->exitCount($emp->emp_machineid,$date)=="0"){
                    $status.='EXNF,';
                }
            }else{
                if($this->firstIn($emp->emp_machineid,$date)=="00:00:00"){
                    $status.='ENNF,';
                }

                if($this->lastOut($emp->emp_machineid,$date,$this->firstIn($emp->emp_machineid,$date))=="00:00:00"){
                    $status.='EXNF,';
                }
            }
        }

        if($this->checkHoliday($date)=="1"){
            $status.='H,';
        }elseif($this->checkWeekend($emp,$date)=="1"){
            $status.='W,';
        }else{
            $checkLeave=$this->checkLeave($emp->emp_id,$date,$date);
            if(isset($checkLeave)){
                $status.='L&'.$this->checkLeave($emp->emp_id,$date,$date)->leave_typeid.',';
            }else{
                if($this->checkPresent($emp->emp_machineid,$date)=="0"){
                    $status.='A,';
                }
            }
        }

        if($this->totalOSDDuration($emp->emp_id,$date,$date)>0){
            $status.='OSD&'.$this->totalOSDDuration($emp->emp_id,$date,$date).' ';
        }

        if($this->adjustment($emp->emp_id,$date)){
            $status.='ADJ ';
        }

        return $status;
    }

    public function attendanceShortStatus($emp,$date,$setup)
    {
        $status='';
        if($this->checkPresent($emp->emp_machineid,$date)=="1"){
            $status.='P,';
            if($setup->attendance_type=='1'){
                if($this->entryCount($emp->emp_machineid,$date)=="0"){
                    $status.='ENNF,';
                }

                if($this->exitCount($emp->emp_machineid,$date)=="0"){
                    $status.='EXNF,';
                }
            }else{
                if($this->firstIn($emp->emp_machineid,$date)=="00:00:00"){
                    $status.='ENNF,';
                }

                if($this->lastOut($emp->emp_machineid,$date,$this->firstIn($emp->emp_machineid,$date))=="00:00:00"){
                    $status.='EXNF,';
                }
            }
        }else{
            if($this->checkHoliday($date)=="1"){
                $status.='H,';
            }elseif($this->checkWeekend($emp,$date)=="1"){
                $status.='W,';
            }else{
                $checkLeave=$this->checkLeave($emp->emp_id,$date,$date);
                if(isset($checkLeave)){
                    $status.='L&'.$this->checkLeave($emp->emp_id,$date,$date)->leave_typeid.',';
                }else{
                    $status.='A,';
                }
            }
        }

        if($this->totalOSDDuration($emp->emp_id,$date,$date)>0){
            $status.=' OSD&'.$this->totalOSDDuration($emp->emp_id,$date,$date).' ';
        }

        if($this->adjustment($emp->emp_id,$date)){
            $status.='ADJ ';
        }
        
        return $status;
    }

    public function checkHoliday($date)
    {
        $holiday=DB::table('tbl_holiday')->where(['holiday_date'=>$date,'holiday_status'=>'1'])->first();
        if(isset($holiday)){
            return '1';
        }else{
            return '0';
        }
    }

    public function checkWeekend($emp,$date)
    {
        if($this->dayName($emp->emp_wknd)==date("D",strtotime($date))){
            return '1';
        }else{
            return '0';
        }
    }

    public function statusButton($type)
    {
        $data='';
        if($type=="P"){
            $data.='<a class="btn btn-success btn-xs">  Present </a>';
        }elseif($type=="A"){
            $data.='<a class="btn btn-danger btn-xs">  Absent </a>';
        }elseif(explode('&',$type)[0]=="OSD"){
            $data.='<a class="btn btn-info btn-xs">  OSD ('.explode('&',$type)[1].') </a>';
        }elseif(explode('&',$type)[0]=="L"){
            $data.='<a class="btn btn-danger btn-xs">  '.$this->getLeaveTypename(explode('&',$type)[1]).' </a>';
        }elseif($type=="ENNF"){
            $data.='<a class="btn btn-danger btn-xs">  No Entry Found </a>';
        }elseif($type=="EXNF"){
            $data.='<a class="btn btn-danger btn-xs">  No Exit Found </a>';
        }elseif($type=="H"){
            $data.='<a class="btn btn-danger btn-xs">  Holiday </a>';
        }elseif($type=="W"){
            $data.='<a class="btn btn-danger btn-xs">  Weekend </a>';
        }elseif($type=="ADJ"){
            $data.='<a class="btn btn-primary btn-xs">  Adjustment </a>';
        }elseif($type=="LA"){
            $data.='<a class="btn btn-primary btn-xs">  Late, </a>';
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
        }elseif($type=="ADJ"){
            $data.='<a class="btn btn-danger btn-xs"> ADJ </a>';
        }elseif($type=="LA"){
            $data.='<a class="btn btn-danger btn-xs"> LA </a>';
        }
        return $data;
    }

    public function totalOSDDuration($osd_done_by,$start_date,$end_date)
    {
        $total_osd=date("H:i:s",strtotime('00:00:00'));

        $totalOSDDuration=DB::table('tbl_osdattendance')
            ->where('tbl_osdattendance.osd_done_by',$osd_done_by)
            ->where('tbl_osdattendance.osd_date','>=',$start_date)
            ->where('tbl_osdattendance.osd_date','<=',$end_date)
            ->where('tbl_osdattendance.osd_status','1')
            ->get();

        if(isset($totalOSDDuration)){
            foreach ($totalOSDDuration as $osd) {
                $osd_duration = strtotime($osd->osd_duration)-strtotime("00:00:00");
                $total_osd = date("H:i:s",strtotime($total_osd)+$osd_duration);
            }
        }
        return $total_osd;
    }

    public function checkLeave($emp_id,$start_date,$end_date)
    {
        $checkLeave=DB::table('tbl_leaveapplication')
            ->where('leave_empid',$emp_id)
            ->where(DB::raw('substr(leave_start_date,1,10)'),'<=',$start_date)
            ->where(DB::raw('substr(leave_end_date,1,10)'),'>=',$start_date)
            ->where('leave_status','1')
            ->first();
        return $checkLeave;
    }

    public function lateEntry($shift_stime,$entry_time,$grace)
    {
        $late=0;
        if(strtotime($shift_stime)<strtotime($entry_time)){
            $late=$this->timeToHours($entry_time)-$this->timeToHours($shift_stime);
            if($grace>0){
                if($late<=($grace/60)){
                    $late=0;
                }
            }
        }
        return $this->hoursToTime($late);
    }

    public function earlyExit($shift_etime,$exit_time)
    {
        $early=0;
        if(strtotime($shift_etime)>strtotime($exit_time)){
            $early=$this->timeToHours($shift_etime)-$this->timeToHours($exit_time);
        }
        return $this->hoursToTime($early);
    }

    public function otHour($shift_info,$twh)
    {
        $ot_hour='00:00:00';
        if($shift_info->shift_type=="1"){
            if(substr($twh,0,2)>='7'){
                $to_time = new DateTime($twh);
                $total_from_time = new DateTime('08:00:00');
                $total_duration = $to_time->diff($total_from_time);
                if($total_duration->h>0){
                    $ot_hour=$total_duration->format('%H:%I:%S');
                }else{
                    if($total_duration->i>=30){
                        $ot_hour=$total_duration->format('%H:%I:%S');
                    }
                }
            }
        }else if($shift_info->shift_type=="2"){
            if(substr($twh,0,2)>='8'){
                $to_time = new DateTime($twh);
                $total_from_time = new DateTime('09:00:00');
                $total_duration = $to_time->diff($total_from_time);
                if($total_duration->h>0){
                    $ot_hour=$total_duration->format('%H:%I:%S');
                }else{
                    if($total_duration->i>=30){
                        $ot_hour=$total_duration->format('%H:%I:%S');
                    }
                }
            }
        }

        return $ot_hour;
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
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function getCorrectShiftInfo($emp,$setup,$date)
    {
        // $sh_id=$emp->emp_shiftid;
        if($setup->attendance_type=="1"){
            $entry_time=DB::table('tbl_checkinout')
                ->join('tbl_device','tbl_checkinout.device','=','tbl_device.id')
                ->where('tbl_checkinout.emp_id',$emp->emp_machineid)
                // ->where('tbl_checkinout.device','<','7')
                ->where('tbl_device.type','1')
                ->where(DB::raw("substr(tbl_checkinout.check_time, 1, 10)"), '=',$date)
                ->orderBy('tbl_checkinout.check_time','ASC')
                ->groupBy(DB::raw("substr(tbl_checkinout.check_time, 12, 5)"))
                ->first(['tbl_checkinout.*']);
            if(!isset($entry_time->check_time)){
                $entry_time=DB::table('tbl_checkinout')
                    ->join('tbl_remotedevice','tbl_checkinout.device','=','tbl_remotedevice.id')
                    ->where('tbl_checkinout.emp_id',$emp->emp_machineid)
                    ->where('tbl_checkinout.device','>','6')
                    ->where('tbl_remotedevice.type','1')
                    ->where(DB::raw("substr(tbl_checkinout.check_time, 1, 10)"), '=',$date)
                    ->orderBy('tbl_checkinout.check_time','ASC')
                    ->groupBy(DB::raw("substr(tbl_checkinout.check_time, 12, 5)"))
                    ->first(['tbl_checkinout.*']);
            }
            if(isset($entry_time->check_time)){
                $check_time=$entry_time->check_time;
                $entry_time=substr($check_time,11,8);
                $entry_date=substr($check_time,0,10);
            }else{
                $check_time=false;
            }
        }else{
            $check_time=$this->firstIn($emp->emp_machineid,$date);
            $entry_time=substr($check_time,11,8);
            $entry_date=substr($check_time,0,10);
        }

        if($check_time){
            $hours=substr($entry_time,0,2);
            $minutes=substr($entry_time,2,2);
            if((int)($hours)>=5 && (int)($hours)<=6){
                if((int)($hours)==5 && (int)($minutes)>=45){
                    $s=1;
                }elseif((int)($hours)==6 && (int)($minutes)<=15){
                    $s=1;
                }else{
                    $s=1;
                }
            }elseif((int)($hours)>=13 && (int)($hours)<=14){
                if((int)($hours)==13 && (int)($minutes)>=45){
                    $s=2;
                }elseif((int)($hours)==14 && (int)($minutes)<=15){
                    $s=2;
                }else{
                    $s=2;
                }
            }elseif((int)($hours)>=21 && (int)($hours)<=22){
                if((int)($hours)==21 && (int)($minutes)>=45){
                    $s=3;
                }elseif((int)($hours)==22 && (int)($minutes)<=15){
                    $s=3;
                }else{
                    $s=3;
                }
            }else{
                $s=1;
            }
        }else{
            $s=1;
        }

        // return Shift::where('shift_id',22)->first();
        return Shift::where('shift_type',$emp->emp_workhr)->first();
        // return Shift::join('tbl_employee', 'tbl_employee.shift_id', '=', 'tbl_shift.shift_id')
        // ->where('tbl_shift.shift_id', $emp->shift_id)
        // ->first();
    }

    public function totalWorkingHour($emp,$setup,$date)
    {
        $emp_machineid=$emp->emp_machineid;
        $shift_info=$this->getCorrectShiftInfo($emp,$setup,$date);
        $card_count=$this->entryCount($emp_machineid,$date);
        $twh=date("H:i:s",strtotime('00:00:00'));
        $late='';
        $early=''; 

        if($setup->attendance_type=="1"){

            for ($i=0; $i < $card_count; $i++) {

                if($i=="0"){
                    //entry time calculation (start)//
                    $entry_time=DB::table('tbl_checkinout')
                        ->join('tbl_device','tbl_checkinout.device','=','tbl_device.id')
                        ->where('tbl_checkinout.emp_id',$emp_machineid)
                        // ->where('tbl_checkinout.device','<','7')
                        ->where('tbl_device.type','1')
                        ->where(DB::raw("substr(tbl_checkinout.check_time, 1, 10)"), '=',$date)
                        ->orderBy('tbl_checkinout.check_time','ASC')
                        ->groupBy(DB::raw("substr(tbl_checkinout.check_time, 12, 5)"))
                        ->first(['tbl_checkinout.*']);
                    if(!isset($entry_time->check_time)){
                        $entry_time=DB::table('tbl_checkinout')
                            ->join('tbl_remotedevice','tbl_checkinout.device','=','tbl_remotedevice.id')
                            ->where('tbl_checkinout.emp_id',$emp_machineid)
                            ->where('tbl_checkinout.device','>','6')
                            ->where('tbl_remotedevice.type','1')
                            ->where(DB::raw("substr(tbl_checkinout.check_time, 1, 10)"), '=',$date)
                            ->orderBy('tbl_checkinout.check_time','ASC')
                            ->groupBy(DB::raw("substr(tbl_checkinout.check_time, 12, 5)"))
                            ->first(['tbl_checkinout.*']);
                    }

                    $grace_time=Setup::first();
                    $grace=$grace_time->grace_time;
                    if(isset($entry_time->check_time)){
                        $final_entry_time=substr($entry_time->check_time,11,8);
                        $calculate_entry_time=substr($entry_time->check_time,11,8);
                        $calculate_entry_date=substr($entry_time->check_time,0,10);
                        $late=$this->lateEntry($shift_info->shift_stime,$final_entry_time,$grace);
                        $twh=date("H:i:s",strtotime('00:00:00'));
                    }
                    //entry time calculation (end)//
                }else{
                    //entry time calculation (start)//
                    if(isset($exit_time->check_time)){
                        $entry_time=DB::table('tbl_checkinout')
                            ->join('tbl_device','tbl_checkinout.device','=','tbl_device.id')
                            ->where('tbl_checkinout.emp_id',$emp_machineid)
                            // ->where('tbl_checkinout.device','<','7')
                            ->where('tbl_checkinout.check_time','>',$exit_time->check_time)
                            ->where('tbl_device.type','1')
                            ->where(DB::raw("substr(tbl_checkinout.check_time, 1, 10)"), '=',$date)
                            ->orderBy('tbl_checkinout.check_time','ASC')
                            ->groupBy(DB::raw("substr(tbl_checkinout.check_time, 12, 5)"))
                            ->first(['tbl_checkinout.*']);
                    if(!isset($entry_time->check_time)){
                        $entry_time=DB::table('tbl_checkinout')
                            ->join('tbl_remotedevice','tbl_checkinout.device','=','tbl_remotedevice.id')
                            ->where('tbl_checkinout.emp_id',$emp_machineid)
                            ->where('tbl_checkinout.device','>','6')
                            ->where('tbl_checkinout.check_time','>',$exit_time->check_time)
                            ->where('tbl_remotedevice.type','1')
                            ->where(DB::raw("substr(tbl_checkinout.check_time, 1, 10)"), '=',$date)
                            ->orderBy('tbl_checkinout.check_time','ASC')
                            ->groupBy(DB::raw("substr(tbl_checkinout.check_time, 12, 5)"))
                            ->first(['tbl_checkinout.*']);
                    }
                        if(isset($entry_time->check_time)){
                            $calculate_entry_time=substr($entry_time->check_time,11,8);
                            $calculate_entry_date=substr($entry_time->check_time,0,10);
                        }
                    }
                    
                    //entry time calculation (end)//
                }

                //exit time calculation (start)//
                if(isset($entry_time->check_time)){
                    $exit_time=DB::table('tbl_checkinout')
                            ->join('tbl_device','tbl_checkinout.device','=','tbl_device.id')
                            ->where('tbl_checkinout.emp_id',$emp_machineid)
                            // ->where('tbl_checkinout.device','<','7')
                            ->where('tbl_checkinout.check_time','>',$entry_time->check_time)
                            ->where('tbl_device.type','0')
                            ->where(DB::raw("substr(tbl_checkinout.check_time, 1, 10)"), '=',$date)
                            ->orderBy('tbl_checkinout.check_time','ASC')
                            ->groupBy(DB::raw("substr(tbl_checkinout.check_time, 12, 5)"))
                            ->first(['tbl_checkinout.*']);
                    if(!isset($exit_time->check_time)){
                        $exit_time=DB::table('tbl_checkinout')
                            ->join('tbl_remotedevice','tbl_checkinout.device','=','tbl_remotedevice.id')
                            ->where('tbl_checkinout.emp_id',$emp_machineid)
                            ->where('tbl_checkinout.device','>','6')
                            ->where('tbl_checkinout.check_time','>',$entry_time->check_time)
                            ->where('tbl_remotedevice.type','0')
                            ->where(DB::raw("substr(tbl_checkinout.check_time, 1, 10)"), '=',$date)
                            ->orderBy('tbl_checkinout.check_time','ASC')
                            ->groupBy(DB::raw("substr(tbl_checkinout.check_time, 12, 5)"))
                            ->first(['tbl_checkinout.*']);
                    }
                    if(!isset($exit_time->check_time)){
                        $exit_time=DB::table('tbl_checkinout')
                            ->join('tbl_device','tbl_checkinout.device','=','tbl_device.id')
                            ->where('tbl_checkinout.emp_id',$emp_machineid)
                            // ->where('tbl_checkinout.device','<','7')
                            ->where('tbl_checkinout.check_time','>',$entry_time->check_time)
                            ->where('tbl_device.type','0')
                            ->where(DB::raw("substr(tbl_checkinout.check_time, 1, 10)"), '=',date('Y-m-d',strtotime($date.' +1 days')))
                            ->orderBy('tbl_checkinout.check_time','ASC')
                            ->groupBy(DB::raw("substr(tbl_checkinout.check_time, 12, 5)"))
                            ->first(['tbl_checkinout.*','tbl_device.type']);
                        if(!isset($exit_time->CardTime)){
                            $exit_time=DB::table('tbl_checkinout')
                            ->join('tbl_remotedevice','tbl_checkinout.device','=','tbl_remotedevice.id')
                            ->where('tbl_checkinout.emp_id',$emp_machineid)
                            ->where('tbl_checkinout.device','>','6')
                            ->where('tbl_checkinout.check_time','>',$entry_time->check_time)
                            ->where('tbl_remotedevice.type','0')
                            ->where(DB::raw("substr(tbl_checkinout.check_time, 1, 10)"), '=',date('Y-m-d',strtotime($date.' +1 days')))
                            ->orderBy('tbl_checkinout.check_time','ASC')
                            ->groupBy(DB::raw("substr(tbl_checkinout.check_time, 12, 5)"))
                            ->first(['tbl_checkinout.*','tbl_remotedevice.type']);
                        }
                        if(isset($exit_time->check_time)){
                            if($exit_time->type=="1"){
                                unset($exit_time);
                            }
                        }
                    }

                    if(isset($exit_time->check_time)){
                        $calculate_exit_time=substr($exit_time->check_time,11,8);
                        $final_exit_time=substr($exit_time->check_time,11,8);
                        $calculate_exit_date=substr($exit_time->check_time,0,10);

                        $to_time = new DateTime($calculate_exit_time);
                        $from_time = new DateTime($calculate_entry_time);
                        if(strtotime($calculate_exit_date)>strtotime($calculate_entry_date)){
                            $midnight_period=date("H:i:s",strtotime('00:00:00'));
                            $afternight_period=date("H:i:s",strtotime('00:00:00'));

                            $midnight= new DateTime('23:59:59');
                            $midnight_duration = $midnight->diff($from_time);
                            $midnight_period=$midnight_duration->format('%H:%I:%S');

                            $afternight= new DateTime('00:00:01');
                            $afternight_duration = $to_time->diff($afternight);
                            $afternight_period=$afternight_duration->format('%H:%I:%S');

                            $afternight_period=strtotime($afternight_period)-strtotime("00:00:00");
                            $period=date("H:i:s",strtotime($midnight_period)+$afternight_period);
                        }else{
                            $duration = $to_time->diff($from_time);
                            $period=$duration->format('%H:%I:%S');
                        }
                        
                        $time2 = strtotime($period)-strtotime("00:00:00");
                        $twh = date("H:i:s",strtotime($twh)+$time2);
                    }
                }
                
                //exit time calculation (end)//
            }
        }else{

            $final_entry_time=$this->firstIn($emp_machineid,$date);
            $calculate_exit_time=$this->lastOut($emp_machineid,$date,$final_entry_time);

            $grace_time=Setup::first();
            $grace=$grace_time->grace_time;
            if(isset($shift_info->shift_stime)){
                $late=$this->lateEntry($shift_info->shift_stime,$final_entry_time,$grace);
            }
            if($this->timeToHours($final_entry_time)<=0 || $this->timeToHours($calculate_exit_time)<=0){
                $twh="00:00:00";
            }else{
                $exit_time = new DateTime($calculate_exit_time.' '.$date);
                $entry_time = new DateTime($final_entry_time.' '.$date);
                $difference=$exit_time->diff($entry_time);
                if($difference->h<10){
                    $hours='0'.$difference->h;
                }else{
                    $hours=$difference->h;
                }

                if($difference->i<10){
                    $minutes='0'.$difference->i;
                }else{
                    $minutes=$difference->i;
                }

                if($difference->s<10){
                    $seconds='0'.$difference->s;
                }else{
                    $seconds=$difference->s;
                }

                $twh=$hours.':'.$minutes.':'.$seconds;
            }
        }

        if(!isset($final_entry_time) || $final_entry_time=="00:00:00"){
            $final_entry_time=$this->attendanceShortStatus($emp,$date,$setup);
        }

        if(!isset($calculate_exit_time) || $calculate_exit_time=="00:00:00"){
            $calculate_exit_time=$this->attendanceShortStatus($emp,$date,$setup);
            $twh='00:00:00';
        }
        $final_exit_time=$calculate_exit_time;

        if(isset($shift_info->shift_etime)){
            $early=$this->earlyExit($shift_info->shift_etime,$final_exit_time);
        }

        $osd = $this->totalOSDDuration($emp->emp_id,$date,$date);
        $total_osd = strtotime($this->totalOSDDuration($emp->emp_id,$date,$date))-strtotime('00:00:00');
        $twh = date("H:i:s",strtotime($twh)+$total_osd);
        
        $night=0;    
        // if(strtotime($final_entry_time)>=strtotime('20:00:00') && strtotime($twh)>=strtotime('04:00:00'))
        // {
        //     $night++;
        // }
        if(isset($shift_info->shift_id) && $shift_info->shift_id=="3"){
            $night++;
        }

        $data[$emp->emp_id] = array(
            'date' => $date, 
            'shift_id' => $shift_info->shift_id,
            // 'shift_id' => 22,
            'entry_time' => $final_entry_time,  
            'exit_time' => $final_exit_time, 
            'late_entry' => $late, 
            'early_exit' => $early,
            'twh' => $twh, 
            'total_osd' => $osd, 
            'ot' => $this->otHour($shift_info,$twh), 
            'night' => $night,
            'present' => $this->checkPresent($emp->emp_machineid,$date,$setup),
            'status' => $this->attendanceStatus($emp,$date,$setup),
        );

        return $data[$emp->emp_id];
    }

    public function show($date)
    {
        if(count(explode('&',$date))>1){
            $dateRange=$this->getDateRange(explode('&',$date)[0],explode('&',$date)[1]);
            foreach ($dateRange as $key => $date) {
                $data = array();
                $employees=DB::table('tbl_employee')
                    ->where('tbl_employee.emp_status', 1)
                    ->where('tbl_employee.emp_id','!=',116)
                    ->whereNotNull('tbl_employee.emp_machineid')
                    ->groupBy('tbl_employee.emp_machineid')
                    ->get([
                        'tbl_employee.emp_id',
                        'tbl_employee.emp_name',
                        'tbl_employee.emp_empid',
                        'tbl_employee.emp_machineid',
                        'tbl_employee.emp_wknd',
                        'tbl_employee.emp_workhr',
                        'tbl_employee.emp_shiftid'
                    ]);
                $setup=Setup::find('1');
                foreach ($employees as $key => $emp) {
                    $data[$emp->emp_id]=$this->totalWorkingHour($emp,$setup,$date);
                }

                if(count($data)){
                    foreach ($data as $key => $row) {
                        $row["emp_id"]=$key;
                        TotalWorkingHours::where([
                            'date' => $row["date"],
                            'emp_id' => $row["emp_id"],
                        ])->delete();

                        TotalWorkingHours::insert($row);
                    }
                }
            }
        }else{
            $data = array();
            $employees=DB::table('tbl_employee')
                ->groupBy('tbl_employee.emp_machineid')
                ->get([
                    'tbl_employee.emp_id',
                    'tbl_employee.emp_name',
                    'tbl_employee.emp_empid',
                    'tbl_employee.emp_machineid',
                    'tbl_employee.emp_wknd',
                    'tbl_employee.emp_workhr'
                ]);
            $setup=Setup::find('1');
            foreach ($employees as $key => $emp) {
                $data[$emp->emp_id]=$this->totalWorkingHour($emp,$setup,$date);
            }

            if(count($data)){
                foreach ($data as $key => $row) {
                    $row["emp_id"]=$key;
                    TotalWorkingHours::where([
                        'date' => $row["date"],
                        'emp_id' => $row["emp_id"],
                    ])->delete();

                    TotalWorkingHours::insert($row);
                }
            } 
        }
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
