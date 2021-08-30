<form action="{{URL::to('changeUserPriorityLevelSubmit')}}" method="post">
{{csrf_field()}}
	<table class="table table-bordered">
		<tbody>
			<tr>
				<td>
					Employee ID : <b>{{$employee->emp_empid}}</b>, Employee Name : <b>{{$employee->emp_name}}</b>, Face ID : <b>{{$employee->emp_machineid}}</b>
				</td>
			</tr>
			<tr>
				<td>
					@if(isset($priority) && count($priority)>0)
						@foreach ($priority as $pr)
							@if($employee->suser_level==$pr->pr_id)
								<input type="radio" name="suser_level" value="{{$pr->pr_id}}" checked="checked">&nbsp;&nbsp;{{$pr->pr_name}}<br>
							@else
								<input type="radio" name="suser_level" value="{{$pr->pr_id}}">&nbsp;&nbsp;{{$pr->pr_name}}<br>
							@endif
						@endforeach
					@endif
				</td>
			</tr>
			<tr>
				<td>
					<input type="hidden" name="id" value="{{$id}}">
					<input type="submit" class="btn btn-md btn-success" value="Change Priority">
				</td>
			</tr>
		</tbody>
	</table>
</form>