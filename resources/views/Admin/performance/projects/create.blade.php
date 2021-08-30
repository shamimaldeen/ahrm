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
                <i class="fa fa-globe"></i>Add Project Information 
              </div>
              <div style="float:right">
                <a class="btn btn-xs btn-primary" href="{{url('projects')}}" style="margin-top:10px">View Project Information</a>
              </div>
            
            </div>
            <div class="portlet-body">
              <div class="table-toolbar">
                <div class="row">

              <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{route('projects.store')}}" name="basic_validate" id="basic_validate" novalidate="novalidate">
              {{ csrf_field() }}

                           
              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                  Choose Goal: <span class="required">* </span>
                </label>

                <div class="col-md-8">
                  <select name="goal_id" id="goal_id" class="form-control chosen">
                    @if(isset($Goal) && count($Goal)>0)
                      @foreach ($Goal as $gl)
                      <option value="{{$gl->id}}">{{$gl->title}}</option>
                      @endforeach
                    @endif
                  </select>
                </div>

              </div>

              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                  Project Name: <span class="required">* </span>
                </label>

                <div class="col-md-8">
                  <input type="text" class="form-control" name="project_name" value="{{old('project_name')}}" required>
                </div>

              </div>

              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                  Project Amount: <span class="required">* </span>
                </label>

                <div class="col-md-8">
                  <input type="number" class="form-control" name="project_amount" value="{{old('project_amount')}}" required>
                </div>

              </div>

              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                  Incentive amount: <span class="required">* </span>
                </label>

                <div class="col-md-8">
                  <input type="number" class="form-control" name="incentive_amount" value="{{old('incentive_amount')}}" required>
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
</script>
@endsection