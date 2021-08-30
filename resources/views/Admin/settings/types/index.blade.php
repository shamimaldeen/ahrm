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
                    <span class="caption-subject bold uppercase" id="hidden_table_title">Employee Types</span>
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
                              <a href="{{url('employee-types/create')}}">
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
              <form class="form-horizontal" method="post" enctype="multipart/form-data" action="" name="basic_validate" id="data_form">
              {{ csrf_field() }}
               <table class="table table-striped table-bordered table-hover" id="sample_3">

                        <thead>
                        <tr>
                          <th>SL</th>
                          <th>Name</th>
                          <th>Description</th>
                          <th>Night Shift Allowance</th>
                          
                          <th>Status</th>
                          <th>Options</th>
                        </tr>
                      </thead>
                      @if(isset($types) && count($types)>0)
                        @foreach ($types as $key => $type)
                         <tr class="gradeX" id="tr-{{$type->id }}">
                            <td>{{$key+1}}</td>
                            <td>{{$type->name}}</td>
                            <td><div>{!! $type->desc !!}</div></td>
                            <td id="nsa-{{$type->id}}" class="text-center">
                              @if(isset($allowance[$type->id]))
                              <strong>{{$allowance[$type->id]['allowance']}}</strong> BDT
                              <br>
                              Execution date from : <strong>{{$allowance[$type->id]['execution_date']}}</strong>
                              @endif
                            </td>
                            
                            @if($type->status=="1")
                              <a class="btn btn-xs btn-success">Active</a>
                            @elseif($type->status=="0")
                              <a class="btn btn-xs btn-danger">Inactive</a>
                            @endif
                            </td>
                            <td>
                              <a class="btn btn-xs btn-success" href="{{url('employee-types')}}/{{$type->id }}/edit"><i class="fa fa-edit"></i></a>
                              <a class="btn btn-info btn-xs" onclick="UpdateNSA('{{$type->id}}')">Update Night Shift Allowance</a>
                              <a class="btn btn-primary btn-xs" onclick="NSAHistory('{{$type->id}}')">Night Shift History</a>
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
  function UpdateNSA(emp_type) {
  $.confirm({
    title: 'Prompt!',
    content: '' +
    '<form action="" class="formName">' +
    '<div class="form-group">' +
    '<label>Allowance Amount</label>' +
    '<input type="text" class="allowance form-control" required />' +
    '</div>' +
    '</form>',
    buttons: {
        formSubmit: {
            text: 'Update',
            btnClass: 'btn-blue',
            action: function () {
                var allowance = this.$content.find('.allowance').val();
                var execution_date = this.$content.find('.execution_date').val();
                if(!allowance || allowance<0){
                    $.alert({
                      title:"Whoops!",
                      content:"<hr><div class='alert alert-danger'>Please enter a valid allowance amount</div><hr>",
                      type:"red"
                    });
                    return false;
                }
                $.ajax({
                  headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
                  url: "{{url('update-night-shift-allowance')}}",
                  type: 'POST',
                  dataType: 'json',
                  data: {emp_type:emp_type,allowance:allowance,execution_date:execution_date},
                })
                .done(function(response) {
                  if(response.success){
                    $.alert({
                      title:"Updated!",
                      content:"<hr><div class='alert alert-success'>"+response.msg+"</div><hr>",
                      type:"green"
                    });
                    $('#nsa-'+emp_type).html("<strong>"+response.allowance+"</strong> BDT <br> Execution date from : <strong>"+response.execution_date+"</strong>");
                  }else{
                    $.alert({
                      title:"Whoops!",
                      content:"<hr><div class='alert alert-danger'>"+response.msg+"</div><hr>",
                      type:"red"
                    });
                  }
                })
                .fail(function() {
                  $.alert({
                    title:"Whoops!",
                    content:"<hr><div class='alert alert-danger'>Something Went Wrong!</div><hr>",
                    type:"red"
                  });
                });
            }
        },
        cancel: {
            text: 'Close',
            btnClass: 'btn-default',
            action: function () {

            }
        },
    }
});
}

function NSAHistory(emp_type) {
  $.alert({
      title: 'Night Shift Allowance History',
      content: "url:{{url('night-shift-allowance-history')}}/"+emp_type,
      animation: 'scale',
      closeAnimation: 'bottom',
      columnClass:"col-md-6 col-md-offset-3",
      buttons: {
          okay: {
              text: 'okay',
              btnClass: 'btn-blue',
              action: function(){
                  // do nothing
              }
          }
      }
  });
}
</script>
@endsection