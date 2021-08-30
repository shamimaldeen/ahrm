@extends('Admin.index')
@section('body')

@php
use App\Payroll;
use App\Http\Controllers\BackEndCon\Controller;
use App\ProjectDetails;
$projectDetails=ProjectDetails::find('1');
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
                    @if($report=='1')
                    <span class="caption-subject bold uppercase"> Bank Payment Report</span>
                    @elseif($report=='2')
                    <span class="caption-subject bold uppercase"> Cash Payment Report</span>
                    @endif
                </div>
            </div>
            <div class="portlet-body">
              <!-- filter start -->
              <div class="row" style='padding: 30px; background-color: #f7f7f7; margin: 0px;'>
                <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{url('view-generated-bank-report-payrolls')}}" name="basic_validate" id="basic_validate" novalidate="novalidate">
                {{ csrf_field() }}

                <div class="form-group">
                  <label class="col-md-1 control-label" style="text-align: right;font-size:12px;padding: 5px 0px">
                    Employee Type :
                  </label>
                  <div class="col-md-3">
                    <select name="type" id="type" class="form-control chosen" onchange="getEmployee()">
                    @if(isset($types[0]))
                    @foreach ($types as $key => $t)
                      <option @if(isset($type) && $type==$t->id) selected @endif value="{{$t->id}}">{{$t->name}}</option>
                    @endforeach
                    @endif
                    </select>
                  </div>
                  <label class="col-md-1 control-label" style="text-align: right;font-size:12px;padding: 5px 0px">
                    Employee :
                  </label>
                  <div class="col-md-3">
                    <select name="emp_id" id="emp_id" class="form-control chosen">
                    
                    </select>
                  </div>
                  <label class="col-md-1 control-label" style="text-align: right;font-size:12px;padding: 5px 0px">
                    Month :
                  </label>
                  <div class="col-md-3">
                    <select name="month" class="form-control chosen">
                    @if(isset($months[0]))
                    @foreach ($months as $key => $m)
                      <option @if($month==$m->id) selected @endif value="{{$m->id}}">{{$m->month}}</option>
                    @endforeach
                    @endif
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-md-1 control-label" style="text-align: right;font-size:12px;padding: 5px 0px">
                    Year :
                  </label>
                  <div class="col-md-3">
                    <select name="year" class="form-control chosen">
                    @for($y=2019; $y <= 2030;$y++)
                      <option @if(isset($year) && $year==$y) selected @elseif($y==date('Y')) selected @endif>{{$y}}</option>
                    @endfor
                    </select>
                  </div>
                  <label class="col-md-1 control-label" style="text-align: right;font-size:12px;padding: 5px 0px">
                    Report :
                  </label>
                  <div class="col-md-3">
                    <select name="report" class="form-control chosen">
                    <option value="1">
                      Bank Report
                    </option>
                    <option value="2">
                      Cash Report
                    </option>
                    </select>
                  </div>
                  <div class="col-md-2">
                    <input type="submit" class="btn btn-success btn-block" value="View Report">
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
                        <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{url('bank-cash-print')}}" name="basic_validate" id="basic_validate" novalidate="novalidate" target="_blank">
                        {{ csrf_field() }}
                        <input type="hidden" name="type" value="{{$type_emp}}">
                        <input type="hidden" name="emp_id" value="{{$empl}}">
                        <input type="hidden" name="month" value="{{$mon_emp}}">
                        <input type="hidden" name="year" value="{{$year_emp}}">
                        <input type="hidden" name="report" value="{{$report_emp}}">

                          <div class="col-md-2">
                         <input type="submit" class="btn btn-success btn-block" value="Print">
                         </div>
                  </div>
                        </form>
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

                             
<!-- 
                              <td class="text-success">
                                Night Allow.
                              </td> -->
                             <!--  <td class="text-success">
                                Due
                              </td> -->
                              <!--  <th class="text-success">Total Taka</th> -->

                               

                             
                             
                            

                              <!-- <th>Bank Account</th> -->
                            </tr>
                          </thead>
                  
                          <tbody>
                            @if($PayrollSummery)
                            
                            @php
                            $nights=0;
                            $pa=0;
                            $gross=0;
                            $na=0;
                            $due=0;
                            $tax=0;
                            $pf=0;
                            $adv=0;
                            $mblbill=0;
                            $addition=0;
                            $deduction=0;
                            $salary=0;
                            @endphp

                            @foreach ($PayrollSummery as $key => $payroll)
                              <tr>
                                
                                <td>{{$key+1}}</td>
                                <!-- <td>{{$payroll->employee->emp_empid}}</td> -->
                                <td>{{$payroll->employee->emp_name}} ({{$payroll->employee->emp_empid}})</td>
                                <td>
                                  {{$payroll->employee->designation->desig_name}}
                                </td>

                                
                                
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

                                @php
                                $head_bank=App\Payroll::where('emp_id', $payroll->employee->emp_id)
                                ->where('head_id', $head->head_id)
                                ->first();
                                @endphp

                                  @if($head->head->head_type=='1')

                                    <td class="text-right text-success">
                                      @if($report=='1')
                                      @if($head_bank->bankcash_status=='1')
                                     
                                      
                                        @money($head->head_amount)
                                     
                                      @else
                                      0 (Cash)
                                      @endif
                                      @elseif($report=='2')
                                      @if($head_bank->bankcash_status=='2')
                                     
                                      @if($head->head->head_id!="5")
                                        @money($head->head_amount)
                                      @else
                                        @money($head->head_amount*$payroll->pa)
                                      @endif
                                      @else
                                      0 (Bank)
                                      @endif
                                      @endif
                                     
                                    </td>
                                  @endif
                                @endforeach
                                @endif

                                <!-- <td class="text-right text-{{(($payroll->addition>=0) ? 'success' : 'danger')}}">
                                  @money($payroll->addition)
                                  @php
                                    $addition+=$payroll->addition;
                                  @endphp
                                </td> -->

                                

                               

                                

                               
                               
                               

                                
                                
                                

                               <!--  <td>{{(($payroll->employee->salary) ? $payroll->employee->salary->bank_account : '' )}}</td> -->
                              </tr>
                            @endforeach
                            @endif

                              
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
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    } );
} );

</script>
@endsection