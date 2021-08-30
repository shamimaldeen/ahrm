
@php
use App\Http\Controllers\BackEndCon\Controller as C;
@endphp
<script src="{{url('/')}}/public/assets/global/plugins/jquery.min.js"></script>
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
<input type="button" id="p" value="print" onclick="printPage();" class="btn btn-label-success btn-sm btn-upper">
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
              <h2 style="text-align: center;">Provident Fund</h2>
            </div>
        </div>

<div class="row">
  <div class="col-md-12">
  
    
    <div class="portlet light">
     
      
      <div class="portlet-body">

     

      <div class="col-md-12">
        <table class="table table-bordered table-hover table-striped" style="margin-bottom: 20px;">
          <thead>
            <tr>
              <th>Name : {{$employee->emp_name}}</th>
              <th>Department : {{(($employee->department) ? $employee->department->depart_name : '' )}}</th>
              <th>Designation : {{(($employee->designation) ? $employee->designation->desig_name : '' )}}</th>
              <th>Employee ID : {{$employee->emp_empid}}</th>
              <th>Face ID : {{$employee->emp_machineid}}</th>
            </tr>           
          </thead>
        </table>

        <table class="table table-bordered table-hover table-striped" style="margin-bottom: 0px;" width="100%" border="1px">
           <thead>
            <tr>
              <th>SL</th>
              <th>Month</th>
              <th style="text-align: right;">Employee Amount</th>
              <th style="text-align: right;">Company Contribution</th>
              <th style="text-align: right;">Total</th>
            </tr>
          </thead>
          <tbody>
          @if(isset($funds[0]))
          <?php $c=0; ?>
          @foreach ($funds as $fund)
          <?php $c++ ?>
            <tr>
              <td>{{$c}}</td>
              <td>{{ $fund->provident_month->month }}({{ $fund->year }}) </td>
              <td class="text-right">{{C::decimal($fund->employee_amount)}} BDT</td>
              <td class="text-right">{{C::decimal($fund->company_amount)}} BDT</td>
              <td class="text-right">{{C::decimal($fund->employee_amount+$fund->company_amount)}} BDT</td>
            </tr>
          @endforeach
          @endif
          <tr>
              <th></th>
              <th style="text-align:right;">Total</th>
              <th style="text-align:right;">{{C::decimal($employeTotal)}} BDT </th>
              <th style="text-align:right;">{{C::decimal($compnayTotal)}} BDT </th>
              <th style="text-align:right;">{{C::decimal($employeTotal+$compnayTotal)}} BDT</th>
            </tr>
          <tr>
           
          </tr>
          </tbody>
          </table><br><br><br>
          <div class="print-footer">
  <div class="row print-footer">
                      <table class="sign-footer" style="table-layout: fixed; width:100%;">
                        <thead>
                          <td>-----------------------------<br>Authorized Signature</td>
                          <td>-----------------------------<br>Authorized Signature</td>
                          <td>-----------------------------<br>Authorized Signature</td>
                          <td>-----------------------------<br>Authorized Signature</td>
                        </thead>
                      </table>
                    </div>
</div>

      </div>

      </div>
    </div>
    
  </div>
</div>

<script type="text/javascript">
        function printPage() {
            window.print();
        }
</script>
