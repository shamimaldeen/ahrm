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
                <i class="fa fa-globe"></i>Edit Holiday Information 
              </div>
              <div style="float:right">
                <a class="btn btn-xs btn-primary" href="{{URL::to('holiday-view')}}" style="margin-top:10px">Go Back</a>
              </div>
            
            </div>
            <div class="portlet-body">
              <div class="table-toolbar">
                <div class="row">

              <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{URL::to('holiday')}}/{{$holiday_id}}/update" name="basic_validate" id="basic_validate" novalidate="novalidate">
                  {{ csrf_field() }}

                           
              <!--<div class="form-group">
                  
                <label class="col-md-2 control-label">
                  Holiday Type: <span class="required">* </span>
                </label>

                <div class="col-md-8">
                  <label class="control-label">
                    <input type="radio" name="holiday_type" id="holiday_type1" value='1' @if($holiday->holiday_type=="1") checked="checked" @endif/>&nbsp;&nbsp;Friday&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="radio" name="holiday_type" id="holiday_type0" value='0'  @if($holiday->holiday_type=="0") checked="checked" @endif />&nbsp;&nbsp;Others Holiday
                  </label>
                </div>

              </div>-->

              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                  Holiday Date: <span class="required">* </span>
                </label>

                <div class="col-md-8">
                  <input type="text" class="form-control" name="holiday_date" id="holiday_date" data-date-format="yyyy-mm-dd" readonly="" style="background: white" value="{{$holiday->holiday_date}}">
                </div>
              </div>
              
              <div class="form-group" id="description_div">
                  
                <label class="col-md-2 control-label">
                  Holiday Description: <span class="required">* </span>
                </label>

                <div class="col-md-8">
                  <input type="text" class="form-control" name="holiday_description" id="holiday_description" value="{{$holiday->holiday_description}}">
                </div>
              </div>    

              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                  Status : <span class="required">* </span>
                </label>

                <div class="col-md-8">
                  <span><input type="radio" name="holiday_status" value='1' @if($holiday->holiday_status=="1") checked="checked" @endif />Active</span>&nbsp;&nbsp;&nbsp;&nbsp;
                  <span><input type="radio" name="holiday_status" value='0' @if($holiday->holiday_status=="0") checked="checked" @endif />Inactive</span>
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


<script src="{{URL::to('/')}}/public/js/bootstrap-datepicker.js"></script>
<script type="text/javascript">
$('#holiday_date').datepicker();

$("#holiday_type1").change(function() {
    if(this.checked) {
      $('#holiday_description').val('Friday (Weekend Holiday)');
      $('#description_div').hide();
    }else{
      $('#holiday_description').val('');
      $('#description_div').show();
    }
});

$("#holiday_type0").change(function() {
    if(this.checked) {
      $('#holiday_description').val('');
      $('#description_div').show();
    }else{
      $('#holiday_description').val('Friday (Weekend Holiday)');
      $('#description_div').hide();
    }
});
</script>

@endsection