@php
use App\Http\Controllers\BackEndCon\Controller as C;
use Illuminate\Support\Facades\Route;
$route=Route::getFacadeRoot()->current()->uri();
@endphp
<style type="text/css">
        @media print{
            #p {
                display: none;
            }
       }
       .custom{
        padding: 1rem;
       }
.btn{
    font-weight: bold;
}
.caption{
    text-align: center;
     font-size: 15px;
    font-weight: bold;
    
}
table {
  width: 100%;
  border-collapse: collapse;
}
th, td {
  padding: 10px;
  text-align: left;
}
th{
  background: #e4e4e4;
}

table.print-content {
  font-size: 12px;
  border: 1px solid #dee2e6;
  border-collapse: collapse !important;
}

table.print-content th,
table.print-content td {
  padding: .2rem .4rem;
  text-align: left;
  vertical-align: top;
  border-top: 1px solid #dee2e6;
}
.sign-footer thead td{
      width:25%;
  }
@media print {
  .print-footer {
    position: fixed;
    bottom: 0;
    left: 0;
  }
  .no-print {
    display: none;
  }
  
}
</style>

<script src="{{URL::to('/')}}/public/assets/global/plugins/jquery.min.js"></script>

 <input type="button" id="p" value="print" onclick="printPage();" class="btn btn-label-success btn-sm btn-upper no-print">
<!-- filter end -->
<table>
  <!-- Start Header -->
  <thead>
    <tr>
      <td>
        <div class="row print-header">
            <div style="width:30%; float:left;">&nbsp;
            </div>
            <div style="text-align:left; width:33%; float:left;display: inline-flex;">
                <div>
                  <img src="{{url('/')}}/public/Main_logo.jpg" width="auto" height="70"/>
                </div>
               <div style="padding-left: 10px;">
                   <h3 style="margin: 0px; padding: 0px;">PFI Securities Limited</h3>
                  <p style="">56-57, 7th & 8th Floor, PFI Tower, Dilkusha C/A, Dhaka 1000</p>
                </div>
            </div>
            <div style="width:30%; float:left;">
              <h2 style="text-align: center;">Attendence List</h2>
            </div>
        </div>
      </td>
    </tr>
  </thead>
  <!-- End Header -->
  <tr>
    <td>
    <!-- Page content Start -->
<div class="row custom" style="">
    <div class="col-md-12">
       @include('error.msg')
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-title">
              @if(!isset($start_date) && !isset($end_date))
                <div class="caption font-dark" style="font-size:15px; text-align:left;">
                    <i class="caption icon-settings font-dark"></i>
                    <span class="caption-subject bold uppercase">Date: @if(isset($start_date) && isset($end_date)) {{$start_date}} to {{$end_date}} @endif @if($flag==1)
                      ({{ $empl_location->jl_name }})

                     @endif

                     @if(!isset($start_date) && !isset($end_date)) {{$today}}@endif @if($flag==1)
                      ({{ $empl_location->jl_name }})

                     @endif

                   </span>
                </div>
                @else
                <div class="caption font-dark" style="font-size:15px; text-align:left;">
                    <i class="icon-settings font-dark"></i>
                    <span class="caption-subject bold uppercase" style="">Date:  @if(isset($start_date) && isset($end_date)) {{$start_date}} to {{$end_date}} @endif @if($flag==1)
                      ({{ $empl_location->jl_name }})
                     @endif</span>
                </div>
                @endif
                <div class="col-md-6"><p id="confirm_msg"></p></div>
                <div class="actions">
                  <!-- <button class="btn btn-success">Print</button> -->
              
                  @if((C::checkAppModulePriority($route,'Add New')=="1") or (C::checkAppModulePriority($route,'Edit')=="1") or (C::checkAppModulePriority($route,'Delete')=="1"))
                    
                  @endif
                   
                </div>
                                        
                <div class="tools"> </div>
            </div>
            <div class="portlet-body">
            <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{URL::to('')}}" name="basic_validate" id="data_form">
            {{ csrf_field() }}
              <table class="table table-striped table-bordered table-hover table-checkable order-column" id="sample_3" width="100%" border="1px">
                      <thead>
                        <tr>
                          <!-- <th id="hidden"></th> -->
                          <th>SL</th>
                          <th>Employee ID</th>
                          <!-- @if($id->suser_level=="1")
                          <th hidden>Face ID</th>
                          @endif -->
                          <th>Employee Name</th>
                          <th>Date</th>
                          <th>Shift</th>
                          <th>Entry Time</th>
                          <th>Exit Time</th>
                          <th>Grace Time (Min)</th>
                          <th>Late Entry</th>
                          <th>Early Exit</th>
                          <th>TWH</th>
                         <!--  <th hidden>Night</th> -->
                          <th>Late</th>
                          <th>Present</th>
                          <th>Absent</th>
                          <th>Leave</th>
                          <th>No Exit Found</th>
                          <th>Remarks</th>
                         <!--  <th>Generated at</th> -->
                        </tr>
                      </thead>
              
                      <tbody>
                      @if(isset($attendances[0]))
                      @foreach ($attendances as $key => $attendance)
                      @if($attendance->employee->emp_empid != 'Atech-1001')
                        <tr>
                          <!-- <td id="hidden"></td> -->
                          <td>{{$key+1}}</td>
                          <td>{{$attendance->employee->emp_empid}}</td>
                         <!--  @if($id->suser_level=="1")
                          <td hidden>{{$attendance->employee->emp_machineid}}</td>
                          @endif -->
                          <td>{{$attendance->employee->emp_name}}</td>
                          <td>{{date('d-m-Y', strtotime($attendance->date))}}</td>
                          <td>
                            @if($attendance->shift)
                            {{$attendance->shift->shift_stime}} to {{$attendance->shift->shift_etime}}
                            @endif
                          </td>
                          <td>
                            @if(count(explode(',',$attendance->entry_time))>1)
                            {!! C::attendanceStatusText($attendance->entry_time) !!}
                            @elseif($attendance->entry_time!="" && $attendance->entry_time!="00:00:00")
                            {{date('g:i a',strtotime($attendance->entry_time))}}
                            @endif
                          </td>
                          <td>
                            @if(count(explode(',',$attendance->exit_time))>1)
                            {!! C::attendanceStatusText($attendance->exit_time) !!}
                            @elseif($attendance->exit_time!="" && $attendance->exit_time!="00:00:00")
                            {{date('g:i a',strtotime($attendance->exit_time))}}
                            @endif
                          </td>
                          <td>
                            @php
                              $grace=App\Setup::first();
                            @endphp
                            {{ $grace->grace_time }}
                          </td>
                          <td>{{$attendance->late_entry}}</td>
                          <td>{{$attendance->early_exit}}</td>
                          <td>{{$attendance->twh}}</td>
                          <!-- <td hidden>{{$attendance->night}}</td> -->
                           <td>

                            {!! C::attendanceLate($attendance->status) !!}
                          </td>
                          <td>
                            {!! C::attendancePresent($attendance->status) !!}
                          </td>
                          <td>
                            {!! C::attendanceAbsent($attendance->status) !!}
                          </td>
                          <td>
                            {!! C::attendanceLeave($attendance->status) !!}
                          </td>
                          <td>
                            {!! C::attendanceNoexit($attendance->status) !!}
                          </td>
                          <td></td>
                         <!--  <td>
                            {{date('Y-m-d g:i a',strtotime($attendance->generated_at))}}
                          </td> -->
                        </tr>
                        @endif
                      @endforeach
                      @endif
                      </tbody>

                    </table>
                    <!-- Page content End -->
                    </td>
  </tr>
  <!-- Start Space For Footer -->
  <tfoot>
    <tr>
      <td style="height: 3cm">
        <!-- Leave this empty and don't remove it. This space is where footer placed on print -->
      </td>
    </tr>
  </tfoot>
  <!-- End Space For Footer -->
</table>
<!-- Start Footer -->
<div class="print-footer">
  <div class="row print-footer">
                      <table class="sign-footer" style="table-layout: fixed; width:100%;">
                        <thead>
                          <td>-----------------------------<br>Authorized Signature</td>
                          <td>-----------------------------<br>Authorized Signature</td>
                          <td>-----------------------------<br>Authorized Signature</td>
                          <td>-----------------------------<br>Authorized Signature</td>
                        </thead>
                      </table>
                    </div>
</div>
<!-- End Footer -->
                    
                  </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script type="text/javascript">
        function printPage() {
            window.print();
        }
</script>

