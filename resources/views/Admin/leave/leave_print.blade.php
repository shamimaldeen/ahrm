

@php
use App\Http\Controllers\BackEndCon\Controller;
use Illuminate\Support\Facades\Route;
$route=Route::getFacadeRoot()->current()->uri();
@endphp

<script src="{{URL::to('/')}}/public/assets/global/plugins/jquery.min.js"></script>
<link href="{{URL::to('/')}}/public/css/bootstrap-timepicker.min.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="{{URL::to('/')}}/public/css/datepicker.css" />

<!-- leave application modal start -->

<!-- leave application modal end -->

<!-- filter start -->

<!-- filter end -->
<style type="text/css">
        @media print{
            #p {
                display: none;
            }
        }
        .print-header{
            margin: auto;
            text-align: -webkit-center;
        }
        .caption{
            text-align: center;
             font-size: 25px;
            font-weight: bold;
                }
                table {
          width: 100%;
          border-collapse: collapse;
        }
        th, td {
          padding: 10px;
          text-align: left;
        }
        th{
          background: #e4e4e4;
        }
</style>
<input type="button" id="p" value="print" onclick="printPage();" class="btn btn-label-success btn-sm btn-upper">
<div class="row print-header">
  <img src="http://localhost/pfi/public/Main_logo.jpg" height="70" width="70" >
  <h2 style="">PFI Securities Limited</h2>
  <p style="">56-57, 7th & 8th Floor, PFI Tower, Dilkusha C/A, Dhaka 1000</p>
</div>

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
                  

                    @if((Controller::checkAppModulePriority($route,'Approve')=="1") or (Controller::checkAppModulePriority($route,'Deny')=="1") or (Controller::checkAppModulePriority($route,'Delete')=="1"))
                   
                    @endif

                   
                </div>

                <div class="tools"> </div>
            </div>
            <div class="portlet-body">
               <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{URL::to('')}}" name="basic_validate" id="data_form">
              {{ csrf_field() }}
               <table class="table table-striped table-bordered table-hover" id="sample_3" width="100%" border="1px">

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
                    <br><br><br>
                     <table width="100%">
                      <thead>
                        <td>-------------------------------</td>
                       
                      </thead>
                      <tbody>
                        <tr>
                          <td>Authorized Signature</td>
                          
                        </tr>
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

<script type="text/javascript">
        function printPage() {
            window.print();
        }
</script>
