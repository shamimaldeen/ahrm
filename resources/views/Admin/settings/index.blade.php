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
                <i class="fa fa-globe"></i>Setup 
              </div>
            </div>
            <div class="portlet-body">
              <div class="table-toolbar">
                <div class="row">

              <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{route('setup.store')}}" name="basic_validate" id="basic_validate" novalidate="novalidate">
              {{ csrf_field() }}

                           
              <div class="form-group">
                <label class="col-md-2 control-label">
                  Developer Mode: <span class="required">* </span>
                </label>

                <div class="col-md-8">
                  <input type="radio" name="developer_mode" value="1" @if($setup->developer_mode=="1") checked @endif>&nbsp;On
                  &nbsp;
                  <input type="radio" name="developer_mode" value="0" @if($setup->developer_mode=="0") checked @endif>&nbsp;Off
                </div>
              </div>

              <div class="form-group">
                <label class="col-md-2 control-label">
                  Attendance Type: <span class="required">* </span>
                </label>

                <div class="col-md-8">
                  <input type="radio" name="attendance_type" value="1" @if($setup->attendance_type=="1") checked @endif>&nbsp;Device Based System
                  &nbsp;
                  <input type="radio" name="attendance_type" value="0" @if($setup->attendance_type=="0") checked @endif>&nbsp;First in Last out System
                </div>
              </div>

              <div class="form-group">
                <label class="col-md-2 control-label">
                Grace Time(Min): <span class="required">* </span>
                </label>

                <div class="col-md-8">
                  <input type="text" name="grace_time" value="{{ $setup->grace_time }}">
                </div>
              </div>

              <div class="form-group">
                <label class="col-md-2 control-label">
                Absent Time(Clock Time): <span class="required">* </span>
                </label>

                <div class="col-md-8">
                  <input type="text" name="absent_time" value="{{ $setup->absent_time }}">
                </div>
              </div>

              <div class="form-group">
                <label class="col-md-2 control-label">
                Baishakhay Bonus(%) : <span class="required">* </span>
                </label>

                <div class="col-md-8">
                  <input type="number" min="0" name="b_bonus" value="{{ $setup->b_bonus }}">
                  <span>Note: To Activate This Bonus, Please Input a Value More Than 0.</span>
                </div>
              </div>

              <div class="form-group">
                <label class="col-md-2 control-label">
                Eid-Ul-Fitr Bonus(%) : <span class="required">* </span>
                </label>

                <div class="col-md-8">
                  <input type="number" min="0" name="fitr_bonus" value="{{ $setup->fitr_bonus }}">
                  <span>Note: To Activate This Bonus, Please Input a Value More Than 0.</span>
                </div>
              </div>

               <div class="form-group">
                <label class="col-md-2 control-label">
                Eid-Ul-Adha Bonus(%) : <span class="required">* </span>
                </label>

                <div class="col-md-8">
                  <input type="number" min="0" name="adha_bonus" value="{{ $setup->adha_bonus }}">
                  <span>Note: To Activate This Bonus, Please Input a Value More Than 0.</span>
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