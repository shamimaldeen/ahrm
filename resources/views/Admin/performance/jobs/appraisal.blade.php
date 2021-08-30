@extends('Admin.index')
@section('body')
@php use App\Http\Controllers\BackEndCon\Controller as C; @endphp
<script src="{{url('/')}}/public/assets/global/plugins/jquery.min.js"></script>

<div class="row">

    <div class="col-md-12">
       @include('error.msg')
       
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption font-dark col-md-2">
                    <i class="icon-settings font-dark"></i>
                    <span class="caption-subject bold uppercase" id="hidden_table_title">Skill Apraisal</span>
                </div>
                <div class="col-md-8">
                  <center><h4><strong>Total Incentive : {{C::decimal($Job->project->incentive_amount*($Job->job_weight/100))}} BDT</strong></h4></center>
                </div>
                <div class="col-md-2">
                  <a class="btn btn-xs btn-primary pull-right" href="{{url('jobs')}}/{{$Job->id}}" style="margin-top:10px">Go Back</a>
                </div>
            </div>
            
            <div class="portlet-body">
              <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{url('jobs')}}/{{$Job->id}}/appraisal" name="basic_validate" id="data_form">
              {{ csrf_field() }}
              <input type="hidden" name="total_incentive" id="total_incentive" value="{{C::decimal($Job->project->incentive_amount*($Job->job_weight/100))}}">
              <input type="hidden" name="incentive_percentage" id="incentive_percentage" value="{{$incentive['percentage']}}">

               <table class="table table-striped table-bordered table-hover">

                      <thead>
                        <tr>
                          <th class="col-md-1">SL</th>
                          <th class="col-md-1">Skill</th>
                          <th class="col-md-2">Skill Weight (%)</th>
                          <th class="col-md-1">Target (Unit)</th>
                          <th class="col-md-1">Achieve (Unit)</th>
                          @if($incentive["incentive"])
                          <th class="col-md-3">Incentive target amount(Optional) (BDT)</th>
                          <th class="col-md-3">Incentive achieve amount(Optional) (BDT)</th>
                          @endif
                        </tr>
                      </thead>
                        @if(isset($Appraisal[0]) && count($Appraisal)>0)
                        @php
                        $c=0;
                        @endphp
                          @foreach ($Appraisal as $ap)
                          @php
                          $c++;
                          @endphp
                           <tr class="gradeX" id="tr-{{$ap->skill_id}}">
                              <td>{{$c}}</td>
                              <td>{{$ap->skill->name}}</td>
                              <td><input type="number" min="1" max="100" step="any" name="skill_weight[{{$ap->skill_id}}]" id="skill_weight-{{$ap->skill_id}}" onkeyup="calculateIncentive('{{$ap->skill_id}}')" value="{{$ap->skill_weight}}" placeholder="Skill Weight (%)" class="form-control"></td>
                              <td><input type="number" min="1" max="100" step="any" name="target[{{$ap->skill_id}}]" id="target-{{$ap->skill_id}}" onkeyup="calculateIncentive('{{$ap->skill_id}}')" value="{{$ap->target}}" placeholder="Target (%)" class="form-control"></td>
                              <td><input type="number" min="1" max="100" step="any" name="achieve[{{$ap->skill_id}}]" id="achieve-{{$ap->skill_id}}" onkeyup="calculateIncentive('{{$ap->skill_id}}')" value="{{$ap->achieve}}" placeholder="Achieve (%)" class="form-control"></td>
                              @if($incentive["incentive"])
                              <td><input type="number" step="any" name="incentive_target_amount[{{$ap->skill_id}}]" id="incentive_target_amount-{{$ap->skill_id}}" onkeyup="calculateIncentive('{{$ap->skill_id}}')" value="{{$ap->incentive_target_amount}}" placeholder="Incentive target Amount (%)" class="form-control"></td>
                              <td><input type="number" step="any" name="incentive_achieve_amount[{{$ap->skill_id}}]" id="incentive_achieve_amount-{{$ap->skill_id}}" onkeyup="calculateIncentive('{{$ap->skill_id}}')" value="{{$ap->incentive_achieve_amount}}" placeholder="Incentive Achieve Amount (%)" class="form-control"></td>
                              @endif
                           </tr>
                          @endforeach
                        @endif
                      <tbody>
                        <tr>
                          <td colspan="7" class="text-right">
                            <input type="submit" value="Update" class="btn btn-md btn-success">
                          </td>
                        </tr>
                      </tbody>
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
        url: "{{url('projects')}}/"+id,
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

    function calculateIncentive(skill_id) {
      var total_incentive=parseFloat($('#total_incentive').val());
      var incentive_percentage=parseFloat($('#incentive_percentage').val());
      
      var skill_weight=$('#skill_weight-'+skill_id).val();
      if(skill_weight=="" || skill_weight<0 || skill_weight>100){
        skill_weight=0;
        $('#skill_weight-'+skill_id).val('0');
        calculateIncentive(skill_id);
      }else{
        skill_weight=parseFloat(skill_weight);
      }

      var target=$('#target-'+skill_id).val();
      if(target=="" || target<0){
        target=0;
        $('#target-'+skill_id).val('0');
        calculateIncentive(skill_id);
      }else{
        target=parseFloat(target);
      }

      var achieve=$('#achieve-'+skill_id).val();
      if(achieve=="" || achieve<0){
        achieve=0;
        $('#achieve-'+skill_id).val('0');
        calculateIncentive(skill_id);
      }else{
        achieve=parseFloat(achieve);
      }

      var target_amount=parseFloat(total_incentive*(skill_weight/100)).toFixed(2);
      var performance=parseFloat((achieve/target)*100).toFixed(2);
      var achieve_amount=parseFloat((target_amount*(performance/100))*(incentive_percentage/100)).toFixed(2);
      $('#incentive_target_amount-'+skill_id).val(target_amount);
      $('#incentive_achieve_amount-'+skill_id).val(achieve_amount);
    }
  </script>
@endsection