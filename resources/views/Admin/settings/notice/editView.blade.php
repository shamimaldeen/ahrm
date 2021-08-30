@extends('Admin.index')
@section('body')
<script src="{{URL::to('/')}}/public/assets/global/plugins/jquery.min.js"></script>
<link rel="stylesheet" href="{{URL::to('/')}}/public/css/datepicker.css" />
<div class="row">
        <div class="col-md-12">
           @include('error.msg')
          <!-- BEGIN EXAMPLE TABLE PORTLET-->
          <div class="portlet box grey-cascade">
            <div class="portlet-title">
              <div class="caption">
                <i class="fa fa-globe"></i>Edit Notice  Information 
              </div>
              <div style="float:right">
                <a class="btn btn-xs btn-primary" href="{{URL::to('notice-board-view')}}" style="margin-top:10px">Go Back</a>
              </div>
            
            </div>
            <div class="portlet-body">
              <div class="table-toolbar">
                <div class="row">

              <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{URL::to('notice-board')}}/{{$notice_id}}/update" name="basic_validate" id="basic_validate" novalidate="novalidate">
              {{ csrf_field() }}
              
              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                  Title: <span class="required">* </span>
                </label>

                <div class="col-md-8">
                  <input type="text" class="form-control" name="notice_title" value="{{$notice->notice_title}}" required >
                </div>

              </div>

              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                  Notice : <span class="required">* </span>
                </label>

                <div class="col-md-8">
                  <textarea class="form-control" name="notice_desc" required style="height: 200px;resize: none">{{$notice->notice_desc}}</textarea>
                </div>

              </div>

              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                  Publish From : <span class="required">* </span>
                </label>

                <div class="col-md-3">
                  <input type="text" class="form-control" name="notice_publish_from" id="notice_publish_from" data-date-format="yyyy-mm-dd" value="{{$notice->notice_publish_from}}" required >
                </div>

                <label class="col-md-2 control-label">
                  Publish To : <span class="required">* </span>
                </label>

                <div class="col-md-3">
                  <input type="text" class="form-control" name="notice_publish_to" id="notice_publish_to" data-date-format="yyyy-mm-dd" value="{{$notice->notice_publish_to}}" required >
                </div>

              </div>

              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                  Status : <span class="required">* </span>
                </label>

                <div class="col-md-8">
                  <span><input type="radio" name="notice_status" value='1' @if($notice->notice_status=="1") checked="checked" @endif />Active</span>&nbsp;&nbsp;&nbsp;&nbsp;
                  <span><input type="radio" name="notice_status" value='0' @if($notice->notice_status=="0") checked="checked" @endif />Inactive</span>
                </div>

              </div>

               <div class="form-group">

                <label class="col-md-2 control-label">
              
                </label>

                <div class="col-md-3">
                  <input type="submit" value="Update" class="btn btn-success">
                </div>                

              </div>
         

                    </div>          




                      </div>



                    </div>





                  </div>



                </div>



              </div>



            </div>



          </form>

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
<script src="{{URL::to('/')}}/public/js/bootstrap-datepicker.js"></script> 
<script type="text/javascript">
$('#notice_publish_from').datepicker();
$('#notice_publish_to').datepicker();
</script>
@endsection