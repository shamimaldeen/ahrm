<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>{{$project->project_name}} :: {{$project->project_company}}</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="NAM Jute Mills (BD) Ltd." name="description" />
        <meta content="roopokar.com" name="author" />
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
        <link href="{{url('public')}}/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="{{url('public')}}/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
        <link href="{{url('public')}}/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="{{url('public')}}/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
        <link href="{{url('public')}}/assets/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
        <link href="{{url('public')}}/assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="{{url('public')}}/assets/global/css/components-md.min.css" rel="stylesheet" id="style_components" type="text/css" />
        <link href="{{url('public')}}/assets/global/css/plugins-md.min.css" rel="stylesheet" type="text/css" />
        <link href="{{url('public')}}/assets/pages/css/login-5.min.css" rel="stylesheet" type="text/css" />
        <link rel="shortcut icon" href="{{url('public/projectDetails')}}/{{$project->project_icon}}" /> </head>

    <body class="login">
        <div class="user-login-5">
            <div class="row bs-reset">
                <div class="col-md-5 bs-reset mt-login-5-bsfix" style="background-size: cover;background-image:url('{{url('public/projectDetails/hrmlogin.jpg')}}'); ">
                    <div class="login-bg" style="position: relative; z-index: 0;">
                        
                        <div class="row" style="vertical-align: middle;height: 500px">
                            <div class="col-md-10 col-md-offset-1 text-center">                            
                               
                            </div>
                        </div>
                        
                    </div>
                </div>
                <div class="col-md-7 login-container bs-reset mt-login-5-bsfix">
                    <div class="login-content" style="margin-top: 20%;">
                        <div class="col-md-10 col-md-offset-1 text-center">
                            <img src="{{url('public/projectDetails')}}/{{$project->project_logo}}" style="text-align:center;">
                            <h3 style="color: black; text-align:center;">{{$project->project_name}} :: {{$project->project_company}}</h3><br>
                            <form action="{{url('CheckAdmin')}}" class="login-form" method="post">
                                {{ csrf_field() }}
                                <div class="row">
                                    <div class="col-xs-12">
                                        <input class="form-control form-control-solid placeholder-no-fix form-group" type="text" autocomplete="off" placeholder="Username" name="email" required style="border: 1px solid #821745"/> </div>
                                    <div class="col-xs-12">
                                        <input class="form-control form-control-solid placeholder-no-fix form-group" type="password" autocomplete="off" placeholder="Password" name="password" required style="border: 1px solid #821745"/> </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-8 text-right">
                                        <button class="btn pull-left" style="background: #821745;color:white" type="submit">Sign In</button>
                                    </div>
                                </div>
                                <br>
                                
                                <div class="row" style="display:none;">
                                    <div class="col-sm-12 text-left">
                                        <h4 class="text-primary">Super Admin</h4>
                                        <hr style="margin:0px;padding:0px">
                                        <strong class="text-primary" style="font-size: 12px">
                                            ID : sales@acquaintbd.com
                                            <br>
                                            Password : atechhrm
                                        </strong>
                                        <hr>
                                        <h4 class="text-success">Department Head/Line Manager</h4>
                                        <hr style="margin:0px;padding:0px">
                                        <strong class="text-success" style="font-size: 12px">
                                            ID : AD-0001
                                            <br>
                                            Password : 0001
                                        </strong>
                                        <hr>
                                        <h4 class="text-info">Employee</h4>
                                        <hr style="margin:0px;padding:0px">
                                        <strong class="text-info" style="font-size: 12px">
                                            ID : AC-0016
                                            <br>
                                            Password : 0016
                                        </strong>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-sm-12 text-left">
                                        @if(Session::has('error'))
                                        <strong style="background: white;color:red;font-size: 12px">{!!Session::get('error')!!}</strong>
                                        @endif
                                    </div>
                                </div>
                                <br>
                                <h4 style="color: white">
                                    <div><p style="font-size: 15px;color: #62686f;">Copyright &copy; Acquaint Technologies 2018-2020</p>
                                    </div>
                                </h4>
                            </form>
                        </div>
                    </div>
                    <div class="login-footer">
                        <div class="row bs-reset">
                            <div class="col-xs-5 bs-reset">
                                 
                            </div>
                            <div class="col-xs-7 bs-reset">
                                <div class="login-copyright text-right">
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- BEGIN CORE PLUGINS -->
        <script src="{{url('public')}}/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
        <script src="{{url('public')}}/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="{{url('public')}}/assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>
        <script src="{{url('public')}}/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
        <script src="{{url('public')}}/assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
        <script src="{{url('public')}}/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
        <script src="{{url('public')}}/assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
        <script src="{{url('public')}}/assets/global/plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script>
        <script src="{{url('public')}}/assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
        <script src="{{url('public')}}/assets/global/scripts/app.min.js" type="text/javascript"></script>
        <script src="{{url('public')}}/assets/pages/scripts/login-5.min.js" type="text/javascript"></script>
    </body>

</html>