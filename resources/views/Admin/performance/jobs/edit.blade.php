@extends('Admin.index')
@section('body')
@php use App\Http\Controllers\BackEndCon\JobController as JC; @endphp
<script src="{{url('/')}}/public/assets/global/plugins/jquery.min.js"></script>
<link rel="stylesheet" href="{{url('/')}}/public/css/datepicker.css" />
<div class="row">
        <div class="col-md-12">
           @include('error.msg')
          <!-- BEGIN EXAMPLE TABLE PORTLET-->
          <div class="portlet box grey-cascade">
            <div class="portlet-title">
              <div class="caption">
                <i class="fa fa-globe"></i>Edit job 
              </div>
              <div style="float:right">
                <a class="btn btn-xs btn-primary" href="{{url('jobs')}}/{{$Job->emp_id}}/view" style="margin-top:10px">View Jobs of {{$Job->employee->emp_name}} ({{$Job->employee->emp_empid}})</a>
                <a class="btn btn-xs btn-primary" href="{{url('jobs')}}/{{$Job->id}}" style="margin-top:10px">Go Back</a>
              </div>
            
            </div>
            <div class="portlet-body">
              <div class="table-toolbar">
                <div class="row">

              <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{route('jobs.update',$Job->id)}}" name="basic_validate" id="basic_validate" novalidate="novalidate">
              {{ method_field('PUT') }}
              {{ csrf_field() }}
                           
              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                  Choose Project: <span class="required">* </span>
                </label>

                <div class="col-md-8">
                  <select name="project_id" id="project_id" class="form-control chosen">
                    @if(isset($Project) && count($Project)>0)
                      @foreach ($Project as $gl)
                      <option value="{{$gl->id}}" @if($Job->project_id=="$gl->id") selected @endif>{{$gl->project_name}}</option>
                      @endforeach
                    @endif
                  </select>
                </div>

              </div>

              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                  Job Name: <span class="required">* </span>
                </label>

                <div class="col-md-8">
                  <input type="text" class="form-control" name="job_title" value="{{old('job_title',$Job->job_title)}}" required>
                </div>

              </div>

              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                  Job Weight: <span class="required">* </span>
                </label>

                <div class="col-md-8">
                  <input type="number" min="1" max="100" class="form-control" name="job_weight" value="{{old('job_weight',$Job->job_weight)}}" required>
                </div>

              </div>

              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                  Start Date: <span class="required">* </span>
                </label>

                <div class="col-md-8">
                  <input type="text" class="form-control" name="start_date" value="{{old('start_date',$Job->start_date)}}" id="start_date" data-date-format="yyyy-mm-dd" >
                </div>

              </div>

              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                  End Date: <span class="required">* </span>
                </label>

                <div class="col-md-8">
                  <input type="text" class="form-control" name="end_date" value="{{old('end_date',$Job->end_date)}}" id="end_date" data-date-format="yyyy-mm-dd" >
                </div>

              </div>

              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                  Progress: <span class="required">* </span>
                </label>

                <div class="col-md-7">
                  <input type="range" min="0" max="100" value="{{$Job->completion}}" class="form-control" id="myRange" onchange="progressvalue()">
                </div>
                <div class="col-md-1">
                  <input type="number" min="0" max="100" value="{{$Job->completion}}" class="form-control" id="completion" name="completion" readonly style="background: white">
                </div>

              </div>

              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                  Choose Skill: <span class="required">* </span>
                </label>

                <div class="col-md-8">
                  @if(isset($Skill[0]))
                  @foreach ($Skill as $sk)
                  <div class="col-md-4">
                    <input type="checkbox" name="skill[]" value="{{$sk->id}}" @if(JC::checkSkill($Job->id,$sk->id)=="1") checked @endif>&nbsp;{{$sk->name}}
                  </div>
                  @endforeach
                  @endif
                </div>

              </div>

              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                  Choose Reviewers: <span class="required">* </span>
                </label>

                <div class="col-md-8">
                  @if(isset($Reviewer[0]))
                  @foreach ($Reviewer as $rv)
                  <div class="col-md-4">
                    <input type="checkbox" name="reviewers[]" value="{{$rv->suser_empid}}" @if(JC::checkReviewer($Job->id,$rv->suser_empid)=="1") checked @endif>&nbsp;{{$rv->employee->emp_name}} ({{$rv->employee->emp_empid}})
                  </div>
                  @endforeach
                  @endif
                </div>

              </div>

              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                  Status: <span class="required">* </span>
                </label>

                <div class="col-md-8">
                  <select class="form-control chosen" name="status">
                    <option @if($Job->status=="On progress") selected @endif>On progress</option>
                    <option @if($Job->status=="Cancelled") selected @endif>Cancelled</option>
                    <option @if($Job->status=="Behind target") selected @endif>Behind target</option>
                    <option @if($Job->status=="No progress") selected @endif>No progress</option> 
                    <option @if($Job->status=="On target") selected @endif>On target</option>
                    <option @if($Job->status=="Ahead target") selected @endif>Ahead target</option>
                    <option @if($Job->status=="Complete") selected @endif>Complete</option>
                  </select>
                </div>

              </div>

               <div class="form-group">

                <label class="col-md-2 control-label">
              
                </label>

                <div class="col-md-3">
                  <input type="submit" value="Save" class="btn btn-success">
                </div>                

              </div>
         

                    </div>          




                      </div>



                    </div>





                  </div>



                </div>



              </div>



            </div>



          </form>

                  </div>
                  
                </div>
              </div>
              
            </div>
          </div>
          <!-- END EXAMPLE TABLE PORTLET-->
        </div>
      </div>
        </div>
      </div>
<script src="{{url('/')}}/public/js/bootstrap-datepicker.js"></script> 
<script type="text/javascript">
$('#start_date').datepicker();
$('#end_date').datepicker();
function progressvalue() {
  var myRange=$('#myRange').val();
  $('#completion').val(myRange);
}
</script>
@endsection