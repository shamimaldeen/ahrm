@extends('Admin.index')
@section('body')
<script src="{{URL::to('/')}}/public/assets/global/plugins/jquery.min.js"></script>
<div class="row">
  <div class="col-md-12">
    @include('error.msg')
    <div class="portlet box grey-cascade">
      
      <div class="portlet-title">
        <div class="caption">
          <i class="fa fa-globe"></i>Job Applications
        </div>
      </div>
      
      <div class="portlet-body">
        <div class="table-toolbar">
          <div class="row">

            <form class="form-horizontal" method="post" enctype="multipart/form-data" action="#" name="basic_validate" id="basic_validate" novalidate="novalidate">
            {{ csrf_field() }}
              <div class="col-md-12">
                <table class="table table-bordered table-striped table-hover" id="sample_3">
                  <thead>
                    <tr>
                      <th>SL</th>
                      <th>Name</th>
                      <th>Salary</th>
                      <th>Experience</th>
                      <th>Jobseeker</th>
                      <th>Jobseeker Resume</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    @if(isset($applications[0]))
                      @foreach ($applications as $key => $application)
                        <tr>
                          <td>{{$key+1}}</td>
                          <td>{{$application->job->name}}</td>
                          <td>{{$application->job->salary}}</td>
                          <td>{{$application->job->experience}}</td>
                          <td>
                            <a href="{{url('jobseeker')}}/{{$application->jobseeker->id}}" target="_blank">{{$application->jobseeker->name}}
                            </a>
                          </td>
                          <td>
                            @if($application->jobseeker->resume!="")
                            <a href="{{url('public/jobseeker/resume')}}/{{$application->jobseeker->resume}}" target="_blank">{{$application->jobseeker->resume}}</a>
                            @endif
                          </td>
                          <td>
                            <a class="btn btn-xs btn-success" href="{{url('recruitment')}}/{{$application->id}}"><i class="fa fa-search"></i></a>
                            <a class="btn btn-xs btn-primary" onclick="Approve('{{$application->id}}')"><i class="fa fa-check"></i></a>
                            <a class="btn btn-xs btn-danger" onclick="Reject('{{$application->id}}')"><i class="fa fa-trash"></i></a>
                          </td>
                        </tr>
                      @endforeach
                    @endif
                  </tbody>
                </table>
              </div>
            </form>

          </div>
        </div>
      </div>
        
    </div>
  </div>
</div>
<script type="text/javascript">
  function Delete(link) {
    $.ajax({
      headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' },
      url: link,
      type: 'DELETE', 
      success: function(response){
        if(response.success){ 
          location.reload();
        }
      } 
    });
  }

  function Approve(application_id) {
    $.confirm({
        title: 'Approve!',
        type:'green',
        columnClass: 'col-md-6 col-md-offset-3',
        content: '' +
        '<form action="" class="formName">' +
        '<div class="form-group">' +
        '<label>Write the reason of acception...</label>' +
        '<textarea class="feedback form-control" rows="10" required></textarea>' +
        '</div>' +
        '<div class="form-group">' +
        '<label>Write The Employee ID</label>' +
        '<input type="text" class="emp_empid form-control">' +
        '</div>' +
        '<div class="form-group">' +
        '<label>Write The Machine ID For Employee</label>' +
        '<input type="text" class="emp_machineid form-control">' +
        '</div>' +
        '</form>',
        buttons: {
            formSubmit: {
                text: 'Approve',
                btnClass: 'btn-green',
                action: function () {
                    var feedback = this.$content.find('.feedback').val();
                    var emp_empid = this.$content.find('.emp_empid').val();
                    var emp_machineid = this.$content.find('.emp_machineid').val();
                    if(!feedback || !emp_empid || !emp_machineid){
                        $.alert({
                          title:'Whoops!',
                          type:'red',
                          content:'<hr><strong class="text-danger">Please Write the reason of acception, Employeee ID & Machine ID.</strong><hr>'
                        });
                        return false;
                    }else{
                      $.ajax({
                        headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' },
                        url: "{{url('recruitment-approve')}}",
                        type: 'POST',
                        dataType: 'json',
                        data: {application_id:application_id,feedback:feedback,emp_empid:emp_empid,emp_machineid:emp_machineid},
                        success:function(response) {
                          if(response.success){
                            location.reload();
                          }else{
                            $.alert({
                              title:'Whoops!',
                              type:'red',
                              content:'<hr><strong class="text-danger">'+response.msg+'</strong><hr>'
                            });
                            return false;
                          }
                        }
                      })
                      .fail(function() {
                        $.alert({
                          title:'Whoops!',
                          type:'red',
                          content:'<hr><strong class="text-danger">Something went wrong!</strong><hr>'
                        });
                        return false;
                      });
                      
                    }
                }
            },
            cancel: function () {
                //close
            },
        }
    });
  }

  function Reject(application_id) {
    $.confirm({
        title: 'Reject!',
        type:'red',
        columnClass: 'col-md-6 col-md-offset-3',
        content: '' +
        '<form action="" class="formName">' +
        '<div class="form-group">' +
        '<label>Write the reason of rejection...</label>' +
        '<textarea class="feedback form-control" rows="10" required></textarea>' +
        '</div>' +
        '</form>',
        buttons: {
            formSubmit: {
                text: 'Reject',
                btnClass: 'btn-red',
                action: function () {
                    var feedback = this.$content.find('.feedback').val();
                    if(!feedback){
                        $.alert({
                          title:'Whoops!',
                          type:'red',
                          content:'<hr><strong class="text-danger">Please Write the reason of rejection.</strong><hr>'
                        });
                        return false;
                    }else{
                      $.ajax({
                        headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' },
                        url: "{{url('recruitment-reject')}}",
                        type: 'POST',
                        dataType: 'json',
                        data: {application_id:application_id,feedback:feedback},
                        success:function(response) {
                          if(response.success){
                            location.reload();
                          }else{
                            $.alert({
                              title:'Whoops!',
                              type:'red',
                              content:'<hr><strong class="text-danger">'+response.msg+'</strong><hr>'
                            });
                            return false;
                          }
                        }
                      })
                      .fail(function() {
                        $.alert({
                          title:'Whoops!',
                          type:'red',
                          content:'<hr><strong class="text-danger">Something went wrong!</strong><hr>'
                        });
                        return false;
                      });
                      
                    }
                }
            },
            cancel: function () {
                //close
            },
        }
    });
  }
</script>
@endsection