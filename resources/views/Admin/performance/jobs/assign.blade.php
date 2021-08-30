@extends('Admin.index')
@section('body')
<script src="{{url('/')}}/public/assets/global/plugins/jquery.min.js"></script>
<link rel="stylesheet" href="{{url('/')}}/public/css/datepicker.css" />
<div class="row">
        <div class="col-md-12">
           @include('error.msg')
          <!-- BEGIN EXAMPLE TABLE PORTLET-->
          <div class="portlet box grey-cascade">
            <div class="portlet-title">
              <div class="caption">
                <i class="fa fa-globe"></i>Assign job for {{$Employee->emp_name}} ({{$Employee->emp_empid}}) 
              </div>
              <div style="float:right">
                  <a class="btn btn-xs btn-primary" href="{{url('jobs')}}/{{$Employee->emp_id}}/view" style="margin-top:10px">View Jobs of {{$Employee->emp_name}} ({{$Employee->emp_empid}})</a>
                </div>
            
            </div>
            <div class="portlet-body">
              <div class="table-toolbar">
                <div class="row">

              <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{route('jobs.store')}}" name="basic_validate" id="basic_validate" novalidate="novalidate">
              {{ csrf_field() }}
              <input type="hidden" name="emp_id" value="{{$emp_id}}">
                           
              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                  Choose Project: <span class="required">* </span>
                </label>

                <div class="col-md-8">
                  <select name="project_id" id="project_id" class="form-control chosen">
                    @if(isset($Project) && count($Project)>0)
                      @foreach ($Project as $gl)
                      <option value="{{$gl->id}}">{{$gl->project_name}}</option>
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
                  <input type="text" class="form-control" name="job_title" value="{{old('job_title')}}" required>
                </div>

              </div>

              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                  Job Weight: <span class="required">* </span>
                </label>

                <div class="col-md-8">
                  <input type="number" min="1" max="100" class="form-control" name="job_weight" value="{{old('job_weight')}}" required>
                </div>

              </div>

              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                  Start Date: <span class="required">* </span>
                </label>

                <div class="col-md-8">
                  <input type="text" class="form-control" name="start_date" value="{{old('start_date')}}" id="start_date" data-date-format="yyyy-mm-dd" >
                </div>

              </div>

              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                  End Date: <span class="required">* </span>
                </label>

                <div class="col-md-8">
                  <input type="text" class="form-control" name="end_date" value="{{old('end_date')}}" id="end_date" data-date-format="yyyy-mm-dd" >
                </div>

              </div>

              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                  Progress: <span class="required">* </span>
                </label>

                <div class="col-md-7">
                  <input type="range" min="0" max="100" value="25" class="form-control" id="myRange" onchange="progressvalue()">
                </div>
                <div class="col-md-1">
                  <input type="number" min="0" max="100" value="25" class="form-control" id="completion" name="completion" readonly style="background: white">
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
                    <input type="checkbox" name="skill[]" value="{{$sk->id}}">&nbsp;{{$sk->name}}
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
                    <input type="checkbox" name="reviewers[]" value="{{$rv->suser_empid}}">&nbsp;{{$rv->employee->emp_name}} ({{$rv->employee->emp_empid}})
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
                    <option>On progress</option>
                    <option>Cancelled</option>
                    <option>Behind target</option>
                    <option>No progress</option> 
                    <option>On target</option>
                    <option>Ahead target</option>
                    <option>Complete</option>
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