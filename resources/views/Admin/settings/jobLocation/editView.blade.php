@extends('Admin.index')
@section('body')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<div class="row">
        <div class="col-md-12">
           @include('error.msg')
          <!-- BEGIN EXAMPLE TABLE PORTLET-->
          <div class="portlet box grey-cascade">
            <div class="portlet-title">
              <div class="caption">
                <i class="fa fa-globe"></i>Edit Job Location Information 
              </div>
              <div style="float:right">
                <a class="btn btn-xs btn-primary" href="{{URL::to('job-location-view')}}" style="margin-top:10px">Go Back</a>
              </div>
            
            </div>
            <div class="portlet-body">
              <div class="table-toolbar">
                <div class="row">

              <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{URL::to('job-location')}}/{{$jl_id}}/update" name="basic_validate" id="basic_validate" novalidate="novalidate">
                  {{ csrf_field() }}

                           
              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                  Job Location Name: <span class="required">* </span>
                </label>

                <div class="col-md-8">
                  <input type="text" class="form-control" name="jl_name" value="{{$jobLocation->jl_name}}" required>
                </div>

              </div>
              
               <div class="form-group">
                  
                <label class="col-md-2 control-label">
                  Status : <span class="required">* </span>
                </label>

                <div class="col-md-8">
                  <span><input type="radio" name="jl_status" value='1' @if($jobLocation->jl_status=="1") checked="checked" @endif />Active</span>&nbsp;&nbsp;&nbsp;&nbsp;
                  <span><input type="radio" name="jl_status" value='0' @if($jobLocation->jl_status=="0") checked="checked" @endif />Inactive</span>
                </div>

              </div>

               <div class="form-group">

                <label class="col-md-2 control-label">
              
                </label>

                <div class="col-md-3">
                  <input type="submit" value="Update" class="btn btn-success">
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