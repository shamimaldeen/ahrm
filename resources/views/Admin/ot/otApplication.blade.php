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
<style type="text/css" media="screen">
  .chosen-container { width: 100% !important; }
</style>
<!--Request Modal start -->

<div class="modal fade" id="requestModal" role="dialog">
  <div class="modal-dialog modal-lg">
  
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">OT Request Application</h4>
      </div>
      <div class="modal-body" >

          
          <div class="container-fluid">

          <div class="row-fluid">
            <div class="span12">
              
              @include('error.msg')
                <div class="widget-box">

                    <div class="widget-content tab-content">
                      
                      <div id="tab1" class="tab-pane active">

                        <div class="widget-box">
                           
                           <div class="widget-title" style='background-color: #f1f0f0; padding: 15px 20px;'> 
                            <span class="icon"> <i class="icon-info-sign"></i> </span>
                              <h5>Dear Line Manager, <br ><br >Due to assigninment/work, I need to perform the duties after my normal woring hour. In this regard, kindly allow me to work for over time. Approximate time require for perform the job as below;</h5>
                            </div>

                            <div class="widget-content nopadding" style='margin-top: 25px;'>
                              <form class="form-horizontal form-row-seperated" method="post" enctype="multipart/form-data" action="{{URL::to('ot-request-submit')}}" name="basic_validate" id="basic_validate" novalidate="novalidate">
                              {{ csrf_field() }}
                          
                              
                           <div class="form-group">
                            <label class="col-md-2 control-label">Date : <span class="required">* </span></label>
                            <div class="col-md-8">
                              <input type="text" class="form-control" name="otapp_perdate" value="{{old('otapp_perdate')}}"  data-date-format="yyyy-mm-dd" id="datepicker1" readonly="readonly" style="background: white" required>
                            </div>
                          </div>

                          <div class="form-group">
                            <label class="col-md-2 control-label"> From: <span class="required">* </span></label>
                            <div class="col-md-3">
                              <input type="text" class="form-control " name="otapp_fromtime" value="{{old('otapp_fromtime')}}"  id="timepicker1" readonly="readonly" style="background: white" required>
                            </div>
                         
                            <label class="col-md-2 control-label">To: <span class="required">* </span></label>
                            <div class="col-md-3">
                              <input type="text" class="form-control " name="otapp_totime" value="{{old('otapp_totime')}}"  data-date-format="yyyy-mm-dd" id="timepicker2" readonly="readonly" style="background: white" required>
                            </div>
                          </div>

                          <div class="form-group">
                          <label class="col-md-2 control-label"></label>
                            <div class="col-md-3">
                              <input type="submit" value="Save" class="btn btn-success">
                            </div>
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
<!--Request Modal end -->

<!--Assign Modal start -->
<div class="modal fade" id="assignModal" role="dialog">
  <div class="modal-dialog modal-lg">
  
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">OT Assign</h4>
      </div>
      <div class="modal-body modal-lg" >

          <div class="container-fluid">
          <div class="row-fluid">
          <div class="span12">
          @include('error.msg')
          <div class="widget-box">
          <div class="widget-content tab-content">
          <div id="tab1" class="tab-pane active">

          <div class="widget-box">
            
            <div class="widget-title" style='background-color: #f1f0f0; padding: 15px 20px;'> <span class="icon"> <i class="icon-info-sign"></i> </span>
              <h5>Dear Mr./Ms,<br><br>Please be inform that you are requested to complete your task even working after normal working hours. In this case, you are allowed to work for following hours beyond your normal working hours.</h5>
            </div>

                <div class="widget-content nopadding" style='margin-top: 25px;'>
                  <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{url('ot-application-assign-submit')}}" name="basic_validate" id="basic_validate" novalidate="novalidate">
                  {{ csrf_field() }}
                    <div class="form-group">
                      <div class="col-md-10 col-md-offset-1">
                        <input type="text" class="form-control text-center" name="otapp_perdate" value="{{old('otapp_perdate',date('Y-m-d'))}}"  data-date-format="yyyy-mm-dd" id="datepicker2" readonly="readonly" style="background: white" required>
                      </div>
                    </div>
                    
                    <div class="form-group">
                    @if(isset($employees[0]))
                    @foreach ($employees as $key => $employee)
                      <div class="col-md-6" style="margin-bottom:5px">
                          <label class="col-md-8 control-label">
                            {{$employee->emp_name}} ({{$employee->emp_empid}}) :
                            <input type="hidden" name="employees[]" value="{{$employee->emp_id}}">
                          </label>

                          <div class="col-md-4">
                            <input type="text" name="hours[{{$employee->emp_id}}]" class="form-control text-center" value="1">
                          </div>
                      </div>
                    @endforeach
                    @endif
                    </div>
                    <hr>
                    <div class="form-group text-center">
                      <button type="submit" class="btn btn-md btn-success"><i class="fa fa-check"></i>&nbsp;Assign</button>
                      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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


      </div>
      
    </div>
    
  </div>
</div>
<!--Assign Modal end -->

<!-- filter start -->
<div class="row" style='padding: 30px; background-color: #f7f7f7; margin: 0px;'>

  <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{URL::to('')}}" name="basic_validate" id="basic_validate" novalidate="novalidate">
      {{ csrf_field() }}
               
  @if($id->suser_level=="1")
  <div class="form-group">

    <label class="col-md-1 control-label" style="text-align: right;font-size:12px;padding: 5px 0px">
      Department:
    </label>

    <div class="col-md-3">
        <select name="emp_depart_id" id="filter_emp_depart_id" class="form-control chosen" required onchange="getFilterSubDepartment(),getFilterEmployee();;">
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
      <select name="emp_sdepart_id" id="filter_emp_sdepart_id" class="form-control chosen" required onchange="getFilterEmployee();">
      </select> 
    </div>



    <label class="col-md-1 control-label" style="text-align: right;font-size:12px;padding: 5px 0px">
      Designation:
    </label>

    <div class="col-md-3">
       <select name="emp_desig_id" id="filter_emp_desig_id" class="form-control chosen" required onchange="getFilterEmployee();">
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
     <select name="emp_jlid" id="emp_jlid" class="form-control chosen " required onchange="getFilterEmployee();">
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
        <select name="emp_type" id="emp_type" class="form-control chosen" required onchange="getFilterEmployee();">
          <option value="0">Select</option>
          @if(isset($types[0]))
          @foreach ($types as $key => $type)
            <option value="{{$type->id}}" @if(isset($emp_type) && $emp_type==$type->id) selected @endif>{{$type->name}}</option>
          @endforeach
          @endif
        </select>                            
    </div>

    <label class="col-md-1 control-label" style="text-align: right;font-size:12px;padding: 5px 0px">
      Employee :
    </label>
    <div class="col-md-3">
       <select name="emp_id" id="emp_id" class="form-control chosen" required>
            <option value="0">Select</option>
        </select>     
    </div>

  </div>
@endif
  <div class="form-group">
     <span id="hidden_table_title" style="display: none;">{{$title}} @if(isset($start_date) && isset($end_date))( {{$start_date}} to {{$end_date}} )
      @endif</span>
     
      @if(!isset($start_date) && !isset($end_date))
      
    <div class="col-md-12">
        <label class="col-md-1 control-label" style="text-align: right;font-size:12px;padding: 5px 0px">
         Start Date:
      </label>
      <div class="col-md-3">
        <input type="date" class="form-control" id="start_date" style="background: white;padding: 0px 0px 0px 10px" required="" value="{{ date('Y-m-d') }}"></div>
         <label class="col-md-1 control-label" style="text-align: right;font-size:12px;padding: 5px 0px">
         End Date:
      </label>
        <div class="col-md-3">
            <input type="date" class="form-control" id="end_date" style="background: white;padding: 0px 0px 0px 10px" required="" value="{{ date('Y-m-d') }}"></div>

    </div><br><br><br>
    @endif
     @if(isset($start_date) && isset($end_date))
      
    <div class="col-md-12">
        <label class="col-md-1 control-label" style="text-align: right;font-size:12px;padding: 5px 0px">
         Start Date:
      </label>
      <div class="col-md-3">
        <input type="date" class="form-control" id="start_date" style="background: white;padding: 0px 0px 0px 10px" required="" value="{{ $start_date }}"></div>
         <label class="col-md-1 control-label" style="text-align: right;font-size:12px;padding: 5px 0px">
         End Date:
      </label>
        <div class="col-md-3">
            <input type="date" class="form-control" id="end_date" style="background: white;padding: 0px 0px 0px 10px" required="" value="{{ $end_date }}"></div>

    </div><br><br><br>
    @endif

    <div class="col-md-4"></div>

    <div class="col-md-4">
      @if($id->suser_level=="1")
          <a class="btn btn-success btn-md btn-block" onclick="getFilter();">Search</a>                    
      @else
          <a class="btn btn-success btn-md btn-block" onclick="getFilterStaff();">Search</a>                    
      @endif                   
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
                    <span style="float: left;padding: 0px 10px 5px 0px">
                      @if(Controller::checkAppModulePriority($route,'Assign OT')=="1")
                      <a style="cursor: pointer;" class="btn btn-success btn-sm btn-circle" data-toggle="modal" data-target="#assignModal" >OT Assign</a>
                      @endif
                    </span>
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
                          <th>Employee ID</th>
                          <th>Employee Name</th>
                          <th>OT request date</th>
                          <th>OT perform date</th>
                          <th>Total Hours</th>
                          <th>Status</th>
                          @if(isset($title) && $title=="Assigned OT Application List")
                          <th>Assigned For</th>
                          <th>Assigned By</th>
                          @endif
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
$(document).ready(function(){
    getSubDepartment();
    getFilterSubDepartment();

    $('#datepicker1').datepicker();
    $('#datepicker2').datepicker();
    $('#timepicker1').timepicker({   
        minuteStep: 1,
         disableFocus: true,
         template: 'dropdown',
         showMeridian:false
    });
    $('#timepicker2').timepicker({   
        minuteStep: 1,
         disableFocus: true,
         template: 'dropdown',
         showMeridian:false
    });
    $('#timepicker3').timepicker({   
        minuteStep: 1,
         disableFocus: true,
         template: 'dropdown',
         showMeridian:false
    });
    $('#timepicker4').timepicker({   
        minuteStep: 1,
         disableFocus: true,
         template: 'dropdown',
         showMeridian:false
    });
});

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

function getSubDepartment () {
  var emp_depart_id=$('#emp_depart_id').val();
  if(emp_depart_id!="0"){
    $.ajax({
      url:"{{URL::to('getSubDepartment')}}/"+emp_depart_id,
      type:'GET',
      data:{},
      success:function(data) {
        $('#emp_sdepart_id').html(data).trigger("chosen:updated");
      }
    });
  }else{
    $('#emp_sdepart_id').html('<option value="0">Select</option>').trigger("chosen:updated");
  }
}

</script>

@if(isset($title) && $title=="OT Application List")
<script type="text/javascript">
function getFilter() {
  var emp_depart_id=$('#filter_emp_depart_id').val();
  var emp_sdepart_id=$('#filter_emp_sdepart_id').val();
  if(emp_sdepart_id=="null"){
    emp_sdepart_id=0;
  }
  var emp_desig_id=$('#filter_emp_desig_id').val();
  var emp_jlid=$('#emp_jlid').val();
  var emp_type=$('#emp_type').val();
  var emp_id=$('#emp_id').val();
  // var date_range=$('#defaultrange').val();
  // date_range=date_range.split(' - ');

  // var start_date=date_range[0].split('/');
  // start_date=start_date[2]+'-'+start_date[1]+'-'+start_date[0];
  // var end_date=date_range[1].split('/');
  // end_date=end_date[2]+'-'+end_date[1]+'-'+end_date[0];

  var start_date=$('#start_date').val();
  var end_date=$('#end_date').val();
  
  if(emp_depart_id>0 || emp_sdepart_id>0 || emp_desig_id>0 || emp_jlid>0 || emp_type>0 || emp_id>0 || start_date!="" || end_date!=""){
    window.open("{{URL::to('ot-application')}}/"+emp_depart_id+"/"+emp_sdepart_id+"/"+emp_desig_id+"/"+emp_jlid+"/"+emp_type+"/"+emp_id+"/"+start_date+"/"+end_date+"/search","_parent");
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
    window.open("{{URL::to('ot-application')}}/0/0/0/0/0/0/"+start_date+"/"+end_date+"/search","_parent");
  }else{
    $('#sms').html('<p class="alert alert-danger">Please Choose at least one Filter Option.</p>');
  }
}
</script>
@elseif(isset($title) && $title=="Pending OT Application List")
<script type="text/javascript">
function getFilter() {
  var emp_depart_id=$('#filter_emp_depart_id').val();
  var emp_sdepart_id=$('#filter_emp_sdepart_id').val();
  if(emp_sdepart_id=="null"){
    emp_sdepart_id=0;
  }
  var emp_desig_id=$('#filter_emp_desig_id').val();
  var emp_jlid=$('#emp_jlid').val();
  var emp_type=$('#emp_type').val();
  var emp_id=$('#emp_id').val();
  // var date_range=$('#defaultrange').val();
  // date_range=date_range.split(' - ');

  // var start_date=date_range[0].split('/');
  // start_date=start_date[2]+'-'+start_date[1]+'-'+start_date[0];
  // var end_date=date_range[1].split('/');
  // end_date=end_date[2]+'-'+end_date[1]+'-'+end_date[0];

  var start_date=$('#start_date').val();
  var end_date=$('#end_date').val();
  
  if(emp_depart_id>0 || emp_sdepart_id>0 || emp_desig_id>0 || emp_jlid>0 || emp_type>0 || emp_id>0 || start_date!="" || end_date!=""){
    window.open("{{URL::to('pending-ot-application')}}/"+emp_depart_id+"/"+emp_sdepart_id+"/"+emp_desig_id+"/"+emp_jlid+"/"+emp_type+"/"+emp_id+"/"+start_date+"/"+end_date+"/search","_parent");
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
    window.open("{{URL::to('pending-ot-application')}}/0/0/0/0/0/0/"+start_date+"/"+end_date+"/search","_parent");
  }else{
    $('#sms').html('<p class="alert alert-danger">Please Choose at least one Filter Option.</p>');
  }
}
</script>
@elseif(isset($title) && $title=="Assigned OT Application List")
<script type="text/javascript">
function getFilter() {
  var emp_depart_id=$('#filter_emp_depart_id').val();
  var emp_sdepart_id=$('#filter_emp_sdepart_id').val();
  if(emp_sdepart_id=="null"){
    emp_sdepart_id=0;
  }
  var emp_desig_id=$('#filter_emp_desig_id').val();
  var emp_jlid=$('#emp_jlid').val();
  var emp_type=$('#emp_type').val();
  var emp_id=$('#emp_id').val();
  // var date_range=$('#defaultrange').val();
  // date_range=date_range.split(' - ');

  // var start_date=date_range[0].split('/');
  // start_date=start_date[2]+'-'+start_date[1]+'-'+start_date[0];
  // var end_date=date_range[1].split('/');
  // end_date=end_date[2]+'-'+end_date[1]+'-'+end_date[0];

  var start_date=$('#start_date').val();
  var end_date=$('#end_date').val();
  
  if(emp_depart_id>0 || emp_sdepart_id>0 || emp_desig_id>0 || emp_jlid>0 || emp_type>0 || emp_id>0 || start_date!="" || end_date!=""){
    window.open("{{URL::to('assigned-ot-application')}}/"+emp_depart_id+"/"+emp_sdepart_id+"/"+emp_desig_id+"/"+emp_jlid+"/"+emp_type+"/"+emp_id+"/"+start_date+"/"+end_date+"/search","_parent");
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
    window.open("{{URL::to('assigned-ot-application')}}/0/0/0/0/0/0/"+start_date+"/"+end_date+"/search","_parent");
  }else{
    $('#sms').html('<p class="alert alert-danger">Please Choose at least one Filter Option.</p>');
  }
}
</script>
@endif

@if($id->suser_level=="1")
<script type="text/javascript">
getEmployee();
function getEmployee () {
  var emp_depart_id=$('#emp_depart_id').val();
  var emp_sdepart_id=$('#emp_sdepart_id').val();
  if(emp_sdepart_id==null){
    emp_sdepart_id=0;
  }
  var emp_desig_id=$('#emp_desig_id').val();
  if(emp_depart_id!="0"){
    $.ajax({
      url:"{{URL::to('getOTEmployee')}}/"+emp_depart_id+'/'+emp_sdepart_id+'/'+emp_desig_id,
      type:'GET',
      data:{},
      success:function(data) {
        $('#emp_empid').html(data).trigger("chosen:updated");
      }
    });
  }else{
    $('#emp_empid').html('<option value="0">Select</option>').trigger("chosen:updated");
  }
}
</script>
@elseif($id->suser_level=="4")
<script type="text/javascript">
getEmployee();
function getEmployee () {
    $.ajax({
      url:"{{URL::to('getOTEmployeeForLM')}}",
      type:'GET',
      data:{},
      success:function(data) {
        $('#emp_empid').html(data).trigger("chosen:updated");
      }
    });
}
</script>
@endif

<script type="text/javascript">
  getFilterEmployee ();

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
                  url: "{{URL::to('ot-application-aprove')}}",
                  type: 'POST',
                  data: data,
                  success:function(data) {
                    if(data=="1"){
                      location.reload();
                    }else if(data=="null"){
                      $.alert({
                        title:'',
                        content:'<hr><strong class="text-danger">Please Select One Or More OT Application To Approve</strong><hr>',
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
                  url: "{{URL::to('ot-application-deny')}}",
                  type: 'POST',
                  data: data,
                  success:function(data) {
                    if(data=="1"){
                      location.reload();
                    }else if(data=="null"){
                      $.alert({
                        title:'',
                        content:'<hr><strong class="text-danger">Please Select One Or More OT Application To Deny</strong><hr>',
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
                  url: "{{URL::to('ot-application-delete')}}",
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
                        content:'<hr><strong class="text-danger">Please Select One Or More OT Application To Delete</strong><hr>',
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

function getFilterEmployee () {
  var emp_depart_id=$('#filter_emp_depart_id').val();
  var emp_sdepart_id=$('#filter_emp_sdepart_id').val();
  if(emp_sdepart_id=="null"){
    emp_sdepart_id=0;
  }
  var emp_desig_id=$('#filter_emp_desig_id').val();
  var emp_jlid=$('#emp_jlid').val();
  var emp_type=$('#emp_type').val();
  $.ajax({
      url: "{{URL::to('getEmployee')}}/"+emp_depart_id+"/"+emp_sdepart_id+"/"+emp_desig_id+"/"+emp_jlid+"/"+emp_type,
      type:'GET',
      data:{},
      success:function(data) {
        $('#emp_id').html(data).trigger("chosen:updated");
      }
  });
}
</script>
@endsection