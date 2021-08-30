@extends('Admin.index')
@section('body')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<div class="row">
        <div class="col-md-12">
           @include('error.msg')
          <!-- BEGIN EXAMPLE TABLE PORTLET-->
          <div class="portlet box grey-cascade">
            <div class="portlet-title">
              <div class="caption">
                <i class="fa fa-globe"></i>Preview 
              </div>
              <div style="float:right">
                <a class="btn btn-xs btn-primary" href="{{URL::to('department-view')}}" style="margin-top:10px">View Department Information</a>
              </div>
            
            </div>
            <div class="portlet-body">
              <div class="table-toolbar">
                <div class="row">

              <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{URL::to('uploadToTable')}}" name="basic_validate" id="basic_validate" novalidate="novalidate">
                  {{ csrf_field() }}
                  <input type="hidden" name="module" value="{{$module}}">

                    <table data-sortable class="table table-hover table-striped table-bordered" id="sample_3">
                      <thead>
                        <tr>
                          @foreach($columns as $key => $value)
                          @if(isset($column_options[$value]))
                            <th>{!! $column_options[$value] !!}</th>
                          @endif
                          @endforeach
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($xls_datas as $xls_data)
                          <tr>
                            @foreach($columns as $key => $value)
                              <td>{!! $xls_data[$key] !!}</td>
                            @endforeach
                          </tr>
                        @endforeach
                      </tbody>
                    </table>

               <div class="form-group">
                <div class="col-md-12 text-center">
                  <input type="submit" value="Upload" class="btn btn-success">
                  <a href="{{url('upload')}}" class="btn btn-danger">Cancel</a>
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