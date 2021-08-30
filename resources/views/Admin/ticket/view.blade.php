@extends('Admin.index')
@section('body')
@php
use App\Http\Controllers\BackEndCon\Controller;
use App\Http\Controllers\BackEndCon\ticketController;
@endphp

<script src="{{URL::to('/')}}/public/assets/global/plugins/jquery.min.js"></script>

<div class="row">

    <div class="col-md-12">
       @include('error.msg')
       
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class="icon-settings font-dark"></i>
                    <span class="caption-subject bold uppercase" id="hidden_table_title">All Tickets</span>
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
                                  <a href="{{URL::to('add-new-ticket')}}">
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
              <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{URL::to('')}}" name="basic_validate" id="data_form">
              {{ csrf_field() }}
               <table class="table table-striped table-bordered table-hover" id="sample_3">

                        <thead>
                        <tr>
                          <th>
                          </th>
                          <th>SL</th>
                          <th>Ticket Code</th>
                          <th>Ticket Topic</th>
                          <th>Ticket Description</th>
                          <th>Ticket Submitted By</th>
                          <th>Ticket Submitted At</th>
                          <th>Total Solution</th>
                          <th>Ticket Status</th>
                        </tr>
                      </thead>
                      @if(isset($ticket) && count($ticket)>0)
                      @php
                      $c=0;
                      @endphp
                        @foreach ($ticket as $tc)
                        @php
                        $c++;
                        @endphp
                         <tr class="gradeX" id="tr-{{$tc->ticket_id }}">
                            <td>
                              @if($id->suser_empid==$tc->ticket_submitted_by)
                                <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                    <input type="checkbox" class="checkboxes" value="{{$tc->ticket_id }}" name="ticket_id[]" />
                                    <span></span>
                                </label>
                              @endif
                            </td>
                            <td>{{$c}}</td>
                            <td>{{$tc->ticket_code}}</td>
                            <td><a href="{{URL::to('add-new-ticket')}}/{{$tc->ticket_id }}/solution">{{$tc->ticket_topic}}</a></td>
                            <td>{{$tc->ticket_desc}}</td>
                            <td>@php echo Controller::getSeniorName($tc->ticket_submitted_by); @endphp</td>
                            <td>{{$tc->ticket_submitted_at}}</td>
                            <td>@php echo ticketController::numberOfSolution($tc->ticket_id); @endphp</td>
                            <td>
                            @if($tc->ticket_status=="1")
                              <a class="btn btn-xs btn-success">Active</a>
                            @elseif($tc->ticket_status=="0")
                              <a class="btn btn-xs btn-danger">Inactive</a>
                            @endif
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
<script  type="text/javascript">
  function Edit() {
    var data=$('#data_form').serializeArray();
    $.ajax({
      headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
      url: "{{URL::to('ticket-edit')}}",
      type: 'POST',
      data: data,
      success:function(data) {
        if(data=="null"){
          $('#confirm_msg').html('<b class="alert alert-danger" style="padding:0px 10px 0px 10px">Please Select A Ticket To Edit</b>');
        }else if(data=="max"){
          $('#confirm_msg').html('<b class="alert alert-danger" style="padding:0px 10px 0px 10px">Please Select Maximum Of One Ticket To Edit</b>');
        }else if(data=="denied"){
          $('#confirm_msg').html('<b class="alert alert-danger" style="padding:0px 10px 0px 10px">Sorry! You cannot Edit this Ticket because it has not been Submitted by you.</b>');
        }else{
          window.open("{{URL::to('add-new-ticket')}}/"+data+"/edit","_parent"); 
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
        url: "{{URL::to('add-new-ticket-delete')}}",
        type: 'POST',
        data: data,
        success:function(data) {
          if(data=="1"){
            location.reload();
          }else if(data=="0"){
            $('#confirm_msg').html('<b class="alert alert-danger" style="padding:0px 10px 0px 10px">Something Went Wrong!!</b>');
          }else if(data=="null"){
            $('#confirm_msg').html('<b class="alert alert-danger" style="padding:0px 10px 0px 10px">Please Select One or More Department To Delete</b>');
          }
        }
      });
  }else{
    return false;
  }
}
</script>
@endsection