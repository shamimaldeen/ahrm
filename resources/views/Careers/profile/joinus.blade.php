@extends('Careers.main')
@section('body')
<div class="container" style="margin-top: 2%">
	<div class="panel panel-default">
		<div class="panel-body">
			<ul class="nav nav-tabs">
			  <li class="active"><a data-toggle="tab" href="#login">Log in to your Profile</a></li>
			  <li><a data-toggle="tab" href="#signup">Create a new profile</a></li>
			</ul>

			<div class="tab-content">
			  <div id="login" class="tab-pane fade in active">
			  	<div class="panel panel-default">
					<div class="panel-body">
						<form action="{{url('join-us-login')}}" method="post">
					    {{csrf_field()}}
						  <div class="form-group">
						    <label for="email">Email address:</label>
						    <input type="email" class="form-control" name="email" id="email">
						  </div>
						  <div class="form-group">
						    <label for="pwd">Password:</label>
						    <input type="password" class="form-control" name="password" id="pwd">
						  </div>
						  <button type="submit" class="btn btn-success">Log in</button>
						</form>
					</div>
				</div>
			  </div>
			  <div id="signup" class="tab-pane fade">
			    <div class="panel panel-default">
					<div class="panel-body">
						<form action="{{route('join-us.store')}}" method="post">
					    {{csrf_field()}}
						  <div class="form-group">
						    <label for="name">Full Name</label>
						    <input type="text" class="form-control" name="name" id="name">
						  </div>
						  <div class="form-group">
						    <label for="phone">Phone Number</label>
						    <input type="text" class="form-control" name="phone" id="phone">
						  </div>
						  <div class="form-group">
						    <label for="gender">Gender</label>
						    <br>
						    <input type="radio" name="sex" value="1" checked>&nbsp;Male
						    &nbsp;<input type="radio" name="sex" value="0">&nbsp;Female
						  </div>
						  <div class="form-group">
						    <label for="email">E-Mail</label>
						    <input type="email" class="form-control" name="email" id="email">
						  </div>
						  <div class="form-group">
						    <label for="pwd">Password:</label>
						    <input type="password" class="form-control" name="password" id="pwd">
						  </div>
						  <div class="form-group">
						    <label for="r_pwd">Retype Password:</label>
						    <input type="password" class="form-control" name="confirm_password" id="r_pwd">
						  </div>
						  <button type="submit" class="btn btn-success">Join Us</button>
						</form>
					</div>
				</div>
			  </div>
			</div>
		</div>
	</div>
	
</div>
@endsection