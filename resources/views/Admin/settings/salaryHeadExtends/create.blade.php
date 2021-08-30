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
                <i class="fa fa-globe"></i>Deduction Salary Head
              </div>
              <div style="float:right">
                <a class="btn btn-xs btn-primary" href="{{url('extends-salary-head')}}" style="margin-top:10px">View Deduction Salary Heads</a>
              </div>
            
            </div>
            <div class="portlet-body">
              <div class="table-toolbar">
                <div class="row">

              <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{route('extends-salary-head.store')}}" name="basic_validate" id="basic_validate" novalidate="novalidate">
              {{ csrf_field() }}
                           
              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                  Head Name: <span class="required">* </span>
                </label>

                <div class="col-md-8">
                  <input type="text" class="form-control" name="head_name" value="{{old('head_name')}}" required>
                </div>

              </div>

              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                  Unit for Absent: <span class="required">* </span>
                </label>

                <div class="col-md-8">
                  <input type="text" class="form-control" name="head_unit_for_absent" value="{{old('head_unit_for_absent')}}" required>
                </div>

              </div>

              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                  Percentage for Basic: <span class="required">* </span>
                </label>

                <div class="col-md-8">
                  <input type="text" class="form-control" name="head_percentage_for_basic" value="{{old('head_percentage_for_basic')}}" required>
                </div>

              </div>

              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                  Percentage for Total: <span class="required">* </span>
                </label>

                <div class="col-md-8">
                  <input type="text" class="form-control" name="head_percentage_for_total" value="{{old('head_percentage_for_total')}}" required>
                </div>

              </div>

              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                 Percentage Type: <span class="required">* </span>
                </label>

                <div class="col-md-8">
                    <input type="radio" name="head_percentage_status" value="1" checked>&nbsp;Basic
                    &nbsp;&nbsp;
                    <input type="radio" name="head_percentage_status" value="2">&nbsp;Total
                </div>

              </div>

              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                 Head Type: <span class="required">* </span>
                </label>

                <div class="col-md-8">
                    <input type="radio" name="head_type" value="1" checked>&nbsp;Addition
                    &nbsp;&nbsp;
                    <input type="radio" name="head_type" value="0">&nbsp;Deduction
                </div>

              </div>

              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                  Head Note: <span class="required">* </span>
                </label>

                <div class="col-md-8">
                  <textarea name="head_note" rows="5" class="form-control">{{old('head_note')}}</textarea>
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
@endsection