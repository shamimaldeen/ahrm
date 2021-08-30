@extends('Admin.index')
@section('body')

<script src="{{URL::to('/')}}/public/assets/global/plugins/jquery.min.js"></script>

<div class="row">

    <div class="col-md-12">

       @include('error.msg')
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class="icon-settings font-dark"></i>
                    <span class="caption-subject bold uppercase"> Payroll</span>
                </div>
            </div>
            <div class="portlet-body">
              <!-- filter start -->
              <div class="row" style='padding: 30px; background-color: #f7f7f7; margin: 0px;'>
                <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{url('generate-day-wise-payroll-submit')}}" name="basic_validate" id="basic_validate" novalidate="novalidate">
                {{ csrf_field() }}
                             
                <div class="form-group">
                  <label class="col-md-2 control-label" style="text-align: right;padding: 5px 0px">
                    Date Range for Payroll :
                  </label>
                  <div class="col-md-6">
                    <input type="text" class="form-control" id="defaultrange" name="dateRange" readonly="yes" style="background: white;padding: 0px 0px 0px 10px;text-align: center">
                  </div>
                  <div class="col-md-4">
                    <input type="submit" class="btn btn-success btn-block" value="Generate Payroll">
                  </div>
                </div>
                
                </form>
              </div>
              <!-- filter end -->
            @if($payment)
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
                              @if(isset($SalaryHead[0]))
                              @foreach ($SalaryHead as $head)
                                <th class="text-{{(($head->head_type=='1') ? 'success' : 'danger')}}">{{$head->head_name}}</th>
                              @endforeach
                              @endif
                              <!-- <th class="text-danger">Taxable</th> -->
                              <th class="text-danger">Tax</th>
                              @if($ProvidentSetup)
                              <th class="text-danger">Provident Fund</th>
                              @endif
                              <th class="text-success">Addition</th>
                              <th class="text-danger">Deduction</th>
                              @if(isset($SalaryHeadExtends[0]))
                              @foreach ($SalaryHeadExtends as $head)
                                <th class="text-{{(($head->head_type=='1') ? 'success' : 'danger')}}">Quantity of {{$head->head_name}}</th>
                                <th class="text-{{(($head->head_type=='1') ? 'success' : 'danger')}}">Total Amount of  {{$head->head_name}}</th>
                              @endforeach
                              @endif
                              <th class="text-success">Total Net Salary</th>
                              <th>Pay Slip</th>
                            </tr>
                          </thead>
                  
                          <tbody>
                            @if($payment)
                            @foreach ($payment as $payroll)
                              <tr>
                                <td>{{$payroll['counter']}}</td>
                                <td>{{$payroll['emp_empid']}}</td>
                                <td>{{$payroll['emp_machineid']}}</td>
                                <td>{{$payroll['emp_name']}}</td>
                                @if(isset($SalaryHead[0]))
                                @foreach ($SalaryHead as $head)
                                  <th class="text-{{(($head->head_type=='1') ? 'success' : 'danger')}}">
                                    @if(isset($payroll[$head->head_id]))
                                      {{$payroll[$head->head_id]}}
                                    @endif
                                  </th>
                                @endforeach
                                @endif
                                <!-- <td class="text-danger">{{$payroll['taxable']}}</td> -->
                                <td class="text-danger">{{$payroll['tax']}}</td>
                                @if($ProvidentSetup)
                                <td class="text-danger">{{$payroll['provident-fund']}}</td>
                                @endif
                                <td class="text-{{(($payroll['addition']>=0) ? 'success' : 'danger')}}">{{$payroll['addition']}}</td>
                                <td class="text-danger">{{$payroll['deduction']}}</td>
                                @if(isset($SalaryHeadExtends[0]))
                                @foreach ($SalaryHeadExtends as $head)
                                  <td class="text-{{(($head->head_type=='1') ? 'success' : 'danger')}}">
                                    {{$payroll['extends'][$head->head_id]}}
                                  </td>
                                  <td class="text-{{(($head->head_type=='1') ? 'success' : 'danger')}}">
                                    {{$payroll['extendsAmount'][$head->head_id]}}
                                  </td>
                                @endforeach
                                @endif
                                <td class="text-{{(($payroll['salary']>=0) ? 'success' : 'danger')}}">{{$payroll['salary']}}</td>
                                <td class="text-center"><a href="generate-payroll/{{$payroll['emp_id']}}&{{$payroll['payroll_date_from']}}&{{$payroll['payroll_date_to']}}/payslip" target="_blank" class="btn btn-xs btn-success">Pay Slip</a></td>
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