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
                <i class="fa fa-globe"></i>Assign HOD for {{$department->depart_name}}
              </div>
              <div style="float:right">
                <a class="btn btn-xs btn-primary" href="{{URL::to('department-view')}}" style="margin-top:10px">View Department Information</a>
              </div>
            
            </div>
            <div class="portlet-body">
              <div class="table-toolbar">
                <div class="row">

              <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{URL::to('department-view')}}/{{$depart_id}}/assignHOD" name="basic_validate" id="basic_validate" novalidate="novalidate">
                  {{ csrf_field() }}
                           
              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                  HOD: <span class="required">* </span>
                </label>

                <div class="col-md-8">
                  <select name="hod_empid" class="form-control chosen">
                    @if(isset($employe))
                    @foreach ($employe as $value)
                      <option value="{{$value->emp_id}}" @if(isset($data) && $data->hod_empid==$value->emp_id) selected @endif>{{$value->emp_name}} ({{$value->emp_empid}})</option>
                    @endforeach
                    @endif
                  </select>
                </div>

              </div>

              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                  Superior HOD: <span class="required">* </span>
                </label>

                <div class="col-md-8">
                  <select name="hod_superior" class="form-control chosen">
                    @if(!isset($data) or $data->hod_superior=="0")
                    <option value="0">Will Be Assiged Later</option>
                    @endif

                    @if(isset($HOD))
                    @foreach ($HOD as $value)
                      <option value="{{$value->hod_empid}}" @if(isset($data) && $data->hod_superior==$value->hod_empid) selected @endif>{{$value->emp_name}} ({{$value->emp_empid}})</option>
                    @endforeach
                    @endif
                  </select>
                </div>

              </div>

              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                  Note: <span class="required">* </span>
                </label>

                <div class="col-md-8">
                  <textarea name="hod_note" class="form-control" rows="5">@php if(isset($data)){ $data->hod_note; } @endphp</textarea>
                </div>

              </div>
 

               <div class="form-group">

                <label class="col-md-2 control-label">
              
                </label>

                <div class="col-md-3">
                  <input type="submit" value="Assign HOD" class="btn btn-success">
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