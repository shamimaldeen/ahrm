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
                    <span class="caption-subject bold uppercase" id="hidden_table_title">Device Information</span>
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
                                  <a href="{{URL::to('device-info-view/create')}}">
                                    <i class="fa fa-plus"></i> Add New</a>
                            </li>
                            <li>                                 
                                  <a onclick="return Edit();">
                                    <i class="fa fa-pencil"></i> Edit</a>
                            </li>
                            <!--<li>                                 
                                  <a  onclick="return Delete();">
                                    <i class="icon-trash"></i> Delete</a>
                            </li>-->
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
                          <th>Device Name</th>
                          <th>Device Type</th>
                        </tr>
                      </thead>
                      @if(isset($devices) && count($devices)>0)
                      @php
                      $c=0;
                      @endphp
                        @foreach ($devices as $key => $device)
                        @php
                        $c++;
                        @endphp
                         <tr class="gradeX" id="tr-{{$device->id}}">
                            <td class="text-center">
                                <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                    <input type="checkbox" class="checkboxes" value="{{$device->id}}" name="id[]" />
                                    <span></span>
                                </label>
                            </td>
                            <td>{{$device->name}}</td>
                            <td>
                              @if($device->type=="1")
                              <a class="btn btn-xs btn-success">Entry</a>
                              @elseif($device->type=="0")
                              <a class="btn btn-xs btn-danger">Exit</a>
                              @endif
                            </td>
                          </tr>
                        @endforeach
                      @endif

                      @if(isset($remotedevices) && count($remotedevices)>0)
                      @php
                      $c=0;
                      @endphp
                        @foreach ($remotedevices as $key => $device)
                        @php
                        $c++;
                        @endphp
                         <tr class="gradeX" id="tr-{{$device->id}}">
                            <td>
                            </td>
                            <td>{{$device->name}}</td>
                            <td>
                              @if($device->type=="1")
                              <a class="btn btn-xs btn-success">Remote Entry</a>
                              @elseif($device->type=="0")
                              <a class="btn btn-xs btn-danger">Remote Exit</a>
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
      url: "{{URL::to('device-info-edit')}}",
      type: 'POST',
      data: data,
      success:function(data) {
        if(data=="null"){
          $('#confirm_msg').html('<b class="alert alert-danger" style="padding:0px 10px 0px 10px">Please Select A Device To Edit</b>');
        }else if(data=="max"){
          $('#confirm_msg').html('<b class="alert alert-danger" style="padding:0px 10px 0px 10px">Please Select Maximum Of One Device To Edit</b>');
        }else{
          window.open("{{URL::to('device-info-view')}}/"+data+"/edit","_parent"); 
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
        url: "{{URL::to('device-info-delete')}}",
        type: 'POST',
        data: data,
        success:function(data) {
          if(data=="1"){
            location.reload();
          }else if(data=="0"){
            $('#confirm_msg').html('<b class="alert alert-danger" style="padding:0px 10px 0px 10px">Something Went Wrong!!</b>');
          }else if(data=="null"){
            $('#confirm_msg').html('<b class="alert alert-danger" style="padding:0px 10px 0px 10px">Please Select One or More Device To Delete</b>');
          }
        }
      });
  }else{
    return false;
  }
}
</script>
@endsection