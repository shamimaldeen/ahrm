@extends('Admin.index')
@section('body')
<script src="{{URL::to('/')}}/public/assets/global/plugins/jquery.min.js"></script>
<div class="row">
  <div class="col-md-12">
    @include('error.msg')
    <div class="portlet box grey-cascade">
      
      <div class="portlet-title">
        <div class="caption">
          <i class="fa fa-globe"></i>Jobseekers
        </div>
      </div>
      
      <div class="portlet-body">
        <div class="table-toolbar">
          <div class="row">

            <form class="form-horizontal" method="post" enctype="multipart/form-data" action="#" name="basic_validate" id="basic_validate" novalidate="novalidate">
            {{ csrf_field() }}
              <div class="col-md-12">
                <table class="table table-bordered table-striped table-hover" id="sample_3">
                  <thead>
                    <tr>
                      <th>SL</th>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Phone</th>
                      <th>Gender</th>
                      <th>Expected Salary</th>
                    </tr>
                  </thead>
                  <tbody>
                    @if(isset($jobseekers[0]))
                      @foreach ($jobseekers as $key => $jobseeker)
                        <tr>
                          <td>{{$key+1}}</td>
                          <td><a href="{{url('jobseeker')}}/{{$jobseeker->id}}" target="_blank">{{$jobseeker->name}}
                            </a></td>
                          <td>{{$jobseeker->email}}</td>
                          <td>{{$jobseeker->phone}}</td>
                          <td>{{(($jobseeker->sex=="1") ? 'Male' : 'Female')}}</td>
                          <td>{{$jobseeker->salary}}</td>
                        </tr>
                      @endforeach
                    @endif
                  </tbody>
                </table>
              </div>
            </form>

          </div>
        </div>
      </div>
        
    </div>
  </div>
</div>

@endsection