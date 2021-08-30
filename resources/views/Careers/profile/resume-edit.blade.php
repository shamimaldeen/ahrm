@extends('Careers.main')
@section('body')
<script src="{{URL::to('/')}}/public/assets/global/plugins/jquery.min.js"></script>
<script src="https://cdn.ckeditor.com/4.10.0/standard/ckeditor.js"></script>
<div class="container" style="margin-top: 2%">
    <div class="panel panel-default">
		<div class="panel-body">
			<form action="{{route('join-us.update',$jobseeker->id)}}" method="post" enctype="multipart/form-data">
		    {{method_field('PUT')}}
		    {{csrf_field()}}

			  <div class="form-group">
			    <label for="name">Full Name</label>
			    <input type="text" class="form-control" name="name" value="{{$jobseeker->name}}" id="name">
			  </div>
			  <div class="form-group">
			    <label for="phone">Phone Number</label>
			    <input type="text" class="form-control" name="phone" value="{{$jobseeker->phone}}" id="phone">
			  </div>
			  <div class="form-group">
			    <label for="gender">Gender</label>
			    <br>
			    <input type="radio" name="sex" value="1" @if($jobseeker->sex=="1") checked @endif>&nbsp;Male
			    &nbsp;<input type="radio" name="sex" value="0" @if($jobseeker->sex=="0") checked @endif>&nbsp;Female
			  </div>
			  <div class="form-group">
			    <label for="email">E-Mail</label>
			    <input type="email" class="form-control" name="email" value="{{$jobseeker->email}}" id="email">
			  </div>
			  <div class="form-group">
			    <label for="skills">Skills</label>
			    <textarea class="form-control" name="skills" id="skills">{{$jobseeker->skills}}</textarea>
			  </div>
			  <div class="form-group">
			    <label for="Study">Study</label>
			    <textarea class="form-control" name="study" id="study">{{$jobseeker->study}}</textarea>
			  </div>
			  <div class="form-group">
			    <label for="experience">Experience</label>
			    <textarea class="form-control" name="experience" id="experience">{{$jobseeker->experience}}</textarea>
			  </div>
			  <div class="form-group">
			    <label for="salary">Expected Salary</label>
			    <input type="number" class="form-control" name="salary" value="{{$jobseeker->salary}}" id="salary">
			  </div>
			  <div class="form-group">
			    <label for="resume">Attach Resume (PDF,DOCX)</label>
			    <input type="file" class="form-control" name="file" id="file">
			    @if($jobseeker->resume!="")
			    <br>
			    Curent Resume - <a href="{{url('/public/jobseeker/resume')}}/{{$jobseeker->resume}}" target="_blank">{{$jobseeker->resume}}</a>
			    @endif
			  </div>
			  <button type="submit" class="btn btn-success">Update Resume</button>
			</form>
		</div>
	</div>
</div>
<script src="{{URL::to('/')}}/public/js/bootstrap-datepicker.js"></script> 
<script type="text/javascript">
CKEDITOR.replace( 'skills' );
CKEDITOR.replace( 'study' );
CKEDITOR.replace( 'experience' );
</script>
@endsection