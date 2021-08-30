@php
    use App\Http\Controllers\BackEndCon\Controller as C;
    $emp_department=C::getDepartmentId($id->suser_empid);







@endphp
<div class="col-md-4">
    <div class="portlet light bordered">
        <div class="portlet-title ">
            <div class="caption">
                <i class="icon-bar-chart font-green-haze"></i>
                <span class="caption-subject bold uppercase font-green-haze "> Employee Summary </span>
            </div>
        </div>
        <div>
            <ul class="list-group">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <a href="{{url('probation-period-notifications')}}">Probation Employee list</a>
                    @php

                     $items =  count($probations)
                    @endphp
                    <span class="badge bg-primary rounded-pill">{{ $items }} </span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">

                    <a href="{{url('view-system-user')}}"> Total Remote Employee</a>
                    <span class="badge bg-primary rounded-pill">{{ $total_remote_emp }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <a href="{{url('view-system-user')}}"> Total Office Employee</a>
                    <span class="badge bg-primary rounded-pill">{{ $total_office_emp }}</span>
                </li>


                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <a href="{{url('pending-leave-application')}}">Pending Leave list</a>
                    <span class="badge bg-primary rounded-pill"> {{ $leaveapplication }}</span>

                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <a href="{{url('pending-osd-attendance')}}">Pending OSD list</a>
                    <span class="badge bg-primary rounded-pill">{{ $osd_attendance }}</span>
                </li>

                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <a href="{{url('employee-details')}}">New jointed Employee</a>
                    <span class="badge bg-primary rounded-pill">{{$recent_jointed_employee}}</span>

                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <a href="#">Recent exited Employee</a>
                    <span class="badge bg-primary rounded-pill">{{$recent_exited_employee}}</span>
                </li>
            </ul>
        </div>

    </div>
</div>