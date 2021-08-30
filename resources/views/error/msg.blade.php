@if(Session::has('success'))
<div class="alert alert-success"><a class="close" data-dismiss="alert">×</a>{!! Session::get('success') !!}</div>
@endif


@if(Session::has('error'))
<div class="alert alert-danger"><a class="close" data-dismiss="alert">×</a>{!! Session::get('error') !!} <br/></div>
@endif

@if (count($errors) > 0)
    <div class="alert alert-danger">
    	<a class="close" data-dismiss="alert">×</a>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{!! $error !!}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="alert alert-danger" id="msg" style="display: none;">
   
</div>