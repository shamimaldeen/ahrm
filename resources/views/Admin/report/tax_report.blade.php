
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
  <title>Tax Report</title>
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
  <div class="row">
     
        
      
    <div class="text-center">
      <img src="{{url('public/projectDetails')}}/{{$projectDetails->project_icon}}" style="margin-top:5px;height: 80px" />
      <h3><strong>{{$projectDetails->project_company}}</strong></h3>
      <h4><strong>Tax Report</strong></h4>
      <h4><strong>For All Employee</strong></h4>
      <h4><strong></strong></h4>
    </div>
  </div>
  <div class="row col-md-10 col-md-offset-1" style="margin-bottom: 50px">
    <table class="table table-bordered">
      <thead>
        <tr>
          <th class="text-center" style="width:5%">SL</th>
          <th class="text-center" style="width:30%">Name</th>
           <th class="text-center" style="width:30%">
            Tax
          </th>
         
          
         
        </tr>
      </thead>
      <tbody>
      @php($i=1)
      @php($sum=0)
     @foreach($tax as $row)
        <tr>
          <td class="text-center">{{ $i }}</td>
          <td>{{ $row->employee->emp_name }} ({{  $row->employee->emp_empid }})</td>
         <td class="text-center">
           {{ $row->tax }}
          </td>
         
          
        </tr>
        
        @php($sum = $sum + $row->tax )
        @php($i++)
     @endforeach
     <tr>
     	<td></td>
     	<td class="text-right"><strong>Total</strong></td>
     	<td class="text-center"><strong> {{ $sum }}</strong></td>
     </tr>
      <tr>
          <td class="text-center" colspan="5">In Word : <strong>{{ucfirst(Controller::inWord($sum))}} Taka only</strong></td>
        </tr>
        

        <tr>
          <td class="text-right" colspan="5">&nbsp;</td>
        </tr>

       
    </table>
  </div>
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