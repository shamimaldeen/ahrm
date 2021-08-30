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
                    <span class="caption-subject bold uppercase" id="hidden_table_title">Prodcuttion Status</span>
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
                                  <a href="{{url('production/create')}}">
                                    <i class="fa fa-plus"></i> Create A New Job</a>
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
                          <th>Employee</th>
                          <th>Piece Name</th>
                          <th>No. Of Dozen</th>
                          <th>Total Price</th>
                          <th>Total Cost</th>
                          <th>Duration</th>
                          <th>Status</th>
                          <th>Actions</th>
                        </tr>
                      </thead>
                      @if(isset($productions) && count($productions)>0)
                      @php
                      $c=0;
                      @endphp
                        @foreach ($productions as $production)
                        @php
                        $c++;
                        @endphp
                         <tr class="gradeX" id="tr-{{$production->pro_id }}">
                            <td>{{$c}}</td>
                            <td>{{$production->employee->emp_name}} ({{$production->employee->emp_empid}})</td>
                            <td>{{$production->piece->pi_name}}</td>
                            <td>{{$production->pro_no_dz}}</td>
                            <td>{{$production->pro_no_dz*$production->piece->pi_price_dz}}</td>
                            <td>{{$production->pro_totalcost}}</td>
                            <td>From <strong>{{$production->pro_startdate}}</strong> to <strong>{{$production->pro_enddate}}</strong></td>
                            <td>
                              <a class="btn btn-xs btn-warning">Pending</a>
                            </td>
                            <td>
                                <a class="btn btn-xs btn-primary" onclick="Complete('{{$production->pro_id}}')"><i class="fa fa-check"></i>&nbsp;Complete</a>
                            </td>
                          </tr>
                        @endforeach
                      @endif
                      <tbody>
                      </tbody>

                    </table>
                  </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script type="text/javascript">
      function Complete(pro_id) {
        $.confirm({
            title: 'Complete!',
            type:'green',
            columnClass: 'col-md-6 col-md-offset-3',
            content: '<hr><strong class="text-success">Is this job completed ?</strong><hr>' +
            '<form action="" class="formName">' +
            '<div class="form-group">' +
            '<label>Write a Completation note</label>' +
            '<textarea name="pro_completation_notes" class="form-control pro_completation_notes" rows="10" style="resize:none"></textarea>' +
            '</div>' +
            '</form>',
            buttons: {
                Delete: {
                    text: 'Complete',
                    btnClass: 'btn-green',
                    action: function () {
                        var pro_completation_notes = this.$content.find('.pro_completation_notes').val();
                        if(!pro_completation_notes){
                            $.alert({
                                title:'Whoops!',
                                type:'red',
                                content:'<hr><strong class="text-danger">Please write a Completation note</strong><hr>'
                              });
                            return false;
                        }else{
                          $.ajax({
                            headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' },
                            url: "{{url('production')}}/"+pro_id+"/complete",
                            type: 'POST',
                            dataType: 'json',
                            data: {pro_id:pro_id,pro_completation_notes:pro_completation_notes},
                            success:function(response) {
                              if(response.success){
                                location.reload();
                              }else{
                                $.alert({
                                  title:'Whoops!',
                                  type:'red',
                                  content:'<hr><strong class="text-danger">'+response.msg+'</strong><hr>'
                                });
                                return false;
                              }
                            }
                          })
                          .fail(function() {
                            $.alert({
                              title:'Whoops!',
                              type:'red',
                              content:'<hr><strong class="text-danger">Something went wrong!</strong><hr>'
                            });
                            return false;
                          });
                        }
                    },
                },
                cancel: function () {
                    //close
                },
            }
        });
      }
    </script>
@endsection