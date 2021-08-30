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
                        <i class="fa fa-globe"></i>Edit Country Information
                    </div>
                    <div style="float:right">
                        <a class="btn btn-xs btn-primary" href="{{url('country')}}" style="margin-top:10px">Go Back</a>
                    </div>

                </div>
                <div class="portlet-body">
                    <div class="table-toolbar">
                        <div class="row">

                            <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{route('country.update',$countries->id)}}" name="basic_validate" id="basic_validate" novalidate="novalidate">
                                {{ method_field('PUT') }}
                                {{ csrf_field() }}


                                <div class="form-group">

                                    <label class="col-md-2 control-label">
                                        Country Name: <span class="required">* </span>
                                    </label>

                                    <div class="col-md-8">
                                        <input type="text" class="form-control" name="country_name" value="{{ $countries->country_name }}" required>
                                    </div>

                                </div>

                                <div class="form-group">

                                    <label class="col-md-2 control-label">
                                        Country Code: <span class="required">* </span>
                                    </label>

                                    <div class="col-md-8">
                                        <input type="text" class="form-control" name="country_code" value="{{ $countries->country_code }}" required>
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