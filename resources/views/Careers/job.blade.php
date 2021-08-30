@extends('Careers.main')
@section('body')
<div class="container" style="margin-top: 2%">

    <div class="row">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h4>
                    <strong>{{$job->name}}</strong>
                    <a href="{{url('careers')}}" class="btn btn-xs btn-success pull-right">View all Jobs</a>
                </h4>
            </div>
            <div class="panel-body">
                <div>
                    <p><strong>Job Details :</strong></p>
                    {!! $job->description !!}
                </div>
                <div>
                    <p><strong>Job Skills :</strong></p>
                    {!! $job->skills !!}
                </div>
                <div>
                    <p><strong>Who Can Apply :</strong></p>
                    {!! $job->who_apply !!}
                </div>
                <div>
                    <p><strong>Facilities :</strong></p>
                    {!! $job->offer !!}
                </div>
                <div>
                    <p><strong>Salary :</strong> {{$job->salary}}</p>
                    <p><strong>Experience :</strong> {{$job->experience}}</p>
                </div>
                <div>
                    <p>
                        @if(session()->get('jobseeker_login')) 
                        <a href="{{url('careers/job')}}/{{$job->id}}/apply" class="btn btn-success btn-md">Apply now</a>
                        @else
                        <a href="{{url('join-us')}}" class="btn btn-success btn-md">Join Us to apply</a>
                        @endif
                    </p>
                </div>
            </div>
            <div class="panel-footer text-right">
                Valid upto - {{$job->closing_date}}
            </div>
        </div>
    </div>
</div>
@endsection