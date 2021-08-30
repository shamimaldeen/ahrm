

@php
use App\Http\Controllers\BackEndCon\Controller;
use Illuminate\Support\Facades\Route;
$route=Route::getFacadeRoot()->current()->uri();
@endphp
<script src="{{URL::to('/')}}/public/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
<style type="text/css">
        @media print{
            #p {
                display: none;
            }
        }
.btn{
    font-weight: bold;
}
.caption{
    text-align: center;
     font-size: 15px;
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

table.print-content {
  font-size: 12px;
  border: 1px solid #dee2e6;
  border-collapse: collapse !important;
}

table.print-content th,
table.print-content td {
  padding: .2rem .4rem;
  text-align: left;
  vertical-align: top;
  border-top: 1px solid #dee2e6;
}
.sign-footer thead td{
      width:25%;
  }
@media print {
  .print-footer {
    position: fixed;
    bottom: 0;
    left: 0;
  }
  .no-print {
    display: none;
  }
  
}
</style>
<table>
  <!-- Start Header -->
  <thead>
    <tr>
      <td>
        <div class="row print-header">
            <div style="width:30%; float:left;">&nbsp;
            </div>
            <div style="text-align:left; width:33%; float:left;display: inline-flex;">
                <div>
                  <img src="{{url('/')}}/public/Main_logo.jpg" width="auto" height="60"/>
                </div>
               <div style="padding-left: 10px;">
                   <h3 style="margin: 0px; padding: 0px;">PFI Securities Limited</h3>
                  <p style="">56-57, 7th & 8th Floor, PFI Tower, Dilkusha C/A, Dhaka 1000</p>
                </div>
            </div>
            <div style="width:30%; float:left;">
              <h2 style="text-align: center;">Employee List @if($flag==1)
                      ({{ $empl_location->jl_name }})
                     @endif</h2>
            </div>
        </div>
      </td>
    </tr>
  </thead>
  <!-- End Header -->
  <tr>
    <td>
<input type="button" id="p" value="print" onclick="printPage();" class="btn btn-label-success btn-sm btn-upper">


@if($id->suser_level=="1" or $id->suser_level=="3")
<!-- filter start -->

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
                    <span class="caption-subject bold uppercase"></span>
                </div>

                <div class="col-md-6"><p id="confirm_msg"></p></div>
                

                <div class="tools"> </div>
            </div>
            <div class="portlet-body">
              <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{URL::to('')}}" id="data_form">
              {{ csrf_field() }}
               <table class="table table-striped table-bordered table-hover" id="sample_3" width="100%" border="1px">

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
                          <th>Shift Time</th>
                          <th>Employee Type</th>
                          <th>Senior Employee</th>
                        </tr>
                      </thead>
              
                      <tbody>
                        {!! $data !!}
                      </tbody>

                    </table><br><br><br>
                    </td>
                    </tr>
                    <tfoot>
                    <tr>
                      <td style="height: 3cm">
                        <!-- Leave this empty and don't remove it. This space is where footer placed on print -->
                      </td>
                    </tr>
                  </tfoot>
                </table>
                </td>
  </tr>
  <!-- Start Space For Footer -->
  <tfoot>
    <tr>
      <td style="height: 3cm">
        <!-- Leave this empty and don't remove it. This space is where footer placed on print -->
      </td>
    </tr>
  </tfoot>
  <!-- End Space For Footer -->
</table>
                     <div class="print-footer">
  <div class="row print-footer">
                      <table class="sign-footer" style="table-layout: fixed; width:100%;">
                        <thead>
                          <td>-----------------------------<br>Authorized Signature</td>
                         
                        </thead>
                      </table>
                    </div>
</div>


                  </form>
            </div>
          </div>
        </div>
      </div>
    </div>

<script src="{{URL::to('/')}}/public/js/bootstrap-datepicker.js"></script> 
<script type="text/javascript">
$('#datepicker1').datepicker();
$('#datepicker2').datepicker();
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
    window.open("{{URL::to('employee-details')}}/"+emp_depart_id+"/"+emp_sdepart_id+"/"+emp_desig_id+"/"+emp_jlid+"/"+emp_type+"/search","_parent");
  }else{
    $('#sms').html('<p class="alert alert-danger">Please Choose at least one Filter Option.</p>');
  }
}

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

function Reject() {  
  var confirm_msg = confirm("Are  You Confirm To Reject ?");
  if (confirm_msg){
      var data=$('#data_form').serializeArray();
      $.ajax({
        headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
        url: "{{URL::to('employee-details-reject')}}",
        type: 'POST',
        data: data,
        success:function(data) {
          if(data=="1"){
            location.reload();
          }else if(data=="0"){
            $('#confirm_msg').html('<b class="alert alert-danger" style="padding:0px 10px 0px 10px">Something Went Wrong!!</b>');
          }else if(data=="null"){
            $('#confirm_msg').html('<b class="alert alert-danger" style="padding:0px 10px 0px 10px">Please Select One or More Employee To Reject</b>');
          }
        }
      });
  }else{
    return false;
  }
}

function Resign() {  
  var confirm_msg = confirm("Are  You Confirm To Resign ?");
  if (confirm_msg){
      var data=$('#data_form').serializeArray();
      $.ajax({
        headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
        url: "{{URL::to('employee-details-resign')}}",
        type: 'POST',
        data: data,
        success:function(data) {
          if(data=="1"){
            location.reload();
          }else if(data=="0"){
            $('#confirm_msg').html('<b class="alert alert-danger" style="padding:0px 10px 0px 10px">Something Went Wrong!!</b>');
          }else if(data=="null"){
            $('#confirm_msg').html('<b class="alert alert-danger" style="padding:0px 10px 0px 10px">Please Select One or More Employee To Resign</b>');
          }
        }
      });
  }else{
    return false;
  }
}

function Suspend() {  
  var confirm_msg = confirm("Are  You Confirm To Suspend ?");
  if (confirm_msg){
      var data=$('#data_form').serializeArray();
      $.ajax({
        headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
        url: "{{URL::to('employee-details-suspend')}}",
        type: 'POST',
        data: data,
        success:function(data) {
          if(data=="1"){
            location.reload();
          }else if(data=="0"){
            $('#confirm_msg').html('<b class="alert alert-danger" style="padding:0px 10px 0px 10px">Something Went Wrong!!</b>');
          }else if(data=="null"){
            $('#confirm_msg').html('<b class="alert alert-danger" style="padding:0px 10px 0px 10px">Please Select One or More Employee To Suspend</b>');
          }
        }
      });
  }else{
    return false;
  }
}

function UpdateEmployeeType() {
  $.alert({
      title: 'Update Employee Type',
      content: "url:{{url('employee-details-update-employee-type')}}",
      animation: 'scale',
      closeAnimation: 'bottom',
      columnClass: 'col-md-6 col-md-offset-3',
      buttons: {
          cancel: {
              text: 'Cancel',
              btnClass: 'btn-light text-dark',
              action: function(){
                  // do nothing
              }
          }
      }
  });
}
</script>
 <script type="text/javascript">
        function printPage() {
            window.print();
        }
</script>
