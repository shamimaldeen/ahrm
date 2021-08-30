@extends('Admin.index')
@section('body')

@php
use App\Http\Controllers\BackEndCon\Controller;
use Illuminate\Support\Facades\Route;
$route=Route::getFacadeRoot()->current()->uri();
@endphp

<script src="{{URL::to('/')}}/public/assets/global/plugins/jquery.min.js"></script>
<link href="{{URL::to('/')}}/public/css/bootstrap-timepicker.min.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="{{URL::to('/')}}/public/css/datepicker.css" />

<!--Request Modal start -->

<div class="modal fade" id="requestModal" role="dialog">
  <div class="modal-dialog modal-lg">
  
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">OT Request Application</h4>
      </div>
      <div class="modal-body" >

          
          <div class="container-fluid">

          <div class="row-fluid">
            <div class="span12">
              
              @include('error.msg')
                <div class="widget-box">

                    <div class="widget-content tab-content">
                      
                      <div id="tab1" class="tab-pane active">

                        <div class="widget-box">
                           
                           <div class="widget-title" style='background-color: #f1f0f0; padding: 15px 20px;'> 
                            <span class="icon"> <i class="icon-info-sign"></i> </span>
                              <h5>Dear Sir/Madam, <br ><br >Due to assigninment/work, I need to perform the duties after my normal woring hour. In this regard, kindly allow me to work for over time. Approximate time require for perform the job as below;</h5>
                            </div>

                            <div class="widget-content nopadding" style='margin-top: 25px;'>
                              <form class="form-horizontal form-row-seperated" method="post" enctype="multipart/form-data" action="{{URL::to('ot-request-submit')}}" name="basic_validate" id="basic_validate" novalidate="novalidate">
                              {{ csrf_field() }}
                          
                              
                           <div class="form-group">
                            <label class="col-md-2 control-label">Date : <span class="required">* </span></label>
                            <div class="col-md-3">
                              <input type="text" class="form-control" name="otapp_perdate" value="{{old('otapp_perdate')}}"  data-date-format="yyyy-mm-dd" id="datepicker1" required>
                            </div>
                          </div>

                          <div class="form-group">
                            <label class="col-md-2 control-label"> From: <span class="required">* </span></label>
                            <div class="col-md-3">
                              <input type="text" class="form-control " name="otapp_fromtime" value="{{old('otapp_fromtime')}}"  id="timepicker1" required>
                            </div>
                         
                            <label class="col-md-2 control-label">To: <span class="required">* </span></label>
                            <div class="col-md-3">
                              <input type="text" class="form-control " name="otapp_totime" value="{{old('otapp_totime')}}"  data-date-format="yyyy-mm-dd" id="timepicker2" required>
                            </div>
                          </div>

                          <div class="form-group">
                          <label class="col-md-2 control-label"></label>
                            <div class="col-md-3">
                              <input type="submit" value="Save" class="btn btn-success">
                            </div>
                          </div>
                            
                        </div>
                          

                            </form>
                          </div>

                        </div>
                      </div>

                    </div>

                </div>

            </div>
          </div>
          </div>
     
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
    
  </div>
</div>
<!--Request Modal end -->

<!--Assign Modal start -->
<div class="modal fade" id="assignModal" role="dialog">
  <div class="modal-dialog modal-lg">
  
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">OT Application Assign</h4>
      </div>
      <div class="modal-body" >

          <div class="container-fluid">
          <div class="row-fluid">
          <div class="span12">
          @include('error.msg')
          <div class="widget-box">
          <div class="widget-content tab-content">
          <div id="tab1" class="tab-pane active">

          <div class="widget-box">
            
            <div class="widget-title" style='background-color: #f1f0f0; padding: 15px 20px;'> <span class="icon"> <i class="icon-info-sign"></i> </span>
              <h5>Dear Sir/Madam, <br ><br >Due to assigninment/work, I need to perform the duties after my normal woring hour. In this regard, kindly allow me to work for over time. Approximate time require for perform the job as below;</h5>
            </div>

                <div class="widget-content nopadding" style='margin-top: 25px;'>
                  <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{URL::to('ot-application-assign-submit')}}" name="basic_validate" id="basic_validate" novalidate="novalidate">
                  {{ csrf_field() }}

                  <div class="form-group">
                     <label class="col-md-2 control-label">
                      Department: <span class="required">* </span>
                    </label>

                    <div class="col-md-3">
                       <select name="emp_depart_id" id="emp_depart_id" class="form-control" required onchange="getSubDepartment(),getEmployee();">
                        <option value="0">Select A Department</option>
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
                 
                     <label class="col-md-2 control-label">
                        Sub-Department: <span class="required">* </span>
                      </label>

                    <div class="col-md-3">
                        <select name="emp_sdepart_id" id="emp_sdepart_id" class="form-control" required onchange="getEmployee();">                     
                        </select> 
                        
                    </div>
                  </div>

                  <div class="form-group">
                     <label class="col-md-2 control-label">
                      Designation: <span class="required">* </span>
                    </label>

                    <div class="col-md-3">
                       <select name="emp_desig_id" id="emp_desig_id" class="form-control" required onchange="getEmployee();">
                        <option value="0">Select Designation</option>}
                          @if(isset($designation) && count($designation)>0)
                            @foreach ($designation as $desig)
                              <option value="{{$desig->desig_id}}">{{$desig->desig_name}}</option>
                            @endforeach
                          @endif
                        </select>
                        
                    </div>
                
                     <label class="col-md-2 control-label">
                        Employee Name: <span class="required">* </span>
                      </label>

                    <div class="col-md-3">
                        <select name="otapp_empid" id="emp_empid" class="form-control" required >                     
                        </select> 
                         
                    </div>
                  </div>

                    <div class="form-group">

                    <div>
                     <label  class="col-md-2 control-label">
                      Date : <span class="required">* </span>
                    </label>

                    <div class="col-md-3">
                      <input type="text" class="form-control span10" name="otapp_perdate" value="{{old('otapp_perdate')}}"  data-date-format="yyyy-mm-dd" id="datepicker2" required>
                    </div>
                     </div>
                     </div>
                    
                  <div class="form-group">  
                    <div>
                     <label class="col-md-2 control-label">
                      From: <span class="required">* </span>
                    </label>

                    <div class="col-md-3">
                      <input type="text" class="form-control span10" name="otapp_fromtime" value="{{old('otapp_fromtime')}}"  id="timepicker3" required>
                      </div>
                     </div>
                      

                    <div>
                     <label class="col-md-2 control-label">
                      To: <span class="required">* </span>
                    </label>

                    <div class="col-md-3">
                      <input type="text" class="form-control span10" name="otapp_totime" value="{{old('otapp_totime')}}"  id="timepicker4" required>
                      </div>
                     </div>
                      </div>

                      <div class="form-group">
                           <label class="col-md-2 control-label"></label>
                           <div class="col-md-3">
                            <input type="submit" value="Save" class="btn btn-success">
                          </div>
                      </div>

                  </form>
                </div>

          </div>
          </div>
          </div>
          </div>
          </div>
          </div>
          </div>


      </div>
      
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
    
  </div>
</div>
<!--Assign Modal end -->


<!-- filter start -->
<div class="row" style='padding: 30px; background-color: #f7f7f7; margin: 0px;'>

  <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{URL::to('')}}" name="basic_validate" id="basic_validate" novalidate="novalidate">
      {{ csrf_field() }}
               
  
  <div class="form-group">

    <label class="col-md-1 control-label" style="text-align: right;font-size:12px;padding: 5px 0px">
      Department:
    </label>

    <div class="col-md-3">
        <select name="emp_depart_id" id="filter_emp_depart_id" class="form-control" required onchange="getFilterSubDepartment();">
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
      <select name="emp_sdepart_id" id="filter_emp_sdepart_id" class="form-control" required>
      </select> 
    </div>



    <label class="col-md-1 control-label" style="text-align: right;font-size:12px;padding: 5px 0px">
      Designation:
    </label>

    <div class="col-md-3">
       <select name="emp_desig_id" id="emp_desig_id" class="form-control" required>
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
     <select name="emp_jlid" id="emp_jlid" class="form-control " required>
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
        <select name="emp_type" id="emp_type" class="form-control" required>
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
    
    <label class="col-md-1 control-label" style="text-align: right;font-size:12px;padding: 5px 0px">
      OT Date:
    </label>
    <div class="col-md-3">
        <input type="text" class="form-control" id="defaultrange" readonly="yes" style="background: white;padding: 0px 0px 0px 10px">
    </div>

  </div>

  <div class="form-group">
    <div class="col-md-6 col-md-offset-3">
     <span id="hidden_table_title" style="display: none;">OT Data @if(isset($start_date) && isset($end_date))( {{$start_date}} to {{$end_date}} )
      @endif</span>
    </div>
    <div class="col-md-3">
          <a class="btn btn-success btn-md btn-block" onclick="getFilter();">Search</a>                    
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
                    <span class="caption-subject bold uppercase">OT Data @if(isset($start_date) && isset($end_date))( {{$start_date}} to {{$end_date}} )@endif</span>
                </div>

                <div class="col-md-4">
                  <p id="confirm_msg"></p>
                </div>
                <div class="actions">
                    <span style="float: left;padding: 0px 10px 5px 0px">
                      <a style="cursor: pointer;" class="btn btn-primary btn-sm btn-circle" data-toggle="modal" data-target="#requestModal" >OT Application Request</a>
                      <a style="cursor: pointer;" class="btn btn-success btn-sm btn-circle" data-toggle="modal" data-target="#assignModal" >OT Application Assign</a>
                    </span>
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
                          <th>Total OT </th>
                          <th>NC OT (2 Hrs daily)</th>
                          <th>NC OT (12 Hrs Weekly</th>
                          <th>NC OT (48 Hrs Monthly</th>
                        </tr>
                      </thead>
                      @if(isset($otdata) && count($otdata)>0)
                      @php
                      $c=0;
                      @endphp
                        @foreach ($otdata as $od)
                        @if(isset($ot_nc) && count($ot_nc)>0)
                        @foreach ($ot_nc as $otnc)
                        @if($otnc->att_empid==$od->emp_id && $otnc->totsec>0)
                        @php
                        $c++;
                        @endphp
                         <tr class="gradeX" id="tr-{{$od->att_id}}">
                            <td>{{$c}}</td>
                            <td>{{$od->emp_id}}</td>
                            <td>{{$od->emp_name}}</td>
                            
                            <td>
                                  {{$otnc->tot}}
                            </td>

                            <td>
                            @if(isset($ot_today_nc) && count($ot_today_nc)>0)
                              @foreach ($ot_today_nc as $ottnc)
                                @if($ottnc->att_empid==$od->emp_id)
                                  
                                    @php
                                    $hour=substr($ottnc->tot,0,2);
                                    if($hour>2){
                                      $hour=$hour-2;
                                    }else{
                                      $hour='00';
                                    }
                                    $minute=substr($ottnc->tot,3,2);
                                    $seconds=substr($ottnc->tot,6,2);
                                    echo $hour.':'.$minute.':'.$seconds;
                                    @endphp
                                @endif
                              @endforeach
                            @endif
                            </td>

                            <td>
                            @if(isset($ot_thisweek_nc) && count($ot_thisweek_nc)>0)
                              @foreach ($ot_thisweek_nc as $ottwnc)
                                @if($ottwnc->att_empid==$od->emp_id)
                                    @php
                                    $hour=substr($ottwnc->tot,0,2);
                                    if($hour>12){
                                      $hour=$hour-12;
                                    }else{
                                      $hour='00';
                                    }
                                    $minute=substr($ottwnc->tot,3,2);
                                    $seconds=substr($ottwnc->tot,6,2);
                                    echo $hour.':'.$minute.':'.$seconds;
                                    @endphp
                                @endif
                              @endforeach
                            @endif
                            </td>

                            <td>
                            @if(isset($ot_thismonth_nc) && count($ot_thismonth_nc)>0)
                              @foreach ($ot_thismonth_nc as $ottmnc)
                                @if($ottmnc->att_empid==$od->emp_id)
                                  @php
                                    $hour=substr($ottmnc->tot,0,2);
                                    if($hour>48){
                                      $hour=$hour-48;
                                    }else{
                                      $hour='00';
                                    }
                                    $minute=substr($ottmnc->tot,3,2);
                                    $seconds=substr($ottmnc->tot,6,2);
                                    echo $hour.':'.$minute.':'.$seconds;
                                  @endphp
                                @endif
                              @endforeach
                            @endif
                            </td>
                            
                          </tr>
                        @endif
                        @endforeach
                        @endif
                        @endforeach
                      @endif
                      <tbody>
                      </tbody>

                    </table>
            </div>
          </div>
        </div>
      </div>
    </div>
<script src="{{URL::to('/')}}/public/js/bootstrap-datepicker.js"></script> 
<script src="{{URL::to('/')}}/public/js/bootstrap-timepicker.min.js" type="text/javascript"></script>
<script type="text/javascript">
getSubDepartment();
getFilterSubDepartment();
$('#datepicker1').datepicker();
$('#datepicker2').datepicker();
$('#timepicker1').timepicker({   
    minuteStep: 1,
     disableFocus: true,
     template: 'dropdown',
     showMeridian:false
});
$('#timepicker2').timepicker({   
    minuteStep: 1,
     disableFocus: true,
     template: 'dropdown',
     showMeridian:false
});
$('#timepicker3').timepicker({   
    minuteStep: 1,
     disableFocus: true,
     template: 'dropdown',
     showMeridian:false
});
$('#timepicker4').timepicker({   
    minuteStep: 1,
     disableFocus: true,
     template: 'dropdown',
     showMeridian:false
});

function getFilterSubDepartment () {
  var emp_depart_id=$('#filter_emp_depart_id').val();
  if(emp_depart_id!="0"){
    $.ajax({
      url:"{{URL::to('getFilterSubDepartment')}}/"+emp_depart_id,
      type:'GET',
      data:{},
      success:function(data) {
        $('#filter_emp_sdepart_id').html(data).change();
      }
    });
  }else{
    $('#filter_emp_sdepart_id').html('<option value="0">Select</option>').change();
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
    window.open("{{URL::to('ot')}}/"+emp_depart_id+"/"+emp_sdepart_id+"/"+emp_desig_id+"/"+emp_jlid+"/"+emp_type+"/"+start_date+"/"+end_date+"/search","_parent");
  }else{
    $('#sms').html('<p class="alert alert-danger">Please Choose at least one Filter Option.</p>');
  }
}


function getSubDepartment () {
  var emp_depart_id=$('#emp_depart_id').val();
  if(emp_depart_id!="0"){
    $.ajax({
      url:"{{URL::to('getOTSubDepartment')}}/"+emp_depart_id,
      type:'GET',
      data:{},
      success:function(data) {
        $('#emp_sdepart_id').html(data).change();
      }
    });
  }else{
    $('#emp_sdepart_id').html('').change();
  }
}

function getEmployee () {
  var emp_depart_id=$('#emp_depart_id').val();
  var emp_sdepart_id=$('#emp_sdepart_id').val();
  if(emp_sdepart_id==null){
    emp_sdepart_id=0;
  }
  var emp_desig_id=$('#emp_desig_id').val();
  if(emp_depart_id!="0"){
    $.ajax({
      url:"{{URL::to('getEmployee')}}/"+emp_depart_id+'/'+emp_sdepart_id+'/'+emp_desig_id,
      type:'GET',
      data:{},
      success:function(data) {
        $('#emp_empid').html(data).change();
      }
    });
  }else{
    $('#emp_empid').html('').change();
  }
}
</script>
@endsection