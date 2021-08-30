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
          <a href="{{url('provident-fund')}}/generate/fund" class="btn btn-success btn-sm">Generate Provident Fund</a>
          <a href="{{url('provident-fund')}}/employee/list" class="btn btn-primary btn-sm">Employees fund</a>
          <a class="btn btn-primary btn-sm" data-toggle="collapse" data-target="#total_fund">Total fund</a>
          <!-- <a href="{{url('provident-fund')}}/pending-refund/list" class="btn btn-primary btn-sm">Pending refund <span class="btn btn-warning btn-xs btn-circle">{{$refundPending}}</span></a>
          <a href="{{url('provident-fund')}}/approved-refund/list" class="btn btn-primary btn-sm">Approved refund <span class="btn btn-success btn-xs btn-circle">{{$refundApproved}}</span></a>
          <a href="{{url('provident-fund')}}/withdrawn-refund/list" class="btn btn-primary btn-sm">Withdrawn refund <span class="btn btn-info btn-xs btn-circle">{{$refundWithdrawn}}</span></a>
          <a href="{{url('provident-fund')}}/rejected-refund/list" class="btn btn-primary btn-sm">Rejected refund <span class="btn btn-danger btn-xs btn-circle">{{$refundRejected}}</span></a> -->
          <a href="{{url('provident-fund')}}/employee/{{$id->suser_empid}}/{{date('Y')}}" class="btn btn-primary btn-sm">Your fund</a>
        </div>
      </div>
      
      <div class="portlet-body">

      <div class="col-md-12">
        <div class="collapse" id="total_fund">
          <div class="panel panel-default">
            <div class="panel-body">
              {!! $total_fund !!}
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-12">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h5><strong>Search Provident Fund....</strong></h5>
          </div>
          <div class="panel-body">
            <form class="form-inline text-center" onsubmit="return false;">
              <div class="form-group">
                <label for="month">Choose Month : </label>
                <select name="month" id="month" class="form-control chosen">
                  @if(isset($Month[0]))
                    @foreach($Month as $m)
                    <option value="{{$m->id}}" @if(isset($month) && $month==$m->id) selected @endif>{{$m->month}}</option>
                    @endforeach
                  @endif
                </select>
              </div>

              <div class="form-group">
                <label for="year">Choose Year : </label>
                <select name="year" id="year" class="form-control chosen">
                  @for($y=2000;$y<=2050;$y++)
                    <option  @if(isset($year) && $year==$y) selected @endif>{{$y}}</option>
                  @endfor
                </select>
              </div>
              <button type="button" class="btn btn-primary" onclick='window.open("{{url("provident-fund")}}/"+$("#month").val()+"/"+$("#year").val()+"/search","_parent")'><i class="fa fa-seach"></i>&nbsp;Search</button>
            </form>
          </div>
        </div>
      </div>
      
      <div class="col-md-12">
        <table class="table table-bordered table-striped table-hover" id="sample_3">
          <thead>
            <tr>
              <th>SL</th>
              <th>Employee Name</th>
              <th>Month</th>
              <th>Employee Amount</th>
              <th>Company Contribution</th>
              <th>Total</th>
            </tr>
          </thead>
          <tbody>
          @if(isset($funds[0]))
          <?php $c=0; ?>
          @foreach ($funds as $fund)
          <?php $c++ ?>
            <tr>
              <td>{{$c}}</td>
              <td>{{$fund->employee->emp_name}} ({{$fund->employee->emp_empid}})</td>
              <td>{{$fund->provident_month->month}} ({{$fund->year}})</td>
              <td>{{C::decimal($fund->employee_amount)}}</td>
              <td>{{C::decimal($fund->company_amount)}}</td>
              <td>{{C::decimal($fund->employee_amount+$fund->company_amount)}}</td>
            </tr>
          @endforeach
          @endif
          </tbody>
        </table>
      </div>

      </div>
    </div>
    
  </div>
</div>
@endsection