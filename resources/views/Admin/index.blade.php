@php
    use App\Http\Controllers\BackEndCon\Controller;
    use Illuminate\Support\Facades\Route;
    $route=Route::getFacadeRoot()->current()->uri();

    use App\ProjectDetails;
    $projectDetails=ProjectDetails::find('1');
@endphp

@if(Controller::checkEnable()=="1")
@else
    <script type="text/javascript">location="{{url('/login')}}"</script>
@endif

<!-- @if($route!="/")
    @if(Controller::checkLinkPriority($route)=="1")
    @else
        <script type="text/javascript">location="{{url('/')}}"</script>
@endif
@endif -->

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
    <link href="{{url('/')}}/public/assets/global/plugins/fullcalendar/fullcalendar.min.css" rel="stylesheet" type="text/css" />
    <link rel="shortcut icon" href="{{url('public/projectDetails')}}/{{$projectDetails->project_icon}}" />


{{--start new added link for header--}}
    <!--begin::Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700|Roboto:300,400,500,600,700">

    <!--end::Fonts -->

    <!--begin::Page Vendors Styles(used by this page) -->
    <link href="{{url('/')}}/public/assets/plugins/custom/fullcalendar/fullcalendar.bundle.css" rel="stylesheet" type="text/css" />

    <!--end::Page Vendors Styles -->

    <!--begin::Global Theme Styles(used by all pages) -->

    <link href="{{url('/')}}/public/assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
    <link href="{{url('/')}}/public/css/style.bundle.css" rel="stylesheet" type="text/css" />

    <!--end::Global Theme Styles -->

    <!--begin::Layout Skins(used by all pages) -->
    <link href="{{url('/')}}/public/assets/css/skins/header/base/light.css" rel="stylesheet" type="text/css" />
    <link href="{{url('/')}}/public/assets/css/skins/header/menu/light.css" rel="stylesheet" type="text/css" />
    <link href="{{url('/')}}/public/assets/css/skins/brand/light.css" rel="stylesheet" type="text/css" />
    <link href="{{url('/')}}/public/assets/css/skins/aside/dark.css" rel="stylesheet" type="text/css" />
    <!--end::Layout Skins -->
    <link rel="shortcut icon" href="{{url('/')}}/public/assets/media/logos/favicon.ico" />
    {{--end new added link for header--}}

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
        }
    </style>

</head>
<!-- END THEME LAYOUT STYLES -->
<!-- END HEAD -->

<body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white">
{{--<div class="page-wrapper">--}}
    {{--<!-- BEGIN HEADER -->--}}
    {{--<div class="page-header navbar navbar-fixed-top">--}}
        {{--<!-- BEGIN HEADER INNER -->--}}
        {{--<div class="page-header-inner ">--}}
            {{--<!-- BEGIN LOGO -->--}}
            {{--<div class="page-logo">--}}
                {{--<a href="{{url('/')}}">--}}
                    {{--<img src="{{url('public/projectDetails')}}/{{$projectDetails->project_logo}}" style="margin: 15px 0 0;width: 150px;max-height: 30px;" class="logo-default" />--}}
                {{--</a>--}}
            {{--</div>--}}
            {{--<!-- END LOGO -->--}}

            {{--<div class="hor-menu   hidden-sm hidden-xs">--}}
                {{--<ul class="nav navbar-nav">--}}
                {{--<!-- DOC: Remove data-hover="megamenu-dropdown" and data-close-others="true" attributes below to disable the horizontal opening on mouse hover--}}
                            {{--<li class="classic-menu-dropdown active" aria-haspopup="true">--}}
                                {{--<a href="{{url('/')}}"> Dashboard--}}
                                    {{--<span class="selected"> </span>--}}
                                {{--</a>--}}
                            {{--</li>-->--}}

                    {{--@if($id->id == '1000')--}}
                        {{--<li class="mega-menu-dropdown mega-menu-full" aria-haspopup="true">--}}
                            {{--<a href="javascript:;" class="dropdown-toggle" data-hover="megamenu-dropdown" data-close-others="true">--}}
                                {{--Dev--}}
                                {{--<i class="fa fa-angle-down"></i>--}}
                            {{--</a>--}}
                            {{--<ul class="dropdown-menu">--}}
                                {{--<li>--}}
                                    {{--<div class="mega-menu-content">--}}
                                        {{--<div class="row">--}}

                                            {{--<div class="col-md-4">--}}
                                                {{--<ul class="mega-menu-submenu">--}}
                                                    {{--<li>--}}
                                                        {{--<a href="{{url('project-details')}}">Project Details</a>--}}
                                                    {{--</li>--}}
                                                    {{--<li>--}}
                                                        {{--<a href="{{url('main-menu-view')}}">Main Menu</a>--}}
                                                    {{--</li>--}}
                                                    {{--<li>--}}
                                                        {{--<a href="{{url('sub-menu-view')}}">Sub Menu</a>--}}
                                                    {{--</li>--}}
                                                    {{--<li>--}}
                                                        {{--<a href="{{url('priority')}}">Priority</a>--}}
                                                    {{--</li>--}}
                                                    {{--<li>--}}
                                                        {{--<a href="{{url('appmodule')}}">App Module</a>--}}
                                                    {{--</li>--}}
                                                {{--</ul>--}}
                                            {{--</div>--}}

                                        {{--</div>--}}
                                    {{--</div>--}}
                                {{--</li>--}}
                            {{--</ul>--}}
                        {{--</li>--}}
                    {{--@endif--}}


                    {{--@if($id->id=="1000")--}}

                        {{--@if(count($Adminminlink) > 0)--}}
                            {{--@foreach($Adminminlink as $showMainlink)--}}
                                {{--@if($showMainlink->routeName == '#')--}}
                                    {{--<li class="mega-menu-dropdown mega-menu-full" aria-haspopup="true">--}}
                                        {{--<a href="javascript:;" class="dropdown-toggle" data-hover="megamenu-dropdown" data-close-others="true">--}}
                                            {{--<i class="{{$showMainlink->icon}}"></i>--}}
                                            {{--{{$showMainlink->Link_Name}}--}}
                                            {{--<i class="fa fa-angle-down"></i>--}}
                                        {{--</a>--}}
                                        {{--<ul class="dropdown-menu">--}}
                                            {{--<li>--}}
                                                {{--<div class="mega-menu-content">--}}
                                                    {{--<div class="row">--}}

                                            {{--@if(count($adminsublink) > 0)--}}

                                                {{--@foreach($adminsublink as $showSubLink)--}}
                                                    {{--@if($showSubLink->mainmenuId == $showMainlink->id)--}}

                                                        {{--@if($showSubLink->routeName == '#')--}}

                                                            {{--@if(Controller::previousLabelExist($showSubLink)=="1")--}}
                                        {{--</ul>--}}
            {{--</div>--}}
            {{--<div class="col-md-4">--}}
                {{--<ul class="mega-menu-submenu">--}}
                    {{--@else--}}
                        {{--<div class="col-md-4">--}}
                            {{--<ul class="mega-menu-submenu">--}}
                                {{--@endif--}}
                                {{--<li>--}}
                                    {{--<h5><strong style="color:white">&nbsp;{{$showSubLink->submenuname}}</strong></h5>--}}
                                    {{--<hr style="margin: 5px;opacity: .5">--}}
                                {{--</li>--}}

                                {{--@else--}}
                                    {{--<li>--}}
                                        {{--<a href="{{url('/')}}/{{$showSubLink->routeName}}"><i class="fa fa-square-o"></i>&nbsp;{{$showSubLink->submenuname}}</a>--}}
                                    {{--</li>--}}
                                    {{--@if(Controller::nextLabelExist($showSubLink)=="0")--}}
                            {{--</ul>--}}
                        {{--</div>--}}
                        {{--<div class="col-md-4">--}}
                            {{--<ul class="mega-menu-submenu">--}}
                            {{--@endif--}}

                            {{--@endif--}}

                            {{--@endif--}}
                            {{--@endforeach--}}

                            {{--@endif--}}

                        {{--</div>--}}
            {{--</div>--}}
            {{--</li>--}}
            {{--</ul>--}}
            {{--</li>--}}
            {{--@else--}}
                {{--@if($showMainlink->routeName!="user-priority-level")--}}
                    {{--<li class="mega-menu-dropdown mega-menu-full">--}}
                        {{--<a href="{{url('/')}}/{{$showMainlink->routeName}}">--}}
                            {{--<i class="{{$showMainlink->icon}}"></i>&nbsp;{{$showMainlink->Link_Name}}--}}
                        {{--</a>--}}
                    {{--</li>--}}
                {{--@endif--}}
            {{--@endif--}}
            {{--@endforeach--}}
            {{--@endif--}}

            {{--<li class="mega-menu-dropdown mega-menu-full">--}}
                {{--<a href="{{url('/')}}/user-priority-level">--}}
                    {{--<i class="fa fa-gear"></i>&nbsp;User Priority Level--}}
                {{--</a>--}}
            {{--</li>--}}

            {{--@else--}}

                {{--@if(count($mainlink) > 0)--}}
                    {{--@foreach($mainlink as $showMainlink)--}}
                        {{--@if($showMainlink->routeName == '#')--}}
                            {{--<li class="mega-menu-dropdown mega-menu-full" aria-haspopup="true">--}}
                                {{--<a href="javascript:;" class="dropdown-toggle" data-hover="megamenu-dropdown" data-close-others="true">--}}
                                    {{--<i class="{{$showMainlink->icon}}"></i>--}}
                                    {{--{{$showMainlink->Link_Name}}--}}
                                    {{--<i class="fa fa-angle-down"></i>--}}
                                {{--</a>--}}
                                {{--<ul class="dropdown-menu">--}}
                                    {{--<li>--}}
                                        {{--<div class="mega-menu-content">--}}
                                            {{--<div class="row">--}}
                                    {{--@if(count($sublink) > 0)--}}

                                        {{--@foreach($sublink as $showSubLink)--}}
                                            {{--@if($showSubLink->mainmenuId == $showMainlink->id)--}}

                                                {{--@if($showSubLink->routeName == '#')--}}

                                                    {{--@if(Controller::previousLabelExist($showSubLink)=="1")--}}
                                {{--</ul>--}}
        {{--</div>--}}
        {{--<div class="col-md-4">--}}
            {{--<ul class="mega-menu-submenu">--}}
                {{--@else--}}
                    {{--<div class="col-md-4">--}}
                        {{--<ul class="mega-menu-submenu">--}}
                            {{--@endif--}}
                            {{--<li>--}}
                                {{--<h5><strong style="color:white">{{$showSubLink->submenuname}}</strong></h5>--}}
                                {{--<hr style="margin: 5px">--}}
                            {{--</li>--}}

                            {{--@else--}}
                                {{--<li>--}}
                                    {{--<a href="{{url('/')}}/{{$showSubLink->routeName}}"><i class="fa fa-square-o"></i>&nbsp;{{$showSubLink->submenuname}}</a>--}}
                                {{--</li>--}}
                                {{--@if(Controller::nextLabelExist($showSubLink)=="0")--}}
                        {{--</ul>--}}
                    {{--</div>--}}
                    {{--<div class="col-md-4">--}}
                        {{--<ul class="mega-menu-submenu">--}}
                        {{--@endif--}}

                        {{--@endif--}}

                        {{--@endif--}}
                        {{--@endforeach--}}

                        {{--@endif--}}
                    {{--</div>--}}
        {{--</div>--}}
        {{--</li>--}}
        {{--</ul>--}}
        {{--</li>--}}
        {{--@else--}}
            {{--@if($showMainlink->routeName!="user-priority-level")--}}
                {{--<li class="mega-menu-dropdown mega-menu-full">--}}
                    {{--<a href="{{url('/')}}/{{$showMainlink->routeName}}">--}}
                        {{--<i class="{{$showMainlink->icon}}"></i>&nbsp;{{$showMainlink->Link_Name}}--}}
                    {{--</a>--}}
                {{--</li>--}}
                {{--@endif--}}
                {{--@endif--}}
                {{--@endforeach--}}
                {{--@endif--}}

                {{--@endif--}}

                {{--</ul>--}}
    {{--</div>--}}
    {{--<!-- END MEGA MENU -->--}}
    {{--<!-- BEGIN HEADER SEARCH BOX -->--}}
{{--<!-- DOC: Apply "search-form-expanded" right after the "search-form" class to have half expanded search box ----}}
                    {{--<form class="search-form" action="{{url('/')}}" onsubmit="return false;" method="post">--}}
                        {{--<div class="input-group">--}}
                            {{--<input type="text" class="form-control" placeholder="Search..." name="query">--}}
                            {{--<span class="input-group-btn">--}}
                                {{--<a href="javascript:;" class="btn submit">--}}
                                    {{--<i class="icon-magnifier"></i>--}}
                                {{--</a>--}}
                            {{--</span>--}}
                        {{--</div>--}}
                    {{--</form>--}}
                    {{--<!-- END HEADER SEARCH BOX -->--}}

    {{--<!-- BEGIN RESPONSIVE MENU TOGGLER -->--}}
    {{--<a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">--}}
        {{--<span></span>--}}
    {{--</a>--}}
    {{--<!-- END RESPONSIVE MENU TOGGLER -->--}}
    {{--<!-- BEGIN TOP NAVIGATION MENU -->--}}
    {{--<div class="top-menu">--}}
        {{--<ul class="nav navbar-nav pull-right">--}}
            {{--<!-- BEGIN USER LOGIN DROPDOWN -->--}}
            {{--<!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->--}}
            {{--<li class="dropdown dropdown-user">--}}
                {{--<span id="suser_empid" style="display: none;">{{$id->suser_empid}}</span>--}}
                {{--<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" id="user_img_name">--}}

                {{--</a>--}}
                {{--<ul class="dropdown-menu dropdown-menu-default">--}}
                    {{--<li>--}}
                        {{--@if(Controller::checkAppModulePriority('employee-details','View')=="1")--}}
                            {{--<a href="{{url('employee-details')}}/{{$id->suser_empid}}/view"> <i class="fa fa-search"></i> My Information</a>--}}
                        {{--@endif--}}

                        {{--@if(Controller::checkAppModulePriority('employee-details','Change Password')=="1")--}}
                            {{--<a href="{{url('change-password')}}"> <i class="fa fa-pencil"></i> Change Password</a>--}}
                        {{--@endif--}}

                        {{--<a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();"> <i class="icon-key"></i> Log Out</a>--}}
                        {{--<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">--}}
                            {{--{{ csrf_field() }}--}}
                        {{--</form>--}}
                    {{--</li>--}}
                {{--</ul>--}}
            {{--</li>--}}
            {{--<!-- END USER LOGIN DROPDOWN -->--}}
            {{--<!-- BEGIN QUICK SIDEBAR TOGGLER -->--}}
            {{--<!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->--}}
            {{--<!-- <li class="dropdown dropdown-quick-sidebar-toggler">--}}
                {{--<a href="javascript:;" class="dropdown-toggle">--}}
                    {{--<i class="icon-logout"></i>--}}
                {{--</a>--}}
            {{--</li> -->--}}
            {{--<!-- END QUICK SIDEBAR TOGGLER -->--}}
        {{--</ul>--}}
    {{--</div>--}}
    {{--<!-- END TOP NAVIGATION MENU -->--}}
{{--</div>--}}
{{--<!-- END HEADER INNER -->--}}
{{--</div>--}}
<!-- END HEADER -->










<!-- new header start-->
<div id="kt_header" class="kt-header kt-grid__item  kt-header--fixed ">

    <!-- begin:: Header Menu -->

    <!-- Uncomment this to display the close button of the panel
<button class="kt-header-menu-wrapper-close" id="kt_header_menu_mobile_close_btn"><i class="la la-close"></i></button>
-->
    <div class="kt-header-menu-wrapper" id="kt_header_menu_wrapper">
        <div class="kt-header-logo">
            <a href="{{url('/')}}">
                <img alt="Logo" src="{{url('public/projectDetails')}}/{{$projectDetails->project_logo}}" style="margin: 15px 0 0;width: 150px;max-height: 30px;" />
            </a>
        </div>
        <div id="kt_header_menu" class="kt-header-menu kt-header-menu-mobile  kt-header-menu--layout-default hor-menu hidden-sm hidden-xs ">
            <ul class="kt-menu__nav">

                @if($id->id == '1000')
                    <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel" data-ktmenu-submenu-toggle="click" aria-haspopup="true"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><span class="kt-menu__link-text">Dev</span><i class=""></i></a>
                        <div class="kt-menu__submenu  kt-menu__submenu--fixed kt-menu__submenu--left" style="width:1000px">
                            <div class="kt-menu__subnav">
                                <ul class="kt-menu__content">
                                    <li class="kt-menu__item ">
                                        <ul class="kt-menu__inner">
                                            <li class="kt-menu__item " aria-haspopup="true"><a href="{{url('project-details')}}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Project Details</span></a></li>
                                            <li class="kt-menu__item " aria-haspopup="true"><a href="{{url('main-menu-view')}}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Main Men</span></a></li>
                                            <li class="kt-menu__item " aria-haspopup="true"><a href="{{url('sub-menu-view')}}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Sub Menu</span></a></li>
                                            <li class="kt-menu__item " aria-haspopup="true"><a href="{{url('priority')}}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Priority</span></a></li>
                                            <li class="kt-menu__item " aria-haspopup="true"><a href="{{url('appmodule')}}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">App Module</span></a></li>
                                        </ul>
                                    </li>

                                </ul>
                            </div>
                        </div>
                    </li>
                @endif
                    @if($id->id=="1000")
                        @if(count($Adminminlink) > 0)
                            @foreach($Adminminlink as $showMainlink)
                                @if($showMainlink->routeName == '#')

                <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel"  data-ktmenu-submenu-toggle="click" aria-haspopup="true"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><span class="kt-menu__link-text" > {{$showMainlink->Link_Name}}</span><i class="{{$showMainlink->icon}}"></i></a>

                    <div class="kt-menu__submenu  kt-menu__submenu--fixed kt-menu__submenu--left" style="width:800px">
                        <div class="kt-menu__subnav">
                            <ul class="kt-menu__content">
                                <li class="kt-menu__item ">
                                    <div class="row">

                                    @if(count($adminsublink) > 0)

                                        @foreach($adminsublink as $showSubLink)
                                            @if($showSubLink->mainmenuId == $showMainlink->id)

                                                @if($showSubLink->routeName == '#')

                                                    @if(Controller::previousLabelExist($showSubLink)=="1")

                            </ul>
                                    <ul class="kt-menu__inner">
                                        @else
                                            <ul class="kt-menu__inner">

                                                @endif
                                                <h3 class="kt-menu__heading kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">&nbsp;{{$showSubLink->submenuname}}</span><i class="kt-menu__ver-arrow la la-angle-right"></i></h3>
                                                <hr style="margin: 10px;opacity: 1">
                                                @else
                                        <li class="kt-menu__item " aria-haspopup="true"><a href="{{url('/')}}/{{$showSubLink->routeName}}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">{{$showSubLink->submenuname}}</span></a></li>
                                                    @if(Controller::nextLabelExist($showSubLink)=="0")

                                    </ul>
                                    </ul>

                         <ul class="kt-menu__inner">
                             @endif

                             @endif

                             @endif
                             @endforeach

                             @endif

                            </ul>


    @else
    @if($showMainlink->routeName!="user-priority-level")
            <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel" data-ktmenu-submenu-toggle="click" aria-haspopup="true"><a href="{{url('/')}}/{{$showMainlink->routeName}}" class="kt-menu__link kt-menu__toggle"><span class="kt-menu__link-text">{{$showMainlink->Link_Name}}</span><i class=""></i></a>

             </li>

            @endif
            @endif
            @endforeach
            @endif

    <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel" data-ktmenu-submenu-toggle="click" aria-haspopup="true"><a href="{{url('/')}}/user-priority-level" class="kt-menu__link kt-menu__toggle"><span class="kt-menu__link-text">User Priority Level</span><i class=""></i></a>

    </li>
    @else
    @if(count($mainlink) > 0)
    @foreach($mainlink as $showMainlink)
    @if($showMainlink->routeName == '#')

                    <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel" data-ktmenu-submenu-toggle="click" aria-haspopup="true"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><span class="kt-menu__link-text">  {{$showMainlink->Link_Name}}</span><i class="{{$showMainlink->icon}}"></i></a>

                        <div class="kt-menu__submenu  kt-menu__submenu--fixed kt-menu__submenu--left" style="width:1000px">
                            <div class="kt-menu__subnav">
                                <ul class="kt-menu__content">
                                    <div class="row">
                                    <li class="kt-menu__item ">
                                    @if(count($sublink) > 0)
                                     @foreach($sublink as $showSubLink)
                                        @if($showSubLink->mainmenuId == $showMainlink->id)

                                            @if($showSubLink->routeName == '#')

                                                @if(Controller::previousLabelExist($showSubLink)=="1")
                                  </ul>
                        <ul class="kt-menu__inner">
                            @else
                                <ul class="kt-menu__inner">
                                    @endif

                                    <h3 class="kt-menu__heading kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">&nbsp;{{$showSubLink->submenuname}}</span><i class="kt-menu__ver-arrow la la-angle-right"></i></h3>
                                    <hr style="margin: 5px;opacity: .5">
                                    @else
                                        <li class="kt-menu__item " aria-haspopup="true"><a href="{{url('/')}}/{{$showSubLink->routeName}}" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">{{$showSubLink->submenuname}}</span></a></li>
                                        @if(Controller::nextLabelExist($showSubLink)=="0")

                                </ul>
                                </li>
                                <ul class="kt-menu__inner">
                                    @endif

                                    @endif

                                    @endif
                                    @endforeach

                                    @endif

                                </ul>


</li>

@else
    @if($showMainlink->routeName!="user-priority-level")
        <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel" data-ktmenu-submenu-toggle="click" aria-haspopup="true"><a href="{{url('/')}}/{{$showMainlink->routeName}}" class="kt-menu__link kt-menu__toggle"><span class="kt-menu__link-text">{{$showMainlink->Link_Name}}</span><i class="{{$showMainlink->icon}}"></i></a>

        </li>
        @endif
        @endif
        @endforeach
        @endif

        @endif


            </ul>
        </div>
    </div>

    <!-- end:: Header Menu -->

    <!-- begin:: Header Topbar -->
    <div class="kt-header__topbar">


        <!--begin: Notifications -->
        <div class="kt-header__topbar-item dropdown">
            <div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="30px,0px" aria-expanded="true">
									<span class="kt-header__topbar-icon kt-pulse kt-pulse--brand">
										<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
											<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
												<rect x="0" y="0" width="24" height="24" />
												<path d="M2.56066017,10.6819805 L4.68198052,8.56066017 C5.26776695,7.97487373 6.21751442,7.97487373 6.80330086,8.56066017 L8.9246212,10.6819805 C9.51040764,11.267767 9.51040764,12.2175144 8.9246212,12.8033009 L6.80330086,14.9246212 C6.21751442,15.5104076 5.26776695,15.5104076 4.68198052,14.9246212 L2.56066017,12.8033009 C1.97487373,12.2175144 1.97487373,11.267767 2.56066017,10.6819805 Z M14.5606602,10.6819805 L16.6819805,8.56066017 C17.267767,7.97487373 18.2175144,7.97487373 18.8033009,8.56066017 L20.9246212,10.6819805 C21.5104076,11.267767 21.5104076,12.2175144 20.9246212,12.8033009 L18.8033009,14.9246212 C18.2175144,15.5104076 17.267767,15.5104076 16.6819805,14.9246212 L14.5606602,12.8033009 C13.9748737,12.2175144 13.9748737,11.267767 14.5606602,10.6819805 Z" fill="#000000" opacity="0.3" />
												<path d="M8.56066017,16.6819805 L10.6819805,14.5606602 C11.267767,13.9748737 12.2175144,13.9748737 12.8033009,14.5606602 L14.9246212,16.6819805 C15.5104076,17.267767 15.5104076,18.2175144 14.9246212,18.8033009 L12.8033009,20.9246212 C12.2175144,21.5104076 11.267767,21.5104076 10.6819805,20.9246212 L8.56066017,18.8033009 C7.97487373,18.2175144 7.97487373,17.267767 8.56066017,16.6819805 Z M8.56066017,4.68198052 L10.6819805,2.56066017 C11.267767,1.97487373 12.2175144,1.97487373 12.8033009,2.56066017 L14.9246212,4.68198052 C15.5104076,5.26776695 15.5104076,6.21751442 14.9246212,6.80330086 L12.8033009,8.9246212 C12.2175144,9.51040764 11.267767,9.51040764 10.6819805,8.9246212 L8.56066017,6.80330086 C7.97487373,6.21751442 7.97487373,5.26776695 8.56066017,4.68198052 Z" fill="#000000" />
											</g>
										</svg> <span class="kt-pulse__ring"></span>
									</span>

            </div>
            <div class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-top-unround dropdown-menu-lg">
                <form>

                    <!--begin: Head -->
                    <div class="kt-head kt-head--skin-dark kt-head--fit-x kt-head--fit-b" style="background-image: url(public/assets/media/misc/bg-1.jpg)">
                        <h3 class="kt-head__title">
                            User Notifications
                            &nbsp;
                            <span class="btn btn-success btn-sm btn-bold btn-font-md">23 new</span>
                        </h3>
                        <ul class="nav nav-tabs nav-tabs-line nav-tabs-bold nav-tabs-line-3x nav-tabs-line-success kt-notification-item-padding-x" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active show" data-toggle="tab" href="#topbar_notifications_notifications" role="tab" aria-selected="true">Alerts</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#topbar_notifications_events" role="tab" aria-selected="false">Events</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#topbar_notifications_logs" role="tab" aria-selected="false">Logs</a>
                            </li>
                        </ul>
                    </div>

                    <!--end: Head -->
                    <div class="tab-content">
                        <div class="tab-pane active show" id="topbar_notifications_notifications" role="tabpanel">
                            <div class="kt-notification kt-margin-t-10 kt-margin-b-10 kt-scroll" data-scroll="true" data-height="300" data-mobile-height="200">
                                <a href="#" class="kt-notification__item">
                                    <div class="kt-notification__item-icon">
                                        <i class="flaticon2-line-chart kt-font-success"></i>
                                    </div>
                                    <div class="kt-notification__item-details">
                                        <div class="kt-notification__item-title">
                                            New order has been received
                                        </div>
                                        <div class="kt-notification__item-time">
                                            2 hrs ago
                                        </div>
                                    </div>
                                </a>
                                <a href="#" class="kt-notification__item">
                                    <div class="kt-notification__item-icon">
                                        <i class="flaticon2-box-1 kt-font-brand"></i>
                                    </div>
                                    <div class="kt-notification__item-details">
                                        <div class="kt-notification__item-title">
                                            New customer is registered
                                        </div>
                                        <div class="kt-notification__item-time">
                                            3 hrs ago
                                        </div>
                                    </div>
                                </a>
                                <a href="#" class="kt-notification__item">
                                    <div class="kt-notification__item-icon">
                                        <i class="flaticon2-chart2 kt-font-danger"></i>
                                    </div>
                                    <div class="kt-notification__item-details">
                                        <div class="kt-notification__item-title">
                                            Application has been approved
                                        </div>
                                        <div class="kt-notification__item-time">
                                            3 hrs ago
                                        </div>
                                    </div>
                                </a>
                                <a href="#" class="kt-notification__item">
                                    <div class="kt-notification__item-icon">
                                        <i class="flaticon2-image-file kt-font-warning"></i>
                                    </div>
                                    <div class="kt-notification__item-details">
                                        <div class="kt-notification__item-title">
                                            New file has been uploaded
                                        </div>
                                        <div class="kt-notification__item-time">
                                            5 hrs ago
                                        </div>
                                    </div>
                                </a>
                                <a href="#" class="kt-notification__item">
                                    <div class="kt-notification__item-icon">
                                        <i class="flaticon2-drop kt-font-info"></i>
                                    </div>
                                    <div class="kt-notification__item-details">
                                        <div class="kt-notification__item-title">
                                            New user feedback received
                                        </div>
                                        <div class="kt-notification__item-time">
                                            8 hrs ago
                                        </div>
                                    </div>
                                </a>
                                <a href="#" class="kt-notification__item">
                                    <div class="kt-notification__item-icon">
                                        <i class="flaticon2-pie-chart-2 kt-font-success"></i>
                                    </div>
                                    <div class="kt-notification__item-details">
                                        <div class="kt-notification__item-title">
                                            System reboot has been successfully completed
                                        </div>
                                        <div class="kt-notification__item-time">
                                            12 hrs ago
                                        </div>
                                    </div>
                                </a>
                                <a href="#" class="kt-notification__item">
                                    <div class="kt-notification__item-icon">
                                        <i class="flaticon2-favourite kt-font-danger"></i>
                                    </div>
                                    <div class="kt-notification__item-details">
                                        <div class="kt-notification__item-title">
                                            New order has been placed
                                        </div>
                                        <div class="kt-notification__item-time">
                                            15 hrs ago
                                        </div>
                                    </div>
                                </a>
                                <a href="#" class="kt-notification__item kt-notification__item--read">
                                    <div class="kt-notification__item-icon">
                                        <i class="flaticon2-safe kt-font-primary"></i>
                                    </div>
                                    <div class="kt-notification__item-details">
                                        <div class="kt-notification__item-title">
                                            Company meeting canceled
                                        </div>
                                        <div class="kt-notification__item-time">
                                            19 hrs ago
                                        </div>
                                    </div>
                                </a>
                                <a href="#" class="kt-notification__item">
                                    <div class="kt-notification__item-icon">
                                        <i class="flaticon2-psd kt-font-success"></i>
                                    </div>
                                    <div class="kt-notification__item-details">
                                        <div class="kt-notification__item-title">
                                            New report has been received
                                        </div>
                                        <div class="kt-notification__item-time">
                                            23 hrs ago
                                        </div>
                                    </div>
                                </a>
                                <a href="#" class="kt-notification__item">
                                    <div class="kt-notification__item-icon">
                                        <i class="flaticon-download-1 kt-font-danger"></i>
                                    </div>
                                    <div class="kt-notification__item-details">
                                        <div class="kt-notification__item-title">
                                            Finance report has been generated
                                        </div>
                                        <div class="kt-notification__item-time">
                                            25 hrs ago
                                        </div>
                                    </div>
                                </a>
                                <a href="#" class="kt-notification__item">
                                    <div class="kt-notification__item-icon">
                                        <i class="flaticon-security kt-font-warning"></i>
                                    </div>
                                    <div class="kt-notification__item-details">
                                        <div class="kt-notification__item-title">
                                            New customer comment recieved
                                        </div>
                                        <div class="kt-notification__item-time">
                                            2 days ago
                                        </div>
                                    </div>
                                </a>
                                <a href="#" class="kt-notification__item">
                                    <div class="kt-notification__item-icon">
                                        <i class="flaticon2-pie-chart kt-font-success"></i>
                                    </div>
                                    <div class="kt-notification__item-details">
                                        <div class="kt-notification__item-title">
                                            New customer is registered
                                        </div>
                                        <div class="kt-notification__item-time">
                                            3 days ago
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="tab-pane" id="topbar_notifications_events" role="tabpanel">
                            <div class="kt-notification kt-margin-t-10 kt-margin-b-10 kt-scroll" data-scroll="true" data-height="300" data-mobile-height="200">
                                <a href="#" class="kt-notification__item">
                                    <div class="kt-notification__item-icon">
                                        <i class="flaticon2-psd kt-font-success"></i>
                                    </div>
                                    <div class="kt-notification__item-details">
                                        <div class="kt-notification__item-title">
                                            New report has been received
                                        </div>
                                        <div class="kt-notification__item-time">
                                            23 hrs ago
                                        </div>
                                    </div>
                                </a>
                                <a href="#" class="kt-notification__item">
                                    <div class="kt-notification__item-icon">
                                        <i class="flaticon-download-1 kt-font-danger"></i>
                                    </div>
                                    <div class="kt-notification__item-details">
                                        <div class="kt-notification__item-title">
                                            Finance report has been generated
                                        </div>
                                        <div class="kt-notification__item-time">
                                            25 hrs ago
                                        </div>
                                    </div>
                                </a>
                                <a href="#" class="kt-notification__item">
                                    <div class="kt-notification__item-icon">
                                        <i class="flaticon2-line-chart kt-font-success"></i>
                                    </div>
                                    <div class="kt-notification__item-details">
                                        <div class="kt-notification__item-title">
                                            New order has been received
                                        </div>
                                        <div class="kt-notification__item-time">
                                            2 hrs ago
                                        </div>
                                    </div>
                                </a>
                                <a href="#" class="kt-notification__item">
                                    <div class="kt-notification__item-icon">
                                        <i class="flaticon2-box-1 kt-font-brand"></i>
                                    </div>
                                    <div class="kt-notification__item-details">
                                        <div class="kt-notification__item-title">
                                            New customer is registered
                                        </div>
                                        <div class="kt-notification__item-time">
                                            3 hrs ago
                                        </div>
                                    </div>
                                </a>
                                <a href="#" class="kt-notification__item">
                                    <div class="kt-notification__item-icon">
                                        <i class="flaticon2-chart2 kt-font-danger"></i>
                                    </div>
                                    <div class="kt-notification__item-details">
                                        <div class="kt-notification__item-title">
                                            Application has been approved
                                        </div>
                                        <div class="kt-notification__item-time">
                                            3 hrs ago
                                        </div>
                                    </div>
                                </a>
                                <a href="#" class="kt-notification__item">
                                    <div class="kt-notification__item-icon">
                                        <i class="flaticon2-image-file kt-font-warning"></i>
                                    </div>
                                    <div class="kt-notification__item-details">
                                        <div class="kt-notification__item-title">
                                            New file has been uploaded
                                        </div>
                                        <div class="kt-notification__item-time">
                                            5 hrs ago
                                        </div>
                                    </div>
                                </a>
                                <a href="#" class="kt-notification__item">
                                    <div class="kt-notification__item-icon">
                                        <i class="flaticon2-drop kt-font-info"></i>
                                    </div>
                                    <div class="kt-notification__item-details">
                                        <div class="kt-notification__item-title">
                                            New user feedback received
                                        </div>
                                        <div class="kt-notification__item-time">
                                            8 hrs ago
                                        </div>
                                    </div>
                                </a>
                                <a href="#" class="kt-notification__item">
                                    <div class="kt-notification__item-icon">
                                        <i class="flaticon2-pie-chart-2 kt-font-success"></i>
                                    </div>
                                    <div class="kt-notification__item-details">
                                        <div class="kt-notification__item-title">
                                            System reboot has been successfully completed
                                        </div>
                                        <div class="kt-notification__item-time">
                                            12 hrs ago
                                        </div>
                                    </div>
                                </a>
                                <a href="#" class="kt-notification__item">
                                    <div class="kt-notification__item-icon">
                                        <i class="flaticon2-favourite kt-font-brand"></i>
                                    </div>
                                    <div class="kt-notification__item-details">
                                        <div class="kt-notification__item-title">
                                            New order has been placed
                                        </div>
                                        <div class="kt-notification__item-time">
                                            15 hrs ago
                                        </div>
                                    </div>
                                </a>
                                <a href="#" class="kt-notification__item kt-notification__item--read">
                                    <div class="kt-notification__item-icon">
                                        <i class="flaticon2-safe kt-font-primary"></i>
                                    </div>
                                    <div class="kt-notification__item-details">
                                        <div class="kt-notification__item-title">
                                            Company meeting canceled
                                        </div>
                                        <div class="kt-notification__item-time">
                                            19 hrs ago
                                        </div>
                                    </div>
                                </a>
                                <a href="#" class="kt-notification__item">
                                    <div class="kt-notification__item-icon">
                                        <i class="flaticon2-psd kt-font-success"></i>
                                    </div>
                                    <div class="kt-notification__item-details">
                                        <div class="kt-notification__item-title">
                                            New report has been received
                                        </div>
                                        <div class="kt-notification__item-time">
                                            23 hrs ago
                                        </div>
                                    </div>
                                </a>
                                <a href="#" class="kt-notification__item">
                                    <div class="kt-notification__item-icon">
                                        <i class="flaticon-download-1 kt-font-danger"></i>
                                    </div>
                                    <div class="kt-notification__item-details">
                                        <div class="kt-notification__item-title">
                                            Finance report has been generated
                                        </div>
                                        <div class="kt-notification__item-time">
                                            25 hrs ago
                                        </div>
                                    </div>
                                </a>
                                <a href="#" class="kt-notification__item">
                                    <div class="kt-notification__item-icon">
                                        <i class="flaticon-security kt-font-warning"></i>
                                    </div>
                                    <div class="kt-notification__item-details">
                                        <div class="kt-notification__item-title">
                                            New customer comment recieved
                                        </div>
                                        <div class="kt-notification__item-time">
                                            2 days ago
                                        </div>
                                    </div>
                                </a>
                                <a href="#" class="kt-notification__item">
                                    <div class="kt-notification__item-icon">
                                        <i class="flaticon2-pie-chart kt-font-success"></i>
                                    </div>
                                    <div class="kt-notification__item-details">
                                        <div class="kt-notification__item-title">
                                            New customer is registered
                                        </div>
                                        <div class="kt-notification__item-time">
                                            3 days ago
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="tab-pane" id="topbar_notifications_logs" role="tabpanel">
                            <div class="kt-grid kt-grid--ver" style="min-height: 200px;">
                                <div class="kt-grid kt-grid--hor kt-grid__item kt-grid__item--fluid kt-grid__item--middle">
                                    <div class="kt-grid__item kt-grid__item--middle kt-align-center">
                                        All caught up!
                                        <br>No new notifications.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!--end: Notifications -->

        <!--begin: Quick Actions -->
        <div class="kt-header__topbar-item dropdown">
            <div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="30px,0px" aria-expanded="true">
									<span class="kt-header__topbar-icon">
										<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
											<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
												<rect x="0" y="0" width="24" height="24" />
												<rect fill="#000000" opacity="0.3" x="13" y="4" width="3" height="16" rx="1.5" />
												<rect fill="#000000" x="8" y="9" width="3" height="11" rx="1.5" />
												<rect fill="#000000" x="18" y="11" width="3" height="9" rx="1.5" />
												<rect fill="#000000" x="3" y="13" width="3" height="7" rx="1.5" />
											</g>
										</svg> </span>
            </div>
            <div class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-top-unround dropdown-menu-xl">
                <form>

                    <!--begin: Head -->
                    <div class="kt-head kt-head--skin-dark" style="background-image: url(assets/media/misc/bg-1.jpg)">
                        <h3 class="kt-head__title">
                            User Quick Actions
                            <span class="kt-space-15"></span>
                            <span class="btn btn-success btn-sm btn-bold btn-font-md">23 tasks pending</span>
                        </h3>
                    </div>

                    <!--end: Head -->

                    <!--begin: Grid Nav -->
                    <div class="kt-grid-nav kt-grid-nav--skin-light">
                        <div class="kt-grid-nav__row">
                            <a href="#" class="kt-grid-nav__item">
													<span class="kt-grid-nav__icon">
														<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon kt-svg-icon--success kt-svg-icon--lg">
															<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
																<rect x="0" y="0" width="24" height="24" />
																<path d="M4.3618034,10.2763932 L4.8618034,9.2763932 C4.94649941,9.10700119 5.11963097,9 5.30901699,9 L15.190983,9 C15.4671254,9 15.690983,9.22385763 15.690983,9.5 C15.690983,9.57762255 15.6729105,9.65417908 15.6381966,9.7236068 L15.1381966,10.7236068 C15.0535006,10.8929988 14.880369,11 14.690983,11 L4.80901699,11 C4.53287462,11 4.30901699,10.7761424 4.30901699,10.5 C4.30901699,10.4223775 4.32708954,10.3458209 4.3618034,10.2763932 Z M14.6381966,13.7236068 L14.1381966,14.7236068 C14.0535006,14.8929988 13.880369,15 13.690983,15 L4.80901699,15 C4.53287462,15 4.30901699,14.7761424 4.30901699,14.5 C4.30901699,14.4223775 4.32708954,14.3458209 4.3618034,14.2763932 L4.8618034,13.2763932 C4.94649941,13.1070012 5.11963097,13 5.30901699,13 L14.190983,13 C14.4671254,13 14.690983,13.2238576 14.690983,13.5 C14.690983,13.5776225 14.6729105,13.6541791 14.6381966,13.7236068 Z" fill="#000000" opacity="0.3" />
																<path d="M17.369,7.618 C16.976998,7.08599734 16.4660031,6.69750122 15.836,6.4525 C15.2059968,6.20749878 14.590003,6.085 13.988,6.085 C13.2179962,6.085 12.5180032,6.2249986 11.888,6.505 C11.2579969,6.7850014 10.7155023,7.16999755 10.2605,7.66 C9.80549773,8.15000245 9.45550123,8.72399671 9.2105,9.382 C8.96549878,10.0400033 8.843,10.7539961 8.843,11.524 C8.843,12.3360041 8.96199881,13.0779966 9.2,13.75 C9.43800119,14.4220034 9.7774978,14.9994976 10.2185,15.4825 C10.6595022,15.9655024 11.1879969,16.3399987 11.804,16.606 C12.4200031,16.8720013 13.1129962,17.005 13.883,17.005 C14.681004,17.005 15.3879969,16.8475016 16.004,16.5325 C16.6200031,16.2174984 17.1169981,15.8010026 17.495,15.283 L19.616,16.774 C18.9579967,17.6000041 18.1530048,18.2404977 17.201,18.6955 C16.2489952,19.1505023 15.1360064,19.378 13.862,19.378 C12.6999942,19.378 11.6325049,19.1855019 10.6595,18.8005 C9.68649514,18.4154981 8.8500035,17.8765035 8.15,17.1835 C7.4499965,16.4904965 6.90400196,15.6645048 6.512,14.7055 C6.11999804,13.7464952 5.924,12.6860058 5.924,11.524 C5.924,10.333994 6.13049794,9.25950479 6.5435,8.3005 C6.95650207,7.34149521 7.5234964,6.52600336 8.2445,5.854 C8.96550361,5.18199664 9.8159951,4.66400182 10.796,4.3 C11.7760049,3.93599818 12.8399943,3.754 13.988,3.754 C14.4640024,3.754 14.9609974,3.79949954 15.479,3.8905 C15.9970026,3.98150045 16.4939976,4.12149906 16.97,4.3105 C17.4460024,4.49950095 17.8939979,4.7339986 18.314,5.014 C18.7340021,5.2940014 19.0909985,5.62999804 19.385,6.022 L17.369,7.618 Z" fill="#000000" />
															</g>
														</svg> </span>
                                <span class="kt-grid-nav__title">Accounting</span>
                                <span class="kt-grid-nav__desc">eCommerce</span>
                            </a>
                            <a href="#" class="kt-grid-nav__item">
													<span class="kt-grid-nav__icon">
														<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon kt-svg-icon--success kt-svg-icon--lg">
															<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
																<rect x="0" y="0" width="24" height="24" />
																<path d="M14.8571499,13 C14.9499122,12.7223297 15,12.4263059 15,12.1190476 L15,6.88095238 C15,5.28984632 13.6568542,4 12,4 L11.7272727,4 C10.2210416,4 9,5.17258756 9,6.61904762 L10.0909091,6.61904762 C10.0909091,5.75117158 10.823534,5.04761905 11.7272727,5.04761905 L12,5.04761905 C13.0543618,5.04761905 13.9090909,5.86843034 13.9090909,6.88095238 L13.9090909,12.1190476 C13.9090909,12.4383379 13.8240964,12.7385644 13.6746497,13 L10.3253503,13 C10.1759036,12.7385644 10.0909091,12.4383379 10.0909091,12.1190476 L10.0909091,9.5 C10.0909091,9.06606198 10.4572216,8.71428571 10.9090909,8.71428571 C11.3609602,8.71428571 11.7272727,9.06606198 11.7272727,9.5 L11.7272727,11.3333333 L12.8181818,11.3333333 L12.8181818,9.5 C12.8181818,8.48747796 11.9634527,7.66666667 10.9090909,7.66666667 C9.85472911,7.66666667 9,8.48747796 9,9.5 L9,12.1190476 C9,12.4263059 9.0500878,12.7223297 9.14285008,13 L6,13 C5.44771525,13 5,12.5522847 5,12 L5,3 C5,2.44771525 5.44771525,2 6,2 L18,2 C18.5522847,2 19,2.44771525 19,3 L19,12 C19,12.5522847 18.5522847,13 18,13 L14.8571499,13 Z" fill="#000000" opacity="0.3" />
																<path d="M9,10.3333333 L9,12.1190476 C9,13.7101537 10.3431458,15 12,15 C13.6568542,15 15,13.7101537 15,12.1190476 L15,10.3333333 L20.2072547,6.57253826 C20.4311176,6.4108595 20.7436609,6.46126971 20.9053396,6.68513259 C20.9668779,6.77033951 21,6.87277228 21,6.97787787 L21,17 C21,18.1045695 20.1045695,19 19,19 L5,19 C3.8954305,19 3,18.1045695 3,17 L3,6.97787787 C3,6.70173549 3.22385763,6.47787787 3.5,6.47787787 C3.60510559,6.47787787 3.70753836,6.51099993 3.79274528,6.57253826 L9,10.3333333 Z M10.0909091,11.1212121 L12,12.5 L13.9090909,11.1212121 L13.9090909,12.1190476 C13.9090909,13.1315697 13.0543618,13.952381 12,13.952381 C10.9456382,13.952381 10.0909091,13.1315697 10.0909091,12.1190476 L10.0909091,11.1212121 Z" fill="#000000" />
															</g>
														</svg> </span>
                                <span class="kt-grid-nav__title">Administration</span>
                                <span class="kt-grid-nav__desc">Console</span>
                            </a>
                        </div>
                        <div class="kt-grid-nav__row">
                            <a href="#" class="kt-grid-nav__item">
													<span class="kt-grid-nav__icon">
														<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon kt-svg-icon--success kt-svg-icon--lg">
															<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
																<rect x="0" y="0" width="24" height="24" />
																<path d="M4,9.67471899 L10.880262,13.6470401 C10.9543486,13.689814 11.0320333,13.7207107 11.1111111,13.740321 L11.1111111,21.4444444 L4.49070127,17.526473 C4.18655139,17.3464765 4,17.0193034 4,16.6658832 L4,9.67471899 Z M20,9.56911707 L20,16.6658832 C20,17.0193034 19.8134486,17.3464765 19.5092987,17.526473 L12.8888889,21.4444444 L12.8888889,13.6728275 C12.9050191,13.6647696 12.9210067,13.6561758 12.9368301,13.6470401 L20,9.56911707 Z" fill="#000000" />
																<path d="M4.21611835,7.74669402 C4.30015839,7.64056877 4.40623188,7.55087574 4.5299008,7.48500698 L11.5299008,3.75665466 C11.8237589,3.60013944 12.1762411,3.60013944 12.4700992,3.75665466 L19.4700992,7.48500698 C19.5654307,7.53578262 19.6503066,7.60071528 19.7226939,7.67641889 L12.0479413,12.1074394 C11.9974761,12.1365754 11.9509488,12.1699127 11.9085461,12.2067543 C11.8661433,12.1699127 11.819616,12.1365754 11.7691509,12.1074394 L4.21611835,7.74669402 Z" fill="#000000" opacity="0.3" />
															</g>
														</svg> </span>
                                <span class="kt-grid-nav__title">Projects</span>
                                <span class="kt-grid-nav__desc">Pending Tasks</span>
                            </a>
                            <a href="#" class="kt-grid-nav__item">
													<span class="kt-grid-nav__icon">
														<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon kt-svg-icon--success kt-svg-icon--lg">
															<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
																<polygon points="0 0 24 0 24 24 0 24" />
																<path d="M18,14 C16.3431458,14 15,12.6568542 15,11 C15,9.34314575 16.3431458,8 18,8 C19.6568542,8 21,9.34314575 21,11 C21,12.6568542 19.6568542,14 18,14 Z M9,11 C6.790861,11 5,9.209139 5,7 C5,4.790861 6.790861,3 9,3 C11.209139,3 13,4.790861 13,7 C13,9.209139 11.209139,11 9,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
																<path d="M17.6011961,15.0006174 C21.0077043,15.0378534 23.7891749,16.7601418 23.9984937,20.4 C24.0069246,20.5466056 23.9984937,21 23.4559499,21 L19.6,21 C19.6,18.7490654 18.8562935,16.6718327 17.6011961,15.0006174 Z M0.00065168429,20.1992055 C0.388258525,15.4265159 4.26191235,13 8.98334134,13 C13.7712164,13 17.7048837,15.2931929 17.9979143,20.2 C18.0095879,20.3954741 17.9979143,21 17.2466999,21 C13.541124,21 8.03472472,21 0.727502227,21 C0.476712155,21 -0.0204617505,20.45918 0.00065168429,20.1992055 Z" fill="#000000" fill-rule="nonzero" />
															</g>
														</svg> </span>
                                <span class="kt-grid-nav__title">Customers</span>
                                <span class="kt-grid-nav__desc">Latest cases</span>
                            </a>
                        </div>
                    </div>

                    <!--end: Grid Nav -->
                </form>
            </div>
        </div>

        <!--end: Quick Actions -->

        <!--begin: My Cart -->
        <div class="kt-header__topbar-item dropdown">
            <div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="30px,0px" aria-expanded="true">
									<span class="kt-header__topbar-icon">
										<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
											<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
												<rect x="0" y="0" width="24" height="24" />
												<path d="M12,4.56204994 L7.76822128,9.6401844 C7.4146572,10.0644613 6.7840925,10.1217854 6.3598156,9.76822128 C5.9355387,9.4146572 5.87821464,8.7840925 6.23177872,8.3598156 L11.2317787,2.3598156 C11.6315738,1.88006147 12.3684262,1.88006147 12.7682213,2.3598156 L17.7682213,8.3598156 C18.1217854,8.7840925 18.0644613,9.4146572 17.6401844,9.76822128 C17.2159075,10.1217854 16.5853428,10.0644613 16.2317787,9.6401844 L12,4.56204994 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
												<path d="M3.5,9 L20.5,9 C21.0522847,9 21.5,9.44771525 21.5,10 C21.5,10.132026 21.4738562,10.2627452 21.4230769,10.3846154 L17.7692308,19.1538462 C17.3034221,20.271787 16.2111026,21 15,21 L9,21 C7.78889745,21 6.6965779,20.271787 6.23076923,19.1538462 L2.57692308,10.3846154 C2.36450587,9.87481408 2.60558331,9.28934029 3.11538462,9.07692308 C3.23725479,9.02614384 3.36797398,9 3.5,9 Z M12,17 C13.1045695,17 14,16.1045695 14,15 C14,13.8954305 13.1045695,13 12,13 C10.8954305,13 10,13.8954305 10,15 C10,16.1045695 10.8954305,17 12,17 Z" fill="#000000" />
											</g>
										</svg> </span>
            </div>
            <div class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-top-unround dropdown-menu-xl">
                <form>

                    <!-- begin:: Mycart -->
                    <div class="kt-mycart">
                        <div class="kt-mycart__head kt-head" style="background-image: url(public/assets/media/misc/bg-1.jpg);">
                            <div class="kt-mycart__info">
                                <span class="kt-mycart__icon"><i class="flaticon2-shopping-cart-1 kt-font-success"></i></span>
                                <h3 class="kt-mycart__title">My Cart</h3>
                            </div>
                            <div class="kt-mycart__button">
                                <button type="button" class="btn btn-success btn-sm" style=" ">2 Items</button>
                            </div>
                        </div>
                        <div class="kt-mycart__body kt-scroll" data-scroll="true" data-height="245" data-mobile-height="200">
                            <div class="kt-mycart__item">
                                <div class="kt-mycart__container">
                                    <div class="kt-mycart__info">
                                        <a href="#" class="kt-mycart__title">
                                            Samsung
                                        </a>
                                        <span class="kt-mycart__desc">
																Profile info, Timeline etc
															</span>
                                        <div class="kt-mycart__action">
                                            <span class="kt-mycart__price">$ 450</span>
                                            <span class="kt-mycart__text">for</span>
                                            <span class="kt-mycart__quantity">7</span>
                                            <a href="#" class="btn btn-label-success btn-icon">&minus;</a>
                                            <a href="#" class="btn btn-label-success btn-icon">&plus;</a>
                                        </div>
                                    </div>
                                    <a href="#" class="kt-mycart__pic">
                                        <img src="{{url('/')}}/public/assets/media/products/product9.jpg" title="">
                                    </a>
                                </div>
                            </div>
                            <div class="kt-mycart__item">
                                <div class="kt-mycart__container">
                                    <div class="kt-mycart__info">
                                        <a href="#" class="kt-mycart__title">
                                            Panasonic
                                        </a>
                                        <span class="kt-mycart__desc">
																For PHoto & Others
															</span>
                                        <div class="kt-mycart__action">
                                            <span class="kt-mycart__price">$ 329</span>
                                            <span class="kt-mycart__text">for</span>
                                            <span class="kt-mycart__quantity">1</span>
                                            <a href="#" class="btn btn-label-success btn-icon">&minus;</a>
                                            <a href="#" class="btn btn-label-success btn-icon">&plus;</a>
                                        </div>
                                    </div>
                                    <a href="#" class="kt-mycart__pic">
                                        <img src="{{url('/')}}/public/assets/media/products/product13.jpg" title="">
                                    </a>
                                </div>
                            </div>
                            <div class="kt-mycart__item">
                                <div class="kt-mycart__container">
                                    <div class="kt-mycart__info">
                                        <a href="#" class="kt-mycart__title">
                                            Fujifilm
                                        </a>
                                        <span class="kt-mycart__desc">
																Profile info, Timeline etc
															</span>
                                        <div class="kt-mycart__action">
                                            <span class="kt-mycart__price">$ 520</span>
                                            <span class="kt-mycart__text">for</span>
                                            <span class="kt-mycart__quantity">6</span>
                                            <a href="#" class="btn btn-label-success btn-icon">&minus;</a>
                                            <a href="#" class="btn btn-label-success btn-icon">&plus;</a>
                                        </div>
                                    </div>
                                    <a href="#" class="kt-mycart__pic">
                                        <img src="{{url('/')}}/public/assets/media/products/product16.jpg" title="">
                                    </a>
                                </div>
                            </div>
                            <div class="kt-mycart__item">
                                <div class="kt-mycart__container">
                                    <div class="kt-mycart__info">
                                        <a href="#" class="kt-mycart__title">
                                            Candy Machine
                                        </a>
                                        <span class="kt-mycart__desc">
																For PHoto & Others
															</span>
                                        <div class="kt-mycart__action">
                                            <span class="kt-mycart__price">$ 784</span>
                                            <span class="kt-mycart__text">for</span>
                                            <span class="kt-mycart__quantity">4</span>
                                            <a href="#" class="btn btn-label-success btn-icon">&minus;</a>
                                            <a href="#" class="btn btn-label-success btn-icon">&plus;</a>
                                        </div>
                                    </div>
                                    <a href="#" class="kt-mycart__pic">
                                        <img src="{{url('/')}}/public/assets/media/products/product15.jpg" title="" alt="">
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="kt-mycart__footer">
                            <div class="kt-mycart__section">
                                <div class="kt-mycart__subtitel">
                                    <span>Sub Total</span>
                                    <span>Taxes</span>
                                    <span>Total</span>
                                </div>
                                <div class="kt-mycart__prices">
                                    <span>$ 840.00</span>
                                    <span>$ 72.00</span>
                                    <span class="kt-font-brand">$ 912.00</span>
                                </div>
                            </div>
                            <div class="kt-mycart__button kt-align-right">
                                <button type="button" class="btn btn-primary btn-sm">Place Order</button>
                            </div>
                        </div>
                    </div>

                    <!-- end:: Mycart -->
                </form>
            </div>
        </div>

        <!--end: My Cart -->

        <!--begin: User Bar -->
        {{--<div class="kt-header__topbar-item kt-header__topbar-item--user">--}}
            {{--<div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="0px,0px">--}}
                {{--<div class="kt-header__topbar-user">--}}
                    {{--<span class="kt-header__topbar-welcome kt-hidden-mobile">Hi,</span>--}}
                    {{--<span class="kt-header__topbar-username kt-hidden-mobile">Sean</span>--}}
                    {{--<img class="kt-hidden" alt="Pic" src="assets/media/users/300_25.jpg" />--}}

                    {{--<!--use below badge element instead the user avatar to display username's first letter(remove kt-hidden class to display it) -->--}}
                    {{--<span class="kt-badge kt-badge--username kt-badge--unified-success kt-badge--lg kt-badge--rounded kt-badge--bold">S</span>--}}
                {{--</div>--}}
            {{--</div>--}}
            {{--<div class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-top-unround dropdown-menu-xl">--}}

                {{--<!--begin: Head -->--}}
                {{--<div class="kt-user-card kt-user-card--skin-dark kt-notification-item-padding-x" style="background-image: url(assets/media/misc/bg-1.jpg)">--}}
                    {{--<div class="kt-user-card__avatar">--}}
                        {{--<img class="kt-hidden" alt="Pic" src="assets/media/users/300_25.jpg" />--}}

                        {{--<!--use below badge element instead the user avatar to display username's first letter(remove kt-hidden class to display it) -->--}}
                        {{--<span class="kt-badge kt-badge--lg kt-badge--rounded kt-badge--bold kt-font-success">S</span>--}}
                    {{--</div>--}}
                    {{--<div class="kt-user-card__name">--}}
                        {{--Sean Stone--}}
                    {{--</div>--}}
                    {{--<div class="kt-user-card__badge">--}}
                        {{--<span class="btn btn-success btn-sm btn-bold btn-font-md">23 messages</span>--}}
                    {{--</div>--}}
                {{--</div>--}}

                {{--<!--end: Head -->--}}

                {{--<!--begin: Navigation -->--}}
                {{--<div class="kt-notification">--}}
                    {{--<a href="custom/apps/user/profile-1/personal-information.html" class="kt-notification__item">--}}
                        {{--<div class="kt-notification__item-icon">--}}
                            {{--<i class="flaticon2-calendar-3 kt-font-success"></i>--}}
                        {{--</div>--}}
                        {{--<div class="kt-notification__item-details">--}}
                            {{--<div class="kt-notification__item-title kt-font-bold">--}}
                                {{--My Profile--}}
                            {{--</div>--}}
                            {{--<div class="kt-notification__item-time">--}}
                                {{--Account settings and more--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</a>--}}
                    {{--<a href="custom/apps/user/profile-3.html" class="kt-notification__item">--}}
                        {{--<div class="kt-notification__item-icon">--}}
                            {{--<i class="flaticon2-mail kt-font-warning"></i>--}}
                        {{--</div>--}}
                        {{--<div class="kt-notification__item-details">--}}
                            {{--<div class="kt-notification__item-title kt-font-bold">--}}
                                {{--My Messages--}}
                            {{--</div>--}}
                            {{--<div class="kt-notification__item-time">--}}
                                {{--Inbox and tasks--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</a>--}}


                    {{--<a href="custom/apps/user/profile-1/overview.html" class="kt-notification__item">--}}
                        {{--<div class="kt-notification__item-icon">--}}
                            {{--<i class="flaticon2-cardiogram kt-font-warning"></i>--}}
                        {{--</div>--}}
                        {{--<div class="kt-notification__item-details">--}}
                            {{--<div class="kt-notification__item-title kt-font-bold">--}}
                                {{--Billing--}}
                            {{--</div>--}}
                            {{--<div class="kt-notification__item-time">--}}
                                {{--billing & statements <span class="kt-badge kt-badge--danger kt-badge--inline kt-badge--pill kt-badge--rounded">2 pending</span>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</a>--}}
                    {{--<div class="kt-notification__custom kt-space-between">--}}
                        {{--<a href="custom/user/login-v2.html" target="_blank" class="btn btn-label btn-label-brand btn-sm btn-bold">Sign Out</a>--}}
                        {{--<a href="custom/user/login-v2.html" target="_blank" class="btn btn-clean btn-sm btn-bold">Upgrade Plan</a>--}}
                    {{--</div>--}}
                {{--</div>--}}

                {{--<!--end: Navigation -->--}}
            {{--</div>--}}
        {{--</div>--}}






        <!-- BEGIN TOP NAVIGATION MENU -->
        <div class="top-menu">
        <ul class="nav navbar-nav pull-right">
        <!-- BEGIN USER LOGIN DROPDOWN -->
        <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
        <li class="dropdown dropdown-user">
        <span id="suser_empid" style="display: none;">{{$id->suser_empid}}</span>
        <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" id="user_img_name">

        </a>
        <ul class="dropdown-menu dropdown-menu-default">
        <li>
        @if(Controller::checkAppModulePriority('employee-details','View')=="1")
        <a href="{{url('employee-details')}}/{{$id->suser_empid}}/view"> <i class="fa fa-search"></i> My Information</a>
        @endif

        @if(Controller::checkAppModulePriority('employee-details','Change Password')=="1")
        <a href="{{url('change-password')}}"> <i class="fa fa-pencil"></i> Change Password</a>
        @endif

        <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();"> <i class="icon-key"></i> Log Out</a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        {{ csrf_field() }}
        </form>
        </li>
        </ul>
        </li>
        <!-- END USER LOGIN DROPDOWN -->
        <!-- BEGIN QUICK SIDEBAR TOGGLER -->
        <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
        <!-- <li class="dropdown dropdown-quick-sidebar-toggler">
        <a href="javascript:;" class="dropdown-toggle">
        <i class="icon-logout"></i>
        </a>
        </li> -->
        <!-- END QUICK SIDEBAR TOGGLER -->
        </ul>
        </div>

        <!--end: User Bar -->
    </div>
    <!-- end:: Header Topbar -->
</div>
<!-- new header end-->







<!-- BEGIN HEADER & CONTENT DIVIDER -->
<div class="clearfix"> </div>
<!-- END HEADER & CONTENT DIVIDER -->
<!-- BEGIN CONTAINER -->
<div class="page-container">
    <!-- BEGIN SIDEBAR -->
    <div class="page-sidebar-wrapper hidden-lg hidden-md">
        <!-- BEGIN SIDEBAR -->
        <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
        <!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
        <div class="page-sidebar navbar-collapse collapse">
            <!-- BEGIN SIDEBAR MENU -->
            <!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
            <!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
            <!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
            <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
            <!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
            <!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
            <ul class="page-sidebar-menu  page-header-fixed " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200" style="padding-top: 20px">
                <!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
                <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
                <li class="sidebar-toggler-wrapper hide">
                    <div class="sidebar-toggler">
                        <span></span>
                    </div>
                </li>
                <!-- END SIDEBAR TOGGLER BUTTON -->
            <!-- DOC: To remove the search box from the sidebar you just need to completely remove the below "sidebar-search-wrapper" LI element
                             <li>
                                <a href="{{url('/Dashboard')}}">
                                <i class="icon-home"></i>
                                <span>Dashboard</span></a>
                             </li>-->

                @if($id->id == '1000')
                    <li class="nav-item start ">
                        <a href="javascript:;" class="nav-link nav-toggle">
                            <i class="icon-home"></i>
                            <span class="title">Dev</span>
                            <span class="arrow"></span>
                        </a>

                        <ul class="sub-menu">
                            <li class="nav-item start ">
                                <a href="{{url('project-details')}}" class="nav-link ">
                                    <i class="icon-bar-chart"></i>
                                    <span class="title">Project Details</span>
                                </a>
                            </li>
                            <li class="nav-item start ">
                                <a href="{{url('main-menu-view')}}" class="nav-link ">
                                    <i class="icon-bar-chart"></i>
                                    <span class="title">Main Menu</span>
                                </a>
                            </li>
                            <li class="nav-item start ">
                                <a href="{{url('sub-menu-view')}}" class="nav-link ">
                                    <i class="icon-bar-chart"></i>
                                    <span class="title">Sub Menu</span>
                                </a>
                            </li>
                            <li class="nav-item start ">
                                <a href="{{url('priority')}}" class="nav-link ">
                                    <i class="icon-bar-chart"></i>
                                    <span class="title">Priority</span>
                                </a>
                            </li>
                            <li class="nav-item start ">
                                <a href="{{url('appmodule')}}" class="nav-link ">
                                    <i class="icon-bar-chart"></i>
                                    <span class="title">App Module</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

                @if($id->id=="1000")

                    @if(count($Adminminlink) > 0)
                        @foreach($Adminminlink as $showMainlink)
                            @if($showMainlink->routeName == '#')
                                <li class="nav-item start ">
                                    <a href="javascript:;" class="nav-link nav-toggle">
                                        <i class="{{$showMainlink->icon}}" style="font-weight: bold;"></i>
                                        <span class="title">{{$showMainlink->Link_Name}}</span>
                                        <span class="arrow"></span>
                                    </a>
                                    <ul class="sub-menu">
                                        @if(count($adminsublink) > 0)
                                            @foreach($adminsublink as $showSubLink)
                                                @if($showSubLink->mainmenuId == $showMainlink->id)
                                                    <li class="nav-item start ">
                                                        <a href="{{url('/')}}/{{$showSubLink->routeName}}" class="nav-link ">
                                                            <i class="icon-bar-chart"></i>
                                                            <span class="title">{{$showSubLink->submenuname}}</span>
                                                        </a>
                                                    </li>
                                                @endif
                                            @endforeach
                                        @endif
                                    </ul>
                                </li>
                            @else
                                <li><a href="{{url('/')}}/{{$showMainlink->routeName}}">
                                        <i class="{{$showMainlink->icon}}" style="font-weight: bold;"></i>
                                        <span>{{$showMainlink->Link_Name}}</span></a> </li>
                            @endif
                        @endforeach
                    @endif

                @else

                    @if(count($mainlink) > 0)
                        @foreach($mainlink as $showMainlink)
                            @if($showMainlink->routeName == '#')
                                <li class="nav-item start ">
                                    <a href="javascript:;" class="nav-link nav-toggle">
                                        <i class="{{$showMainlink->icon}}" style="font-weight: bold;"></i>
                                        <span class="title">{{$showMainlink->Link_Name}}</span>
                                        <span class="arrow"></span>
                                    </a>
                                    <ul class="sub-menu">
                                        @if(count($sublink) > 0)
                                            @foreach($sublink as $showSubLink)
                                                @if($showSubLink->mainmenuId == $showMainlink->id)
                                                    <li class="nav-item start ">
                                                        <a href="{{url('/')}}/{{$showSubLink->routeName}}" class="nav-link ">
                                                            <i class="icon-bar-chart"></i>
                                                            <span class="title">{{$showSubLink->submenuname}}</span>
                                                        </a>
                                                    </li>
                                                @endif
                                            @endforeach
                                        @endif
                                    </ul>
                                </li>
                            @else
                                <li><a href="{{url('/')}}/{{$showMainlink->routeName}}">
                                        <i class="{{$showMainlink->icon}}" style="font-weight: bold;"></i>
                                        <span>{{$showMainlink->Link_Name}}</span></a> </li>

                            @endif
                        @endforeach
                    @endif

                @endif

            </ul>
            <!-- END SIDEBAR MENU -->
            <!-- END SIDEBAR MENU -->
        </div>
        <!-- END SIDEBAR -->
    </div>
    <!-- END SIDEBAR -->
    <!-- BEGIN CONTENT -->
    <div class="page-content-wrapper">
        <!-- BEGIN CONTENT BODY -->
        <div class="page-content" style="margin: 0px">
            <!-- BEGIN PAGE HEADER-->
            <!-- BEGIN THEME PANEL -->
            <div class="theme-panel hidden-xs hidden-sm">
                <!-- <div class="toggler"> </div> -->
                <div class="toggler-close"> </div>
                <div class="theme-options">
                    <div class="theme-option theme-colors clearfix">
                        <span> THEME COLOR </span>
                        <ul>
                            <li class="color-default current tooltips" data-style="default" data-container="body" data-original-title="Default"> </li>
                            <li class="color-darkblue tooltips" data-style="darkblue" data-container="body" data-original-title="Dark Blue"> </li>
                            <li class="color-blue tooltips" data-style="blue" data-container="body" data-original-title="Blue"> </li>
                            <li class="color-grey tooltips" data-style="grey" data-container="body" data-original-title="Grey"> </li>
                            <li class="color-light tooltips" data-style="light" data-container="body" data-original-title="Light"> </li>
                            <li class="color-light2 tooltips" data-style="light2" data-container="body" data-html="true" data-original-title="Light 2"> </li>
                        </ul>
                    </div>
                    <div class="theme-option">
                        <span> Theme Style </span>
                        <select class="layout-style-option form-control input-sm">
                            <option value="square" selected="selected">Square corners</option>
                            <option value="rounded">Rounded corners</option>
                        </select>
                    </div>
                    <div class="theme-option">
                        <span> Layout </span>
                        <select class="layout-option form-control input-sm">
                            <option value="fluid" selected="selected">Fluid</option>
                            <option value="boxed">Boxed</option>
                        </select>
                    </div>
                    <div class="theme-option">
                        <span> Header </span>
                        <select class="page-header-option form-control input-sm">
                            <option value="fixed" selected="selected">Fixed</option>
                            <option value="default">Default</option>
                        </select>
                    </div>
                    <div class="theme-option">
                        <span> Top Menu Dropdown</span>
                        <select class="page-header-top-dropdown-style-option form-control input-sm">
                            <option value="light" selected="selected">Light</option>
                            <option value="dark">Dark</option>
                        </select>
                    </div>
                    <div class="theme-option">
                        <span> Sidebar Mode</span>
                        <select class="sidebar-option form-control input-sm">
                            <option value="fixed">Fixed</option>
                            <option value="default" selected="selected">Default</option>
                        </select>
                    </div>
                    <div class="theme-option">
                        <span> Sidebar Menu </span>
                        <select class="sidebar-menu-option form-control input-sm">
                            <option value="accordion" selected="selected">Accordion</option>
                            <option value="hover">Hover</option>
                        </select>
                    </div>
                    <div class="theme-option">
                        <span> Sidebar Style </span>
                        <select class="sidebar-style-option form-control input-sm">
                            <option value="default" selected="selected">Default</option>
                            <option value="light">Light</option>
                        </select>
                    </div>
                    <div class="theme-option">
                        <span> Sidebar Position </span>
                        <select class="sidebar-pos-option form-control input-sm">
                            <option value="left" selected="selected">Left</option>
                            <option value="right">Right</option>
                        </select>
                    </div>
                    <div class="theme-option">
                        <span> Footer </span>
                        <select class="page-footer-option form-control input-sm">
                            <option value="fixed">Fixed</option>
                            <option value="default" selected="selected">Default</option>
                        </select>
                    </div>
                </div>
            </div>
            <!-- END THEME PANEL -->
            <!-- BEGIN PAGE BAR -->
            <div class="page-bar" style="display: none;">
                <ul class="page-breadcrumb">
                    <li>
                        <a href="index.html">Home</a>
                        <i class="fa fa-circle"></i>
                    </li>
                    <li>
                        <span>Tables</span>
                    </li>
                </ul>
                <!-- <div class="page-toolbar">
                    <div class="btn-group pull-right">
                        <button type="button" class="btn green btn-sm btn-outline dropdown-toggle" data-toggle="dropdown"> Actions
                            <i class="fa fa-angle-down"></i>
                        </button>
                        <ul class="dropdown-menu pull-right" role="menu">
                            <li>
                                <a href="#">
                                    <i class="icon-bell"></i> Action</a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="icon-shield"></i> Another action</a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="icon-user"></i> Something else here</a>
                            </li>
                            <li class="divider"> </li>
                            <li>
                                <a href="#">
                                    <i class="icon-bag"></i> Separated link</a>
                            </li>
                        </ul>
                    </div>
                </div> -->
            </div>
            <!-- END PAGE BAR -->
            <!-- BEGIN PAGE TITLE-->
            <!-- <h1 class="page-title"> Basic Bootstrap Tables
                <small>basic bootstrap tables with various options and styles</small>
            </h1> -->
            <!-- END PAGE TITLE-->
            <!-- END PAGE HEADER-->
            @yield('body')
        </div>
        <!-- END CONTENT BODY -->
    </div>
    <!-- END CONTENT -->
    <!-- BEGIN QUICK SIDEBAR -->
    <a href="javascript:;" class="page-quick-sidebar-toggler">
        <i class="icon-login"></i>
    </a>
    <div class="page-quick-sidebar-wrapper" data-close-on-body-click="false">
        <div class="page-quick-sidebar">
            <ul class="nav nav-tabs">
                <li class="active">
                    <a href="javascript:;" data-target="#quick_sidebar_tab_1" data-toggle="tab"> Users
                        <span class="badge badge-danger">2</span>
                    </a>
                </li>
                <li>
                    <a href="javascript:;" data-target="#quick_sidebar_tab_2" data-toggle="tab"> Alerts
                        <span class="badge badge-success">7</span>
                    </a>
                </li>
                <li class="dropdown">
                    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown"> More
                        <i class="fa fa-angle-down"></i>
                    </a>
                    <ul class="dropdown-menu pull-right">
                        <li>
                            <a href="javascript:;" data-target="#quick_sidebar_tab_3" data-toggle="tab">
                                <i class="icon-bell"></i> Alerts </a>
                        </li>
                        <li>
                            <a href="javascript:;" data-target="#quick_sidebar_tab_3" data-toggle="tab">
                                <i class="icon-info"></i> Notifications </a>
                        </li>
                        <li>
                            <a href="javascript:;" data-target="#quick_sidebar_tab_3" data-toggle="tab">
                                <i class="icon-speech"></i> Activities </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="javascript:;" data-target="#quick_sidebar_tab_3" data-toggle="tab">
                                <i class="icon-settings"></i> Settings </a>
                        </li>
                    </ul>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active page-quick-sidebar-chat" id="quick_sidebar_tab_1">
                    <div class="page-quick-sidebar-chat-users" data-rail-color="#ddd" data-wrapper-class="page-quick-sidebar-list">
                        <h3 class="list-heading">Staff</h3>
                        <ul class="media-list list-items">
                            <li class="media">
                                <div class="media-status">
                                    <span class="badge badge-success">8</span>
                                </div>
                                <img class="media-object" src="{{url('/')}}/public/assets/layouts/layout/img/avatar3.jpg" alt="...">
                                <div class="media-body">
                                    <h4 class="media-heading">Bob Nilson</h4>
                                    <div class="media-heading-sub"> Project Manager </div>
                                </div>
                            </li>
                            <li class="media">
                                <img class="media-object" src="{{url('/')}}/public/assets/layouts/layout/img/avatar1.jpg" alt="...">
                                <div class="media-body">
                                    <h4 class="media-heading">Nick Larson</h4>
                                    <div class="media-heading-sub"> Art Director </div>
                                </div>
                            </li>
                            <li class="media">
                                <div class="media-status">
                                    <span class="badge badge-danger">3</span>
                                </div>
                                <img class="media-object" src="{{url('/')}}/public/assets/layouts/layout/img/avatar4.jpg" alt="...">
                                <div class="media-body">
                                    <h4 class="media-heading">Deon Hubert</h4>
                                    <div class="media-heading-sub"> CTO </div>
                                </div>
                            </li>
                            <li class="media">
                                <img class="media-object" src="{{url('/')}}/public/assets/layouts/layout/img/avatar2.jpg" alt="...">
                                <div class="media-body">
                                    <h4 class="media-heading">Ella Wong</h4>
                                    <div class="media-heading-sub"> CEO </div>
                                </div>
                            </li>
                        </ul>
                        <h3 class="list-heading">Customers</h3>
                        <ul class="media-list list-items">
                            <li class="media">
                                <div class="media-status">
                                    <span class="badge badge-warning">2</span>
                                </div>
                                <img class="media-object" src="{{url('/')}}/public/assets/layouts/layout/img/avatar6.jpg" alt="...">
                                <div class="media-body">
                                    <h4 class="media-heading">Lara Kunis</h4>
                                    <div class="media-heading-sub"> CEO, Loop Inc </div>
                                    <div class="media-heading-small"> Last seen 03:10 AM </div>
                                </div>
                            </li>
                            <li class="media">
                                <div class="media-status">
                                    <span class="label label-sm label-success">new</span>
                                </div>
                                <img class="media-object" src="{{url('/')}}/public/assets/layouts/layout/img/avatar7.jpg" alt="...">
                                <div class="media-body">
                                    <h4 class="media-heading">Ernie Kyllonen</h4>
                                    <div class="media-heading-sub"> Project Manager,
                                        <br> SmartBizz PTL </div>
                                </div>
                            </li>
                            <li class="media">
                                <img class="media-object" src="{{url('/')}}/public/assets/layouts/layout/img/avatar8.jpg" alt="...">
                                <div class="media-body">
                                    <h4 class="media-heading">Lisa Stone</h4>
                                    <div class="media-heading-sub"> CTO, Keort Inc </div>
                                    <div class="media-heading-small"> Last seen 13:10 PM </div>
                                </div>
                            </li>
                            <li class="media">
                                <div class="media-status">
                                    <span class="badge badge-success">7</span>
                                </div>
                                <img class="media-object" src="{{url('/')}}/public/assets/layouts/layout/img/avatar9.jpg" alt="...">
                                <div class="media-body">
                                    <h4 class="media-heading">Deon Portalatin</h4>
                                    <div class="media-heading-sub"> CFO, H&D LTD </div>
                                </div>
                            </li>
                            <li class="media">
                                <img class="media-object" src="{{url('/')}}/public/assets/layouts/layout/img/avatar10.jpg" alt="...">
                                <div class="media-body">
                                    <h4 class="media-heading">Irina Savikova</h4>
                                    <div class="media-heading-sub"> CEO, Tizda Motors Inc </div>
                                </div>
                            </li>
                            <li class="media">
                                <div class="media-status">
                                    <span class="badge badge-danger">4</span>
                                </div>
                                <img class="media-object" src="{{url('/')}}/public/assets/layouts/layout/img/avatar11.jpg" alt="...">
                                <div class="media-body">
                                    <h4 class="media-heading">Maria Gomez</h4>
                                    <div class="media-heading-sub"> Manager, Infomatic Inc </div>
                                    <div class="media-heading-small"> Last seen 03:10 AM </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="page-quick-sidebar-item">
                        <div class="page-quick-sidebar-chat-user">
                            <div class="page-quick-sidebar-nav">
                                <a href="javascript:;" class="page-quick-sidebar-back-to-list">
                                    <i class="icon-arrow-left"></i>Back</a>
                            </div>
                            <div class="page-quick-sidebar-chat-user-messages">
                                <div class="post out">
                                    <img class="avatar" alt="" src="{{url('/')}}/public/assets/layouts/layout/img/avatar3.jpg" />
                                    <div class="message">
                                        <span class="arrow"></span>
                                        <a href="javascript:;" class="name">Bob Nilson</a>
                                        <span class="datetime">20:15</span>
                                        <span class="body"> When could you send me the report ? </span>
                                    </div>
                                </div>
                                <div class="post in">
                                    <img class="avatar" alt="" src="{{url('/')}}/public/assets/layouts/layout/img/avatar2.jpg" />
                                    <div class="message">
                                        <span class="arrow"></span>
                                        <a href="javascript:;" class="name">Ella Wong</a>
                                        <span class="datetime">20:15</span>
                                        <span class="body"> Its almost done. I will be sending it shortly </span>
                                    </div>
                                </div>
                                <div class="post out">
                                    <img class="avatar" alt="" src="{{url('/')}}/public/assets/layouts/layout/img/avatar3.jpg" />
                                    <div class="message">
                                        <span class="arrow"></span>
                                        <a href="javascript:;" class="name">Bob Nilson</a>
                                        <span class="datetime">20:15</span>
                                        <span class="body"> Alright. Thanks! :) </span>
                                    </div>
                                </div>
                                <div class="post in">
                                    <img class="avatar" alt="" src="{{url('/')}}/public/assets/layouts/layout/img/avatar2.jpg" />
                                    <div class="message">
                                        <span class="arrow"></span>
                                        <a href="javascript:;" class="name">Ella Wong</a>
                                        <span class="datetime">20:16</span>
                                        <span class="body"> You are most welcome. Sorry for the delay. </span>
                                    </div>
                                </div>
                                <div class="post out">
                                    <img class="avatar" alt="" src="{{url('/')}}/public/assets/layouts/layout/img/avatar3.jpg" />
                                    <div class="message">
                                        <span class="arrow"></span>
                                        <a href="javascript:;" class="name">Bob Nilson</a>
                                        <span class="datetime">20:17</span>
                                        <span class="body"> No probs. Just take your time :) </span>
                                    </div>
                                </div>
                                <div class="post in">
                                    <img class="avatar" alt="" src="{{url('/')}}/public/assets/layouts/layout/img/avatar2.jpg" />
                                    <div class="message">
                                        <span class="arrow"></span>
                                        <a href="javascript:;" class="name">Ella Wong</a>
                                        <span class="datetime">20:40</span>
                                        <span class="body"> Alright. I just emailed it to you. </span>
                                    </div>
                                </div>
                                <div class="post out">
                                    <img class="avatar" alt="" src="{{url('/')}}/public/assets/layouts/layout/img/avatar3.jpg" />
                                    <div class="message">
                                        <span class="arrow"></span>
                                        <a href="javascript:;" class="name">Bob Nilson</a>
                                        <span class="datetime">20:17</span>
                                        <span class="body"> Great! Thanks. Will check it right away. </span>
                                    </div>
                                </div>
                                <div class="post in">
                                    <img class="avatar" alt="" src="{{url('/')}}/public/assets/layouts/layout/img/avatar2.jpg" />
                                    <div class="message">
                                        <span class="arrow"></span>
                                        <a href="javascript:;" class="name">Ella Wong</a>
                                        <span class="datetime">20:40</span>
                                        <span class="body"> Please let me know if you have any comment. </span>
                                    </div>
                                </div>
                                <div class="post out">
                                    <img class="avatar" alt="" src="{{url('/')}}/public/assets/layouts/layout/img/avatar3.jpg" />
                                    <div class="message">
                                        <span class="arrow"></span>
                                        <a href="javascript:;" class="name">Bob Nilson</a>
                                        <span class="datetime">20:17</span>
                                        <span class="body"> Sure. I will check and buzz you if anything needs to be corrected. </span>
                                    </div>
                                </div>
                            </div>
                            <div class="page-quick-sidebar-chat-user-form">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Type a message here...">
                                    <div class="input-group-btn">
                                        <button type="button" class="btn green">
                                            <i class="icon-paper-clip"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane page-quick-sidebar-alerts" id="quick_sidebar_tab_2">
                    <div class="page-quick-sidebar-alerts-list">
                        <h3 class="list-heading">General</h3>
                        <ul class="feeds list-items">
                            <li>
                                <div class="col1">
                                    <div class="cont">
                                        <div class="cont-col1">
                                            <div class="label label-sm label-info">
                                                <i class="fa fa-check"></i>
                                            </div>
                                        </div>
                                        <div class="cont-col2">
                                            <div class="desc"> You have 4 pending tasks.
                                                <span class="label label-sm label-warning "> Take action
                                                                <i class="fa fa-share"></i>
                                                            </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col2">
                                    <div class="date"> Just now </div>
                                </div>
                            </li>
                            <li>
                                <a href="javascript:;">
                                    <div class="col1">
                                        <div class="cont">
                                            <div class="cont-col1">
                                                <div class="label label-sm label-success">
                                                    <i class="fa fa-bar-chart-o"></i>
                                                </div>
                                            </div>
                                            <div class="cont-col2">
                                                <div class="desc"> Finance Report for year 2013 has been released. </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col2">
                                        <div class="date"> 20 mins </div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <div class="col1">
                                    <div class="cont">
                                        <div class="cont-col1">
                                            <div class="label label-sm label-danger">
                                                <i class="fa fa-user"></i>
                                            </div>
                                        </div>
                                        <div class="cont-col2">
                                            <div class="desc"> You have 5 pending membership that requires a quick review. </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col2">
                                    <div class="date"> 24 mins </div>
                                </div>
                            </li>
                            <li>
                                <div class="col1">
                                    <div class="cont">
                                        <div class="cont-col1">
                                            <div class="label label-sm label-info">
                                                <i class="fa fa-shopping-cart"></i>
                                            </div>
                                        </div>
                                        <div class="cont-col2">
                                            <div class="desc"> New order received with
                                                <span class="label label-sm label-success"> Reference Number: DR23923 </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col2">
                                    <div class="date"> 30 mins </div>
                                </div>
                            </li>
                            <li>
                                <div class="col1">
                                    <div class="cont">
                                        <div class="cont-col1">
                                            <div class="label label-sm label-success">
                                                <i class="fa fa-user"></i>
                                            </div>
                                        </div>
                                        <div class="cont-col2">
                                            <div class="desc"> You have 5 pending membership that requires a quick review. </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col2">
                                    <div class="date"> 24 mins </div>
                                </div>
                            </li>
                            <li>
                                <div class="col1">
                                    <div class="cont">
                                        <div class="cont-col1">
                                            <div class="label label-sm label-info">
                                                <i class="fa fa-bell-o"></i>
                                            </div>
                                        </div>
                                        <div class="cont-col2">
                                            <div class="desc"> Web server hardware needs to be upgraded.
                                                <span class="label label-sm label-warning"> Overdue </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col2">
                                    <div class="date"> 2 hours </div>
                                </div>
                            </li>
                            <li>
                                <a href="javascript:;">
                                    <div class="col1">
                                        <div class="cont">
                                            <div class="cont-col1">
                                                <div class="label label-sm label-default">
                                                    <i class="fa fa-briefcase"></i>
                                                </div>
                                            </div>
                                            <div class="cont-col2">
                                                <div class="desc"> IPO Report for year 2013 has been released. </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col2">
                                        <div class="date"> 20 mins </div>
                                    </div>
                                </a>
                            </li>
                        </ul>
                        <h3 class="list-heading">System</h3>
                        <ul class="feeds list-items">
                            <li>
                                <div class="col1">
                                    <div class="cont">
                                        <div class="cont-col1">
                                            <div class="label label-sm label-info">
                                                <i class="fa fa-check"></i>
                                            </div>
                                        </div>
                                        <div class="cont-col2">
                                            <div class="desc"> You have 4 pending tasks.
                                                <span class="label label-sm label-warning "> Take action
                                                                <i class="fa fa-share"></i>
                                                            </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col2">
                                    <div class="date"> Just now </div>
                                </div>
                            </li>
                            <li>
                                <a href="javascript:;">
                                    <div class="col1">
                                        <div class="cont">
                                            <div class="cont-col1">
                                                <div class="label label-sm label-danger">
                                                    <i class="fa fa-bar-chart-o"></i>
                                                </div>
                                            </div>
                                            <div class="cont-col2">
                                                <div class="desc"> Finance Report for year 2013 has been released. </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col2">
                                        <div class="date"> 20 mins </div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <div class="col1">
                                    <div class="cont">
                                        <div class="cont-col1">
                                            <div class="label label-sm label-default">
                                                <i class="fa fa-user"></i>
                                            </div>
                                        </div>
                                        <div class="cont-col2">
                                            <div class="desc"> You have 5 pending membership that requires a quick review. </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col2">
                                    <div class="date"> 24 mins </div>
                                </div>
                            </li>
                            <li>
                                <div class="col1">
                                    <div class="cont">
                                        <div class="cont-col1">
                                            <div class="label label-sm label-info">
                                                <i class="fa fa-shopping-cart"></i>
                                            </div>
                                        </div>
                                        <div class="cont-col2">
                                            <div class="desc"> New order received with
                                                <span class="label label-sm label-success"> Reference Number: DR23923 </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col2">
                                    <div class="date"> 30 mins </div>
                                </div>
                            </li>
                            <li>
                                <div class="col1">
                                    <div class="cont">
                                        <div class="cont-col1">
                                            <div class="label label-sm label-success">
                                                <i class="fa fa-user"></i>
                                            </div>
                                        </div>
                                        <div class="cont-col2">
                                            <div class="desc"> You have 5 pending membership that requires a quick review. </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col2">
                                    <div class="date"> 24 mins </div>
                                </div>
                            </li>
                            <li>
                                <div class="col1">
                                    <div class="cont">
                                        <div class="cont-col1">
                                            <div class="label label-sm label-warning">
                                                <i class="fa fa-bell-o"></i>
                                            </div>
                                        </div>
                                        <div class="cont-col2">
                                            <div class="desc"> Web server hardware needs to be upgraded.
                                                <span class="label label-sm label-default "> Overdue </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col2">
                                    <div class="date"> 2 hours </div>
                                </div>
                            </li>
                            <li>
                                <a href="javascript:;">
                                    <div class="col1">
                                        <div class="cont">
                                            <div class="cont-col1">
                                                <div class="label label-sm label-info">
                                                    <i class="fa fa-briefcase"></i>
                                                </div>
                                            </div>
                                            <div class="cont-col2">
                                                <div class="desc"> IPO Report for year 2013 has been released. </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col2">
                                        <div class="date"> 20 mins </div>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="tab-pane page-quick-sidebar-settings" id="quick_sidebar_tab_3">
                    <div class="page-quick-sidebar-settings-list">
                        <h3 class="list-heading">General Settings</h3>
                        <ul class="list-items borderless">
                            <li> Enable Notifications
                                <input type="checkbox" class="make-switch" checked data-size="small" data-on-color="success" data-on-text="ON" data-off-color="default" data-off-text="OFF"> </li>
                            <li> Allow Tracking
                                <input type="checkbox" class="make-switch" data-size="small" data-on-color="info" data-on-text="ON" data-off-color="default" data-off-text="OFF"> </li>
                            <li> Log Errors
                                <input type="checkbox" class="make-switch" checked data-size="small" data-on-color="danger" data-on-text="ON" data-off-color="default" data-off-text="OFF"> </li>
                            <li> Auto Sumbit Issues
                                <input type="checkbox" class="make-switch" data-size="small" data-on-color="warning" data-on-text="ON" data-off-color="default" data-off-text="OFF"> </li>
                            <li> Enable SMS Alerts
                                <input type="checkbox" class="make-switch" checked data-size="small" data-on-color="success" data-on-text="ON" data-off-color="default" data-off-text="OFF"> </li>
                        </ul>
                        <h3 class="list-heading">System Settings</h3>
                        <ul class="list-items borderless">
                            <li> Security Level
                                <select class="form-control input-inline input-sm input-small">
                                    <option value="1">Normal</option>
                                    <option value="2" selected>Medium</option>
                                    <option value="e">High</option>
                                </select>
                            </li>
                            <li> Failed Email Attempts
                                <input class="form-control input-inline input-sm input-small" value="5" /> </li>
                            <li> Secondary SMTP Port
                                <input class="form-control input-inline input-sm input-small" value="3560" /> </li>
                            <li> Notify On System Error
                                <input type="checkbox" class="make-switch" checked data-size="small" data-on-color="danger" data-on-text="ON" data-off-color="default" data-off-text="OFF"> </li>
                            <li> Notify On SMTP Error
                                <input type="checkbox" class="make-switch" checked data-size="small" data-on-color="warning" data-on-text="ON" data-off-color="default" data-off-text="OFF"> </li>
                        </ul>
                        <div class="inner-content">
                            <button class="btn btn-success">
                                <i class="icon-settings"></i> Save Changes</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END QUICK SIDEBAR -->
</div>
<!-- END CONTAINER -->

<!-- BEGIN FOOTER -->
<div class="page-footer">
    <div class="page-footer-inner"> Developed by
        <a target="_blank" href="https://acquaintbd.com">Acquaint Technologies</a>

    </div>
    <div class="scroll-to-top">
        <i class="icon-arrow-up"></i>
    </div>
</div>
<!-- END FOOTER -->
</div>
<!-- BEGIN QUICK NAV -->
<!-- <nav class="quick-nav">
    <a class="quick-nav-trigger" href="#0">
        <span aria-hidden="true"></span>
    </a>
    <ul>
        <li>
            <a href="https://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes" target="_blank" class="active">
                <span>Purchase Metronic</span>
                <i class="icon-basket"></i>
            </a>
        </li>
        <li>
            <a href="https://themeforest.net/item/metronic-responsive-admin-dashboard-template/reviews/4021469?ref=keenthemes" target="_blank">
                <span>Customer Reviews</span>
                <i class="icon-users"></i>
            </a>
        </li>
        <li>
            <a href="http://keenthemes.com/showcast/" target="_blank">
                <span>Showcase</span>
                <i class="icon-user"></i>
            </a>
        </li>
        <li>
            <a href="http://keenthemes.com/metronic-theme/changelog/" target="_blank">
                <span>Changelog</span>
                <i class="icon-graph"></i>
            </a>
        </li>
    </ul>
    <span aria-hidden="true" class="quick-nav-bg"></span>
</nav> -->
<div class="quick-nav-overlay"></div>
<!-- END QUICK NAV -->
<!--[if lt IE 9]>
<script src="{{url('/')}}/public/assets/global/plugins/respond.min.js"></script>
<script src="{{url('/')}}/public/assets/global/plugins/excanvas.min.js"></script>
<script src="{{url('/')}}/public/assets/global/plugins/ie8.fix.min.js"></script>
<![endif]-->
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



<!-- END THEME LAYOUT SCRIPTS -->
<script>
    $(document).ready(function()
    {
        $('#clickmewow').click(function()
        {
            $('#radio1003').attr('checked', 'checked');
        });

    });

    getUserImgName();

    function getUserImgName() {
        var suser_empid=$('#suser_empid').text();
        $.ajax({
            url: "{{url('getSuserEmpInfo')}}/"+suser_empid,
            type: 'GET',
            data: {},
            success:function(data) {
                $('#user_img_name').html(data);
            }
        });
    }

    $('.chosen').chosen();
    $('input[type=text]').attr('autocomplete','off');
    $('input[type=number]').attr('autocomplete','off');
    // $( "ul.nav li.mega-menu-full:nth-child(5)" ).hide();
</script>


<!-- start added js file for header -->
<script>
    var KTAppOptions = {
        "colors": {
            "state": {
                "brand": "#5d78ff",
                "dark": "#282a3c",
                "light": "#ffffff",
                "primary": "#5867dd",
                "success": "#34bfa3",
                "info": "#36a3f7",
                "warning": "#ffb822",
                "danger": "#fd3995"
            },
            "base": {
                "label": [
                    "#c5cbe3",
                    "#a1a8c3",
                    "#3d4465",
                    "#3e4466"
                ],
                "shape": [
                    "#f0f3ff",
                    "#d9dffa",
                    "#afb4d4",
                    "#646c9a"
                ]
            }
        }
    };
</script>

<!-- end::Global Config -->

<!--begin::Global Theme Bundle(used by all pages) -->
<script src="{{url('/')}}/public/assets/plugins/global/plugins.bundle.js" type="text/javascript"></script>
<script src="{{url('/')}}/public/assets/js/scripts.bundle.js" type="text/javascript"></script>

<!--end::Global Theme Bundle -->

<!--begin::Page Vendors(used by this page) -->
<script src="{{url('/')}}/public/assets/plugins/custom/fullcalendar/fullcalendar.bundle.js" type="text/javascript"></script>
<script src="//maps.google.com/maps/api/js?key=AIzaSyBTGnKT7dt597vo9QgeQ7BFhvSRP4eiMSM" type="text/javascript"></script>
<script src="{{url('/')}}/public/assets/plugins/custom/gmaps/gmaps.js" type="text/javascript"></script>

<!--end::Page Vendors -->

<!--begin::Page Scripts(used by this page) -->
<script src="{{url('/')}}/public/assets/js/pages/dashboard.js" type="text/javascript"></script>
<!-- end added js file for header -->

</body>

</html>