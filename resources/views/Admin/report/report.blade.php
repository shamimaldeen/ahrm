@extends('Admin.index')
@section('body')

@php
use App\Http\Controllers\BackEndCon\Controller;
use Illuminate\Support\Facades\Route;
$route=Route::getFacadeRoot()->current()->uri();
if($route!="report-submit"){
if(Controller::checkAppModulePriority($route,$link)!="1"){
@endphp
<script type="text/javascript">location="{{URL::to('report')}}"</script>
@php
}
}
@endphp

<script src="{{URL::to('/')}}/public/assets/global/plugins/jquery.min.js"></script>

<div class="row">

    <div class="col-md-12">

       @include('error.msg')
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class="icon-settings font-dark"></i>
                    <span class="caption-subject bold uppercase"> {{$title}}</span>

                </div>
               <!--  <a hidden="" href="{{URL::to('report')}}" class="btn btn-md btn-primary btn-circle" style="float: right">Back To Report</a> -->
            </div>
            <div class="portlet-body">
              <!-- filter start -->
              <div class="row" style='padding: 30px; background-color: #f7f7f7; margin: 0px;'>

                <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{URL::to('report-submit')}}" name="basic_validate" id="basic_validate" novalidate="novalidate">
                {{ csrf_field() }}

                <div class="form-group">
                  @if($title!='Exited Employee Archive Report')
                  <label class="col-md-1 control-label" style="text-align: right;font-size:12px;padding: 5px 0px">
                    Department:
                  </label>

                  <div class="col-md-2">
                      <select name="emp_depart_id" id="filter_emp_depart_id" class="form-control chosen" required onchange="getFilterSubDepartment(),getEmployee();">
                        <option value="0">Select</option>
                           @if(isset($department) && count($department)>0)
                            @foreach ($department as $depart)
                              @if(isset($var['emp_depart_id']) && $var['emp_depart_id']==$depart->depart_id)
                                <option value="{{$depart->depart_id}}" selected="selected">{{$depart->depart_name}}</option>
                              @else
                                <option value="{{$depart->depart_id}}">{{$depart->depart_name}}</option>
                              @endif
                            @endforeach
                          @endif
                      </select>
                  </div>
                  @endif

                  @if($title!='Exited Employee Archive Report')
                  <label class="col-md-1 control-label" style="text-align: right;font-size:12px;padding: 5px 0px">
                    Sub-Department:
                  </label>

                  <div class="col-md-2">
                    <select name="emp_sdepart_id" id="filter_emp_sdepart_id" class="form-control chosen" required onchange="getEmployee();">
                    </select>
                  </div>
                  @endif


                  @if($title!='Exited Employee Archive Report')
                  <label class="col-md-1 control-label" style="text-align: right;font-size:12px;padding: 5px 0px" onchange="getEmployee();">
                    Designation:
                  </label>

                  <div class="col-md-2">
                     <select name="emp_desig_id" id="emp_desig_id" class="form-control chosen" required onchange="getEmployee();">
                      <option value="0">Select</option>
                        @if(isset($designation) && count($designation)>0)
                          @foreach ($designation as $desig)
                            @if(isset($var['emp_desig_id']) && $var['emp_desig_id']==$desig->desig_id)
                              <option value="{{$desig->desig_id}}" selected="selected">{{$desig->desig_name}}</option>
                            @else
                              <option value="{{$desig->desig_id}}">{{$desig->desig_name}}</option>
                            @endif
                          @endforeach
                        @endif
                      </select>
                  </div>
                  @endif

                  @if($title!='Exited Employee Archive Report')
                  <label class="col-md-1 control-label" style="text-align: right;font-size:12px;padding: 5px 0px">
                    Job Location:
                  </label>

                  <div class="col-md-2">
                   <select name="emp_jlid" id="emp_jlid" class="form-control chosen " required onchange="getEmployee();">
                    <option value="0">Select</option>
                        @if(isset($joblocation) && count($joblocation)>0)
                          @foreach ($joblocation as $jl)
                            @if(isset($var['emp_jlid']) && $var['emp_jlid']==$jl->jl_id)
                              <option value="{{$jl->jl_id}}" selected="selected">{{$jl->jl_name}}</option>
                            @else
                              <option value="{{$jl->jl_id}}">{{$jl->jl_name}}</option>
                            @endif
                          @endforeach
                        @endif
                      </select>
                  </div>
                  @endif

                </div>

                <div class="form-group">
                  @if($title!='Exited Employee Archive Report')
                  <label class="col-md-1 control-label" style="text-align: right;font-size:12px;padding: 5px 0px">
                    Employee Type:
                  </label>

                  <div class="col-md-2">
                      <select name="emp_type" id="emp_type" class="form-control chosen" required onchange="getEmployee();">
                        <option value="0">Select</option>
                        @if(isset($types[0]))
                        @foreach ($types as $key => $type)
                          <option value="{{$type->id}}" @if(isset($var['emp_type']) && $var['emp_type']==$type->id) selected @endif>{{$type->name}}</option>
                        @endforeach
                        @endif
                      </select>
                  </div>
                  @endif

                  @if($title!='Exited Employee Archive Report')
                  <label class="col-md-1 control-label" style="text-align: right;font-size:12px;padding: 5px 0px">
                    Employee ID :
                  </label>
                  <div class="col-md-2">
                      <input type="text" class="form-control" id="emp_empid" onkeyup="searchEmployee();" onchange="searchEmployee();">
                  </div>
                  @endif

                   @if($title!='Exited Employee Archive Report')
                  <label class="col-md-1 control-label" style="text-align: right;font-size:12px;padding: 5px 0px">
                    Employee :
                  </label>
                  <div class="col-md-2">
                     <select name="emp_id" id="emp_id" class="form-control chosen" required>
                          <option value="0">Select</option>
                      </select>
                  </div>
                  @endif

                  @if($title=='Exited Employee Archive Report')
                   <label class="col-md-1 control-label" style="text-align: right;font-size:12px;padding: 5px 0px">
                    Exit Type :
                  </label>
                  <div class="col-md-2">
                     <select name="status_condition" id="status_condition" class="form-control chosen" required>
                      @if(!isset($condition))
                       <option value="0">Select</option>
                          <option value="1">Resign</option>
                          <option value="2">Suspend</option>

                     @elseif($condition==0)
                          <option value="0" selected="">Select</option>
                          <option value="1">Resign</option>
                          <option value="2">Suspend</option>
                      @elseif($condition==1)
                       <option value="0">Select</option>
                          <option value="1" selected="">Resign</option>
                          <option value="2">Suspend</option>
                           @elseif($condition==2)
                            <option value="0">Select</option>
                          <option value="1">Resign</option>
                          <option value="2" selected="">Suspend</option>
                      @endif

                      </select>
                  </div>
                  @endif

                   @if($title!='Exited Employee Archive Report')
                  <label class="col-md-1 control-label" style="text-align: right;font-size:12px;padding: 5px 0px">
                    Date Range:
                  </label>
                  @if(!isset($start_date) && !isset($end_date))

                  <div class="col-md-2">
                      Start Date
                      <input type="date" class="form-control" id="start_date" name="start_date" style="background: white;padding: 0px 0px 0px 10px" required=""><br>
                       End Date
                      <input type="date" class="form-control" id="end_date" name="end_date" style="background: white;padding: 0px 0px 0px 10px" required="">

                  </div><br><br><br>
                  @endif
                   @if(isset($start_date) && isset($end_date))

                  <div class="col-md-2">
                    Start Date
                      <input type="date" class="form-control" id="start_date" name="start_date" style="background: white;padding: 0px 0px 0px 10px" required="" value="{{ $start_date }}"><br>
                       End Date
                      <input type="date" class="form-control" id="end_date" name="end_date" style="background: white;padding: 0px 0px 0px 10px" required="" value="{{ $end_date }}">

                  </div><br><br><br>
                  @endif

                </div>
                @endif

                <div class="form-group" style="padding: 0px;margin: 0px">
                  <div class="col-md-6 col-md-offset-3">
                    <input type="hidden" name="link" value="{{$link}}">
                    <input type="submit" class="btn btn-success btn-block" value="See Report">
                  </div>
                </div>


                </form>
              </div>
              <!-- filter end -->

              {!! $report !!}

            </div>
          </div>
        </div>
      </div>
    </div>

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
    url: "{{URL::to('reportEmployee')}}/"+emp_depart_id+"/"+emp_sdepart_id+"/"+emp_desig_id+"/"+emp_jlid+"/"+emp_type,
    type: 'GET',
    data: {},
    success:function(data) {

      $('#emp_id').html(data).trigger("chosen:updated");
    }
  });
}

function searchEmployee() {
  var emp_depart_id=$('#filter_emp_depart_id').val();
  var emp_sdepart_id=$('#filter_emp_sdepart_id').val();
  if(emp_sdepart_id=="null"){
    emp_sdepart_id=0;
  }
  var emp_desig_id=$('#emp_desig_id').val();
  var emp_jlid=$('#emp_jlid').val();
  var emp_type=$('#emp_type').val();
  var emp_empid=$('#emp_empid').val();
  if(emp_empid!="" && emp_empid.length>5){
    $.ajax({
      url: "{{URL::to('searchEmployee')}}/"+emp_depart_id+"/"+emp_sdepart_id+"/"+emp_desig_id+"/"+emp_jlid+"/"+emp_type+"/"+emp_empid,
      type: 'GET',
      data: {},
      success:function(data) {
        if(data!="0"){
          $('#emp_id').html(data).trigger("chosen:updated");
        }else{
          getEmployee();
        }
      }
    });
  }else{
    getEmployee();
  }
}
</script>
@endsection