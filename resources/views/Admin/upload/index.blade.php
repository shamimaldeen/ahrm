@extends('Admin.index')
@section('body')
<script src="{{URL::to('/')}}/public/assets/global/plugins/jquery.min.js"></script>
<link rel="stylesheet" href="{{URL::to('/')}}/public/css/datepicker.css" />

<div class="row">
        <div class="col-md-12">
           @include('error.msg')
          <!-- BEGIN EXAMPLE TABLE PORTLET-->
          <div class="portlet box grey-cascade">
            <div class="portlet-title">
              <div class="caption">
                <i class="fa fa-globe"></i>Upload File 
              </div>
            
            </div>
            <div class="portlet-body">
              <div class="table-toolbar">
                <div class="row">

              <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{URL::to('uploadPreview')}}" name="basic_validate" id="basic_validate" novalidate="novalidate">
                  {{ csrf_field() }}

              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                  Select Module 
                </label>

                <div class="col-md-8">
                  <select name="module" id="module" class="form-control chosen" onchange="toggoleMonthYear()">
                    <option value="salaryHeadData">Employee Salaray Data (Salary Head)</option>
                    <option value="employeeData">Employee Information</option>
                    <option value="insuranceData">Employee Insurance Data</option>
                    <option value="salaryData">Employee Salaray Data (Basic)</option>
                  </select>
                </div>

              </div>

              <div class="form-group" id="date_show">
                  
                <label class="col-md-2 control-label">
                  Date of execution Starts From : 
                </label>

                <div class="col-md-8">
                  <input type="text" name="upload_date_of_execution" id="upload_date_of_execution" class="form-control" data-date-format="yyyy-mm-dd" value="{{date('Y-m-d')}}">
                </div>

              </div>

              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                  Select File (.CSV): 
                </label>

                <div class="col-md-8">
                  <input type="file" class="form-control" name="file">
                </div>

              </div>
 

               <div class="form-group">

                <label class="col-md-2 control-label">
              
                </label>

                <div class="col-md-3">
                  <input type="submit" value="See Preview" class="btn btn-success">
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
<script type="text/javascript">
$('#upload_date_of_execution').datepicker();

  function toggoleMonthYear() {
    if($('#module').val()=="salaryHeadData"){
      $('#date_show').show();
    }else{
      $('#date_show').hide();
    }
  }
</script>
@endsection