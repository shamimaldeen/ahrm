@extends('Admin.index')
@section('body')
@php
use App\Http\Controllers\BackEndCon\Controller;
use Illuminate\Support\Facades\Route;
$route=Route::getFacadeRoot()->current()->uri();
@endphp

@if(Controller::checkAppModulePriority($route,'Edit')=="1")
@else
<script type="text/javascript">location="{{URL::to('/')}}"</script>
@endif

<script src="{{URL::to('/')}}/public/assets/global/plugins/jquery.min.js"></script>
<link rel="stylesheet" href="{{URL::to('/')}}/public/css/datepicker.css" />
<script src="{{URL::to('/')}}/public/js/bootstrap-datepicker.js"></script> 
<style type="text/css">
  .labelcol{
    background-color: #8cbfe8;
    padding: 7px;
    color: white;
    font-size: 13px;    
  }
</style>
<div class="row">
        <div class="col-md-12">
           @include('error.msg')
          <!-- BEGIN EXAMPLE TABLE PORTLET-->
          <div class="portlet box grey-cascade">
            <div class="portlet-title">
              <div class="caption">
                <i class="fa fa-globe"></i>Update Employee Information
              </div>
              
              @if(Controller::checkLinkPriority($route)=="1")
              <a class="btn btn-primary" href="{{URL::to('/employee-details')}}" style="float:right;margin:5px">Go Back</a>
              @endif
              
            </div>
            <div class="portlet-body">
              <div class="table-toolbar">
                <div class="row col-md-12 col-md-offset-0">

              <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{URL::to('employee-details-edit-submit')}}" name="basic_validate" id="basic_validate" novalidate="novalidate">
                  {{ csrf_field() }}
               
                  <div class="form-group">
                    <label class="col-md-2 control-label"></label>
                    <div class="col-md-4"></div>
                    <label class="col-md-2 control-label"></label>

                    <div class="col-md-4">
                      @if($employeeDetails->emp_imgext!="")
                      <img style="height: 100px;border: 1px solid black;padding: 5px;float: right;" src="{{URL::to('public/EmployeeImage')}}/{{$employeeDetails->emp_id}}.{{$employeeDetails->emp_imgext}}">
                      @else
                      <img  style="height: 100px;border: 1px solid black;padding: 5px;float: right;" src="{{URL::to('public')}}/male.jpg"/>
                      @endif

                    </div>
                  </div>
              
              <input type="hidden" class="form-control" name="emp_id" value="{{$employeeDetails->emp_id}}" required>
              
        @if($id->suser_level=="1" or $id->suser_level=="3")
              <div class="form-group">
                <label class="col-md-2 control-label labelcol">
                  <span class="labelcol">  Employee Name:</span> 
                </label>

                <div class="col-md-4">
                  <input type="text" class="form-control" name="emp_name" value="{{$employeeDetails->emp_name}}" required>
                </div>
                
                
                <label class="col-md-2 control-label labelcol">
                  <span class="labelcol">Employee Type:  </span>
                </label>

                <div class="col-md-4">
                    <select name="emp_type" class="form-control chosen" required>
                      @if(isset($types[0]))
                      @foreach ($types as $key => $type)
                        <option value="{{$type->id}}" @if($employeeDetails->emp_type==$type->id) selected @endif>{{$type->name}}</option>
                      @endforeach
                      @endif
                    </select>                             
                </div>
              </div>  

                <div class="form-group">

                <label class="col-md-2 control-label labelcol">
                    <span class="labelcol">  Employee ID: </span> 
                </label>

                <div class="col-md-4">
                 <input type="text" class="form-control" name="emp_empid" value="{{$employeeDetails->emp_empid}}" required>
                </div>
                
                <label class="col-md-2 control-label labelcol">
                <span class="labelcol">  SF ID: </span> 
                </label>

                <div class="col-md-4">
                  <input type="text" class="form-control span12" name="emp_sfid" value="{{$employeeDetails->emp_sfid}}" >
                </div>

              </div>
              
              <div class="form-group">

                <label class="col-md-2 control-label labelcol">
                 <span class="labelcol"> Contact Number:  </span><!--  --><span class="required">* </span>
                </label>

                <div class="col-md-4">
                  <input type="text" class="form-control span12" name="emp_phone" value="{{$employeeDetails->emp_phone}}" required>
                </div>
                
                <label class="col-md-2 control-label labelcol">
                 <span class="labelcol"> Date of Birth: </span> 
                </label>

                <div class="col-md-4">
                <div class="input-group">
                  <input type="date" class="form-control span12" name="emp_dob" value="{{$employeeDetails->emp_dob}}"  data-date-format="yyyy-mm-dd" id="" required>
                  <!-- <span class="input-group-btn">
                  <button class="btn default" type="button"><i class="fa fa-calendar"></i></button>
                  </span> -->
                </div>
                </div>

              </div>
              
              <div class="form-group">
                
                <label class="col-md-2 control-label labelcol">
                 <span class="labelcol"> Country:  </span>
                </label>

                <div class="col-md-4">
                   <select name="emp_country" class="form-control chosen" required>
                      @if(isset($country) && count($country)>0)
                        @foreach ($country as $cnt)
                          @if($cnt->id==$employeeDetails->emp_country)
                            <option value="{{$cnt->id}}" selected="selected">{{$cnt->country_name}}</option>
                          @else
                            <option value="{{$cnt->id}}">{{$cnt->country_name}}</option>
                          @endif
                        @endforeach
                      @endif
                    </select>
                </div>

                <label class="col-md-2 control-label labelcol">
                 <span class="labelcol"> Designation: </span> 
                </label>

                <div class="col-md-4">
                   <select name="emp_desig_id" class="form-control chosen" required>
                      @if(isset($designation) && count($designation)>0)
                        @foreach ($designation as $desig)
                          @if($desig->desig_id==$employeeDetails->emp_desig_id)
                            <option value="{{$desig->desig_id}}" selected="selected">{{$desig->desig_name}}  {{$desig->desig_specification}}</option>
                          @else
                            <option value="{{$desig->desig_id}}">{{$desig->desig_name}}  {{$desig->desig_specification}}</option>
                          @endif
                        @endforeach
                      @endif
                    </select>
                    @if(Controller::checkLinkPriority('designation')=="1")
                    <a class="btn btn-xs btn-primary" href="{{URL::to('designation-view/create')}}">Add New</a>
                    @endif 
                </div>

              </div>
              
              <div class="form-group">
                
                <label class="col-md-2 control-label labelcol">
                 <span class="labelcol"> Department: </span> 
                </label>

                <div class="col-md-4">
                    <select name="emp_depart_id" id="emp_depart_id" class="form-control chosen" required onchange="getSubDepartment();">
                        @if(isset($department) && count($department)>0)
                          @foreach ($department as $depart)
                          @if($depart->depart_id==$employeeDetails->emp_depart_id)
                            <option value="{{$depart->depart_id}}" selected="selected">{{$depart->depart_name}}</option>
                          @else
                            <option value="{{$depart->depart_id}}">{{$depart->depart_name}}</option>
                          @endif
                          @endforeach
                        @endif
                      </select>
                      @if(Controller::checkLinkPriority('department')=="1")
                      <a class="btn btn-xs btn-primary" href="{{URL::to('department-view/create')}}">Add New</a>
                      @endif                     
                                
                </div>

                <label class="col-md-2 control-label labelcol">
                 <span class="labelcol"> Sub-Department: </span> 
                </label>

                <div class="col-md-4">
                     <select name="emp_sdepart_id" id="emp_sdepart_id" class="form-control chosen span12">
                      @if(isset($subdepartment) && count($subdepartment)>0)
                        @foreach ($subdepartment as $sdepart)
                          @if($sdepart->sdepart_id==$employeeDetails->emp_sdepart_id)
                            <option value="{{$sdepart->sdepart_id}}" selected="selected">{{$sdepart->sdepart_name}}</option>
                          @else
                            <option value="{{$sdepart->sdepart_id}}">{{$sdepart->sdepart_name}}</option>
                          @endif
                        @endforeach
                      @endif
                      </select>
                      @if(Controller::checkLinkPriority('sub-department')=="1") 
                      <a class="btn btn-xs btn-primary" href="{{URL::to('sub-department-view/create')}}">Add New</a>
                      @endif
                </div>

              </div>

              <div class="form-group">

                <label class="col-md-2 control-label labelcol">
                 <span class="labelcol"> Supervisor:  </span>
                </label>

                <div class="col-md-4">
                     <select name="emp_seniorid" id="emp_seniorid" class="form-control chosen">
                      @if($employeeDetails->emp_seniorid=="")
                      <option selected="selected"></option>
                      @endif
                      @if(isset($senioremployee) && count($senioremployee)>0)
                        @foreach ($senioremployee as $senior)
                        @if($senior->emp_id==$employeeDetails->emp_seniorid)
                          <option value="{{$senior->emp_id}}" selected="selected">{{$senior->emp_name}} - {{$senior->emp_empid}}</option>
                        @else
                          <option value="{{$senior->emp_id}}">{{$senior->emp_name}} - {{$senior->emp_empid}}</option>
                        @endif
                        @endforeach
                      @endif
                      </select>                         
                </div>
                
                <label class="col-md-2 control-label labelcol">
                 <span class="labelcol"> Authorized Person: </span> 
                </label>

                <div class="col-md-4">
                     <select name="emp_authperson" id="emp_authperson" class="form-control chosen">
                      @if($employeeDetails->emp_authperson=="")
                      <option selected="selected"></option>
                      @endif
                      @if(isset($authperson) && count($authperson)>0)
                        @foreach ($authperson as $ap)
                        @if($ap->emp_id==$employeeDetails->emp_authperson)
                          <option value="{{$ap->emp_id}}" selected="selected">{{$ap->emp_name}} - {{$ap->emp_empid}}</option>
                        @else
                          <option value="{{$ap->emp_id}}">{{$ap->emp_name}} - {{$ap->emp_empid}}</option>
                        @endif
                        @endforeach
                      @endif
                    </select>                        
                                
                </div>

              </div>

              <div class="form-group">

                <label class="col-md-2 control-label labelcol">
                  <span class="labelcol"> Weekend :  </span>
                </label>

                <div class="col-md-4">
                     <select name="emp_wknd" class="form-control chosen" required>
                        @if($employeeDetails->emp_wknd=="")
                        <option selected="selected"></option>
                        @endif
                        <option value="1" @if($employeeDetails->emp_wknd=="1") selected="selected" @endif>Friday</option>
                        <option value="2" @if($employeeDetails->emp_wknd=="2") selected="selected" @endif>Saturday</option>
                        <option value="3" @if($employeeDetails->emp_wknd=="3") selected="selected" @endif>SunDay</option>
                        <option value="4" @if($employeeDetails->emp_wknd=="4") selected="selected" @endif>Monday</option>
                        <option value="5" @if($employeeDetails->emp_wknd=="5") selected="selected" @endif>Tuesday</option>
                        <option value="6" @if($employeeDetails->emp_wknd=="6") selected="selected" @endif>Wednesday</option>
                        <option value="7" @if($employeeDetails->emp_wknd=="7") selected="selected" @endif>Thursday</option>

                      </select> 
                </div>

                <label class="col-md-2 control-label labelcol">
                 <span class="labelcol"> Vehicle Entitlement: </span> 
                </label>

                <div class="col-md-4">
                      <select name="emp_vehicle" class="form-control chosen" required>
                        @if($employeeDetails->emp_vehicle=="1")
                        <option value="1" selected="selected">Yes</option>
                        <option value="0">No</option>                    
                        @elseif($employeeDetails->emp_vehicle=="0")
                        <option value="0" selected="selected">No</option>
                        <option value="1">Yes</option>
                        @else
                        <option></option>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                        @endif
                      </select>                          
                                
                </div>
              </div>
              
              <div class="form-group">
                  
                <label class="col-md-2 control-label labelcol">
                 <span class="labelcol"> Blood Group: </span> 
                </label>

                <div class="col-md-4">
                    <select name="emp_blgrp" class="form-control chosen" required>                     
                        <option>{{$employeeDetails->emp_blgrp}}</option>
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

                <label class="col-md-2 control-label labelcol">
                 <span class="labelcol"> Education Qualification: </span> 
                </label>

                <div class="col-md-4">
                   <input type="text" class="form-control span12" name="emp_education" value="{{$employeeDetails->emp_education}}" required>                 
                                
                </div>
              </div>
            
              <div class="form-group">
                  
                <label class="col-md-2 control-label labelcol" hidden="">
                 <span class="labelcol"> Daily Working Hour: </span> 
                </label>

                <div class="col-md-4" hidden="">
                <select name="emp_workhr" id="emp_workhr" class="form-control chosen" required onchange="getShift();">
                      @if($employeeDetails->emp_workhr=="1")
                        <option value="1" selected="selected"> 7 hrs </option>
                        <option value="2"> 8 hrs </option>                      
                        <option value="3"> 6 hrs </option>                      
                      @elseif($employeeDetails->emp_workhr=="2")
                        <option value="2" selected="selected"> 8 hrs </option>
                        <option value="1"> 7 hrs </option>
                        <option value="3"> 6 hrs </option>
                        @elseif($employeeDetails->emp_workhr=="3")
                        <option value="2"> 8 hrs </option>
                        <option value="1"> 7 hrs </option>
                        <option value="3" selected="selected"> 6 hrs </option>
                      @else
                        <option></option>
                        <option value="1"> 7 hrs </option>
                        <option value="2"> 8 hrs </option>
                        <option value="3"> 6 hrs </option>
                      @endif
                      </select> 
                </div>

                <label class="col-md-2 control-label labelcol">
                 <span class="labelcol"> OT Entitlement: </span> 
                </label>

                <div class="col-md-4">
                       <select name="emp_otent" class="form-control chosen" required>                     
                      @if($employeeDetails->emp_otent=="1")
                        <option value="1" selected="selected"> Yes </option>
                        <option value="2"> No </option>
                      @elseif($employeeDetails->emp_otent=="2")
                        <option value="2" selected="selected="selected"> No </option>
                        <option value="1"> Yes </option>
                      @else
                        <option></option>
                        <option value="1"> Yes </option>
                        <option value="2"> No </option>
                      @endif   
                      </select>                       
                                
                </div>
              </div>
              
              <div class="form-group">
                  
                <label class="col-md-2 control-label labelcol" hidden="">
                 <span class="labelcol"> Shift: </span> 
                </label>

                <div class="col-md-4" hidden="">
                 <select name="emp_shiftid" id="emp_shiftid" class="form-control chosen" required>
                      <option value="{{$employeeDetails->emp_shiftid}}">
                        @php 
                        if(Controller::getCurrentShiftInfo($employeeDetails->emp_id,date('Y-m-d'))){
                          $shift_id=Controller::getCurrentShiftInfo($employeeDetails->emp_id,date('Y-m-d'))->shift_id;
                          echo Controller::getShiftInfo($shift_id);
                        }
                        @endphp
                      </option>
                    </select>
                    @if(Controller::checkLinkPriority('shift')=="1")  
                    <a class="btn btn-xs btn-primary" href="{{URL::to('shift-view/create')}}">Add New</a>
                    @endif
                </div>

                <label class="col-md-2 control-label labelcol">
                 <span class="labelcol"> Face ID: </span> 
                </label>

                <div class="col-md-4">
                  <input type="text" class="form-control span12" name="emp_machineid" value="{{$employeeDetails->emp_machineid}}" required>        
                </div>
              </div>

              <div class="form-group">
                  
                <label class="col-md-2 control-label labelcol">
                 <span class="labelcol"> NID No.: </span> 
                </label>

                <div class="col-md-4">
                       <input type="text" class="form-control span12" name="emp_nid" value="{{$employeeDetails->emp_nid}}" required> 
                </div>

                <label class="col-md-2 control-label labelcol">
                 <span class="labelcol"> Job Location: </span> 
                </label>

                <div class="col-md-4">
                 <select name="emp_jlid" class="form-control chosen" required>
                      @if(isset($joblocation) && count($joblocation)>0)
                        @foreach ($joblocation as $jl)
                        @if($employeeDetails->emp_jlid==$jl->jl_id)
                          <option value="{{$jl->jl_id}}" selected="selected">{{$jl->jl_name}}</option>
                        @else
                          <option value="{{$jl->jl_id}}">{{$jl->jl_name}}</option>
                        @endif
                        @endforeach
                      @endif
                    </select>
                    @if(Controller::checkLinkPriority('job-location')=="1")   
                    <a class="btn btn-xs btn-primary" href="{{URL::to('job-location-view/create')}}">Add New</a>
                    @endif               
                                
                </div>
              </div>
              
              <div class="form-group">
                
                <label class="col-md-2 control-label labelcol">
                 <span class="labelcol"> Joining Date: </span> 
                </label>

                <div class="col-md-4">
                  <div class="input-group">
                    <input type="date" onblur="GetConfirmationDate();" onchange="GetConfirmationDate();" class="form-control span12" name="emp_joindate" value="{{$employeeDetails->emp_joindate}}" data-date-format="yyyy-mm-dd" id="" required>
                    <!-- <span class="input-group-btn">
                    <button class="btn default" type="button"><i class="fa fa-calendar"></i></button>
                    </span> -->
                  </div>
                </div>

                <label class="col-md-2 control-label labelcol">
                 <span class="labelcol"> Confirmation Date: </span> 
                </label>

                <div class="col-md-4">
                  <div class="input-group">
                    <input type="date" class="form-control span12" name="emp_confirmdate" value="{{$employeeDetails->emp_confirmdate}}" data-date-format="yyyy-mm-dd" id="" required>
                    <!-- <span class="input-group-btn">
                    <button class="btn default" type="button"><i class="fa fa-calendar"></i></button>
                    </span> -->
                  </div>
                </div>
              </div>

              <div class="form-group">
                
                <!-- <label class="col-md-2 control-label labelcol">
                <span class="labelcol"> Provident Fund :  </span>
                </label>

                <div class="col-md-4">
                  <p style="margin-top: 5px">
                    <input type="radio" name="pf" value="1" @if($employeeDetails->pf=="1") checked @endif >&nbsp;<strong>Yes</strong>
                    &nbsp;<input type="radio" name="pf" value="0" @if($employeeDetails->pf=="0") checked @endif>&nbsp;<strong>No</strong>
                  </p>
                </div>  -->

                <!--  <label class="col-md-2 control-label labelcol">
                <span class="labelcol"> Washing Allowance :  </span>
                </label>

                <div class="col-md-4">
                  <p style="margin-top: 5px">
                    <input type="radio" name="washing" value="1" @if($employeeDetails->washing=="1") checked @endif >&nbsp;<strong>Yes</strong>
                    &nbsp;<input type="radio" name="washing" value="0" @if($employeeDetails->washing=="0") checked @endif>&nbsp;<strong>No</strong>
                  </p>
                </div>  -->

                <!-- <label class="col-md-2 control-label labelcol">
                <span class="labelcol"> Leave Festival Allowance :  </span>
                </label>

                <div class="col-md-4">
                  <p style="margin-top: 5px">
                    <input type="radio" name="lfa" value="1" @if($employeeDetails->lfa=="1") checked @endif >&nbsp;<strong>Yes</strong>
                    &nbsp;<input type="radio" name="lfa" value="0" @if($employeeDetails->lfa=="0") checked @endif>&nbsp;<strong>No</strong>
                  </p>
                </div>  -->

                <!-- <label class="col-md-2 control-label labelcol">
                <span class="labelcol"> Tax Allowance :  </span>
                </label>

                <div class="col-md-4">
                  <p style="margin-top: 5px">
                    <input type="radio" name="tax_allow" value="1" @if($employeeDetails->tax_allow=="1") checked @endif >&nbsp;<strong>Yes</strong>
                    &nbsp;<input type="radio" name="tax_allow" value="0" @if($employeeDetails->tax_allow=="0") checked @endif>&nbsp;<strong>No</strong>
                  </p>
                </div>  -->

                <label class="col-md-2 control-label labelcol">
                <span class="labelcol">  E-Mail address: </span> <!--  -->
                </label>

                <div class="col-md-4">
                    <input type="email" class="form-control span12" name="emp_email" value="{{$employeeDetails->emp_email}}">
                </div>
                

              </div>
              
      @endif

              <div class="form-group">

                <label class="col-md-2 control-label">
                  Father's Name: 
                </label>

                <div class="col-md-3">
                  <input type="text" class="form-control span12" name="emp_father" value="{{old('emp_father',$employeeDetails->emp_father)}}" >
                </div>

                <label class="col-md-2 control-label">
                  Mother's Name: 
                </label>

                <div class="col-md-3">
                  <input type="text" class="form-control span12" name="emp_mother" value="{{old('emp_mother',$employeeDetails->emp_mother)}}" >
                </div>

              </div>


              <div class="form-group">

                <label class="col-md-2 control-label labelcol">
                 <span class="labelcol"> Emergency Contact No: </span> 
                </label>

                <div class="col-md-4">
                  <input type="text" class="form-control span12" name="emp_emjcontact" value="{{$employeeDetails->emp_emjcontact}}">
                </div>

                <label class="col-md-2 control-label labelcol">
                 <span class="labelcol"> Current Address:  </span>
                </label>

                <div class="col-md-4">
                 <textarea class="form-control span12" name="emp_crntaddress" style="resize:none;height:50px;">{{$employeeDetails->emp_crntaddress}}</textarea>
                </div>

              </div>
              
              <div class="form-group">

                <label class="col-md-2 control-label labelcol">
                 <span class="labelcol"> Permanent Address:  </span>
                </label>

                <div class="col-md-4">
                  <textarea class="form-control span12" name="emp_prmntaddress" style="resize:none;height:50px;">{{$employeeDetails->emp_prmntaddress}}</textarea>
                  
                </div>

                <label class="col-md-2 control-label labelcol">
                <span class="labelcol"> Image :  </span>
                </label>

                <div class="col-md-4">
                  <input type="file" name="emp_img">
                </div> 
                 </div>

                

             

              <h4 style="padding: 10px 25px; background-color: #d8dcdc;margin: 30px 10px;">Insurance Data</h4>
      
      @if($id->suser_level=="1" or $id->suser_level=="6")
              <div class="form-group">
                <div class="col-md-12">
                  <div class="col-md-6">
                    <label for="self_member_id">Self Member ID</label>
                    <input type="text" class="form-control" name="self_member_id" id="self_member_id" value="{{old('self_member_id',$employeeDetails->insurance->self_member_id)}}">
                  </div>
                  <div class="col-md-6">
                    <label for="datepicker11">Insurance Effective Date</label>
                    <input type="text" class="form-control" name="effective_date" id="datepicker11" readonly style="background: white" data-date-format="yyyy-mm-dd" value="{{old('effective_date',$employeeDetails->insurance->effective_date)}}">
                  </div>
                </div>
              </div>

              <div class="form-group">
                <div class="col-md-12">
                  <div class="col-md-4">
                    <label for="spouse_name">Spouse Name</label>
                    <input type="text" class="form-control" name="spouse_name" id="spouse_name" value="{{old('spouse_name',$employeeDetails->insurance->spouse_name)}}">
                  </div>
                  <div class="col-md-2" hidden="">
                    <label for="spouse_member_id">Spouse Member ID</label>
                    <input type="text" class="form-control" id="spouse_member_id" name="spouse_member_id" value="{{old('spouse_member_id',$employeeDetails->insurance->spouse_member_id)}}">
                  </div>
                  <div class="col-md-4">
                    <label for="datepicker12">Date Of Birth</label>
                    <input type="text" class="form-control" name="spouse_dob" id="datepicker12" readonly style="background: white" data-date-format="yyyy-mm-dd" value="{{old('spouse_dob',$employeeDetails->insurance->spouse_dob)}}">
                  </div>
                  <div class="col-md-2" hidden="">
                    <label for="datepicker13">Insurance Start From</label>
                    <input type="text" class="form-control" name="spouse_start_date" id="datepicker13" readonly style="background: white" data-date-format="yyyy-mm-dd" value="{{old('spouse_start_date',$employeeDetails->insurance->spouse_start_date)}}">
                  </div>
                  <div class="col-md-2" hidden="">
                    <label for="datepicker14">Insurance Valid To</label>
                    <input type="text" class="form-control" name="spouse_end_date" id="datepicker14" readonly style="background: white" data-date-format="yyyy-mm-dd" value="{{old('spouse_end_date',$employeeDetails->insurance->spouse_end_date)}}">
                  </div>

                  <div class="col-md-4">
                    <label for="datepicker14">Spouse Phone</label>
                    <input type="text" class="form-control" name="spouse_phone" value="{{$employeeDetails->spouse_phone}}">
                  </div>
                </div>
              </div>

              <div class="form-group">
                <div class="col-md-12">
                  <div class="col-md-6">
                    <label for="child1_name">Child-1 Name</label>
                    <input type="text" class="form-control" name="child1_name" id="child1_name" value="{{old('child1_name',$employeeDetails->insurance->child1_name)}}">
                  </div>
                  <div class="col-md-2" hidden="">
                    <label for="child1_member_id">Child-1 Member ID</label>
                    <input type="text" class="form-control" id="child1_member_id" name="child1_member_id" value="{{old('child1_member_id',$employeeDetails->insurance->child1_member_id)}}">
                  </div>
                  <div class="col-md-6">
                    <label for="datepicker15">Date Of Birth</label>
                    <input type="text" class="form-control" name="child1_dob" id="datepicker15" readonly style="background: white" data-date-format="yyyy-mm-dd" value="{{old('child1_dob',$employeeDetails->insurance->child1_dob)}}">
                  </div>
                  <div class="col-md-2" hidden="">
                    <label for="datepicker16">Insurance Start From</label>
                    <input type="text" class="form-control" name="child1_start_date" id="datepicker16" readonly style="background: white" data-date-format="yyyy-mm-dd" value="{{old('child1_start_date',$employeeDetails->insurance->child1_start_date)}}">
                  </div>
                  <div class="col-md-2" hidden="">
                    <label for="datepicker17">Insurance Valid To</label>
                    <input type="text" class="form-control" name="child1_end_date" id="datepicker17" readonly style="background: white" data-date-format="yyyy-mm-dd" value="{{old('child1_end_date',$employeeDetails->insurance->child1_end_date)}}">
                  </div>
                </div>
              </div>

              <div class="form-group">
                <div class="col-md-12">
                  <div class="col-md-6">
                    <label for="child2_name">Child-2 Name</label>
                    <input type="text" class="form-control" name="child2_name" id="child2_name" value="{{old('child2_name',$employeeDetails->insurance->child2_name)}}">
                  </div>
                  <div class="col-md-2" hidden="">
                    <label for="child2_member_id">Child-2 Member ID</label>
                    <input type="text" class="form-control" id="child2_member_id" name="child2_member_id" value="{{old('child2_member_id',$employeeDetails->insurance->child2_member_id)}}">
                  </div>
                  <div class="col-md-6">
                    <label for="datepicker18">Date Of Birth</label>
                    <input type="text" class="form-control" name="child2_dob" id="datepicker18" readonly style="background: white" data-date-format="yyyy-mm-dd" value="{{old('child2_dob',$employeeDetails->insurance->child2_dob)}}">
                  </div>
                  <div class="col-md-2" hidden="">
                    <label for="datepicker19">Insurance Start From</label>
                    <input type="text" class="form-control" name="child2_start_date" id="datepicker19" readonly style="background: white" data-date-format="yyyy-mm-dd" value="{{old('child2_start_date',$employeeDetails->insurance->child2_start_date)}}">
                  </div>
                  <div class="col-md-" hidden="">
                    <label for="datepicker20">Insurance Valid To</label>
                    <input type="text" class="form-control" name="child2_end_date" id="datepicker20" readonly style="background: white" data-date-format="yyyy-mm-dd" value="{{old('child2_end_date',$employeeDetails->insurance->child2_end_date)}}">
                  </div>
                </div>
              </div>
      @else

              <div class="form-group">
                <div class="col-md-12">
                  <div class="col-md-4">
                    <label for="spouse_name">Spouse Name</label>
                    <input type="text" class="form-control" name="spouse_name" id="spouse_name" value="{{old('spouse_name',$employeeDetails->insurance->spouse_name)}}">
                  </div>
                  <div class="col-md-4">
                    <label for="child1_name">Child-1 Name</label>
                    <input type="text" class="form-control" name="child1_name" id="child1_name" value="{{old('child1_name',$employeeDetails->insurance->child1_name)}}">
                  </div>
                  <div class="col-md-4">
                    <label for="child2_name">Child-2 Name</label>
                    <input type="text" class="form-control" name="child2_name" id="child2_name" value="{{old('child2_name',$employeeDetails->insurance->child2_name)}}">
                  </div>
                </div>
              </div>
      @endif

      @if($id->suser_level=="1")

              <h4 style="padding: 10px 25px; background-color: #d8dcdc;margin: 30px 10px;">Salary Data</h4>
              <div class="form-group">
                <label class="col-md-2 control-label labelcol">
                  <span class="labelcol"> Provident Fund :  </span>
                  </label>
                  <div class="col-md-2">
                  <p style="margin-top: 5px">
                    <input type="radio" name="pf" value="1" @if($employeeDetails->pf=="1") checked @endif >&nbsp;<strong>Yes</strong>
                    &nbsp;<input type="radio" name="pf" value="0" @if($employeeDetails->pf=="0") checked @endif>&nbsp;<strong>No</strong>
                  </p>
                </div> 

                <label class="col-md-2 control-label labelcol">
                  <span class="labelcol">  Washing Allowance :  </span>
                  </label>
                 <div class="col-md-2">
                  <p style="margin-top: 5px">
                    <input type="radio" name="washing" value="1" @if($employeeDetails->washing=="1") checked @endif >&nbsp;<strong>Yes</strong>
                    &nbsp;<input type="radio" name="washing" value="0" @if($employeeDetails->washing=="0") checked @endif>&nbsp;<strong>No</strong>
                  </p>
                </div> 

                <label class="col-md-2 control-label labelcol">
                  <span class="labelcol">  Leave Festival Allowance :  </span>
                  </label>
                 <div class="col-md-2">
                   <p style="margin-top: 5px">
                    <input type="radio" name="lfa" value="1" @if($employeeDetails->lfa=="1") checked @endif >&nbsp;<strong>Yes</strong>
                    &nbsp;<input type="radio" name="lfa" value="0" @if($employeeDetails->lfa=="0") checked @endif>&nbsp;<strong>No</strong>
                  </p>
                </div> 

                <label class="col-md-2 control-label labelcol">
                  <span class="labelcol">  Tax Allowance :  </span>
                  </label>
                 <div class="col-md-2">
                    <p style="margin-top: 5px">
                    <input type="radio" name="tax_allow" value="1" @if($employeeDetails->tax_allow=="1") checked @endif >&nbsp;<strong>Yes</strong>
                    &nbsp;<input type="radio" name="tax_allow" value="0" @if($employeeDetails->tax_allow=="0") checked @endif>&nbsp;<strong>No</strong>
                  </p>
                </div> 
              </div>

              <div class="form-group">
                  <label class="col-md-2 control-label labelcol">
                  <span class="labelcol"> Official Mobile :  </span>
                  </label>
                  <div class="col-md-2">
                    <input type="text" class="form-control span12" name="emp_officecontact" value="{{$employeeDetails->emp_officecontact}}" required>
                  </div>

                  <label class="col-md-2 control-label labelcol">
                  <span class="labelcol"> Handset/Mobile Allocation Date :  </span>
                  </label>
                  <div class="col-md-2">
                    <input type="date" class="form-control span12" name="emp_handsetallocdate" value="{{$employeeDetails->emp_handsetallocdate}}" required>
                  </div>
                  <label class="col-md-2 control-label labelcol">
                  <span class="labelcol">Mobile Bill Allocation Amount :  </span>
                  </label>
                  <div class="col-md-2">
                    <input type="number" class="form-control span12" name="emp_allocamount" value="{{$employeeDetails->emp_allocamount}}" required>
                  </div>
                </div>

              
              <div class="form-group">
                <div class="col-md-12">
                  <div class="col-md-3">
                    <label for="tin_no">TIN No.</label>
                    <input type="text" class="form-control" name="tin_no" id="tin_no" value="{{$employeeDetails->salary->tin_no}}">
                  </div>
                  <div class="col-md-3">
                    <label for="grade">Grade</label>
                    <input type="text" class="form-control" name="grade" id="grade" value="{{$employeeDetails->salary->grade}}">
                  </div>
                  <div class="col-md-3">
                    <label for="bank_account">Bank Account</label>
                    <input type="text" class="form-control" name="bank_account" id="bank_account" value="{{$employeeDetails->salary->bank_account}}">
                  </div>
                  <div class="col-md-3">
                    <label for="bu_code">Bank Name</label>
                    <input type="text" class="form-control" name="bu_code" id="bu_code" value="{{$employeeDetails->salary->bu_code}}">
                  </div>
                </div>
              </div>

              <div class="form-group">
                <div class="col-md-12">
                  <div class="col-md-3" hidden="">
                    <label for="category">Category</label>
                    <select name="category" class="form-control chosen">
                      <option value="1" @if($employeeDetails->salary->category=="1") selected @endif>Direct</option>
                      <option value="2" @if($employeeDetails->salary->category=="2") selected @endif>Indirect</option>
                    </select>
                  </div>
                  <div class="col-md-3" hidden="">
                    <label for="ten_steps">10 Steps</label>
                    <input type="text" class="form-control" name="ten_steps" id="ten_steps" value="{{$employeeDetails->salary->ten_steps}}">
                  </div>
                  <div class="col-md-3">
                    <label for="gender">Gender</label>
                    <select name="gender" class="form-control chosen">
                      <option value="1" @if($employeeDetails->salary->gender=="1") selected @endif>Male</option>
                      <option value="2" @if($employeeDetails->salary->gender=="2") selected @endif>Female</option>
                    </select>
                  </div>
                  <div class="col-md-3">
                    <label for="gender">Date of execution Starts From :</label>
                    @if(isset($head_date_of_execution))
                    <input type="date" class="form-control span12"  name="head_date_of_execution" value="{{old('head_date_of_execution',$head_date_of_execution->head_date_of_execution)}}" data-date-format="yyyy-mm-dd"  style="background: white" id="" required>
                    @else
                    <input type="date" class="form-control span12"  name="head_date_of_execution" data-date-format="yyyy-mm-dd" style="background: white" id="" required>
                    @endif
                  </div>
                </div>
              </div>

              <div class="form-group">
                <div class="col-md-12">
                  @if(isset($payroll[0]))
                  @foreach ($payroll as $pay)
                    <div class="col-md-5">
                      <label for="basic_salary">{{$pay->head->head_name}}</label>
                      @if($pay->head_id == 1)
                      <input id="test" type="number" min="0" value="{{$payroll[0]->amount}}" class="form-control input" name="salary[{{$pay->head_id}}]" style="border: {{(($pay->head->head_type=='1')? '1px solid green' : '1px solid red')}}">

                     
                      @if($bank_cash[0]->bankcash_status=='1' && $bank_cash[0]->head_id=='1')
                       Bank
                      <input type="radio" name="bank_cash[{{$pay->head_id}}]" value="1" checked="">

                      Cash
                      <input type="radio" name="bank_cash[{{$pay->head_id}}]" value="2">
                      @else
                      Bank
                      <input type="radio" name="bank_cash[{{$pay->head_id}}]" value="1">

                      Cash
                      <input type="radio" name="bank_cash[{{$pay->head_id}}]" value="2" checked="">
                      @endif
                      

                      @elseif($pay->head_id == 2)
                      <input type="hidden" name="" id="rent_percent" value="{{ $payroll[1]->head->head_percentage }}">

                      <input id="rent" type="number" min="0" value="{{ $payroll[1]->amount }}" class="form-control input" name="salary[{{$pay->head_id}}]" style="border: {{(($pay->head->head_type=='1')? '1px solid green' : '1px solid red')}}">

                      @if($bank_cash[1]->bankcash_status=='1' && $bank_cash[1]->head_id=='2')
                       Bank
                      <input type="radio" name="bank_cash[{{$pay->head_id}}]" value="1" checked="">

                      Cash
                      <input type="radio" name="bank_cash[{{$pay->head_id}}]" value="2">
                      @else
                      Bank
                      <input type="radio" name="bank_cash[{{$pay->head_id}}]" value="1">

                      Cash
                      <input type="radio" name="bank_cash[{{$pay->head_id}}]" value="2" checked="">
                      @endif

                       @elseif($pay->head_id == 3)
                      <input type="hidden" name="" id="medical_percent" value="{{ $payroll[2]->head->head_percentage }}">

                      <input id="medical" type="number" min="0" value="{{ $payroll[2]->amount }}" class="form-control input" name="salary[{{$pay->head_id}}]" style="border: {{(($pay->head->head_type=='1')? '1px solid green' : '1px solid red')}}">

                      @if($bank_cash[2]->bankcash_status=='1' && $bank_cash[2]->head_id=='3')
                      Bank
                      <input type="radio" name="bank_cash[{{$pay->head_id}}]" value="1" checked="">

                      Cash
                      <input type="radio" name="bank_cash[{{$pay->head_id}}]" value="2">
                      @else
                      Bank
                      <input type="radio" name="bank_cash[{{$pay->head_id}}]" value="1">

                      Cash
                      <input type="radio" name="bank_cash[{{$pay->head_id}}]" value="2" checked="">
                      @endif

                       @elseif($pay->head_id == 4)
                       <input type="hidden" name="" id="conv_percent" value="{{ $payroll[3]->head->head_percentage }}">

                      <input id="conv" type="number" min="0" value="{{ $payroll[3]->amount }}" class="form-control input" name="salary[{{$pay->head_id}}]" style="border: {{(($pay->head->head_type=='1')? '1px solid green' : '1px solid red')}}">

                       @if($bank_cash[3]->bankcash_status=='1' && $bank_cash[3]->head_id=='4')
                       Bank
                      <input type="radio" name="bank_cash[{{$pay->head_id}}]" value="1" checked="">

                      Cash
                      <input type="radio" name="bank_cash[{{$pay->head_id}}]" value="2">
                      @else
                      Bank
                      <input type="radio" name="bank_cash[{{$pay->head_id}}]" value="1">

                      Cash
                      <input type="radio" name="bank_cash[{{$pay->head_id}}]" value="2" checked="">
                      @endif

                       @elseif($pay->head_id == 5)

                        <input type="hidden" name="" id="washing_percent" value="{{ $payroll[4]->head->head_percentage }}">
                        @if($employeeDetails->washing=='1')
                      <input id="washing" type="number" min="0" value="{{ $payroll[4]->amount }}" class="form-control input" name="salary[{{$pay->head_id}}]" style="border: {{(($pay->head->head_type=='1')? '1px solid green' : '1px solid red')}}">
                      @else
                       <input id="" type="number" min="0" value="0" class="form-control input" name="salary[{{$pay->head_id}}]" style="border: {{(($pay->head->head_type=='1')? '1px solid green' : '1px solid red')}}" readonly="">
                       @endif

                        @if($bank_cash[4]->bankcash_status=='1' && $bank_cash[4]->head_id=='5')
                         Bank
                      <input type="radio" name="bank_cash[{{$pay->head_id}}]" value="1" checked="">

                      Cash
                      <input type="radio" name="bank_cash[{{$pay->head_id}}]" value="2">
                      @else
                      Bank
                      <input type="radio" name="bank_cash[{{$pay->head_id}}]" value="1">

                      Cash
                      <input type="radio" name="bank_cash[{{$pay->head_id}}]" value="2" checked="">
                      @endif


                        @elseif($pay->head_id == 6)
                       
                         <input type="hidden" name="" id="lfa_percent" value="{{ $payroll[5]->head->head_percentage }}">
                        @if($employeeDetails->lfa=='1')
                      <input id="leave_fest" type="number" min="0" value="{{ $payroll[5]->amount }}" class="form-control input" name="salary[{{$pay->head_id}}]" style="border: {{(($pay->head->head_type=='1')? '1px solid green' : '1px solid red')}}">
                      @else
                       <input id="" type="number" min="0" value="0" class="form-control input" name="salary[{{$pay->head_id}}]" style="border: {{(($pay->head->head_type=='1')? '1px solid green' : '1px solid red')}}" readonly="">
                       @endif


                        @if($bank_cash[5]->bankcash_status=='1' && $bank_cash[5]->head_id=='6')
                        Bank
                      <input type="radio" name="bank_cash[{{$pay->head_id}}]" value="1" checked="">

                      Cash
                      <input type="radio" name="bank_cash[{{$pay->head_id}}]" value="2">
                      @else
                      Bank
                      <input type="radio" name="bank_cash[{{$pay->head_id}}]" value="1">

                      Cash
                      <input type="radio" name="bank_cash[{{$pay->head_id}}]" value="2" checked="">
                      @endif
                      @else
                      <input id="" type="number" min="0" value="" class="form-control input" name="salary[{{$pay->head_id}}]" style="border: {{(($pay->head->head_type=='1')? '1px solid green' : '1px solid red')}}">
                      @endif
                    </div>
                  @endforeach
                  @endif
                  <div class="form-group">
                     <div class="col-md-5">

                      @if($employeeDetails->emp_type==9)
                      <label>Minimum Salary</label>
                       <input id="min_salary" type="number" min="0" value="{{ $employeeDetails->min_salary }}" class="form-control input" name="min_salary" style="border:  1px solid green)}}">
                     </div>
                      <div class="col-md-5">
                        <label>Target Percentage</label>
                       <input id="target_percent" type="number" min="0" value="{{$employeeDetails->target_percent}}" class="form-control input" name="target_percent" style="border:  1px solid green)}}">
                       @endif

                     </div>
                   </div>
                   <input type="hidden" name="" class="input" id="emp_type" value="{{ $employeeDetails->emp_type }}">


                </div>
              </div>
      @endif
              <!-- <input type="text" name="" id="sample"> -->


               <div class="form-group">

                <div class="col-md-4">
                   <input type="submit" value="Update" class="btn btn-success">
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
 <script>
        $(".input").on('input', function(){

            var emp_type = document.getElementById('emp_type').value;
            emp_type = parseFloat(emp_type);

            if(emp_type!=9){

            var basic = document.getElementById('test').value;
            basic = parseFloat(basic);

            var rent_percent = document.getElementById('rent_percent').value;
            rent_percent = parseFloat(rent_percent);

            var rent = (basic*rent_percent)/100;

            document.getElementById('rent').value = rent;

            var medical_percent = document.getElementById('medical_percent').value;

            medical_percent = parseFloat(medical_percent);       

            var medical = (basic*medical_percent)/100;

            document.getElementById('medical').value = medical;



            var conv_percent = document.getElementById('conv_percent').value;

            conv_percent = parseFloat(conv_percent);       

            var conv = (basic*conv_percent)/100;

            document.getElementById('conv').value = conv;    


            var lfa_percent = document.getElementById('lfa_percent').value;

            lfa_percent = parseFloat(lfa_percent);       

            var leave_fest = (basic*lfa_percent)/100;

            document.getElementById('leave_fest').value = leave_fest;


            var washing_percent = document.getElementById('washing_percent').value;

            washing_percent = parseFloat(washing_percent);       

            var washing = (basic*washing_percent)/100;

            document.getElementById('washing').value = washing;
            // (fare);
             
             }

    
        });
    </script>

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
function GetConfirmationDate () {
  var emp_joindate=$('#datepicker2').val();
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
        getSeniorEmployee(emp_depart_id);
        getAuthPerson(emp_depart_id);
      }
    });
  }else{
    $('#emp_sdepart_id').html('').trigger("chosen:updated");
    getSeniorEmployee(emp_depart_id);
    getAuthPerson(emp_depart_id);
  }
}

function  getSeniorEmployee(emp_depart_id) {
  if(emp_depart_id!="0"){
    $.ajax({
      url:"{{URL::to('getSeniorEmployee')}}/"+emp_depart_id,
      type:'GET',
      data:{},
      success:function(data) {
        $('#emp_seniorid').html(data).trigger("chosen:updated");
      }
    });
  }else{
    $('#emp_seniorid').html('').trigger("chosen:updated");
  }
}

function  getAuthPerson(emp_depart_id) {
  if(emp_depart_id!="0"){
    $.ajax({
      url:"{{URL::to('getAuthPerson')}}/"+emp_depart_id,
      type:'GET',
      data:{},
      success:function(data) {
        $('#emp_authperson').html(data).trigger("chosen:updated");
      }
    });
  }else{
    $('#emp_authperson').html('').trigger("chosen:updated");
  }
}


function  getShift() {
  var emp_workhr=$('#emp_workhr').val();
  var emp_shiftid=$('#emp_shiftid').val();
  if(emp_shiftid!="0"){
    $.ajax({
      url:"{{URL::to('getShiftForEdit')}}/"+emp_workhr+"/"+emp_shiftid,
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

<script src="{{URL::to('/')}}/public/js/matrix.tables.js"></script>
@endsection