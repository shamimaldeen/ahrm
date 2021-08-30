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
                <i class="fa fa-globe"></i>Edit Salary Head
              </div>
              <div style="float:right">
                <a class="btn btn-xs btn-primary" href="{{url('salary-head')}}" style="margin-top:10px">View Salary Heads</a>
              </div>
            
            </div>
            <div class="portlet-body">
              <div class="table-toolbar">
                <div class="row">

              <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{route('salary-head.update',$head->head_id)}}" name="basic_validate" id="basic_validate" novalidate="novalidate">
              {{ method_field('PUT') }}
              {{ csrf_field() }}
                           
              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                  Salary Head Name: <span class="required">* </span>
                </label>

                <div class="col-md-8">
                  <input type="text" class="form-control" name="head_name" value="{{old('head_name',$head->head_name)}}" required>
                </div>

              </div>

              <div class="form-group" hidden="">
                  
                <label class="col-md-2 control-label">
                  Salary Head Type: <span class="required">* </span>
                </label>

                <div class="col-md-8">
                    <input type="radio" name="head_type" value="1" @if($head->head_type=="1") checked @endif>&nbsp;Addition
                    &nbsp;&nbsp;
                    <input type="radio" name="head_type" value="0" @if($head->head_type=="0") checked @endif>&nbsp;Deduction
                </div>

              </div>


               <div class="form-group" hidden="">
                  
                <label class="col-md-2 control-label">
                  Other Head Type: <span class="required">* </span>
                </label>

                <div class="col-md-8">
                    <input type="radio" name="other_head_type" value="1" @if($head->other_head_type=="1") checked @endif>&nbsp;On
                    &nbsp;&nbsp;
                    <input type="radio" name="other_head_type" value="0" @if($head->other_head_type=="0") checked @endif>&nbsp;Off
                </div>

              </div>

              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                  Percentage of Basic Salary: <span class="required">* </span>
                </label>

                <div class="col-md-8">
                    <input type="text" name="head_percentage" value="{{old('head_percentage',$head->head_percentage)}}" class="form-control">
                </div>

              </div>


              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                  Taxable: <span class="required">* </span>
                </label>

                <div class="col-md-8">
                  <select name="head_taxable" id="head_taxable" class="form-control chosen" onchange="toggoleExempt()">
                    <option value="1" @if($head->head_taxable=="1") selected @endif>Yes</option>
                    <option value="0" @if($head->head_taxable=="0") selected @endif>No</option>
                  </select>
                </div>

              </div>

              <div class="form-group" id="head_taxexempt">
                  
                <label class="col-md-2 control-label">
                  Tax Exemption Amount (Yearly): <span class="required">* </span>
                </label>

                <div class="col-md-8">
                  <input type="number" name="head_taxexempt" value="{{$head->head_taxexempt}}" min="0" class="form-control">
                </div>

              </div>

              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                  Salary Head Note: <span class="required">* </span>
                </label>

                <div class="col-md-8">
                  <textarea name="head_note" rows="5" class="form-control">{{old('head_note',$head->head_note)}}</textarea>
                </div>

              </div>

              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                  Salary Head Status: <span class="required">* </span>
                </label>

                <div class="col-md-8">
                    <input type="radio" name="head_status" value="1" @if($head->head_status=="1") checked @endif>&nbsp;Active
                    &nbsp;&nbsp;
                    <input type="radio" name="head_status" value="0" @if($head->head_status=="0") checked @endif>&nbsp;Inactive
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
<script type="text/javascript">
  toggoleExempt();
  function toggoleExempt() {
    if($('#head_taxable').val()=="1"){
      $('#head_taxexempt').show();
    }else{
      $('#head_taxexempt').hide();
    }
  }
</script>
@endsection