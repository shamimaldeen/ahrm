@extends('Admin.index')
@section('body')
<script src="{{url('/')}}/public/assets/global/plugins/jquery.min.js"></script>
<div class="row">
        <div class="col-md-12">
           @include('error.msg')
          <!-- BEGIN EXAMPLE TABLE PORTLET-->
          <div class="portlet box grey-cascade">
            <div class="portlet-title">
              <div class="caption">
                <i class="fa fa-globe"></i>Performance Report 
              </div>
              <div style="float:right">
                <a class="btn btn-xs btn-primary" href="{{url('jobs')}}" style="margin-top:10px">View Jobs</a>
              </div>
            
            </div>
            <div class="portlet-body">
              <div class="table-toolbar">
                <div class="row">

              <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{route('job-report.store')}}" name="basic_validate" id="basic_validate" novalidate="novalidate">
              {{ csrf_field() }}

                           
              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                  Choose Employee: <span class="required">* </span>
                </label>

                <div class="col-md-8">
                  <select name="emp_id" id="emp_id" class="form-control chosen">
                    @if(isset($Employee) && count($Employee)>0)
                      @foreach ($Employee as $emp)
                      <option value="{{$emp->emp_id}}">{{$emp->emp_name}} ({{$emp->emp_empid}})</option>
                      @endforeach
                    @endif
                  </select>
                </div>

              </div>

              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                  Repoort Type: <span class="required">* </span>
                </label>

                <div class="col-md-8">
                  <select class="form-control chosen" name="type">
                    <option value="1">Project Value Report</option>
                    <option value="2">Performance Report</option>
                  </select>
                </div>

              </div>

              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                  Choose Date Range: <span class="required">* </span>
                </label>




               @if(!isset($start_date) && !isset($end_date))
      
                <div class="col-md-4">
                    Start Date
                    <input type="date" class="form-control" id="start_date" style="background: white;padding: 0px 0px 0px 10px" required=""><br>
                     End Date
                    <input type="date" class="form-control" id="end_date" style="background: white;padding: 0px 0px 0px 10px" required="">

                </div><br><br><br>
                @endif
                 @if(isset($start_date) && isset($end_date))
                  Start Date
                <div class="col-md-4">
                  Start Date
                    <input type="date" class="form-control" id="start_date" style="background: white;padding: 0px 0px 0px 10px" required="" value="{{ $start_date }}"><br>
                     End Date
                    <input type="date" class="form-control" id="end_date" style="background: white;padding: 0px 0px 0px 10px" required="" value="{{ $end_date }}">

                </div><br><br><br>
                @endif


                

              </div>

               <div class="form-group">

                <label class="col-md-2 control-label">
              
                </label>

                <div class="col-md-3">
                  <input type="submit" value="Get Report" class="btn btn-success">
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
@endsection