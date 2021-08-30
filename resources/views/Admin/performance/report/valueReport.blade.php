@php use App\Http\Controllers\BackEndCon\Controller as C; @endphp
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Project Value Report :: {{$projectDetails->project_name}} :: {{$projectDetails->project_company}}</title>
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
      <h4 class="text-center"><strong>Project Value Report</strong></h4>
      <h5 class="text-center"><strong>{{$Employee->emp_name}} ({{$Employee->emp_empid}})</strong></h5>
      <h6 class="text-center"><strong>From <span style="text-decoration: underline">{{date("l, jS \of F Y",strtotime($start_date))}}</span> to <span style="text-decoration: underline">{{date("l, jS \of F Y",strtotime($end_date))}}</span></strong></h6>

      <table class="table table-bordered table-striped table-hover">
        <thead>
          <tr>
            <th colspan="7"><h4 class="text-center">Job Details</h4></th>
          </tr>
          <tr>
            <th>Project</th>
            <th>Project Value</th>
            <th>Job</th>
            <th>Job Weight</th>
            <th>Job Value</th>
            <th>Job Completion</th>
            <th>Job Completion Value</th>
          </tr>
        </thead>
        <tbody>
        @if(isset($Job[0]))
        @php
          $c=0;
          $total_job_value=0;
          $job_completion=0;
          $total_job_completion_value=0;
        @endphp
        @foreach ($Job as $data)
        @php
          $c++;
          $job_value=C::decimal($data->project->project_amount*($data->job_weight/100));
          $total_job_value+=$job_value;
          $job_completion+=$data->completion;
          $job_completion_value=C::decimal(($data->project->project_amount*($data->job_weight/100))*($data->completion/100));
          $total_job_completion_value+=$job_completion_value;
        @endphp
          <tr>
            <td>{{$data->project->project_name}}</td>
            <td>{{$data->project->project_amount}} BDT</td>
            <td><a onclick="window.open('{{url('jobs')}}/{{$data->id}}/job-report','_blank')" style="cursor: pointer">{{$data->job_title}}</a></td>
            <td class="text-right">{{$data->job_weight}} %</td>
            <td class="text-right">{{$job_value}} BDT</td>
            <td class="text-right">{{$data->completion}} %</td>
            <td class="text-right">{{$job_completion_value}} BDT</td>
          </tr>
        @endforeach
          <tr>
            <td colspan="4" class="text-right"><strong>Total :</strong></td>
            <td class="text-right"><strong>{{$total_job_value}} BDT</strong></td>
            <td class="text-right"><strong>{{C::decimal($job_completion/$c)}} %</strong></td>
            <td class="text-right"><strong>{{$total_job_completion_value}} BDT</strong></td>
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