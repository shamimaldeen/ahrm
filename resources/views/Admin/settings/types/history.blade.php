<hr>
<table class="table table-bordered table-striped">
	<thead>
		<tr>
			<th>SL</th>
			<th>Execution Date</th>
			<th>Allowance</th>
			<th>Assigned By</th>
			<th>Assigned at</th>
		</tr>
	</thead>
	<tbody>
	@if(isset($allowance[0]))
	@foreach ($allowance as $key => $night)
		<tr>
			<td>{{$key+1}}</td>
			<td>{{$night->execution_date}}</td>
			<td>{{$night->allowance}} BDT</td>
			<td>{{$night->assigned->emp_name}} ({{$night->assigned->emp_empid}})</td>
			<td>{{date("F j, Y, g:i a",strtotime($night->created_at))}}</td>
		</tr>
	@endforeach
	@endif
	</tbody>
</table>
<hr>