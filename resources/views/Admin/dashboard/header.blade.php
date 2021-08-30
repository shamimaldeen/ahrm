@php
use App\Http\Controllers\BackEndCon\Controller;
use App\ProjectDetails;
$projectDetails=ProjectDetails::find('1');
@endphp

<div class="row">
@include('error.msg')

@if(isset($notification->id))
<div class="col-md-12">
  <div class="col-md-6 col-md-offset-3">
    <div class="panel panel-primary">
      <div class="panel-heading text-center">
        <strong>Welcome! Your Probation Period Has been Completed!</strong>
      </div>
      <div class="panel-body">
        Welcome <strong>{{$notification->employee->emp_name}} ({{$notification->employee->emp_empid}})</strong>, Your probation period has been complited.
        <br>
        <br>
        Note : Your joinning date : <strong>{{date('F j,Y',strtotime($notification->employee->emp_joindate))}}</strong> & probation period complecation date : <strong>{{date('F j,Y',strtotime($notification->employee->emp_confirmdate))}}</strong>
        <hr>
        Approved by <strong>{{$notification->approver->emp_name}} ({{$notification->approver->emp_empid}})</strong> at <strong>{{date("F j, Y, g:i a",strtotime($notification->created_at))}}</strong>
        <br>
        <br>
        He/She said,
        <p>{{$notification->text}}</p>
      </div>
      <div class="panel-footer text-center">
        <a class="btn btn-default btn-xs" href="{{url('probation-period-notifications')}}/{{$id->suser_empid}}/edit">Mark As read</a>
      </div>
    </div>
  </div>
</div>
@endif

@if($id->suser_level=="1" or $id->suser_level=="4")

<div class="col-md-10 col-md-offset-1" style="text-align: center">
    <h3 style="margin: 0px"><strong>{{$projectDetails->project_name}} :: {{$projectDetails->project_company}}</strong></h3>
</div>

@elseif($id->suser_level=="2" or $id->suser_level=="3" or $id->suser_level=="5" or $id->suser_level=="6")

<div class="col-md-10 col-md-offset-1" style="text-align: center">
  <img src="{{URL::to('/')}}/public/logo.jpg" alt="logo" style="height: 125px" class="logo-default" />
</div>

<div class="col-md-10 col-md-offset-1" style="text-align: center">
    <h3><strong>{{$projectDetails->project_name}} :: {{$projectDetails->project_company}}</strong></h3>
</div>

@endif
<div class="col-md-12">
    <div class="form-group">
       <div class="col-md-6 col-md-offset-3" style="padding:5px;text-align: center">

        @if($id->suser_emptype=="0")

          @if(Controller::checkClock()=="1")
            <input type="submit" class="btn btn-md btn-success" onclick="ClockIn();" disabled="disabled" id="ClockIn" value="Clocked In">   
            <input type="submit" class="btn btn-md btn-danger" onclick="ClockOut();" id="ClockOut" value="Clock Out">
          @elseif(Controller::checkClock()=="0")
            <input type="submit" class="btn btn-md btn-success" onclick="ClockIn();" id="ClockIn" value="Clock In">   
            <input type="submit" class="btn btn-md btn-danger" onclick="ClockOut();" disabled="disabled" id="ClockOut" value="Clocked Out">
          @elseif(Controller::checkClock()=="null")
            <input type="submit" class="btn btn-md btn-success" onclick="ClockIn();" id="ClockIn" value="Clock In">   
            <input type="submit" class="btn btn-md btn-danger" onclick="ClockOut();" disabled="disabled" id="ClockOut" value="Clock Out">
          @endif

        @endif

       </div>
    </div>
</div>

</div>