@extends('Admin.index')
@section('body')

@php
use App\Http\Controllers\BackEndCon\Controller;
@endphp
<script src="{{URL::to('/')}}/public/assets/global/plugins/jquery.min.js"></script>
<link rel="stylesheet" href="{{URL::to('/')}}/public/css/datepicker.css" />
<script src="{{URL::to('/')}}/public/js/bootstrap-datepicker.js"></script> 

<div class="row">
        <div class="col-md-12">
           @include('error.msg')
          <!-- BEGIN EXAMPLE TABLE PORTLET-->
          <div class="portlet box grey-cascade">
            <div class="portlet-title">
              <div class="caption">
                <i class="fa fa-globe"></i>Add Employee Information
              </div>
            
            </div>
            <div class="portlet-body">
              <div class="table-toolbar">
                <div class="row">

              <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{URL::to('add-new-employee-submit')}}" name="basic_validate" id="basic_validate" novalidate="novalidate">
                  {{ csrf_field() }}

              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                  Employee Name: <span class="required">* </span>
                </label>

                <div class="col-md-3">
                  <input type="text" class="form-control" name="emp_name" value="{{old('emp_name')}}" required>
                </div>

                <label class="col-md-2 control-label">
                  Employee Type: <span class="required">* </span>
                </label>

                <div class="col-md-3">
                  <select name="emp_type" class="form-control chosen" required>               
                    @if(isset($types[0]))
                    <option value="">Select</option>
                    @foreach ($types as $key => $type)
                      <option value="{{$type->id}}">{{$type->name}}</option>
                    @endforeach
                    @endif
                  </select>                            
                </div>
              </div>  

                           
                <div class="form-group">

                <label style="text-align:right" class="col-md-2 control-label">
                      Employee ID: <span class="required">* </span>
                </label>

                <div class="col-md-3">
                   <input type="text" class="form-control span12" name="emp_empid" value="{{old('emp_empid')}}" required>
                </div>
                
                <label class="col-md-2 control-label">
                  SF ID:
                </label>

                <div class="col-md-3">
                  <input type="text" class="form-control span12" name="emp_sfid" value="{{old('emp_sfid')}}">
                </div>

              </div>
              
              <div class="form-group">

                <label class="col-md-2 control-label">
                  Contact Number: <span class="required">* </span>
                </label>

                <div class="col-md-3">
                  <input type="text" class="form-control span12" name="emp_phone" value="{{old('emp_phone')}}" required>
                </div>
                
                <label class="col-md-2 control-label">
                  Date of Birth:
                </label>

                <div class="col-md-3">
                <div class="input-group">
                  <input type="date" class="form-control span12" name="emp_dob" value="{{old('emp_dob')}}"  data-date-format="yyyy-mm-dd" style="background: white" id="">
                 <!--  <span class="input-group-btn">
                  <button class="btn default" type="button"><i class="fa fa-calendar"></i></button>
                  </span> -->
                </div>
                </div>

              </div>
              
              <div class="form-group">
                
                <label class="col-md-2 control-label">
                  Country:
                </label>

                <div class="col-md-3">
                   <select name="emp_country" class="form-control chosen">
                      @if(isset($country) && count($country)>0)
                        @foreach ($country as $cnt)
                          @if($cnt->country_code=="BD")
                            <option value="{{$cnt->id}}" selected="selected">{{$cnt->country_name}}</option>
                          @else
                            <option value="{{$cnt->id}}">{{$cnt->country_name}}</option>
                          @endif
                        @endforeach
                      @endif
                    </select>
                </div>

                <label class="col-md-2 control-label">
                  Designation: <span class="required">* </span>
                </label>

                <div class="col-md-3">
                   <select name="emp_desig_id" class="form-control chosen" required>
                      @if(isset($designation) && count($designation)>0)
                        @foreach ($designation as $desig)
                          <option value="{{$desig->desig_id}}">{{$desig->desig_name}}  {{$desig->desig_specification}}</option>
                        @endforeach
                      @endif
                    </select>
                    <a class="btn btn-xs btn-primary" href="{{URL::to('designation-view/create')}}">Add New</a>
                </div>

              </div>

              <div class="form-group">

                <label class="col-md-2 control-label">
                  Department: <span class="required">* </span>
                </label>

                <div class="col-md-3">
                    <select name="emp_depart_id" id="emp_depart_id" class="form-control chosen" required onchange="getSubDepartment();" required="">
                        <option value="0">Select Department</option>                     
                        @if($id->suser_level=="1" or $id->suser_level=="2" or $id->suser_level=="3")
                          @if(isset($department) && count($department)>0)
                            @foreach ($department as $depart)
                              <option value="{{$depart->depart_id}}">{{$depart->depart_name}}</option>
                            @endforeach
                          @endif
                        @elseif($id->suser_level=="4")
                          @php echo Controller::getLineManagerDepartment($id->suser_empid); @endphp
                        @endif
                        
                      </select>                  
                      <a class="btn btn-xs btn-primary" href="{{URL::to('department-view/create')}}">Add New</a>   
                </div>

                <label class="col-md-2 control-label">
                  Sub-Department: 
                </label>

                <div class="col-md-3">
                    <select name="emp_sdepart_id" id="emp_sdepart_id" class="form-control chosen">                     
                    </select> 
                    <a class="btn btn-xs btn-primary" href="{{URL::to('sub-department-view/create')}}">Add New</a>  
                </div>

              </div>
              
              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                  Supervisor: <span class="required">* </span>
                </label>

                <div class="col-md-3">
                  <select name="emp_seniorid" id="emp_seniorid" class="form-control chosen">
                  </select>                        
                </div>

                <label class="col-md-2 control-label">
                  Authorized Person: <span class="required">* </span>
                </label>

                <div class="col-md-3">
                  <select name="emp_authperson" id="emp_authperson" class="form-control chosen">
                  </select>                        
                </div>
              </div>

              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                   Weekend : <span class="required">* </span>
                </label>

                <div class="col-md-3">
                 <select name="emp_wknd" class="form-control chosen" required>       
                    <option value="1">Friday</option>
                    <option value="2">Saturday</option>
                    <option value="3">SunDay</option>
                    <option value="4">Monday</option>
                    <option value="5">Tuesday</option>
                    <option value="6">Wednesday</option>
                    <option value="7">Thursday</option>
                  </select> 
                </div>

                <label class="col-md-2 control-label">
                  Vehicle Entitlement: <span class="required">* </span>
                </label>

                <div class="col-md-3">
                     <select name="emp_vehicle" class="form-control chosen" required>             
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                      </select>                      
                                
                </div>
              </div>
              
              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                  Blood Group: <span class="required">* </span>
                </label>

                <div class="col-md-3">
                     <select name="emp_blgrp" class="form-control chosen" required>                     
                        <option>A+</option>
                        <option>A-</option>
                        <option>B+</option>
                        <option>B-</option>
                        <option>O+</option>
                        <option>O-</option>
                        <option>AB+</option>
                        <option>AB-</option>
                        <option>N/A</option>
                      </select> 
                </div>

                <label class="col-md-2 control-label">
                  Education Qualification: <span class="required">* </span>
                </label>

                <div class="col-md-3">
                   <input type="text" class="form-control span12" name="emp_education" value="{{old('emp_education')}}" required>           
                                
                </div>
              </div>
              
              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                  Daily Working Hour: <span class="required">* </span>
                </label>

                <div class="col-md-3">
                 <select name="emp_workhr" id="emp_workhr" class="form-control chosen" required onchange="getShift();">                     
                      <option value="2">8 hours</option>
                      <option value="1">7 hours</option>
                     
                      <option value="3">6 hours</option>
                      <option value="4">5 hours</option>
                      <option value="5">4 hours</option>
                      <option value="6">3 hours</option>
                      <option value="7">9 hours</option>
                      <option value="8">10 hours</option>
                      <option value="9">11 hours</option>
                      <option value="10">12 hours</option>
                      </select> 
                </div>

                <label class="col-md-2 control-label">
                  OT Entitlement: <span class="required">* </span>
                </label>

                <div class="col-md-3">
                      <select name="emp_otent" class="form-control chosen" required>                     
                        <option value="1"> Yes</option>
                        <option value="0"> No </option>
                      </select>                         
                                
                </div>
              </div>
              
              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                  Shift: <span class="required">* </span>
                </label>

                <div class="col-md-3">
                <select name="emp_shiftid" id="emp_shiftid" class="form-control " required>
                    
                </select> 
                 <a class="btn btn-xs btn-primary" href="{{URL::to('shift-view/create')}}">Add New</a>  
                </div>

                <label class="col-md-2 control-label">
                  Face ID: <span class="required">* </span>
                </label>

                <div class="col-md-3">
                  <input type="text" class="form-control span12" name="emp_machineid" value="{{old('emp_machineid')}}" required>                 
                </div>
              </div>
              
              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                  NID No.: <span class="required">* </span>
                </label>

                <div class="col-md-3">
                    <input type="number" class="form-control span12" name="emp_nid" value="{{old('emp_nid')}}" required>
                </div>

                <label class="col-md-2 control-label">
                  Job Location: <span class="required">* </span>
                </label>

                <div class="col-md-3">
                 <select name="emp_jlid" class="form-control chosen" required>                     
                      @if(isset($joblocation) && count($joblocation)>0)
                      <option value="">Select Job Location</option>
                        @foreach ($joblocation as $jl)
                          <option value="{{$jl->jl_id}}">{{$jl->jl_name}}</option>
                        @endforeach
                      @endif
                    </select> 
                    <a class="btn btn-xs btn-primary" href="{{URL::to('job-location-view/create')}}">Add New</a>            
                                
                </div>
              </div>
              
              <div class="form-group">
                
                <label class="col-md-2 control-label">
                  Joining Date: <span class="required">* </span>
                </label>

                <div class="col-md-3">
                  <div class="input-group">
                   <input type="date" class="form-control span12" onblur="GetConfirmationDate();" onchange="GetConfirmationDate();"  name="emp_joindate" value="{{old('emp_joindate',date('Y-m-d'))}}" data-date-format="yyyy-mm-dd"  style="background: white" id="" required>
                   <!--  <span class="input-group-btn">
                    <button class="btn default" type="button"><i class="fa fa-calendar"></i></button>
                    </span> -->
                  </div>
                </div>

                <label class="col-md-2 control-label">
                  Confimation Date: <span class="required">* </span>
                </label>

                <div class="col-md-3">
                  <div class="input-group">
                     <input type="date" class="form-control span12" name="emp_confirmdate" value="{{old('emp_confirmdate')}}" data-date-format="yyyy-mm-dd"  style="background: white" id="" required>
                    <!-- <span class="input-group-btn">
                    <button class="btn default" type="button"><i class="fa fa-calendar"></i></button>
                    </span> -->
                  </div>
                  </div>
              </div>

              <div class="form-group">
                <label class="col-md-2 control-label">
                  Archive History From: <!-- <span class="required">* </span> -->
                </label>

                <div class="col-md-3">
                  <input type="text" class="form-control span12" name="emp_workhistoryfrom" value="{{old('emp_workhistoryfrom')}}" data-date-format="yyyy-mm-dd" readonly style="background: white" id="datepicker3">
                </div>

                <label class="col-md-2 control-label">
                  E-Mail Address: <!-- <span class="required">* </span> -->
                </label>

                <div class="col-md-3">
                  <input type="email" class="form-control span12" name="emp_email" value="{{old('emp_email')}}">
                </div>
                

              </div>
              
              <div class="form-group">

                <label class="col-md-2 control-label">
                  Father's Name: <!-- <span class="required">* </span> -->
                </label>

                <div class="col-md-3">
                  <input type="text" class="form-control span12" name="emp_father" value="{{old('emp_father')}}" >
                </div>

                <label class="col-md-2 control-label">
                  Mother's Name: <!-- <span class="required">* </span> -->
                </label>

               <div class="col-md-3">
                  <input type="text" class="form-control span12" name="emp_mother" value="{{old('emp_mother')}}" >
                </div>

              </div>

              <div class="form-group">

                <label class="col-md-2 control-label">
                  Emergency Contact No: <!-- <span class="required">* </span> -->
                </label>

                <div class="col-md-3">
                  <input type="text" class="form-control span12" name="emp_emjcontact" value="{{old('emp_emjcontact')}}" >
                </div>

                <label class="col-md-2 control-label">
                  Current Address: <!-- <span class="required">* </span> -->
                </label>

                <div class="col-md-3">
                 <textarea class="form-control span12" name="emp_crntaddress" style="resize:none;height:50px;">{{old('emp_crntaddress')}}</textarea>
                </div>

              </div>
              
              <div class="form-group">
                <label class="col-md-2 control-label">
                  Permanent Address: <!-- <span class="required">* </span> -->
                </label>

                <div class="col-md-3">
                 <textarea class="form-control span12" name="emp_prmntaddress" style="resize:none;height:50px;">{{old('data')}}</textarea>
                </div>
                
                <label class="col-md-2 control-label">
                Image : <span class="required">* </span>
                </label>

                <div class="col-md-3">
                  <input type="file" name="emp_img">
                </div>
              </div>
              

              <h4 style="padding: 10px 25px; background-color: #d8dcdc;margin: 30px 10px;">Insurance Data</h4>
              
              <div class="form-group">
                <div class="col-md-12">
                  <div class="col-md-6">
                    <label for="self_member_id">Self Member ID</label>
                    <input type="text" class="form-control" name="self_member_id" id="self_member_id" value="{{old('self_member_id')}}">
                  </div>
                  <div class="col-md-6">
                    <label for="datepicker11">Insurance Effective Date</label>
                    <input type="text" class="form-control" name="effective_date" id="datepicker11" readonly style="background: white" data-date-format="yyyy-mm-dd" value="{{old('effective_date')}}">
                  </div>
                </div>
              </div>

              <div class="form-group">
                <div class="col-md-12">
                  <div class="col-md-4">
                    <label for="spouse_name">Spouse Name</label>
                    <input type="text" class="form-control" name="spouse_name" id="spouse_name" value="{{old('spouse_name')}}">
                  </div>
                  <div class="col-md-2" hidden="">
                    <label for="spouse_member_id">Spouse Member ID</label>
                    <input type="text" class="form-control" id="spouse_member_id" name="spouse_member_id" value="{{old('spouse_member_id')}}">
                  </div>
                  <div class="col-md-4">
                    <label for="datepicker12">Date Of Birth</label>
                    <input type="text" class="form-control" name="spouse_dob" id="datepicker12" readonly style="background: white" data-date-format="yyyy-mm-dd" value="{{old('spouse_dob')}}">
                  </div>
                  <div class="col-md-2" hidden="">
                    <label for="datepicker13">Insurance Start From</label>
                    <input type="text" class="form-control" name="spouse_start_date" id="datepicker13" readonly style="background: white" data-date-format="yyyy-mm-dd" value="{{old('spouse_start_date')}}">
                  </div>
                  <div class="col-md-2" hidden="">
                    <label for="datepicker14">Insurance Valid To</label>
                    <input type="text" class="form-control" name="spouse_end_date" id="datepicker14" readonly style="background: white" data-date-format="yyyy-mm-dd" value="{{old('spouse_end_date')}}">
                  </div>
                  <div class="col-md-4">
                    <label for="datepicker14">Spouse Phone</label>
                    <input type="text" class="form-control" name="spouse_phone"
                  </div>
                </div>
              </div>

              <div class="form-group">
                <div class="col-md-12">
                  <div class="col-md-6">
                    <label for="child1_name">Child-1 Name</label>
                    <input type="text" class="form-control" name="child1_name" id="child1_name" value="{{old('child1_name')}}">
                  </div>
                  <div class="col-md-2" hidden="">
                    <label for="child1_member_id">Child-1 Member ID</label>
                    <input type="text" class="form-control" id="child1_member_id" name="child1_member_id" value="{{old('child1_member_id')}}">
                  </div>
                  <div class="col-md-6">
                    <label for="datepicker15">Date Of Birth</label>
                    <input type="text" class="form-control" name="child1_dob" id="datepicker15" readonly style="background: white" data-date-format="yyyy-mm-dd" value="{{old('child1_dob')}}">
                  </div>
                  <div class="col-md-2" hidden="">
                    <label for="datepicker16">Insurance Start From</label>
                    <input type="text" class="form-control" name="child1_start_date" id="datepicker16" readonly style="background: white" data-date-format="yyyy-mm-dd" value="{{old('child1_start_date')}}">
                  </div>
                  <div class="col-md-2" hidden="">
                    <label for="datepicker17">Insurance Valid To</label>
                    <input type="text" class="form-control" name="child1_end_date" id="datepicker17" readonly style="background: white" data-date-format="yyyy-mm-dd" value="{{old('child1_end_date')}}">
                  </div>
                </div>
              </div>

              <div class="form-group">
                <div class="col-md-12">
                  <div class="col-md-6">
                    <label for="child2_name">Child-2 Name</label>
                    <input type="text" class="form-control" name="child2_name" id="child2_name" value="{{old('child2_name')}}">
                  </div>
                  <div class="col-md-2" hidden="">
                    <label for="child2_member_id">Child-2 Member ID</label>
                    <input type="text" class="form-control" id="child2_member_id" name="child2_member_id" value="{{old('child2_member_id')}}">
                  </div>
                  <div class="col-md-6">
                    <label for="datepicker18">Date Of Birth</label>
                    <input type="text" class="form-control" name="child2_dob" id="datepicker18" readonly style="background: white" data-date-format="yyyy-mm-dd" value="{{old('child2_dob')}}">
                  </div>
                  <div class="col-md-2" hidden="">
                    <label for="datepicker19">Insurance Start From</label>
                    <input type="text" class="form-control" name="child2_start_date" id="datepicker19" readonly style="background: white" data-date-format="yyyy-mm-dd" value="{{old('child2_start_date')}}">
                  </div>
                  <div class="col-md-2" hidden="">
                    <label for="datepicker20">Insurance Valid To</label>
                    <input type="text" class="form-control" name="child2_end_date" id="datepicker20" readonly style="background: white" data-date-format="yyyy-mm-dd" value="{{old('child2_end_date')}}">
                  </div>
                </div>
              </div>

              <h4 style="padding: 10px 25px; background-color: #d8dcdc;margin: 30px 10px;">Salary Data</h4>
              
              <div class="form-group">
                <div class="col-md-12">
                  <div class="col-md-3">
                    <label for="tin_no">TIN No.</label>
                    <input type="text" class="form-control" name="tin_no" id="tin_no" value="{{old('tin_no')}}">
                  </div>
                  <div class="col-md-3">
                    <label for="grade">Grade</label>
                    <input type="text" class="form-control" name="grade" id="grade" value="{{old('grade')}}">
                  </div>
                  <div class="col-md-3">
                    <label for="bank_account">Bank Account</label>
                    <input type="text" class="form-control" name="bank_account" id="bank_account" value="{{old('bank_account')}}">
                  </div>
                  <div class="col-md-3">
                    <label for="bu_code">BU Code</label>
                    <input type="text" class="form-control" name="bu_code" id="bu_code" value="{{old('effective_date')}}">
                  </div>
                </div>
              </div>

              <div class="form-group">
                <div class="col-md-12">
                  <div class="col-md-3">
                    <label for="category">Category</label>
                    <select name="category" class="form-control chosen">
                      <option value="1">Direct</option>
                      <option value="2">Indirect</option>
                    </select>
                  </div>
                  <div class="col-md-3">
                    <label for="ten_steps">10 Steps</label>
                    <input type="text" class="form-control" name="ten_steps" id="ten_steps" value="{{old('ten_steps')}}">
                  </div>
                  <div class="col-md-3">
                    <label for="gender">Gender</label>
                    <select name="gender" class="form-control chosen">
                      <option value="1">Male</option>
                      <option value="2">Female</option>
                    </select>
                  </div>
                 <div class="col-md-3">
                    <label for="gender">Date of execution Starts From :</label>
                    <input type="date" class="form-control span12"  name="head_date_of_execution" value="{{date('Y-m-d')}}" required>
                  </div>
                </div>
              </div>

              <div class="form-group" hidden="">
                <div class="col-md-12">
                  <div class="col-md-12">
                    <label for="basic_salary">Gross Salary</label>
                    <input type="number" min="0" value="0" class="form-control" name="gross_salary">
                  </div>
                </div>
              </div>

              <div class="form-group" style="margin-left:0px">

                <div class="col-md-3">
                  <button type="submit" class="btn btn-success"><i class="fa fa-save"></i>&nbsp;Save</button>
                </div>                

              </div>
         

                    </div>          




                      </div>



                    </div>





                  </div>



                </div>



              </div>



            </div>



          </form>

                  </div>
                  
                </div>
              </div>
              
            </div>
          </div>
          <!-- END EXAMPLE TABLE PORTLET-->
        </div>
      </div>
        </div>
      </div>



<script type="text/javascript">
$('#datepicker1').datepicker();
$('#datepicker2').datepicker();
$('#datepicker3').datepicker();
$('#datepicker4').datepicker();
$('#head_date_of_execution').datepicker();
$('#datepicker11').datepicker();
$('#datepicker12').datepicker();
$('#datepicker13').datepicker();
$('#datepicker14').datepicker();
$('#datepicker15').datepicker();
$('#datepicker16').datepicker();
$('#datepicker17').datepicker();
$('#datepicker18').datepicker();
$('#datepicker19').datepicker();
$('#datepicker20').datepicker();
getShift();
getSeniorEmployee();
getAuthPerson();
GetConfirmationDate();

function GetConfirmationDate () {
  var emp_joindate=$('#datepicker2').val();
  $('#head_date_of_execution').val(emp_joindate);
  if(emp_joindate!=""){
    var emp_confirmdate=emp_joindate.split('-');
    if(emp_confirmdate[0].length==4 & emp_confirmdate[1].length==2 & emp_confirmdate[2].length==2){
      new_emp_confirmdate=parseInt(emp_confirmdate[1])+6;
      if(new_emp_confirmdate>12){
        var month=new_emp_confirmdate-12;
        var year=parseInt(emp_confirmdate[0])+1;
        if(month.length==2){
          $('#datepicker4').val(year+'-'+month+'-'+emp_confirmdate[2]);
        }else{
          $('#datepicker4').val(year+'-0'+month+'-'+emp_confirmdate[2]);
        }
      }else{
        var month=new_emp_confirmdate;
        var year=emp_confirmdate[0];
        if(month.length==2){
          $('#datepicker4').val(year+'-'+month+'-'+emp_confirmdate[2]);
        }else{
          $('#datepicker4').val(year+'-0'+month+'-'+emp_confirmdate[2]);
        }
      }
    }
  }
}

function getSubDepartment () {
  var emp_depart_id=$('#emp_depart_id').val();
  if(emp_depart_id!="0"){
    $.ajax({
      url:"{{URL::to('getSubDepartment')}}/"+emp_depart_id,
      type:'GET',
      data:{},
      success:function(data) {
        $('#emp_sdepart_id').html(data).trigger("chosen:updated");
      }
    });
  }else{
    $('#emp_sdepart_id').html('').trigger("chosen:updated");
  }
}

function  getSeniorEmployee() {
  $.ajax({
    url:"{{URL::to('getSeniorEmployee')}}",
    type:'GET',
    data:{},
    success:function(data) {
      $('#emp_seniorid').html(data).trigger("chosen:updated");
    }
  });
}

function  getAuthPerson() {
  $.ajax({
    url:"{{URL::to('getAuthPerson')}}",
    type:'GET',
    data:{},
    success:function(data) {
      $('#emp_authperson').html(data).trigger("chosen:updated");
    }
  });
}

function  getShift() {
  var emp_workhr=$('#emp_workhr').val();
  
  if(emp_workhr!="0"){
    $.ajax({
      url:"{{URL::to('getShift')}}/"+emp_workhr,
      type:'GET',
      data:{},
      success:function(data) {
        $('#emp_shiftid').html(data).trigger("chosen:updated");
      }
    });
  }else{
    $('#emp_shiftid').html('').trigger("chosen:updated");
  }
}

function check_all()
{

if($('#chkbx_all').is(':checked')){
  $('input.check_elmnt2').prop('disabled', false);
  $('input.check_elmnt').prop('checked', true);
  $('input.check_elmnt2').prop('checked', true);
}else{
  $('input.check_elmnt2').prop('disabled', true);
  $('input.check_elmnt').prop('checked', false);
  $('input.check_elmnt2').prop('checked', false);
  }
} 
    

function chekMain(getID){

if($('#linkID-'+getID).is(':checked')){
    
  $("input#sublinkID-"+getID).attr('disabled', false);
  $("input#sublinkID-"+getID).attr('checked', true);

}else{
  $("input#sublinkID-"+getID).attr('disabled', true);
  $("input#sublinkID-"+getID).attr('checked', false);

}

}


</script>

@endsection