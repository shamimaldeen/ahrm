
@php
use App\Http\Controllers\BackEndCon\Controller;
use App\ProjectDetails;
$projectDetails=ProjectDetails::find('1');
@endphp

@if(Controller::checkEnable()=="1")
@else
<script type="text/javascript">location="{{url('/login')}}"</script>
@endif

@if(Controller::checkLinkPriority('month-wise-bank-and-cash-payments-report')=="1")
@else
<script type="text/javascript">location="{{url('/')}}"</script>
@endif

<!DOCTYPE html>
<html>
<head>
  <title>{{(($report=="1")? "Bank" : "Cash")}} Report</title>
  <link href="{{url('/')}}/public/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
  <link rel="shortcut icon" href="{{url('public/projectDetails')}}/{{$projectDetails->project_icon}}" />
  <style type="text/css" media="screen">
    h4{
      margin: 5px
    }
  </style>
</head>
<body>
<div class="container-fluid">
   @if($report == 0)
  <div class="row">
     <div class="col-md-10 col-md-offset-1" style="text-align: center">
        <img src="{{url('public/projectDetails')}}/{{$projectDetails->project_icon}}" style="margin-top:5px;height: 80px" />
      </div>
  </div>
  @endif
   @if($report == 1)
  <div class="row" style="padding: 95px 15px 15px 15px;">
    @else
    <div class="row">
      @endif
    <div class="text-center">
       @if($report == 0)
      <h3><strong>{{$projectDetails->project_company}}</strong></h3>
      @endif
      <h4><strong>Salary Sheet</strong></h4>
      <h4><strong>For the month of {{$payrollmonth->month}}, {{$year}}</strong></h4>
      <h4><strong>{{$typename}} {{(($report=="1")? "Bank" : "Cash")}} payment</strong></h4>
    </div>
  </div>
  <div class="row col-md-10 col-md-offset-1" style="margin-bottom: 50px">
    <table class="table table-bordered">
      <thead>
        <tr>
          <th class="text-center" style="width:5%">SL</th>
          <th class="text-center" style="width:30%">Name</th>
           <th class="text-center" style="width:30%">
            @if($report=="1")
            Bank Account No.
            @elseif($report=="0")
            Net Payable Salary
            @endif
          </th>
         
          <th class="text-center" style="width:15%">
            @if($report=="1")
          Net Payable Salary
          @elseif($report=="0")
            Sign
            @endif
        </th>
         
        </tr>
      </thead>
      <tbody>
      
      @php
        $total=0;
      @endphp
      @if($PayrollSummery)
      @foreach ($PayrollSummery as $key => $payroll)
      @php
        $total+=$payroll->salary;
      @endphp
        <tr>
          <td class="text-center">{{$key+1}}</td>
          <td>{{$payroll->employee->emp_name}} ({{$payroll->employee->emp_empid}})</td>
         <td class="text-center">
            @if($report=="1" && isset($payroll->employee->salary))
            {{$payroll->employee->salary->bank_account}}
            @elseif($report == 0)
             @money($payroll->salary)
            @endif
          </td>
          <td class="text-right">
            @if($report == 1)
            @money($payroll->salary)
            @endif
          </td>
          
        </tr>
      @endforeach
      @endif
        <tr>
          <td class="text-right"></td>
          <td class="text-right">
             @if($report == "0")
            <strong>Total</strong>
            @endif
          </td>
          <td class="text-right">
            @if($report == "1")
            <strong>Total</strong>
            @elseif($report == "0")
            <strong>@money($total)</strong>
            @endif
          </td>
          <td class="text-right">
             @if($report == "1")
            <strong>@money($total)</strong>
            @endif
          </td>
          
        </tr>

        <tr>
          <td class="text-right" colspan="5">&nbsp;</td>
        </tr>

        <tr>
          <td class="text-center" colspan="5">In Word : <strong>{{ucfirst(Controller::inWord($total))}} Taka only</strong></td>
        </tr>
      </tbody>
    </table>
  </div>
  @if($report == "1")
  <div class="row col-md-10 col-md-offset-1" style="margin-bottom: 50px">
    <table width="100%">
      <tr>
        <td>--------------------------------</td>
        <td class="text-right">--------------------------------</td>
      </tr>
      <tr>
        <td><strong>Authorized Signature</strong></td>
        <td class="text-right"><strong>Authorized Signature</strong></td>
      </tr>
      
    </table>
  </div>
  @endif
  @if($report == "0")
  <div class="row col-md-10 col-md-offset-1" style="margin-bottom: 50px">
    <table width="100%">
      <tr>
        <td>--------------------------------</td>
        <td>--------------------------------</td>
        <td>--------------------------------</td>
        <td>--------------------------------</td>
      </tr>
      <tr>
        <td><strong>Prepared By</strong></td>
        <td><strong>Checked/Varified By</strong></td>
        <td><strong>Authorized Signature</strong></td>
        <td><strong>Authorized Signature</strong></td>
      </tr>
      
    </table>
  </div>
@endif
  <div class="row col-md-10 col-md-offset-1" style="margin-bottom: 10px">
   <!--  <div class="text-center" style="width: 20%;float:left;clear:right;">
      <hr style="margin:5px 20px 5px 0px;border: 1px solid #ccc">
      Perpared By
    </div> -->
    <!-- <div class="text-center" style="width: 20%;float:left;clear:right;">
      <hr style="margin:5px 20px 5px 0px;border: 1px solid #ccc">
      Audited By
    </div> -->
    <!-- <div class="text-center" style="width: 20%;float:left;clear:right;">
      <hr style="margin:5px 20px 5px 0px;border: 1px solid #ccc">
      D.G.M
    </div> -->
    <!-- <div class="text-center" style="width: 20%;float:left;clear:right;">
      <hr style="margin:5px 20px 5px 0px;border: 1px solid #ccc">
      HR & Admin
    </div> -->
    <!-- <div class="text-center" style="width: 20%;float:left;clear:right;">
      <hr style="margin:5px 20px 5px 0px;border: 1px solid #ccc">
      Director
    </div> -->
    <center class="hidden-print" style="margin-top: 20px;"><a class="btn btn-primary btn-md" onclick="window.print()"><i class="fa fa-print"></i>&nbsp;Print</a></center>
  </div>
</div>
</body>
</html>