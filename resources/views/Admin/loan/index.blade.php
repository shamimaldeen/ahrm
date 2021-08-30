@extends('Admin.index')
@section('body')
<script src="{{url('/')}}/public/assets/global/plugins/jquery.min.js"></script>

<div class="row">

    <div class="col-md-12">
       @include('error.msg')
       
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class="icon-settings font-dark"></i>
                    <span class="caption-subject bold uppercase" id="hidden_table_title">Loans Information</span>
                </div>

                <div class="col-md-6"><p id="confirm_msg"></p></div>
                <div class="actions">
                  <a href="{{url('loans/create')}}" class="btn btn-sm btn-success"><i class="fa fa-plus"></i>&nbsp;Apply For Loan</a>
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
                          <th>Actions</th>
                          <th>Employee Name</th>
                          <th>Purpose</th>
                          <th>Amount</th>
                          <th>Taken Month</th>
                          <th>Status</th>
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
                            <td>
                              @if($ln->flag=="0" && $ln->emp_id==$id->suser_empid)
                              <a class="btn btn-xs btn-primary" href="{{url('loans')}}/{{$ln->id}}/edit">Edit</a>
                              <a class="btn btn-xs btn-danger" onclick="DeleteData('{{$ln->id}}')">Delete</a>
                              @endif
                            </td>
                            <td>{{$ln->employee->emp_name}} ({{$ln->employee->emp_empid}})</td>
                            <td><a href="{{url('loans')}}/{{$ln->id}}">{{$ln->purpose}}</a></td>
                            <td>{{$ln->amount}}</td>
                            <td>{{$ln->loan_month->month}} ({{$ln->year}})</td>
                            <td>
                              @if($ln->flag=="0")
                                <a class="btn btn-xs btn-warning">Pending</a>
                              @elseif($ln->flag=="1")
                                <a class="btn btn-xs btn-primary">Approved</a>
                              @elseif($ln->flag=="2")
                                <a class="btn btn-xs btn-success">Completed</a>
                              @elseif($ln->flag=="3")
                                <a class="btn btn-xs btn-danger">Rejected</a>
                              @endif
                            </td>
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