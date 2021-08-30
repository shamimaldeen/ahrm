@extends('Admin.index')
@section('body')

 <table class="table table-striped table-bordered table-hover nowrap" id="sample_3">

                        <thead>
                        <tr>
                          <th>SL</th>
                          <th>Month-Year</th>
                          <th>Action</th>
                          
                         
                           
                        </tr>
                      </thead>
                      <tbody>
                      	@php($i=1)
                      	@foreach($payroll as $row)
                       <tr>
                       	<td>{{ $i }}</td>
                       	<td>{{ date('M-Y', strtotime($row->payroll_date_from)) }}</td>
                       	<td>
                       		<a href="{{ route('payroll-delete', $row->payroll_date_from) }}" class="btn btn-danger">Delete Payroll</a>
                       	</td>
                       </tr>
                       @php($i++)
                       @endforeach
                      </tbody>
                    </table>

@endsection