@extends('Admin.index')
@section('body')

@php
use App\Http\Controllers\BackEndCon\Controller;
use Illuminate\Support\Facades\Route;
$route=Route::getFacadeRoot()->current()->uri();
@endphp

<script src="{{URL::to('/')}}/public/assets/global/plugins/jquery.min.js"></script>
<link href="{{URL::to('/')}}/public/css/bootstrap-timepicker.min.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="{{URL::to('/')}}/public/css/datepicker.css" />

<!-- leave application modal start -->
<div class="modal fade" id="leaveApplicationModal" role="dialog">
  <div class="modal-dialog modal-lg" style="width: 80%;height: auto;margin: auto;padding: 0;">
  
    <!-- Modal content-->
    <div class="modal-content" style="height: auto;min-height: 100%;border-radius: 0;">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Leave Application</h4>
      </div>
      <div class="modal-body" >

          
          <div class="container-fluid">

          <div class="row-fluid">
            <div class="span12">
              
                <div class="widget-box">

                    <div class="widget-content tab-content">
                      
                      <div id="tab1" class="tab-pane active">

                        <div class="widget-box">

                          <div class="widget-content nopadding" style='margin-top: 25px;'>
                            <form class="form-horizontal form-row-seperated" method="post" enctype="multipart/form-data" action="{{URL::to('leave-application-submit')}}" name="basic_validate" id="basic_validate" novalidate="novalidate">
                            {{ csrf_field() }}
                        
                            
                             <div class="form-group">
                                <ul class="nav nav-tabs">
                                  @if($leave_type)
                                  @php
                                  $count=0;
                                  @endphp
                                    @foreach ($leave_type as $lt)
                                    @php
                                    $count++;
                                    @endphp
                                      @if($count=="1")
                                        <li class="active"><a data-toggle="tab" href="#{{$lt->li_id}}" onclick="getExtra('{{$lt->li_id}}');">{{$lt->li_name}}</a></li>
                                      @else
                                        <li><a data-toggle="tab" href="#{{$lt->li_id}}" onclick="getExtra('{{$lt->li_id}}');">{{$lt->li_name}}</a></li>
                                      @endif
                                    @endforeach
                                  @endif     
                                </ul>
                              </div>

                              <div class="form-group" id="leave_type_div" style="text-align: center;">
                                <input type="radio" name="leave_type" id="leave_type_0" checked="checked" value="0">&nbsp;Full day Leave(Single or Multiple)
                                &nbsp;&nbsp;&nbsp;
                                <input type="radio" name="leave_type" id="leave_type_1" value="1">&nbsp;Half day Leave(First Half)
                                &nbsp;&nbsp;&nbsp;
                                <input type="radio" name="leave_type" id="leave_type_2" value="2">&nbsp;Half day Leave(Second Half)
                              </div>

                              <div class="form-group">
                                <label class="col-md-2 control-label">Start Date : <span class="required">* </span></label>
                                <div class="col-md-2">
                                  @if($leave_type)
                                    <input type="hidden" name="leave_typeid" id="leave_typeid" value="{{$leave_type[0]->li_id}}">
                                  @endif 
                                  <input type="text" class="form-control" name="leave_start_date" id="leave_start_date"  data-date-format="yyyy-mm-dd" onblur="dateChecker();" onchange="dateChecker();" required readonly="readonly" style="background: white;">
                                </div>
                                <div class="col-md-2">
                                  <input type="text" class="form-control" name="leave_start_time" id="leave_start_time" value="00:00" readonly="readonly" style="background: white;display: none" onblur="dateChecker();" onchange="dateChecker();">
                                </div>

                                <label class="col-md-2 control-label">End Date : <span class="required">* </span></label>
                                <div class="col-md-2">
                                  <input type="text" class="form-control" name="leave_end_date" id="leave_end_date"  data-date-format="yyyy-mm-dd" onblur="dateChecker();" onchange="dateChecker();" required readonly="readonly" style="background: white">
                                </div>
                                <div class="col-md-2">
                                  <input type="text" class="form-control" name="leave_end_time" id="leave_end_time" value="00:00" readonly="readonly" style="background: white;display: none" onblur="dateChecker();" onchange="dateChecker();">
                                </div>
                              </div>
                              
                              <div class="form-group" id="date_checker" style="display:none;text-align: center;color: red">
                                
                              </div>

                              <div class="form-group">
                                <label class="col-md-2 control-label">Reason : <span class="required">* </span></label>
                                <div class="col-md-10">
                                  <input type="text" class="form-control" name="leave_reason" value="{{old('leave_reason')}}" required>
                                </div>
                              </div>

                              <div class="form-group" id="compensatory" style="display: none;">
                                <label class="col-md-2 control-label">Leave Against From : <span class="required">* </span></label>
                                <div class="col-md-4">
                                  <input type="text" class="form-control" name="leave_replacement_from_date" id="leave_replacement_from_date"  data-date-format="yyyy-mm-dd" required readonly="readonly" style="background: white">
                                </div>

                                <label class="col-md-2 control-label">To : <span class="required">* </span></label>
                                <div class="col-md-4">
                                  <input type="text" class="form-control" name="leave_replacement_to_date" id="leave_replacement_to_date"  data-date-format="yyyy-mm-dd" required readonly="readonly" style="background: white">
                                </div>
                              </div>

                              <br>

                              <div class="form-group" id="sick" style="display: none;">
                                <label class="col-md-2 control-label">Medical Report : <span class="required">* </span></label>
                                <div class="col-md-10">
                                  <input type="file" class="form-control" name="leave_docext" required>
                                </div>
                              </div>

                            
                            <div class="form-group">
                            <label class="col-md-2 control-label"></label>
                              <div class="col-md-3">
                                <input type="submit" value="Save" class="btn btn-success">
                              </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-6 col-md-offset-2"  style="font-size: 12px">Remaining Balance : <span id="balance" style="font-weight: bold"></span><input type="hidden" name="hidden_balance" id="hidden_balance" value="{{$balance}}"><br><span id="leave_msg" style="font-weight: bold"></span></label>
                            </div>

                            <div class="form-group table-responsive">
                              <table class="table table-bordered table-striped table-hover">
                                <br>
                                <caption><strong>Current Leave Status</strong></caption>
                                <thead>
                                  <tr>
                                    <th>Leave Type</th>
                                    <th>Qouta Day</th>
                                    <th>Leave Taken</th>
                                    <th>Remaining Leave</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  {!! $current_leave_status !!}
                                </tbody>
                              </table>
                            </div>
                              
                          </div>
                          

                            </form>
                          </div>

                        </div>
                      </div>

                    </div>

                </div>

            </div>
          </div>
          </div>

     
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
    
  </div>
</div>
<!-- leave application modal end -->

<!-- filter start -->
<div class="row" style='padding: 30px; background-color: #f7f7f7; margin: 0px;'>

  <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{route('leave-application-filter')}}">
     @csrf
               
  @if($id->suser_level=="1")
  <div class="form-group">

    

    
    
    



    
    
  </div>
  
  <div class="form-group">

  

      

    

    

    

  </div>
  @endif

  <div class="form-group">
     <span id="hidden_table_title" style="display: none;">Leave Application List @if(isset($start_date) && isset($end_date))( {{$start_date}} to {{$end_date}} )
      @endif</span>



    
    @if(!isset($start_date) && !isset($end_date))
    <div class="col-md-12">
        <label class="col-md-1 control-label" style="text-align: right;font-size:12px;padding: 5px 0px">
        Start Date:
      </label>
      <div class="col-md-3">
        <input type="date" class="form-control" id="start_date" style="background: white;padding: 0px 0px 0px 10px" required="" value="{{ date('Y-m-d') }}" name="start_date"></div>
         <label class="col-md-1 control-label" style="text-align: right;font-size:12px;padding: 5px 0px">
        End Date:
      </label>
        <div class="col-md-3">
            <input type="date" class="form-control" id="end_date" style="background: white;padding: 0px 0px 0px 10px" required="" value="{{ date('Y-m-d') }}" name="end_date"></div>

    </div><br><br><br>
    @endif

    @if(isset($start_date) && isset($end_date))
    <div class="col-md-12">
        <label class="col-md-1 control-label" style="text-align: right;font-size:12px;padding: 5px 0px">
        Start Date:
      </label>
      <div class="col-md-3">
        <input type="date" class="form-control" id="start_date" style="background: white;padding: 0px 0px 0px 10px" required="" value="{{ $start_date }}" name="start_date"></div>
         <label class="col-md-1 control-label" style="text-align: right;font-size:12px;padding: 5px 0px">
         End Date:
      </label>
        <div class="col-md-3">
            <input type="date" class="form-control" id="end_date" style="background: white;padding: 0px 0px 0px 10px" required="" value="{{ $end_date }}" name="end_date"></div>



    </div><br><br><br>
    @endif
     <label class="col-md-1 control-label" style="text-align: right;font-size:12px;padding: 5px 0px">
      Employee:
    </label>
    @php
      $emp=App\Employee::get();
      $filter_id = App\Employee::where('emp_id', $emp_id)->first();
      
    @endphp
    <div class="col-md-3">
        <select name="emp" class="form-control chosen" required>

          @if($flag==0)
          <option value="0">Select</option>
          @elseif($flag==1)
            @if(isset($filter_id))
            <option value="{{ $filter_id->emp_id }}">{{ $filter_id->emp_name }}</option>
            @endif
          @endif
           
          @foreach ($emp as $key)
            <option value="{{$key->emp_id}}">{{$key->emp_name}}</option>
          @endforeach
          
        </select>                            
    </div>
    <div class="col-md-2"></div>
    <div class="col-md-2">
     
          
          <button type="submit" class="btn btn-success btn-md btn-block">Search</button>                  
                    
    </div>
  </div>


    <div class="col-md-12"  id="sms">
    </div>
  
  </form>
</div>
<!-- filter end -->



<div class="row">
    <div class="col-md-12">
       @include('error.msg')
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class="icon-settings font-dark"></i>
                    <span class="caption-subject bold uppercase">{{$title}} @if(isset($start_date) && isset($end_date))( {{$start_date}} to {{$end_date}} )@endif</span>
                </div>

                <div class="col-md-6"><p id="confirm_msg"></p></div>
                <div class="actions">
                    @if(Controller::checkAppModulePriority($route,'Leave Application Request')=="1")
                      <span style="float: left;padding: 0px 10px 5px 0px">
                        <a style="cursor: pointer;" class="btn btn-primary btn-sm btn-circle" data-toggle="modal" data-target="#leaveApplicationModal" >Leave Application Request</a>
                      </span>
                    @endif

                    @if((Controller::checkAppModulePriority($route,'Approve')=="1") or (Controller::checkAppModulePriority($route,'Deny')=="1") or (Controller::checkAppModulePriority($route,'Delete')=="1"))
                    <div class="btn-group">
                        <a class="btn purple btn-outline btn-circle" href="javascript:;" data-toggle="dropdown">
                            <i class="fa fa-list"></i>
                            <span> Options </span>
                            <i class="fa fa-angle-down"></i>
                        </a>
                        <ul class="dropdown-menu" id="sample_3_tools">
                          @if(Controller::checkAppModulePriority($route,'Approve')=="1")
                            <li>                                 
                                  <a onclick="return Approve();">
                                    <i class="fa fa-check-circle"></i> Approve</a>
                            </li>
                          @endif

                          @if(Controller::checkAppModulePriority($route,'Deny')=="1")
                            <li>                                 
                                  <a  onclick="return Deny();">
                                    <i class="fa fa-close"></i> Deny</a>
                            </li>
                          @endif

                          @if(Controller::checkAppModulePriority($route,'Delete')=="1")
                            <li>                                 
                                  <a  onclick="return Delete();">
                                    <i class="fa fa-trash"></i> Delete</a>
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
                            <li>
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
            <div class="portlet-body">
               <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{URL::to('')}}" name="basic_validate" id="data_form">
              {{ csrf_field() }}
               <table class="table table-striped table-bordered table-hover" id="sample_3">

                       <thead>
                        <tr>
                          <th>
                          </th>
                          <th>SL</th>
                          <th>Application No.</th>
                          <th>Employee ID</th>
                          <th>Employee Name</th>
                          <th>Leave Type</th>
                          <th>Start Date</th>
                          <th>End Date</th>
                          <th>Total Days</th>
                          <th>Reason</th>
                          <th>Attachments</th>
                          <th>Leave Against</th>
                          <th>Notes</th>
                          <th>Status</th>
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
<script src="{{URL::to('/')}}/public/js/bootstrap-timepicker.min.js" type="text/javascript"></script>

<script type="text/javascript">
$('#leave_start_date').datepicker();
$('#leave_end_date').datepicker();
$('#leave_replacement_from_date').datepicker();
$('#leave_replacement_to_date').datepicker();
$('#leave_start_time').timepicker({   
    minuteStep: 1,
     disableFocus: true,
     template: 'dropdown',
     showMeridian:false
}).val('00:00');
$('#leave_end_time').timepicker({   
    minuteStep: 1,
     disableFocus: true,
     template: 'dropdown',
     showMeridian:false
}).val('00:00');

$('#leave_type_0').click(function() {
  if($('#leave_type_0').is(':checked')) {

  }
});

$('#leave_type_1').click(function() {
   if($('#leave_type_1').is(':checked')) { 

  }
});

$('#leave_type_2').click(function() {
   if($('#leave_type_2').is(':checked')) {

  }
});

function getExtra(leave_typeid) {
  $('#leave_typeid').val(leave_typeid);
  $('#leave_type_div').show();
  $('#leave_msg').html('');
  if(leave_typeid=="4"){
    $('#sick').show();
    $('#compensatory').hide();
    $('#leave_start_time').val('00:00').hide();
    $('#leave_start_time_label').html('00:00').show();
    $('#leave_end_time').val('00:00').hide();
    $('#leave_end_time_label').html('00:00').show();
    $('#leave_msg').html('* Medical Document is required for 3 or more days Sick leave.');
  }else if(leave_typeid=="5"){
    $('#compensatory').show();
    $('#sick').hide();
    $('#leave_start_time').val('00:00').hide();
    $('#leave_start_time_label').html('00:00').show();
    $('#leave_end_time').val('00:00').hide();
    $('#leave_end_time_label').html('00:00').show();
  }else if(leave_typeid=="2"){
    $('#leave_type_div').hide();
    $('#sick').hide();
    $('#compensatory').hide();
    $('#leave_start_time').val('00:00').show();
    $('#leave_start_time_label').hide();
    $('#leave_end_time').val('00:00').show();
    $('#leave_end_time_label').hide();
    $('#leave_msg').html('* Short leaves will be adjusted from Casual leave extended upto Annual leave.');
  }else if(leave_typeid=="6"){
    $('#leave_type_div').hide();
    $('#leave_start_time_label').hide();
    $('#leave_start_time').val('00:00').show();
    $('#leave_end_time_label').hide();
    $('#leave_end_time').val('00:00').show();
    $('#sick').hide();
    $('#compensatory').hide();
    $('#leave_start_time').val('00:00').hide();
    $('#leave_start_time_label').html('00:00').show();
    $('#leave_end_time').val('00:00').hide();
    $('#leave_end_time_label').html('00:00').show();
  }else{
    $('#leave_start_time_label').hide();
    $('#leave_start_time').val('00:00').show();
    $('#leave_end_time_label').hide();
    $('#leave_end_time').val('00:00').show();
    $('#sick').hide();
    $('#compensatory').hide();
    $('#leave_start_time').val('00:00').hide();
    $('#leave_start_time_label').html('00:00').show();
    $('#leave_end_time').val('00:00').hide();
    $('#leave_end_time_label').html('00:00').show();
  }

  dateChecker();
}

function getBalance() {
    var leave_typeid=$('#leave_typeid').val();
    var leave_start_date=$('#leave_start_date').val();
    if(leave_start_date==null || leave_start_date==""){
      leave_start_date='0000-00-00';
    }
    var leave_end_date=$('#leave_end_date').val();
    if(leave_end_date==null || leave_end_date==""){
      leave_end_date='0000-00-00';
    }

    var leave_start_time=$('#leave_start_time').val();
    if(leave_start_time==null || leave_start_time==""){
      leave_start_time='00:00';
    }
    var leave_end_time=$('#leave_end_time').val();
    if(leave_end_time==null || leave_end_time==""){
      leave_end_time='00:00';
    }
    $.ajax({
      url: "{{URL::to('getBalance')}}/"+leave_typeid+'/'+leave_start_date+'/'+leave_end_date+'/'+leave_start_time+'/'+leave_end_time,
      type: 'GET',
      data: {},
      success:function(data) {
        $('#balance').html(parseFloat(data));
        $('#hidden_balance').val(parseFloat(data));
      }
    });
    
  }

  function dateChecker() {
    var leave_typeid=$('#leave_typeid').val();
    var leave_start_date=$('#leave_start_date').val();
    if(leave_start_date==null || leave_start_date==""){
      leave_start_date='0000-00-00';
    }
    var leave_end_date=$('#leave_end_date').val();
    if(leave_end_date==null || leave_end_date==""){
      leave_end_date='0000-00-00';
    }

    var leave_start_time=$('#leave_start_time').val();
    if(leave_start_time==null || leave_start_time==""){
      leave_start_time='00:00';
    }
    var leave_end_time=$('#leave_end_time').val();
    if(leave_end_time==null || leave_end_time==""){
      leave_end_time='00:00';
    }
      $.ajax({
        url: "{{URL::to('dateChecker')}}/"+leave_typeid+'/'+leave_start_date+'/'+leave_end_date+'/'+leave_start_time+'/'+leave_end_time,
        type: 'GET',
        data: {},
        success:function(data) {
          if(data!=""){
            data=data.split('///');
            if(data[0]=="0"){
              $('#date_checker').html('<h6><strong>'+data[1]+'</strong></h6>');
              $('#date_checker').show();
            }else{
              $('#date_checker').html('');
              $('#date_checker').hide();
            }
          }else{
            $('#date_checker').html('');
            $('#date_checker').hide();
          }
        }
      });
      getBalance();
  }
  
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

function Approve() {
  $.confirm({
    title: '',
    content: '<h4><strong class="text-success">Are  You Confirm To Approve ?</strong></h4><hr>',
    buttons: {
        Confirm: {
            text: 'Approve',
            btnClass: 'btn-success',
            action: function(){
                var data=$('#data_form').serializeArray();
                $.ajax({
                  headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
                  url: "{{URL::to('leave-aprove')}}",
                  type: 'POST',
                  data: data,
                  success:function(data) {
                    if(data=="1"){
                      location.reload();
                    }else if(data=="null"){
                      $.alert({
                        title:'',
                        content:'<hr><strong class="text-danger">Please Select One or More Leave Application To Approve</strong><hr>',
                        type:'red'
                      });
                    }
                  }
                });
            }
        },
        Cancel: {
            text: 'Cancel',
            btnClass: 'btn-default',
            action: function(){
                
            }
        }
    }
  });
}

function Deny() {
  $.confirm({
    title: '',
    content: '<h4><strong class="text-danger">Are  You Confirm To Deny ?</strong></h4><hr>',
    buttons: {
        Confirm: {
            text: 'Deny',
            btnClass: 'btn-danger',
            action: function(){
                var data=$('#data_form').serializeArray();
                $.ajax({
                  headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
                  url: "{{URL::to('leave-deny')}}",
                  type: 'POST',
                  data: data,
                  success:function(data) {
                    if(data=="1"){
                      location.reload();
                    }else if(data=="null"){
                      $.alert({
                        title:'',
                        content:'<hr><strong class="text-danger">Please Select One or More Leave Application To Deny</strong><hr>',
                        type:'red'
                      });
                    }
                  }
                });
            }
        },
        Cancel: {
            text: 'Cancel',
            btnClass: 'btn-default',
            action: function(){
                
            }
        }
    }
  });
}

function Delete() {
  $.confirm({
    title: '',
    content: '<h4><strong class="text-danger">Are  You Confirm To Delete ?</strong></h4><hr>',
    buttons: {
        Confirm: {
            text: 'Delete',
            btnClass: 'btn-danger',
            action: function(){
                var data=$('#data_form').serializeArray();
                $.ajax({
                  headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
                  url: "{{URL::to('leave-application-delete')}}",
                  type: 'POST',
                  data: data,
                  success:function(data) {
                    if(data=="1"){
                      location.reload();
                    }else if(data=="0"){
                      $.alert({
                        title:'',
                        content:'<hr><strong class="text-danger">Something Went Wrong!!</strong><hr>',
                        type:'red'
                      });
                    }else if(data=="null"){
                      $.alert({
                        title:'',
                        content:'<hr><strong class="text-danger">Please Select One or More Leave Application To Delete</strong><hr>',
                        type:'red'
                      });
                    }
                  }
                });
            }
        },
        Cancel: {
            text: 'Cancel',
            btnClass: 'btn-default',
            action: function(){
                
            }
        }
    }
  });
}
</script>

@if($title=="Leave Application List")
<script type="text/javascript">
  function getFilter() {
  var emp_depart_id=$('#filter_emp_depart_id').val();
  var emp_sdepart_id=$('#filter_emp_sdepart_id').val();
  if(emp_sdepart_id=="null"){
    emp_sdepart_id=0;
  }
  var emp_desig_id=$('#emp_desig_id').val();
  var emp_jlid=$('#emp_jlid').val();
  var emp_type=$('#emp_type').val();
  // var date_range=$('#defaultrange').val();
  // date_range=date_range.split(' - ');
  // var start_date=date_range[0].split('/');
  // start_date=start_date[2]+'-'+start_date[1]+'-'+start_date[0];
  // var end_date=date_range[1].split('/');
  // end_date=end_date[2]+'-'+end_date[1]+'-'+end_date[0];
  var start_date=$('#start_date').val();
  var end_date=$('#end_date').val();
  
  if(emp_depart_id>0 || emp_sdepart_id>0 || emp_desig_id>0 || emp_jlid>0 || emp_type>0 || start_date!="" || end_date!=""){
    window.open("{{URL::to('leave-application')}}/"+emp_depart_id+"/"+emp_sdepart_id+"/"+emp_desig_id+"/"+emp_jlid+"/"+emp_type+"/"+start_date+"/"+end_date+"/search","_parent");
  }else{
    $('#sms').html('<p class="alert alert-danger">Please Choose at least one Filter Option.</p>');
  }
}

function getFilterStaff() {
  // var date_range=$('#defaultrange').val();
  // date_range=date_range.split(' - ');
  // var start_date=date_range[0].split('/');
  // start_date=start_date[2]+'-'+start_date[1]+'-'+start_date[0];
  // var end_date=date_range[1].split('/');
  // end_date=end_date[2]+'-'+end_date[1]+'-'+end_date[0];

  var start_date=$('#start_date').val();
  var end_date=$('#end_date').val();
  
  if(start_date!="" || end_date!=""){
    window.open("{{URL::to('leave-application')}}/0/0/0/0/0/"+start_date+"/"+end_date+"/search","_parent");
  }else{
    $('#sms').html('<p class="alert alert-danger">Please Choose at least one Filter Option.</p>');
  }
}
</script>
@elseif($title=="Pending Leave Application List")
<script type="text/javascript">
  function getFilter() {
  var emp_depart_id=$('#filter_emp_depart_id').val();
  var emp_sdepart_id=$('#filter_emp_sdepart_id').val();
  if(emp_sdepart_id=="null"){
    emp_sdepart_id=0;
  }
  var emp_desig_id=$('#emp_desig_id').val();
  var emp_jlid=$('#emp_jlid').val();
  var emp_type=$('#emp_type').val();
  // var date_range=$('#defaultrange').val();
  // date_range=date_range.split(' - ');
  // var start_date=date_range[0].split('/');
  // start_date=start_date[2]+'-'+start_date[1]+'-'+start_date[0];
  // var end_date=date_range[1].split('/');
  // end_date=end_date[2]+'-'+end_date[1]+'-'+end_date[0];

  var start_date=$('#start_date').val();
  var end_date=$('#end_date').val();
  
  if(emp_depart_id>0 || emp_sdepart_id>0 || emp_desig_id>0 || emp_jlid>0 || emp_type>0 || start_date!="" || end_date!=""){
    window.open("{{URL::to('pending-leave-application')}}/"+emp_depart_id+"/"+emp_sdepart_id+"/"+emp_desig_id+"/"+emp_jlid+"/"+emp_type+"/"+start_date+"/"+end_date+"/search","_parent");
  }else{
    $('#sms').html('<p class="alert alert-danger">Please Choose at least one Filter Option.</p>');
  }
}

function getFilterStaff() {
  // var date_range=$('#defaultrange').val();
  // date_range=date_range.split(' - ');
  // var start_date=date_range[0].split('/');
  // start_date=start_date[2]+'-'+start_date[1]+'-'+start_date[0];
  // var end_date=date_range[1].split('/');
  // end_date=end_date[2]+'-'+end_date[1]+'-'+end_date[0];

  var start_date=$('#start_date').val();
  var end_date=$('#end_date').val();
  
  if(start_date!="" || end_date!=""){
    window.open("{{URL::to('pending-leave-application')}}/0/0/0/0/0/"+start_date+"/"+end_date+"/search","_parent");
  }else{
    $('#sms').html('<p class="alert alert-danger">Please Choose at least one Filter Option.</p>');
  }
}
</script>
@endif
@endsection