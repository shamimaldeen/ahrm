@php 
use App\Http\Controllers\BackEndCon\Controller as C; 
$emp_department=C::getDepartmentId($id->suser_empid);
@endphp
<div class="col-md-4">
    <div class="portlet light bordered">
        <div class="portlet-title ">
            <div class="caption">
                <i class="icon-bar-chart font-green-haze"></i>
                <span class="caption-subject bold uppercase font-green-haze "> Notice Board</span>
            </div>                            
        </div>
        <div class="portlet-body">
          <div class="panel-group" id="accordion">
            @if(isset($notice) && count($notice)>0)
            @php
            $c=0;
            $show=false;
            @endphp
              @foreach ($notice as $nt)
              
              @php
              if(count($nt->notice_employee)>0){
                $employees=json_decode($nt->notice_employee, true);
                if(count($employees)>0){
                  if(in_array($id->suser_empid, $employees)){
                    $show=true;
                  }
                }else{
                  $show=true;
                }
              }else{
                if(in_array($emp_department, $notice_department)){
                  $show=true;
                }
              }
              @endphp

              @if($show)
                @php
                $c++;
                $created_at=strtotime($nt->notice_created_at);
                $created_at=date('g:ia \o\n l jS F Y',$created_at);
                @endphp
                  <div class="panel panel-default">
                    <div class="panel-heading">
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapse{{$c}}">
                        <h4 class="panel-title" style="min-height: 20px;max-height: 50px">
                          <span class="col-md-6" style="font-size: 14px;"><strong>{{$nt->notice_title}}</strong></span>
                          <span class="col-md-3" style="float: right;font-size: 10px;padding: 1px;font-weight: bold">Published By - {{$nt->notice_added_by}}</span> <span class="col-md-3" style="float: right;font-size: 10px;padding: 1px;font-weight: bold">At - {{$created_at}}</span>
                        </h4>
                      </a>
                    </div>
                    @if($c=="1")
                      <div id="collapse{{$c}}" class="panel-collapse collapse in">
                        <div class="panel-body">{{$nt->notice_desc}}</div>
                      </div>
                    @else
                      <div id="collapse{{$c}}" class="panel-collapse collapse">
                        <div class="panel-body">{{$nt->notice_desc}}</div>
                      </div>
                    @endif
                  </div>
              @endif
              @endforeach
            @else
                <div class="panel panel-default">
                  <div class="panel-heading">
                      <h4 class="panel-title">No Notice Available Right Now.</h4>
                  </div>
                </div>
            @endif
          </div>
        </div>
    </div>
</div>