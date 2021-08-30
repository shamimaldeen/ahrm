@php use App\Http\Controllers\BackEndCon\Controller; @endphp
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
        <link href="{{url('/')}}/public/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link rel="shortcut icon" href="{{url('public/projectDetails')}}/{{$projectDetails->project_icon}}" />
        <style type="text/css"">
         @media print {
               .printACopy {
                   display: none;
               }
         }
        </style>
    </head>

    <body>
        <div class="container">
            <div class="row">
                <table class="table">
                    <tbody>
                        <tr>
                            <td style="width: 30%;text-align: right;font-family: arial-black;border: none">
                                <img src="{{url('public/projectDetails')}}/{{$projectDetails->project_icon}}" style="height:70px;">
                            </td>
                            <td style="width: 70%;font-family: arial-black;border: none">
                                <h3 class="text-left">{{$projectDetails->project_name}} :: {{$projectDetails->project_company}}</h3>
                                <h4 class="text-left"><strong style="padding-left: 100px">Application for Short Leave</strong></h4>
                            </td>
                        </tr>
                         <tr">
                            <td colspan="2" style="border: none">
                               <span style="font-size: 18px;font-family: arial-black">Dear Sir,</span>
                                <br>
                                <span style="font-size: 18px;font-family: arial-black">I would like to apply for a Short Leave from <strong>{{date('g:i a',strtotime($leaveApplication->leave_start_date))}}</strong> to <strong>{{date('g:i a',strtotime($leaveApplication->leave_end_date))}}</strong>, <strong>@php echo Controller::leaveHours($leaveApplication) @endphp</strong> hours on <strong>{{date('Y-m-d',strtotime($leaveApplication->leave_requested_date))}}</strong>, due to <strong>{{$leaveApplication->leave_reason}}</strong></span>  
                            </td>
                        </tr>
                    </tbody>
                    <table class="table" style="margin-top: 50px">
                        <tbody>
                            <tr>
                                <td style="width: 25%;font-family: arial-black;border-top: 2px solid #ccc;">
                                    <h6 class="text-center" style="padding:0px 0px 5px 0px;margin:0px"><strong>Signature</strong></h6>
                                    <span style="font-size: 16px">Employee Name : {{$employee->emp_name}}</span>
                                    <br>
                                    <span style="font-size: 16px">Employee ID : {{$employee->emp_empid}}</span>
                                    <br>
                                    <span style="font-size: 16px">Department : @php echo Controller::getDepartmentName($employee->emp_depart_id) @endphp</span>
                                    <br>
                                    <span style="font-size: 16px">Date : {{date('Y-m-d')}}</span>
                                </td>
                                <td style="width: 75%;border: none"></td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="table table-bordered" style="margin-top: 0px">
                        <tbody>
                            <tr>
                                <td style="width: 33%;font-family: arial-black;">
                                    <div class="text-center">
                                        <strong>Approved/Not Approved</strong>
                                    </div>
                                    <div class="text-left" style="border-top: 2px solid #ccc;margin-top: 50px">
                                        <span>Signature & Name of The Department Head</span>
                                        <br>
                                        <span>Date :</span>
                                    </div>
                                </td>
                                <td style="width: 33%;font-family: arial-black;">
                                    <div class="text-center">
                                        <strong>Approved/Not Approved</strong>
                                    </div>
                                    <div class="text-left" style="border-top: 2px solid #ccc;margin-top: 50px">
                                        <span>Signature & Name of The concenrn person of HR</span>
                                        <br>
                                        <span>Date :</span>
                                    </div>
                                </td>
                                <td style="width: 34%;font-family: arial-black;">
                                    <div class="text-center">
                                        <strong>Application Number</strong>
                                    </div>
                                    <div class="text-center" style="border-bottom: 2px solid #ccc;margin-top: 30px">
                                        <span><strong>{{$leaveApplication->leave_id}}</strong></span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <center><a class="btn btn-success printACopy" onclick="printACopy();">Print</a></center>
            </div>
        </div>
    </body>

    <script src="{{url('/')}}/public/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
    <script src="{{url('/')}}/public/js/jquery-confirm.min.js"></script>
    <script src="{{url('/')}}/public/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            printACopy();
        });
        function printACopy() {
            window.print();
        }
    </script>
</html>