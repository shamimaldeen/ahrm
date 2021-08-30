<hr>
<table class="table table-bordered">
	<tbody>
		<tr>
			<td>
				<table class="table table-bordered">
					<tbody>
						<tr>
							<td class="col-md-4"><strong>Loan Purpose</strong></td>
							<td>
								{{$loanApprove->loan->purpose}}
							</td>
						</tr>
						<tr>
							<td class="col-md-4"><strong>Loan Amount</strong></td>
							<td>
								{{$loanApprove->loan->amount}} BDT
							</td>
						</tr>
						<tr>
							<td class="col-md-4"><strong>Approved</strong></td>
							<td>
								By <strong>{{$loanApprove->approve->emp_name}} ({{$loanApprove->approve->emp_empid}})</strong>
								<br>
								at <strong>{{date("F j, Y, g:i a",strtotime($loanApprove->created_at))}}</strong>
							</td>
						</tr>
						<tr>
							<td class="col-md-4"><strong>Installment</strong></td>
							<td>{{$installment}} BDT</td>
						</tr>
						<tr>
							<td class="col-md-4"><strong>Installment Month</strong></td>
							<td>{{$loanApprove->install_month->month}} ({{$loanApprove->year}})</td>
						</tr>
						@if($loanApprove->flag=="1")
						<tr>
							<td class="col-md-4"><strong>Received</strong></td>
							<td>
								By <strong>{{$loanApprove->receive->emp_name}} ({{$loanApprove->receive->emp_empid}})</strong>
								<br>
								at <strong>{{date("F j, Y, g:i a",strtotime($loanApprove->updated_at))}}</strong>
							</td>
						</tr>
						@endif
					</tbody>
				</table>
			</td>
		</tr>
		
	</tbody>
</table>

<hr>