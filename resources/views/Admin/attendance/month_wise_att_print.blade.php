<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Employee Attendance</title>
    <style>
        table {
              font-family: arial, sans-serif;
              border-collapse: collapse;
              width: 100%;
            }

            td, th {
              border: 1px solid black;
/*              text-align: left;
*/              padding: 8px;
            }

            @media print{
            #p {
                display: none;
            }
        }


    </style>
    <input type="button" id="p" value="print" onclick="printPage();" class="btn btn-label-success btn-sm btn-upper">
  </head>
  <body>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
<header class="header-info d-flex justify-content-center flex-wrap">
                <div class="logo-image" style="width: 80px;">
                  <img src="{{url('/')}}/public/Main_logo.jpg" alt="logo" style="width:100%;">
                </div>
                <div style="width: 100%;text-align: center;">
                <span style="font-size: 21px; display: block;">  PFI Securities Limited </span>
                <small>DP-17400, PFI Tower(8th floor)56-57, Dilkusha
                 Dhaka
                  </small>
                </div>
                
             <h4 class="mt-4">Employee Attendance</h4>
         </header>

<table>
    @php
    $month= App\Month::where('id', $mon)->first();
    @endphp

   <tr style="background:#dddddd;">
    <th style="text-align:center;"; colspan="32">P = Present, A = Absent, LA = Late, H = Holiday, ENNF = No Entry Found, EXNF = No Exit Found, W = Weekend</th>

  </tr>
 <tr style="background:#dddddd;">
    <th style="text-align:center;"; colspan="32">{{ $month->month }} - 2021</th>

  </tr>
  <tr style="background:#dddddd;">
    <th>Employee Name</th>
    <th>1</th>
    <th>2</th>
    <th>3</th>
    <th>4</th>
    <th>5</th>
    <th>6</th>
    <th>7</th>
    <th>8</th>
    <th>9</th>
    <th>10</th>
    <th>11</th>
    <th>12</th>
    <th>13</th>
    <th>14</th>
    <th>15</th>
    <th>16</th>
    <th>17</th>
    <th>18</th>
    <th>19</th>
    <th>20</th>
    <th>21</th>
    <th>22</th>
    <th>23</th>
    <th>24</th>
    <th>25</th>
    <th>26</th>
    <th>27</th>
    <th>28</th>
     @if($month->days == 31 || $month->days == 30)
    <th>29</th>
    @endif
     @if($month->days == 31 || $month->days == 30)
    <th>30</th>
    @endif
    @if($month->days == 31)
    <th>31</th>
    @endif
  </tr>

  

@foreach($attendances as $row)
  <tr>
    <td style="background:#dddddd; font-weight: bold;">
        @php
            $name= App\Employee::where('emp_id', $row->emp_id)->first();
        @endphp
        {{ $name->emp_name }}</td>
    <td>
        @php
            $a1=App\TotalWorkingHours::where('emp_id', $row->emp_id)
            ->where('date', '2021-'.$mon.'-01')->first();
        @endphp
        @if(isset($a1))
        {{ $a1->status }}
        @endif
    </td>
    <td>
        @php
            $a2=App\TotalWorkingHours::where('emp_id', $row->emp_id)
            ->where('date', '2021-'.$mon.'-02')->first();
        @endphp
         @if(isset($a2))
        {{ $a2->status }}
        @endif
    </td>
    <td>
        @php
            $a3=App\TotalWorkingHours::where('emp_id', $row->emp_id)
            ->where('date', '2021-'.$mon.'-03')->first();
        @endphp
         @if(isset($a3))
        {{ $a3->status }}
        @endif
    </td>
    <td>
         @php
            $a4=App\TotalWorkingHours::where('emp_id', $row->emp_id)
            ->where('date', '2021-'.$mon.'-04')->first();
        @endphp
         @if(isset($a4))
        {{ $a4->status }}
        @endif
    </td>
    <td>
         @php
            $a5=App\TotalWorkingHours::where('emp_id', $row->emp_id)
            ->where('date', '2021-'.$mon.'-05')->first();
        @endphp
         @if(isset($a5))
        {{ $a5->status }}
        @endif
    </td>
    <td>
         @php
            $a6=App\TotalWorkingHours::where('emp_id', $row->emp_id)
            ->where('date', '2021-'.$mon.'-06')->first();
        @endphp
         @if(isset($a6))
        {{ $a6->status }}
        @endif
    </td>
    <td>
         @php
            $a7=App\TotalWorkingHours::where('emp_id', $row->emp_id)
            ->where('date', '2021-'.$mon.'-07')->first();
        @endphp
         @if(isset($a7))
        {{ $a7->status }}
        @endif
    </td>
    <td>
         @php
            $a8=App\TotalWorkingHours::where('emp_id', $row->emp_id)
            ->where('date', '2021-'.$mon.'-08')->first();
        @endphp
         @if(isset($a8))
        {{ $a8->status }}
        @endif
    </td>
    <td>
         @php
            $a9=App\TotalWorkingHours::where('emp_id', $row->emp_id)
            ->where('date', '2021-'.$mon.'-09')->first();
        @endphp
         @if(isset($a9))
        {{ $a9->status }}
        @endif
    </td>
    <td>
         @php
            $a10=App\TotalWorkingHours::where('emp_id', $row->emp_id)
            ->where('date', '2021-'.$mon.'-10')->first();
        @endphp
         @if(isset($a10))
        {{ $a10->status }}
        @endif
    </td>
    <td>
         @php
            $a11=App\TotalWorkingHours::where('emp_id', $row->emp_id)
            ->where('date', '2021-'.$mon.'-11')->first();
        @endphp
         @if(isset($a11))
        {{ $a11->status }}
        @endif
    </td>
    <td>
         @php
            $a12=App\TotalWorkingHours::where('emp_id', $row->emp_id)
            ->where('date', '2021-'.$mon.'-12')->first();
        @endphp
         @if(isset($a12))
        {{ $a12->status }}
        @endif
    </td>
    <td>
         @php
            $a13=App\TotalWorkingHours::where('emp_id', $row->emp_id)
            ->where('date', '2021-'.$mon.'-13')->first();
        @endphp
         @if(isset($a13))
        {{ $a13->status }}
        @endif
    </td>
    <td>
         @php
            $a14=App\TotalWorkingHours::where('emp_id', $row->emp_id)
            ->where('date', '2021-'.$mon.'-14')->first();
        @endphp
         @if(isset($a14))
        {{ $a14->status }}
        @endif
    </td>
    <td>
         @php
            $a15=App\TotalWorkingHours::where('emp_id', $row->emp_id)
            ->where('date', '2021-'.$mon.'-15')->first();
        @endphp
         @if(isset($a15))
        {{ $a15->status }}
        @endif
    </td>
    <td>
         @php
            $a16=App\TotalWorkingHours::where('emp_id', $row->emp_id)
            ->where('date', '2021-'.$mon.'-16')->first();
        @endphp
         @if(isset($a16))
        {{ $a16->status }}
        @endif
    </td>
    <td>
         @php
            $a17=App\TotalWorkingHours::where('emp_id', $row->emp_id)
            ->where('date', '2021-'.$mon.'-17')->first();
        @endphp
         @if(isset($a17))
        {{ $a17->status }}
        @endif
    </td>    
    <td>
         @php
            $a18=App\TotalWorkingHours::where('emp_id', $row->emp_id)
            ->where('date', '2021-'.$mon.'-18')->first();
        @endphp
         @if(isset($a18))
        {{ $a18->status }}
        @endif
    </td>
    <td>
         @php
            $a19=App\TotalWorkingHours::where('emp_id', $row->emp_id)
            ->where('date', '2021-'.$mon.'-19')->first();
        @endphp
         @if(isset($a19))
        {{ $a19->status }}
        @endif
    </td>
    <td>
         @php
            $a20=App\TotalWorkingHours::where('emp_id', $row->emp_id)
            ->where('date', '2021-'.$mon.'-20')->first();
        @endphp
         @if(isset($a20))
        {{ $a20->status }}
        @endif
    </td>
    <td>
         @php
            $a21=App\TotalWorkingHours::where('emp_id', $row->emp_id)
            ->where('date', '2021-'.$mon.'-21')->first();
        @endphp
         @if(isset($a21))
        {{ $a21->status }}
        @endif
    </td>
    <td>
         @php
            $a22=App\TotalWorkingHours::where('emp_id', $row->emp_id)
            ->where('date', '2021-'.$mon.'-22')->first();
        @endphp
         @if(isset($a22))
        {{ $a22->status }}
        @endif
    </td>
    <td>
         @php
            $a23=App\TotalWorkingHours::where('emp_id', $row->emp_id)
            ->where('date', '2021-'.$mon.'-23')->first();
        @endphp
         @if(isset($a23))
        {{ $a23->status }}
        @endif
    </td>
    <td>
         @php
            $a24=App\TotalWorkingHours::where('emp_id', $row->emp_id)
            ->where('date', '2021-'.$mon.'-24')->first();
        @endphp
         @if(isset($a24))
        {{ $a24->status }}
        @endif
    </td>
    <td>
         @php
            $a25=App\TotalWorkingHours::where('emp_id', $row->emp_id)
            ->where('date', '2021-'.$mon.'-25')->first();
        @endphp
         @if(isset($a25))
        {{ $a25->status }}
        @endif
    </td>
    <td>
         @php
            $a26=App\TotalWorkingHours::where('emp_id', $row->emp_id)
            ->where('date', '2021-'.$mon.'-26')->first();
        @endphp
         @if(isset($a26))
        {{ $a26->status }}
        @endif
    </td>
    <td>
         @php
            $a27=App\TotalWorkingHours::where('emp_id', $row->emp_id)
            ->where('date', '2021-'.$mon.'-27')->first();
        @endphp
         @if(isset($a27))
        {{ $a27->status }}
        @endif
    </td>
    <td>
         @php
            $a28=App\TotalWorkingHours::where('emp_id', $row->emp_id)
            ->where('date', '2021-'.$mon.'-28')->first();
        @endphp
         @if(isset($a28))
        {{ $a28->status }}
        @endif
    </td>
    <td>
         @php
            $a29=App\TotalWorkingHours::where('emp_id', $row->emp_id)
            ->where('date', '2021-'.$mon.'-29')->first();
        @endphp
         @if(isset($a29))
        {{ $a29->status }}
        @endif
    </td>
    <td>
         @php
            $a30=App\TotalWorkingHours::where('emp_id', $row->emp_id)
            ->where('date', '2021-'.$mon.'-30')->first();
        @endphp
         @if(isset($a30))
        {{ $a30->status }}
        @endif
    </td>
    @if($month->days == 31)
    <td> 
        @php
            $a31=App\TotalWorkingHours::where('emp_id', $row->emp_id)
            ->where('date', '2021-'.$mon.'-31')->first();
        @endphp
         @if(isset($a31))
        {{ $a31->status }}
        @endif
        </td>
        @endif
  </tr>
  @endforeach
  
      
</table>

</div>
</div>
</div>



    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    -->
  </body>
</html>
<script type="text/javascript">
        function printPage() {
            window.print();
        }
</script>



