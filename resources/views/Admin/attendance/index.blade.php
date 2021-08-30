@extends('Admin.index')
@section('body')

@php
use App\Http\Controllers\BackEndCon\Controller as C;
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
        <select name="emp_depart_id" id="filter_emp_depart_id" class="form-control chosen" required onchange="getFilterSubDepartment(),getEmployee();">
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
      <select name="emp_sdepart_id" id="filter_emp_sdepart_id" class="form-control chosen" required onchange="getEmployee();">
      </select> 
    </div>



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

  </div>
  
  <div class="form-group">

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
    <span id="hidden_table_title" style="display: none;">Attendance List @if(isset($start_date) && isset($end_date))( {{$start_date}} to {{$end_date}} )
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
    <!-- <div class="col-md-8">
      End Date
        <input type="date" class="form-control" id="end_date" style="background: white;padding: 0px 0px 0px 10px">
    </div> -->
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
                    <span class="caption-subject bold uppercase">Attendence List @if(isset($start_date) && isset($end_date))( {{$start_date}} to {{$end_date}} )@endif @if($flag==1)
                      ({{ $empl_location->jl_name }})
                     @endif</span>
                </div>
                <div class="col-md-6"><p id="confirm_msg"></p></div>
                <div class="actions">
                  <!-- <button class="btn btn-success">Print</button> -->
                  @if($id->suser_level=="1")
                  <a href="{{ route('late-application') }}" class="btn btn-success" target="_blank">Sample Late Application</a>
                  @endif
                  <a href="{{ route('emp-late-application', $id->suser_empid) }}" class="btn btn-success" target="_blank">Your Late Application</a>
                @if(!isset($start_date) && !isset($end_date))
                <a href="{{ route('attendance-report-print') }}" type="button" class="btn btn-success" target="_blank">Print</a>
                @else
                 @if($id->suser_level=="1")
                 <input type="hidden" name="" id="empl" value="{{$emp_id}}">
                <a type="button" class="btn btn-success" onclick="getFilter_print();">Print</a>
                @else
                <a type="button" class="btn btn-success" onclick="getFilterStaff_print();">Print</a>
                @endif
                @endif


                  @if((C::checkAppModulePriority($route,'Add New')=="1") or (C::checkAppModulePriority($route,'Edit')=="1") or (C::checkAppModulePriority($route,'Delete')=="1"))
                    <div class="btn-group">
                        <a class="btn purple btn-outline btn-circle" href="javascript:;" data-toggle="dropdown">
                            <i class="fa fa-list"></i>
                            <span> Options </span>
                            <i class="fa fa-angle-down"></i>
                        </a>
                        <ul class="dropdown-menu" id="sample_3_tools">
                          @if(C::checkAppModulePriority($route,'Add New')=="1")
                            <li>                                 
                                <a href="{{URL::to('attendance/add')}}"><i class="fa fa-plus"></i>Add New</a>
                            </li>
                          @endif

                          @if(C::checkAppModulePriority($route,'Edit')=="1")
                            <li>                                 
                                <a onclick="return Edit();"><i class="fa fa-pencil"></i>Edit</a>
                            </li>
                          @endif

                          @if(C::checkAppModulePriority($route,'Delete')=="1")
                            <li>                                 
                                <a  onclick="return Delete();"><i class="icon-trash"></i> Delete</a>
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
            <div class="portlet-body">
            <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{URL::to('')}}" name="basic_validate" id="data_form">
            {{ csrf_field() }}
              <table class="table table-striped table-bordered table-hover table-checkable order-column" id="sample_3">
                      <thead>
                        <tr>
                          <th id="hidden"></th>
                          <th>SL</th>
                          <th>Employee ID</th>
                          <!-- @if($id->suser_level=="1")
                          <th hidden>Face ID</th>
                          @endif -->
                          <th>Employee Name</th>
                          <th>Date</th>
                          <th>Shift</th>
                          <th>Entry Time</th>
                          <th>Exit Time</th>
                          <th>Grace Time (Min)</th>
                          <th>Late Entry</th>
                          <th>Early Exit</th>
                          <th>TWH</th>
                         <!--  <th hidden>Night</th> -->
                          <th>Late</th>
                          <th>Present</th>
                          <th>Absent</th>
                          <th>Leave</th>
                          <th>No Exit Found</th>
                         <!--  <th>Generated at</th> -->
                        </tr>
                      </thead>
              
                      <tbody>
                      @if(isset($attendances[0]))
                      @foreach ($attendances as $key => $attendance)
                      @if($attendance->employee->emp_empid != 'Atech-1001')
                        <tr>
                          <td id="hidden"></td>
                          <td>{{$key+1}}</td>
                          <td>{{$attendance->employee->emp_empid}}</td>
                         <!--  @if($id->suser_level=="1")
                          <td hidden>{{$attendance->employee->emp_machineid}}</td>
                          @endif -->
                          <td>{{$attendance->employee->emp_name}}</td>
                          <td>{{date('d-m-Y', strtotime($attendance->date))}}</td>
                          <td>
                            @if($attendance->shift)
                            {{$attendance->shift->shift_stime}} to {{$attendance->shift->shift_etime}}
                            @endif
                          </td>
                          <td>
                            @if(count(explode(',',$attendance->entry_time))>1)
                            {!! C::attendanceStatusText($attendance->entry_time) !!}
                            @elseif($attendance->entry_time!="" && $attendance->entry_time!="00:00:00")
                            {{date('g:i a',strtotime($attendance->entry_time))}}
                            @endif
                          </td>
                          <td>
                            @if(count(explode(',',$attendance->exit_time))>1)
                            {!! C::attendanceStatusText($attendance->exit_time) !!}
                            @elseif($attendance->exit_time!="" && $attendance->exit_time!="00:00:00")
                            {{date('g:i a',strtotime($attendance->exit_time))}}
                            @endif
                          </td>
                          <td>
                            @php
                              $grace=App\Setup::first();
                            @endphp
                            {{ $grace->grace_time }}
                          </td>
                          <td>{{$attendance->late_entry}}</td>
                          <td>{{$attendance->early_exit}}</td>
                          <td>{{$attendance->twh}}</td>
                          <!-- <td hidden>{{$attendance->night}}</td> -->
                           <td>

                            {!! C::attendanceLate($attendance->status) !!}
                          </td>
                          <td>
                            {!! C::attendancePresent($attendance->status) !!}
                          </td>
                          <td>
                            {!! C::attendanceAbsent($attendance->status) !!}
                          </td>
                          <td>
                            {!! C::attendanceLeave($attendance->status) !!}
                          </td>
                          <td>
                            {!! C::attendanceNoexit($attendance->status) !!}
                          </td>
                         <!--  <td>
                            {{date('Y-m-d g:i a',strtotime($attendance->generated_at))}}
                          </td> -->
                        </tr>
                        @endif
                      @endforeach
                      @endif
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

function getFilter() {
  var emp_depart_id=$('#filter_emp_depart_id').val();
  var emp_sdepart_id=$('#filter_emp_sdepart_id').val();
  if(emp_sdepart_id=="null"){
    emp_sdepart_id=0;
  }
  var emp_desig_id=$('#emp_desig_id').val();
  var emp_jlid=$('#emp_jlid').val();
  var emp_type=$('#emp_type').val();
  var emp_id=$('#emp_id').val();
  var date_range=$('#defaultrange').val();
  // date_range=date_range.split(' - ');



  // var start_date=date_range[0].split('/');
  // start_date=start_date[2]+'-'+start_date[1]+'-'+start_date[0];
  // var end_date=date_range[1].split('/');
  // end_date=end_date[2]+'-'+end_date[1]+'-'+end_date[0];


  var start_date=$('#start_date').val();
  var end_date=$('#end_date').val();

  

  // alert(start_date);
  
  if(emp_depart_id>0 || emp_sdepart_id>0 || emp_desig_id>0 || emp_jlid>0 || emp_type>0 || emp_id>0 || start_date!="" || end_date!=""){
    window.open("{{URL::to('attendance')}}/"+emp_depart_id+"/"+emp_sdepart_id+"/"+emp_desig_id+"/"+emp_jlid+"/"+emp_type+"/"+emp_id+"/"+start_date+"/"+end_date+"/search","_parent");
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
  var emp_id=$('#empl').val();
  var date_range=$('#defaultrange').val();
  // date_range=date_range.split(' - ');



  // var start_date=date_range[0].split('/');
  // start_date=start_date[2]+'-'+start_date[1]+'-'+start_date[0];
  // var end_date=date_range[1].split('/');
  // end_date=end_date[2]+'-'+end_date[1]+'-'+end_date[0];


  var start_date=$('#start_date').val();
  var end_date=$('#end_date').val();

  

  // alert(start_date);
  
  if(emp_depart_id>0 || emp_sdepart_id>0 || emp_desig_id>0 || emp_jlid>0 || emp_type>0 || emp_id>0 || start_date!="" || end_date!=""){
    window.open("{{URL::to('attendance-print')}}/"+emp_depart_id+"/"+emp_sdepart_id+"/"+emp_desig_id+"/"+emp_jlid+"/"+emp_type+"/"+emp_id+"/"+start_date+"/"+end_date+"/search","_parent");
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
    window.open("{{URL::to('attendance')}}/0/0/0/0/0/0/"+start_date+"/"+end_date+"/search","_parent");
  }else{
    $('#sms').html('<p class="alert alert-danger">Please Choose at least one Filter Option.</p>');
  }
}

function getFilterStaff_print() {
  // var date_range=$('#defaultrange').val();
  // date_range=date_range.split(' - ');

  // var start_date=date_range[0].split('/');
  // start_date=start_date[2]+'-'+start_date[1]+'-'+start_date[0];
  // var end_date=date_range[1].split('/');
  // end_date=end_date[2]+'-'+end_date[1]+'-'+end_date[0];


  var start_date=$('#start_date').val();
  var end_date=$('#end_date').val();
  
  if(start_date!="" || end_date!=""){
    window.open("{{URL::to('attendance-print')}}/0/0/0/0/0/0/"+start_date+"/"+end_date+"/search","_parent");
  }else{
    $('#sms').html('<p class="alert alert-danger">Please Choose at least one Filter Option.</p>');
  }
}

function Delete() {
  var confirm_msg = confirm("Are  You Confirm To Delete ?");
  if (confirm_msg){
      var data=$('#data_form').serializeArray();
      $.ajax({
        headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
        url: "{{URL::to('attendance-delete')}}",
        type: 'POST',
        data: data,
        success:function(data) {
          if(data=="1"){
            location.reload();
          }else if(data=="0"){
            $('#confirm_msg').html('<b class="alert alert-danger" style="padding:0px 10px 0px 10px">Something Went Wrong!!</b>');
          }else if(data=="null"){
            $('#confirm_msg').html('<b class="alert alert-danger" style="padding:0px 10px 0px 10px">Please Select One or More Attendence To Delete</b>');
          }
        }
      });
  }else{
    return false;
  }
}

function Edit() {
  var data=$('#data_form').serializeArray();
  $.ajax({
    headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
    url: "{{URL::to('attendance-edit')}}",
    type: 'POST',
    data: data,
    success:function(data) {
      if(data=="null"){
        $('#confirm_msg').html('<b class="alert alert-danger" style="padding:0px 10px 0px 10px">Please Select An Attendence To Edit</b>');
      }else if(data=="max"){
        $('#confirm_msg').html('<b class="alert alert-danger" style="padding:0px 10px 0px 10px">Please Select Maximum Of One Attendence To Edit</b>');
      }else{
        window.open("{{URL::to('attendance')}}/"+data+"/edit",'_parent');
      }
    }
  });
}


function getAttendanceSummary(att_empid,total_working_hour) {
    $.dialog({
        title: 'Entry & Exit Summary',
        content: "url:{{URL::to('getAttendanceSummary')}}/"+att_empid+"/"+total_working_hour,
        animation: 'scale',
        columnClass: 'medium',
        closeAnimation: 'scale',
        backgroundDismiss: true,
    });
}

function getAttendanceSummarySearch(att_empid,total_working_hour,date) {
    $.dialog({
        title: 'Entry & Exit Summary',
        content: "url:{{URL::to('getAttendanceSummarySearch')}}/"+att_empid+"/"+total_working_hour+"/"+date,
        animation: 'scale',
        columnClass: 'medium',
        closeAnimation: 'scale',
        backgroundDismiss: true,
    });
}

</script>
@endsection