@extends('Admin.index')
@section('body')
<script src="{{url('/')}}/public/assets/global/plugins/jquery.min.js"></script>
<div class="row">
        <div class="col-md-12">
           @include('error.msg')
          <!-- BEGIN EXAMPLE TABLE PORTLET-->
          <div class="portlet box grey-cascade">
            <div class="portlet-title">
              <div class="caption">
                <i class="fa fa-globe"></i>Add Piece 
              </div>
              <div style="float:right">
                <a class="btn btn-xs btn-primary" href="{{url('piece')}}" style="margin-top:10px">View Pieces</a>
              </div>
            
            </div>
            <div class="portlet-body">
              <div class="table-toolbar">
                <div class="row">

              <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{route('piece.store')}}" name="basic_validate" id="basic_validate" novalidate="novalidate">
              {{ csrf_field() }}

              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                  Choose Style: <span class="required">* </span>
                </label>

                <div class="col-md-8">
                  <select name="pi_styleid" class="form-control chosen">
                    @if(isset($styles))
                    @foreach ($styles as $style)
                      <option value="{{$style->sty_id}}">{{$style->sty_desc}}</option>
                    @endforeach
                    @endif
                  </select>
                </div>

              </div>

              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                  Piece Name: <span class="required">* </span>
                </label>

                <div class="col-md-8">
                  <input type="text" name="pi_name" class="form-control" value="{{old('pi_name')}}">
                </div>

              </div>

              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                  Price per Dozen: <span class="required">* </span>
                </label>

                <div class="col-md-8">
                  <input type="text" name="pi_price_dz" class="form-control" value="{{old('pi_price_dz')}}">
                </div>

              </div>
 

               <div class="form-group">

                <label class="col-md-2 control-label">
              
                </label>

                <div class="col-md-3">
                  <input type="submit" value="Save" class="btn btn-success">
                </div>                

              </div>
         

                    </div>          




                      </div>



                    </div>





                  </div>



                </div>



              </div>



            </div>



          </form>

                  </div>
                  
                </div>
              </div>
              
            </div>
          </div>
          <!-- END EXAMPLE TABLE PORTLET-->
        </div>
      </div>
        </div>
      </div>

@endsection