<!DOCTYPE html>
@php
use App\Http\Controllers\BackEndCon\Controller;
use App\ProjectDetails;
$projectDetails=ProjectDetails::find('1');
@endphp
<html lang="en">
<head>
  <title>Loan Certificate</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <style>
  	@media print{
            #p {
                display: none;
            }
        }

   .late-application{
      margin:4rem auto;
   }

    .header-info .logo-image{
    width:95px;
    margin: 0 auto;
   }
   .header-info .logo-image img{
    width: 100%;
    height: 100%;
   }
   span{
    display: block;
    font-size: 2rem;
   }
   .date{
    margin:1rem 0rem 3rem;
   }
   .tables{
     border-collapse: collapse;
     width: 50%;
   }
   .tables th{
    background-color:#c3c3c3;
    text-align: center;
   }
   .tables td, .tables th {
      border: 1px solid #ddd;
      padding: 7px;
    }
    .tables th:nth-of-type(3){
      width: 35%;

    }
    .tables td:last-child{
      text-align: right;
    }
   .tables td:nth-of-type(2){
      text-align: center;
      font-weight: bold;
    }




  </style>
  <input type="button" id="p" value="print" onclick="printPage();" class="btn btn-label-success btn-sm btn-upper">
</head>
<body>


  
<div class="container">
  <div class="row">
    <div class="col-md-12">
         <header class="header-info text-center">
                <div class="logo-image">
                  <img src="http://localhost/pfi_jun9/public/logo.png" alt="logo"/>
                </div>
                <span>  PFI Securities Limited </span>
                <small>DP-17400, PFI Tower(8th floor)56-57, Dilkusha
                 Dhaka
                  </small>
         </header>
     <section class="loan-certificate mt-4">
        <p class="date ">
        Date:   {{ date('d-m-Y') }}
        </p>
          <div class="application-sub text-center">
           <strong style="font-weight: 800;font-size: 17px;"> TO WHOM IT MAY CONCERN </strong>
         </div>
         <br>
         <br> 
         <br>
         <p>This is to notify that <strong>{{$loan_emp->emp_name}},</strong> {{$loan_emp->designation->desig_name}} has been working in PFI Securities Limited from {{$loan_emp->emp_joindate}}. He is a {{$loan_emp->type->name}} employee of the company.In the month of {{$loan->loan_month->month}} {{$loan->year}},he took an amount of <strong> Taka {{$loan->amount}} </strong>({{ucfirst(Controller::inWord($loan->amount))}}) only as advance salary detailed below:</p>
         <br>
         <br> <br>
         <table class="tables">
                <tr>
                    <th>
                      Particulars
                    </th>
                    <th>:
                    </th>
                    <th>
                      Amount (Tk.)
                    </th>
                </tr>
                 <tr>
                      <td>Advance salary</td>
                      <td>:</td>
                      <td>{{$loan->amount}}</td>
                </tr>   
                 <tr>
                    <td>Realized amount</td>
                    <td>:</td>
                    <td>
                    @if($loan->flag==1)
                    {{$loan_approve_paid}}
                    @endif
                	</td>
                 </tr>    
                 <tr>
                    <td>Due amount</td>
                     <td>:</td>
                    <td>
                     @if($loan->flag==1)
                    {{$loan->amount - $loan_approve_paid}}
                    @endif
                	</td>
              </tr>
         </table>

         <br> <br> <br><br>
         <strong>Md. Mushfiqur Rahman</strong>
         <p> CEO & Managing Director (CC) </p>
        


    </section>
  </div>
    
  </div>
</div>

</body>
</html>
<script type="text/javascript">
        function printPage() {
            window.print();
        }
</script>