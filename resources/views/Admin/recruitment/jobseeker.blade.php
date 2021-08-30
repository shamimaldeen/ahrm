@extends('Admin.index')
@section('body')
<script src="{{URL::to('/')}}/public/assets/global/plugins/jquery.min.js"></script>
<div class="row">
  <div class="col-md-12">
    @include('error.msg')
    <div class="portlet box grey-cascade">
      
      <div class="portlet-title">
        <div class="caption">
          <i class="fa fa-globe"></i>{{$jobseeker->name}}
        </div>
      </div>
      
      <div class="portlet-body">
        <div class="table-toolbar">
          <div class="row">

            <form class="form-horizontal" method="post" enctype="multipart/form-data" action="#" name="basic_validate" id="basic_validate" novalidate="novalidate">
            {{ csrf_field() }}
              <div class="col-md-12">
                  <div>
                      <p><strong>Skills :</strong></p>
                      {!! $jobseeker->skills !!}
                  </div>
                  <div>
                      <p><strong>Education :</strong></p>
                      {!! $jobseeker->study !!}
                  </div>
                  <div>
                      <p><strong>Experience :</strong></p>
                      {!! $jobseeker->experience !!}
                  </div>
                  <div>
                      <p><strong>Expected Salary :</strong> {{$jobseeker->salary}}</p>
                      <p><strong>Email :</strong> {{$jobseeker->email}}</p>
                      <p><strong>Contact No :</strong> {{$jobseeker->phone}}</p>
                      <p><strong>Gender :</strong> {{(($jobseeker->sex=="1") ? 'Male' : 'Female')}}</p>
                  </div>
                  <div>
                      @if($jobseeker->resume!="")
                      <p>Resume Link - <a href="{{url('public/jobseeker/resume')}}/{{$jobseeker->resume }}" target="_blank">{{$jobseeker->resume }}</a></p>
                      @endif
                  </div>
                  <div>
                      <p>
                        <a class="btn btn-md btn-default" href="{{url('recruitment-applications')}}"><i class="fa fa-hand-o-left"></i>&nbsp;Go Back</a>
                      </p>
                  </div>

              </div>
            </form>

          </div>
        </div>
      </div>
        
    </div>
  </div>
</div>
@endsection