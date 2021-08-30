@extends('Admin.index')
@section('body')
@php
use App\Http\Controllers\BackEndCon\Controller as C;
@endphp
<script src="{{url('/')}}/public/assets/global/plugins/jquery.min.js"></script>

<div class="row">
  <div class="col-md-12">
   @include('error.msg')
    
    <div class="portlet light">
      <div class="portlet-title">
        <div class="caption font-dark">
            <i class="icon-settings font-dark"></i>
            <span class="caption-subject bold uppercase" id="hidden_table_title">Provident Fund</span>
        </div>
        
        <div class="actions">
          <!-- <a class="btn btn-success btn-sm" data-toggle="collapse" data-target="#withdraw">Request For Withdraw</a> -->
          <a href="{{ route('provident-fund-print', $employee->emp_id) }}" class="btn btn-primary btn-sm">Print</a>
          <a href="{{url('provident-fund')}}/employee/list" class="btn btn-primary btn-sm">Go Back</a>
        </div>
      </div>
      
      <div class="portlet-body">

      <div class="col-md-12">
        <div class="collapse" id="withdraw">
          <div class="panel panel-default">
            <div class="panel-body">
              <h4><strong>Requests for withdraw</strong></h4>
              <hr>
              <form method="POST" action="{{url('provident-fund')}}/employee/apply" accept-charset="UTF-8" role="form" data-no-form-clear="1" class="production-form" id="production-form">
                {{csrf_field()}}
                <div class="form-group">
                  <label for="purpose">Purpose</label>
                  <input type="text" name="purpose" id="purpose" class="form-control" placeholder="Psurpose" autocomplete="off">
                </div>
                <div class="form-group">
                  <label for="amount_in_fund">Amount in fund</label>
                  <input type="text" name="amount_in_fund" value="{{C::decimal(($employeTotal+$compnayTotal)-$totalWithDrawn)}}" class="form-control" id="amount_in_fund" placeholder="Amount in fund" readonly="" style="background: white" autocomplete="off">
                </div>
                <div class="form-group">
                  <label for="requested_amount">Request amount</label>
                  <input type="text" name="requested_amount" class="form-control" id="requested_amount" placeholder="Request amount" autocomplete="off">
                </div>
                <input class="btn btn-primary pull-right" type="submit" value="Apply" autocomplete="off">         
              </form>
            </div>
          </div>
        </div>
      </div>

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

        <table class="table table-bordered table-hover table-striped" style="margin-bottom: 0px;">
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
            <td colspan="5" align="center">
             
            </td>
          </tr>
          </tbody>
          </table>

      </div>

      </div>
    </div>
    
  </div>
</div>
@endsection