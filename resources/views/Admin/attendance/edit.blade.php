@extends('Admin.index')
@section('body')

@php
use App\Http\Controllers\BackEndCon\Controller;
use Illuminate\Support\Facades\Route;
$route=Route::getFacadeRoot()->current()->uri();
@endphp

@if(Controller::checkAppModulePriority('attendance','Edit')=="1")
@else
<script type="text/javascript">location="{{URL::to('/')}}"</script>
@endif

<script src="{{URL::to('/')}}/public/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
<link href="{{URL::to('/')}}/public/css/bootstrap-timepicker.min.css" rel="stylesheet" type="text/css" />

<div class="row">
        <div class="col-md-12">
           @include('error.msg')
          <!-- BEGIN EXAMPLE TABLE PORTLET-->
          <div class="portlet box grey-cascade">
            <div class="portlet-title">
              <div class="caption">
                <i class="fa fa-globe"></i>Edit Attendace Information 
              </div>
              <div style="float:right">
                <a class="btn btn-xs btn-primary" href="{{URL::to('attendance')}}" style="margin-top:10px">Go Back </a>
              </div>
            
            </div>
            <div class="portlet-body">
              <div class="table-toolbar">
                <div class="row">

              <form class="form-horizontal" method="post" enctype="multipart/form-data" action="#" name="basic_validate" id="basic_validate" novalidate="novalidate">
                  {{ csrf_field() }}

              <div class="form-group">
                  
                <label class="col-md-12" style="padding-left: 50px;">
                  Employee ID: <b>{{$employee->emp_empid}}</b> , Machine ID: <b>{{$employee->emp_machineid}}</b> , Employee Name: <b>{{$employee->emp_name}}</b> , Date: <b>{{$date}}</b>
                </label>

              </div>

              <div class="col-md-12" style="border-top: 1px solid #ccc">
                <br>
              </div>

              @if(isset($attendance) && count($attendance)>0)
              @php
              $c=0;
              @endphp
              @foreach ($attendance as $att)
              @php
              $c++;
              $time=substr($att->CardTime,11,5);
              @endphp
                <div class="form-group" id="att-{{$c}}">
                    
                  <label class="col-md-2 control-label">
                    @if($c=="1")
                    Punch Type (Entry) : <span class="required">* </span>
                    @else
                      @if($c%2=="0")
                        Punch Type (Exit) : <span class="required">* </span>
                      @else
                        Punch Type (Entry) : <span class="required">* </span>
                      @endif
                    @endif
                  </label>

                  <div class="col-md-3">
                    <input type="text" class="form-control" id="CardTime{{$c}}" value="{{$time}}" required>
                  </div>
                  
                  <label class="col-md-2 control-label">
                    Workstation : <span class="required">* </span>
                  </label>

                  <div class="col-md-3">
                     <select id="DevID{{$c}}" class="form-control chosen" required>
                      @if($joblocation)
                      @foreach ($joblocation as $jl)
                        @if($jl->DevID==$att->DevID)
                        <option value="{{$jl->DevID}}" selected="selected">{{$jl->DevName}}</option>
                        @else
                        <option value="{{$jl->DevID}}">{{$jl->DevName}}</option>
                        @endif
                      @endforeach
                      @endif
                     </select>
                  </div>

                  <div class="col-md-2">
                     <a class="btn btn-primary btn-sm" onclick="updateAttendance('{{$att->CardID}}','{{$c}}')">Update</a>
                     <a class="btn btn-danger btn-sm" onclick="deleteAttendance('{{$att->CardID}}','{{$c}}')">Delete</a>
                     <span id="success_msg{{$c}}" style="float: right;display: none;padding-right: 10px"></span>
                  </div>
                </div>
              @endforeach
              <span id="hidden_count" style="display: none;">{{$c}}</span>
              @else
              <div class="form-group">
                  <h3 class="text-danger text-center">Whoops! No Punch Found!</h3>
              </div>
              @endif  
         

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

<script src="{{URL::to('/')}}/public/js/bootstrap-timepicker.min.js" type="text/javascript"></script>
<script type="text/javascript">
  getTimepicker();
  function getTimepicker() {
    var count=$('#hidden_count').text();
    for (i = 1; i <= count; i++) { 
        $('#CardTime'+i).timepicker({   
         minuteStep: 1,
         disableFocus: true,
         template: 'dropdown',
         showMeridian:false
        });
    }
  }

  function updateAttendance(CardID,count) {
    $('#success_msg'+count).hide();
    var CardTime=$('#CardTime'+count).val();
    var DevID=$('#DevID'+count).val();
    $.ajax({
      url: "{{URL::to('attendance-update')}}/"+CardID+"/"+CardTime+"/"+DevID,
      type: 'GET',
      data: {},
      success:function(data) {
        $('#success_msg'+count).show();
        if(data=="1"){
          $('#success_msg'+count).html('<b style="color:green"><i class="fa fa-check-circle"></i>&nbsp;Updated!</b>');
        }else if(data="0"){
          $('#success_msg'+count).html('<b style="color:red"><i class="fa fa-times-circle-o"></i>&nbsp;Not Changed!</b>');
        }
      }
    });
    
  }

  function deleteAttendance(CardID,count) {
    $('#success_msg'+count).hide();

    $.confirm({
      title: '',
      content: '<h4><strong class="text-danger">Are  You Confirm To Delete ?</strong></h4><hr>',
      buttons: {
          Confirm: {
              text: 'Delete',
              btnClass: 'btn-danger',
              action: function(){
                  $.ajax({
                    url: "{{URL::to('attendance-delete')}}/"+CardID,
                    type: 'GET',
                    data: {},
                    success:function(data) {
                      if(data=="1"){
                        $('#att-'+count).fadeOut();
                      }else if(data=="0"){
                        $.alert({
                          title:'',
                          content:'<hr><strong class="text-danger">Something Wrong</strong><hr>',
                          type:'red'
                        });
                      }
                    }
                  });
              }
          },
          Cancel: {
              text: 'Cancel',
              btnClass: 'btn-default',
              action: function(){
                  
              }
          }
      }
    });
  }
</script>

@endsection