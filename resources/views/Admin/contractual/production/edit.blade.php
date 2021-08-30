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
                <i class="fa fa-globe"></i>Update Piece 
              </div>
              <div style="float:right">
                <a class="btn btn-xs btn-primary" href="{{url('piece')}}" style="margin-top:10px">View Pieces</a>
              </div>
            
            </div>
            <div class="portlet-body">
              <div class="table-toolbar">
                <div class="row">

              <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{route('piece.update',$piece->pi_id)}}" name="basic_validate" id="basic_validate" novalidate="novalidate">
              {{ method_field('PUT') }}
              {{ csrf_field() }}

              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                  Choose Style: <span class="required">* </span>
                </label>

                <div class="col-md-8">
                  <select name="pi_styleid" class="form-control chosen">
                    @if(isset($styles))
                    @foreach ($styles as $style)
                      <option value="{{$style->sty_id}}" @if($style->sty_id==$piece->pi_styleid) selected @endif>{{$style->sty_desc}}</option>
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
                  <input type="text" name="pi_name" class="form-control" value="{{old('pi_name',$piece->pi_name)}}">
                </div>

              </div>

              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                  Price per Dozen: <span class="required">* </span>
                </label>

                <div class="col-md-8">
                  <input type="text" name="pi_price_dz" class="form-control" value="{{old('pi_price_dz',$piece->pi_price_dz)}}">
                </div>

              </div>

              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                  Status: <span class="required">* </span>
                </label>

                <div class="col-md-8">
                  <input type="radio" name="pi_status" value="1" @if($piece->pi_status=="1") checked @endif>&nbsp;Active
                  &nbsp;<input type="radio" name="pi_status" value="0" @if($piece->pi_status=="0") checked @endif>&nbsp;Inactive
                </div>

              </div>
 

               <div class="form-group">

                <label class="col-md-2 control-label">
              
                </label>

                <div class="col-md-3">
                  <input type="submit" value="Update" class="btn btn-success">
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