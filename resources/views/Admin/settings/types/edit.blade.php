@extends('Admin.index')
@section('body')

<script src="{{url('/')}}/public/assets/global/plugins/jquery.min.js"></script>
<script src="https://cdn.ckeditor.com/4.10.0/standard/ckeditor.js"></script>
<div class="row">
    <div class="col-md-12">
       @include('error.msg')
        <div class="portlet box grey-cascade">
            <div class="portlet-title">
              <div class="caption">
                <i class="fa fa-globe"></i>Edit Type 
              </div>
              <div style="float:right">
                <a class="btn btn-xs btn-primary" href="{{url('employee-types')}}" style="margin-top:10px">View Employee Types</a>
              </div>
            
            </div>
            <div class="portlet-body">
              <div class="table-toolbar">
                <div class="row">

                <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{route('employee-types.update',$type->id)}}" name="basic_validate" id="basic_validate" novalidate="novalidate">
                {{ method_field('PUT') }}
                {{ csrf_field() }}
                  
                  <div class="form-group">
                   <label  class="col-md-2 control-label">
                      Name : <span class="required">* </span>
                    </label>

                    <div class="col-md-8">
                      <input type="text" class="form-control span10" id="name" name="name" value="{{old('name',$type->name)}}" required>
                    </div>
                  </div>

                  <div class="form-group">
                   <label  class="col-md-2 control-label">
                      Description : <span class="required">* </span>
                    </label>

                    <div class="col-md-8">
                      <textarea name="desc" class="form-control">{{$type->desc}}</textarea>
                    </div>
                  </div>

                  <div class="form-group">
                   <label  class="col-md-2 control-label">
                      Status : <span class="required">* </span>
                    </label>

                    <div class="col-md-8">
                      <input type="radio" name="status" value="1" @if($type->status=="1") checked @endif>&nbsp;Active
                      &nbsp;<input type="radio" name="status" value="0" @if($type->status=="0") checked @endif>&nbsp;Inactive
                    </div>
                  </div>

                    <div class="form-group">
                        <label  class="col-md-2 control-label">
                        </label>
                        <div class="col-md-8">
                          <input type="submit" value="Update" class="btn btn-success">
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

<script type="text/javascript">
  CKEDITOR.replace( 'desc' );
</script>
@endsection