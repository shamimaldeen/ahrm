@extends('Admin.index')
@section('body')

@php
use App\Http\Controllers\BackEndCon\Controller;
use Illuminate\Support\Facades\Route;
$route=Route::getFacadeRoot()->current()->uri();
@endphp

<script src="{{URL::to('/')}}/public/assets/global/plugins/jquery.min.js"></script>

@if($id->suser_level=="1")

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
     <select name="emp_jlid" id="emp_jlid" class="form-control chosen " required>
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
    <!--<label class="col-md-1 control-label" style="text-align: right;font-size:12px;padding: 5px 0px">
      Joining Date:
    </label>
    <div class="col-md-3">
        <input type="text" class="form-control" id="defaultrange" readonly="yes" style="background: white;padding: 0px 0px 0px 10px">
    </div>-->

  </div>

  <div class="form-group">
    <div class="col-md-6 col-md-offset-3">
     <span id="hidden_table_title" style="display: none;">Shift Wise Employee List @if(isset($start_date) && isset($end_date)) Joining Date From {{$start_date}} to {{$end_date}} @endif</span>
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
                    <span class="caption-subject bold uppercase">Shift Wise Employee List @if(isset($start_date) && isset($end_date))( {{$start_date}} to {{$end_date}} )@endif</span>
                </div>

                <div class="col-md-6"><p id="confirm_msg"></p></div>
                <div class="actions">
                    
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
              <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{URL::to('')}}" id="data_form">
              {{ csrf_field() }}
               <table class="table table-striped table-bordered table-hover" id="sample_3">

                   <thead>
                    <tr>
                      <th>SL</th>
                      <th>Employee ID</th>
                      @if($id->suser_level=="1")
                      <th>Face ID</th>
                      @endif
                      <th>Employee Name</th>
                      <th>Department</th>
                      <th>Shift Type</th>
                      <th>Shift Time</th>
                      <th hidden="">Action</th>
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
<script type="text/javascript">
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
    window.open("{{URL::to('shift-wise-employee-list')}}/"+emp_depart_id+"/"+emp_sdepart_id+"/"+emp_desig_id+"/"+emp_jlid+"/"+emp_type+"/search","_parent");
  }else{
    $('#sms').html('<p class="alert alert-danger">Please Choose at least one Filter Option.</p>');
  }
}

function ShiftUpdate(emp_id) {
  $.dialog({
        title: 'Shift Update',
        content: "url:{{URL::to('getShiftUpdate')}}/"+emp_id,
        animation: 'scale',
        columnClass: 'medium',
        closeAnimation: 'scale',
        backgroundDismiss: true,
    });
}

function UpdateShift() {
  var form=$('#update_shift_form');
  $('#update_button').hide();
  $('#update_text').show();
  $.ajax({
    url: form.attr('action'),
    type: form.attr('method'),
    dataType: 'json',
    data: form.serializeArray(),
  })
  .done(function(response) {
    var msg='';
    if(response.success>0){
      msg+='<hr><strong class="text-success">'+response.success_msg+'</strong><hr>';
    }

    if(response.error>0){
      msg+='<strong class="text-danger">'+response.error_msg+'</strong><hr>';
    }

    if(response.success>0 || response.error>0){
      $.alert({
          title: 'Success!',
          content: msg,
          type:'green',
      });
    }else{
      $.alert({
          title: 'Whoops',
          content: '<strong class="text-danger">Something Went Wrong!</strong>',
          type:'red',
      });
    }
    $('#update_text').hide();
    $('#update_button').show();
  })
  .fail(function() {
    $.alert({
        title: 'Whoops',
        content: '<strong class="text-danger">Something Went Wrong!</strong>',
        type:'red',
    });
    $('#update_text').hide();
    $('#update_button').show();
  });
}

function  getShift() {
  var emp_workhr=$('#emp_workhr').val();
  var emp_shiftid=$('#emp_shiftid').val();
  
  if(emp_workhr!="0"){
    $.ajax({
      url:"{{URL::to('getShiftForEdit')}}/"+emp_workhr+"/"+emp_shiftid,
      type:'GET',
      data:{},
      success:function(data) {
        $('#emp_shiftid').html(data).trigger("chosen:updated");
      }
    });
  }else{
    $('#emp_shiftid').html('<option value="0">Select</option>').trigger("chosen:updated");
  }
}
</script>
@endsection