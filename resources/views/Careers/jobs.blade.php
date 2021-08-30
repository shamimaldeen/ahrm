@extends('Careers.main')
@section('body')
<div class="container">
    <div class="row text-center">
        <h3 class="alert alert-success"><strong>Latest Job Offers</strong></h3>    
    </div>

    <div class="row">
        @if(isset($jobs[0]))
            @foreach ($jobs as $job)
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h4><strong>{{$job->name}}</strong></h4>
                </div>
                <div class="panel-body">
                    <div>
                        {!! $job->description !!}
                    </div>
                    <div>
                        <p>Salary : {{$job->salary}}</p>
                        <p>Experience : {{$job->experience}}</p>
                        <p>
                            <a href="{{url('careers/job')}}/{{$job->id}}" class="btn btn-primary btn-xs">View Details</a>
                            @if(session()->get('jobseeker_login')) 
                            <a href="{{url('careers/job')}}/{{$job->id}}/apply" class="btn btn-success btn-xs">Apply</a>
                            @else
                            <a href="{{url('join-us')}}" class="btn btn-success btn-xs">Join Us to apply</a>
                            @endif
                        </p>
                    </div>
                </div>
                <div class="panel-footer text-right">
                    Valid upto - {{$job->closing_date}}
                </div>
            </div>
            @endforeach
        @else
        <div class="panel panel-danger">
            <div class="panel-body text-center">
                <strong class="text-danger">Sorry! We do not have any job offer right now.</strong>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection