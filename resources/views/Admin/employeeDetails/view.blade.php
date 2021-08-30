@extends('Admin.index')
@section('body')
@php
use App\Http\Controllers\BackEndCon\Controller;
use Illuminate\Support\Facades\Route;
$route=Route::getFacadeRoot()->current()->uri();
@endphp

@if(Controller::checkAppModulePriority($route,'View')=="1")
@else
<script type="text/javascript">location="{{url('/')}}"</script>
@endif

<script src="{{url('/')}}/public/assets/global/plugins/jquery.min.js"></script>
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
                <i class="fa fa-globe"></i>View Employee Information
              </div>

              @if(Controller::checkLinkPriority($route)=="1")
              <a class="btn btn-primary" href="{{url('/employee-details')}}" style="float:right;margin:5px">Go Back</a>
              @endif

               <a href="{{ route('employee-info-print', $emp_id) }}" class="btn btn-primary btn-md" style="float:right;margin:5px" target="_blank">Print</a>

              @if(Controller::checkAppModulePriority($route,'Edit')=="1")
              @if($id->suser_level == 1)
              <a class="btn btn-primary" href="{{url('/employee-details')}}/{{$emp_id}}/edit" style="float:right;margin:5px">Edit</a>
              @endif
              @endif

              @if(Controller::checkAppModulePriority($route,'View History')=="1")
                <a href="{{url('employee-details')}}/{{$employeeDetails->emp_id}}/history" class="btn btn-primary btn-md" style="float:right;margin:5px">View History</a>
              @endif
            
            </div>
            <div class="portlet-body">
              <div class="table-toolbar">
                <div class="row col-md-12 col-md-offset-0">

                  <div class="panel panel-success">
                    <div class="panel-body">
                      <ul class="nav nav-tabs">
                          @if($attendance)
                          <li><a data-toggle="tab" href="#basic_info">Basic Info</a></li>
                          @else
                          <li class="active"><a data-toggle="tab" href="#basic_info">Basic Info</a></li>
                          @endif
                          <li><a data-toggle="tab" href="#details">Detail Info</a></li>
                          <li><a data-toggle="tab" href="#education">Educational Info</a></li>
                          @if($id->suser_level=="1")
                          <li><a data-toggle="tab" href="#salary">Salary Info</a></li>
                          @endif
                          <li><a data-toggle="tab" href="#insurance">Insurance Info</a></li>
                          <li><a data-toggle="tab" href="#documents">Documents</a></li>
                          @if($attendance)
                          <li class="active"><a data-toggle="tab" href="#attendance">Attendance</a></li> 
                          @else
                          <li><a data-toggle="tab" href="#attendance">Attendance</a></li> 
                          @endif  
                          <li><a data-toggle="tab" href="#leave_status">Leave Status</a></li>
                          <li><a data-toggle="tab" href="#payroll">Payroll Information</a></li>
                          <li><a data-toggle="tab" href="#loan">Loan</a></li>
                          <li><a data-toggle="tab" href="#pf">Provident Fund</a></li>
                          <li><a data-toggle="tab" href="#bonus">Bonus</a></li>
                      </ul>
                      <div class="tab-content">
                        @if($attendance)
                        <div id="basic_info" class="tab-pane fade in">
                        @else
                        <div id="basic_info" class="tab-pane fade in active">
                        @endif
                          <div class="panel panel-success">
                            <div class="panel-body">
                              <div class="row">
                                <div class="col-md-2">
                                  @if($employeeDetails->emp_imgext!="")
                                    <img style="width:100%;" class="img img-thumbnail" src="{{url('public/EmployeeImage')}}/{{$employeeDetails->emp_id}}.{{$employeeDetails->emp_imgext}}">
                                    <!-- <img  style="height: 200px;border: 1px solid black;padding: 5px;" src="{{url('public')}}/male.jpg"/> -->
                                  @endif
                                </div>
                                <div class="col-md-10">
                                  <table class="table table-bordered table-striped">
                                    <tbody>
                                      <tr>
                                        <td class="bg-info col-md-2"><strong>Employee Name:</strong></td>
                                        <td>{{$employeeDetails->emp_name}}</td>
                                        <td class="bg-info col-md-2"><strong>Employee Type:</strong></td>
                                        <td>{{$employeeDetails->type->name}}</td>
                                      </tr>
                                      <tr>
                                        <td colspan="4"></td>
                                      </tr>
                                      <tr>
                                        <td class="bg-info col-md-2"><strong>Employee ID:</strong></td>
                                        <td>{{$employeeDetails->emp_empid}}</td>
                                        <td class="bg-info col-md-2"><strong>Designation:</strong></td>
                                        <td>{{((isset($employeeDetails->designation)) ? $employeeDetails->designation->desig_name : '')}}</td>
                                      </tr>
                                      <tr>
                                        <td colspan="4"></td>
                                      </tr>
                                      <tr>
                                        <td class="bg-info col-md-2"><strong>Department:</strong></td>
                                        <td>{{((isset($employeeDetails->department)) ? $employeeDetails->department->depart_name : '')}}</td>
                                        <td class="bg-info col-md-2"><strong>Sub-Department:</strong></td>
                                        <td>
                                          @if(isset($employeeDetails->subdepartment))
                                            {{$employeeDetails->subdepartment->sdepart_name}}
                                          @endif
                                        </td>
                                      </tr>
                                      <tr>
                                        <td colspan="4"></td>
                                      </tr>
                                      <tr>
                                        <td class="bg-info col-md-2"><strong>Supervisor:</strong></td>
                                        <td>@php echo Controller::getSeniorName($employeeDetails->emp_seniorid); @endphp</td>
                                        <td class="bg-info col-md-2"><strong>Authorized Person:</strong></td>
                                        <td>@php echo Controller::getAuthorName($employeeDetails->emp_authperson); @endphp</td>
                                      </tr>
                                    </tbody>
                                  </table>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div id="details" class="tab-pane fade in">
                          <div class="panel panel-success">
                            <div class="panel-body">
                              <table class="table table-bordered table-striped">
                                <tbody>
                                  <tr>
                                    <td class="bg-info col-md-2"><strong>Contact Number:</strong></td>
                                    <td>{{$employeeDetails->emp_phone}}</td>
                                    <td class="bg-info col-md-2"><strong>Date of Birth:</strong></td>
                                    <td>{{$employeeDetails->emp_dob}}</td>
                                  </tr>
                                  <tr>
                                    <td colspan="4"></td>
                                  </tr>
                                  <tr>
                                    <td class="bg-info col-md-2"><strong>Country:</strong></td>
                                    <td>{{(($employeeDetails->country) ? $employeeDetails->country->country_name : '')}}</td>
                                  </tr>
                                  <tr>
                                    <td colspan="4"></td>
                                  </tr>
                                  <tr>
                                    <td class="bg-info col-md-2"><strong>Weekend:</strong></td>
                                    <td>@php echo Controller::weekend($employeeDetails->emp_wknd); @endphp</td>
                                    <td class="bg-info col-md-2"><strong>Vehicle Entitlement:</strong></td>
                                    <td>
                                      @if($employeeDetails->emp_vehicle=="1")
                                      Yes                  
                                      @elseif($employeeDetails->emp_vehicle=="0")
                                      No
                                      @endif
                                    </td>
                                  </tr>
                                  <tr>
                                    <td colspan="4"></td>
                                  </tr>
                                  <tr>
                                    <td class="bg-info col-md-2"><strong>Blood Group:</strong></td>
                                    <td>{{$employeeDetails->emp_blgrp}}</td>
                                    <td class="bg-info col-md-2"><strong>Education Qualification:</strong></td>
                                    <td>{{$employeeDetails->emp_education}}</td>
                                  </tr>
                                  <tr>
                                    <td colspan="4"></td>
                                  </tr>
                                  <tr>
                                   <!--  <td class="bg-info col-md-2" hidden=""><strong>Daily Working Hour:</strong></td> -->
                                    <td hidden="">
                                      @if($employeeDetails->emp_workhr=="1")
                                        7 hrs (+1 hrs lunch)
                                      @elseif($employeeDetails->emp_workhr=="2")
                                        8 hrs (+1 hrs lunch)
                                      @endif
                                    </td>
                                    <td class="bg-info col-md-2"><strong>OT Entitlement:</strong></td>
                                    <td>
                                      @if($employeeDetails->emp_otent=="1")
                                        Yes
                                      @elseif($employeeDetails->emp_otent=="2")
                                        No 
                                      @endif
                                    </td>
                                  </tr>
                                  <tr>
                                    <td colspan="4"></td>
                                  </tr>
                                  <tr>
                                   <!--  <td class="bg-info col-md-2" hidden=""><strong>Shift:</strong></td> -->
                                    <td hidden="">
                                       @php 
                                        if(Controller::getCurrentShiftInfo($employeeDetails->emp_id,date('Y-m-d'))){
                                          $shift_id=Controller::getCurrentShiftInfo($employeeDetails->emp_id,date('Y-m-d'))->shift_id;
                                          echo Controller::getShiftInfo($shift_id); 
                                        }
                                        @endphp
                                    </td>
                                    <td class="bg-info col-md-2"><strong>Face ID:</strong></td>
                                    <td>{{$employeeDetails->emp_machineid}}</td>
                                  </tr>
                                  <tr>
                                    <td colspan="4"></td>
                                  </tr>
                                  <tr>
                                    <td class="bg-info col-md-2"><strong>NID No:</strong></td>
                                    <td>{{$employeeDetails->emp_nid}}</td>
                                    <td class="bg-info col-md-2"><strong>Job Location:</strong></td>
                                    <td>{{(($employeeDetails->joblocation) ? $employeeDetails->joblocation->jl_name : '')}}</td>
                                  </tr>
                                  <tr>
                                    <td colspan="4"></td>
                                  </tr>
                                  <tr>
                                    <td class="bg-info col-md-2"><strong>Joining Date:</strong></td>
                                    <td>{{$employeeDetails->emp_joindate}}</td>
                                    <td class="bg-info col-md-2"><strong>Confirmation Date:</strong></td>
                                    <td>{{$employeeDetails->emp_confirmdate}}</td>
                                  </tr>
                                  <tr>
                                    <td colspan="4"></td>
                                  </tr>
                                  <tr>
                                    <td class="bg-info col-md-2"><strong>E-Mail address:</strong></td>
                                    <td>{{$employeeDetails->emp_email}}</td>
                                    <td class="bg-info col-md-2"><strong>Emergency Contact No:</strong></td>
                                    <td>{{$employeeDetails->emp_emjcontact}}</td>
                                  </tr>
                                  <tr>
                                    <td colspan="4"></td>
                                  </tr>
                                  <tr>
                                    <td class="bg-info col-md-2"><strong>Father's Name:</strong></td>
                                    <td>{{$employeeDetails->emp_father}}</td>
                                    <td class="bg-info col-md-2"><strong>Mother's Name:</strong></td>
                                    <td>{{$employeeDetails->emp_mother}}</td>
                                  </tr>
                                  <tr>
                                    <td colspan="4"></td>
                                  </tr>
                                  <tr>
                                    <td class="bg-info col-md-2"><strong>Current Address:</strong></td>
                                    <td>{{$employeeDetails->emp_crntaddress}}</td>
                                    <td class="bg-info col-md-2"><strong>Permanent Address:</strong></td>
                                    <td>{{$employeeDetails->emp_prmntaddress}}</td>
                                  </tr>
                                </tbody>
                              </table>
                            </div>
                          </div>
                        </div>

                        <div id="education" class="tab-pane fade in">
                          <div class="panel panel-success">
                            <div class="panel-body">
                              <table class="table table-bordered table-striped">
                                <tbody>
                                  <tr>
                                    <td class="bg-info col-md-2"><strong>Level Of Education:</strong></td>
                                    <td>{{$employeeDetails->education->level_of_education}}</td>
                                    <td class="bg-info col-md-2"><strong>Exam/Degree/Title :</strong></td>
                                    <td>{{$employeeDetails->education->exam_title}}</td>
                                  </tr>
                                  <tr>
                                    <td colspan="4"></td>
                                  </tr>
                                  <tr>
                                    <td class="bg-info col-md-2"><strong>Concentration/ Major/Group:</strong></td>
                                    <td>{{$employeeDetails->education->group}}</td>
                                    <td class="bg-info col-md-2"><strong>Institute Name :</strong></td>
                                    <td>{{$employeeDetails->education->institute}}</td>
                                  </tr>
                                  <tr>
                                    <td colspan="4"></td>
                                  </tr>
                                  <tr>
                                    <td class="bg-info col-md-2"><strong>Result:</strong></td>
                                    <td>{{$employeeDetails->education->result}}</td>
                                    <td class="bg-info col-md-2"><strong>CGPA :</strong></td>
                                    <td>{{$employeeDetails->education->cgpa}}</td>
                                  </tr>
                                  <tr>
                                    <td colspan="4"></td>
                                  </tr>
                                  <tr>
                                    <td class="bg-info col-md-2"><strong>Scale:</strong></td>
                                    <td>{{$employeeDetails->education->scale}}</td>
                                    <td class="bg-info col-md-2"><strong>Year of Passing :</strong></td>
                                    <td>{{$employeeDetails->education->year}}</td>
                                  </tr>
                                  <tr>
                                    <td colspan="4"></td>
                                  </tr>
                                  <tr>
                                    <td class="bg-info col-md-2"><strong>Duration (Years):</strong></td>
                                    <td>{{$employeeDetails->education->duration}}</td>
                                  </tr>
                                  <tr>
                                    <td colspan="4">
                                      <a class="btn btn-md btn-primary" onclick="EditEducation('{{$employeeDetails->emp_id}}')"><i class="fa fa-edit"></i>&nbsp;Edit</a>
                                    </td>
                                  </tr>
                                </tbody>
                              </table>
                            </div>
                          </div>
                        </div>

                        @if($id->suser_level=="1")
                        <div id="salary" class="tab-pane fade">
                          <div class="panel panel-success">
                            <div class="panel-body">
                              @if(isset($employeeDetails->salary))
                              <table class="table table-bordered table-striped">
                                <tbody>
                                  <tr>
                                    <td class="bg-info col-md-2"><strong>TIN No.:</strong></td>
                                    <td>{{$employeeDetails->salary->tin_no}}</td>
                                    <td class="bg-info col-md-2"><strong>Grade :</strong></td>
                                    <td>{{$employeeDetails->salary->grade}}</td>
                                  </tr>
                                  <tr>
                                    <td colspan="4"></td>
                                  </tr>
                                  <tr>
                                    <td class="bg-info col-md-2"><strong>Bank Account:</strong></td>
                                    <td>{{$employeeDetails->salary->bank_account}}</td>
                                    <td class="bg-info col-md-2"><strong>BU Code:</strong></td>
                                    <td>{{$employeeDetails->salary->bu_code}}</td>
                                  </tr>
                                  <tr>
                                    <td colspan="4"></td>
                                  </tr>
                                  <tr>
                                    <td class="bg-info col-md-2"><strong>Category:</strong></td>
                                    <td>
                                      @if($employeeDetails->salary->category=="1") Direct @elseif($employeeDetails->salary->category=="2") Indirect @endif
                                    </td>
                                    <td class="bg-info col-md-2"><strong>10 Steps :</strong></td>
                                    <td>{{$employeeDetails->salary->ten_steps}}</td>
                                  </tr>
                                  <tr>
                                    <td colspan="4"></td>
                                  </tr>
                                  <tr>
                                    <td class="bg-info col-md-2"><strong>Gender:</strong></td>
                                    <td>@if($employeeDetails->salary->gender=="1") Male @elseif($employeeDetails->salary->gender=="2") Female @endif</td>
                                    <td class="bg-info col-md-2"><strong>Provident Fund:</strong></td>
                                    <td>
                                      @if($employeeDetails->pf=="1") Yes @elseif($employeeDetails->pf=="2") No @endif
                                    </td>
                                  </tr>
                                  <tr>
                                    <td colspan="4"></td>
                                  </tr>
                                  <tr>
                                    <td class="bg-info col-md-2"><strong>Date of execution Starts From:</strong></td>
                                    @if(isset($head_date_of_execution))
                                    <td>{{$head_date_of_execution->head_date_of_execution}}</td>

                                    @endif
                                  </tr>
                                  <tr>
                                    <td colspan="4"></td>
                                  </tr>
                                  <tr>
                                  @php
                                  $tdcount=0;
                                  @endphp
                                  @if(isset($payroll[0]))
                                  @foreach ($payroll as $pay)
                                  @php
                                  $tdcount++;
                                  @endphp
                                    <td class="bg-info col-md-2"><strong>{{$pay->head->head_name}}:</strong></td>
                                    <td style="text-align: left;font-size:14px;color:{{(($pay->head->head_type=='1')? 'green' : 'red')}}">
                                      {{$pay->amount}}
                                    </td>
                                  @if($tdcount>0 &&$tdcount%2=="0")
                                  </tr>
                                  <tr>
                                    <td colspan="4"></td>
                                  </tr>
                                  <tr>
                                  @endif
                                  @endforeach
                                  @endif
                                </tbody>
                              </table>
                              @endif
                            </div>
                          </div>
                        </div>
                        @endif
                        <div id="insurance" class="tab-pane fade">
                          <div class="panel panel-success">
                            <div class="panel-body">
                              @if(isset($employeeDetails->insurance))
                              <table class="table table-bordered table-striped">
                                <tbody>
                                  <tr>
                                    <td class="bg-info col-md-2"><strong>Self Member ID:</strong></td>
                                    <td class="col-md-4">{{$employeeDetails->insurance->self_member_id}}</td>
                                    <td class="bg-info col-md-2"><strong>Effective Date :</strong></td>
                                    <td class="col-md-4">{{$employeeDetails->insurance->effective_date}}</td>
                                  </tr>
                                  <tr>
                                    <td colspan="4"></td>
                                  </tr>
                                  <tr>
                                    <td class="bg-info col-md-2"><strong>Spouse Name:</strong></td>
                                    <td class="col-md-4">{{$employeeDetails->insurance->spouse_name}}</td>
                                    <td class="bg-info col-md-2"><strong>Spouse Member ID :</strong></td>
                                    <td class="col-md-4">{{$employeeDetails->insurance->spouse_member_id}}</td>
                                  </tr>
                                  <tr>
                                    <td colspan="4"></td>
                                  </tr>
                                  <tr>
                                    <td class="bg-info col-md-2"><strong>Spouse Date of Birth:</strong></td>
                                    <td class="col-md-4">{{$employeeDetails->insurance->spouse_dob}}</td>
                                    <td class="bg-info col-md-2"><strong>Spouse Insurance From :</strong></td>
                                    <td class="col-md-4">{{$employeeDetails->insurance->spouse_start_date}}</td>
                                  </tr>
                                  <tr>
                                    <td colspan="4"></td>
                                  </tr>
                                  <tr>
                                    <td class="bg-info col-md-2"><strong>Spouse Date of Birth:</strong></td>
                                    <td class="col-md-4">{{$employeeDetails->insurance->spouse_dob}}</td>
                                    <td class="bg-info col-md-2"><strong>Spouse Insurance to :</strong></td>
                                    <td>{{$employeeDetails->insurance->spouse_end_date}}</td>
                                  </tr>
                                  <tr>
                                    <td colspan="4"></td>
                                  </tr>
                                  <tr>
                                    <td class="bg-info col-md-2"><strong>Child-1 Name:</strong></td>
                                    <td class="col-md-4">{{$employeeDetails->insurance->child1_name}}</td>
                                    <td class="bg-info col-md-2"><strong>Child-1 Member ID :</strong></td>
                                    <td class="col-md-4">{{$employeeDetails->insurance->child1_member_id}}</td>
                                  </tr>
                                  <tr>
                                    <td colspan="4"></td>
                                  </tr>
                                  <tr>
                                    <td class="bg-info col-md-2"><strong>Child-1 Date of Birth:</strong></td>
                                    <td class="col-md-4">{{$employeeDetails->insurance->child1_dob}}</td>
                                    <td class="bg-info col-md-2"><strong>Child-1 Insurance From :</strong></td>
                                    <td class="col-md-4">{{$employeeDetails->insurance->child1_start_date}}</td>
                                  </tr>
                                  <tr>
                                    <td colspan="4"></td>
                                  </tr>
                                  <tr>
                                    <td class="bg-info col-md-2"><strong>Child-1 Date of Birth:</strong></td>
                                    <td class="col-md-4">{{$employeeDetails->insurance->child1_dob}}</td>
                                    <td class="bg-info col-md-2"><strong>Child-1 Insurance to :</strong></td>
                                    <td>{{$employeeDetails->insurance->child1_end_date}}</td>
                                  </tr>
                                  <tr>
                                    <td colspan="4"></td>
                                  </tr>
                                  <tr>
                                    <td class="bg-info col-md-2"><strong>Child-2 Name:</strong></td>
                                    <td class="col-md-4">{{$employeeDetails->insurance->child2_name}}</td>
                                    <td class="bg-info col-md-2"><strong>Spouse Member ID :</strong></td>
                                    <td class="col-md-4">{{$employeeDetails->insurance->child2_member_id}}</td>
                                  </tr>
                                  <tr>
                                    <td colspan="4"></td>
                                  </tr>
                                  <tr>
                                    <td class="bg-info col-md-2"><strong>Child-2 Date of Birth:</strong></td>
                                    <td class="col-md-4">{{$employeeDetails->insurance->child2_dob}}</td>
                                    <td class="bg-info col-md-2"><strong>Child-2 Insurance From :</strong></td>
                                    <td class="col-md-4">{{$employeeDetails->insurance->child2_start_date}}</td>
                                  </tr>
                                  <tr>
                                    <td colspan="4"></td>
                                  </tr>
                                  <tr>
                                    <td class="bg-info col-md-2"><strong>Child-2 Date of Birth:</strong></td>
                                    <td class="col-md-4">{{$employeeDetails->insurance->child2_dob}}</td>
                                    <td class="bg-info col-md-2"><strong>Child-2 Insurance to :</strong></td>
                                    <td>{{$employeeDetails->insurance->child2_end_date}}</td>
                                  </tr>
                                  <tr>
                                    <td colspan="4"></td>
                                  </tr>
                                </tbody>
                              </table>
                              @endif
                            </div>
                          </div>
                        </div>
                        <div id="documents" class="tab-pane fade">
                          <div class="panel panel-success">
                            <div class="panel-body">
                              <span id="hidden_table_title" style="display: none;">Documents of {{$employeeDetails->emp_name}} ({{$employeeDetails->emp_empid}})</span>
                              <div class="actions pull-right">
                                <a class="btn btn-md btn-primary pull-left" onclick="New()" style="margin-right:5px"><i class="fa fa-upload"></i>&nbsp;Upload New</a>
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
                            </div>
                            <div class="panel-body">
                              <table class="table table-bordered table-striped table-hover" id="sample_3">
                                <thead>
                                  <tr>
                                    <th>SL</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>File</th>
                                    <th>Uploaded By</th>
                                    <th>Actions</th>
                                  </tr>
                                </thead>
                                <tbody>
                                @if(isset($employeeDetails->documents[0]))
                                @foreach ($employeeDetails->documents as $key => $document)
                                  <tr id="tr-{{$document->id}}">
                                    <td>{{$key+1}}</td>
                                    <td>{{$document->title}}</td>
                                    <td>
                                      <div>
                                        {!! $document->description !!}
                                      </div>
                                    </td>
                                    <td>
                                      <a onclick="View('{{$document->file}}')">{{$document->name}}</a>
                                    </td>
                                    <td>
                                      {{$document->uploader->emp_name}} ({{$document->uploader->emp_empid}})
                                    </td>
                                    <td>
                                      <a onclick="Edit('{{$document->id}}')" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i></a>
                                      <a onclick="Delete('{{$document->id}}')" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></a>
                                    </td>
                                  </tr>
                                @endforeach
                                @endif
                                </tbody>
                              </table>
                            </div>
                          </div>
                        </div>
                        @if($attendance)
                        <div id="attendance" class="tab-pane fade in active">
                        @else
                        <div id="attendance" class="tab-pane fade in">
                        @endif
                          <div class="panel panel-success">
                            <div class="panel-body" >
                              <div class="row">
                                <div class="col-md-2 col-md-offset-2 text-right">
                                  <p style="margin-top: 7px"><strong>Choose Date Range :</strong></p>
                                </div>
                                <div class="col-md-6">
                                  @if(!isset($start_date) && !isset($end_date))
      
    <div class="col-md-4">
        Start Date
        <input type="date" class="form-control" id="start_date" style="background: white;padding: 0px 0px 0px 10px" required=""><br>
         End Date
        <input type="date" class="form-control" id="end_date" style="background: white;padding: 0px 0px 0px 10px" required="">

    </div><br><br><br>
    @endif
     @if(isset($start_date) && isset($end_date))
     
    <div class="col-md-4">
      Start Date
        <input type="date" class="form-control" id="start_date" style="background: white;padding: 0px 0px 0px 10px" required="" value="{{ $start_date }}"><br>
         End Date
        <input type="date" class="form-control" id="end_date" style="background: white;padding: 0px 0px 0px 10px" required="" value="{{ $end_date }}">

    </div><br><br><br>
    @endif
                                </div>
                                <div class="col-md-2 text-left">
                                  <a class="btn btn-md btn-primary" onclick="searchAttendance();"><i class="fa fa-search"></i>&nbsp;Search Attendance</a>
                                </div>
                              </div>
                            </div>
                            <hr style="margin-top: 0px">
                            <div class="panel-body">
                              <div class="row" style="margin-bottom: 10px">
                                <div class="col-md-10">
                                    @if($start_date!=$end_date)
                                    <center><h5><strong>{{date('l F j,Y',strtotime($start_date))}}</strong> to <strong>{{date('l F j,Y',strtotime($end_date))}}</strong></h5></center>
                                    @else
                                    <center><h5><strong>{{date('l F j,Y',strtotime($start_date))}}</strong></h5></center>
                                    @endif
                                </div>
                                <div class="col-md-2">
                                  <span id="hidden_table_title_4" style="display: none;">Attendance of {{$employeeDetails->emp_name}} ({{$employeeDetails->emp_empid}})</span>
                                  <div class="actions pull-right">
                                    <div class="btn-group">
                                        <a class="btn red btn-outline btn-circle" href="javascript:;" data-toggle="dropdown">
                                            <i class="fa fa-gears"></i>
                                            <span> Tools </span>
                                            <i class="fa fa-angle-down"></i>
                                        </a>
                                        <ul class="dropdown-menu pull-right" id="sample_4_tools">
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
                                </div>
                              </div>
                              
                              <table class="table table-striped table-bordered table-hover table-checkable order-column" id="sample_4">
                                <thead>
                                  <tr>
                                    <th>SL</th>
                                    <th>Employee ID</th>
                                    <!-- @if($id->suser_level=="1")
                                    <th>Face ID</th>
                                    @endif -->
                                    <th>Employee Name</th>
                                    <th>Date</th>
                                    <th>Shift</th>
                                    <th>Entry Time</th>
                                    <th>Exit Time</th>
                                    <th>Late Entry</th>
                                    <th>Early Exit</th>
                                    <th>TWH</th>
                                    <!-- <th>Night</th> -->
                                    <th>Status</th>
                                    <!-- <th>Generated at</th> -->
                                  </tr>
                                </thead>
                        
                                <tbody>
                                @if(isset($attendances[0]))
                                @foreach ($attendances as $key => $attendance)
                                  <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{$attendance->employee->emp_empid}}</td>
                                    <!-- @if($id->suser_level=="1")
                                    <td>{{$attendance->employee->emp_machineid}}</td>
                                    @endif -->
                                    <td>{{$attendance->employee->emp_name}}</td>
                                    <td>{{$attendance->date}}</td>
                                    <td>
                                      @if($attendance->shift)
                                      {{$attendance->shift->shift_stime}} to {{$attendance->shift->shift_etime}}
                                      @endif
                                    </td>
                                    <td>
                                      @if(count(explode(',',$attendance->entry_time))>1)
                                      {!! Controller::attendanceStatusText($attendance->entry_time) !!}
                                      @elseif($attendance->entry_time!="" && $attendance->entry_time!="00:00:00")
                                      {{date('g:i a',strtotime($attendance->entry_time))}}
                                      @endif
                                    </td>
                                    <td>
                                      @if(count(explode(',',$attendance->exit_time))>1)
                                      {!! Controller::attendanceStatusText($attendance->exit_time) !!}
                                      @elseif($attendance->exit_time!="" && $attendance->exit_time!="00:00:00")
                                      {{date('g:i a',strtotime($attendance->exit_time))}}
                                      @endif
                                    </td>
                                    <td>{{$attendance->late_entry}}</td>
                                    <td>{{$attendance->early_exit}}</td>
                                    <td>{{$attendance->twh}}</td>
                                   <!--  <td>{{$attendance->night}}</td> -->
                                    <td>
                                      {!! Controller::attendanceStatusButton($attendance->status) !!}
                                    </td>
                                   <!--  <td>
                                      {{date('Y-m-d g:i a',strtotime($attendance->generated_at))}}
                                    </td> -->
                                  </tr>
                                @endforeach
                                @endif
                                </tbody>

                              </table>
                            </div>
                          </div>
                        </div>

                        <div id="leave_status" class="tab-pane fade">
                          <div class="panel panel-success">
                            <div class="panel-body">
                                <span id="hidden_table_title_8" style="display: none;">Leave Status of {{$employeeDetails->emp_name}} ({{$employeeDetails->emp_empid}})</span>
                                <div class="actions pull-right">
                                  <div class="btn-group">
                                      <a class="btn red btn-outline btn-circle" href="javascript:;" data-toggle="dropdown">
                                          <i class="fa fa-gears"></i>
                                          <span> Tools </span>
                                          <i class="fa fa-angle-down"></i>
                                      </a>
                                      <ul class="dropdown-menu pull-right" id="sample_8_tools">
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
                            </div>
                            <div class="panel-body">
                              <table class="table table-bordered table-striped table-hover" id="sample_8">
                                <thead>
                                  <tr>
                                    <th>Leave Type</th>
                                    <th>Qouta Day</th>
                                    <th>Leave Taken</th>
                                    <th>Remaining Leave</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  {!! $leave_status !!}
                                </tbody>
                              </table>
                            </div>
                          </div>
                        </div>

                        <div id="payroll" class="tab-pane fade">
                          <div class="panel panel-success">
                            <div class="panel-body">
                              <span id="hidden_table_title_5" style="display: none;">Payroll Information of {{$employeeDetails->emp_name}} ({{$employeeDetails->emp_empid}})</span>
                              <div class="actions pull-right">
                                <div class="btn-group">
                                    <a class="btn red btn-outline btn-circle" href="javascript:;" data-toggle="dropdown">
                                        <i class="fa fa-gears"></i>
                                        <span> Tools </span>
                                        <i class="fa fa-angle-down"></i>
                                    </a>
                                    <ul class="dropdown-menu pull-right" id="sample_5_tools">
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
                            </div>

                            <div class="panel-body">
                              @php
                                $nights=0;
                                $pa=0;
                                $gross=0;
                                $na=0;
                                $due=0;
                                $tax=0;
                                $pf=0;
                                $adv=0;
                                $addition=0;
                                $deduction=0;
                                $salary=0;
                              @endphp

                              @if(isset($PayrollSummery[0]))
                              <table class="table table-bordered" id="sample_5">
                                <thead>
                                  <tr>
                                    <th style="width: 1%"></th>
                                    <th style="width: 1%">SL</th>
                                    <th>Month</th>
                                    <th>Days</th>

                                    @if(isset($PayrollSummery[0]->extends[0]))
                                    @foreach ($PayrollSummery[0]->extends as $extends)
                                      @if($extends->head->head_type=='1')
                                      <td class="text-success">
                                        {{$extends->head->head_name}}
                                      </td>
                                      @endif
                                    @endforeach
                                    @endif
                                   <!--  <td class="text-success">
                                      Night Allow.
                                    </td>
                                    <td class="text-success">
                                      Payment Allow.
                                    </td> -->

                                    <!-- <th class="text-primary">
                                      Total Taka
                                    </th> -->

                                    <!-- addition head -->
                                    @if(isset($PayrollSummery[0]->heads[0]))
                                    @foreach ($PayrollSummery[0]->heads as $head)
                                      @if($head->head->head_type=='1')
                                        <th class="text-success">
                                          {{$head->head->head_name}}
                                        </th>
                                      @endif
                                    @endforeach
                                    @endif

                                    <!-- extra deduction -->
                                    <!-- @if(isset($PayrollSummery[1]->heads[0]))
                                    @foreach ($PayrollSummery[1]->heads as $head)
                                      @if($head->head->head_type=='0')
                                        <th class="text-danger">
                                          {{$head->head->head_name}}
                                        </th>
                                      @endif
                                    @endforeach
                                    @endif -->

                                    @if(isset($PayrollSummery[0]->extends[0]))
                                    @foreach ($PayrollSummery[0]->extends as $extends)
                                      @if($extends->head->head_type=='1')
                                      <td class="text-success">
                                        {{$extends->head->head_name}} Amount
                                      </td>
                                      @endif
                                    @endforeach
                                    @endif

                                   <!--  <td class="text-success">
                                      Night Allow.
                                    </td>
                                    <td class="text-success">
                                      Due
                                    </td> -->
                                     <th class="text-success">Total Taka</th>


                                     @if($ProvidentSetup)
                                    <th class="text-danger">P.F.</th>
                                    @endif

                                    <!-- deduction head -->
                                    @if(isset($PayrollSummery[0]->heads[0]))
                                    @foreach ($PayrollSummery[0]->heads as $head)
                                      @if($head->head->head_type=='0')
                                        <th class="text-danger">
                                          {{$head->head->head_name}}
                                        </th>
                                      @endif
                                    @endforeach
                                    @endif

                                    @if(isset($PayrollSummery[0]->extends[0]))
                                    @foreach ($PayrollSummery[0]->extends as $extends)
                                      @if($extends->head->head_type=='0')
                                      <td class="text-danger">
                                        {{$extends->head->head_name}}
                                      </td>
                                      <td class="text-danger">
                                        {{$extends->head->head_name}} Amount
                                      </td>
                                      @endif
                                    @endforeach
                                    @endif

                                   <!--  <th class="text-danger">Tax</th> -->
                                   
                                    <th class="text-danger">LWP & Other Deduction</th>

                                   
                                   
                                    <th class="text-success">Total Net Salary</th>

                                    <!-- <th>Bank Account</th> -->
                                  </tr>
                                </thead>
                        
                                <tbody>
                                  @foreach ($PayrollSummery as $key => $payroll)
                                  @php
                                  $payrollmonth=\App\Month::find((int)(explode('-',$payroll->payroll_date_from)[1]));
                                  @endphp
                                    <tr>
                                      <td class="text-center"><a href="{{url('/')}}/generate-month-wise-payroll/{{$payroll->employee->emp_id}}&{{$payroll->payroll_date_from}}&{{$payroll->payroll_date_to}}/payslip" target="_blank" class="btn btn-xs btn-success">Pay Slip</a></td>
                                      <td>{{$key+1}}</td>
                                      <td>
                                        {{$payrollmonth->month}} ({{explode('-',$payroll->payroll_date_from)[0]}})
                                      </td>
                                      <td>
                                        {{$payrollmonth->days}}
                                      </td>

                                      @if(isset($payroll->extends[0]))
                                      @foreach ($payroll->extends as $extends)
                                        @if($extends->head->head_type=='1')
                                        <td class="text-right text-success">
                                          {{$extends->head_quantity}}
                                        </td>
                                        @endif
                                      @endforeach
                                      @endif
                                      
                                     <!--  <td class="text-right text-success">
                                        {{$payroll->nights}}
                                        @php
                                          $nights+=$payroll->nights;
                                        @endphp
                                      </td> -->
                                      <!-- <td class="text-right text-success">
                                        {{$payroll->pa}}
                                        @php
                                          $pa+=$payroll->pa;
                                        @endphp
                                      </td> -->

                                      <!-- <td class="text-right text-primary">
                                          @money($gross_amount[$payroll->emp_id])
                                          @php
                                            $gross+=$gross_amount[$payroll->emp_id];
                                          @endphp
                                      </td> -->

                                      @if(isset($payroll->heads[0]))
                                      @foreach ($payroll->heads as $head)
                                        @if($head->head->head_type=='1')
                                          <td class="text-right text-success">
                                            @if($head->head->head_id!="5")
                                              @money($head->head_amount)
                                            @else
                                              @money($head->head_amount*$payroll->pa)
                                            @endif
                                          </td>
                                        @endif
                                      @endforeach
                                      @endif

                                      @if(isset($payroll->extends[0]))
                                      @foreach ($payroll->extends as $extends)
                                        @if($extends->head->head_type=='1')
                                        <td class="text-right text-success">
                                          @money($extends->head_amount)
                                        </td>
                                        @endif
                                      @endforeach
                                      @endif

                                     <!--  <td class="text-right text-success">
                                        @money($payroll->night_allowance)
                                        @php
                                          $na+=$payroll->night_allowance;
                                        @endphp
                                      </td>
                                      <td class="text-right text-success">
                                        @money($payroll->due)
                                        @php
                                          $due+=$payroll->due;
                                        @endphp
                                      </td> -->
                                      <td class="text-right text-{{(($payroll->addition>=0) ? 'success' : 'danger')}}">
                                        @money($payroll->addition)
                                        @php
                                          $addition+=$payroll->addition;
                                        @endphp
                                      </td>

                                      @if($ProvidentSetup)
                                      <td class="text-right text-danger">
                                        @money($payroll->provident_fund)
                                        @php
                                          $pf+=$payroll->provident_fund;
                                        @endphp
                                      </td>
                                      @endif
                                      <!-- deduction -->
                                      @if(isset($payroll->heads[0]))
                                      @foreach ($payroll->heads as $head)
                                        @if($head->head->head_type=='0')
                                          <td class="text-right text-danger">
                                            @money($head->head_amount)
                                          </td>
                                        @endif
                                      @endforeach
                                      @endif

                                      
                                      @if(isset($payroll->extends[0]))
                                      @foreach ($payroll->extends as $extends)
                                        @if($extends->head->head_type=='0')
                                        <td class="text-right text-danger">
                                          {{$extends->head_quantity}}
                                        </td>
                                        <td class="text-right text-danger">
                                          @money($extends->head_amount)
                                        </td>
                                        @endif
                                      @endforeach
                                      @endif
                                      <!-- <td class="text-right text-danger">
                                        @money($payroll->tax)
                                        @php
                                          $tax+=$payroll->tax;
                                        @endphp
                                      </td> -->
                                      
                                      <td class="text-right text-danger">
                                        @money($payroll->advance)
                                        @php
                                          $adv+=$payroll->advance;
                                        @endphp
                                      </td>

                                      <!-- <td class="text-right text-danger">
                                  <span id="deduction-{{$payroll['emp_id']}}">{{$payroll['deduction']}}</span> -->
                                  <input type="hidden" name="deduction[{{$payroll['emp_id']}}]" id="deduction-field-{{$payroll['emp_id']}}" value="{{$payroll['deduction']}}">
                                <!-- </td> -->

                                      
                                      
                                      <td class="text-right text-{{(($payroll->salary>=0) ? 'success' : 'danger')}}">
                                        @money($payroll->salary)
                                        @php
                                          $salary+=$payroll->salary;
                                        @endphp
                                      </td>

                                      <!-- <td>{{(($payroll->employee->salary) ? $payroll->employee->salary->bank_account : '' )}}</td> -->
                                    </tr>
                                  @endforeach

                                    <tr>
                                      <td class="text-center"><a disabled class="btn btn-xs btn-success">Pay Slip</a></td>
                                      <td>{{$key+2}}</td>
                                      <td></td>
                                      <td><strong>Total :</strong></td>

                                      @if(isset($payroll->extends[0]))
                                      @foreach ($payroll->extends as $extends)
                                        @if($extends->head->head_type=='1')
                                        <td class="text-right text-success">
                                          {{$extendsqty[$extends->head->head_id]}}
                                        </td>
                                        @endif
                                      @endforeach
                                      @endif
                                     <!--  <td class="text-right text-success">
                                        {{$nights}}
                                      </td> -->
                                      <!-- <td class="text-right text-success">
                                        {{$pa}}
                                      </td> -->

                                     <!--  <td class="text-right text-primary">
                                        @money($gross)
                                      </td> -->

                                      <!-- addition -->
                                      @if(isset($payroll->heads[0]))
                                      @foreach ($payroll->heads as $head)
                                        @if($head->head->head_type=='1')
                                          <td class="text-right text-success">
                                            @money($heads[$head->head->head_id])
                                          </td>
                                        @endif
                                      @endforeach
                                      @endif

                                      @if(isset($payroll->extends[0]))
                                      @foreach ($payroll->extends as $extends)
                                        @if($extends->head->head_type=='1')
                                        <td class="text-right text-success">
                                          @money($extendsqty[$extends->head->head_id])
                                        </td>
                                        @endif
                                      @endforeach
                                      @endif

                                     <!--  <td class="text-right text-success">
                                        @money($na)
                                      </td>
                                      <td class="text-right text-success">
                                        @money($due)
                                      </td> -->
                                      <td class="text-right text-{{(($addition>=0) ? 'success' : 'danger')}}">@money($addition)</td>

                                      @if($ProvidentSetup)
                                      <td class="text-right text-danger">@money($pf)</td>
                                      @endif
                                      <!-- deduction -->
                                      @if(isset($payroll->heads[0]))
                                      @foreach ($payroll->heads as $head)
                                        @if($head->head->head_type=='0')
                                          <td class="text-right text-danger">
                                            @money($heads[$head->head->head_id])
                                          </td>
                                        @endif
                                      @endforeach
                                      @endif

                                      @if(isset($payroll->extends[0]))
                                      @foreach ($payroll->extends as $extends)
                                        @if($extends->head->head_type=='0')
                                        <td class="text-right text-danger">
                                          @money($extendsqty[$extends->head->head_id])
                                        </td>
                                        <td class="text-right text-danger">
                                          @money($extendsamount[$extends->head->head_id])
                                        </td>
                                        @endif
                                      @endforeach
                                      @endif

                                     
                                      
                                      <td class="text-right text-danger">
                                        @money($adv)
                                      </td>

                                      
                                      
                                      <td class="text-right text-{{(($salary>=0) ? 'success' : 'danger')}}">@money($salary)</td>

                                      <!-- <td></td> -->
                                    </tr>
                                </tbody>
                             </table>
                             @endif
                            </div>
                          </div>
                        </div>

                        <div id="loan" class="tab-pane fade">
                          <div class="panel panel-success">
                            <div class="panel-body">
                              <span id="hidden_table_title_6" style="display: none;">Loan Information of {{$employeeDetails->emp_name}} ({{$employeeDetails->emp_empid}})</span>
                              <div class="actions pull-right">
                                <div class="btn-group">
                                    <a class="btn red btn-outline btn-circle" href="javascript:;" data-toggle="dropdown">
                                        <i class="fa fa-gears"></i>
                                        <span> Tools </span>
                                        <i class="fa fa-angle-down"></i>
                                    </a>
                                    <ul class="dropdown-menu pull-right" id="sample_6_tools">
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
                            </div>

                            <div class="panel-body">
                              <table class="table table-striped table-bordered table-hover" id="sample_6">
                                <thead>
                                  <tr>
                                    <th>Actions</th>
                                    <th>Employee Name</th>
                                    <th>Purpose</th>
                                    <th>Amount</th>
                                    <th>Taken Month</th>
                                    <th>Status</th>
                                  </tr>
                                </thead>
                                @if(isset($loans) && count($loans)>0)
                                @php
                                $c=0;
                                @endphp
                                  @foreach ($loans as $ln)
                                  @php
                                  $c++;
                                  @endphp
                                   <tr class="gradeX" id="tr-{{$ln->id }}">
                                      <td>
                                        @if($ln->flag=="0" && $ln->emp_id==$id->suser_empid)
                                        <a class="btn btn-xs btn-primary" href="{{url('loans')}}/{{$ln->id}}/edit">Edit</a>
                                        <a class="btn btn-xs btn-danger" onclick="DeleteData('{{$ln->id}}')">Delete</a>
                                        @endif
                                      </td>
                                      <td>{{$ln->employee->emp_name}} ({{$ln->employee->emp_empid}})</td>
                                      <td><a href="{{url('loans')}}/{{$ln->id}}" target="_blank">{{$ln->purpose}}</a></td>
                                      <td>@money($ln->amount)</td>
                                      <td>{{$ln->loan_month->month}} ({{$ln->year}})</td>
                                      <td>
                                        @if($ln->flag=="0")
                                          <a class="btn btn-xs btn-warning">Pending</a>
                                        @elseif($ln->flag=="1")
                                          <a class="btn btn-xs btn-primary">Approved</a>
                                        @elseif($ln->flag=="2")
                                          <a class="btn btn-xs btn-success">Completed</a>
                                        @elseif($ln->flag=="3")
                                          <a class="btn btn-xs btn-danger">Rejected</a>
                                        @endif
                                      </td>
                                    </tr>
                                  @endforeach
                                @endif
                              </table>
                            </div>
                          </div>
                        </div>
                        
                        <div id="pf" class="tab-pane fade">
                          <div class="panel panel-success">
                            <div class="panel-body">
                              <span id="hidden_table_title_7" style="display: none;">Provident Fund of {{$employeeDetails->emp_name}} ({{$employeeDetails->emp_empid}}) ({{date('Y')}})</span>
                              <div class="actions pull-right">
                                <div class="btn-group">
                                    <a class="btn red btn-outline btn-circle" href="javascript:;" data-toggle="dropdown">
                                        <i class="fa fa-gears"></i>
                                        <span> Tools </span>
                                        <i class="fa fa-angle-down"></i>
                                    </a>
                                    <ul class="dropdown-menu pull-right" id="sample_7_tools">
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
                            </div>

                            <div class="panel-body">
                              <table class="table table-bordered" id="sample_7">
                                 <thead>
                                  <tr>
                                    <th>SL</th>
                                    <th>Month</th>
                                    <th>Employee Amount</th>
                                    <th>Company Contribution</th>
                                    <th>Total</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  @if(isset($funds[0]))
                                  @php $c=0; @endphp
                                  @foreach ($funds as $fund)
                                  @php $c++ @endphp
                                    <tr>
                                      <td>{{$c}}</td>
                                      <td>{{$fund->provident_month->month}} ({{$fund->year}})</td>
                                      <td class="text-right">@money($fund->employee_amount)</td>
                                      <td class="text-right">@money($fund->company_amount)</td>
                                      <td class="text-right">@money($fund->employee_amount+$fund->company_amount)</td>
                                    </tr>
                                  @endforeach
                                  @endif
                                </tbody>
                              </table>
                            </div>

                           <!--  <div class="panel-body text-center">
                              <ul class="pagination">
                                @if(isset($fundsYears[0]))
                                @foreach ($fundsYears as $y)
                                  <li @if($y->year==date('Y')) class="active" @endif>
                                    <a href="{{url('provident-fund')}}/employee/{{$employeeDetails->emp_id}}/{{$y->year}}" style="cursor: pointer" target="_blank">{{$y->year}}</a>
                                  </li>
                                @endforeach
                                @endif
                              </ul>
                            </div> -->

                          </div>
                        </div>

                        <div id="bonus" class="tab-pane fade">
                          <div class="panel panel-success">
                            <div class="panel-body">
                              <span id="hidden_table_title_7" style="display: none;">Provident Fund of {{$employeeDetails->emp_name}} ({{$employeeDetails->emp_empid}}) ({{date('Y')}})</span>
                              <div class="actions pull-right">
                                <!-- <div class="btn-group">
                                    <a class="btn red btn-outline btn-circle" href="javascript:;" data-toggle="dropdown">
                                        <i class="fa fa-gears"></i>
                                        <span> Tools </span>
                                        <i class="fa fa-angle-down"></i>
                                    </a>
                                    <ul class="dropdown-menu pull-right" id="sample_7_tools">
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
                                </div> -->
                              </div>
                            </div>

                            <div class="panel-body">
                              <table class="table table-bordered" id="sample_7">
                                 <thead>
                                  <tr>
                                    <th>SL</th>
                                    <th>Month</th>
                                    <th>Baishakhy Bonus</th>
                                    <th>Eid-Ul-Fitr Bonus</th>
                                    <th>Eid-Ul-Adha Bonus</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  @if(isset($bonus[0]))
                                  @php $c=0; @endphp
                                  @foreach ($bonus as $row)
                                  @php $c++ @endphp
                                    <tr>
                                      <td>{{$c}}</td>
                                      <td>{{$row->loan_month->month}}({{ date('Y') }}) </td>
                                      <td >@money($row->b_bonus)</td>
                                      <td >@money($row->fitr_bonus)</td>
                                      <td >@money($row->adha_bonus)</td>
                                    </tr>
                                  @endforeach
                                  @endif
                                </tbody>
                              </table>
                            </div>

                           <!--  <div class="panel-body text-center">
                              <ul class="pagination">
                                @if(isset($fundsYears[0]))
                                @foreach ($fundsYears as $y)
                                  <li @if($y->year==date('Y')) class="active" @endif>
                                    <a href="{{url('provident-fund')}}/employee/{{$employeeDetails->emp_id}}/{{$y->year}}" style="cursor: pointer" target="_blank">{{$y->year}}</a>
                                  </li>
                                @endforeach
                                @endif
                              </ul>
                            </div> -->

                          </div>
                        </div>

                      </div>
                     </div>
                    </div>  

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
  function New() {
    $.alert({
        title: '<i class="fa fa-upload"></i>&nbsp;Upload New Document',
        content: 'url:{{url("documents")}}/{{$employeeDetails->emp_id}}',
        animation: 'scale',
        closeAnimation: 'bottom',
        columnClass:"col-md-6 col-md-offset-3",
        buttons: {
            close: {
                text: 'Close',
                btnClass: 'btn-blue',
                action: function(){
                    // do nothing
                }
            }
        }
    });
  }

  function View(file) {
    $.alert({
        title: '',
        content: '<embed src="{{url("public/documents")}}/'+file+'"  style="width:100%;height:500px">',
        animation: 'scale',
        closeAnimation: 'bottom',
        columnClass:"col-md-10 col-md-offset-1 text-center",
        buttons: {
            close: {
                text: 'Close',
                btnClass: 'btn-blue',
                action: function(){
                    // do nothing
                }
            }
        }
    });
  }


  function Edit(id) {
    $.alert({
        title: '<i class="fa fa-edit"></i>&nbsp;Edit Uploaded Document',
        content: 'url:{{url("documents")}}/'+id+'/edit',
        animation: 'scale',
        closeAnimation: 'bottom',
        columnClass:"col-md-6 col-md-offset-3",
        buttons: {
            close: {
                text: 'Close',
                btnClass: 'btn-blue',
                action: function(){
                    // do nothing
                }
            }
        }
    });
  }

  function Delete(id) {
    $.confirm({
        title: 'Confirm!',
        content: '<hr><strong class="text-danger">Are you sure to delete ?</strong><hr>',
        buttons: {
            confirm: function () {
                $.ajax({
                  headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' },
                  url: "{{url('documents')}}/"+id,
                  type: 'DELETE',
                  dataType: 'json',
                  data: {},
                  success:function(response) {
                    if(response.success){
                      $('#tr-'+id).fadeOut();
                    }else{
                      $.alert({
                        title:"Whoops!",
                        content:"<hr><strong class='text-danger'>Something Went Wrong!</strong><hr>",
                        type:"red"
                      });
                    }
                  }
                });
            },
            cancel: function () {

            }
        }
    });   
  }

  function searchAttendance() {
    // var date_range=$('#defaultrange').val();
    // date_range=date_range.split(' - ');

    // var start_date=date_range[0].split('/');
    // start_date=start_date[2]+'-'+start_date[1]+'-'+start_date[0];
    // var end_date=date_range[1].split('/');
    // end_date=end_date[2]+'-'+end_date[1]+'-'+end_date[0];

    var start_date=$('#start_date').val();
    var end_date=$('#end_date').val();

    window.open('{{url('employee-details/'.$employeeDetails->emp_id.'/view')}}/'+start_date+'&'+end_date,'_parent');
  }

  function EditEducation(emp_id) {
    $.alert({
        title: '<i class="fa fa-edit"></i>&nbsp;Edit Education Info',
        content: 'url:{{url("employee-details")}}/'+emp_id+'/education',
        animation: 'scale',
        closeAnimation: 'bottom',
        columnClass:"col-md-6 col-md-offset-3",
        buttons: {
            Update: {
                text: '<i class="fa fa-save"></i>&nbsp;Update',
                btnClass: 'btn-blue',
                action: function(){
                  var form=$('#education_form');
                    $.ajax({
                      url: form.attr('action'),
                      type: 'POST',
                      dataType: 'json',
                      data: form.serializeArray(),
                    })
                    .done(function(response) {
                      if(response.success){
                        location.reload();
                      }else{
                        $.alert({
                          title:"Whoops!",
                          content:"<hr><div class='alert alert-danger'>"+response.msg+"</div><hr>",
                          type:"red"
                        });
                        return false;
                      }
                    })
                    .fail(function() {
                      $.alert({
                        title:"Whoops!",
                        content:"<hr><div class='alert alert-danger'>Something Went Wrong!</div><hr>",
                        type:"red"
                      });
                      return false;
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
@endsection