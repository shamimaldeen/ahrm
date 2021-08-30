@extends('Careers.main')
@section('body')
<div class="container">
    <div class="row text-center">
        <h3 class="alert alert-success"><strong>Applied Jobs</strong></h3>    
    </div>

    <div class="row">
        
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                      <th>SL</th>
                      <th>Job</th>
                      <th>Salary</th>
                      <th>Experience</th>
                      <th>Status</th>
                      <th>Feedback</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($applications[0]))
                        @foreach ($applications as $key => $application)
                        <tr>
                          <td>{{$key+1}}</td>
                          <td>{{$application->job->name}}</td>
                          <td>{{$application->job->salary}}</td>
                          <td>{{$application->job->experience}}</td>
                          <td>
                              @if($application->status=="0")
                              <a class="btn btn-xs btn-warning">Pending</a>
                              @elseif($application->status=="1")
                              <a class="btn btn-xs btn-success">Approved</a>
                              @elseif($application->status=="2")
                              <a class="btn btn-xs btn-danger">rejected</a>
                              @endif
                          </td>
                          <td>{{$application->feedback}}</td>
                        </tr>
                        @endforeach
                    @else
                    <tr>
                        <td colspan="6" class="text-center">
                            <strong class="text-danger">Sorry! You did not applied for any job yet. <a href="{{url('careers')}}" class="text-success">Apply Now</a></strong>
                        </td>      
                    </tr>
                    @endif
                </tbody>
            </table>
            
    </div>
</div>
@endsection