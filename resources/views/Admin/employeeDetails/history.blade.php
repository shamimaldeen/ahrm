@extends('Admin.index')
@section('body')

@php
use App\Http\Controllers\BackEndCon\Controller;
use Illuminate\Support\Facades\Route;
$route=Route::getFacadeRoot()->current()->uri();
@endphp

@if(Controller::checkAppModulePriority($route,'View History')=="1")
@else
<script type="text/javascript">location="{{URL::to('/')}}"</script>
@endif

<script src="{{URL::to('/')}}/public/assets/global/plugins/jquery.min.js" type="text/javascript"></script>

  <div class="row">
    <div class="col-md-12">
       @include('error.msg')
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light bordered">

            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class="icon-settings font-dark"></i>
                    <span class="caption-subject bold uppercase" id="hidden_table_title">Employee History of {{$employee->emp_name}} ({{$employee->emp_empid}})</span>
                </div>

                <div class="col-md-6"><p id="confirm_msg"></p></div>
                <div class="actions">
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
              <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{URL::to('')}}" id="data_form">
              {{ csrf_field() }}
               <table class="table table-striped table-bordered" id="sample_3">

                      <thead>
                        <tr>
                          <th>SL</th>
                          <th>Updated DateTime</th>
                          <th>Updated Details</th>
                          <th>Updated By</th>
                        </tr>
                      </thead>
              
                      <tbody>
                      @if(isset($employeeHistory) && count($employeeHistory)>0)
                      <?php $c=0; ?>
                        @foreach ($employeeHistory as $eh)
                        <?php $c++; ?>
                          <tr class="gradeX" id="tr-{{$eh->emp_id}}">
                            <td>{{$c}}</td>
                            <td>{{$eh->eh_datetime}}</td>
                            <td>
                              {!! $eh->eh_log !!}
                            </td>
                            <td>{{$eh->operator->emp_name}} ({{$eh->operator->emp_empid}})</td>
                          </tr>
                        @endforeach
                      @endif
                      </tbody>

                    </table>
                  </form>
            </div>
          </div>
        </div>
      </div>
    </div>

<script src="{{URL::to('/')}}/public/js/bootstrap-datepicker.js"></script> 
@endsection