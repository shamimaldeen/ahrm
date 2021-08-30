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
                <i class="fa fa-globe"></i>Provident Fund Sentup 
              </div>
            
            </div>
            <div class="portlet-body">
              <div class="table-toolbar">
                <div class="row">

              <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{route('provident-fund-setup.store')}}" name="basic_validate" id="basic_validate" novalidate="novalidate">
              {{ csrf_field() }}

              <div class="form-group">
                  
                <label class="col-md-3 control-label">
                  Provident Fund Status: <span class="required">* </span>
                </label>

                <div class="col-md-8">
                  <select name="provident_fund" id="provident_fund" class="form-control chosen">
                    <option value="1" @if(isset($ProvidentFundSetup) && $ProvidentFundSetup->provident_fund=="1") selected @endif>Active</option>
                    <option value="0" @if(isset($ProvidentFundSetup) && $ProvidentFundSetup->provident_fund=="0") selected @endif>DeActive</option>
                  </select>
                </div>

              </div>
              
              <div id="percentage_view">
                
              <div class="form-group">
                  
                <label class="col-md-3 control-label">
                  Employee Percentage of Basic Allowance: <span class="required">* </span>
                </label>

                <div class="col-md-8">
                  <input type="text" name="employee_percentage" value="{{(($ProvidentFundSetup) ? $ProvidentFundSetup->employee_percentage : '' )}}" class="form-control">
                </div>

              </div>

              <div class="form-group">
                  
                <label class="col-md-3 control-label">
                  Company Percentage of Basic Allowance: <span class="required">* </span>
                </label>

                <div class="col-md-8">
                  <input type="text" name="company_percentage" value="{{(($ProvidentFundSetup) ? $ProvidentFundSetup->company_percentage : '' )}}" class="form-control">
                </div>

              </div>

              </div>
 

               <div class="form-group">

                <label class="col-md-3 control-label">
              
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