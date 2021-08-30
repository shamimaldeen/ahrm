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

  <form action="{{ route('date-wise-entry-submit') }}" method="POST">
      @csrf
               
  @if($id->suser_level=="1")
  <div class="form-group">

  </div>
  
  <div class="form-group">

         

    
    
    

 
@endif

  
    <span id="hidden_table_title" style="display: none;">Attendance List </span>
      
   
    
      
   
        

     
    <div class="col-md-12">
    
         @if(!isset($s_date))
         <div class="col-md-12">
        <label class="col-md-1 control-label" style="text-align: right;font-size:12px;padding: 5px 0px">
         Start Date:
      </label>
      <div class="col-md-3">
        <input type="date" class="form-control" name="start_date" style="background: white;padding: 0px 0px 0px 10px" required="" value="{{ date('Y-m-d') }}"></div>
         <label class="col-md-1 control-label" style="text-align: right;font-size:12px;padding: 5px 0px">
         End Date:
      </label>
        <div class="col-md-3">
            <input type="date" class="form-control" name="end_date" style="background: white;padding: 0px 0px 0px 10px" required="" value="{{ date('Y-m-d') }}"></div>

      @if(!isset($emp))
    <div class="col-md-3">
       <select name="emp_id" class="form-control chosen" required="">
           <!--  <option value="0">Select</option> -->
            @foreach($employee as $row)
              <option value="{{$row->emp_machineid}}">{{$row->emp_name}}</option>
            @endforeach
        </select>     
    </div>
    @elseif(isset($emp))
     <div class="col-md-3">
       <select name="emp_id" class="form-control chosen" required="">
            <option value="{{ $emp }}">{{ $emp_nam->emp_name }}</option>
            @foreach($employee as $row)
              <option value="{{$row->emp_machineid}}">{{$row->emp_name}}</option>
            @endforeach
        </select>     
    </div><br><br><br>

    @endif

    </div><br><br><br>
        @elseif(isset($s_date))
        <div class="col-md-12">
        <label class="col-md-1 control-label" style="text-align: right;font-size:12px;padding: 5px 0px">
         Start Date:
      </label>
      <div class="col-md-3">
        <input type="date" class="form-control" name="start_date" style="background: white;padding: 0px 0px 0px 10px" required="" value="{{ $s_date }}"></div>
         <label class="col-md-1 control-label" style="text-align: right;font-size:12px;padding: 5px 0px">
         End Date:
      </label>
        <div class="col-md-3">
            <input type="date" class="form-control" name="end_date" style="background: white;padding: 0px 0px 0px 10px" required="" value="{{ $e_date }}"></div>


      <label class="col-md-1 control-label" style="text-align: right;font-size:12px;padding: 5px 0px">
      Employee :
    </label>

    @if(!isset($emp))
    <div class="col-md-3">
       <select name="emp_id" class="form-control chosen" required="">
           <!--  <option value="0">Select</option> -->
            @foreach($employee as $row)
              <option value="{{$row->emp_machineid}}">{{$row->emp_name}}</option>
            @endforeach
        </select>     
    </div>
    @elseif(isset($emp))
     <div class="col-md-3">
       <select name="emp_id" class="form-control chosen" required="">
            <option value="{{ $emp }}">{{ $emp_nam->emp_name }}</option>
            @foreach($employee as $row)
              <option value="{{$row->emp_machineid}}">{{$row->emp_name}}</option>
            @endforeach
        </select>     
    </div><br><br><br>

    @endif

    </div><br><br><br>
    @endif
       
        

    </div>
   
    <!-- <div class="col-md-8">
      End Date
        <input type="date" class="form-control" id="end_date" style="background: white;padding: 0px 0px 0px 10px">
    </div> -->
    <div class="col-md-5" style="padding: 20px 20px 20px 500px;">
      @if($id->suser_level=="1")
          <button type="submit" class="btn btn-primary">Search</button>                    
      @else
         <button type="submit" class="btn btn-primary">Search</button>                   
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
                    <span class="caption-subject bold uppercase">Attendence List @if(isset($start_date) && isset($end_date))( {{$start_date}} to {{$end_date}} )@endif</span>
                </div>
                <div class="col-md-6"><p id="confirm_msg"></p></div>
                <div class="actions">
                
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
              <table class="table table-striped table-bordered table-hover table-checkable order-column" id="sample_3">
                      <thead>
                        <tr>
                          <th>SL</th>
                          <th>Employee ID</th>
                          <th>Employee Name</th>
                          <th>Date</th>  
                          <th>Punch Time</th>   
                          <th>Device</th>   
                        </tr>
                      </thead>
              
                      <tbody>
                      @php($i=1)
                      @if(isset($filter))
                      @foreach($filter as $key )
                     
                        <tr>
                         
                          <td>{{$i}}</td>
                          <td>{{$key->emp_empid}}</td>
                        
                          <td>{{$key->emp_name}}</td>
                          <td>{{ substr($key->check_time, 0, 10) }}</td>
                         <td>{{ substr($key->check_time, 11, 18) }}</td>
                         <td>
                           {{ $key->name }}
                         </td>
                         
                        </tr>
                      @php($i++)
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
 $(document).ready(function() {
    $('#example').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    } );
} );

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