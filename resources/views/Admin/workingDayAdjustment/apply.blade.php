<link rel="stylesheet" href="{{URL::to('/')}}/public/css/datepicker.css" />

<hr>
<form action="{{route('working-day-adjustment.store')}}" method="post" enctype="multipart/form-data">
{{csrf_field()}}
	<div class="form-group">
		<label>Adjust to :</label>
		<input type="text" name="to" value="{{date('Y-m-d')}}" id="to" class="form-control" data-date-format="yyyy-mm-dd" readonly style="background: white">
	</div>
	<div class="form-group">
		<label>Adjust for :</label>
		<input type="text" name="for" value="{{date('Y-m-d')}}" id="for" class="form-control" data-date-format="yyyy-mm-dd" readonly style="background: white">
	</div>
	<button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-check"></i>&nbsp;Apply</button>
</form>
<hr>
<script  type="text/javascript">
$('#to').datepicker();
$('#for').datepicker();
</script>