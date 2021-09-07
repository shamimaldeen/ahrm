@extends('Admin.index')
@section('body')

@php
use App\Http\Controllers\BackEndCon\Controller;
use Illuminate\Support\Facades\Route;
$route=Route::getFacadeRoot()->current()->uri();
@endphp
<script src="{{URL::to('/')}}/public/assets/global/plugins/jquery.min.js" type="text/javascript"></script>

@if($id->suser_level=="1" or $id->suser_level=="3")
<!-- filter start -->
<div class="row" style='padding: 30px; background-color: #f7f7f7; margin: 0px;'>

  <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{URL::to('')}}" name="basic_validate" id="basic_validate" novalidate="novalidate">
      {{ csrf_field() }}

  <div class="form-group">

    <label class="col-md-1 control-label" style="text-align: right;font-size:12px;padding: 5px 0px">
      Department:
    </label>

    <div class="col-md-3">
        <select name="emp_depart_id" id="filter_emp_depart_id" class="form-control chosen" required onchange="getFilterSubDepartment();">
          <option value="0">Select</option>
            @if(isset($department) && count($department)>0)
              @foreach ($department as $depart)
                @if(isset($emp_depart_id) && $emp_depart_id==$depart->depart_id)
                  <option value="{{$depart->depart_id}}" selected="selected">{{$depart->depart_name}}</option>
                @else
                  <option value="{{$depart->depart_id}}">{{$depart->depart_name}}</option>
                @endif
              @endforeach
            @endif
        </select>
    </div>

    <label class="col-md-1 control-label" style="text-align: right;font-size:12px;padding: 5px 0px">
      Sub-Department:
    </label>

    <div class="col-md-3">
      <select name="emp_sdepart_id" id="filter_emp_sdepart_id" class="form-control chosen" required>
      </select>
    </div>



    <label class="col-md-1 control-label" style="text-align: right;font-size:12px;padding: 5px 0px">
      Designation:
    </label>

    <div class="col-md-3">
       <select name="emp_desig_id" id="emp_desig_id" class="form-control chosen" required>
        <option value="0">Select</option>
          @if(isset($designation) && count($designation)>0)
            @foreach ($designation as $desig)
              @if(isset($emp_desig_id) && $emp_desig_id==$desig->desig_id)
                <option value="{{$desig->desig_id}}" selected="selected">{{$desig->desig_name}}</option>
              @else
                <option value="{{$desig->desig_id}}">{{$desig->desig_name}}</option>
              @endif
            @endforeach
          @endif
        </select>
    </div>

  </div>

  <div class="form-group">

    <label class="col-md-1 control-label" style="text-align: right;font-size:12px;padding: 5px 0px">
      Job Location:
    </label>

    <div class="col-md-3">
     <select name="emp_jlid" id="emp_jlid" class="form-control chosen" required>
      <option value="0">Select</option>
          @if(isset($joblocation) && count($joblocation)>0)
            @foreach ($joblocation as $jl)
              @if(isset($emp_jlid) && $emp_jlid==$jl->jl_id)
                <option value="{{$jl->jl_id}}" selected="selected">{{$jl->jl_name}}</option>
              @else
                <option value="{{$jl->jl_id}}">{{$jl->jl_name}}</option>
              @endif
            @endforeach
          @endif
        </select>
    </div>

    <label class="col-md-1 control-label" style="text-align: right;font-size:12px;padding: 5px 0px">
      Employee Type:
    </label>

    <div class="col-md-3">
        <select name="emp_type" id="emp_type" class="form-control chosen" required>
          <option value="0">Select</option>
          @if(isset($types[0]))
          @foreach ($types as $key => $type)
            <option value="{{$type->id}}" @if(isset($emp_type) && $emp_type==$type->id) selected @endif>{{$type->name}}</option>
          @endforeach
          @endif
        </select>
    </div>

    <div class="col-md-1">
    </div>
    <div class="col-md-3">
          <a class="btn btn-success btn-md btn-block" onclick="getFilter();">Search</a>
    </div>

  </div>

  <div class="form-group">
    <div class="col-md-6 col-md-offset-3">
     <span id="hidden_table_title" style="display: none;">Employee List</span>
    </div>
  </div>


    <div class="col-md-12"  id="sms">
    </div>

  </form>
</div>
<!-- filter end -->
@endif

  <div class="row">
    <div class="col-md-12">
       @include('error.msg')
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light bordered">

            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class="icon-settings font-dark"></i>
                    <span class="caption-subject bold uppercase">Employee List </span>
                </div>

                <div class="col-md-6"><p id="confirm_msg"></p></div>
                <div class="actions">

                  @if(!isset($search))
                   <button type="button" class="btn btn-success checkall">Select All Data</button>
                  <a href="{{route('employee-list-print')}}" type="button" class="btn btn-success" target="_blank">Print</a>
                  @else
                   <button type="button" class="btn btn-success checkall">Select All Data</button>
                   <a class="btn btn-success" onclick="getFilter_print();">Print</a>
                  @endif

                  @if((Controller::checkAppModulePriority($route,'Edit')=="1") or (Controller::checkAppModulePriority($route,'Update Employee Type')=="1") or (Controller::checkAppModulePriority($route,'Reject/Resign/Suspend')=="1") or (Controller::checkAppModulePriority($route,'View')=="1") or (Controller::checkAppModulePriority($route,'View History')=="1"))
                    <div class="btn-group">
                        <a class="btn purple btn-outline btn-circle" href="javascript:;" data-toggle="dropdown">
                            <i class="fa fa-list"></i>
                            <span> Options </span>
                            <i class="fa fa-angle-down"></i>
                        </a>
                        <ul class="dropdown-menu" id="sample_3_tools">

                          @if(Controller::checkAppModulePriority($route,'View')=="1")
                            <li>
                                <a onclick="return View();"><i class="fa fa-search"></i>View</a>
                            </li>
                          @endif

                          @if(Controller::checkAppModulePriority($route,'Edit')=="1")
                            <li>
                                <a onclick="return Edit();"><i class="fa fa-pencil"></i>Edit</a>
                            </li>
                          @endif

                          @if(Controller::checkAppModulePriority($route,'Update Employee Type')=="1")
                            <li>
                                <a onclick="return UpdateEmployeeType();"><i class="fa fa-edit"></i>Update Employee Type</a>
                            </li>
                          @endif

                           @if(Controller::checkAppModulePriority($route,'Update Employee Type')=="1")
                           <li>
                                <a onclick="return UpdateEmployeeShift();"><i class="fa fa-edit"></i>Update Employee Shift</a>
                            </li>
                            @endif

                          @if(Controller::checkAppModulePriority($route,'Reject/Resign/Suspend')=="1")
                            <li>
                                <a  onclick="return Resign();"><i class="icon-trash"></i> Resign</a>
                            </li>
                          @endif

                           @if(Controller::checkAppModulePriority($route,'Reject/Resign/Suspend')=="1")
                            <li>
                                <a  onclick="return Suspend();"><i class="icon-trash"></i> Suspend</a>
                            </li>
                          @endif

                           @if(Controller::checkAppModulePriority($route,'Reject/Resign/Suspend')=="1")
                            <li hidden="">
                                <a  onclick="return Reject();"><i class="icon-trash"></i> Reject</a>
                            </li>
                          @endif

                          @if(Controller::checkAppModulePriority($route,'View History')=="1")
                            <li>
                                <a  onclick="return History();"><i class="fa fa-search"></i> View History</a>
                            </li>
                          @endif
                        </ul>
                    </div>
                  @endif

                    <div class="btn-group">
                        <a class="btn red btn-outline btn-circle" href="javascript:;" data-toggle="dropdown">
                            <i class="fa fa-gears"></i>
                            <span> Tools </span>
                            <i class="fa fa-angle-down"></i>
                        </a>
                        <ul class="dropdown-menu pull-right" id="sample_3_tools">
                            <li hidden="">
                                <a href="javascript:;" data-action="0" class="tool-action">
                                    <i class="icon-printer"></i> Print</a>
                            </li>

                            <li>
                                <a href="javascript:;" data-action="2" class="tool-action">
                                    <i class="icon-doc"></i> PDF</a>
                            </li>
                            <li>
                                <a href="javascript:;" data-action="3" class="tool-action">
                                    <i class="icon-paper-clip"></i> Excel</a>
                            </li>

                            </li>
                        </ul>
                    </div>
                </div>

                <div class="tools"> </div>
            </div>
            <div class="kt-portlet__body">
              <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{URL::to('')}}" id="data_form">
              {{ csrf_field() }}
               {{--<table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">--}}
               <table class="table table-striped table-bordered table-hover" id="sample_3">


                      <thead>
                        <tr>
                          <th>
                          </th>
                          <th>SL</th>
                          <th>Employee ID</th>
                          @if($id->suser_level=="1")
                          <th>Face ID</th>
                          @endif
                          <th>Employee Name</th>
                          <th>Designation</th>
                          <th>Department</th>
                          <th>Sub-Department</th>
                          <th>Shift Time</th>
                          <th>Employee Type</th>
                          <th>Senior Employee</th>
                        </tr>
                      </thead>

                      <tbody>
                        {!! $data !!}
                      </tbody>

                    </table>
                  </form>
            </div>
          </div>
        </div>
      </div>
    </div>

<script src="{{URL::to('/')}}/public/js/bootstrap-datepicker.js"></script>
<script type="text/javascript">
$('#datepicker1').datepicker();
$('#datepicker2').datepicker();
getFilterSubDepartment();
function getFilterSubDepartment () {
  var emp_depart_id=$('#filter_emp_depart_id').val();
  if(emp_depart_id!="0"){
    $.ajax({
      url:"{{URL::to('getFilterSubDepartment')}}/"+emp_depart_id,
      type:'GET',
      data:{},
      success:function(data) {
        $('#filter_emp_sdepart_id').html(data).trigger("chosen:updated");
      }
    });
  }else{
    $('#filter_emp_sdepart_id').html('<option value="0">Select</option>').trigger("chosen:updated");
  }
}

function getFilter() {
  var emp_depart_id=$('#filter_emp_depart_id').val();
  var emp_sdepart_id=$('#filter_emp_sdepart_id').val();
  if(emp_sdepart_id=="null"){
    emp_sdepart_id=0;
  }
  var emp_desig_id=$('#emp_desig_id').val();
  var emp_jlid=$('#emp_jlid').val();
  var emp_type=$('#emp_type').val();

  if(emp_depart_id>0 || emp_sdepart_id>0 || emp_desig_id>0 || emp_jlid>0 || emp_type>0){
    window.open("{{URL::to('employee-details')}}/"+emp_depart_id+"/"+emp_sdepart_id+"/"+emp_desig_id+"/"+emp_jlid+"/"+emp_type+"/search","_parent");
  }else{
    $('#sms').html('<p class="alert alert-danger">Please Choose at least one Filter Option.</p>');
  }
}
function getFilter_print() {
  var emp_depart_id=$('#filter_emp_depart_id').val();
  var emp_sdepart_id=$('#filter_emp_sdepart_id').val();
  if(emp_sdepart_id=="null"){
    emp_sdepart_id=0;
  }
  var emp_desig_id=$('#emp_desig_id').val();
  var emp_jlid=$('#emp_jlid').val();
  var emp_type=$('#emp_type').val();

  if(emp_depart_id>0 || emp_sdepart_id>0 || emp_desig_id>0 || emp_jlid>0 || emp_type>0){
    window.open("{{URL::to('employee-details-print')}}/"+emp_depart_id+"/"+emp_sdepart_id+"/"+emp_desig_id+"/"+emp_jlid+"/"+emp_type+"/search","_parent");
  }else{
    $('#sms').html('<p class="alert alert-danger">Please Choose at least one Filter Option.</p>');
  }
}
function View() {
  var data=$('#data_form').serializeArray();
  $.ajax({
    headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
    url: "{{URL::to('employee-details-view')}}",
    type: 'POST',
    data: data,
    success:function(data) {
      if(data=="null"){
        $('#confirm_msg').html('<b class="alert alert-danger" style="padding:0px 10px 0px 10px">Please Select A Employee To View</b>');
      }else if(data=="max"){
        $('#confirm_msg').html('<b class="alert alert-danger" style="padding:0px 10px 0px 10px">Please Select Maximum Of One Employee To View</b>');
      }else{
        window.open("{{URL::to('employee-details')}}/"+data+"/view",'_parent');
      }
      }
  });
}

function History() {
  var data=$('#data_form').serializeArray();
  $.ajax({
    headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
    url: "{{URL::to('employee-details-view')}}",
    type: 'POST',
    data: data,
    success:function(data) {
      if(data=="null"){
        $('#confirm_msg').html('<b class="alert alert-danger" style="padding:0px 10px 0px 10px">Please Select A Employee To View History</b>');
      }else if(data=="max"){
        $('#confirm_msg').html('<b class="alert alert-danger" style="padding:0px 10px 0px 10px">Please Select Maximum Of One Employee To View History</b>');
      }else{
        window.open("{{URL::to('employee-details')}}/"+data+"/history",'_parent');
      }
      }
  });
}

function Edit() {
  var data=$('#data_form').serializeArray();
  $.ajax({
    headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
    url: "{{URL::to('employee-details-edit')}}",
    type: 'POST',
    data: data,
    success:function(data) {
      if(data=="null"){
        $('#confirm_msg').html('<b class="alert alert-danger" style="padding:0px 10px 0px 10px">Please Select A Employee To Edit</b>');
      }else if(data=="max"){
        $('#confirm_msg').html('<b class="alert alert-danger" style="padding:0px 10px 0px 10px">Please Select Maximum Of One Employee To Edit</b>');
      }else{
        window.open("{{URL::to('employee-details')}}/"+data+"/edit",'_parent');
      }
      }
  });
}

function Reject() {
  var confirm_msg = confirm("Are  You Confirm To Reject ?");
  if (confirm_msg){
      var data=$('#data_form').serializeArray();
      $.ajax({
        headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
        url: "{{URL::to('employee-details-reject')}}",
        type: 'POST',
        data: data,
        success:function(data) {
          if(data=="1"){
            location.reload();
          }else if(data=="0"){
            $('#confirm_msg').html('<b class="alert alert-danger" style="padding:0px 10px 0px 10px">Something Went Wrong!!</b>');
          }else if(data=="null"){
            $('#confirm_msg').html('<b class="alert alert-danger" style="padding:0px 10px 0px 10px">Please Select One or More Employee To Reject</b>');
          }
        }
      });
  }else{
    return false;
  }
}

function Resign() {
  var confirm_msg = confirm("Are  You Confirm To Resign ?");
  if (confirm_msg){
      var data =$('#data_form').serializeArray();
      $.ajax({
        headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
        url: "{{URL::to('employee-details-resign')}}",
        type: 'POST',
        data: data,
        success:function(data) {
          if(data=="1"){
            location.reload();
          }else if(data=="0"){
            $('#confirm_msg').html('<b class="alert alert-danger" style="padding:0px 10px 0px 10px">Something Went Wrong!!</b>');
          }else if(data=="null"){
            $('#confirm_msg').html('<b class="alert alert-danger" style="padding:0px 10px 0px 10px">Please Select One or More Employee To Resign</b>');
          }
        }
      });
  }else{
    return false;
  }
}

function Suspend() {
  var confirm_msg = confirm("Are  You Confirm To Suspend ?");
  if (confirm_msg){
      var data=$('#data_form').serializeArray();
      $.ajax({
        headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
        url: "{{URL::to('employee-details-suspend')}}",
        type: 'POST',
        data: data,

        success:function(data) {
          if(data=="1"){
            location.reload();
          }else if(data=="0"){
            $('#confirm_msg').html('<b class="alert alert-danger" style="padding:0px 10px 0px 10px">Something Went Wrong!!</b>');
          }else if(data=="null"){
            $('#confirm_msg').html('<b class="alert alert-danger" style="padding:0px 10px 0px 10px">Please Select One or More Employee To Suspend</b>');
          }
        }
      });
  }else{
    return false;
  }
}

function UpdateEmployeeType() {
  $.alert({
      title: 'Update Employee Type',
      content: "url:{{url('employee-details-update-employee-type')}}",
      animation: 'scale',
      closeAnimation: 'bottom',
      columnClass: 'col-md-6 col-md-offset-3',
      buttons: {
          cancel: {
              text: 'Cancel',
              btnClass: 'btn-light text-dark',
              action: function(){
                  // do nothing
              }
          }
      }
  });
}
function UpdateEmployeeShift() {
  $.alert({
      title: 'Update Employee Shift',
      content: "url:{{url('employee-details-update-employee-shift')}}",
      animation: 'scale',
      closeAnimation: 'bottom',
      columnClass: 'col-md-6 col-md-offset-3',
      buttons: {
          cancel: {
              text: 'Cancel',
              btnClass: 'btn-light text-dark',
              action: function(){
                  // do nothing
              }
          }
      }
  });
}
var clicked = false;
$(".checkall").on("click", function() {
  $(".checkboxes").prop("checked", !clicked);
  clicked = !clicked;
  this.innerHTML = clicked ? 'Deselect All Data' : 'Select All Data';
});
</script>
@endsection