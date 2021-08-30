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
            <span class="caption-subject bold uppercase" id="hidden_table_title">Provident Fund Exist!</span>
        </div>
        
        <div class="actions">
          <a href="{{url('provident-fund/generate/fund')}}" class="btn btn-success btn-sm">Go Back</a>
        </div>
      </div>
      
      <div class="portlet-body">
        <div class="table-toolbar">
          <div class="row">
              <h4 class="text-danger">
                <strong>
                  A generated provident fund calculation already exist!If you want to generate it again you have to delete the previous calculaion.
                  <br>
                  <br>
                <center>
                  Are you sure to delete the prevoiusly generated provident fund calculation for {{$Month->month}} of {{$year}} ?
                </center>
                </strong>
                <br>
                <center>
                  <a href="{{url('provident-fund')}}/{{$Month->id}}/{{$year}}/delete" class="btn btn-sm btn-danger">Delete</a>
                  <a href="{{url('provident-fund/generate/fund')}}" class="btn btn-sm btn-default">Cancel</a>
                </center>
              </h4>
          </div> 
        </div> 
      </div> 

    </div>
    
  </div>
</div>
@endsection