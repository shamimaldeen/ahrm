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
                    <span class="caption-subject bold uppercase" id="hidden_table_title">Probation Period End List (This Year)</span>
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
              <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{URL::to('')}}" name="basic_validate" id="data_form">
              {{ csrf_field() }}
               <table class="table table-striped table-bordered table-hover" id="sample_3">

                      <thead>
                        <tr>
                          <th>SL</th>
                          <th>Employee</th>
                          <th>Joinning Date</th>
                          <th>Probation Period End Date</th>
                          <th>Actions</th>
                        </tr>
                      </thead>
                      @if(isset($probations[1]))
                        @foreach ($probations as $key => $probation)
                         <tr class="gradeX" id="tr-{{$probation[0]['emp_id']}}">
                            <td>{{$key}}</td>
                            <td>{{$probation[0]['emp_name']}} ({{$probation[0]['emp_empid']}})</td>
                            <td>{{date('F j, Y',strtotime($probation[0]['join_date']))}}</td>
                            <td>{{date('F j, Y',strtotime($probation[0]['confirm_date']))}}</td>
                            <td>
                              <a class="btn btn-info btn-xs" onclick="PerformanceEvaluation('{{$probation[0]['emp_id']}}')">Write Performance Evaluation</a>
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
 function PerformanceEvaluation(emp_id) {
  $.confirm({
      title: 'Write Performance Evaluation',
      columnClass:"col-md-6 col-md-offset-3",
      content: '<hr>' +
      '<form action="#" class="formName" onsubmit="return false">' +
      '<div class="form-group">' +
      '<label>Write Performance Evaluation Here..</label>' +
      '<textarea class="text form-control" rows="10" style="resize:none"></textarea>' +
      '</div>' +
      '</form><hr>',
      buttons: {
          formSubmit: {
              text: 'Send Notification',
              btnClass: 'btn-blue',
              action: function () {
                  var text = this.$content.find('.text').val();
                  if(!text){
                      $.alert({
                        title:"Whoops!",
                        content:"<hr><div class='alert alert-danger'>Please write Performance Evaluation</div><hr>",
                        type:"red"
                      });
                      return false;
                  }

                  $.ajax({
                    headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
                    url: "{{url('probation-period-notifications')}}",
                    type: 'POST',
                    dataType: 'json',
                    data: {emp_id:emp_id,text:text},
                  })
                  .done(function(response) {
                    if(response.success){
                      $.alert({
                        title:"Done!",
                        content:"<hr><div class='alert alert-success'>"+response.msg+"</div><hr>",
                        type:"green"
                      });
                      $('#tr-'+emp_id).fadeOut();
                    }else{
                      $.alert({
                        title:"Whoops!",
                        content:"<hr><div class='alert alert-danger'>"+response.msg+"</div><hr>",
                        type:"red"
                      });
                      return false;
                    }
                  })
                  .fail(function() {
                    $.alert({
                      title:"Whoops!",
                      content:"<hr><div class='alert alert-danger'>Something Went Wrong!</div><hr>",
                      type:"red"
                    });
                    return false;
                  });
              }
          },
          cancel: function () {
              //close
          },
      }
  });
 }
</script>
@endsection