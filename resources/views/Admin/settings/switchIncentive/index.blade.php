@extends('Admin.index')
@section('body')
<script src="{{url('/')}}/public/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
<div class="row">
        <div class="col-md-12">
           @include('error.msg')
          <!-- BEGIN EXAMPLE TABLE PORTLET-->
          <div class="portlet box grey-cascade">
            <div class="portlet-title">
              <div class="caption">
                <i class="fa fa-globe"></i>Switch Incentive Calculation
              </div>
            </div>
            <div class="portlet-body">
              <div class="table-toolbar">
                <div class="row">

              <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{route('switch-incentive-calculation.store')}}" name="basic_validate" id="basic_validate" novalidate="novalidate">
              {{ csrf_field() }}
              <input type="hidden" name="updated_by" value="{{$id->suser_empid}}">

              <div class="form-group">
                <label class="col-md-3 control-label">
                  Switch Incentive Calculation :
                </label>

                <div class="col-md-4">
                  <select name="incentive" id="incentive" class="form-control chosen" onchange="toggolePercantage()">
                    <option  value="1" @if(isset($SwitchIncentive->incentive) && $SwitchIncentive->incentive=="1") selected @endif>Enable</option>
                    <option  value="0" @if(isset($SwitchIncentive->incentive) && $SwitchIncentive->incentive=="0") selected @endif>Disable</option>
                  </select>
                </div>
              </div>

              <div class="form-group" id="percentage_view">
                <label class="col-md-3 control-label">
                  Incentive Percentage :
                </label>

                <div class="col-md-4">
                  <input type="number" min="0" max="100" value="{{(($SwitchIncentive->percentage) ? $SwitchIncentive->percentage : '100')}}" name="percentage" class="form-control">
                </div>
              </div>

              <div class="form-group">
                <div class="col-md-offset-3">
                  <input type="submit" value="Switch Incentive Calculation" class="btn btn-success">
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
<script type="text/javascript">
  toggolePercantage();
  function toggolePercantage() {
    var incentive=$('#incentive').val();
    if(incentive=="1"){
      $('#percentage_view').show();
    }else{
      $('#percentage_view').hide();
    }
  }
</script>
@endsection