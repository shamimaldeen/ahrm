@extends('Admin.index')
@section('body')

@php
use App\Http\Controllers\BackEndCon\Controller;
use Illuminate\Support\Facades\Route;
$route=Route::getFacadeRoot()->current()->uri();
@endphp
<script src="{{URL::to('/')}}/public/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
  <div class="row">
    <div class="col-md-12">
       @include('error.msg')
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light bordered">

            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class="icon-settings font-dark"></i>
                    <span class="caption-subject bold uppercase">My Team </span>
                </div>

                <div class="col-md-6"><p id="confirm_msg"></p></div>
                <div class="actions">
                  
                  @if((Controller::checkAppModulePriority($route,'Edit')=="1") or (Controller::checkAppModulePriority($route,'View')=="1") or (Controller::checkAppModulePriority($route,'View History')=="1"))
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

</script>
@endsection