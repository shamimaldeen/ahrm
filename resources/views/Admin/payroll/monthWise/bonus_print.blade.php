
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

<style type="text/css">
        @media print{
            #p {
                display: none;
            }
        }
.btn{
    font-weight: bold;
}
.caption{
    text-align: center;
     font-size: 15px;
    font-weight: bold;
    
}
table {
  width: 100%;
  border-collapse: collapse;
}
th, td {
  padding: 10px;
  text-align: left;
}
th{
  background: #e4e4e4;
}

table.print-content {
  font-size: 12px;
  border: 1px solid #dee2e6;
  border-collapse: collapse !important;
}

table.print-content th,
table.print-content td {
  padding: .2rem .4rem;
  text-align: left;
  vertical-align: top;
  border-top: 1px solid #dee2e6;
}
.sign-footer thead td{
      width:25%;
  }
@media print {
  .print-footer {
    position: fixed;
    bottom: 0;
    left: 0;
  }
  .no-print {
    display: none;
  }
  
}
</style>

<!DOCTYPE html>
<html>
<head>
  <title>Bonus Report</title>
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
   
 <!--  <div class="row">
     <div class="col-md-10 col-md-offset-1" style="text-align: center">
        <img src="{{url('public/projectDetails')}}/{{$projectDetails->project_icon}}" style="margin-top:5px;height: 80px" />
      </div>
  </div> -->

  
  <table>
  <!-- Start Header -->
  <thead>
    <tr>
      <td>
        <div class="row print-header">
            <div style="width:30%; float:left;">&nbsp;
            </div>
            <div style="text-align:left; width:33%; float:left;display: inline-flex;">
                <div>
                  <img src="{{url('/')}}/public/Main_logo.jpg" width="auto" height="40"/>
                </div>
               <div style="padding-left: 10px;">
                   <h3 style="margin: 0px; padding: 0px;">PFI Securities Limited</h3>
                  <p style="">56-57, 7th & 8th Floor, PFI Tower, Dilkusha C/A, Dhaka 1000</p>
                </div>
            </div>
            <div style="width:30%; float:left;">
              <h2 style="text-align: center;">@if($bonus_type==1)Baishakhy @elseif($bonus_type==2)Eid-Ul-Fitr @elseif($bonus_type==3)Eid-Ul-Adha @endif  Bonus Report</h2>
              <h3 style="text-align: center;">{{$mon->month}}-{{$year}}</h3>
            </div>

        </div>
      </td>
    </tr>
  </thead>
</table>
  </div>
  <div class="row col-md-10 col-md-offset-1" style="margin-bottom: 50px">
    <table class="table table-bordered">
      <thead>
        <tr>
          <th class="text-center" style="width:5%">SL</th>
          <th class="text-center" style="width:30%">Name</th>
          @if($bonus_type==1)
          <th class="text-center" style="width:30%">Baishakhy Bonus</th>
          @elseif($bonus_type==2)
          <th class="text-center" style="width:30%">Eid-Ul-Fitr Bonus</th>
          @elseif($bonus_type==3)
          <th class="text-center" style="width:30%">Eid-Ul-Adha Bonus</th>
          @endif

           
         
         
         
        </tr>
      </thead>
      <tbody>
      
      @php
        $total=0;
        $i=1;
      @endphp
      @if($mobile_report)
      @foreach ($mobile_report as $row)
     
        <tr>
          <td class="text-center">{{ $i }}</td>
          <td>{{$row->emp_name}} ({{$row->employee->emp_empid}})</td>
           @if($bonus_type==1)
          <td class="text-center">{{ $row->b_bonus }}</td>
           @elseif($bonus_type==2)
          <td class="text-center">{{ $row->fitr_bonus }}</td>
           @elseif($bonus_type==3)
          <td class="text-center">{{ $row->adha_bonus }}</td>
          @endif
         
        </tr>
        @php
          $i++;
          if($bonus_type==1){
          $total = $total+$row->b_bonus;
           }elseif($bonus_type==2){
          $total = $total+$row->fitr_bonus;
            }elseif($bonus_type==3){
          $total = $total+$row->adha_bonus;
        }

        @endphp
      @endforeach
      @endif
        <tr>
          <td></td>
          
          <td class="text-center">
            
            <strong>Total</strong>
           
          </td>
          <td class="text-center">
           
            
          
            <strong>@money($total)</strong>
            
          </td>
         

        
          
        </tr>

       <!--  <tr>
          <td class="text-right" colspan="5">&nbsp;</td>
        </tr> -->

        <tr>
          <td class="text-center" colspan="8">In Word : <strong>{{ucfirst(Controller::inWord($total))}} Taka only</strong></td>
        </tr>
      </tbody>
    </table><br><br><br>
    <table>
      <thead>
        <td>------------------------------</td>
      </thead>
      <tbody>
        <tr>
          <td>Authorized Signature</td>
        </tr>
      </tbody>
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