<!DOCTYPE html>
<html lang="en">
<head>
  <title>Application for late office</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

  <style type="text/css">
        @media print{
            #p {
                display: none;
            }
        }
</style>
  <style>

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

  </style>
</head>
<body>

<input type="button" id="p" value="print" onclick="printPage();" class="btn btn-label-success btn-sm btn-upper">
  
<div class="container">
  <div class="row">
    <div class="col-md-12">
         <header class="header-info text-center">
          <div class="logo-image">
            <img src="http://localhost/pfi/public/logo.png" alt="logo"/>
          </div>
          <span>  PFI Securities Limited </span>
          <small>56-57, 7th & 8th Floor, PFI Tower, Dilkusha C/A, Dhaka 1000
            </small>
         </header>
    <section class="late-application">
          <div class="application-sub text-center">
           <strong><u> Request Application for Late Coming in Office </u></strong>
         </div>
         <br>
         <p class="date">
          Date: {{ date('d-m-Y') }}
         </p>
         <br> 
         <p class="subject-heading">
          Subject:  Application for Late Entry 

         </p>
         <br>
         <p>Respected Sir,</p>
         <p>I have been working in the @if(isset($employee)) {{ $employee->department->depart_name }} @endif @if(!isset($employee))--------------@endif department of this company as an  @if(isset($employee)) {{ $employee->designation->desig_name }} @endif @if(!isset($employee))--------------@endif. I did not come on time today because of -----------------------------------------------------------------------------------------------. I am well aware of the company policies towards the latecomers but I have a genuine reason for which was beyond my resources. I am assuring you not to be late in the office again.</p>
         <br>
         <p>Please accept my apology for coming late office in the morning.</p>
         <br> <br>
         <p>Thank you  </p>
         <p> Name: @if(isset($employee)) {{ $employee->emp_name }} @endif </p>
         <p> Employee ID: @if(isset($employee)) {{ $employee->emp_empid }} @endif </p>



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
