@extends('Admin.index')
@section('body')

@php
use App\Payroll;
use App\Http\Controllers\BackEndCon\Controller;
@endphp
<script src="{{URL::to('/')}}/public/assets/global/plugins/jquery.min.js"></script>

<div class="row">

    <div class="col-md-12">

       @include('error.msg')
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class="icon-settings font-dark"></i>
                    <span class="caption-subject bold uppercase"> Payroll Summery</span>
                </div>
            </div>
            <div class="portlet-body">
              <!-- filter start -->
              <div class="row" style='padding: 30px; background-color: #f7f7f7; margin: 0px;'>
                <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{url('view-generated-day-wise-payrolls')}}" name="basic_validate" id="basic_validate" novalidate="novalidate">
                {{ csrf_field() }}
                             
                <div class="form-group">
                  <label class="col-md-3 control-label" style="text-align: right;padding: 5px 0px">
                    Date Range for Already Generated Payroll :
                  </label>
                  <div class="col-md-6">
                    <input type="text" class="form-control" id="defaultrange" name="dateRange" readonly="yes" style="background: white;padding: 0px 0px 0px 10px;text-align: center">
                  </div>
                  <div class="col-md-3">
                    <input type="submit" class="btn btn-success btn-block" value="View Payroll">
                  </div>
                </div>
                
                </form>
              </div>
              <!-- filter end -->
            @if($PayrollSummery)
              <div class="row">
                <div class="col-md-12">
                  <div class="portlet light bordered">
                    
                    <div class="portlet-title">
                      <div class="col-md-10">
                        <div class="caption font-dark">
                            <i class="icon-settings font-dark"></i>
                            <span class="caption-subject bold uppercase" id="hidden_table_title"> {!!$page_title!!}</span>
                        </div>
                      </div>
                      <div class="col-md-2">
                          <div class="actions pull-right">
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
                        <div class="tools"> </div>
                    </div>

                    <div class="portlet-body">
                      <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{URL::to('')}}" id="data_form">
                      {{ csrf_field() }}
                       <table class="table table-striped table-bordered table-hover" id="sample_3">
                          <thead>
                            <tr>
                              <th>SL</th>
                              <th>Employee ID</th>
                              <th>Face ID</th>
                              <th>Name</th>
                              <th>Salary Date Of Execution</th>
                              <th>Payroll Date From</th>
                              <th>Payroll Date to</th>
                              @if(isset($SalaryHead[0]))
                              @foreach ($SalaryHead as $head)
                                <th class="text-{{(($head->head_type=='1') ? 'success' : 'danger')}}">{{$head->head_name}}</th>
                              @endforeach
                              @endif
                              <th class="text-danger">Tax</th>
                              @if($ProvidentSetup)
                              <th class="text-danger">Provident Fund</th>
                              @endif
                              <th class="text-success">Addition</th>
                              <th class="text-danger">Deduction</th>
                              @if(isset($PayrollSummery[0]->extends[0]))
                              @foreach ($PayrollSummery[0]->extends as $extends)
                              <td class="text-{{(($extends->head->head_type=='1')? 'success' : 'danger' )}}">
                                Quantity of {{$extends->head->head_name}}
                              </td>
                              <td class="text-{{(($extends->head->head_type=='1')? 'success' : 'danger' )}}">
                                Total Amount of {{$extends->head->head_name}}
                              </td>
                              @endforeach
                              @endif
                              <th class="text-success">Total Net Salary</th>
                              <th>Pay Slip</th>
                            </tr>
                          </thead>
                  
                          <tbody>
                            @if($PayrollSummery)
                            @php $counter=0; @endphp
                            @foreach ($PayrollSummery as $payroll)
                            @php $counter++; @endphp
                              <tr>
                                <td>{{$counter}}</td>
                                <td>{{$payroll->employee->emp_empid}}</td>
                                <td>{{$payroll->employee->emp_machineid}}</td>
                                <td>{{$payroll->employee->emp_name}}</td>
                                <td>{{$payroll->date_of_execution}}</td>
                                <td>{{$payroll->payroll_date_from}}</td>
                                <td>{{$payroll->payroll_date_to}}</td>
                                @if(isset($SalaryHead[0]))
                                @foreach ($SalaryHead as $head)
                                @php
                                  $amount=0;
                                  $data=Payroll::where(['emp_id'=>$payroll->employee->emp_id,'head_date_of_execution'=>$payroll->date_of_execution,'head_id'=>$head->head_id])->first();
                                  if(isset($data->emp_id)){
                                    $amount=$data->amount;
                                  }
                                @endphp
                                  <td class="text-{{(($head->head_type=='1') ? 'success' : 'danger')}}">
                                    {{Controller::decimal((($amount*12.17)/365)*$salaryDays)}}
                                  </td>
                                @endforeach
                                @endif
                                <td class="text-danger">{{$payroll->tax}}</td>
                                @if($ProvidentSetup)
                                <td class="text-danger">{{$payroll->provident_fund}}</td>
                                @endif
                                <td class="text-{{(($payroll->addition>=0) ? 'success' : 'danger')}}">{{$payroll->addition}}</td>
                                <td class="text-danger">{{$payroll->deduction}}</td>
                                @if(isset($payroll->extends[0]))
                                @foreach ($payroll->extends as $extends)
                                <td class="text-{{(($extends->head->head_type=='1')? 'success' : 'danger' )}}">
                                  {{$extends->head_quantity}}
                                </td>
                                <td class="text-{{(($extends->head->head_type=='1')? 'success' : 'danger' )}}">
                                  {{$extends->head_amount}}
                                </td>
                                @endforeach
                                @endif
                                <td class="text-{{(($payroll->salary>=0) ? 'success' : 'danger')}}">{{$payroll->salary}}</td>
                                <td class="text-center"><a href="generate-payroll/{{$payroll->employee->emp_id}}&{{$payroll->payroll_date_from}}&{{$payroll->payroll_date_to}}/payslip" target="_blank" class="btn btn-xs btn-success">Pay Slip</a></td>
                              </tr>
                            @endforeach
                            @endif
                          </tbody>
                       </table>
                     </form>
                    </div>

                </div>
            </div>
          </div>
          @endif
        </div>
      </div>
    </div>
@endsection