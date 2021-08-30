<hr>
<form action="{{route('documents.store')}}" method="post" enctype="multipart/form-data">
{{csrf_field()}}
<input type="hidden" name="emp_id" value="{{$emp_id}}">
	<div class="form-group">
		<label>Title</label>
		<textarea name="title" rows="3" class="form-control" style="resize: none"></textarea>
	</div>
	<div class="form-group">
		<label>Decription</label>
		<textarea name="description" rows="5" class="form-control" style="resize: none"></textarea>
	</div>
	<div class="form-group">
		<label>Choose File </label>
		<input type="file" name="doc"  data-placeholder="Choose a file to upload" class="form-control">
	</div>
	<button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-upload"></i>&nbsp;Upload</button>
</form>
<hr>