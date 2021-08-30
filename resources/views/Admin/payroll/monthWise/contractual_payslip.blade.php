@php use App\Http\Controllers\BackEndCon\Controller; @endphp
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Pay Slip :: {{$projectDetails->project_name}} :: {{$projectDetails->project_company}}</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="{{url('/')}}/public/assets/global/plugins/bootstrap/css/bootstrap.min.css">
  <link href="{{URL::to('/')}}/public/assets/global/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css" />
  <link href="{{URL::to('/')}}/public/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet" type="text/css" />
  <script src="{{url('/')}}/public/assets/global/plugins/jquery.min.js"></script>
  <script src="{{url('/')}}/public/assets/global/plugins/bootstrap/js/bootstrap.min.js"></script>
  <link rel="shortcut icon" href="{{url('public/projectDetails')}}/{{$projectDetails->project_icon}}" />
  <style type="text/css">
    @media print{
      .printbutton {
        display: none
      }
    }
  </style>
</head>
<body>

<div class="container">
	<div class="row">
		  <div class="col-md-10 col-md-offset-1" style="text-align: center">
		    <img src="{{url('public/projectDetails')}}/{{$projectDetails->project_icon}}" style="margin-top:5px;height: 80px" />
		  </div>
		  
		  <div class="col-md-10 col-md-offset-1" style="text-align: center">
		      <h4>{{$projectDetails->project_company}}</h4>
		      <h6>
				{{$projectDetails->project_address}}
				<br>
				{{$projectDetails->project_contact}}
			</h6>
		  </div>
	</div>
</div>

<div class="container">
  <table class="table table-striped table-bordered">
    <caption><h4><center class="bg-success text-warning" style="padding: 5px">Pay Slip</center></h4><hr style="margin-bottom: 0px;padding-bottom: 0px;margin-top: 0px"></caption>

    <tr>
      <th>Name </th>
      <th>{{$employee->emp_name}}</th>
      <th>Employee Code </th>
      <th>{{$employee->emp_empid}}</th>
    </tr>
    <tr>
      <th>Department </th>
      <th>{{$employee->department->depart_name}}</th>
      <th>Designation </th>
      <th>{{$employee->designation->desig_name}}</th>
    </tr>
    <tr>
      <th>Duration </td>
      <th>{{ date('d-m-Y', strtotime($payroll_date_from)) }} to {{ date('d-m-Y', strtotime($payroll_date_to)) }}</th>
    </tr>
    <tr>
      <td colspan = "2" valign="top" style="padding:0px;width: 50%">
        <table class="table table-striped" style="border:0px">
          <thead>
           <!--  <tr>
              <th>Earning Salary </th>
              <td align="right">Amount </td>
            </tr> -->
          </thead>
          <tbody>
         

         
          
             
             <!--  <td class="strong-text">Due </td>
              <td class="text-right strong-text">{{$PayrollSummery->due}} BDT</td> -->
            
            
            <tr>
              <td>Basic</td>
              <td align="right">{{ $PayrollSummery->basic }}</td>
            </tr>
             <tr>
              <td>Gross</td>
              <td align="right">{{ $PayrollSummery->gross }}</td>
            </tr>
            <tr>
              <td>Conveyance</td>
              <td align="right">{{ $PayrollSummery->conv }}</td>
            </tr>
            <tr>
              <td>Net Paid</td>
              <td align="right">{{ $PayrollSummery->net_paid }}</td>
            </tr>
            <tr>
              <td>% Of Target</td>
              <td align="right">{{ $PayrollSummery->target_percent }}%</td>
            </tr>
            <tr>
              <td>Targeted Income</td>
              <td align="right">{{ $PayrollSummery->targeted_income }}</td>
            </tr>
            <tr>
              <td>Net Income</td>
              <td align="right">{{ $PayrollSummery->net_income }}</td>
            </tr>
             <tr>
              <td>% Of Achivement</td>
              <td align="right">{{ $PayrollSummery->percent_achivement }}</td>
            </tr>
             <tr>
              <td>Paid Amount</td>
              <td align="right">{{ $PayrollSummery->balance }}</td>
            </tr>
          </tbody>
        </table>
      </td>
      <td colspan = "2" valign="top" style="padding:0px;width: 50%">
        <table class="table table-striped">
          <thead>
            <!-- <tr>
              <th>Deduction Salary </th>
              <td align="right">Amount </td>
            </tr> -->
          </thead>
          <tbody>
             <tr>
                  <td>Income Tax</td>
                  <td align="right">{{$PayrollSummery->tax}} BDT</td>
                </tr>
             @if($ProvidentSetup)
                <tr>
                  <td>Provident Fund</td>
                  <td align="right">{{$PayrollSummery->provident_fund}} BDT</td>
                </tr>
              @endif
            @if(isset($PayrollSummery->heads[0]))
            @foreach($PayrollSummery->heads as $head)
              @if($head->head->head_type=="0")
                <tr>
                  <td>{{$head->head->head_name}}</td>
                  <td align="right">{{$head->head_amount}} BDT</td>
                </tr>
              @endif
            @endforeach
            @endif
               
             
            @if(isset($PayrollSummery->extends[0]))
            @php $extendsDeduction=0; @endphp
            @foreach($PayrollSummery->extends as $extends)
              @if($extends->head->head_type=="0")
              @php $extendsDeduction+=Controller::decimal($extends->head_amount); @endphp
                <tr>
                  <td class="strong-text">{{$extends->head->head_name}}</td>
                  <td class="text-right strong-text">{{Controller::decimal($extends->head_amount)}} BDT</td>
                </tr>
              @endif
            @endforeach
            @endif
           
            <tr>
              <td class="strong-text">Excess Mobile Bill </td>
              <td class="text-right strong-text">{{$PayrollSummery->mobilebill}} BDT</td>
            </tr>
            <tr>
              <td class="strong-text">Net Pay </td>
              <td class="text-right strong-text">{{$PayrollSummery->net_pay}} BDT</td>
            </tr>
            <tr>
              <td class="strong-text">Previous Month Calculation</td>
              <td class="text-right strong-text">{{$PayrollSummery->prev_cal}} BDT</td>
            </tr>
            <tr>
              <td class="strong-text">Previous Month Calculation</td>
              <td class="text-right strong-text">{{$PayrollSummery->prev_cal}} BDT</td>
            </tr>
             <tr>
              <td class="strong-text">Loan/Advance </td>
              <td class="text-right strong-text">{{$PayrollSummery->advance}} BDT</td>
            </tr>
            <tr>
              <td class="strong-text">Net Pay With Previous Calculation </td>
              <td class="text-right strong-text">{{$PayrollSummery->net_pay_with_prev}} BDT</td>
            </tr>
            <tr>
              <td class="strong-text">Remarks</td>
              <td class="text-right strong-text">{{$PayrollSummery->remarks}}</td>
            </tr>
          </tbody>
        </table>
      </td>
    </tr>
    <!-- <tr>
      <td colspan="2" style="width: 50%">
        <table class="table">
            <tr>
              <th>Net Earning </th>
              <th class="text-right">{{$PayrollSummery->addition}} BDT</th>
            </tr>
        </table>
      </td>
      <td colspan="2" style="width: 50%">
        <table class="table">
            <tr>
              
              <th>Net Deduction </th>
              <th class="text-right">{{$PayrollSummery->deduction}} BDT</th>
            </tr>
        </table>
      </td>
    </tr> -->
   <!--  <tr>
      <th colspan="2">Net Salary </th>
      <th colspan="2" class="text-right">{{$PayrollSummery->salary}} BDT</th>
    </tr> -->
  </table>
  <div class="row">
  <p class="pull-right" style="margin-top:20px;margin-right:10px;"><strong>*</strong> This Payslip is computer generated. So, any Seal/Signature is not required.</p>
  </div>

  <div class="row printbutton">
  <p><center><a onclick="window.print()" class="btn btn-md btn-success">Print</a></center></p>
  </div>
</div>

<script src="{{URL::to('/')}}/public/assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
<script src="{{URL::to('/')}}/public/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>
 <script src="{{URL::to('/')}}/public/assets/pages/scripts/table-datatables-buttons.js" type="text/javascript"></script>
</body>
</html>