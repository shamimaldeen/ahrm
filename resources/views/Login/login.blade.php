@php
use App\ProjectDetails;
$projectDetails=ProjectDetails::find('1');
@endphp
<!DOCTYPE html>
<html lang="en">
    
<head>
        <title>Log In :: {{$projectDetails->project_name}} :: {{$projectDetails->project_company}}</title>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="stylesheet" href="{{url('/')}}/public/css/bootstrap.min.css" />
        <link rel="stylesheet" href="{{url('/')}}/public/css/bootstrap-responsive.min.css" />
        <link rel="stylesheet" href="{{url('/')}}/public/css/matrix-login.css" />
        <link href="{{url('/')}}/public/font-awesome/css/font-awesome.css" rel="stylesheet" />
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>
        <link rel="shortcut icon" href="{{url('public/projectDetails')}}/{{$projectDetails->project_icon}}" />

</head>
    <body>
        <div id="loginbox">            
            <form id="loginform" class="form-vertical" action="{{url('CheckAdmin')}}" method="post">
                 {{ csrf_field() }}
                 <div class="control-group normal_text"> 
                    <img src="{{url('public/projectDetails')}}/{{$projectDetails->project_logo}}" style="height:50px;">
                    <h4>{{$projectDetails->project_name}} :: {{$projectDetails->project_company}}</h4>
                    <!-- <br>
                    <img src="{{url('public/projectDetails')}}/{{$projectDetails->project_icon}}" style="height:80px;"> -->
                </div>
                <div class="control-group">
                    <div class="controls">
                        <div class="main_input_box">
                            <span class="add-on bg_lg"><i class="icon-user"> 
                            </i></span><input type="text" name='email' placeholder="Employee ID" />
                        </div>
                    </div>
                </div>
                <div class="control-group">
                    <div class="controls">
                        <div class="main_input_box">
                            <span class="add-on bg_ly"><i class="icon-lock"></i>
                            </span><input type="password" name='password' placeholder="Password" />
                        </div>
                    </div>
                </div>

                @if(Session::has('error'))
                <h6 class="alert" style="background: white;color:red;font-weight: bold;text-align: center">{{Session::get('error')}} <br/></h6>
                @endif

                <div class="form-actions">
                    <span class="pull-left"><a class="flip-link btn btn-info" id="to-recover">Forget Password ?</a></span>
                    <span class="pull-right"><button type="submit" class="btn btn-success">Login</button></span>
                </div>
            </form>
            <form id="recoverform" action="{{url('forget-password-request')}}" class="form-vertical" method="post">
                {{csrf_field()}}
                <p class="normal_text"><b>Please submit your Employee ID to apply to reset your password.<b></p>
                
                <div class="controls">
                    <div class="main_input_box">
                        <span class="add-on bg_lo"><i class="icon-user"></i></span><input type="text" placeholder="Employee ID" name="emp_empid" id="emp_empid" />
                    </div>
                </div>

                <div class="controls" style="text-align: center">
                    <h5 id="error_box" style="color:red;"></h5>
                    <h5 id="success_box" style="color:green;"></h5>
                </div>
               
                <div class="form-actions" style="text-align: center">
                    <span class="pull-left"><a class="flip-link btn btn-success" id="to-login">&laquo; Back to login</a></span>
                    <span class="pull-right"><input type="submit" class="btn btn-info" value="Submit Request"/></span>
                </div>
            </form>
        </div>
        
        <script src="{{url('/')}}/public/js/jquery.min.js"></script>  
        <script src="{{url('/')}}/public/js/matrix.login.js"></script> 
    </body>

</html>

<script type="text/javascript">
    $("#recoverform").submit(function(e){
        e.preventDefault();
        if($('#emp_empid').val()!=""){
        $.ajax({
            url: $('#recoverform').attr('action'),
            type: $('#recoverform').attr('method'),
            data: $('#recoverform').serializeArray(),
            success:function(data) {
                data=data.split('<->');
                if(data[0]=="1"){
                    $('#emp_empid').val('');
                    $('#error_box').html('').hide();
                    $('#success_box').html(data[1]).show();
                }else if(data[0]=="0"){
                    $('#success_box').html('').hide();
                    $('#error_box').html(data[1]).show();
                }
            }
        });
        }else{
            $('#success_box').html('').hide();
            $('#error_box').html('Write your Employee ID').show();
        }
      });
</script>
