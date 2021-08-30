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
                    <span class="caption-subject bold uppercase" id="hidden_table_title">Extends Salary Head Information</span>
                </div>

                <div class="col-md-6"><p id="confirm_msg"></p></div>
                
                <div class="actions">
                  <a href="{{url('extends-salary-head/create')}}" class="btn btn-success btn-sm" style="margin: 5px;"><i class="fa fa-plus"></i>&nbsp;Add New</a>
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
                          <th>SL</th>
                          <th>Salary Head Name</th>
                          <th>Unit for Absent</th>
                          <th>Percentage for Basic</th>
                          <th>Percentage for Total</th>
                          <th>Active Percentage</th>
                          <th>Type</th>
                          <th>Status</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      @if(isset($head) && count($head)>0)
                      @php
                      $c=0;
                      @endphp
                        @foreach ($head as $hd)
                        @php
                        $c++;
                        @endphp
                         <tr id="tr-{{$hd->head_id }}">
                            <td>{{$c}}</td>
                            <td>{{$hd->head_name}}</td>
                            <td>{{$hd->head_unit_for_absent}}</td>
                            <td>{{$hd->head_percentage_for_basic}} %</td>
                            <td>{{$hd->head_percentage_for_total}} %</td>
                            <td>
                            @if($hd->head_percentage_status=="1")
                              <a class="btn btn-xs btn-success">Basic</a>
                            @elseif($hd->head_percentage_status=="2")
                              <a class="btn btn-xs btn-primary">Total</a>
                            @endif
                            </td>
                            <td>
                            @if($hd->head_status=="1")
                              <a class="btn btn-xs btn-success">Active</a>
                            @elseif($hd->head_status=="0")
                              <a class="btn btn-xs btn-danger">Inactive</a>
                            @endif
                            </td>
                            <td>
                            @if($hd->head_type=="1")
                              <a class="btn btn-xs btn-success">Addition</a>
                            @elseif($hd->head_type=="0")
                              <a class="btn btn-xs btn-danger">Deduction</a>
                            @endif
                            </td>
                            <td>
                              <a class="btn btn-xs btn-success" href="{{url('extends-salary-head')}}/{{$hd->head_id }}/edit">Edit</a>
                              <!-- <a class="btn btn-xs btn-danger" onclick="DeleteData('{{$hd->head_id }}')">Delete</a> -->
                            </td>
                          </tr>
                        @endforeach
                      @endif
                      <tbody>
                      </tbody>

                    </table>
                  </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script type="text/javascript">
      function DeleteData(head_id) {
        $.ajax({
          headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' },
          url: "{{url('extends-salary-head')}}/"+head_id,
          type: 'DELETE',
          dataType: 'json',
          data: {},
        })
        .done(function(response) {
          if(response.success){
            $('#tr-'+head_id).fadeOut();
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