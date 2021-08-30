@extends('Admin.index')
@section('body')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/css/bootstrap-timepicker.min.css" rel="stylesheet" type="text/css" />

<div class="row">
        <div class="col-md-12">
           @include('error.msg')
          <!-- BEGIN EXAMPLE TABLE PORTLET-->
          <div class="portlet box grey-cascade">
            <div class="portlet-title">
              <div class="caption">
                <i class="fa fa-globe"></i>Edit Shift Information 
              </div>
              <div style="float:right">
                <a class="btn btn-xs btn-primary" href="{{URL::to('shift-view')}}" style="margin-top:10px">Go Back</a>
              </div>
            
            </div>
            <div class="portlet-body">
              <div class="table-toolbar">
                <div class="row">

              <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{URL::to('shift')}}/{{$shift_id}}/update" name="basic_validate" id="basic_validate" novalidate="novalidate">
                  {{ csrf_field() }}

                           
              <div class="form-group" hidden="">
                  
                <label class="col-md-2 control-label">
                  Shift Type: <span class="required">* </span>
                </label>

                <div class="col-md-8">
                   <select name="shift_type" class="form-control" required hidden="">
                    @if($shift->shift_type=="1")
                    <option value="1">7 hours</option>
                    <option value="2">8 hours</option>
                    <option value="3">6 hours</option>
                    <option value="4">5 hours</option>
                    <option value="5">4 hours</option>
                    <option value="6">3 hours</option>
                    <option value="7">9 hours</option>
                    <option value="8">10 hours</option>
                    <option value="9">11 hours</option>
                    <option value="10">12 hours</option>
                    @elseif($shift->shift_type=="2")
                    <option value="2">8 hours</option>
                    <option value="1">7 hours</option>
                    <option value="3">6 hours</option>
                    <option value="4">5 hours</option>
                    <option value="5">4 hours</option>
                    <option value="6">3 hours</option>
                    <option value="7">9 hours</option>
                    <option value="8">10 hours</option>
                    <option value="9">11 hours</option>
                    <option value="10">12 hours</option>
                    @elseif($shift->shift_type=="3")
                    <option value="3">6 hours</option>
                    <option value="2">8 hours</option>
                    <option value="1">7 hours</option>
                    <option value="4">5 hours</option>
                    <option value="5">4 hours</option>
                    <option value="6">3 hours</option>
                    <option value="7">9 hours</option>
                    <option value="8">10 hours</option>
                    <option value="9">11 hours</option>
                    <option value="10">12 hours</option>
                    @elseif($shift->shift_type=="4")
                    <option value="4">5 hours</option>
                    <option value="3">6 hours</option>
                    <option value="2">8 hours</option>
                    <option value="1">7 hours</option>
                    
                    <option value="5">4 hours</option>
                    <option value="6">3 hours</option>
                    <option value="7">9 hours</option>
                    <option value="8">10 hours</option>
                    <option value="9">11 hours</option>
                    <option value="10">12 hours</option>
                    @elseif($shift->shift_type=="5")
                    <option value="5">4 hours</option>
                    <option value="1">7 hours</option>
                    <option value="2">8 hours</option>
                    <option value="3">6 hours</option>
                    <option value="4">5 hours</option>
                    
                    <option value="6">3 hours</option>
                    <option value="7">9 hours</option>
                    <option value="8">10 hours</option>
                    <option value="9">11 hours</option>
                    <option value="10">12 hours</option>
                    @elseif($shift->shift_type=="6")
                    <option value="6">3 hours</option>
                    <option value="1">7 hours</option>
                    <option value="2">8 hours</option>
                    <option value="3">6 hours</option>
                    <option value="4">5 hours</option>
                    <option value="5">4 hours</option>
                    
                    <option value="7">9 hours</option>
                    <option value="8">10 hours</option>
                    <option value="9">11 hours</option>
                    <option value="10">12 hours</option>
                    @elseif($shift->shift_type=="7")
                    <option value="7">9 hours</option>
                    <option value="1">7 hours</option>
                    <option value="2">8 hours</option>
                    <option value="3">6 hours</option>
                    <option value="4">5 hours</option>
                    <option value="5">4 hours</option>
                    <option value="6">3 hours</option>
                    
                    <option value="8">10 hours</option>
                    <option value="9">11 hours</option>
                    <option value="10">12 hours</option>
                    @elseif($shift->shift_type=="8")
                    <option value="8">10 hours</option>
                    <option value="1">7 hours</option>
                    <option value="2">8 hours</option>
                    <option value="3">6 hours</option>
                    <option value="4">5 hours</option>
                    <option value="5">4 hours</option>
                    <option value="6">3 hours</option>
                    <option value="7">9 hours</option>
                   
                    <option value="9">11 hours</option>
                    <option value="10">12 hours</option>
                    @elseif($shift->shift_type=="9")
                    <option value="9">11 hours</option>
                    <option value="1">7 hours</option>
                    <option value="2">8 hours</option>
                    <option value="3">6 hours</option>
                    <option value="4">5 hours</option>
                    <option value="5">4 hours</option>
                    <option value="6">3 hours</option>
                    <option value="7">9 hours</option>
                    <option value="8">10 hours</option>
                    
                    <option value="10">12 hours</option>
                    @elseif($shift->shift_type=="10")
                    <option value="10">12 hours</option>
                    <option value="1">7 hours</option>
                    <option value="2">8 hours</option>
                    <option value="3">6 hours</option>
                    <option value="4">5 hours</option>
                    <option value="5">4 hours</option>
                    <option value="6">3 hours</option>
                    <option value="7">9 hours</option>
                    <option value="8">10 hours</option>
                    <option value="9">11 hours</option>
                    
                    @endif
                    </select>
                </div>

              </div>

              <div class="form-group" hidden="">
                  
                <label class="col-md-2 control-label">
                  Start Time: <span class="required">* </span>
                </label>

                <div class="col-md-3">
                  <input type="text" class="form-control" name="shift_stime" value="{{$shift->shift_stime}}" id="timepicker1" required>
                </div>

                <label class="col-md-2 control-label">
                  End Time: <span class="required">* </span>
                </label>

                <div class="col-md-3">  
                <input type="text" class="form-control" name="shift_etime" value="{{$shift->shift_etime}}" id="timepicker2" required>      
                </div>
              </div>  

              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                  Status : <span class="required">* </span>
                </label>

                <div class="col-md-8">
                  <span><input type="radio" name="shift_status" value='1' @if($shift->shift_status=="1") checked="checked" @endif />Active</span>&nbsp;&nbsp;&nbsp;&nbsp;
                  <span><input type="radio" name="shift_status" value='0' @if($shift->shift_status=="0") checked="checked" @endif />Inactive</span>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/js/bootstrap-timepicker.min.js" type="text/javascript"></script>
<script type="text/javascript">
$('#datepicker1').datepicker();
$('#timepicker1').timepicker({   
 minuteStep: 1,
 disableFocus: true,
 template: 'dropdown',
 showMeridian:false
});
$('#timepicker2').timepicker({   
 minuteStep: 1,
 disableFocus: true,
 template: 'dropdown',
 showMeridian:false
});
</script>

@endsection