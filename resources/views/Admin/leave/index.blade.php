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

 <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{route('leave-type-filter')}}">
     @csrf
               
 

  <div class="form-group">
    
   <label class="col-md-1 control-label" style="text-align: right;font-size:12px;padding: 5px 0px">
      Leave Type:
    </label>
     @php
          $x = App\LeaveType::
          where('li_id', $leave_type->li_id)
           ->first();
     @endphp

      <div class="col-md-3">

        <select name="leave_type_id" id="leave_type" class="form-control chosen" required>
         
          @if(isset($all_leave[0]))
          @if($flag==0)
          <option value="1">{{$all_leave[0]->li_name}}</option>
          @elseif($flag==1)
          <option value="{{ $x->li_id }}">{{$x->li_name}}</option>
          @endif
          @foreach ($all_leave as $key)
            <option value="{{$key->li_id}}">{{$key->li_name}}</option>
          @endforeach
          @endif
        </select>                            
    </div>
    

    
    
   
    <div class="col-md-4">
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
                    <span class="caption-subject bold uppercase">{{ $x->li_name }} Quota Status @if(isset($start_date) && isset($end_date))( {{$start_date}} to {{$end_date}} )@endif</span>
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
               <table class="table table-striped table-bordered table-hover nowrap" id="sample_3">

                        <thead>
                        <tr>
                          <th>SL</th>
                          <th>Employee ID</th>
                          <th>Employee Name</th>
                          <th>Department</th>
                          <th hidden="">Sub-Department</th>
                          @php
                            $x = App\LeaveType::
                            where('li_id', $leave_type->li_id)
                            ->first();
                          @endphp
                           

                              <th>Qouta</th>
                              <th>Taken</th>
                              <th>Remaining</th>
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
  // var date_range=$('#defaultrange').val();
  // date_range=date_range.split(' - ');
  // var start_date=date_range[0].split('/');
  // start_date=start_date[2]+'-'+start_date[1]+'-'+start_date[0];
  // var end_date=date_range[1].split('/');
  // end_date=end_date[2]+'-'+end_date[1]+'-'+end_date[0];

  var start_date=$('#start_date').val();
  var end_date=$('#end_date').val();
  
  if(emp_depart_id>0 || emp_sdepart_id>0 || emp_desig_id>0 || emp_jlid>0 || emp_type>0 || start_date!="" || end_date!=""){
    window.open("{{URL::to('leave-data')}}/"+emp_depart_id+"/"+emp_sdepart_id+"/"+emp_desig_id+"/"+emp_jlid+"/"+emp_type+"/"+start_date+"/"+end_date+"/search","_parent");
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
    window.open("{{URL::to('leave-data')}}/0/0/0/0/0/"+start_date+"/"+end_date+"/search","_parent");
  }else{
    $('#sms').html('<p class="alert alert-danger">Please Choose at least one Filter Option.</p>');
  }
}
</script>
@endsection