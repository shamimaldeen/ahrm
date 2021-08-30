@extends('Admin.index')
@section('body')
<script src="{{url('/')}}/public/assets/global/plugins/jquery.min.js"></script>
<link rel="stylesheet" href="{{url('/')}}/public/css/datepicker.css" />
<style>
.chosen-container { width: 100% !important; }
</style>
<div class="row">
        <div class="col-md-12">
           @include('error.msg')
          <!-- BEGIN EXAMPLE TABLE PORTLET-->
          <div class="portlet box grey-cascade">
            <div class="portlet-title">
              <div class="caption">
                <i class="fa fa-globe"></i>New Publishment 
              </div>
              <div style="float:right">
                <a class="btn btn-xs btn-primary" href="{{url('notice-board-view')}}" style="margin-top:10px">View Notice Board </a>
              </div>
            
            </div>
            <div class="portlet-body">
              <div class="table-toolbar">
                <div class="row col-md-12">
                  <ul class="nav nav-tabs">
                      <li class="active"><a data-toggle="tab" href="#notice">Notice</a></li>
                      <li><a data-toggle="tab" href="#email">E-Mail</a></li>
                      <li><a data-toggle="tab" href="#sms">SMS</a></li>
                  </ul>
                  <div class="tab-content">
                    <div id="notice" class="tab-pane fade in active">
                      <div class="panel panel-success">
                        <div class="panel-body">
                          <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{url('notice-board-notice')}}" name="basic_validate" id="basic_validate" novalidate="novalidate">
                          {{ csrf_field() }}

                            <div class="form-group">
                              <label class="col-md-2 control-label">
                                Choose Department :
                              </label>
                              <div class="col-md-8">
                                <select name="department" id="department-1" class="form-control chosen" onchange="getEmployeeForNotice('1')" required>
                                  @if(isset($department[0]))
                                  @foreach ($department as $depart)
                                    <option value="{{$depart->depart_id}}">{{$depart->depart_name}}</option>
                                  @endforeach
                                  @endif
                                </select>                            
                              </div>
                            </div>

                            <div class="form-group">
                              <label class="col-md-2 control-label">
                                Choose Employee :
                              </label>
                              <div class="col-md-8">
                                <select name="employee[]" id="employee-1" class="form-control chosen" multiple required>
                                </select>                            
                              </div>
                            </div>

                            <div class="form-group">
                              <label class="col-md-2 control-label">
                                Title: <span class="required">* </span>
                              </label>
                              <div class="col-md-8">
                                <input type="text" class="form-control" name="notice_title" value="{{old('notice_title')}}" required >
                              </div>
                            </div>

                            <div class="form-group">
                              <label class="col-md-2 control-label">
                                Notice : <span class="required">* </span>
                              </label>
                              <div class="col-md-8">
                                <textarea class="form-control" name="notice_desc" required style="height: 200px;resize: none">{{old('notice_desc')}}</textarea>
                              </div>
                            </div>

                            <div class="form-group">
                              <label class="col-md-2 control-label">
                                Publish From : <span class="required">* </span>
                              </label>
                              <div class="col-md-3">
                                <input type="text" class="form-control" name="notice_publish_from" id="notice_publish_from" data-date-format="yyyy-mm-dd" value="{{date('Y-m-d')}}" required >
                              </div>

                              <label class="col-md-2 control-label">
                                Publish To : <span class="required">* </span>
                              </label>
                              <div class="col-md-3">
                                <input type="text" class="form-control" name="notice_publish_to" id="notice_publish_to" data-date-format="yyyy-mm-dd" value="{{date('Y-m-d')}}" required >
                              </div>
                            </div>

                            <div class="form-group">
                              <label class="col-md-2 control-label">
                              </label>
                              <div class="col-md-3">
                                <input type="submit" value="Publish Notice" class="btn btn-success">
                              </div>                
                            </div>

                          </form> 
                        </div>
                      </div>
                    </div>
                    <div id="email" class="tab-pane">
                      <div class="panel panel-success">
                        <div class="panel-body">
                          <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{url('notice-board-email')}}" name="basic_validate" id="basic_validate" novalidate="novalidate">
                          {{ csrf_field() }}

                            <div class="form-group">
                              <label class="col-md-2 control-label">
                                Choose Department :
                              </label>
                              <div class="col-md-8">
                                <select name="department" id="department-2" class="form-control chosen" onchange="getEmployeeForNotice('2')" required>
                                  @if(isset($department[0]))
                                  @foreach ($department as $depart)
                                    <option value="{{$depart->depart_id}}">{{$depart->depart_name}}</option>
                                  @endforeach
                                  @endif
                                </select>                            
                              </div>
                            </div>

                            <div class="form-group">
                              <label class="col-md-2 control-label">
                                Choose Employee :
                              </label>
                              <div class="col-md-8">
                                <select name="employee[]" id="employee-2" class="form-control chosen" multiple required>
                                </select>                            
                              </div>
                            </div>

                            <div class="form-group">
                              <label class="col-md-2 control-label">
                                Subject: <span class="required">* </span>
                              </label>
                              <div class="col-md-8">
                                <input type="text" class="form-control" name="subject" value="{{old('subject')}}" required >
                              </div>
                            </div>

                            <div class="form-group">
                              <label class="col-md-2 control-label">
                                Message : <span class="required">* </span>
                              </label>
                              <div class="col-md-8">
                                <textarea class="form-control" name="message" required style="height: 200px;resize: none">{{old('message')}}</textarea>
                              </div>
                            </div>

                            <div class="form-group">
                              <div class="col-md-3 col-md-offset-2">
                                <input type="submit" value="Send E-Mail" class="btn btn-success">
                              </div>                
                            </div>

                          </form> 
                        </div>
                      </div>
                    </div>
                    <div id="sms" class="tab-pane">
                      <div class="panel panel-success">
                        <div class="panel-body">
                          <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{url('notice-board-sms')}}" name="basic_validate" id="basic_validate" novalidate="novalidate">
                          {{ csrf_field() }}

                            <div class="form-group">
                              <label class="col-md-2 control-label">
                                Choose Department :
                              </label>
                              <div class="col-md-8">
                                <select name="department" id="department-3" class="form-control chosen" onchange="getEmployeeForNotice('3')" required>
                                  @if(isset($department[0]))
                                  @foreach ($department as $depart)
                                    <option value="{{$depart->depart_id}}">{{$depart->depart_name}}</option>
                                  @endforeach
                                  @endif
                                </select>                            
                              </div>
                            </div>

                            <div class="form-group">
                              <label class="col-md-2 control-label">
                                Choose Employee :
                              </label>
                              <div class="col-md-8">
                                <select name="employee[]" id="employee-3" class="form-control chosen" multiple required>
                                </select>                            
                              </div>
                            </div>

                            <div class="form-group">
                              <label class="col-md-2 control-label">
                                Message : <span class="required">* </span>
                              </label>
                              <div class="col-md-8">
                                <textarea class="form-control" name="message" required style="height: 200px;resize: none">{{old('message')}}</textarea>
                              </div>
                            </div>

                            <div class="form-group">
                              <div class="col-md-3 col-md-offset-2">
                                <input type="submit" value="Send SMS" class="btn btn-success">
                              </div>                
                            </div>

                          </form> 
                        </div>
                      </div>
                    </div>
                  </div>

                  </div>
                  
                </div>
              </div>
              
            </div>
          </div>
          <!-- END EXAMPLE TABLE PORTLET-->
        </div>
      </div>
        </div>
      </div>


<script src="{{url('/')}}/public/js/bootstrap-datepicker.js"></script> 
<script type="text/javascript">
getEmployeeForNotice('1');
getEmployeeForNotice('2');
getEmployeeForNotice('3');
$('#notice_publish_from').datepicker();
$('#notice_publish_to').datepicker();
$('.chosen-container chosen-container-single').attr('style','');
function getEmployeeForNotice(serial) {
  var department=$('#department-'+serial).val();
  $.ajax({
    url: "{{url('getEmployeeForNotice')}}/"+department,
    type: 'GET',
    data: {},
    success:function(data) {
      $('#employee-'+serial).html(data).trigger("chosen:updated")
    }
  });
  
}
</script>
@endsection