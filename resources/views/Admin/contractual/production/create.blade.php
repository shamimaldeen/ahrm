@extends('Admin.index')
@section('body')
<script src="{{url('/')}}/public/assets/global/plugins/jquery.min.js"></script>
<link rel="stylesheet" href="{{URL::to('/')}}/public/css/datepicker.css" />
<div class="row">
        <div class="col-md-12">
           @include('error.msg')
          <!-- BEGIN EXAMPLE TABLE PORTLET-->
          <div class="portlet box grey-cascade">
            <div class="portlet-title">
              <div class="caption">
                <i class="fa fa-globe"></i>Create a Job 
              </div>
              <div style="float:right">
                <a class="btn btn-xs btn-primary" href="{{url('production')}}" style="margin-top:10px">View Jobs</a>
              </div>
            
            </div>
            <div class="portlet-body">
              <div class="table-toolbar">
                <div class="row">

              <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{route('production.store')}}" name="basic_validate" id="basic_validate" novalidate="novalidate">
              {{ csrf_field() }}

              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                  Choose Employee: <span class="required">* </span>
                </label>

                <div class="col-md-8">
                  <select name="pro_empid" id="pro_empid" class="form-control chosen">
                    @if(isset($employees))
                    @foreach ($employees as $employee)
                      <option value="{{$employee->emp_id}}">{{$employee->emp_name}} ({{$employee->emp_empid}})</option>
                    @endforeach
                    @endif
                  </select>
                </div>

              </div>

              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                  Choose Style: <span class="required">* </span>
                </label>

                <div class="col-md-8">
                  <select name="sty_id" id="sty_id" class="form-control chosen" onchange="getPiece()">
                    @if(isset($styles))
                    @foreach ($styles as $style)
                      <option value="{{$style->sty_id}}">{{$style->sty_desc}}</option>
                    @endforeach
                    @endif
                  </select>
                </div>

              </div>

              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                  Choose Piece: <span class="required">* </span>
                </label>

                <div class="col-md-8">
                  <select name="pro_pi_id" id="pro_pi_id" class="form-control chosen">
                    
                  </select>
                </div>

              </div>

              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                  No. Of Dozen: <span class="required">* </span>
                </label>

                <div class="col-md-8">
                  <input type="text" name="pro_no_dz" class="form-control" value="{{old('pro_no_dz')}}">
                </div>

              </div>

              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                  Total Cost: <span class="required">* </span>
                </label>

                <div class="col-md-8">
                  <input type="text" name="pro_totalcost" class="form-control" value="{{old('pro_totalcost')}}">
                </div>

              </div>

              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                  Start Date: <span class="required">* </span>
                </label>

                <div class="col-md-8">
                  <input type="text" name="pro_startdate" id="pro_startdate" data-date-format="yyyy-mm-dd" readonly style="background: white" class="form-control" value="{{old('pro_startdate',date('Y-m-d'))}}">
                </div>

              </div>

              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                  End Date: <span class="required">* </span>
                </label>

                <div class="col-md-8">
                  <input type="text" name="pro_enddate" id="pro_enddate" data-date-format="yyyy-mm-dd" readonly style="background: white" class="form-control" value="{{old('pro_enddate',date('Y-m-d',strtotime('+7 days')))}}">
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

<script type="text/javascript">
  getPiece();
  $('#pro_startdate').datepicker();
  $('#pro_enddate').datepicker();
  function getPiece() {
    var sty_id=$('#sty_id').val();
    $.ajax({
      url: "{{url('production')}}/"+sty_id+"/getPiece",
      type: 'get',
      data: {},
      success:function(data) {
          $('#pro_pi_id').html(data).trigger("chosen:updated");        
      }
    });
  }
</script>
@endsection