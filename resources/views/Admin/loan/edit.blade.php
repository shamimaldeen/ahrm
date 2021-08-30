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
                <i class="fa fa-globe"></i>Edit Pending Loan Application 
              </div>
              <div style="float:right">
                <a class="btn btn-xs btn-primary" href="{{url('loans')}}" style="margin-top:10px">View Loans</a>
              </div>
            
            </div>
            <div class="portlet-body">
              <div class="table-toolbar">
                <div class="row">

              <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{route('loans.update',$loan->id)}}" name="basic_validate" id="basic_validate" novalidate="novalidate">
              {{ method_field('PUT') }}
              {{ csrf_field() }}
              
              <input type="hidden" name="emp_id" value="{{$id->suser_empid}}">
              <input type="hidden" name="year" value="{{date('Y')}}">
              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                  Purpose: <span class="required">* </span>
                </label>

                <div class="col-md-8">
                  <textarea name="purpose" rows="5" class="form-control">{{old('purpose',$loan->purpose)}}</textarea>
                </div>

              </div>
              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                  Loan Amount: <span class="required">* </span>
                </label>

                <div class="col-md-8">
                  <input type="number" name="amount" min="0" value="{{old('amount',$loan->amount)}}" class="form-control">
                </div>

              </div>
              <div class="form-group">
                  
                <label class="col-md-2 control-label">
                  Choose Month: <span class="required">* </span>
                </label>

                <div class="col-md-8">
                  <select name="month" class="form-control chosen">
                    @if(isset($month[0]))
                    @foreach ($month as $mn)
                      <option value="{{$mn->id}}" @if($loan->month==$mn->id) selected @endif>{{$mn->month}}</option>
                    @endforeach
                    @endif
                  </select>
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