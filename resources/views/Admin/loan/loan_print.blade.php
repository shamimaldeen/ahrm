
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
                  <img src="{{url('/')}}/public/Main_logo.jpg" width="auto" height="60"/>
                </div>
               <div style="padding-left: 10px;">
                   <h3 style="margin: 0px; padding: 0px;">PFI Securities Limited</h3>
                  <p style="">56-57, 7th & 8th Floor, PFI Tower, Dilkusha C/A, Dhaka 1000</p>
                </div>
            </div>
            <div style="width:30%; float:left;">
              <h2 style="text-align: center;">Loan Report</h2>
            </div>
        </div>
      </td>
    </tr>
  </thead>
  <!-- End Header -->
  <tr>
    <td>


<div class="row">

    <div class="col-md-12">
       @include('error.msg')
       

        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption font-dark" style="">
                    <i class="icon-settings font-dark"></i>
                    <span class="caption-subject bold uppercase" id="hidden_table_title"></span>
                </div>

                <div class="col-md-6"><p id="confirm_msg"></p></div>
                <div class="actions">

                 

                 
               
            
            <div class="portlet-body">
              <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{url('')}}" name="basic_validate" id="data_form">
              {{ csrf_field() }}
               <table width="100%" border="1px">
                     <thead>
                        <tr>
                          <!-- <th>Actions</th> -->
                          <th>Employee Name</th>
                          <th>Amount</th>
                          <th>Duration (Months)</th>
                          <th>Starting Month</th>
                          
                          <th>Ending Month</th>
                          <th>Monthly Installment</th>
                          <th>Adjustment</th>
                          <th>Pending Amount</th>

                          <th>Purpose</th>
                          
                          <!-- <th>Taken Month</th> -->
                          <!-- <th>Status</th> -->
                        </tr>
                      </thead>
                     @if(isset($Loan) && count($Loan)>0)
                      @php
                      $c=0;
                      @endphp
                        @foreach ($Loan as $ln)
                        @php
                        $c++;
                        @endphp
                         <tr class="gradeX" id="tr-{{$ln->id }}">
                            <!-- <td>
                              @if($ln->flag=="0" && $ln->emp_id==$id->suser_empid)
                              <a class="btn btn-xs btn-primary" href="{{url('loans')}}/{{$ln->id}}/edit">Edit</a>
                              <a class="btn btn-xs btn-danger" onclick="DeleteData('{{$ln->id}}')">Delete</a>
                              @endif
                            </td> -->
                            <td>{{$ln->employee->emp_name}} ({{$ln->employee->emp_empid}})</td>
                            <td>{{$ln->amount}}</td>
                            <td>{{ $ln->loanapprove->count() }}</td>

                            <td>
                              @php
                              $start_month = App\LoanApprove::orderBy('id', 'ASC')
                                    ->where('loan_id', $ln->id)
                                    ->first();
                              @endphp
                              
                              {{ $start_month->install_month->month }}
                            </td>

                            <td>
                              @php
                              $end_month = App\LoanApprove::orderBy('id', 'DESC')
                                    ->where('loan_id', $ln->id)
                                    ->first();
                              @endphp
                               
                              {{ $end_month->install_month->month }} 
                            </td>
                            <td>
                              @php
                                $month_count = App\LoanApprove::where('loan_id', $ln->id)->count();
                                $adjustment = $ln->amount/$month_count;
                              @endphp
                               {{ $adjustment }}
                            </td>
                            <td>
                               @php
                                $month_count = App\LoanApprove::where('loan_id', $ln->id)->count();
                                $adjustment = $ln->amount/$month_count;
                                $pending_count = App\LoanApprove::where('loan_id', $ln->id)->where('flag', 1)->count();
                                $adjustment_amount = $pending_count*$adjustment;
                              @endphp
                              {{ $adjustment_amount }}
                            </td>
                            <td>
                               @php
                                $month_count = App\LoanApprove::where('loan_id', $ln->id)->count();
                                $adjustment = $ln->amount/$month_count;
                                $pending_count = App\LoanApprove::where('loan_id', $ln->id)->where('flag', 0)->count();
                                $pending = $pending_count*$adjustment;
                              @endphp
                              {{ $pending }}
                            </td>
                            <td>{{$ln->purpose}}</td>
                            
                            <!-- <td>{{$ln->loan_month->month}} ({{$ln->year}})</td> -->
                            <!-- <td>
                              @if($ln->flag=="0")
                                <a class="btn btn-xs btn-warning">Pending</a>
                              @elseif($ln->flag=="1")
                                <a class="btn btn-xs btn-primary">Approved</a>
                              @elseif($ln->flag=="2")
                                <a class="btn btn-xs btn-success">Completed</a>
                              @elseif($ln->flag=="3")
                                <a class="btn btn-xs btn-danger">Rejected</a>
                              @endif
                            </td> -->
                          </tr>
                          @endforeach
                      @endif
                    </table></td>
  </tr>
  <!-- Start Space For Footer -->
  <tfoot>
    <tr>
      <td style="height: 3cm">
        <!-- Leave this empty and don't remove it. This space is where footer placed on print -->
      </td>
    </tr>
  </tfoot>
  <!-- End Space For Footer -->
</table>
<!-- Start Footer -->
<div class="print-footer">
  <div class="row print-footer">
                      <table class="sign-footer" style="table-layout: fixed; width:100%;">
                        <thead>
                          <td>-----------------------------<br>Authorized Signature</td>
                          
                        </thead>
                      </table>
                   
                  </form>
            </div>
          </div>
        </div>
      </div>
    </div>
<script type="text/javascript">
    function DeleteData(id) {
      $.ajax({
        headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' },
        url: "{{url('loans')}}/"+id,
        type: 'DELETE',
        dataType: 'json',
        data: {},
      })
      .done(function(response) {
        if(response.success){
          $('#tr-'+id).fadeOut();
        }else{
          $.alert({
          title:'Whoops!',
          content:'<strong class="text-danger">'+response.msg+'</strong>',
          type:'red',
        });
        }
      })
      .fail(function() {
        $.alert({
          title:'Whoops!',
          content:'<strong class="text-danger">Something Went Wrong!!</strong>',
          type:'red',
        });
      });
      
    }
  </script>
  <script type="text/javascript">
        function printPage() {
            window.print();
        }
</script>
