@extends('Admin.index')
@section('body')

@php
use App\Http\Controllers\BackEndCon\Controller;
use Illuminate\Support\Facades\Route;
$route=Route::getFacadeRoot()->current()->uri();
@endphp

@if(Controller::checkAppModulePriority('attendance','Add New')=="1")
@else
<script type="text/javascript">location="{{URL::to('/')}}"</script>
@endif

<script src="{{URL::to('/')}}/public/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
<link href="{{URL::to('/')}}/public/css/bootstrap-timepicker.min.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="{{URL::to('/')}}/public/css/datepicker.css" />


<div class="row">
        <div class="col-md-12">
           @include('error.msg')
          <!-- BEGIN EXAMPLE TABLE PORTLET-->
          <div class="portlet box grey-cascade">
            <div class="portlet-title">
              <div class="caption">
                <i class="fa fa-globe"></i>Add Attendace 
              </div>
              <div style="float:right">
                <a class="btn btn-xs btn-primary" href="{{URL::to('attendance')}}" style="margin-top:10px">Go Back </a>
              </div>
            
            </div>
            <div class="portlet-body">
              <div class="table-toolbar">
                
                <div class="row">
                  <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{URL::to('attendance-add-submit')}}" name="basic_validate" id="basic_validate" novalidate="novalidate">
                  {{ csrf_field() }}
                  <div class="form-group" style="text-align: center;border-bottom: 1px dotted #ccc">
                    <h4>Search Employee</h4>
                  </div>
                  <div class="form-group">

                    <label class="col-md-1 control-label" style="text-align: right;font-size:12px;padding: 5px 0px">
                      Department:
                    </label>

                    <div class="col-md-5">
                        <select name="emp_depart_id" id="filter_emp_depart_id" class="form-control chosen" required onchange="getFilterSubDepartment(),getEmployee();">
                          <option value="0">Select</option>
                          @if($id->suser_level=="1")
                             
                             @if(isset($department) && count($department)>0)
                              @foreach ($department as $depart)
                                @if(isset($emp_depart_id) && $emp_depart_id==$depart->depart_id)
                                  <option value="{{$depart->depart_id}}" selected="selected">{{$depart->depart_name}}</option>
                                @else
                                  <option value="{{$depart->depart_id}}">{{$depart->depart_name}}</option>
                                @endif
                              @endforeach
                            @endif
                            
                          @elseif($id->suser_level=="2")
                            @php echo Controller::getLineManagerDepartment($id->suser_empid); @endphp
                          @endif
                        </select>                  
                    </div>
                    
                    <label class="col-md-1 control-label" style="text-align: right;font-size:12px;padding: 5px 0px">
                      Sub-Department:
                    </label>

                    <div class="col-md-5">
                      <select name="emp_sdepart_id" id="filter_emp_sdepart_id" class="form-control chosen" required onchange="getEmployee();">
                      </select> 
                    </div>

                  </div>
                  
                  <div class="form-group">
                    <label class="col-md-1 control-label" style="text-align: right;font-size:12px;padding: 5px 0px" onchange="getEmployee();">
                      Designation:
                    </label>

                    <div class="col-md-3">
                       <select name="emp_desig_id" id="emp_desig_id" class="form-control chosen" required onchange="getEmployee();">
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

                    <label class="col-md-1 control-label" style="text-align: right;font-size:12px;padding: 5px 0px">
                      Job Location:
                    </label>

                    <div class="col-md-3">
                     <select name="emp_jlid" id="emp_jlid" class="form-control chosen " required onchange="getEmployee();">
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
                        <select name="emp_type" id="emp_type" class="form-control chosen" required onchange="getEmployee();">
                          <option value="0">Select</option>
                          @if(isset($types[0]))
                          @foreach ($types as $key => $type)
                            <option value="{{$type->id}}" @if(isset($emp_type) && $emp_type==$type->id) selected @endif>{{$type->name}}</option>
                          @endforeach
                          @endif
                        </select>                            
                    </div>
                  </div>
                  
                  <div class="form-group" style="text-align: center;border-bottom: 1px dotted #ccc">
                    <h4>Attendance Information</h4>
                  </div>

                  <div class="form-group">
                    
                    <label class="col-md-1 control-label" style="text-align: right;font-size:12px;padding: 5px 0px">
                      Employee :
                    </label>
                    <div class="col-md-2">
                       <select name="emp_id" id="emp_id" class="form-control chosen" required>
                            <option value="0">Select</option>
                        </select>     
                    </div>

                    <label class="col-md-1 control-label" style="text-align: right;font-size:12px;padding: 5px 0px">
                      Device :
                    </label>
                    <div class="col-md-2">
                       <select name="device" id="device" class="form-control chosen" required>
                            <option value="0">Select</option>
                            @if(isset($devices) && count($devices)>0)
                              @foreach ($devices as $device)
                                <option value="{{$device->id}}">{{$device->name}}</option>
                              @endforeach
                            @endif
                        </select>     
                    </div>

                    <label class="col-md-1 control-label" style="text-align: right;font-size:12px;padding: 5px 0px">
                      Date :
                    </label>
                    <div class="col-md-2">
                       <input type="text" name="check_time_date" id="check_time_date" data-date-format="yyyy-mm-dd" class="form-control" readonly="readonly" style="background: white" value="{{date('Y-m-d')}}">
                    </div>

                    <label class="col-md-1 control-label" style="text-align: right;font-size:12px;padding: 5px 0px">
                      Time :
                    </label>
                    <div class="col-md-2">
                       <input type="text" name="check_time_time" id="check_time_time" class="form-control" readonly="readonly" style="background: white">     
                    </div>

                  </div>

                  <div class="form-group">
                    <div class="col-md-3 col-md-offset-1">
                      <input type="submit" value="Submit" class="btn btn-md btn-success">
                    </div>
                  </div>

                  </form>
                </div>

              </div>
                  
              </div>
            </div>
              
            </div>
          </div>
          <!-- END EXAMPLE TABLE PORTLET-->
        </div>
      </div>
    </div>
  </div>

<script src="{{URL::to('/')}}/public/js/bootstrap-timepicker.min.js" type="text/javascript"></script>
<script src="{{URL::to('/')}}/public/js/bootstrap-datepicker.js"></script> 

<script type="text/javascript">
  $('#check_time_date').datepicker();
  $('#check_time_time').timepicker({   
   minuteStep: 1,
   disableFocus: true,
   template: 'dropdown',
   showMeridian:false
  });
</script>

<script type="text/javascript">
getFilterSubDepartment();
getEmployee();
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

function getEmployee() {
  var emp_depart_id=$('#filter_emp_depart_id').val();
  var emp_sdepart_id=$('#filter_emp_sdepart_id').val();
  if(emp_sdepart_id=="null"){
    emp_sdepart_id=0;
  }
  var emp_desig_id=$('#emp_desig_id').val();
  var emp_jlid=$('#emp_jlid').val();
  var emp_type=$('#emp_type').val();
  $.ajax({
    url: "{{URL::to('getEmployee')}}/"+emp_depart_id+"/"+emp_sdepart_id+"/"+emp_desig_id+"/"+emp_jlid+"/"+emp_type,
    type: 'GET',
    data: {},
    success:function(data) {
      $('#emp_id').html(data).trigger("chosen:updated");
    }
  });
}
</script>

@endsection