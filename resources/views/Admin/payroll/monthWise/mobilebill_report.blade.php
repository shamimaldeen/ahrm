@extends('Admin.index')
@section('body')
<script src="{{URL::to('/')}}/public/assets/global/plugins/jquery.min.js"></script>

<div class="row">

    <div class="col-md-12">

       @include('error.msg')
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class="icon-settings font-dark"></i>
                    <span class="caption-subject bold uppercase"> Mobile Bill Report</span>
                </div>
            </div>
            <div class="portlet-body">
              <!-- filter start -->
              <div class="row" style='padding: 30px; background-color: #f7f7f7; margin: 0px;'>
                <form class="form-horizontal" method="post" enctype="multipart/form-data" action="#" name="basic_validate" id="basic_validate" novalidate="novalidate" onsubmit="return false">
                {{ csrf_field() }}

                <div class="form-group" hidden="">
                  <label class="col-md-1 col-md-offset-3 control-label" style="text-align: right;padding: 5px 0px">
                    Employee Type :
                  </label>
                  <div class="col-md-4">
                    <select name="type" id="type" class="form-control chosen">
                      @foreach($employee_types as $row)
                      <option value="{{ $row->id }}">{{ $row->name }}</option>
                      @endforeach
                      <!-- <option value="0">Factory Officers and Staffs</option> -->
                    </select>
                  </div>
                </div>
                <div class="form-group" hidden="">
                  <label class="col-md-1 col-md-offset-3 control-label" style="text-align: right;padding: 5px 0px">
                    Report Type :
                  </label>
                  <div class="col-md-4">
                    <select name="report" id="report" class="form-control chosen">
                      <option value="1">Bank Payment</option>
                      <option value="0">Cash Payment</option>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-md-1 col-md-offset-3 control-label" style="text-align: right;padding: 5px 0px">
                    Month :
                  </label>
                  <div class="col-md-4">
                    <select name="month" id="month" class="form-control chosen">
                    @if(isset($months[0]))
                    @foreach ($months as $key => $m)
                      <option @if($month==$m->id) selected @endif value="{{$m->id}}">{{$m->month}}</option>
                    @endforeach
                    @endif
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-md-1 col-md-offset-3 control-label" style="text-align: right;padding: 5px 0px">
                    Year :
                  </label>
                  <div class="col-md-4">
                    <select name="year" id="year" class="form-control chosen">
                    @for($y=2019; $y <= 2030;$y++)
                      <option @if(isset($year) && $year==$y) selected @elseif($y==date('Y')) selected @endif>{{$y}}</option>
                    @endfor
                    </select>
                </div>
                <div class="form-group">
                  <br>
                  <br>
                  <br>
                  <div class="col-md-4 col-md-offset-4 text-center">
                    <button type="button" class="btn btn-primary" onclick="window.open('{{url('month-wise-mobilebill-report')}}/'+$('#type').val()+'/'+$('#report').val()+'/'+$('#month').val()+'/'+$('#year').val(),'_blank')"><i class="fa fa-print"></i>&nbsp;Print Report</button>
                  </div>
                </div>
                </form>
              </div>
        </div>
      </div>
    </div>
@endsection