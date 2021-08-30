@php
use App\ProjectDetails;
$projectDetails=ProjectDetails::find('1');
@endphp

<!DOCTYPE html>

<html lang="en">

    <head>
        <meta charset="utf-8" />
        <title>{{$projectDetails->project_name}} :: {{$projectDetails->project_company}}</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="{{$projectDetails->project_name}} :: {{$projectDetails->project_company}}" />
        <meta content="{{$projectDetails->project_name}} :: {{$projectDetails->project_company}}" name="author" />
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="{{url('/')}}/public/css/fonts-googleapis.css" rel="stylesheet" type="text/css" />
        <link href="{{url('/')}}/public/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="{{url('/')}}/public/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
        <link href="{{url('/')}}/public/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="{{url('/')}}/public/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
        <link href="{{url('/')}}/public/css/bootstrap-timepicker.min.css" rel="stylesheet" type="text/css" />
        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- CSS FOR DATERANGE -->
        <link href="{{url('/')}}/public/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css" rel="stylesheet" type="text/css" />
        <!-- ENDS -->
        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="{{url('/')}}/public/assets/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
        <link href="{{url('/')}}/public/assets/global/css/plugins.min.css" rel="stylesheet" type="text/css" />
        <!-- END THEME GLOBAL STYLES -->
        <!-- BEGIN THEME LAYOUT STYLES -->
        <link href="{{url('/')}}/public/assets/layouts/layout/css/layout.min.css" rel="stylesheet" type="text/css" />
        <link href="{{url('/')}}/public/assets/layouts/layout/css/themes/darkblue.min.css" rel="stylesheet" type="text/css" id="style_color" />
        <link href="{{url('/')}}/public/assets/layouts/layout/css/custom.min.css" rel="stylesheet" type="text/css" />

        <link href="{{url('/')}}/public/assets/global/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css" />
        <link href="{{url('/')}}/public/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="{{url('/')}}/public/css/jquery-confirm.min.css">
        <link rel="stylesheet" href="{{url('/')}}/public/css/chosen.css">
        <link href="{{url('public')}}/assets/global/plugins/fullcalendar/fullcalendar.min.css" rel="stylesheet" type="text/css" />
        <link rel="shortcut icon" href="{{url('public/projectDetails')}}/{{$projectDetails->project_icon}}" />
        <style>
          @media (min-width: 768px){
          ::-webkit-scrollbar {
              width: 10px;
          }
          ::-webkit-scrollbar-track {
              background: none; 
          }
          ::-webkit-scrollbar-thumb {
              background: #999;
              height: 15px;
          }
          ::-webkit-scrollbar-thumb:hover {
              background: #ccc; 
          }
          ::-webkit-scrollbar-button:start {
            height: 10px;
          }
        </style>
        <!-- END THEME LAYOUT STYLES -->
    <!-- END HEAD -->

    <body style="background: white">
      
      <div class="container">
        <div class="row text-center">
            @include('error.msg')    
        </div>
      </div>

      <div class="container">
        <div class="row text-right" style="margin-top: 2%">
          @if(session()->get('jobseeker_login'))
             <a class="btn btn-md btn-primary" href="{{url('careers')}}">View Jobs</a>
             <a class="btn btn-md btn-success" href="{{url('join-us')}}/{{session()->get('jobseeker_id')}}/edit">My Resume</a>
             <a class="btn btn-md btn-primary" href="{{url('join-us')}}/{{session()->get('jobseeker_id')}}">My Applications</a>
             <a class="btn btn-md btn-danger" href="{{url('log-out')}}">Logout</a>
          @else
            <a class="btn btn-md btn-primary" href="{{url('join-us')}}">Join us</a>
            <a class="btn btn-md btn-default" href="{{url('/')}}">Go Back</a>
          @endif
        </div>
      </div>
        
        @yield('body')   
        <!-- BEGIN CORE PLUGINS -->
        <script src="{{url('/')}}/public/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
        <script src="{{url('/')}}/public/js/jquery-confirm.min.js"></script>
        <script src="{{url('/')}}/public/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="{{url('/')}}/public/assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>
        <script src="{{url('/')}}/public/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
        <script src="{{url('/')}}/public/assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
        <script src="{{url('/')}}/public/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
        <!-- END CORE PLUGINS -->
        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="{{url('/')}}/public/assets/global/scripts/app.min.js" type="text/javascript"></script>
        <!-- END THEME GLOBAL SCRIPTS -->
        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        <script src="{{url('/')}}/public/assets/layouts/layout/scripts/layout.min.js" type="text/javascript"></script>
        <script src="{{url('/')}}/public/assets/layouts/layout/scripts/demo.min.js" type="text/javascript"></script>
        <script src="{{url('/')}}/public/assets/layouts/global/scripts/quick-sidebar.min.js" type="text/javascript"></script>
        <script src="{{url('/')}}/public/assets/layouts/global/scripts/quick-nav.min.js" type="text/javascript"></script>

         <!-- for DateRange Picker SCRIPTS -->
        <script src="{{url('/')}}/public/assets/global/plugins/moment.min.js" type="text/javascript"></script>
        <script src="{{url('/')}}/public/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.js" type="text/javascript"></script>
        <script src="{{url('/')}}/public/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>    
         <script src="{{url('/')}}/public/assets/pages/scripts/components-date-time-pickers.min.js" type="text/javascript"></script>
         <script src="{{url('/')}}/public/assets/global/plugins/counterup/jquery.waypoints.min.js" type="text/javascript"></script>
        <script src="{{url('/')}}/public/assets/global/plugins/counterup/jquery.counterup.min.js" type="text/javascript"></script>
        <!-- ends -->

        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <script src="{{url('/')}}/public/assets/global/plugins/amcharts/amcharts/amcharts.js" type="text/javascript"></script>
        <script src="{{url('/')}}/public/assets/global/plugins/amcharts/amcharts/serial.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL SCRIPTS -->

        <script src="{{url('/')}}/public/assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
        <script src="{{url('/')}}/public/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>

         <script src="{{url('/')}}/public/assets/pages/scripts/table-datatables-buttons.js" type="text/javascript"></script>
         <script src="{{url('/')}}/public/js/bootstrap-timepicker.min.js" type="text/javascript"></script>
         <script src="{{url('/')}}/public/js/chosen.jquery.js" type="text/javascript"></script>
        <script>
            $('.chosen').chosen();
            $('input[type=text]').attr('autocomplete','off');
            $('input[type=number]').attr('autocomplete','off');
        </script>
    </body>

</html>