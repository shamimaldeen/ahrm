@extends('Admin.index')
@section('body')

@php
use App\Http\Controllers\BackEndCon\Controller;
use Illuminate\Support\Facades\Route;
$route=Route::getFacadeRoot()->current()->uri();
@endphp

<script src="{{URL::to('/')}}/public/assets/global/plugins/jquery.min.js"></script>

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

    <div class="col-md-5">
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

    <div class="col-md-5">
        <select name="emp_type" id="emp_type" class="form-control chosen" required>
              <option value="0">Select</option>
              @if(isset($emp_type) && $emp_type=='1')
                <option value="1" selected="selected"> Parmanent </option>
                <option value="2"> Contractual </option>
              @elseif(isset($emp_type) && $emp_type=='2')
                <option value="1"> Parmanent </option>
                <option value="2" selected="selected"> Contractual </option>
              @else
                <option value="1"> Parmanent </option>
                <option value="2"> Contractual </option>
              @endif
          
        </select>                            
    </div>

  </div>
@endif

  <div class="form-group">
     <span id="hidden_table_title" style="display: none;">Leave Data @if(isset($start_date) && isset($end_date))( {{$start_date}} to {{$end_date}} )
      @endif</span>
    <label class="col-md-1 control-label" style="text-align: right;font-size:12px;padding: 0px 0px">
      Leave Application Date:
    </label>
    <div class="col-md-8">
        <input type="text" class="form-control" id="defaultrange" readonly="yes" style="background: white;padding: 0px 0px 0px 10px">
    </div>
    
    <div class="col-md-3">
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
                    <span class="caption-subject bold uppercase">Leave Taken Status @if(isset($start_date) && isset($end_date))( {{$start_date}} to {{$end_date}} )@endif</span>
                </div>

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
               <table class="table table-striped table-bordered table-hover" id="sample_3">

                        <thead>
                        <tr>
                          <th>SL</th>
                          <th>Employee ID</th>
                          <th>Employee Name</th>
                          <th>Department</th>
                          <th>Sub-Department</th>
                          @if($leave_type)
                            @foreach ($leave_type as $lt)
                              <th>{{$lt->li_name}} Taken</th>
                            @endforeach
                          @endif
                        </tr>
                      </thead>
                      <tbody>
                        {!! $data !!}
                      </tbody>
                    </table>
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
  var date_range=$('#defaultrange').val();
  date_range=date_range.split(' - ');
  var start_date=date_range[0].split('/');
  start_date=start_date[2]+'-'+start_date[1]+'-'+start_date[0];
  var end_date=date_range[1].split('/');
  end_date=end_date[2]+'-'+end_date[1]+'-'+end_date[0];
  
  if(emp_depart_id>0 || emp_sdepart_id>0 || emp_desig_id>0 || emp_jlid>0 || emp_type>0 || start_date!="" || end_date!=""){
    window.open("{{URL::to('leave-taken-status')}}/"+emp_depart_id+"/"+emp_sdepart_id+"/"+emp_desig_id+"/"+emp_jlid+"/"+emp_type+"/"+start_date+"/"+end_date+"/search","_parent");
  }else{
    $('#sms').html('<p class="alert alert-danger">Please Choose at least one Filter Option.</p>');
  }
}

function getFilterStaff() {
  var date_range=$('#defaultrange').val();
  date_range=date_range.split(' - ');
  var start_date=date_range[0].split('/');
  start_date=start_date[2]+'-'+start_date[1]+'-'+start_date[0];
  var end_date=date_range[1].split('/');
  end_date=end_date[2]+'-'+end_date[1]+'-'+end_date[0];
  
  if(start_date!="" || end_date!=""){
    window.open("{{URL::to('leave-taken-status')}}/0/0/0/0/0/"+start_date+"/"+end_date+"/search","_parent");
  }else{
    $('#sms').html('<p class="alert alert-danger">Please Choose at least one Filter Option.</p>');
  }
}
</script>
@endsection