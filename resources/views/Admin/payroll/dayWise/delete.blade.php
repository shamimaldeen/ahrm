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
                  <span class="caption-subject bold uppercase text-danger"> Delete Payroll</span>
              </div>
          </div>
          <div class="portlet-body">
            <!-- filter start -->
            <div class="row" style='padding: 30px; background-color: #fff; margin: 0px;'>
              <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{url('generate-day-wise-payroll')}}/{{$data}}/delete" name="basic_validate" id="basic_validate" novalidate="novalidate">
              {{ csrf_field() }}
                           
              <div class="form-group">
                <h4 class="caption-subject bold text-danger text-center">
                  Warning! A Payroll already been generated from <strong style="text-decoration: underline">{{explode('&',$data)[1]}}</strong> to <strong style="text-decoration: underline">{{explode('&',$data)[2]}}</strong>. If you want to generate it again, you have to delete the previous one.
                </h4>
                <hr>
                <h4 class="text-danger text-center">
                  Are you sure to delete payroll from <strong style="text-decoration: underline">{{explode('&',$data)[1]}}</strong> to <strong style="text-decoration: underline">{{explode('&',$data)[2]}}</strong>?
                  <br>
                  <br>
                  <input type="submit" value="Delete" class="btn btn-md btn-danger">
                  <a class="btn btn-md btn-default" href="{{url('generate-day-wise-payroll')}}">Not Now</a>
                </h4>
              </div>
              
              </form>
            </div>
          </div>
      </div>
  </div>
</div>
@endsection