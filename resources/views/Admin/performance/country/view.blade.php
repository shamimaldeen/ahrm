@extends('Admin.index')
@section('body')
    <script src="{{url('/')}}/public/assets/global/plugins/jquery.min.js"></script>

    <div class="row">

        <div class="col-md-12">
        @include('error.msg')

        <!-- BEGIN EXAMPLE TABLE PORTLET-->
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption font-dark">
                        <i class="icon-settings font-dark"></i>
                        <span class="caption-subject bold uppercase" id="hidden_table_title">Country Information</span>
                    </div>

                    <div class="col-md-6"><p id="confirm_msg"></p></div>
                    <div class="actions">
                        <div class="btn-group">
                            <a class="btn purple btn-outline btn-circle" href="javascript:;" data-toggle="dropdown">
                                <i class="fa fa-list"></i>
                                <span> Options </span>
                                <i class="fa fa-angle-down"></i>
                            </a>
                            <ul class="dropdown-menu" id="sample_3_tools">
                                <li>
                                    <a href="{{url('country/create')}}">
                                        <i class="fa fa-plus"></i> Add New Country</a>
                                </li>
                            </ul>
                        </div>
                        <div class="btn-group">
                            <a class="btn red btn-outline btn-circle" href="javascript:;" data-toggle="dropdown">
                                <i class="fa fa-gears"></i>
                                <span> Tools </span>
                                <i class="fa fa-angle-down"></i>
                            </a>
                            <ul class="dropdown-menu pull-right" id="sample_3_tools">
                                <li>
                                    <a href="javascript:;" data-action="0" class="tool-action">
                                        <i class="icon-printer"></i> Print</a>
                                </li>

                                <li>
                                    <a href="javascript:;" data-action="2" class="tool-action">
                                        <i class="icon-doc"></i> PDF</a>
                                </li>
                                <li>
                                    <a href="javascript:;" data-action="3" class="tool-action">
                                        <i class="icon-paper-clip"></i> Excel</a>
                                </li>

                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="tools"> </div>
                </div>
                <div class="portlet-body">
                    <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{url('')}}" name="basic_validate" id="data_form">
                        {{ csrf_field() }}
                        <table class="table table-striped table-bordered table-hover" id="sample_3">

                            <thead>
                            <tr>
                                <th>SL</th>
                                <th>Country Name</th>
                                <th>Country Code</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            @if(isset($countries) && count($countries)>0)
                                @php
                                    $c=0;
                                @endphp
                                @foreach ($countries as $val)
                                    @php
                                        $c++;
                                    @endphp
                                    <tr class="gradeX" id="tr-{{$val->id }}">
                                        <td>{{$c}}</td>
                                        <td>{{$val->country_name}}</td>
                                        <td>{{$val->country_code}}</td>

                                        <td>
                                            <a class="btn btn-xs btn-success" href="{{url('country')}}/{{$val->id}}/edit">Edit</a>
                                            <a class="btn btn-xs btn-danger" onclick="DeleteData('{{$val->id}}')">Delete</a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
    <script type="text/javascript">
        function DeleteData(id) {
            $.ajax({
                headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' },
                url: "{{url('country')}}/"+id,
                type: 'DELETE',
                dataType: 'json',
                data: {},
            })
                .done(function(response) {
                    if(response.success){
                        $('#tr-'+id).fadeOut();
                    }else{
                        $.alert({
                            title:'Whoops!',
                            content:'<strong class="text-danger">'+response.msg+'</strong>',
                            type:'red',
                        });
                    }
                })
                .fail(function() {
                    $.alert({
                        title:'Whoops!',
                        content:'<strong class="text-danger">Something Went Wrong!!</strong>',
                        type:'red',
                    });
                });

        }
    </script>
@endsection