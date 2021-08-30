@extends('Admin.index')
@section('body')
<script src="{{URL::to('/')}}/public/assets/global/plugins/jquery.min.js"></script>

<div class="row">
        <div class="col-md-12">
           @include('error.msg')
          <!-- BEGIN EXAMPLE TABLE PORTLET-->
          <div class="portlet box grey-cascade">
            <div class="portlet-title">
              <div class="caption">
                <i class="fa fa-globe"></i>Add App Module 
              </div>
              <div style="float:right">
                <a class="btn btn-xs btn-primary" href="{{URL::to('appmodule-view')}}" style="margin-top:10px">View App Module</a>
              </div>
            
            </div>
            <div class="portlet-body">
              <div class="table-toolbar">
                <div class="row">

              <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{URL::to('appmoduleadd')}}" name="basic_validate" id="basic_validate" novalidate="novalidate">
                  {{ csrf_field() }}
              
              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                  Main Menu Name: <span class="required">* </span>
                </label>

                <div class="col-md-4">
                   <select name="appm_menuid" id="appm_menuid" class="form-control" required onchange="getSubMenu();">
                      <option value="0">Select MainMenu</option>
                      @if(isset($mainmenu) && count($mainmenu)>0)
                      @foreach ($mainmenu as $mn)
                         <option value="{{$mn->id}}">{{$mn->Link_Name}}</option>
                      @endforeach
                      @endif
                    </select>
                </div>

              </div>
              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                  Sub-Menu Name: <span class="required">* </span>
                </label>

                <div class="col-md-4">
                   <select name="appm_submenuid" id="appm_submenuid" class="form-control col-md-6" required>
                      <option value="0">Select SubMenu</option>
                    </select>
                </div>

              </div>

              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                  App Module Name : <span class="required">* </span>
                </label>

                <div class="col-md-4">
                   <input type="text" class='form-control' placeholder="App Module Name" name="appm_name" id="appm_name" value="{{old('appm_name')}}">
                </div>

              </div>

              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                 
                </label>

                <div class="col-md-4">
                    <input type="submit" value="Save" class='btn btn-sm- btn-primary'>
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
    <script>
      function getSubMenu() {
        var appm_menuid=$('#appm_menuid').val();
        if(appm_menuid!="0"){
          $.ajax({
            url: "{{URL::to('getSubMenu')}}/"+appm_menuid,
            type: 'GET',
            data: {},
            success:function(data) {
              $('#appm_submenuid').html(data);
            }
          });
          
        }else{
           $('#appm_submenuid').html('<option value="0">Select Sub-Menu</option>');
        }
      }
    </script>
@endsection