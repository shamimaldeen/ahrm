@php use App\Http\Controllers\BackEndCon\Controller as C; @endphp
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Job Report :: {{$projectDetails->project_name}} :: {{$projectDetails->project_company}}</title>
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
      <h4 class="text-center"><strong>{{$Job->project->project_name}}</strong></h4>
      <h6 class="text-center"><strong>{{$Job->job_title}}</strong></h6>
      <h6 class="text-center"><a class="btn btn-xs btn-success">{{$Job->status}}</a></h6>
      <div class="progress">
        <div class="progress-bar" role="progressbar" aria-valuenow="{{$Job->completion}}"
        aria-valuemin="0" aria-valuemax="100" style="width:{{$Job->completion}}%">
          <span>{{$Job->completion}}% Complete</span>
        </div>
      </div>
      <table class="table table-bordered table-striped table-hover">
        <thead>
          <tr>
            <th colspan="2">Job Holder Information</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Employee ID</td>
            <td>{{$Job->employee->emp_empid}}</td>
          </tr>
          <tr>
            <td>Employee Name</td>
            <td>{{$Job->employee->emp_name}}</td>
          </tr>
          <tr>
            <td>Face ID</td>
            <td>{{$Job->employee->emp_machineid}}</td>
          </tr>
        </tbody>
      </table>

      <table class="table table-bordered table-striped table-hover">
        <thead>
          <tr>
            <th colspan="2">Job Details</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Job weight</td>
            <td>{{$Job->job_weight}} %</td>
          </tr>
          <tr>
            <td>Job amount(Based on project amount)</td>
            <td>{{C::decimal($Job->project->project_amount*($Job->job_weight/100))}} BDT</td>
          </tr>
          @if($incentive["incentive"])
          <tr>
            <td>Job incentive(Based on project incentive amount)</td>
            <td>{{C::decimal($Job->project->incentive_amount*($Job->job_weight/100))}} BDT</td>
          </tr>
          @endif
          <tr>
            <td>Start date</td>
            <td>{{$Job->start_date}}</td>
          </tr>
          <tr>
            <td>End date</td>
            <td>{{$Job->end_date}}</td>
          </tr>
        </tbody>
      </table>

      <table class="table table-bordered table-striped table-hover">
        <thead>
          <tr>
            <th colspan="8">
              Appraisal
            </th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>SL</td>
            <td>Skill</td>
            <td>Skill Weight (%)</td>
            <td>Target (Unit)</td>
            <td>Achieve (Unit)</td>
            <td>Performance (%)</td>
            @if($incentive["incentive"])
            <td>Incentive Target Amount</td>
            <td>Incentive Achieve Amount</td>
            @endif
          </tr>
          @if(isset($Job->appraisal[0]))
            @php 
            $c=0; 
            $total_performance=0; 
            $total_weight=0; 
            $target_incentive=0; 
            $achieve_incentive=0; 
            @endphp
          @foreach ($Job->appraisal as $app)
            @php 
            $c++;
            if($app->achieve=="" || $app->achieve=="0"){
              $performance=0;
            } else {
              $performance=C::decimal(($app->achieve*100)/$app->target);
            }
            $total_performance+=$performance;
            $total_weight+=$app->skill_weight; 
            $target_incentive+=$app->incentive_target_amount; 
            $achieve_incentive+=$app->incentive_achieve_amount;
            @endphp
            <tr>
              <td>{{$c}}</td>
              <td>{{$app->skill->name}}</td>
              <td class="text-right">{{$app->skill_weight}} %</td>
              <td class="text-right">{{$app->target}}</td>
              <td class="text-right">{{$app->achieve}}</td>
              <td class="text-right">{{$performance}} %</td>
              @if($incentive["incentive"])
              <td class="text-right">{{$app->incentive_target_amount}} BDT</td>
              <td class="text-right">{{$app->incentive_achieve_amount}} BDT</td>
              @endif
            </tr>
          @endforeach
          @endif
            <tr>
              <td colspan="2" class="text-right">Total</td>
              <td class="text-right">{{$total_weight}} %</td>
              <td colspan="2"></td>
              <td class="text-right">{{C::decimal($total_performance/$c)}} %</td>
              @if($incentive["incentive"])
              <td class="text-right">{{$target_incentive}} BDT</td>
              <td class="text-right">{{$achieve_incentive}} BDT</td>
              @endif
            </tr>
        </tbody>
      </table>

      <table class="table table-bordered table-striped table-hover">
        <thead>
          <tr>
            <th colspan="2">Reviewers</th>
          </tr>
        </thead>
        <tbody>
          @if(isset($Job->reviewer[0]))
          @php $c=0; @endphp
          @foreach ($Job->reviewer as $reviewer)
          @php $c++; @endphp
            <tr>
              <td>{{$c}}</td>
              <td>{{$reviewer->employee->emp_name}} ({{$reviewer->employee->emp_empid}})</td>
            </tr>
          @endforeach
          @endif
        </tbody>
      </table>
    </div>
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