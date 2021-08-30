<link rel="stylesheet" href="{{URL::to('/')}}/public/css/datepicker.css" />

@php use App\Http\Controllers\BackEndCon\Controller; @endphp
<form action="{{url('UpdateShift')}}/{{$info->emp_id}}" method="post" accept-charset="utf-8" id="update_shift_form">
{{csrf_field()}}
<table class="table table-bordered table-striped table-hover">
	<tbody>
		<tr>
			<td>Daily Working Hour:</td>
			<td colspan="3">
				<select id="emp_workhr" name="emp_workhr" class="form-control" onchange="getShift();">
					<option value="{{$info->emp_workhr}}">@php echo Controller::getWorkHour($info->emp_workhr) @endphp</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>Start Date:</td>
			<td colspan="3"><input type="text" name="start_date" id="start_date" readonly style="background: white" value="{{date('Y-m-d',strtotime('last saturday'))}}" class="form-control" data-date-format="yyyy-mm-dd"></td>
		</tr>
	</tbody>
	<tbody id="shiftRow">
		<tr id="shiftRow-1">
			<td style="width: 25%">Shift for this Week</td>
			<td id="shiftTd-1">
				<select id="emp_shiftid" name="emp_shiftid[]" class="form-control">
					@php
					if(Controller::getCurrentShiftInfo($info->emp_id,date('Y-m-d'))){
						$shift_id=Controller::getCurrentShiftInfo($info->emp_id,date('Y-m-d'))->shift_id;
					 	echo Controller::getShifts($info->emp_workhr,$shift_id); 
					}
					@endphp
				</select>
			</td>
			<td id="shiftTdField-1" style="width: 15%"><input type="number" min="1" name="days[]" value="7" class="form-control"></td>
			<td style="width: 15%">
				<a class="btn btn-xs btn-primary" onclick="addNewShiftRow('1')"><i class="fa fa-plus"></i></a>
			</td>
		</tr>
	</tbody>
	<tbody>
		<tr>
			<td>End Date:</td>
			<td colspan="3"><input type="text" name="end_date" id="end_date" readonly style="background: white" value="{{date('Y-m-d',strtotime('last thursday of this year'))}}" class="form-control" data-date-format="yyyy-mm-dd"></td>
		</tr>
		<tr>
			<td colspan="4">
				<center><input type="button" class="btn btn-md btn-success" id="update_button" onclick="UpdateShift();" value="Update Shift"/></center>
				<center id="update_text" style="display: none"><strong class="text-primary">Please Wait...</strong></center>
			</td>
		</tr>
	</tbody>
</table>
</form>
<script type="text/javascript">
	$('#start_date').datepicker();
	$('#end_date').datepicker();
	getShift();
	function addNewShiftRow(id) {
		var new_id=id+1;
		var new_row='';
		new_row+='<tr id="shiftRow-'+new_id+'"><td style="width: 25%">Shift for next Week</td><td id="shiftTd-'+new_id+'">'+$('#shiftTd-'+id).html()+'</td><td id="shiftTdField-'+new_id+'" style="width: 15%">'+$('#shiftTdField-'+id).html()+'</td><td style="width: 15%"><a class="btn btn-xs btn-primary" onclick="addNewShiftRow('+new_id+')"><i class="fa fa-plus"></i></a><a class="btn btn-xs btn-danger" onclick="removeShiftRow('+new_id+')"><i class="fa fa-close"></i></a></td>';
		$('#shiftRow').append(new_row);
	}

	function removeShiftRow(id) {
		$('#shiftRow-'+id).remove();
	}
</script>