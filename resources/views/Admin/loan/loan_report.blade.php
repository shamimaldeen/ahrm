@extends('Admin.index')
@section('body')
<script src="{{url('/')}}/public/assets/global/plugins/jquery.min.js"></script>


<div class="row" style='padding: 30px; background-color: #f7f7f7; margin: 0px;'>

  <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{route('loan-report-filter')}}" >
      {{ csrf_field() }}
               
  @if($id->suser_level=="1")
  
  
  <div class="form-group">

   

          

   
    
    <label class="col-md-1 control-label" style="text-align: right;font-size:12px;padding: 5px 0px">
      Employee :
    </label>
    <div class="col-md-3">
       <select name="emp_id" id="emp_id" class="form-control chosen" required>
          @if($flag==0)
            <option value="0">Select</option>
          @elseif($flag==1)
            <option value="{{ $emp_filter->emp_id }}">{{$emp_filter->emp_name}}</option>
          @endif
            @foreach($all_emp as $row)
              <option value="{{ $row->emp_id }}">{{ $row->emp_name }}</option>
            @endforeach
        </select>     
    </div>

     <label class="col-md-1 control-label" style="text-align: right;font-size:12px;padding: 5px 0px">
     Taken Month :
    </label>

     <div class="col-md-3">
       <select name="month_id" id="month_id" class="form-control chosen" required>
             @if($flag==0)
            <option value="0">Select</option>
          @elseif($flag==1)
            <option value="{{ $month_filter->id }}">{{$month_filter->month}}</option>
          @endif
            @foreach($all_month as $row)
              <option value="{{ $row->id }}">{{ $row->month }}</option>
            @endforeach
        </select>     
    </div>


    <div class="col-md-4">
      @if($id->suser_level=="1")
          
          <button type="submit" class="btn btn-success btn-md btn-block">Search</button>                                      
      @endif
    </div>

  </div>
  
@endif

  


    <div class="col-md-12"  id="sms">
    </div>
  
  </form>
</div>

<div class="row">

    <div class="col-md-12">
       @include('error.msg')
       
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class="icon-settings font-dark"></i>
                    <span class="caption-subject bold uppercase" id="hidden_table_title">Loans Report
                      @if(isset($month_filter))
                      ({{ $month_filter->month }})
                      @endif
                    </span>
                </div>

                <div class="col-md-6"><p id="confirm_msg"></p></div>
                <div class="actions">

                   <a href="{{ route('loan-report-print') }}" type="button" class="btn btn-success" target="_blank">Print</a>
                  <!-- <a href="{{url('loans/create')}}" class="btn btn-sm btn-success"><i class="fa fa-plus"></i>&nbsp;Apply For Loan</a> -->
                    <div class="btn-group">
                        <a class="btn red btn-outline btn-circle" href="javascript:;" data-toggle="dropdown">
                            <i class="fa fa-gears"></i>
                            <span> Tools </span>
                            <i class="fa fa-angle-down"></i>
                        </a>
                        <ul class="dropdown-menu pull-right" id="sample_3_tools">
                            <li>
                                <a href="javascript:;" data-action="0" class="tool-action">
                                    <i class="icon-printer"></i> Print</a>
                            </li>                           
                               
                            <li>
                                <a href="javascript:;" data-action="2" class="tool-action">
                                    <i class="icon-doc"></i> PDF</a>
                            </li>
                            <li>
                                <a href="javascript:;" data-action="3" class="tool-action">
                                    <i class="icon-paper-clip"></i> Excel</a>
                            </li>                            
                            
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="tools"> </div>
            </div>
            
            <div class="portlet-body">
              <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{url('')}}" name="basic_validate" id="data_form">
              {{ csrf_field() }}
               <table class="table table-striped table-bordered table-hover" id="sample_3">
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
                            <td>
                               @php
                              $con = App\LoanApprove::orderBy('id', 'ASC')
                                    ->where('loan_id', $ln->id)
                                    ->where('after_installment', 0)
                                    ->count();
                              @endphp
                              {{ $con }}
                            </td>

                            <td>
                              @php
                              $start_month = App\LoanApprove::orderBy('id', 'ASC')
                                    ->where('loan_id', $ln->id)
                                    ->where('after_installment', 0)
                                    ->first();
                              @endphp
                              
                              {{ $start_month->install_month->month }}
                            </td>

                            <td>
                              @php
                              $end_month = App\LoanApprove::orderBy('id', 'DESC')
                                    ->where('loan_id', $ln->id)
                                    ->where('after_installment', 0)
                                    ->first();
                              @endphp
                               
                              {{ $end_month->install_month->month }} 
                            </td>
                            <td>
                              @php
                                $month_count = App\LoanApprove::where('loan_id', $ln->id)
                                ->where('after_installment', 0)
                                ->count();
                                $adjustment = $ln->amount/$month_count;
                              @endphp
                               {{ $adjustment }}
                            </td>
                            <td>
                              <!--  @php
                                $month_count = App\LoanApprove::where('loan_id', $ln->id)->count();
                                $adjustment = $ln->amount/$month_count;
                                $pending_count = App\LoanApprove::where('loan_id', $ln->id)->where('flag', 1)->count();
                                $adjustment_amount = $pending_count*$adjustment;
                              @endphp
                              {{ $adjustment_amount }} -->
                              @php
                                $paid_adjustment = App\LoanApprove::where('loan_id', $ln->id)
                                ->where('flag', 1)
                                ->sum('paid_amount');
                              @endphp
                              {{ $paid_adjustment }}
                            </td>
                            <td>
                               <!-- @php
                                $month_count = App\LoanApprove::where('loan_id', $ln->id)->count();
                                $adjustment = $ln->amount/$month_count;
                                $pending_count = App\LoanApprove::where('loan_id', $ln->id)->where('flag', 0)->count();
                                $pending = $pending_count*$adjustment;
                              @endphp
                              {{ $pending }} -->
                              {{ $ln->amount -  $paid_adjustment }}
                            </td>
                            <td><a href="{{url('loans')}}/{{$ln->id}}">{{$ln->purpose}}</a></td>
                            
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
@endsection