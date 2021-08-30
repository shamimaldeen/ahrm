@extends('Admin.index')
@section('body')
<script src="{{URL::to('/')}}/public/assets/global/plugins/jquery.min.js"></script>
<div class="row">
  <div class="col-md-12">
    @include('error.msg')
    <div class="portlet box grey-cascade">
      
      <div class="portlet-title">
        <div class="caption">
          <i class="fa fa-globe"></i>Application for {{$application->job->name}}
        </div>
      </div>
      
      <div class="portlet-body">
        <div class="table-toolbar">
          <div class="row">

            <form class="form-horizontal" method="post" enctype="multipart/form-data" action="#" name="basic_validate" id="basic_validate" novalidate="novalidate">
            {{ csrf_field() }}
              <div class="col-md-12">
                  <div>
                      <p><strong>Cover Letter :</strong></p>
                      {!! $application->intro !!}
                  </div>

                  <div>
                      <p><strong>Applied by :</strong></p>
                      <p><a href="{{url('jobseeker')}}/{{$application->jobseeker->id}}" target="_blank">{{ $application->jobseeker->name }}</a></p>
                      @if($application->jobseeker->resume!="")
                      <p>Resume Link - <a href="{{url('public/jobseeker/resume')}}/{{$application->jobseeker->resume }}" target="_blank">{{$application->jobseeker->resume }}</a></p>
                      @endif
                  </div>

                  <div>
                      <p><strong>Job Details :</strong></p>
                      {!! $application->job->description !!}
                  </div>
                  <div>
                      <p><strong>Job Skills :</strong></p>
                      {!! $application->job->skills !!}
                  </div>
                  <div>
                      <p><strong>Who Can Apply :</strong></p>
                      {!! $application->job->who_apply !!}
                  </div>
                  <div>
                      <p><strong>Facilities :</strong></p>
                      {!! $application->job->offer !!}
                  </div>
                  <div>
                      <p><strong>Salary :</strong> {{$application->job->salary}}</p>
                      <p><strong>Experience :</strong> {{$application->job->experience}}</p>
                  </div>
                  <div>
                      <p>
                        <a class="btn btn-md btn-primary" onclick="Approve('{{$application->id}}')"><i class="fa fa-check"></i>&nbsp;Approve</a>
                        <a class="btn btn-md btn-danger" onclick="Reject('{{$application->id}}')"><i class="fa fa-close"></i>&nbsp;Reject</a>
                        <a class="btn btn-md btn-default" href="{{url('recruitment-applications')}}"><i class="fa fa-hand-o-left"></i>&nbsp;Go Back</a>
                      </p>
                  </div>
              </div>
            </form>

          </div>
        </div>
      </div>
        
    </div>
  </div>
</div>
<script type="text/javascript">
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
</script>
@endsection