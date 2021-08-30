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
                    <span class="caption-subject bold uppercase" id="hidden_table_title">Jobs Information</span>
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
              <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{url('')}}" name="basic_validate" id="data_form">
              {{ csrf_field() }}
               <table class="table table-striped table-bordered table-hover" id="sample_3">

                      <thead>
                        <tr>
                          <th>Actions</th>
                          <th>Employee ID</th>
                          <th>Face ID</th>
                          <th>Employee Name</th>
                          <th>Designation</th>
                          <th>Department</th>
                          <th>Phone Number</th>
                          <th>Running jobs</th>
                          <th>Finished jobs</th>
                          <th>Total Assaigned jobs</th>
                        </tr>
                      </thead>
                      @if(isset($Employee) && count($Employee)>0)
                      @php
                      $c=0;
                      @endphp
                        @foreach ($Employee as $emp)
                        @php
                        $c++;
                        @endphp
                         <tr class="gradeX" id="tr-{{$emp->emp_id }}">
                            <td>
                              <a class="btn btn-xs btn-primary" href="{{url('jobs')}}/{{$emp->emp_id}}/view">View Jobs</a>
                              <br>
                              <a class="btn btn-xs btn-success" href="{{url('jobs')}}/{{$emp->emp_id}}/assign">Assign Jobs</a>
                            </td>
                            <td>{{$emp->emp_empid}}</td>
                            <td>{{$emp->emp_machineid}}</td>
                            <td>{{$emp->emp_name}}</td>
                            <td>{{(($emp->designation) ? $emp->designation->desig_name : '' )}}</td>
                            <td>{{(($emp->department) ? $emp->department->depart_name : '' )}}</td>
                            <td>{{$emp->emp_phone}}</td>
                            <td>{{count($emp->runningjobs)}}</td>
                            <td>{{count($emp->finishedjobs)}}</td>
                            <td>{{count($emp->jobs)}}</td>
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
        url: "{{url('projects')}}/"+id,
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