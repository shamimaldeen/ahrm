@extends('Admin.index')
@section('body')
<script src="{{URL::to('/')}}/public/assets/global/plugins/jquery.min.js"></script>
<link rel="stylesheet" href="{{URL::to('/')}}/public/css/datepicker.css" />
<script src="https://cdn.ckeditor.com/4.10.0/standard/ckeditor.js"></script>
<div class="row">
  <div class="col-md-12">
    @include('error.msg')
    <div class="portlet box grey-cascade">
      
      <div class="portlet-title">
        <div class="caption">
          <i class="fa fa-globe"></i>Edit Job
        </div>
      </div>
      
      <div class="portlet-body">
        <div class="table-toolbar">
          <div class="row">

            <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{route('recruitment.update',$job->id)}}" name="basic_validate" id="basic_validate" novalidate="novalidate">
            {{ method_field('PUT') }}
            {{ csrf_field() }}

                <div class="form-group">
                  <label class="col-md-2 control-label">
                    Job Name: <span class="required">* </span>
                  </label>
                  <div class="col-md-8">
                    <input type="text" class="form-control" name="name" value="{{old('name',$job->name)}}">
                  </div>
                </div>  
                <div class="form-group">
                  <label class="col-md-2 control-label">
                    Skills: <span class="required">* </span>
                  </label>
                  <div class="col-md-8">
                    <textarea name="skills" class="form-control" rows="5">{{old('skills',$job->skills)}}</textarea>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-md-2 control-label">
                    Details: <span class="required">* </span>
                  </label>
                  <div class="col-md-8">
                    <textarea name="description" class="form-control" rows="5">{{old('description',$job->description)}}</textarea>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-md-2 control-label">
                    Who Can Apply: <span class="required">* </span>
                  </label>
                  <div class="col-md-8">
                    <textarea name="who_apply" class="form-control" rows="5">{{old('who_apply',$job->who_apply)}}</textarea>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-md-2 control-label">
                    Offers: <span class="required">* </span>
                  </label>
                  <div class="col-md-8">
                    <textarea name="offer" class="form-control" rows="5">{{old('offer',$job->offer)}}</textarea>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-md-2 control-label">
                    Salary: <span class="required">* </span>
                  </label>
                  <div class="col-md-8">
                    <input type="number" name="salary" class="form-control" value="{{old('salary',$job->salary)}}" />
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-md-2 control-label">
                    Experience: <span class="required">* </span>
                  </label>
                  <div class="col-md-8">
                    <input type="number" name="experience" class="form-control" value="{{old('experience',$job->experience)}}" />
                  </div>
                </div>  
                <div class="form-group">
                  <label class="col-md-2 control-label">
                    Closing Date: <span class="required">* </span>
                  </label>
                  <div class="col-md-8">
                    <input type="text" name="closing_date" id="closing_date" data-date-format="yyyy-mm-dd" readonly style="background: white" class="form-control" value="{{old('closing_date',$job->closing_date)}}" />
                  </div>
                </div>  
                <div class="form-group">
                  <label class="col-md-2 control-label">
                    Email Address: <span class="required">* </span>
                  </label>
                  <div class="col-md-8">
                    <input type="email" name="email" class="form-control" value="{{old('email',$job->email)}}" />
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-md-2 control-label">
                    Status: <span class="required">* </span>
                  </label>
                  <div class="col-md-8">
                    <input type="radio" name="inactive" value="0" @if($job->inactive=="0") checked @endif>&nbsp;Active
                    &nbsp;<input type="radio" name="inactive" value="1" @if($job->inactive=="1") checked @endif>&nbsp;Inactive
                  </div>
                </div>

                <div class="form-group">
                  <div class="col-md-8 col-md-offset-2">
                    <input type="submit" value="Update Job" class="btn btn-success">
                  </div>                
                </div>
       
            </form>

          </div>
        </div>
      </div>
        
    </div>
  </div>
</div>
<script src="{{URL::to('/')}}/public/js/bootstrap-datepicker.js"></script> 
<script type="text/javascript">
$('#closing_date').datepicker();
CKEDITOR.replace( 'skills' );
CKEDITOR.replace( 'description' );
CKEDITOR.replace( 'who_apply' );
CKEDITOR.replace( 'offer' );
</script>
@endsection