@extends('Admin.index')
@section('body')
<div class="row">
        <div class="col-md-12">
           @include('error.msg')
          <!-- BEGIN EXAMPLE TABLE PORTLET-->
          <div class="portlet box grey-cascade">
            <div class="portlet-title">
              <div class="caption">
                <i class="fa fa-globe"></i>Edit Device Information 
              </div>
              <div style="float:right">
                <a class="btn btn-xs btn-primary" href="{{URL::to('device-info-view')}}" style="margin-top:10px">Go Back</a>
              </div>
            
            </div>
            <div class="portlet-body">
              <div class="table-toolbar">
                <div class="row">

              <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{URL::to('device-info')}}/{{$device->id}}/update" name="basic_validate" id="basic_validate" novalidate="novalidate">
                  {{ csrf_field() }}

              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                  Device Name: <span class="required">* </span>
                </label>

                <div class="col-md-8">
                  <input type="text" class="form-control" name="name" value="{{old('name',$device->name)}}" required>
                </div>

              </div>

              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                  Device Type: <span class="required">* </span>
                </label>

                <div class="col-md-8">
                  <select name="type" class="form-control chosen">
                    <option value="1" @if($device->type=="1") selected @endif>Check In</option>
                    <option value="0" @if($device->type=="0") selected @endif>Check Out</option>
                  </select>
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