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
                    <span class="caption-subject bold uppercase" id="hidden_table_title">Working Day Adjustment</span>
                </div>

                <div class="col-md-6"><p id="confirm_msg"></p></div>
                <div class="actions">
                  <a class="btn btn-md btn-primary btn-circle" onclick="Apply()"><i class="fa fa-tasks"></i>&nbsp;Apply for an Adjustment</a>
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
              <form class="form-horizontal" method="post" enctype="multipart/form-data" action="#" name="basic_validate" id="data_form">
              {{ csrf_field() }}
               <table class="table table-striped table-bordered table-hover" id="sample_3">
                      <thead>
                        <tr>
                          <th>Actions</th>
                          <th>SL</th>
                          <th>Employee</th>
                          <th>Adjustment Dates</th>
                          <th>Date Against</th>
                          <th>Submitted at</th>
                          <th>Status</th>
                        </tr>
                      </thead>
                      @if(isset($adjustments) && count($adjustments)>0)
                        @foreach ($adjustments as $key => $adjust)
                         <tr class="gradeX" id="tr-{{$adjust->id }}">
                            <td class="text-center">
                              @if($adjust->status=="0")
                                @if($adjust->emp_id==$id->suser_empid)
                                  <a class="btn btn-xs btn-danger" onclick="Delete('{{$adjust->id}}')"><i class="fa fa-trash"></i></a>
                                @else
                                  <a class="btn btn-xs btn-primary" onclick="Toggole('{{$adjust->id}}',1)" id="approve-button-{{$adjust->id }}"><i class="fa fa-check"></i></a>
                                  <a class="btn btn-xs btn-danger" onclick="Toggole('{{$adjust->id}}',2)" id="deny-button-{{$adjust->id }}"><i class="fa fa-ban"></i></a>
                                @endif
                              @endif
                            </td>
                            <td>{{$key+1}}</td>
                            <td>{{$adjust->employee->emp_name}} ({{$adjust->employee->emp_empid}})</td>
                            <td>{{date('l, F j, Y',strtotime($adjust->to))}}</td>
                            <td>{{date('l, F j, Y',strtotime($adjust->for))}}</td>
                            <td>{{date('l, F j, Y',strtotime($adjust->created_at))}}</td>
                            <td>
                            @if($adjust->status=="1")
                              <a class="btn btn-xs btn-success">Approved</a>
                            @elseif($adjust->status=="2")
                              <a class="btn btn-xs btn-danger">Denied</a>
                            @else
                              <a class="btn btn-xs btn-warning" id="button-{{$adjust->id}}">Pending</a>
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
<script  type="text/javascript">
function Apply() {
  $.alert({
      title: '<i class="fa fa-tasks"></i>&nbsp;Apply for an Adjustment',
      content: 'url:{{url("working-day-adjustment/create")}}',
      animation: 'scale',
      closeAnimation: 'bottom',
      columnClass:"col-md-6 col-md-offset-3",
      buttons: {
          close: {
              text: 'Close',
              btnClass: 'btn-blue',
              action: function(){
                  // do nothing
              }
          }
      }
  });
}

function Delete(id) {
    $.confirm({
        title: 'Confirm!',
        content: '<hr><strong class="text-danger">Are you sure to delete ?</strong><hr>',
        buttons: {
            confirm: function () {
                $.ajax({
                  headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' },
                  url: "{{url('working-day-adjustment')}}/"+id,
                  type: 'DELETE',
                  dataType: 'json',
                  data: {},
                  success:function(response) {
                    if(response.success){
                      $('#tr-'+id).fadeOut();
                    }else{
                      $.alert({
                        title:"Whoops!",
                        content:"<hr><strong class='text-danger'>"+response.msg+"</strong><hr>",
                        type:"red"
                      });
                    }
                  }
                });
            },
            cancel: function () {

            }
        }
    });   
  }

  function Toggole(id,status) {
    var title="Approve!";
    var content='<hr><strong class="text-primary">Are you sure to approve ?</strong><hr>';
    if(status=="2"){
      title='Deny!';
      content='<hr><strong class="text-danger">Are you sure to deny ?</strong><hr>';
    }
    $.confirm({
        title: title,
        content: content,
        buttons: {
            confirm: function () {
                $.ajax({
                  headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' },
                  url: "{{url('working-day-adjustment')}}/"+id,
                  type: 'PUT',
                  dataType: 'json',
                  data: {status:status},
                  success:function(response) {
                    if(response.success){
                      var button=$('#button-'+id);
                      button.html(response.text);
                      button.removeClass('btn-warning');
                      button.addClass(response.class);
                      $('#approve-button-'+id).hide();
                      $('#deny-button-'+id).hide();
                    }else{
                      $.alert({
                        title:"Whoops!",
                        content:"<hr><strong class='text-danger'>"+response.msg+"</strong><hr>",
                        type:"red"
                      });
                    }
                  }
                });
            },
            cancel: function () {

            }
        }
    });   
  }
</script>
@endsection