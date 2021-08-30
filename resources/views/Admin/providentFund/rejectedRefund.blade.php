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
            <span class="caption-subject bold uppercase" id="hidden_table_title">Rejected Provident Refund</span>
        </div>
        
        <div class="actions">
          <a href="{{url('provident-fund')}}" class="btn btn-primary btn-sm">Go Back</a>
        </div>
      </div>
      
      <div class="portlet-body">
      
      <div class="col-md-12">
        <table class="table table-bordered table-striped table-hover" id="sample_3">
          <thead>
            <tr>
              <th>SL</th>
              <th>Employee Name</th>
              <th>Apply Date</th>
              <th>Purpose</th>
              <th>Requested Amount</th>
              <th>Reason Of Rejection</th>
            </tr>
          </thead>
          <tbody>
          @if(isset($refunds[0]))
          <?php $c=0; ?>
          @foreach ($refunds as $refund)
          <?php $c++ ?>
            <tr id="tr-{{$refund->id}}">
              <td>{{$c}}</td>
              <td>{{$refund->employee->emp_name}} ({{$refund->employee->emp_empid}})</td>
              <td>{{$refund->apply_date}}</td>
              <td>{{$refund->purpose}}</td>
              <td>{{$refund->requested_amount}}</td>
              <td>{{$refund->reason_of_rejection}}</td>
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