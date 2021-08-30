@extends('Admin.index')
@section('body')

@php
use App\Http\Controllers\BackEndCon\Controller;
use Illuminate\Support\Facades\Route;
$route=Route::getFacadeRoot()->current()->uri();
@endphp
<style>
  .btn-style{
    padding: 40px 5px 40px 5px;
    font-size: 14px;
  }
  .form-group{
    padding: 10px 0px;
  }
  .mb-20{
    margin-bottom: 20px
  }
</style>
<script src="{{URL::to('/')}}/public/assets/global/plugins/jquery.min.js"></script>

<div class="row">
    <div class="col-md-12">
       @include('error.msg')
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class="icon-settings font-dark"></i>
                    <span class="caption-subject bold uppercase">Report </span>
                </div>
            </div>
            <div class="portlet-body">
            <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{URL::to('')}}" name="basic_validate" id="data_form">
            {{ csrf_field() }}

              <div class="row mb-20">
                @if(Controller::checkAppModulePriority($route,'employee-details')=="1")
                <div class="col-md-3">
                  <a class="btn btn-lg btn-primary btn-circle btn-block btn-style" href="{{URL::to('report/employee-details/view')}}">Employee Details</a>
                </div>
                @endif
                @if(Controller::checkAppModulePriority($route,'employee-insurance-data')=="1")
                <div class="col-md-3">
                  <a class="btn btn-lg btn-primary btn-circle btn-block btn-style" href="{{URL::to('report/employee-insurance-data/view')}}">Insurance Data</a>
                </div>
                @endif
                @if(Controller::checkAppModulePriority($route,'shift')=="1")
                <div class="col-md-3">
                  <a class="btn btn-lg btn-primary btn-circle btn-block btn-style" href="{{URL::to('report/shift/view')}}">Shift</a>
                </div>
                @endif
                @if(Controller::checkAppModulePriority($route,'exited-employee-archive')=="1")
                <div class="col-md-3">
                  <a class="btn btn-lg btn-primary btn-circle btn-block btn-style" href="{{URL::to('report/exited-employee-archive/view')}}">Exited Employee Archive </a>
                </div>
                @endif
              </div>
              <div class="row mb-20">
                @if(Controller::checkAppModulePriority($route,'attendance')=="1")
                <div class="col-md-3">
                  <a class="btn btn-lg btn-primary btn-circle btn-block btn-style" href="{{URL::to('report/attendance/view')}}">Attendance</a>
                </div>
                @endif
                @if(Controller::checkAppModulePriority($route,'totalWorkingHour')=="1")
                <div class="col-md-3">
                  <a class="btn btn-lg btn-primary btn-circle btn-block btn-style" href="{{URL::to('report/totalWorkingHour/view')}}">Total Working Hour</a>
                </div>
                @endif
                @if(Controller::checkAppModulePriority($route,'delay')=="1")
                <div class="col-md-3">
                  <a class="btn btn-lg btn-primary btn-circle btn-block btn-style" href="{{URL::to('report/delay/view')}}">Delay Entry</a>
                </div>
                @endif
                @if(Controller::checkAppModulePriority($route,'late')=="1")
                <div class="col-md-3">
                  <a class="btn btn-lg btn-primary btn-circle btn-block btn-style" href="{{URL::to('report/late/view')}}">Late Exit</a>
                </div>
                @endif
              </div>

              <div class="row mb-20">
                @if(Controller::checkAppModulePriority($route,'no-show-no-entry')=="1")
                <div class="col-md-3">
                  <a class="btn btn-lg btn-primary btn-circle btn-block btn-style" href="{{URL::to('report/no-show-no-entry/view')}}">No Show/No Entry</a>
                </div>
                @endif
                @if(Controller::checkAppModulePriority($route,'ot')=="1")
                <div class="col-md-3">
                  <a class="btn btn-lg btn-primary btn-circle btn-block btn-style" href="{{URL::to('report/ot/view')}}">OT</a>
                </div>
                @endif
                @if(Controller::checkAppModulePriority($route,'nc-ot')=="1")
                <!-- <div class="col-md-3">
                  <a class="btn btn-lg btn-primary btn-circle btn-block btn-style" href="{{URL::to('report/nc-ot/view')}}">NC OT</a>
                </div> -->
                @endif
                @if(Controller::checkAppModulePriority($route,'food')=="1")
                <!-- <div class="col-md-3">
                  <a class="btn btn-lg btn-primary btn-circle btn-block btn-style" href="{{URL::to('report/food/view')}}">Food</a>
                </div> -->
                @endif
                @if(Controller::checkAppModulePriority($route,'leave-application')=="1")
                <div class="col-md-3">
                  <a class="btn btn-lg btn-primary btn-circle btn-block btn-style" href="{{URL::to('report/leave-application/view')}}">Leave Application</a>
                </div>
                @endif
                @if(Controller::checkAppModulePriority($route,'hr-tickets')=="1")
                <!-- <div class="col-md-3">
                  <a class="btn btn-lg btn-primary btn-circle btn-block btn-style" href="{{URL::to('report/hr-tickets/view')}}">HR Tickets</a>
                </div> -->
                @endif
                @if(Controller::checkAppModulePriority($route,'leave-taken-status')=="1")
                <div class="col-md-3">
                  <a class="btn btn-lg btn-primary btn-circle btn-block btn-style" href="{{URL::to('report/leave-taken-status/view')}}">Leave Taken Status</a>
                </div>
                @endif
              </div>

              <div class="row mb-20">
                
                @if(Controller::checkAppModulePriority($route,'annual-leave')=="1")
                <div class="col-md-3">
                  <a class="btn btn-lg btn-primary btn-circle btn-block btn-style" href="{{URL::to('report/annual-leave/view')}}">Annual Leave</a>
                </div>
                @endif
                @if(Controller::checkAppModulePriority($route,'short-leave')=="1")
                <div class="col-md-3">
                  <a class="btn btn-lg btn-primary btn-circle btn-block btn-style" href="{{URL::to('report/short-leave/view')}}">Short Leave</a>
                </div>
                @endif
                @if(Controller::checkAppModulePriority($route,'casual-leave')=="1")
                <div class="col-md-3">
                  <a class="btn btn-lg btn-primary btn-circle btn-block btn-style" href="{{URL::to('report/casual-leave/view')}}">Casual Leave</a>
                </div>
                @endif
                @if(Controller::checkAppModulePriority($route,'sick-leave')=="1")
                <div class="col-md-3">
                  <a class="btn btn-lg btn-primary btn-circle btn-block btn-style" href="{{URL::to('report/sick-leave/view')}}">Sick Leave</a>
                </div>
                @endif
              </div>

              <div class="row mb-20">
                @if(Controller::checkAppModulePriority($route,'compensatory-leave')=="1")
                <div class="col-md-3 col-md-offset-3">
                  <a class="btn btn-lg btn-primary btn-circle btn-block btn-style" href="{{URL::to('report/compensatory-leave/view')}}">Compensatory Leave</a>
                </div>
                @endif
               
                @if(Controller::checkAppModulePriority($route,'compensatory-leave')=="1")
                <div class="col-md-3">
                  <a class="btn btn-lg btn-primary btn-circle btn-block btn-style" href="{{ route('tax-report-general') }}">Tax Report</a>
                </div>
                @endif
               

                
              </div>

            </form>
            </div>
          </div>
        </div>
      </div>
    </div>
@endsection