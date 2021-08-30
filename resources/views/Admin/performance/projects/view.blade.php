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
                    <span class="caption-subject bold uppercase" id="hidden_table_title">Projects Information</span>
                </div>

                <div class="col-md-6"><p id="confirm_msg"></p></div>
                <div class="actions">
                    <div class="btn-group">
                        <a class="btn purple btn-outline btn-circle" href="javascript:;" data-toggle="dropdown">
                            <i class="fa fa-list"></i>
                            <span> Options </span>
                            <i class="fa fa-angle-down"></i>
                        </a>
                        <ul class="dropdown-menu" id="sample_3_tools">
                            <li>                                 
                                  <a href="{{url('projects/create')}}">
                                    <i class="fa fa-plus"></i> Add New</a>
                            </li>
                        </ul>
                    </div>
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
               <table class="table table-bordered">
                <tbody>
                  <tr>
                    <td style="width: 25%">Choose Goal :</td>
                    <td style="width: 75%">
                      <select name="goal_id" id="goal_id" class="form-control chosen" onchange="window.open('{{url('projects')}}/'+$('#goal_id').val(),'_parent')">
                        @if(isset($Goal) && count($Goal)>0)
                          @foreach ($Goal as $gl)
                          <option value="{{$gl->id}}" @if(isset($goal_id) && $gl->id==$goal_id) selected @endif>{{$gl->title}}</option>
                          @endforeach
                        @endif
                      </select>
                    </td>
                  </tr>
                </tbody>
               </table>
              </form>
            </div>
            <div class="portlet-body">
              <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{url('')}}" name="basic_validate" id="data_form">
              {{ csrf_field() }}
               <table class="table table-striped table-bordered table-hover" id="sample_3">

                      <thead>
                        <tr>
                          <th>SL</th>
                          <th>Name</th>
                          <th>Amount</th>
                          <th>Intensive Amount</th>
                          <th>Start Date</th>
                          <th>End Date</th>
                          <th>Status</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      @if(isset($Project) && count($Project)>0)
                      @php
                      $c=0;
                      @endphp
                        @foreach ($Project as $pr)
                        @php
                        $c++;
                        @endphp
                         <tr class="gradeX" id="tr-{{$pr->id }}">
                            <td>{{$c}}</td>
                            <td>{{$pr->project_name}}</td>
                            <td>{{$pr->project_amount}}</td>
                            <td>{{$pr->incentive_amount}}</td>
                            <td>{{$pr->start_date}}</td>
                            <td>{{$pr->end_date}}</td>
                            <td>
                              <a class="btn btn-xs btn-primary">{{$pr->status}}</a>
                            </td>
                            <td>
                              <a class="btn btn-xs btn-success" href="{{url('projects')}}/{{$pr->id}}/edit">Edit</a>
                              <a class="btn btn-xs btn-danger" onclick="DeleteData('{{$pr->id}}')">Delete</a>
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