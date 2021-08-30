@extends('Admin.index')
@section('body')

<script src="{{URL::to('/')}}/public/assets/global/plugins/jquery.min.js"></script>

<div class="row">
    <div class="col-md-12">
       @include('error.msg')
        <div class="portlet box grey-cascade">
            <div class="portlet-title">
              <div class="caption">
                <i class="fa fa-globe"></i>User Priority Level 
              </div>
            
            </div>
            <div class="portlet-body">
              <div class="table-toolbar">
                <div class="row">

                <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{URL::to('user-priority-update')}}" name="basic_validate" id="basic_validate" novalidate="novalidate">
                {{ csrf_field() }}

                <div class="form-group">
                  <label class="col-md-2 control-label">
                    Priority Name: <span class="required">* </span>
                  </label>

                  <div class="col-md-8">
                     <select name="prrole_prid" id="prrole_prid" class="form-control" required onchange="getAppModuleView();">
                      <option value="null">Select A Priority</option>
                        @if(isset($priority) && count($priority)>0)
                          @foreach ($priority as $pr)
                            <option value="{{$pr->pr_id}}">{{$pr->pr_name}}</option>
                          @endforeach
                        @endif
                      </select>
                  </div>
                </div>
                
                <div class="form-group" id="appmodule_view">

                </div>

                    <div class="form-group">
                        <label  class="col-md-1 control-label">
                        </label>
                        <div class="col-md-8">
                          <button type="submit" class="btn btn-success"><i class="fa fa-save"></i>&nbsp;Update User Priority</button>
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
  function getAppModuleView() {
    $('#appmodule_view').html('<h4 style="text-align:center">Loading....</h4>');
    var pr_id=$('#prrole_prid').val();
    if(pr_id!="null"){
      $.ajax({
        url: "{{URL::to('getAppModuleView')}}/"+pr_id,
        type: 'GET',
        data: {},
        success:function(data) {
          $('#appmodule_view').html(data);
        }
      });
      
    }else{
      $('#appmodule_view').html('<h4 style="text-align:center;color:red">Please Select Priority Name</h4>');
    }
  }
</script>
@endsection