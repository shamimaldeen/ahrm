@extends('Admin.index')
@section('body')

@php
use App\Payroll;
use App\Http\Controllers\BackEndCon\Controller;
use App\ProjectDetails;
$projectDetails=ProjectDetails::find('1');
@endphp
<style type="text/css">
  .dt-buttons{
    display: none;

  }
</style>
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
                <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{url('view-contractual-payroll')}}" name="basic_validate" id="basic_validate" novalidate="novalidate">
                {{ csrf_field() }}

                <div class="form-group">
                  <label class="col-md-1 control-label" style="text-align: right;padding: 5px 0px">
                    Employee Type :
                  </label>
                  <div class="col-md-2">
                    <select name="type" id="type" class="form-control chosen" onchange="getEmployee()">
                    @if(isset($types[0]))
                   
                      <option value="9">Contractual</option>
                  
                    @endif
                    </select>
                  </div>
                  <label class="col-md-1 control-label" style="text-align: right;padding: 5px 0px">
                    Employee :
                  </label>
                  <div class="col-md-2">
                    <select name="emp_id" id="emp_id" class="form-control chosen">
                    
                    </select>
                  </div>
                  <label class="col-md-1 control-label" style="text-align: right;padding: 5px 0px">
                    Month :
                  </label>
                  <div class="col-md-1">
                    <select name="month" class="form-control chosen">
                    @if(isset($months[0]))
                    @foreach ($months as $key => $m)
                      <option @if($month==$m->id) selected @endif value="{{$m->id}}">{{$m->month}}</option>
                    @endforeach
                    @endif
                    </select>
                  </div>
                  <label class="col-md-1 control-label" style="text-align: right;padding: 5px 0px">
                    Year :
                  </label>
                  <div class="col-md-1">
                    <select name="year" class="form-control chosen">
                    @for($y=2019; $y <= 2030;$y++)
                      <option @if(isset($year) && $year==$y) selected @elseif($y==date('Y')) selected @endif>{{$y}}</option>
                    @endfor
                    </select>
                  </div>
                  <div class="col-md-2">
                    <input type="submit" class="btn btn-success btn-block" value="View Contractual Payroll">
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
                      <div class="col-md-6">
                        <div class="caption font-dark">
                            <i class="icon-settings font-dark"></i>
                            <span class="caption-subject bold uppercase" id="table_title">{!!$page_title!!}</span>
                        </div>
                      </div>
                         <!-- <a href="{{ route('payrollsummery-print') }}" type="button" class="btn btn-success">Print</a> -->
                         <div class="col-md-6">
                         <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{url('payrollsummery-print')}}" name="basic_validate" id="basic_validate" novalidate="novalidate" target="_blank">
                         {{ csrf_field() }}

                        <div hidden="">
                        <select name="type" id="type" class="form-control chosen" onchange="getEmployee()">
                        @if(isset($types[0]))
                        @foreach ($types as $key => $t)
                          <option @if(isset($type) && $type==$t->id) selected @endif value="{{$t->id}}">{{$t->name}}</option>
                        @endforeach
                        @endif
                        </select>
                      </div>

                     
                  <div hidden="">
                    <input type="hidden" name="emp_id" value="{{$emp}}">
                  </div>

                 
                  <div  hidden="">
                    <select name="month" class="form-control chosen">
                    @if(isset($months[0]))
                    @foreach ($months as $key => $m)
                      <option @if($month==$m->id) selected @endif value="{{$m->id}}">{{$m->month}}</option>
                    @endforeach
                    @endif
                    </select>
                  </div>

                
                  <div hidden="">
                    <select name="year" class="form-control chosen">
                    @for($y=2019; $y <= 2030;$y++)
                      <option @if(isset($year) && $year==$y) selected @elseif($y==date('Y')) selected @endif>{{$y}}</option>
                    @endfor
                    </select>
                  </div>
                  <div class="col-md-4"></div>
                    <div class="col-md-4">
                    <input type="submit" class="btn btn-success btn-block" value="Print">
                  </div>
                           
                         </form>
                         <div class="col-md-4">
                         <a href="{{ route('tax-certificate', $id->suser_empid) }}" class="btn btn-success btn-block" target="_blank">Tax Certificate</a>
                       </div>
                      </div>
                      
                     <!--  <div class="col-md-6">
                          <div class="actions pull-right">
                            <div class="btn-group">
                                <a class="btn red btn-outline btn-circle" href="javascript:;" data-toggle="dropdown">
                                    <i class="fa fa-gears"></i>
                                    <span> Tools </span>
                                    <i class="fa fa-angle-down"></i>
                                </a>
                                <ul class="dropdown-menu pull-right" id="table_payroll_tools">
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
                    </div> -->

                    <div class="portlet-body">
                      <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{URL::to('')}}" id="data_form">
                      {{ csrf_field() }}
                      
                      @if(isset($PayrollSummery[0]))
                      <div  id="table_header"  style="display: none">
                        <table class="table table-bordered">
                          <tbody>
                            <tr>
                              <td>
                                <div class="text-center">
                                  <h3><strong>{{$projectDetails->project_company}}</strong></h3>
                                  <h4><strong>Salary Sheet</strong></h4>
                                  <h4><strong>For the month of {{$header['month']}}, {{$header['year']}}</strong></h4>
                                  <h4><strong>{{$header['type']}}</strong></h4>
                                </div>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                      @endif

                       <table class="table table-striped table-bordered table-hover" id="example">
                          <thead>
                            <tr>
                              
                              <th style="width: 1%">SL</th>
                              <!-- <th>Employee ID</th> -->
                              <th>Employee</th>
                              <th>Position</th>

                              @if(isset($PayrollSummery[0]->extends[0]))
                              @foreach ($PayrollSummery[0]->extends as $extends)
                                @if($extends->head->head_type=='1')
                                <td class="text-success">
                                  {{$extends->head->head_name}}
                                </td>
                                @endif
                              @endforeach
                              @endif
                              <!-- <td class="text-success">
                                Night Allow.
                              </td> -->
                             <!--  <td class="text-success">
                                Payment Allow.
                              </td>
 -->
                             <!--  <th class="text-primary">
                                Total
                              </th> -->

                              @if(isset($PayrollSummery[0]->heads[0]))
                              @foreach ($PayrollSummery[0]->heads as $head)
                                @if($head->head->head_type=='1')
                                  <th class="text-success">
                                    {{$head->head->head_name}}
                                  </th>
                                @endif
                              @endforeach
                              @endif

                              @if(isset($PayrollSummery[0]->extends[0]))
                              @foreach ($PayrollSummery[0]->extends as $extends)
                                @if($extends->head->head_type=='1')
                                <td class="text-success">
                                  {{$extends->head->head_name}} Amount
                                </td>
                                @endif
                              @endforeach
                              @endif
<!-- 
                              <td class="text-success">
                                Night Allow.
                              </td> -->
                             <!--  <td class="text-success">
                                Due
                              </td> -->
                               <th class="text-success">Basic Salary (BDT)</th>
                               <th class="text-success">Gross Salary (BDT)</th>
                               <th class="text-success">Conveyance (BDT)</th>
                               <th class="text-success">Net Paid (BDT)</th>
                               <th class="text-success">% Of Target</th>
                               <th class="text-success">Targeted Income</th>
                               <th class="text-success">Net Income</th>
                               <th class="text-success">% Of Achivement</th>
                               <th class="text-success">Paid Amount</th>

                             

                                <th class="text-danger">Income Tax</th>

                               @if($ProvidentSetup)
                              <th class="text-danger">P.F.</th>
                              @endif

                             


                                <th class="text-danger">Excess Mobile Bill</th>
                              

                              <th class="text-success">Net Pay</th>
                              <th class="text-success">Previous Month Calculation</th>

                             
                             


                              <th class="text-danger">Advance</th>

                               <th class="text-success">Net Pay With Previous Calculation</th>

                             

                             
                             
                              <th class="text-success">Remarks</th>

                              <th style="width: 1%"></th>

                              <!-- <th>Bank Account</th> -->
                            </tr>
                          </thead>
                  
                          <tbody>
                            @foreach($PayrollSummery as $row)
                            <tr>
                              <td>1</td>
                              <td>{{ $row->employee->emp_name }}({{ $row->employee->emp_empid }})</td>
                              <td>
                                @php
                                    $desig=App\Designation::where('desig_id', $row->employee->emp_desig_id)->first();
                                @endphp
                                {{ $desig->desig_name }}</td>
                              <td class="text-success">{{ $row->basic }}</td>
                              <td class="text-success">{{ $row->gross }}</td>
                              <td class="text-success">{{ $row->conv }}</td>
                              <td class="text-success">{{ $row->net_paid }}</td>
                              <td class="text-success">{{ $row->target_percent }}</td>
                              <td class="text-success">{{ $row->targeted_income }}</td>
                              <td class="text-success">{{ $row->net_income }}</td>
                              <td class="text-success">{{ round($row->percent_achivement,2) }}</td>
                              <td class="text-success">{{ round($row->balance,2) }}</td>
                              <td class="text-danger">{{ $row->tax }}</td>
                              <td class="text-danger">{{ $row->provident_fund }}</td>
                              <td class="text-danger">{{ $row->mobilebill }}</td>
                              <td class="text-success">{{ round($row->net_pay,2) }}</td>
                              <td class="text-success">{{ $row->prev_cal }}</td>
                              <td class="text-danger">{{ $row->advance }}</td>
                              <td class="text-success">{{ round($row->net_pay_with_prev, 2) }}</td>
                              <td class="text-success">{{ $row->remarks }}</td>
                              <td class="text-center"> <a href="generate-contractual-payroll/{{$row->employee->emp_id}}&{{$row->payroll_date_from}}&{{$row->payroll_date_to}}/payslip" target="_blank" class="btn btn-xs btn-success">Pay Slip</a></td>
                            </tr>
                            @endforeach
                          </tbody>
                       </table>

                       @if(isset($PayrollSummery[0]))
                       <div id="table_footer" style="display: none">
                          <br>
                          <br>
                          <br>
                          <br>
                          <br>
                          <table class="table" style="border:none">
                            <tbody style="border:none">
                              <tr style="border:none">
                                <td style="border:none">
                                  <div class="row" style="margin-bottom: 10px">
                                    <div class="text-center" style="width: 20%;float:left;clear:right;">
                                      <hr style="margin:5px 20px 5px 0px;border: 1px solid #ccc">
                                      Perpared By
                                    </div>
                                    <div class="text-center" style="width: 20%;float:left;clear:right;">
                                      <hr style="margin:5px 20px 5px 0px;border: 1px solid #ccc">
                                      Audited By
                                    </div>
                                    <div class="text-center" style="width: 20%;float:left;clear:right;">
                                      <hr style="margin:5px 20px 5px 0px;border: 1px solid #ccc">
                                      D.G.M
                                    </div>
                                    <div class="text-center" style="width: 20%;float:left;clear:right;">
                                      <hr style="margin:5px 20px 5px 0px;border: 1px solid #ccc">
                                      HR & Admin
                                    </div>
                                    <div class="text-center" style="width: 20%;float:left;clear:right;">
                                      <hr style="margin:5px 20px 5px 0px;border: 1px solid #ccc">
                                      Director
                                    </div>
                                  </div>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                          <br>
                          <p>** Note : Overtime, Night & payment allowance is being paid for the month of {{$header['prevmonth']}}, {{$header['prevyear']}}</p>
                        </div>
                        @endif
                     </form>
                    </div>

                </div>
            </div>
          </div>
          @endif
        </div>
      </div>
    </div>
<script type="text/javascript">
  getEmployee();
  function getEmployee() {
    $('#emp_id').html('').trigger('chosen:updated');
    var type=$('#type').val();
    $.ajax({
      url: "{{url('generate-month-wise-payroll')}}/"+type+"/get-employee",
      type: 'GET',
      dataType: 'json',
      data: {},
    })
    .done(function(response) {
      var data='<option value="0">Choose Employee</oprion>';
      if(response.success){
        $.each(response.employees, function(index, val) {
           data+='<option value="'+val.emp_id+'">'+val.emp_name+'('+val.emp_empid+')</oprion>';
        });
      }
      $('#emp_id').html(data).trigger('chosen:updated');
    })
    .fail(function() {
      $('#emp_id').html('<option value="0">Choose Employee</oprion>').trigger('chosen:updated');
    });
  }
</script>
<script type="text/javascript">
 $(document).ready(function() {
    $('#example').DataTable( {
        dom: 'Bfrtip',
        "scrollX": true,        
        buttons: [
           // 'copy', 'csv', 'excel', 'pdf', 'print'
       
        {
            extend: 'pdf',
            orientation: 'landscape'
            
        },
        {
            extend: 'excel',
           // orientation: 'landscape'
            
        }
        ]

        
    } );
} );

</script>
@endsection