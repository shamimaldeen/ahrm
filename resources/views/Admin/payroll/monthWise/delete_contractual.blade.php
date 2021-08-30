@extends('Admin.index')
@section('body')

<script src="{{URL::to('/')}}/public/assets/global/plugins/jquery.min.js"></script>

<div class="row">
  <div class="col-md-12">
     @include('error.msg')
      <!-- BEGIN EXAMPLE TABLE PORTLET-->
      <div class="portlet light bordered">
          <div class="portlet-title">
              <div class="caption font-dark">
                  <i class="icon-settings font-dark"></i>
                  <span class="caption-subject bold uppercase text-danger"> Delete Contractual Payroll</span>
              </div>
          </div>
          <div class="portlet-body">
            <!-- filter start -->
            <div class="row" style='padding: 30px; background-color: #fff; margin: 0px;'>
              <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{ route('contractual-delete-submit') }}" name="basic_validate" id="basic_validate" novalidate="novalidate">
              {{ csrf_field() }}
                           <input type="hidden" name="date" value="{{$date}}">
              <div class="form-group">
                <h4 class="caption-subject bold text-danger text-center">
                  
                </h4>
                <hr>
                <h4 class="text-danger text-center">
                  Do You Want to Delete Payroll For This Month?
                  <br>
                  <br>
                  <input type="submit" value="Delete" class="btn btn-md btn-danger">
                  <a class="btn btn-md btn-default" href="{{url('generate-contractual-payroll')}}">Not Now</a>
                </h4>
              </div>
              
              </form>
            </div>
          </div>
      </div>
  </div>
</div>
@endsection