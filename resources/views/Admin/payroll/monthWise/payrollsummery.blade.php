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
                <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{url('view-generated-month-wise-payrolls')}}" name="basic_validate" id="basic_validate" novalidate="novalidate">
                {{ csrf_field() }}

                <div class="form-group">
                  <label class="col-md-1 control-label" style="text-align: right;padding: 5px 0px">
                    Employee Type :
                  </label>
                  <div class="col-md-2">
                    <select name="type" id="type" class="form-control chosen" onchange="getEmployee()">
                    @if(isset($types[0]))
                    @foreach ($types as $key => $t)
                      <option @if(isset($type) && $type==$t->id) selected @endif value="{{$t->id}}">{{$t->name}}</option>
                    @endforeach
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
                               @php
                                  $b=App\Setup::where('b_bonus','>',0)->first();
                              @endphp
                              @if(isset($b))
                              <th class="text-success">Baishakhy Bonus</th>
                              @endif

                               @php
                                  $c=App\Setup::where('fitr_bonus','>',0)->first();
                              @endphp
                              @if(isset($c))
                              <th class="text-success">Eid-Ul-Fitr</th>
                              @endif
                               @php
                                  $d=App\Setup::where('adha_bonus','>',0)->first();
                              @endphp
                              @if(isset($d))
                              <th class="text-success">Eid-Ul-Adha</th>
                              @endif

                               <th class="text-success">Total Taka</th>

                                <th class="text-danger">Income Tax</th>

                               @if($ProvidentSetup)
                              <th class="text-danger">P.F.</th>
                              @endif

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

                              

                             
                             


                              <th class="text-danger">LWP & Other Deduction</th>

                               <th class="text-danger">Excess Mobile Bill</th>

                             
                             
                              <th class="text-success">Total Net Salary</th>

                              <th style="width: 1%"></th>

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
                            $b_bonus=0;
                            $fitr_bonus=0;
                            $adha_bonus=0;
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
                                     
                                        @money($head->head_amount)
                                     
                                    </td>
                                  @endif
                                @endforeach
                                @endif

                                 @php
                                  $b=App\Setup::where('b_bonus','>',0)->first();
                              @endphp
                              @if(isset($b))

                                <td class="text-right text-success">
                                   @money($payroll->b_bonus)
                                  @php
                                    $b_bonus+=$payroll->b_bonus;
                                  @endphp
                                </td>
                                @endif
                                @php
                                  $c=App\Setup::where('fitr_bonus','>',0)->first();
                              @endphp
                              @if(isset($c))
                                <td class="text-right text-success">
                                  @money($payroll->fitr_bonus)
                                  @php
                                    $fitr_bonus+=$payroll->fitr_bonus;
                                  @endphp
                                </td>
                                @endif
                                @php
                                  $c=App\Setup::where('adha_bonus','>',0)->first();
                              @endphp
                              @if(isset($c))
                                <td class="text-right text-success">
                                   @money($payroll->adha_bonus)
                                  @php
                                    $adha_bonus+=$payroll->adha_bonus;
                                  @endphp
                                  
                                </td>
                                @endif

                                <td class="text-right text-{{(($payroll->addition>=0) ? 'success' : 'danger')}}">
                                  @money($payroll->addition)
                                  @php
                                    $addition+=$payroll->addition;
                                  @endphp
                                </td>

                                 <td class="text-right text-danger">
                                  @money($payroll->tax)
                                  @php
                                    $tax+=$payroll->tax;
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

                                @if(isset($payroll->extends[0]))
                                @foreach ($payroll->extends as $extends)
                                  @if($extends->head->head_type=='1')
                                  <td class="text-right text-success">
                                    @money($extends->head_amount)
                                  </td>
                                  @endif
                                @endforeach
                                @endif

                                <!-- <td class="text-right text-success">
                                  @money($payroll->night_allowance)
                                  @php
                                    $na+=$payroll->night_allowance;
                                  @endphp
                                </td> -->
                               <!--  <td class="text-right text-success">
                                  @money($payroll->due)
                                  @php
                                    $due+=$payroll->due;
                                  @endphp
                                </td> -->

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

                                

                               
                               
                                <td class="text-right text-danger">
                                  @money($payroll->advance)
                                  @php
                                    $adv+=$payroll->advance;
                                  @endphp
                                </td>

                                 <td class="text-right text-danger">
                                  @money($payroll->mobilebill)
                                  @php
                                    $mblbill+=$payroll->mobilebill;
                                  @endphp
                                </td>

                                
                                
                                <td class="text-right text-{{(($payroll->salary>=0) ? 'success' : 'danger')}}">
                                  @money($payroll->salary)
                                  @php
                                    $salary+=$payroll->salary;
                                  @endphp
                                </td>

                                 <td class="text-center">
                                  <a href="generate-month-wise-payroll/{{$payroll->employee->emp_id}}&{{$payroll->payroll_date_from}}&{{$payroll->payroll_date_to}}/payslip" target="_blank" class="btn btn-xs btn-success">Pay Slip</a>
                                  <br><br>
                                  <a href="generate-month-wise-certificate/{{$payroll->employee->emp_id}}&{{$payroll->payroll_date_from}}&{{$payroll->payroll_date_to}}/payslip" target="_blank" class="btn btn-xs btn-success">Certificate</a>
                                </td>

                               <!--  <td>{{(($payroll->employee->salary) ? $payroll->employee->salary->bank_account : '' )}}</td> -->
                              </tr>
                            @endforeach
                            @endif

                              <tr>
                               
                                <td>{{$key+2}}</td>
                                <td></td>
                                <!-- <td></td> -->
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
                                <!-- <td class="text-right text-success">
                                  {{$nights}}
                                </td> -->
                               <!--  <td class="text-right text-success">
                                  {{$pa}}
                                </td> -->

                                <!-- <td class="text-right text-primary">
                                  @money($gross)
                                </td> -->

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

                                @php
                                  $b=App\Setup::where('b_bonus','>',0)->first();
                              @endphp
                              @if(isset($b))
                                <td class="text-right text-success">
                                  @money($b_bonus)
                                </td>
                                @endif
                                @php
                                  $c=App\Setup::where('fitr_bonus','>',0)->first();
                              @endphp
                              @if(isset($c))
                                <td class="text-right text-success">
                                  @money($fitr_bonus)
                                </td>
                              @endif
                              @php
                                  $d=App\Setup::where('adha_bonus','>',0)->first();
                              @endphp
                              @if(isset($d))
                                <td class="text-right text-success">
                                  @money($adha_bonus)
                                </td>
                                @endif

                               <td class="text-right text-{{(($addition>=0) ? 'success' : 'danger')}}">@money($addition)</td>

                              <td class="text-right text-danger">@money($tax)</td>

                                @if($ProvidentSetup)
                                <td class="text-right text-danger">@money($pf)</td>
                                @endif

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

                                 <td class="text-right text-danger">
                                  @money($mblbill)
                                </td>

                                
                               
                                <td class="text-right text-{{(($salary>=0) ? 'success' : 'danger')}}">@money($salary)</td>

                                <!-- <td></td> -->
                                 <td class="text-center">
                                  <a disabled class="btn btn-xs btn-success">Pay Slip</a>
                                  <a disabled class="btn btn-xs btn-primary"><i class="fa fa-edit"></i></a>
                                </td>
                              </tr>
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