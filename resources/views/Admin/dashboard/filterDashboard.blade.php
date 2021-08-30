@for($chart_count=1;$chart_count<=count($chartArray);$chart_count++)
<script>
    $("#{{$chartArray[$chart_count]['chart_id']}}-after").html('').hide();
    $("#{{$chartArray[$chart_count]['chart_id']}}-before").show();

    var data=$("#chart_form-{{$chartArray[$chart_count]['chart_id']}}").serializeArray();
    $.ajax({
        url: "{{URL::to('getChartData')}}/{{$emp_depart_id}}/{{$chart_count}}/{{$chartArray[$chart_count]['chart_id']}}",
        type: 'POST',
        data: data,
        success:function(data) {
           $("#{{$chartArray[$chart_count]['chart_id']}}-before").html('').hide();
           $("#{{$chartArray[$chart_count]['chart_id']}}-after").html(data).show(); 
        }
    });
</script>
@endfor
