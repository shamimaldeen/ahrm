@php use App\Http\Controllers\BackEndCon\Controller; @endphp
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Salary Certificate :: {{$projectDetails->project_name}} :: {{$projectDetails->project_company}}</title>
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
    <caption><h4><center class="bg-success text-warning" style="padding: 5px">Salary Certificate</center></h4><hr style="margin-bottom: 0px;padding-bottom: 0px;margin-top: 0px"></caption>

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
      <th>Month </td>
      <th>{{ date('M-Y', strtotime($payroll_date_from)) }}</th>
    </tr>
    <tr>
      <td colspan = "4" valign="top" style="padding:0px;width: 100%">
        <table class="table table-striped" style="border:0px">
          <thead>
            <tr>
              <th>Earning Salary </th>
              <td align="right">Amount </td>
            </tr>
          </thead>
          <tbody>
          @if(isset($PayrollSummery->heads[0]))
          @foreach($PayrollSummery->heads as $head)
            @if($head->head->head_type=="1")
              <tr>
                <td>{{$head->head->head_name}}</td>
               
                <td align="right">{{$head->head_amount}} BDT</td>
                
                
              </tr>
            @endif
          @endforeach

          @endif
          @if(isset($PayrollSummery->extends[0]))
          @php $extendsAddition=0; @endphp
          @foreach($PayrollSummery->extends as $extends)
            @if($extends->head->head_type=="1")
            @php $extendsAddition+=Controller::decimal($extends->head_amount); @endphp
              <tr>
                <td class="strong-text">{{$extends->head->head_name}}</td>
                <td class="text-right strong-text">{{Controller::decimal($extends->head_amount)}} BDT</td>
              </tr>
            @endif
          @endforeach
          @endif
            <tr>
             <!--  <td class="strong-text">Due </td>
              <td class="text-right strong-text">{{$PayrollSummery->due}} BDT</td> -->
            </tr>
          </tbody>
        </table>
      </td>
      <!--<td colspan = "2" valign="top" style="padding:0px;width: 50%">-->
       
      <!--</td>-->
    </tr>
    @php
         $b=App\Setup::where('b_bonus','>',0)->first();
         $c=App\Setup::where('fitr_bonus','>',0)->first();
         $d=App\Setup::where('adha_bonus','>',0)->first();
    @endphp
    @if(isset($b))
      $x=$PayrollSummery->b_bonus;
    @else
      $x=0;
    @endif
    @if(isset($c))
     
    @else
      $y=0;
    @endif
    @if(isset($d))
     
    @else
      $z=0;
    @endif
    <tr>
      <td colspan="4" style="width: 100%">
        <table class="table">
            <tr>
              <th>Net Earning </th>
              <th class="text-right">{{$PayrollSummery->addition-$PayrollSummery->b_bonus-$PayrollSummery->fitr_bonus-$PayrollSummery->adha_bonus }} BDT</th>
            </tr>
        </table>
      </td>
      <!--<td colspan="2" style="width: 50%">-->
        
      <!--</td>-->
    </tr>
    <tr>
     
    </tr>
  </table>
  <div class="row">
  <p class="pull-right" style="margin-top:20px;margin-right:10px;"><strong>*</strong> This Salary Certificate is computer generated. So, any Seal/Signature is not required.</p>
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