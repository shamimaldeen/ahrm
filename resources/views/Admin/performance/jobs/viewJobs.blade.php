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
                    <span class="caption-subject bold uppercase" id="hidden_table_title">View Jobs Information of {{$Employee->emp_name}} ({{$Employee->emp_empid}})</span>
                </div>

                <div style="float:right">
                  <a class="btn btn-xs btn-primary" href="{{url('jobs')}}/{{$Employee->emp_id}}/assign" style="margin-top:10px">Assign New Job for {{$Employee->emp_name}} ({{$Employee->emp_empid}})</a>
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
                          <th>Goal</th>
                          <th>Project</th>
                          <th>Job Title</th>
                          <th>Job Weight</th>
                          <th>Start Date</th>
                          <th>End Date</th>
                          <th>Job Progress</th>
                          <th>Status</th>
                        </tr>
                      </thead>
                      @if(isset($Employee->jobs[0]) && count($Employee->jobs)>0)
                      @php
                      $c=0;
                      @endphp
                        @foreach ($Employee->jobs as $job)
                        @php
                        $c++;
                        @endphp
                        <tr class="gradeX" id="tr-{{$job->emp_id }}">
                          <td>
                            <a class="btn btn-primary btn-xs" href="{{url('jobs/')}}/{{$job->id}}/appraisal">Update Appraisal</a>
                          </td>
                          <td>{{$job->project->goal->title}}</td>
                          <td>{{$job->project->project_name}}</td>
                          <td><a href="{{url('jobs')}}/{{$job->id}}">{{$job->job_title}}</a></td>
                          <td>{{$job->job_weight}} %</td>
                          <td>{{$job->start_date}}</td>
                          <td>{{$job->end_date}}</td>
                          <td>{{$job->completion}} %</td>
                          <td>
                              <a class="btn btn-xs btn-success">{{$job->status}}</a>
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