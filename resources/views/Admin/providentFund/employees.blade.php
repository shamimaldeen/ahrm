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
          <a href="{{url('provident-fund')}}" class="btn btn-primary btn-sm">Go Back</a>
        </div>
      </div>

      <div class="portlet-body">

      <div class="col-md-12">
        <table class="table table-bordered table-striped table-hover" id="sample_3">
          <thead>
            <tr>
              <th>SL</th>
              <th>Employee ID</th>
              <th>Face ID</th>
              <th>Employee Name</th>
              <th>Designation</th>
              <th>Department</th>
            </tr>
          </thead>
          <tbody>
          @if(isset($employees[0]))
          <?php $c=0; ?>
          @foreach ($employees as $employee)
          <?php $c++ ?>
            <tr>
              <td>{{$c}}</td>
              <td>{{$employee->emp_empid}}</td>
              <td>{{$employee->emp_machineid}}</td>
              <td><a href="{{url('provident-fund')}}/employee/{{$employee->emp_id}}">{{$employee->emp_name}}</a></td>
              <td>{{(($employee->designation) ? $employee->designation->desig_name : '' )}}</td>
              <td>{{(($employee->department) ? $employee->department->depart_name : '' )}}</td>
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