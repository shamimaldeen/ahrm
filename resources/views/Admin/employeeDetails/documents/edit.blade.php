<hr>
<form action="{{route('documents.update',$document->id)}}" method="post" enctype="multipart/form-data">
{{csrf_field()}}
{{method_field('PUT')}}
	<div class="form-group">
		<label>Title</label>
		<textarea name="title" rows="3" class="form-control" style="resize: none">{{$document->title}}</textarea>
	</div>
	<div class="form-group">
		<label>Decription</label>
		<textarea name="description" rows="5" class="form-control" style="resize: none">{{$document->description}}</textarea>
	</div>
	<div class="form-group">
		<label>Choose File </label>
		<input type="file" name="doc"  data-placeholder="Choose a file to upload" class="form-control">
	</div>
	<div class="form-group">
		<label>Current Document : <a onclick="View('{{$document->file}}')"><strong>{{$document->name}}</strong></a> </label>
	</div>
	<button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-upload"></i>&nbsp;Update</button>
</form>
<hr>