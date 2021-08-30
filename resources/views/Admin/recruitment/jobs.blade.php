@extends('Admin.index')
@section('body')
<script src="{{URL::to('/')}}/public/assets/global/plugins/jquery.min.js"></script>
<div class="row">
  <div class="col-md-12">
    @include('error.msg')
    <div class="portlet box grey-cascade">
      
      <div class="portlet-title">
        <div class="caption">
          <i class="fa fa-globe"></i>List of Job
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
                      <th>Closing Date</th>
                      <th>Status</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    @if(isset($jobs[0]))
                      @foreach ($jobs as $key => $job)
                        <tr>
                          <td>{{$key+1}}</td>
                          <td>{{$job->name}}</td>
                          <td>{{$job->salary}}</td>
                          <td>{{$job->experience}}</td>
                          <td>{{$job->closing_date}}</td>
                          <td>
                            @if($job->inactive=="0")
                            <a class="btn btn-xs btn-success">Active</a>
                            @else
                            <a class="btn btn-xs btn-danger">InActive</a>
                            @endif
                          </td>
                          <td>
                            <a class="btn btn-xs btn-primary" href="{{url('recruitment')}}/{{$job->id}}/edit"><i class="fa fa-edit"></i></a>
                            <a class="btn btn-xs btn-danger" onclick="Delete('{{url('recruitment')}}/{{$job->id}}')"><i class="fa fa-trash"></i></a>
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
</script>
@endsection