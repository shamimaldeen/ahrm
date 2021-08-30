@extends('Admin.index')
@section('body')
@php use App\Http\Controllers\BackEndCon\noticeController as NC @endphp

<script src="{{url('/')}}/public/assets/global/plugins/jquery.min.js"></script>

<div class="row">
    <div class="col-md-12">
       @include('error.msg')
       
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class="icon-settings font-dark"></i>
                    <span class="caption-subject bold uppercase" id="hidden_table_title">Notice Board</span>
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
                                  <a href="{{url('notice-board-view/create')}}">
                                    <i class="fa fa-plus"></i> Add New</a>
                            </li>
                            <li>                                 
                                  <a onclick="return Edit();">
                                    <i class="fa fa-pencil"></i> Edit</a>
                            </li>
                            <li>                                 
                                  <a  onclick="return Delete();">
                                    <i class="icon-trash"></i> Delete</a>
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
               <table class="table table-striped table-bordered table-hover" id="sample_3">

                        <thead>
                        <tr>
                          <th>
                          </th>
                          <th>SL</th>
                          <th>Title</th>
                          <th class="col-md-4">Notice</th>
                          <th>Selected Department</th>
                          <th>Selected Employee(s)</th>
                          <th>Posted By</th>
                          <th>Publish From</th>
                          <th>Publish To</th>
                          <th>Status</th>
                          <th>Created At</th>
                          <th>Updated At</th>
                        </tr>
                      </thead>
                      @if(isset($notice) && count($notice)>0)
                      @php
                      $c=0;
                      @endphp
                        @foreach ($notice as $nt)
                        @php

                        $c++;
                        @endphp
                         <tr class="gradeX" id="tr-{{$nt->notice_id}}">
                            <td>
                                <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                    <input type="checkbox" class="checkboxes" value="{{$nt->notice_id}}" name="notice_id[]" />
                                    <span></span>
                                </label>
                            </td>
                            <td>{{$c}}</td>
                            <td>{{$nt->notice_title}}</td>
                            <td >{{substr($nt->notice_desc,0,100)}}...</td>
                            <td>{{ $nt->department->depart_name }} </td>
                            <td>
                              {{NC::getEmployeeList($nt->notice_id)}}
                            </td>
                            <td>{{$nt->addedby->emp_name}} ({{$nt->addedby->emp_empid}})</td>
                            <td>{{$nt->notice_publish_from}}</td>
                            <td>{{$nt->notice_publish_to}}</td>
                            <td>
                              @if($nt->notice_status=="1")
                              <a class="btn btn-xs btn-success">Active</a>
                              @elseif($nt->notice_status=="0")
                              <a class="btn btn-xs btn-danger">InActive</a>
                              @endif
                            </td>
                            <td>{{$nt->notice_created_at}}</td>
                            <td>{{$nt->notice_updated_at}}</td>
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
<script  type="text/javascript">
  function Edit() {
    var data=$('#data_form').serializeArray();
    $.ajax({
      headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
      url: "{{url('notice-board-edit')}}",
      type: 'POST',
      data: data,
      success:function(data) {
        if(data=="null"){
          $('#confirm_msg').html('<b class="alert alert-danger" style="padding:0px 10px 0px 10px">Please Select A Notice To Edit</b>');
        }else if(data=="max"){
          $('#confirm_msg').html('<b class="alert alert-danger" style="padding:0px 10px 0px 10px">Please Select Maximum Of One Notice To Edit</b>');
        }else{
          window.open("{{url('notice-board-view')}}/"+data+"/edit","_parent"); 
        }
      }
    });
}

function Delete() {
  var confirm_msg = confirm("Are  You Confirm To Delete ?");
  if (confirm_msg){
      var data=$('#data_form').serializeArray();
      $.ajax({
        headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
        url: "{{url('notice-board-delete')}}",
        type: 'POST',
        data: data,
        success:function(data) {
          if(data=="1"){
            location.reload();
          }else if(data=="0"){
            $('#confirm_msg').html('<b class="alert alert-danger" style="padding:0px 10px 0px 10px">Something Went Wrong!!</b>');
          }else if(data=="null"){
            $('#confirm_msg').html('<b class="alert alert-danger" style="padding:0px 10px 0px 10px">Please Select One or More Notice To Delete</b>');
          }
        }
      });
  }else{
    return false;
  }
}
</script>
@endsection