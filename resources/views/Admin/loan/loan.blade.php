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
            <span class="caption-subject bold uppercase" id="hidden_table_title">Loan</span>
        </div>
        
        <div class="actions">
          @if($Loan->flag!="2" || $Loan->flag!="3")
            @if($Loan->flag=="1" && count($pendingInstallments)>0 && $id->suser_level=="1")
            <!-- <a class="btn btn-sm btn-primary" data-toggle="collapse" data-target="#submitMoney"><i class="fa fa-money"></i>&nbsp;Submit Money</a> -->
            @endif

            @if($Loan->flag=="0" && $id->suser_level=="1")
            <a class="btn btn-sm btn-success" data-toggle="collapse" data-target="#approve"><i class="fa fa-check"></i>&nbsp;Approve</a>
            <a class="btn btn-sm btn-danger" onclick="Reject('{{$Loan->id}}')"><i class="fa fa-close"></i>&nbsp;Reject</a>
            @endif
          @endif
          <a class="btn btn-sm btn-primary" href="{{route('loan-certificate', [$Loan->employee->emp_id, $loan_id])}}"><i class="fa fa-search"></i>&nbsp;Loan Certificate</a>
          <a class="btn btn-sm btn-primary" href="{{url('loans')}}"><i class="fa fa-search"></i>&nbsp;View Loans</a>
        </div>
      </div>
      
      <div class="portlet-body">
      
      <div class="col-md-12">
        <div class="collapse" id="submitMoney">
          <div class="panel panel-default">
            <div class="panel-body">
              <div class="panel panel-default">
                <div class="panel-body">
                  <form action="{{url('loans')}}/{{$Loan->id}}/submitMoney" method="post">
                  {{csrf_field()}}
                    <div class="panel panel-default">
                      <div class="panel-heading">
                        <h4><strong>Submit Money</strong></h4>
                      </div>
                      <div class="panel-body">
                        <div class="row">
                          <div class="form-group col-md-12">
                            <label for="month">Month:</label>
                            <select name="month" id="month" class="form-control">
                              @if(isset($pendingInstallments))
                                @foreach ($pendingInstallments as $install)
                                  <option value="{{$install->id}}">{{$install->install_month->month}} ({{$install->year}})</option>
                                @endforeach
                              @endif
                            </select>
                          </div>
                          <div class="form-group col-md-12">
                            <label for="amount">Amount:</label>
                            <input type="text" readonly style="background: white" class="form-control" value="{{$each_installment}}" id="amount">
                          </div>
                        </div>
                        <hr>
                        <div class="row">
                          <button type="submit" class="btn btn-md btn-success pull-right" style="margin-right: 15px"><i class="fa fa-save"></i>&nbsp;Submit</button>
                        </div>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <div class="col-md-12">
        <div class="collapse" id="approve">
          <div class="panel panel-default">
            <div class="panel-body">
              <form action="{{url('loans')}}/{{$Loan->id}}/approve" method="post">
              {{csrf_field()}}
                <div class="panel panel-default">
                  <div class="panel-heading">
                    <h4><strong>Approve Loan</strong></h4>
                  </div>
                  <div class="panel-body">
                    <div class="row">
                      @php
                        $time= App\Loan::where('id', $loan_id)->first();
                      @endphp
                     
                     
                        <?php

                        $myloan_date=$time->year.'-'.$time->month.'-01';
                        
                        for($k=1;$k<=60;$k++){
                            
                            $d = strtotime("+$k months",strtotime($myloan_date));
                            $monthname = date('F', strtotime(date("Y-m-d",$d)));
                            $dateElements = explode('-', date("Y-m-d",$d));
                            $year = $dateElements[0];
                            $month = $dateElements[1];
                            
                         echo '
                         <div class="col-md-2">
                            <input type="checkbox" name="months[]" value="'.$month.'&'.$year.'" style="width: 16px;height: 16px;">'.$monthname.' ('.$year.')</div>';
                        }
                        ?>
                    </div>
                    <hr>
                    <div class="row">
                      <button type="submit" class="btn btn-md btn-success pull-right" style="margin-right: 15px"><i class="fa fa-check"></i>&nbsp;Approve</button>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      
      <div class="col-md-12">
        <table class="table table-bordered table-striped table-hover">
          <thead>
            <tr>
              <th>Employee Name</th>
              <th>Purpose</th>
              <th>Taken Month</th>
              <th>Amount</th>
              
              <th>Adjustment</th>
              <th>Pending Amount</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>{{$Loan->employee->emp_name}} ({{$Loan->employee->emp_empid}})</td>
              <td>{{$Loan->purpose}}</td>
              <td>{{$Loan->loan_month->month}} ({{$Loan->year}})</td>
              <td>{{$Loan->amount}}</td>
              
              <td>
                @php
                  $adjustment = App\LoanApprove::where('loan_id', $Loan->id)
                  ->where('flag', 1)
                  ->sum('paid_amount');
                @endphp
                {{ $adjustment }}
              </td>
              <td>{{ $Loan->amount -  $adjustment }}</td>
              <td>
                @if($Loan->flag=="0")
                  <a class="btn btn-xs btn-warning">Pending</a>
                @elseif($Loan->flag=="1")
                  <a class="btn btn-xs btn-primary">Approved</a>
                @elseif($Loan->flag=="2")
                  <a class="btn btn-xs btn-success">Completed</a>
                @elseif($Loan->flag=="3")
                  <a class="btn btn-xs btn-danger">Rejected</a>
                @endif
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      
      <div class="col-md-4">
        <table class="table table-bordered table-striped table-hover">
          <thead>
            <tr>
              <th colspan="2"><h4><strong>Loan Status</strong></h4></th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <th><i class="fa fa-bell text-info"></i> Loan Applied</th>
              <th>{{$loan_status["total"]}}</th>
            </tr>
            <tr>
              <th><i class="fa fa-thumbs-up text-success"></i> Approved loan</th>
              <th>{{$loan_status["approved"]}}</th>
            </tr>
            <tr>
              <th><i class="fa fa-thumbs-up text-success"></i> Completed loan</th>
              <th>{{$loan_status["completed"]}}</th>
            </tr>
            <tr>
              <th><i class="fa fa-hourglass text-warning"></i> Pending loan</th>
              <th>{{$loan_status["pending"]}}</th>
            </tr>
            <tr>
              <th><i class="fa fa-thumbs-down text-danger"></i> Rejected loan</th>
              <th>{{$loan_status["rejected"]}}</th>
            </tr>
          </tboby>
        </table>
      </div>

      <div class="col-md-8">
        <table class="table table-bordered table-striped table-hover">
            <thead>
              <tr>
                <th colspan="5"><h4><strong>Installment</strong></h4></th>
              </tr>
            </thead>
            <tbody>
              @if(isset($installments[0]))
                <tr>
                  @foreach ($installments as $install)
                    <th class="text-center"><a onclick="installmentInfo('{{$install->id}}')">{{$install->install_month->month}} ({{$install->year}})</a></th>
                  @endforeach
                </tr>

                <tr>
                  @foreach ($installments as $install)
                  <td align="center">
                    <i style="font-size: 22px;" class="fa fa-{{(($install->flag=='1')? 'check' : 'close')}} text-{{(($install->flag=='1')? 'success' : 'danger')}}"></i>
                    <br>
                    <strong style="color: #2e6da4;">Installment :</strong> {{$each_installment}} <b>BDT</b><br>
                    @if($install->paid_amount > 0)
                  <strong style="color: green;">Paid :</strong>   {{ $install->paid_amount }} <b>BDT</b>
                    @endif
                  </td>
                  @endforeach
                </tr>
              @endif
          </tbody>
        </table>

         <!-- after installment -->
         @if(isset($installments_after[0]))
         <table class="table table-bordered table-striped table-hover">
            <thead>
              <tr>
                <th colspan="5"><h4><strong>After Installment</strong></h4></th>
              </tr>
            </thead>
            <tbody>
             
                <tr>
                  @foreach ($installments_after as $install)
                    <th class="text-center"><a onclick="installmentInfo('{{$install->id}}')">{{$install->install_month->month}} ({{$install->year}})</a></th>
                  @endforeach
                </tr>
                <tr>
                  @foreach ($installments_after as $install)
                  <td align="center">
                    <i style="font-size: 22px;" class="fa fa-{{(($install->flag=='1')? 'check' : 'close')}} text-{{(($install->flag=='1')? 'success' : 'danger')}}"></i>
                    <br>
                    <strong style="color: #2e6da4;">Installment :</strong> {{0}} <b>BDT</b><br>
                    @if($install->paid_amount > 0)
                  <strong style="color: green;">Paid :</strong>   {{ $install->paid_amount }} <b>BDT</b>
                    @endif
                  </td>
                  @endforeach
                </tr>


                
              @endif
          </tbody>
        </table>
      </div>

      </div>
    </div>
    
  </div>
</div>
<script type="text/javascript">
  function Reject(id) {
    $.confirm({
      title: 'Confirm!',
      content: '<hr><strong class="text-danger">Are You Sure to Reject This Loan Application ?</strong><hr>',
      icon: 'fa fa-question-circle',
      animation: 'scale',
      closeAnimation: 'scale',
      opacity: 0.5,
      type:'red',
      buttons: {
          'confirm': {
            text: 'Reject',
            btnClass: 'btn-red',
            action: function () {
              rejectLoan(id);
            }
          },
          'cancel': {
            text: 'Cancel',
            btnClass: 'btn-default',
            action: function () {
              
            }
          }
        }
    });
  }

  function rejectLoan(id) {
    $.ajax({
      headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' },
      url: "{{url('loans')}}/"+id+"/reject",
      type: 'POST',
      dataType: 'json',
      data: {},
    })
    .done(function(response) {
      if(response.success){
        location.reload();
      }else{
        $.alert({
          title:"Whoops!",
          content:"<strong class='text-danger'>"+response.msg+"</strong>",
          type:"red"
        });
      }
    })
    .fail(function() {
      $.alert({
        title:"Whoops!",
        content:"<strong class='text-danger'>Something Went Wrong!</strong>",
        type:"red"
      });
    });
    
  }

  function installmentInfo(id) {
    $.alert({
        title: 'Installment Details',
        content: "url:{{url('loans')}}/"+id+"/info",
        animation: 'scale',
        closeAnimation: 'bottom',
        columnClass: 'col-md-6 col-md-offset-3',
        backgroundDismiss: true,
        buttons: {
            ok: {
                text: 'Okay',
                btnClass: 'btn-blue',
                action: function(){
                
                }
            }
        }
    });
  }
</script>
@endsection