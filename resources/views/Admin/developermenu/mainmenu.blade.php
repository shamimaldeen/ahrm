@extends('Admin.index')
@section('body')
<script src="{{URL::to('/')}}/public/assets/global/plugins/jquery.min.js"></script>

<div class="row">
        <div class="col-md-12">
           @include('error.msg')
          <!-- BEGIN EXAMPLE TABLE PORTLET-->
          <div class="portlet box grey-cascade">
            <div class="portlet-title">
              <div class="caption">
                <i class="fa fa-globe"></i>Add Main Menu 
              </div>
              <div style="float:right">
                <a class="btn btn-xs btn-primary" href="{{URL::to('main-menu-view')}}" style="margin-top:10px">View Main Menu Information</a>
              </div>
            
            </div>
            <div class="portlet-body">
              <div class="table-toolbar">
                <div class="row">

              <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{URL::to('AdmainSaveMainlink')}}" name="basic_validate" id="basic_validate" novalidate="novalidate">
                  {{ csrf_field() }}

                           
              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                  Serial No : <span class="required">* </span>
                </label>

                <div class="col-md-4">
                   <input type="text"  class='form-control'  value="{{old('serial')}}" placeholder="Serial No"
                   id="child" name="serial">
                </div>

              </div>

              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                  Link Name : <span class="required">* </span>
                </label>

                <div class="col-md-4">
                   <input type="text" class='form-control' placeholder="Menu Name English" name="MenuNameEn" id="MenuNameEn" value="{{old('MenuNameEn')}}">
                </div>

              </div>

              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                  Route Name : <span class="required">* </span>
                </label>

                <div class="col-md-4">
                    <input type="text"   
                  value="{{old('Route')}}" placeholder="Route Name " class='form-control'  
                  id="child" name="Route">
                </div>

              </div>

              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                  Icon Name (Font Awoesome) : <span class="required">* </span>
                </label>

                <div class="col-md-4">
                    <input type="text"   
                  value="{{old('icon')}}" placeholder="icon-home" class='form-control'  
                  id="child" name="icon">
                </div>

              </div>

              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                 
                </label>

                <div class="col-md-4">
                    <input type="submit" value="Save" class='btn btn-sm- btn-primary'>
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