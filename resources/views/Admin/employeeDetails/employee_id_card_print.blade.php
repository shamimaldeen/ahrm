<style type="text/css">
        @media print{
            #p {
                display: none;
            }
        }

</style>

<input type="button" id="p" value="print" onclick="printPage();" class="btn btn-label-success btn-sm btn-upper">

@foreach($employeeDetails as $row)
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">

    <title>idcard-info</title>
  </head>
  <body>

    <section class="card-info d-flex p-3" style=" max-width: 900px; margin: auto;" > 
      <div class="card-font p-3 border " style="width: 50%; margin-right: 10px;">
        <div class="header-section text-center">
            <img src="{{url('/')}}/public/Main_logo.jpg" alt="logo" style="width: 60px;">
            <span style="font-size: 25px;font-weight: bold;">PFI Securities Limited</span>
            <small style="font-style: italic; display: block;margin-top:-20px;margin-left:1rem;">  a member of Prime Financial Group</small>
        </div>
   
        <div class="main-sec-wrapper d-flex justify-content-between mt-5">
        <div class="main-sec-left">
          <h4>{{ $row->emp_name }} </h4>
          <span style="display: block;"> {{ $row->designation->desig_name }} </span>
          <span style="display: block;"> Blood group:   {{ $row->emp_blgrp }} </span>
          <span style="display: block;">ID: {{$row->emp_empid}}</span>

          <div class="mt-5">
          <span style="display: block">    </span>

         <small style="display: block; padding-top: 1rem;" >  Signature of Card Holder </small>
       </div>
        </div>

        <div class="main-sec-right">
          <div class="user-image" style="width: 100px;height: 100px;">
          	@if($row->emp_imgext)
            <img src="{{URL::to('public/EmployeeImage')}}/{{$row->emp_id}}.{{$row->emp_imgext}}" style="width: 100%;height: 100%">
            @else
             <img src="{{url('/')}}/public/Main_logo.jpg" style="width: 100%;height: 100%">
            @endif
          </div>
          <div class="user-signature mt-4">
            <span style="display: block"></span>
            <small style="display: block; padding-top: 1rem;">  Authorized Signature</small>
          </div>
        </div>
      </div>
    </div>
      <div class="card-back border p-3"  style="width: 50%; margin-left: 10px;">
        <div class="top-contents">
        	@php
        		$project=App\ProjectDetails::first();
        	@endphp
          <small style="display:block;"> * This card is the property of {{$project->project_company}} </small>
          <small  style="display:block;"> * Upon Resignation this card must be returned to the HRM Department Immediately. </small>
          <small  style="display:block;"> * In case of emergency,please contact: {{$row->emp_emjcontact}}</small>
          <small  style="display:block ;margin-top:1rem;"> Note: If found please return it to the below address </small>
    
        </div>
        <div class="footer-contents border-top mt-4" style="text-align: center;">
           <strong>Head office: </strong> {{$project->project_address}}
           <small style="display: block;">{{$project->project_contact}} </small>
           <!-- <small  style="display: block;"> Fax: +88-02-9551598, Email: pfisl@yahoo.com </small> -->
           <small style="display: block;">  www.pfislbd.com </small>
        </div>
      </div>

    </section>












    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>
    -->
  </body>
</html>
@endforeach

<script type="text/javascript">
        function printPage() {
            window.print();
        }
</script>