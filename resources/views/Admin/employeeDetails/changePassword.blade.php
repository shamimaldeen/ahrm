@extends('Admin.index')
@section('body')

@php
use App\Http\Controllers\BackEndCon\Controller;
use Illuminate\Support\Facades\Route;
$route=Route::getFacadeRoot()->current()->uri();
@endphp

@if(Controller::checkAppModulePriority('employee-details','Change Password')=="1")
@else
<script type="text/javascript">location="{{URL::to('/')}}"</script>
@endif
<script src="{{URL::to('/')}}/public/assets/global/plugins/jquery.min.js"></script>

<div class="row">
        <div class="col-md-12">
           @include('error.msg')
          <!-- BEGIN EXAMPLE TABLE PORTLET-->
          <div class="portlet box grey-cascade">
            <div class="portlet-title">
              <div class="caption">
                <i class="fa fa-globe"></i>Change Password
              </div>
            
            </div>
            <div class="portlet-body">
              <div class="table-toolbar">
                <div class="row">

              <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{URL::to('change-password-submit')}}" name="basic_validate" id="basic_validate" novalidate="novalidate">
                  {{ csrf_field() }}

                           
              <div class="form-group">
                  
                <label class="col-md-3 control-label">
                  Old Password: <span class="required">* </span>
                </label>

                <div class="col-md-5">
                  <input type="password" class="form-control" name="oldPass"  required>
                </div>

              </div>

              <div class="form-group">
                  
                <label class="col-md-3 control-label">
                  New Password: <span class="required">* </span>
                </label>

                <div class="col-md-5">
                  <input type="password" class="form-control" name="newPass"  required>
                </div>

              </div>

				      <div class="form-group">
                  
                <label class="col-md-3 control-label">
                  Confirm New Password: <span class="required">* </span>
                </label>

                <div class="col-md-5">
                  <input type="password" class="form-control" name="confirmPass"  required>
                </div>

              </div>			  



               <div class="form-group" style="">

                <div class="col-md-3 col-md-offset-3">
                  <input type="submit" value="Change Password" class="btn btn-success">
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