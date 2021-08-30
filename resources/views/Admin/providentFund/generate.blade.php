@extends('Admin.index')
@section('body')
@php
use App\Http\Controllers\BackEndCon\Controller as C;
@endphp
<script src="{{url('/')}}/public/assets/global/plugins/jquery.min.js"></script>

<div class="row">
  <div class="col-md-12">
   @include('error.msg')
    
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
            <i class="icon-settings font-dark"></i>
            <span class="caption-subject bold uppercase" id="hidden_table_title">Generate Provident Fund</span>
        </div>
        
        <div class="actions">
          <a href="{{url('provident-fund')}}" class="btn btn-success btn-sm">Go Back</a>
        </div>
      </div>
      
      <div class="portlet-body">
        <div class="table-toolbar">
          <div class="row">
            <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{url('provident-fund/generate/fund')}}" name="basic_validate" id="basic_validate" novalidate="novalidate">
            {{ csrf_field() }}
              <div class="form-group">
                <label class="col-md-2 control-label">
                  Choose Month: <span class="required">* </span>
                </label>
                <div class="col-md-8">
                  <select name="month" class="form-control chosen">
                    @if(isset($Month[0]))
                      @foreach($Month as $m)
                      <option value="{{$m->id}}" @if($m->id==date('m')) selected @endif>{{$m->month}}</option>
                      @endforeach
                    @endif
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label class="col-md-2 control-label">
                  Goal Year: <span class="required">* </span>
                </label>
                <div class="col-md-8">
                  <select name="year" class="form-control chosen">
                    @for($year=2000;$year<=2050;$year++)
                    <option @if($year==date('Y')) selected @endif>{{$year}}</option>
                    @endfor
                  </select>
                </div>
              </div>
       
              <div class="form-group">
                <div class="col-md-3 col-md-offset-2">
                  <input type="submit" value="Generate Provident Fund" class="btn btn-success">
                </div>                
              </div>
            </form>
        </div> 
      </div> 
    </div> 

    </div>
    
  </div>
</div>
@endsection