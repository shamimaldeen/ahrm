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
                <i class="fa fa-globe"></i>Add Leave Type 
              </div>
              <div style="float:right">
                <a class="btn btn-xs btn-primary" href="{{URL::to('leave-type-view')}}" style="margin-top:10px">View Leave Type </a>
              </div>
            
            </div>
            <div class="portlet-body">
              <div class="table-toolbar">
                <div class="row">

              <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{URL::to('leave-type-add')}}" name="basic_validate" id="basic_validate" novalidate="novalidate">
                  {{ csrf_field() }}

                           
              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                  Leave Name: <span class="required">* </span>
                </label>

                <div class="col-md-8">
                  <input type="text" class="form-control" name="li_name" value="{{old('li_name')}}" required>
                </div>

              </div>

              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                  Qouta Day: <span class="required">* </span>
                </label>

                <div class="col-md-8">
                  <input type="number" class="form-control" name="li_qoutaday" value="{{old('li_qoutaday')}}" required>
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


<script src="{{URL::to('/')}}/public/js/bootstrap-datepicker.js"></script> 

@endsection