@extends('Admin.index')
@section('body')
@php
use App\Http\Controllers\BackEndCon\Controller as C;
@endphp
<script src="{{url('/')}}/public/assets/global/plugins/jquery.min.js"></script>
<div class="row">
  @include('error.msg')
  <div class="col-md-12">

        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class="icon-settings font-dark"></i>
                    <span class="caption-subject bold uppercase" id="hidden_table_title">Jobs View</span>
                </div>
                <div style="float:right">
                  <a class="btn btn-xs btn-primary" href="{{url('jobs')}}/{{$Job->emp_id}}/view" style="margin-top:10px">View Jobs of {{$Job->employee->emp_name}} ({{$Job->employee->emp_empid}})</a>
                  <a class="btn btn-xs btn-primary" href="{{url('jobs')}}/{{$Job->id}}/job-report" target="_blank" style="margin-top:10px">View Report</a>
                </div>
            </div>
            
            <div class="portlet-body">
              <div class="col-md-8">
                <div class="panel panel-default">
                  <div class="panel-heading">
                    Job
                  </div>
                  <div class="panel-body">
                    <h4 class="text-center"><strong>{{$Job->project->project_name}}</strong></h4>
                    <h6 class="text-center"><strong>{{$Job->job_title}}</strong></h6>
                    <h6 class="text-center"><a class="btn btn-xs btn-success">{{$Job->status}}</a></h6>
                    <h6 class="text-center">
                      <a class="btn btn-xs btn-primary" href="{{url('jobs')}}/{{$Job->id}}/edit">
                        <i class="fa fa-edit"></i>&nbsp;Edit Job
                      </a>
                    </h6>
                    <div class="progress">
                      <div class="progress-bar" role="progressbar" aria-valuenow="{{$Job->completion}}"
                      aria-valuemin="0" aria-valuemax="100" style="width:{{$Job->completion}}%">
                        <span>{{$Job->completion}}% Complete</span>
                      </div>
                    </div>
                    <table class="table table-bordered table-striped table-hover">
                      <thead>
                        <tr>
                          <th colspan="2">Job Details</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>Job weight</td>
                          <td>{{$Job->job_weight}} %</td>
                        </tr>
                        <tr>
                          <td>Job amount(Based on project amount)</td>
                          <td>{{C::decimal($Job->project->project_amount*($Job->job_weight/100))}} BDT</td>
                        </tr>
                        <tr>
                          <td>Job incentive(Based on project incentive amount)</td>
                          <td>{{C::decimal($Job->project->incentive_amount*($Job->job_weight/100))}} BDT</td>
                        </tr>
                        <tr>
                          <td>Start date</td>
                          <td>{{$Job->start_date}}</td>
                        </tr>
                        <tr>
                          <td>End date</td>
                          <td>{{$Job->end_date}}</td>
                        </tr>
                      </tbody>
                    </table>

                    <table class="table table-bordered table-striped table-hover">
                      <thead>
                        <tr>
                          <th colspan="8">
                            Appraisal
                            <a class="btn btn-xs btn-primary pull-right" href="{{url('jobs')}}/{{$Job->id}}/appraisal">Update Appraisal</a>
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>SL</td>
                          <td>Skill</td>
                          <td>Skill Weight (%)</td>
                          <td>Target (Unit)</td>
                          <td>Achieved (Unit)</td>
                          <td>Performance (%)</td>
                          @if($incentive["incentive"])
                          <td>Incentive Target Amount</td>
                          <td>Incentive Achieve Amount</td>
                          @endif
                        </tr>
                        @if(isset($Job->appraisal[0]))
                          @php 
                          $c=0; 
                          $total_performance=0; 
                          $total_weight=0; 
                          $target_incentive=0; 
                          $achieve_incentive=0; 
                          @endphp
                        @foreach ($Job->appraisal as $app)
                          @php 
                          $c++;
                          if($app->achieve=="" || $app->achieve=="0"){
                            $performance=0;
                          } else {
                            $performance=C::decimal(($app->achieve*100)/$app->target);
                          }
                          $total_performance+=$performance;
                          $total_weight+=$app->skill_weight; 
                          $target_incentive+=$app->incentive_target_amount; 
                          $achieve_incentive+=$app->incentive_achieve_amount;
                          @endphp
                          <tr>
                            <td>{{$c}}</td>
                            <td>{{$app->skill->name}}</td>
                            <td class="text-right">{{$app->skill_weight}} %</td>
                            <td class="text-right">{{$app->target}}</td>
                            <td class="text-right">{{$app->achieve}}</td>
                            <td class="text-right">{{$performance}} %</td>
                            @if($incentive["incentive"])
                            <td class="text-right">{{$app->incentive_target_amount}} BDT</td>
                            <td class="text-right">{{$app->incentive_achieve_amount}} BDT</td>
                            @endif
                          </tr>
                        @endforeach
                        @endif
                          <tr>
                            <td colspan="2" class="text-right">Total</td>
                            <td class="text-right">{{$total_weight}} %</td>
                            <td colspan="2"></td>
                            <td class="text-right">{{C::decimal($total_performance/$c)}} %</td>
                            @if($incentive["incentive"])
                            <td class="text-right">{{$target_incentive}} BDT</td>
                            <td class="text-right">{{$achieve_incentive}} BDT</td>
                            @endif
                          </tr>
                      </tbody>
                    </table>

                    <table class="table table-bordered table-striped table-hover">
                      <thead>
                        <tr>
                          <th colspan="2">Reviewers</th>
                        </tr>
                      </thead>
                      <tbody>
                        @if(isset($Job->reviewer[0]))
                        @php $c=0; @endphp
                        @foreach ($Job->reviewer as $reviewer)
                        @php $c++; @endphp
                          <tr>
                            <td>{{$c}}</td>
                            <td>{{$reviewer->employee->emp_name}} ({{$reviewer->employee->emp_empid}})</td>
                          </tr>
                        @endforeach
                        @endif
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="panel panel-default">
                  <div class="panel-heading">
                    Notes
                  </div>
                  <div class="panel-body" style="min-height: 400px;max-height: 500px;overflow: auto;" id="note_view">
                  
                  </div>
                  <div class="panel-footer">
                    <div class="form-inline">
                      <form action="{{url('jobs')}}/{{$Job->id}}/sendNote" method="post" id="message_form">
                        {{csrf_field()}}
                        <textarea class="form-control" name="message" rows="2" style="resize: none; width: 80%" placeholder="Type your message" id="message"></textarea>
                        <button type="button" data-toggle="tooltip" data-title="Send" class="btn btn-default" style="height: 52px; font-size: 20px;" onclick="sendNote()"><i class="fa fa-paper-plane text-primary"></i></button>
                      </form>
                    </div> 
                    <h6>Notes are not editable. Please check and confirm before sending</h6>
                  </div>
                </div>
              </div>
            </div>
        </div>

  </div>
</div>
<script type="text/javascript">
  getNote();
  setInterval(function(){
    getNote();
  }, 5000);

  function getNote() {
    $.ajax({
      url: "{{url('jobs')}}/{{$Job->id}}/getNote",
      type: 'GET',
      data: {},
      success:function(data) {
          $('#note_view').html(data);
          $("#note_view").animate({ scrollTop: $('#note_view').prop("scrollHeight")}, 300);
        }
    })
    .fail(function() {
      $('#note_view').html('');
    });
  }

  function sendNote() {
    var message=$('#message').val();
    if(message!=""){
      $.ajax({
        headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' },
        url: $('#message_form').attr('action'),
        type: $('#message_form').attr('method'),
        data: $('#message_form').serializeArray(),
        success:function(response) {
          if(response.success){
            $('#message').val('');
            $("#note_view").append('<div class="panel panel-default"><div class="panel-heading"><small><strong>'+response.emp_name+' ( '+response.emp_empid+' )</strong></small><br><span style="font-size: 10px">'+response.date+'</span></div><div class="panel-body">'+response.message+'</div></div>');
            $("#note_view").animate({ scrollTop: $('#note_view').prop("scrollHeight")}, 300);
          }
        }
      });
    }
  }
</script>
@endsection