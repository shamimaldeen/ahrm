@extends('Careers.main')
@section('body')
<script src="{{URL::to('/')}}/public/assets/global/plugins/jquery.min.js"></script>
<script src="https://cdn.ckeditor.com/4.10.0/standard/ckeditor.js"></script>
<div class="container" style="margin-top: 2%">

    <div class="row">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h4>
                    <strong>{{$job->name}}</strong>
                    <a href="{{url('careers/job')}}/{{$job->id}}" class="btn btn-xs btn-default pull-right" style="margin:5px">Go Back</a>
                </h4>
            </div>
            <div class="panel-body">
                <div>
                    <form action="{{route('careers.store')}}" method="post">
                        {{csrf_field()}}
                      <div class="form-group">
                        <label for="intro"><strong>Introdue Yourself</strong></label>
                        <input type="hidden" name="job_id" value="{{$job->id}}">
                        <input type="hidden" name="jobseeker_id" value="{{session()->get('jobseeker_id')}}">
                        <textarea name="intro" rows="10" class="form-control"></textarea>
                      </div>
                      <button type="submit" class="btn btn-success">Apply for this job</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{URL::to('/')}}/public/js/bootstrap-datepicker.js"></script> 
<script type="text/javascript">
CKEDITOR.replace( 'intro' );
</script>
@endsection