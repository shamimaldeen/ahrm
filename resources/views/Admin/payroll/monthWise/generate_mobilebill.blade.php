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
                    <span class="caption-subject bold uppercase"> Month Wise Mobile Bill</span>
                </div>
            </div>
            <div class="portlet-body">
              <!-- filter start -->
              <div class="row" style='padding: 30px; background-color: #f7f7f7; margin: 0px;'>
                <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{url('generate-month-wise-mobilebill-submit')}}" name="basic_validate" id="basic_validate" novalidate="novalidate">
                {{ csrf_field() }}
                             
                <div class="form-group">
                  <label class="col-md-1 control-label" style="text-align: right;padding: 5px 0px">
                    Employee Type :
                  </label>
                  <div class="col-md-2">
                    <select name="type" id="type" class="form-control chosen" onchange="getEmployee()" >
                    @if(isset($types[0]))
                    @foreach ($types as $key => $t)
                      <option @if(isset($type) && $type==$t->id) selected @endif value="{{$t->id}}">{{$t->name}}</option>
                    @endforeach
                    @endif
                    </select>
                  </div>
                  <label class="col-md-1 control-label" style="text-align: right;padding: 5px 0px" hidden="">
                    Employee :
                  </label>
                  <div class="col-md-1" hidden="">
                    <select name="emp_id" id="emp_id" class="form-control chosen">
                    
                    </select>
                  </div>
                  <label class="col-md-1 control-label" style="text-align: right;padding: 5px 0px">
                    Month :
                  </label>
                  <div class="col-md-2">
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
                  <div class="col-md-2">
                    <select name="year" class="form-control chosen">
                    @for($y=2019; $y <= 2030;$y++)
                      <option @if(isset($year) && $year==$y) selected @elseif($y==date('Y')) selected @endif>{{$y}}</option>
                    @endfor
                    </select>
                  </div>
                  <div class="col-md-3">
                    <input type="submit" class="btn btn-success btn-block" value="Generate Mobile Bill">
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

                    <div class="portlet-body table-responsive">
                      <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{url('month-wise-mobilebill-save')}}" id="data_form">
                      {{ csrf_field() }}
                      <input type="hidden" name="type" value="{{$type}}">
                       <table class="table table-striped table-bordered table-hover">
                          <thead>
                            <tr>
                              <th style="width: 1%">SL</th>
                              <th class="col-md-1">Employee</th>
                              
                              <th class="col-md-1">Position</th>
                              <th class="col-md-1">Mobile</th>

                              <!-- salary head addition -->
                              <!-- @if(isset($SalaryHeadAddition) && count($SalaryHeadAddition))
                              @foreach ($SalaryHeadAddition as $head)
                                <th class="col-md-1 text-success">
                                  {{$head->head_name}} (BDT)
                                </th>
                              @endforeach
                              @endif -->

                               <th class="col-md-1 text-success">Mobile Bill (BDT)</th>

                              <!--  <th class="col-md-1 text-danger">Income Tax (BDT)</th>  -->
                              <!-- @if($ProvidentSetup)
                              <th class="col-md-1 text-danger">P.F. (BDT)</th> 
                              @endif
                              

                              @if(isset($SalaryHeadExtendsAddition) && count($SalaryHeadExtendsAddition))
                              @foreach ($SalaryHeadExtendsAddition as $head)
                                <th class="col-md-1 text-success">
                                  {{$head->head_name}} (Qty)
                                </th>
                              @endforeach
                              @endif -->

                              

                             <!--  @if(isset($SalaryHeadDeduction) && count($SalaryHeadDeduction))
                              @foreach ($SalaryHeadDeduction as $head)
                                <th class="col-md-1 text-danger">
                                  {{$head->head_name}} (BDT)
                                </th>
                              @endforeach
                              @endif -->
                             <!--  @if(isset($SalaryHeadExtendsDeduction) && count($SalaryHeadExtendsDeduction))
                              @foreach ($SalaryHeadExtendsDeduction as $head)
                                <th class="col-md-1 text-danger">
                                  {{$head->head_name}} (Qty)
                                </th> 
                              @endforeach
                              @endif 
                             
                              <th class="col-md-1 text-danger">
                                LWP & Other Deduction (BDT)
                              </th>

                             
                             
                              <th class="col-md-1 text-success">Total Net Salary (BDT)</th> -->

                            </tr>
                          </thead>
                  
                          <tbody>
                            @if($payment)
                            @foreach ($payment as $payroll)
                            <input type="hidden" name="basic[{{$payroll['emp_id']}}]" value="{{$payroll['basic']}}">
                            <input type="hidden" name="perDayBasic[{{$payroll['emp_id']}}]" value="{{$payroll['perDayBasic']}}">
                            <input type="hidden" name="perDaySalary[{{$payroll['emp_id']}}]" value="{{$payroll['perDaySalary']}}">
                              <tr>
                                <td>
                                  {{$payroll['counter']+1}}
                                  <input type="hidden" name="employees[]" value="{{$payroll['emp_id']}}">
                                </td>
                                <td>{{$payroll['emp_name']}} ({{$payroll['emp_empid']}})</td>
                                
                               
                                <td>{{$payroll['emp_desig_id']}}</td>
                                <td>
                                  @if(isset($payroll['emp_officecontact']))
                                  {{ $payroll['emp_officecontact'] }}
                                  @endif
                                </td>
                                
                                <!-- salary head addition -->
                               <!--  @if(isset($SalaryHeadAddition) && count($SalaryHeadAddition))
                                @foreach ($SalaryHeadAddition as $head)
                                  <td>
                                    @if(isset($payroll[$head->head_id]))
                                      <input type="text" name="additionheads[{{$payroll['emp_id']}}][{{$head->head_id}}]" value="{{$payroll[$head->head_id]}}" class="form-control text-right text-success" onkeyup="calculate('{{$payroll['emp_id']}}')">
                                    @endif
                                  </td>
                                @endforeach
                                @endif -->
                                <!-- <td>
                                  <input type="text" name="pa[{{$payroll['emp_id']}}]" value="0" class="form-control text-right text-success" onkeyup="calculate('{{$payroll['emp_id']}}')">
                                </td> -->
                               <!--  @if(isset($SalaryHeadExtendsAddition) && count($SalaryHeadExtendsAddition))
                                @foreach ($SalaryHeadExtendsAddition as $head)
                                  <td>
                                    <input type="text" name="headExtendsQuantity[{{$payroll['emp_id']}}][{{$head->head_id}}]" value="{{$payroll['extends'][$head->head_id]}}" class="form-control text-right text-success" onkeyup="calculate('{{$payroll['emp_id']}}')">
                                  </td>
                                @endforeach
                                @endif -->
                                <!-- <td>
                                  <input type="text" name="due[{{$payroll['emp_id']}}]" value="0" class="form-control text-right text-success" onkeyup="calculate('{{$payroll['emp_id']}}')">
                                </td> -->
                                <!-- <td>
                                  <input type="text" name="nights[{{$payroll['emp_id']}}]" value="{{$payroll['nights']}}" class="form-control text-right text-success" onkeyup="calculate('{{$payroll['emp_id']}}')">
                                </td> -->
                                 <td class="text-right text-{{(($payroll['addition']>=0) ? 'success' : 'danger')}}">
                                  <!-- <span id="addition-{{$payroll['emp_id']}}">{{$payroll['addition']}}</span> -->
                                  <input type="number" name="Mobilebill[{{$payroll['emp_id']}}]" id="addition-field-{{$payroll['emp_id']}}" value="" class="form-control text-left " >
                                </td>
                                <input type="hidden" name="month" value="{{$month}}">

                               <!--  <td>
                                  <input type="text" name="tax[{{$payroll['emp_id']}}]" value="{{$payroll['tax']}}" class="form-control text-right text-danger" onkeyup="calculate('{{$payroll['emp_id']}}')">
                                </td> -->
                               <!--  @if($ProvidentSetup)
                                <td>
                                  <input type="text" name="pf[{{$payroll['emp_id']}}]" value="{{$payroll['provident-fund']}}" class="form-control text-right text-danger" onkeyup="calculate('{{$payroll['emp_id']}}')">
                                </td>
                                @endif -->

                                <!-- @if(isset($SalaryHeadDeduction) && count($SalaryHeadDeduction))
                                @foreach ($SalaryHeadDeduction as $head)
                                  <th>
                                    @if(isset($payroll[$head->head_id]))
                                      <input type="text" name="deductionheads[{{$payroll['emp_id']}}][{{$head->head_id}}]" value="{{$payroll[$head->head_id]}}" class="form-control text-right text-danger" onkeyup="calculate('{{$payroll['emp_id']}}')">
                                    @endif
                                  </th>
                                @endforeach
                                @endif -->
                                <!-- @if(isset($SalaryHeadExtendsDeduction) && count($SalaryHeadExtendsDeduction))
                                @foreach ($SalaryHeadExtendsDeduction as $head)
                                  <td>
                                    <input type="text" name="headExtendsQuantity[{{$payroll['emp_id']}}][{{$head->head_id}}]" value="{{$payroll['extends'][$head->head_id]}}" class="form-control text-right text-danger" onkeyup="calculate('{{$payroll['emp_id']}}')">
                                  </td>
                                @endforeach
                                @endif -->
                                
                               <!--  <td>
                                  <input type="text" name="advance[{{$payroll['emp_id']}}]" value="{{$payroll['loan']}}"class="form-control text-right text-danger" onkeyup="calculate('{{$payroll['emp_id']}}')">
                                </td>
                                
                                 <td hidden class="text-right text-danger">
                                  <span id="deduction-{{$payroll['emp_id']}}">{{$payroll['deduction']}}</span>
                                  <input type="hidden" name="deduction[{{$payroll['emp_id']}}]" id="deduction-field-{{$payroll['emp_id']}}" value="{{$payroll['deduction']}}">
                                </td> -->
                               
                                
                                <!-- <td class="text-right text-{{(($payroll['salary']>=0) ? 'success' : 'danger')}}">
                                  <span id="salary-{{$payroll['emp_id']}}">{{$payroll['salary']}}</span>
                                  <input type="hidden" name="salary[{{$payroll['emp_id']}}]" id="salary-field-{{$payroll['emp_id']}}" value="{{$payroll['salary']}}">
                                </td> -->

                              </tr>
                            @endforeach
                            @endif
                          </tbody>
                       </table>
                       <hr>
                       <div class="col-md-12 text-center">
                         <button type="submit" class="btn btn-success"><i class="fa fa-save"></i>&nbsp;Save</button>
                       </div>
                       <hr>
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
  function calculate(emp_id){
    var form=$('#data_form').serializeArray();
    $.ajax({
      headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
      url: "{{url('generate-month-wise-payroll-calculate')}}/"+emp_id,
      type: 'POST',
      dataType: 'json',
      data: form,
    })
    .done(function(response) {
      $('#addition-'+emp_id).html(response.addition);
      $('#addition-field-'+emp_id).val(response.addition);
      $('#deduction-'+emp_id).html(response.deduction);
      $('#deduction-field-'+emp_id).val(response.deduction);
      $('#salary-'+emp_id).html(response.salary);
      $('#salary-field-'+emp_id).val(response.salary);
    })
    .fail(function() {
      $.alert({
        title:"Whoops!",
        content:"<hr><div class='alert alert-danger'>Something Went Wrong!</div><hr>",
        type:"red"
      });
    });
    
  }

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
@endsection