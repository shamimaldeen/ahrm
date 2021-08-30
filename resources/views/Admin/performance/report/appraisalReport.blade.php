@php 
use App\Http\Controllers\BackEndCon\Controller as C;
use App\Http\Controllers\BackEndCon\JobReportController as JRC;
use App\Job;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Appraisal Performance Report :: {{$projectDetails->project_name}} :: {{$projectDetails->project_company}}</title>
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
		      <h4>{{$projectDetails->project_name}} :: {{$projectDetails->project_company}}</h4>
		      <h6>
				{{$projectDetails->project_address}}
				<br>
				{{$projectDetails->project_contact}}
			</h6>
		  </div>
	</div>
</div>

<div class="container">

  <div class="panel panel-default">
    <div class="panel-body">
      <h4 class="text-center"><strong>Appraisal Performance Report</strong></h4>
      <h5 class="text-center"><strong>{{$Employee->emp_name}} ({{$Employee->emp_empid}})</strong></h5>
      <h6 class="text-center"><strong>From <span style="text-decoration: underline">{{date("l, jS \of F Y",strtotime($start_date))}}</span> to <span style="text-decoration: underline">{{date("l, jS \of F Y",strtotime($end_date))}}</span></strong></h6>

      <table class="table table-bordered table-striped table-hover">
        <thead>
          <tr>
            <th colspan="7"><h4 class="text-center">Skill Details</h4></th>
          </tr>
          <tr>
            <th>SL</th>
            <th>Skill</th>
            <th>Target (Unit)</th>
            <th>Achieve (Unit)</th>
            <th>Performance (%)</th>
            @if($incentive["incentive"])
            <th>Incentive Target Amount</th>
            <th>Incentive Achieve Amount</th>
            @endif
          </tr>
        </thead>
        <tbody>
        @if(isset($Skill[0]))
        @php
          $c=0;
          $total_performance=0; 
          $target_incentive=0; 
          $achieve_incentive=0; 
        @endphp
        @foreach ($Skill as $sk)
        @php
          $sp=JRC::skillPerformance($Employee->emp_id,$sk->id,$start_date,$end_date);
        @endphp
          @if($sp['result'] && $sp['target']>0)
          @php 
            $c++;
            $total_performance+=$sp['performance']; 
            $target_incentive+=$sp['target_incentive']; 
            $achieve_incentive+=$sp['achieve_incentive'];
          @endphp
            <tr>
              <td>{{$c}}</td>
              <td>{{$sk->name}}</td>
              <td>{{$sp['target']}}</td>
              <td>{{$sp['achieve']}}</td>
              <td class="text-right">{{$sp['performance']}} %</td>
              @if($incentive["incentive"])
              <td class="text-right">{{$sp['target_incentive']}} BDT</td>
              <td class="text-right">{{$sp['achieve_incentive']}} BDT</td>
              @endif
            </tr>
          @endif
        @endforeach
          <tr>
              <td colspan="4" class="text-right">Total</td>
              <td class="text-right">{{C::decimal($total_performance/$c)}} %</td>
              @if($incentive["incentive"])
              <td class="text-right">{{$target_incentive}} BDT</td>
              <td class="text-right">{{$achieve_incentive}} BDT</td>
              @endif
            </tr>
        @endif
        </tbody>
      </table>
    </div>
  </div>

  <div class="row printbutton">
    <p>
      <center>
        <a onclick="window.print()" class="btn btn-md btn-success">Print</a>
        <a href="{{url('job-report')}}" class="btn btn-md btn-danger">Back To Report</a>
      </center>
    </p>
  </div>
</div>

<script src="{{URL::to('/')}}/public/assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
<script src="{{URL::to('/')}}/public/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>
 <script src="{{URL::to('/')}}/public/assets/pages/scripts/table-datatables-buttons.js" type="text/javascript"></script>
</body>
</html>