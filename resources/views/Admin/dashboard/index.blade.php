@extends('Admin.index')
@section('body')

    @include('Admin.dashboard.header')

    @if($id->suser_level=="2" or $id->suser_level=="3" or $id->suser_level=="5" or $id->suser_level=="6")
      @include('Admin.dashboard.user')
    @endif

    @if($id->suser_level=="1" or $id->suser_level=="4")
      @include('Admin.dashboard.dashboard')
    @endif

<br>
<br>
<div class="row">
  @include('Admin.dashboard.noticeBoard')
  @include('Admin.dashboard.holiday')
  @include('Admin.dashboard.probation')
</div>

<script type="text/javascript">
  function ClockIn() {
    $('#ClockIn').attr('disabled','disabled');
    $.ajax({
      url: "{{URL::to('ClockIn')}}",
      type: 'GET',
      data: {},
      success:function(data) {
        data=data.split('///');
        if(data[0]=="success"){
          $('#ClockIn').val('Clocked In');
          $('#Clockout').val('Clock Out');
          $('#ClockOut').removeAttr('disabled');
          $.dialog({
              title: data[1],
              content: data[2],
              type:'green',
          });
        }

        if(data[0]=="error"){
          $('#ClockIn').removeAttr('disabled');
          $.dialog({
              title: data[1],
              content: data[2],
              type:'red',
          });
        }
      }
    });
  }

  function ClockOut() {
    $('#ClockOut').attr('disabled','disabled');
    $.ajax({
      url: "{{URL::to('ClockOut')}}",
      type: 'GET',
      data: {},
      success:function(data) {
        data=data.split('///');
        if(data[0]=="success"){
          $('#ClockOut').val('Clocked Out');
          $('#ClockIn').val('Clock In');
          $('#ClockIn').removeAttr('disabled');
          $.dialog({
              title: data[1],
              content: data[2],
              type:'green',
          });
        }

        if(data[0]=="error"){
          $('#ClockOut').removeAttr('disabled');
          $.dialog({
              title: data[1],
              content: data[2],
              type:'red',
          });
        }
      }
    });
  }

  function filterDashboard() {
    getTicketInfo();
    $('#chart_functions').html('');
    $(".loading").html('<center><img src="{{URL::to("public")}}/loading.svg"/></center>')
    var data=$('#filterDashboard_form').serializeArray();
    $.ajax({
      url: "{{URL::to('filter-dashboard')}}",
      type: 'POST',
      data: data,
      success:function(data) {
        $('#chart_functions').html(data);
      }
    });
  }
  setTimeout(filterDashboard, 1000)
</script>
@endsection