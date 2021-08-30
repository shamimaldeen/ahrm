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
            <span class="caption-subject bold uppercase" id="hidden_table_title">Withdrawn Provident fund</span>
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
              <th>Approve Date</th>
              <th>Purpose</th>
              <th>Requested Amount</th>
              <th>Approved Amount</th>
              <th>Coopon Number</th>
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
              <td>{{$refund->approved_date}}</td>
              <td>{{$refund->purpose}}</td>
              <td>{{$refund->requested_amount}}</td>
              <td>{{$refund->approved_amount}}</td>
              <td>{{$refund->coopon_number}}</td>
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
  function Withdraw(refund_id) {
    $.confirm({
        title: 'Withdraw Provident fund',
        content: '<hr>' +
        '<form action="" class="formName">' +
        '<div class="form-group">' +
        '<label>Coopon Number</label>' +
        '<input type="text" placeholder="Coopon Number" class="coopon_number form-control" required />' +
        '</div>' +
        '</form><hr>',
        buttons: {
            formSubmit: {
                text: 'Withdraw',
                btnClass: 'btn-blue',
                action: function () {
                  var coopon_number = this.$content.find('.coopon_number').val();
                  if(coopon_number!=""){
                    $.ajax({
                      headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
                      url: "{{url('provident-fund/refund/withdraw')}}",
                      type: 'POST',
                      dataType: 'json',
                      data: {refund_id:refund_id,coopon_number:coopon_number},
                    })
                    .done(function(response) {
                      if(response.success){
                        $('#tr-'+refund_id).fadeOut();
                        $.alert({
                          title:"Withdrawn!",
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
                      content:'<hr><strong class="text-danger">Please Write Coopon Number</strong><hr>'
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