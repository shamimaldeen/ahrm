@extends('Admin.index')
@section('body')

<script src="{{URL::to('/')}}/public/assets/global/plugins/jquery.min.js"></script>

<div class="row">
    <div class="col-md-12">
       @include('error.msg')
        <div class="portlet box grey-cascade">
            <div class="portlet-title">
              <div class="caption">
                <i class="fa fa-globe"></i>Create System User 
              </div>
              <div style="float:right">
                <a class="btn btn-xs btn-primary" href="{{URL::to('view-system-user')}}" style="margin-top:10px">View System User</a>
                <a class="btn btn-xs btn-primary" href="{{URL::to('add-new-employee')}}" style="margin-top:10px">Add New Employee</a>
              </div>
            
            </div>
            <div class="portlet-body">
              <div class="table-toolbar">
                <div class="row">

                <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{URL::to('create-syatem-user-submit')}}" name="basic_validate" id="basic_validate" novalidate="novalidate">
                {{ csrf_field() }}

                <div class="form-group">
                   <label class="col-md-2 control-label">
                    Department: <span class="required">* </span>
                  </label>

                  <div class="col-md-8">
                     <select name="emp_depart_id" id="emp_depart_id" class="form-control chosen" required onchange="getSubDepartment(),getEmployee();">
                      <option value="0">Select A Department</option>
                        @if(isset($department) && count($department)>0)
                          @foreach ($department as $depart)
                            <option value="{{$depart->depart_id}}">{{$depart->depart_name}}</option>
                          @endforeach
                        @endif
                      </select>
                      <a class="btn btn-xs btn-primary" href="{{URL::to('department-view/create')}}">Add New</a>
                  </div>
                </div>

                <div class="form-group">
                   <label class="col-md-2 control-label">
                      Sub-Department: <span class="required">* </span>
                    </label>

                  <div class="col-md-8">
                      <select name="emp_sdepart_id" id="emp_sdepart_id" class="form-control chosen" required onchange="getEmployee();">
                        <option value="0">Select Sub-Department</option>
                      </select> 
                      <a class="btn btn-xs btn-primary" href="{{URL::to('sub-department-view/create')}}">Add New</a> 
                  </div>
                </div>

                <div class="form-group">
                   <label class="col-md-2 control-label">
                    Designation: <span class="required">* </span>
                  </label>

                  <div class="col-md-8">
                     <select name="emp_desig_id" id="emp_desig_id" class="form-control chosen" required onchange="getEmployee();">
                      <option value="0">Select Designation</option> 
                        @if(isset($designation) && count($designation)>0)
                          @foreach ($designation as $desig)
                            <option value="{{$desig->desig_id}}">{{$desig->desig_name}}</option>
                          @endforeach
                        @endif
                      </select>
                      <a class="btn btn-xs btn-primary" href="{{URL::to('designation-view/create')}}">Add New</a>
                  </div>
                </div>

                <div class="form-group">
                   <label class="col-md-2 control-label">
                      Employee Name: <span class="required">* </span>
                    </label>

                  <div class="col-md-8">
                      <select name="suser_empid" id="emp_empid" class="form-control chosen" required onchange="getEmployeeEmail();">
                      <option value="0">Select Employee</option>                     
                      </select> 
                      <a class="btn btn-xs btn-primary" href="{{URL::to('add-new-employee')}}">Add New</a> 
                  </div>
                </div>

                <div class="form-group">
                   <label class="col-md-2 control-label">
                      Attendance Type: <span class="required">* </span>
                    </label>

                  <div class="col-md-8">
                    <input type="radio" name="suser_emptype" value="1" checked="checked" />&nbsp;&nbsp;Office Attendance&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="radio" name="suser_emptype" value="0"/>&nbsp;&nbsp;Remote Attendance&nbsp;&nbsp;&nbsp;&nbsp;
                  </div>
                </div>

                  <div class="form-group">

                  <div>
                   <label  class="col-md-2 control-label">
                    E-Mail : <span class="required">* </span>
                  </label>

                  <div class="col-md-8">
                    <input type="email" class="form-control span10" id="email" name="email" value="{{old('email')}}" required>
                  </div>
                   </div>
                    </div>


                    <div class="form-group">

                    <div>
                      <label  class="col-md-2 control-label">
                        Password : <span class="required">* </span>
                      </label>

                    <div class="col-md-8">
                      <input type="password" class="form-control span10" name="password" value="{{old('password')}}" required>
                    </div>
                    </div>
                    
                    </div>

                    <div class="form-group">

                  <div>
                   <label  class="col-md-2 control-label">
                    User Priority Level : <span class="required">* </span>
                  </label>

                  <div class="col-md-8">
                    @if($priority)
                    @foreach ($priority as $pr)
                      <input type="radio" name="suser_level" value="{{$pr->pr_id}}"/>&nbsp;&nbsp;{{$pr->pr_name}}&nbsp;&nbsp;&nbsp;&nbsp;
                    @endforeach
                    @endif
                    </div>
                   </div>
                    </div>

                    <div class="form-group">
                        <label  class="col-md-2 control-label">
                        </label>
                        <div class="col-md-8">
                          <input type="submit" value="Save" class="btn btn-success">
                        </div>
                    </div>

                </form>
              </div>

        </div>
        </div>
        </div>
        </div>
</div>
</div>
<script type="text/javascript">
function getSubDepartment () {
  var emp_depart_id=$('#emp_depart_id').val();
  if(emp_depart_id!="0"){
    $.ajax({
      url:"{{URL::to('getSubDepartment')}}/"+emp_depart_id,
      type:'GET',
      data:{},
      success:function(data) {
        $('#emp_sdepart_id').html(data).trigger("chosen:updated");
      }
    });
  }else{
    $('#emp_sdepart_id').html('').trigger("chosen:updated");
  }
}

function getEmployee () {
  var emp_depart_id=$('#emp_depart_id').val();
  var emp_sdepart_id=$('#emp_sdepart_id').val();
  if(emp_sdepart_id==null){
    emp_sdepart_id=0;
  }
  var emp_desig_id=$('#emp_desig_id').val();
  if(emp_depart_id!="0"){
    $.ajax({
      url:"{{URL::to('getEmployee')}}/"+emp_depart_id+'/'+emp_sdepart_id+'/'+emp_desig_id,
      type:'GET',
      data:{},
      success:function(data) {
        $('#emp_empid').html(data).trigger("chosen:updated");
      }
    });
  }else{
    $('#emp_empid').html('').trigger("chosen:updated");
  }
}

function getEmployeeEmail() {
  var emp_empid=$('#emp_empid').val();
  if(emp_empid!="0"){
    $.ajax({
      url: "{{URL::to('getEmployeeEmail')}}/"+emp_empid,
      type: 'GET',
      data: {},
      success:function(data) {
        $('#email').val(data);
      }
    });
  }else{
    $('#email').val('');
  }
}
</script>
@endsection