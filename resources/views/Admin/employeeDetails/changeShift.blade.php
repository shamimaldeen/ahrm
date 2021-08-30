<hr>
<div class="col" style="min-height:250px;max-height:auto">
    <form action="#" method="post" onsubmit="return false;">
    {{csrf_field()}}
      <div class="form-group">
        <label for="">Choose Employe  Working Hour:</label>
        <select name="emp_type_for_change" id="emp_type_for_change" class="form-control chosen">
        
        @foreach($types as $row)

        	@if($row->shift_type==3)
         		
         		<option value="{{ $row->shift_id }}">6 Hours ({{ $row->shift_stime }}-{{ $row->shift_etime }})</option>
         	@endif
         	@if($row->shift_type==1)
         		
         		<option value="{{ $row->shift_id }}">7 Hours ({{ $row->shift_stime }}-{{ $row->shift_etime }})</option>
         	@endif
        	@if($row->shift_type==2)
        		
         		<option value="{{ $row->shift_id }}">8 Hours ({{ $row->shift_stime }}-{{ $row->shift_etime }})</option>
         	@endif
         	@if($row->shift_type==4)
        		
         		<option value="{{ $row->shift_id }}">5 Hours ({{ $row->shift_stime }}-{{ $row->shift_etime }})</option>
         	@endif
         	@if($row->shift_type==5)
        		
         		<option value="{{ $row->shift_id }}">4 Hours ({{ $row->shift_stime }}-{{ $row->shift_etime }})</option>
         	@endif
         	@if($row->shift_type==6)
        		
         		<option value="{{ $row->shift_id }}">3 Hours ({{ $row->shift_stime }}-{{ $row->shift_etime }})</option>
         	@endif
         	@if($row->shift_type==7)
        		
         		<option value="{{ $row->shift_id }}">9 Hours ({{ $row->shift_stime }}-{{ $row->shift_etime }})</option>
         	@endif
         	<!-- @if($row->shift_type==8)
        		
         		<option value="{{ $row->shift_id }}">10 Hours ({{ $row->shift_stime }}-{{ $row->shift_etime }})</option>
         	@endif -->
         	@if($row->shift_type==9)
        		
         		<option value="{{ $row->shift_id }}">11 Hours ({{ $row->shift_stime }}-{{ $row->shift_etime }})</option>
         	@endif
         	@if($row->shift_type==10)
        		
         		<option value="{{ $row->shift_id }}">12 Hours ({{ $row->shift_stime }}-{{ $row->shift_etime }})</option>
         	@endif
         	
         	

        @endforeach
        
        			 <!--  <option value="2">8 hours</option>
                      <option value="1">7 hours</option>
                     
                      <option value="3">6 hours</option>
                      <option value="4">5 hours</option>
                      <option value="5">4 hours</option>
                      <option value="6">3 hours</option>
                      <option value="7">9 hours</option>
                      <option value="8">10 hours</option>
                      <option value="9">11 hours</option>
                      <option value="10">12 hours</option> -->
        
       
        </select>
      </div>

      <!-- <div class="form-group">
        <label for="">Choose Employe  Starting Time:</label>
        <select name="shift_stime" id="" class="form-control chosen">
        
        
        			  @foreach($types as $row)
        			  	<option value="{{ $row->shift_stime }}">{{ $row->shift_stime }}</option>
        			  @endforeach
        
       
        </select>
        <label for="">Choose Employe  Ending Time:</label>
        <select name="shift_etime" id="" class="form-control chosen">
        
        
        			  @foreach($types as $row)
        			  	<option value="{{ $row->shift_etime }}">{{ $row->shift_etime }}</option>
        			  @endforeach
        
       
        </select>
      </div> -->
      <div class="form-group">
        
      </div>
      <button type="button" class="btn btn-primary"
       onclick="UpdateShift()"><i class="fa fa-save"></i>&nbsp;Update</button>
    </form>
</div>
<hr>
<script type="text/javascript">
	$('.chosen').chosen();
	function UpdateShift() {
		var emp_type=$('#emp_type_for_change').val();
		var data=$('#data_form').serializeArray();
	      $.ajax({
	        headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
	        url: "{{URL::to('employee-details')}}/"+(emp_type)+"/update-employee-shift",
	        type: 'POST',
	        data: data,
	        success:function(response) {
	        	if(response.success){
	        		location.reload();
	        	}else{
	        		$.alert({
	        			title: "Whoops!",
	        			content: "<hr><div class='alert alert-danger'>"+(response.msg)+"</div><hr>",
	        			type: "red",
	        			columnClass: "col-md-4 col-md-offset-4"
	        		});
	        	}
	        }
	      });
	}
</script>