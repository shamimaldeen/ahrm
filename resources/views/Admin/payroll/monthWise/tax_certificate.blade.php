
@php
use App\Http\Controllers\BackEndCon\Controller;
use App\ProjectDetails;
$projectDetails=ProjectDetails::find('1');
@endphp

@if(Controller::checkEnable()=="1")
@else
<script type="text/javascript">location="{{url('/login')}}"</script>
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
   <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <title>tax certificate</title>
    <style>
      body{
      font-size: 17px;
    }
      @media print{
            #p {
                display: none;
            }
        }

   .tables{
     border-collapse: collapse;
     width: 60%;
     margin-left: 10%;
   }
   .tables th{
    background-color:#c3c3c3;
    text-align: center;
   }
   .tables tr th:last-child{
      width: 30%;
   }
   .tables td, .tables th {
      border: 1px solid #ddd;
      padding: 6px;
    }
    .tables td:nth-of-type(2){
      text-align: right;
    }
    .tables tr:last-child{
      text-align: right;
      font-weight:bold;
    }
    .tables tr td:nth-of-type(1){
      text-align: left;
    }
    .table-second{
         border-collapse: collapse;
         width: 60%;
         margin: 0 auto;

    }
       .table-second th{
    background-color:#c3c3c3;
    text-align: center;
   }
     .table-second td, .table-second th {
      border: 1px solid #ddd;
      padding: 6px;
      text-align: center;
    }
    .table-second tbody tr td:last-child{
      text-align: right;
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
        <div class="row">
               <header class="header-info d-flex justify-content-center flex-wrap">
                <div class="logo-image" style="width: 80px;">
                  <img src="{{url('/')}}/public/logo.png" alt="logo" style="width:100%;">
                </div>
                <div style="width: 100%;text-align: center;">
                <span style="font-size: 21px; display: block;">  PFI Securities Limited </span>
                <small>DP-17400, PFI Tower(8th floor)56-57, Dilkusha
                 Dhaka
                  </small>
                </div>
         </header>
          <table class="table-second mt-3" hidden="">
      <thead>
        <tr>
          <th class="text-center" style="width:5%">SL</th>
          <th class="text-center" style="width:30%">Month-Year</th>
          <th class="text-center" style="width:30%">Paid Tax</th>
         

           
         
         
         
        </tr>
      </thead>
      <tbody>
        @php($i = 1)
        @php($sum = 0)
      @foreach($a as $row)
      <tr>
        <td class="text-center">{{ $i }}</td>
        <td class="text-center">{{ date('M', strtotime($row->payroll_date_from)) }}-{{ date('Y', strtotime($row->payroll_date_from)) }}</td>
        <td class="text-center">{{ $row->tax }}</td>
      </tr>
      @php($sum=$sum+$row->tax)
       @php($i++)
      
      
      @endforeach
      <tr>
        <td></td>
        <td class="text-center"><strong>Total</strong> </td>
        <td class="text-center"><strong>{{ $sum }}</strong></td>
      </tr>
      

       <!--  <tr>
          <td class="text-right" colspan="5">&nbsp;</td>
        </tr> -->

        
      </tbody>
    </table>
        <div class="date-header mt-4">
          <span>PFISL/TC/2020 </span>
           <p>{{date('d-m-Y')}} </p>
        </div>
        <div class="heading text-center mb-5">
         <span style="font-weight: 800;font-size: 17px;"> TO WHOM IT MAY CONCERN </span>
        </div>
        <div class="paragraph-message mb-3">
          <p> This is to certify that <b>Mr. {{$emp_tax->emp_name}},</b> {{$emp_tax->designation->designame}} has been working in FPI Securites Limited from
           <span> {{$emp_tax->emp_joindate}} </span>.During the period from {{$last_year}} to {{$present_year}} an amount of <b>Taka {{$sum}}</b> ({{ucfirst(Controller::inWord($sum))}}) only was paid to him as salary and allowances as detailed below:</p>
        </div>
      </td>
    </tr>
  </thead>
</table>
  </div>
  <div class="row col-md-10 col-md-offset-1" style="margin-bottom: 50px">
    <table class="table-second mt-3" >
      <thead>
        <tr>
          <th class="text-center" style="width:5%">SL</th>
          <th class="text-center" style="width:30%">Month-Year</th>
          <th class="text-center" style="width:30%">Paid Tax</th>
         

           
         
         
         
        </tr>
      </thead>
      <tbody>
        @php($i = 1)
        @php($sum = 0)
      @foreach($a as $row)
      <tr>
        <td class="text-center">{{ $i }}</td>
        <td class="text-center">{{ date('M', strtotime($row->payroll_date_from)) }}-{{ date('Y', strtotime($row->payroll_date_from)) }}</td>
        <td class="text-center">{{ $row->tax }}</td>
      </tr>
      @php($sum=$sum+$row->tax)
       @php($i++)
      
      
      @endforeach
      <tr>
        <td></td>
        <td class="text-center"><strong>Total</strong> </td>
        <td class="text-center"><strong>{{ $sum }}</strong></td>
      </tr>
      

       <!--  <tr>
          <td class="text-right" colspan="5">&nbsp;</td>
        </tr> -->

        
      </tbody>
    </table><br><br><br><br><br><br>

     <div class="paragraph-message mb-3">
          <p> Further confirmed that a sum of <b>Taka {{$sum}}</b> ({{ucfirst(Controller::inWord($sum))}}) only has been deducted from his salary as Income Tax Under Section 50 of Income Tax Ordinance 1984 and deposited to Bangladesh bank vide follwing Challans:</p>
        </div>

     <table class="table-second mt-3" >
      <thead>
        <tr>
          <th class="text-center" style="width:5%">SL</th>
          <th class="text-center" style="width:30%">Month-Year of Challan</th>
          <th class="text-center" style="width:30%">Challan No</th>
         

           
         
         
         
        </tr>
      </thead>
      <tbody>
        @php($i = 1)
        @php($sum = 0)
      @foreach($tax_challan as $row)
      <tr>
        <td class="text-center">{{ $i }}</td>
        <td class="text-center">{{ $row->challan_month->month }}-{{ $row->year }}</td>
        <td class="text-center">{{ $row->challan_no }}</td>
      </tr>
     
       @php($i++)
      
      
      @endforeach
      
      

       <!--  <tr>
          <td class="text-right" colspan="5">&nbsp;</td>
        </tr> -->

        
      </tbody>
    </table><br><br><br>
     </div><br><br>
   <div class="author mt-5 ms-3">
              <strong>-------------------------</strong>
              <p>Authorized Signature </p>
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
    <center class="hidden-print" style="margin-top: 20px;"><a id="p" class="btn btn-primary btn-md" onclick="window.print()"><i class="fa fa-print"></i>&nbsp;Print</a></center>
  </div>
</div>
</body>
</html>