@php
use App\Http\Controllers\BackEndCon\Controller;
@endphp

<style type="text/css">
  .labelcol{
    background-color: #40a7f9;
    padding: 7px;
    color: white;
    font-size: 13px;    
  }
  .rowme{
    padding: 25px;
    background-color: #f9f9f9;
    margin: 20px 15px 0px 15px;
  }
</style>

<div class="row rowme">
    <div class="col-md-2 col-md-offset-0 col-xs-offset-2 col-sm-offset-4">
      @if($employee->emp_imgext!="")
        <img style="height: 150px;border: 1px solid black;padding: 5px;margin: auto" src="{{URL::to('public/EmployeeImage')}}/{{$employee->emp_id}}.{{$employee->emp_imgext}}">
      @else
        <img  style="height: 150px;border: 1px solid black;padding: 5px;margin: auto" src="{{URL::to('public')}}/male.jpg"/>
      @endif
    </div>

    <br>

    <div class="col-md-10">
      <div class="col-md-6">
        <form class="form-horizontal">
            <div class="form-group">                  
              <label class="col-md-4 col-xs-5 control-label labelcol">
                <span class="labelcol">Name:</span> 
              </label>

              <label class="col-md-8 col-xs-7 control-label" style="text-align: left">
                {{$employee->emp_name}}
              </label>
            </div>
        </form>
      </div>

      <div class="col-md-6">
        <form class="form-horizontal">
            <div class="form-group">                    
              <label class="col-md-4 col-xs-5 control-label labelcol">
                <span class="labelcol">ID:</span> 
              </label>

              <label class="col-md-8 col-xs-7 control-label" style="text-align: left">
                {{$employee->emp_empid}}
              </label>
            </div>
        </form>
      </div>

      <div class="col-md-6">
        <form class="form-horizontal">
            <div class="form-group">                  
              <label class="col-md-4 col-xs-5 control-label labelcol">
                <span class="labelcol">Department:</span> 
              </label>

              <label class="col-md-8 col-xs-7 control-label" style="text-align: left">
                @php echo Controller::getDepartmentName($employee->emp_depart_id); @endphp
              </label>
            </div>
        </form>
      </div>

      <div class="col-md-6">
        <form class="form-horizontal">
            <div class="form-group">                    
              <label class="col-md-4 col-xs-5 control-label labelcol">
                <span class="labelcol">Designation:</span> 
              </label>

              <label class="col-md-8 col-xs-7 control-label" style="text-align: left">
                @php echo Controller::getDesignationName($employee->emp_desig_id); @endphp
              </label>
            </div>
        </form>
      </div>

      <div class="col-md-6">
        <form class="form-horizontal">
            <div class="form-group">                  
              <label class="col-md-4 col-xs-5 control-label labelcol">
                <span class="labelcol">Phone:</span> 
              </label>

              <label class="col-md-8 col-xs-7 control-label" style="text-align: left">
                {{$employee->emp_phone}}
              </label>
            </div>
        </form>
      </div>

      <div class="col-md-6">
        <form class="form-horizontal">
            <div class="form-group">                    
              <label class="col-md-4 col-xs-5 control-label labelcol">
                <span class="labelcol">Email:</span> 
              </label>

              <label class="col-md-8 col-xs-7 control-label" style="text-align: left">
                {{$employee->emp_email}}
              </label>
            </div>
        </form>
      </div>

       <div class="col-md-6">
        <form class="form-horizontal">
            <div class="form-group">                    
              <label class="col-md-4 col-xs-5 control-label labelcol">
                <span class="labelcol">Attendance:</span> 
              </label>

              <label class="col-md-8 col-xs-7 control-label" style="text-align: left">
                @if($id->suser_emptype==1)
                Office Attendance
                @else
                Remote Attendance
                @endif
              </label>
            </div>
        </form>
      </div>

      <div class="col-md-6">
        <form class="form-horizontal">
            <div class="form-group">                    
              <label class="col-md-4 col-xs-5 control-label labelcol" style="background-color: #f44336;">
                <span class="labelcol" style="background-color: #f44336;">Pending Leave:</span> 
              </label>

              <label class="col-md-8 col-xs-7 control-label" style="text-align: left">
                @if($leave_notification)
                <a href="{{URL::to('pending-leave-application')}}">{{$leave_notification}}</a>
                
                @endif
              </label>
            </div>
        </form>
      </div>


      <div class="col-md-6">
        <form class="form-horizontal">
            <div class="form-group">                    
              <label class="col-md-4 col-xs-5 control-label labelcol" style="background-color: #f44336;">
                <span class="labelcol" style="background-color: #f44336;">Pending Loan:</span> 
              </label>

              <label class="col-md-8 col-xs-7 control-label" style="text-align: left">
                @if($loan_notification)
                <a href="{{URL::to('loans')}}">{{$loan_notification}}</a>
                
                @endif
              </label>
            </div>
        </form>
      </div>

      <div class="col-md-6">
        <form class="form-horizontal">
            <div class="form-group">                    
              <label class="col-md-4 col-xs-5 control-label labelcol" style="background-color: #f44336;">
                <span class="labelcol" style="background-color: #f44336;">Pending OSD:</span> 
              </label>

              <label class="col-md-8 col-xs-7 control-label" style="text-align: left">
                @if($osd_notification)
                <a href="{{URL::to('pending-osd-attendance')}}">{{$osd_notification}}</a>
                
                @endif
              </label>
            </div>
        </form>
      </div>

     
    </div>
               
</div>

<div class="row" style="text-align: center; padding: 10px;">
  @if(Controller::checkLinkPriority('ot')=="1")
    <div class="col-md-3 col-sm-6 col-xs-12">
      <a style="margin: 5px" href="{{URL::to('ot')}}" class="btn btn-primary btn-lg btn-circle">OT Status</a>
    </div>
  @endif

  @if(Controller::checkLinkPriority('ot-application')=="1")
    <div class="col-md-3 col-sm-6 col-xs-12">
      <a style="margin: 5px" href="{{URL::to('ot-application')}}" class="btn btn-primary btn-lg btn-circle">OT Application Status</a>
    </div>
  @endif

  @if(Controller::checkLinkPriority('leave-data')=="1")
    <div class="col-md-3 col-sm-6 col-xs-12">
      <a style="margin: 5px" href="{{URL::to('leave-data')}}" class="btn btn-primary btn-lg btn-circle">Leave Status</a>
    </div>
  @endif

  @if(Controller::checkLinkPriority('leave-application')=="1")
    <div class="col-md-3 col-sm-6 col-xs-12">
      <a style="margin: 5px" href="{{URL::to('leave-application')}}" class="btn btn-primary btn-lg btn-circle">Leave Application Status</a>
    </div>
  @endif
</div>