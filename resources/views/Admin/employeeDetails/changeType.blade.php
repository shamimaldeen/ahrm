<hr>
<div class="col" style="min-height:250px;max-height:auto">
    <form action="#" method="post" onsubmit="return false;">
    {{csrf_field()}}
      <div class="form-group">
        <label for="emp_type_for_change">Choose Employe  Type:</label>
        <select name="emp_type_for_change" id="emp_type_for_change" class="form-control chosen">
        @if(isset($types[0]))
        @foreach ($types as $key => $type)
        	<option value="{{$type->id}}">{{$type->name}}</option>
        @endforeach
        @endif
        </select>
      </div>
      <button type="button" class="btn btn-primary"
       onclick="UpdateType()"><i class="fa fa-save"></i>&nbsp;Update</button>
    </form>
</div>
<hr>
<script type="text/javascript">
	$('.chosen').chosen();
	function UpdateType() {
		var emp_type=$('#emp_type_for_change').val();
		var data=$('#data_form').serializeArray();
	      $.ajax({
	        headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
	        url: "{{URL::to('employee-details')}}/"+(emp_type)+"/update-employee-type",
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