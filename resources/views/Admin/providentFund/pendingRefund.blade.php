@extends('Admin.index')
@section('body')
@php
use App\Http\Controllers\BackEndCon\Controller as C;
@endphp
<script src="{{url('/')}}/public/assets/global/plugins/jquery.min.js"></script>

<div class="row">
  <div class="col-md-12">
   @include('error.msg')
    
    <div class="portlet light">
      <div class="portlet-title">
        <div class="caption font-dark">
            <i class="icon-settings font-dark"></i>
            <span class="caption-subject bold uppercase" id="hidden_table_title">Pending Provident Refund</span>
        </div>
        
        <div class="actions">
          <a href="{{url('provident-fund')}}" class="btn btn-primary btn-sm">Go Back</a>
        </div>
      </div>
      
      <div class="portlet-body">
      
      <div class="col-md-12">
        <table class="table table-bordered table-striped table-hover" id="sample_3">
          <thead>
            <tr>
              <th>SL</th>
              <th>Employee Name</th>
              <th>Apply Date</th>
              <th>Purpose</th>
              <th>Requested Amount</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
          @if(isset($refunds[0]))
          <?php $c=0; ?>
          @foreach ($refunds as $refund)
          <?php $c++ ?>
            <tr id="tr-{{$refund->id}}">
              <td>{{$c}}</td>
              <td>{{$refund->employee->emp_name}} ({{$refund->employee->emp_empid}})</td>
              <td>{{$refund->apply_date}}</td>
              <td>{{$refund->purpose}}</td>
              <td>{{$refund->requested_amount}}</td>
              <td>
                <a class="btn btn-xs btn-success" onclick="Approve('{{$refund->id}}','{{$refund->requested_amount}}')">Approve</a>
                <a class="btn btn-xs btn-danger" onclick="Reject('{{$refund->id}}')">Reject</a>
              </td>
            </tr>
          @endforeach
          @endif
          </tbody>
        </table>
      </div>

      </div>
    </div>
    
  </div>
</div>
<script type="text/javascript">
  function Approve(refund_id,requested_amount) {
    $.confirm({
        title: 'Approve Refund Request',
        content: '<hr>' +
        '<form action="" class="formName">' +
        '<div class="form-group">' +
        '<label>Approve Amount</label>' +
        '<input type="number" skope="any" min="1" max="'+requested_amount+'" placeholder="" class="approved_amount form-control" required value="'+requested_amount+'" />' +
        '</div>' +
        '</form><hr>',
        buttons: {
            formSubmit: {
                text: 'Approve',
                btnClass: 'btn-blue',
                action: function () {
                    var approved_amount = this.$content.find('.approved_amount').val();
                    if(approved_amount!="" && approved_amount>0 && approved_amount<=requested_amount){
                      $.ajax({
                        headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
                        url: "{{url('provident-fund/refund/approve')}}",
                        type: 'POST',
                        dataType: 'json',
                        data: {refund_id:refund_id,approved_amount:approved_amount},
                      })
                      .done(function(response) {
                        if(response.success){
                          $('#tr-'+refund_id).fadeOut();
                          $.alert({
                            title:"Approved!",
                            content:'<hr><strong class="text-success">'+response.msg+'</strong><hr>'
                          });
                        }else{
                          $.alert({
                            title:"Whoops!",
                            content:'<hr><strong class="text-danger">'+response.msg+'</strong><hr>'
                          });
                        }
                      })
                      .fail(function() {
                        $.alert({
                          title:"Whoops!",
                          content:'<hr><strong class="text-danger">Something Went Wrong!</strong><hr>'
                        });
                      });
                      
                    }else{
                      $.alert({
                        title:"Whoops!",
                        content:'<hr><strong class="text-danger">Approve amount cannot be null or 0 or less than 0 or grater than Requested amount!</strong><hr>'
                      });
                    }
                }
            },
            cancel: function () {
                //close
            },
        },
        onContentReady: function () {
            var jc = this;
            this.$content.find('form').on('submit', function (e) {
                e.preventDefault();
                jc.$$formSubmit.trigger('click');
            });
        }
    });
  }

  function Reject(refund_id) {
    $.confirm({
        title: 'Reject Refund Request',
        content: '<hr>' +
        '<form action="" class="formName">' +
        '<label>Reason of Rejection</label>' +
        '<textarea class="reason_of_rejection form-control" rows="5" required></textarea>' +
        '</div>' +
        '</form><hr>',
        buttons: {
            formSubmit: {
                text: 'Reject',
                btnClass: 'btn-red',
                action: function () {
                    var reason_of_rejection = this.$content.find('.reason_of_rejection').val();
                    if(reason_of_rejection!=""){
                      $.ajax({
                        headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
                        url: "{{url('provident-fund/refund/reject')}}",
                        type: 'POST',
                        dataType: 'json',
                        data: {refund_id:refund_id,reason_of_rejection:reason_of_rejection},
                      })
                      .done(function(response) {
                        if(response.success){
                          $('#tr-'+refund_id).fadeOut();
                          $.alert({
                            title:"Rejected!",
                            content:'<hr><strong class="text-success">'+response.msg+'</strong><hr>'
                          });
                        }else{
                          $.alert({
                            title:"Whoops!",
                            content:'<hr><strong class="text-danger">'+response.msg+'</strong><hr>'
                          });
                        }
                      })
                      .fail(function() {
                        $.alert({
                          title:"Whoops!",
                          content:'<hr><strong class="text-danger">Something Went Wrong!</strong><hr>'
                        });
                      });
                      
                    }else{
                      $.alert({
                        title:"Whoops!",
                        content:'<hr><strong class="text-danger">Please Write Reason of rejection</strong><hr>'
                      });
                    }
                }
            },
            cancel: function () {
                //close
            },
        },
        onContentReady: function () {
            var jc = this;
            this.$content.find('form').on('submit', function (e) {
                e.preventDefault();
                jc.$$formSubmit.trigger('click');
            });
        }
    });
  }
</script>
@endsection