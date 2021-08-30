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
                <i class="fa fa-globe"></i>Project Details 
              </div>
            
            </div>
            <div class="portlet-body">
              <div class="table-toolbar">
                <div class="row">

              <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{route('project-details.store')}}" name="basic_validate" id="basic_validate" novalidate="novalidate">
              {{ csrf_field() }}

              <div class="form-group">
                <label class="col-md-2 control-label">
                  Project Name : <span class="required">* </span>
                </label>
                <div class="col-md-8">
                   <input type="text" class='form-control' placeholder="Project Name" name="project_name" id="project_name" value="{{old('project_name',$projectDetails->project_name)}}">
                </div>
              </div>

              <div class="form-group">
                <label class="col-md-2 control-label">
                  Project Company : <span class="required">* </span>
                </label>
                <div class="col-md-8">
                   <input type="text" class='form-control' placeholder="Project Company" name="project_company" id="project_company" value="{{old('project_company',$projectDetails->project_company)}}">
                </div>
              </div>

              <div class="form-group">
                <label class="col-md-2 control-label">
                  Project Details : <span class="required">* </span>
                </label>
                <div class="col-md-8">
                   <textarea class='form-control text-left' name="project_details" id="project_details" rows="5">
                     {{old('project_details',$projectDetails->project_details)}}
                   </textarea>
                </div>
              </div>
              <div class="form-group">

                <label class="col-md-2 control-label">
                  Project Address : <span class="required">* </span>
                </label>
                <div class="col-md-8">
                   <textarea class='form-control text-left' name="project_address" id="project_address" rows="5">
                     {{old('project_address',$projectDetails->project_address)}}
                   </textarea>
                </div>
              </div>

              <div class="form-group">
                <label class="col-md-2 control-label">
                  Project Contact : <span class="required">* </span>
                </label>
                <div class="col-md-8">
                   <textarea class='form-control text-left' name="project_contact" id="project_contact" rows="5">
                     {{old('project_contact',$projectDetails->project_contact)}}
                   </textarea>
                </div>
              </div>

              <div class="form-group">
                <label class="col-md-2 control-label">
                  Project Logo : <span class="required">* </span>
                </label>
                <div class="col-md-2">
                   <input type="file" name="project_logo" class="form-control">
                </div>
                <label class="col-md-2 control-label">
                  Cuurent Logo :
                </label>
                <div class="col-md-4">
                  @if($projectDetails->project_logo!="")
                    <img src="{{url('public/projectDetails')}}/{{$projectDetails->project_logo}}" class="img-thumbnail img-rounded img-responsive" style="height: 100px;">
                  @else
                    <h4 class="text-danger text-left">No Logo Available!</h4>
                  @endif
                </div>
              </div>

              <div class="form-group">
                <label class="col-md-2 control-label">
                  Project Icon : <span class="required">* </span>
                </label>
                <div class="col-md-2">
                   <input type="file" name="project_icon" class="form-control">
                </div>
                <label class="col-md-2 control-label">
                  Cuurent Icon :
                </label>
                <div class="col-md-4">
                  @if($projectDetails->project_icon!="")
                    <img src="{{url('public/projectDetails')}}/{{$projectDetails->project_icon}}" class="img-thumbnail img-rounded img-responsive" style="height: 50px;">
                  @else
                    <h4 class="text-danger text-left">No Icon Available!</h4>
                  @endif
                </div>
              </div>

              <div class="form-group">
                <div class="col-md-4 col-md-offset-2">
                    <input type="submit" value="Update" class='btn btn-sm- btn-primary'>
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