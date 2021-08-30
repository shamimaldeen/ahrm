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
                <i class="fa fa-globe"></i>Edit Style 
              </div>
              <div style="float:right">
                <a class="btn btn-xs btn-primary" href="{{url('style')}}" style="margin-top:10px">View Styles</a>
              </div>
            
            </div>
            <div class="portlet-body">
              <div class="table-toolbar">
                <div class="row">

              <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{route('style.update',$style->sty_id)}}" name="basic_validate" id="basic_validate" novalidate="novalidate">
              {{ csrf_field() }}
              {{ method_field('PUT') }}

              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                  Style Name: <span class="required">* </span>
                </label>

                <div class="col-md-8">
                  <input type="text" class="form-control" name="sty_desc" value="{{old('sty_desc',$style->sty_desc)}}" required>
                </div>

              </div>

              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                  Status: <span class="required">* </span>
                </label>

                <div class="col-md-8">
                  <input type="radio" name="sty_status" value="1" @if($style->sty_status=="1") checked @endif>&nbsp;Active
                  &nbsp;<input type="radio" name="sty_status" value="0" @if($style->sty_status=="0") checked @endif>&nbsp;Inactive
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