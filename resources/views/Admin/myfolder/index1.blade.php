<!DOCTYPE html>
<html lang="en">
<head>
<title>Admin Panel</title>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="stylesheet" href="{!! asset('public/css/bootstrap.min.css') !!}" />

<link rel="stylesheet" href="{!! asset('public/css/bootstrap-responsive.min.css') !!}" />
<link rel="stylesheet" href="{!! asset('public/css/fullcalendar.css') !!}" />
<link rel="stylesheet" href="{!! asset('public/css/matrix-style.css') !!}" />
<link rel="stylesheet" href="{!! asset('public/css/matrix-media.css') !!}" />
<link href="{!! asset('public/font-awesome/css/font-awesome.css') !!}" rel="stylesheet" />
<link rel="stylesheet" href="{!! asset('public/css/jquery.gritter.css') !!}" />
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>

<link rel="stylesheet" href="{{URL::to('/')}}/public/css/datepicker.css" />
<link rel="stylesheet" href="{{URL::to('/')}}/public/css/bootstrap-wysihtml5.css" />
<link rel="stylesheet" href="{!! asset('public/css/uniform.css') !!}" />
<link rel="stylesheet" href="{!! asset('public/css/select2.css') !!}" />
</head>
<body>

<!--Header-part-->
<div id="header" style='height: 60px;'>
  <div style='padding: 10px;
color: white;
font-size: 14px;
font-weight: bold;'>Bureau Veritas<br> Consumer Products Services</div>

</div>
<!--close-Header-part--> 


<!--top-Header-menu-->
<div id="user-nav" class="navbar navbar-inverse">
  <ul class="nav">
    <li  class="dropdown" id="profile-messages" ><a title="" href="#" data-toggle="dropdown" data-target="#profile-messages" class="dropdown-toggle"><i class="icon icon-user"></i>  <span class="text">Welcome {{$id->name}}</span><b class="caret"></b></a>
      <ul class="dropdown-menu">
        <li><a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">Logout</a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>
        </li>
      </ul>
    </li>

    <li class=""><a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();"><i class="icon icon-share-alt"></i> <span class="text">Logout</span></a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form></a>
    </li>
  </ul>
</div>
<!--close-top-Header-menu-->
<!--start-top-serch-->
<div id="search">
  <input type="text" placeholder="Search here..."/>
  <button type="submit" class="tip-bottom" title="Search"><i class="icon-search icon-white"></i></button>
</div>
<!--close-top-serch-->

<!--sidebar-menu-->
<div id="sidebar"><a href="#" class="visible-phone"><i class="icon icon-home"></i> Menu</a>
  <ul>
    <li class="active"><a href="{{URL::to('/Dashboard')}}"><i class="icon icon-home"></i> <span>Menu</span></a> </li>
  
@if($id->id == '1001')
   <li class="submenu"> <a href="#"><i class="icon icon-th-list"></i> <span>Developer Forms</span></a>
      <ul>
        <li><a href="{{URL::to('MainMenu')}}">Main Menu Link</a></li>
        <li><a href="{{URL::to('SubMenu')}}">Sub Menu Link</a></li> 
        <li><a href="{{URL::to('AddCompany')}}">Company Information</a></li> 
        <li><a href="{{URL::to('Brance')}}">Branch Information</a></li> 
      </ul>
    </li>
@endif

@if($id->fk_brance_id=="1")


@if(count($Adminminlink) > 0)
  @foreach($Adminminlink as $showMainlink)
    @if($showMainlink->routeName == '#')
      <li class="submenu"> <a style="cursor: pointer;"><i class="icon icon-th-list"></i>
        <span>{{$showMainlink->Link_Name}}</span></a>
            <ul>
              @if(count($adminsublink) > 0)
                @foreach($adminsublink as $showSubLink)
                  @if($showSubLink->mainmenuId == $showMainlink->id)
                    <li><a href="{{URL::to('/')}}/{{$showSubLink->routeName}}">{{$showSubLink->submenuname}}</a></li>
                  @endif
                @endforeach
              @endif
            </ul>
      </li>
    @else
      <li> <a href="{{URL::to('/')}}/{{$showMainlink->routeName}}"><i class="icon icon-signal"></i> <span>{{$showMainlink->Link_Name}}</span></a> </li>
    @endif
  @endforeach
@endif


@else


@if(count($mainlink) > 0)
  @foreach($mainlink as $showMainlink)
    @if($showMainlink->routeName == '#')
    <li class="submenu"> <a style="cursor: pointer;"><i class="icon icon-th-list"></i> <span>{{$showMainlink->Link_Name}}</span></a>
          <ul>
            @if(count($sublink) > 0)
              @foreach($sublink as $showSubLink)
                @if($showSubLink->mainmenuId == $showMainlink->id)
                  <li><a href="{{URL::to('/')}}/{{$showSubLink->routeName}}">{{$showSubLink->submenuname}}</a></li>
                @endif
              @endforeach
            @endif
          </ul>
    </li>
    @else
     <li> <a href="{{URL::to('/')}}/{{$showMainlink->routeName}}"><i class="icon icon-signal"></i> <span>{{$showMainlink->Link_Name}}</span></a> </li>
    @endif
  @endforeach
@endif

@endif

  </ul>
</div>
<!--sidebar-menu-->

<!--main-container-part-->
<div id="content">
    @yield('body')
  </div>
<!--end-main-container-part-->

<!--Footer-part-->

<div class="row-fluid">
  <div id="footer" class="span12"> Developed by <a href="http://sbit.com.bd">SBIT</a> </div>
</div>

<!--end-Footer-part-->

<script src="{{URL::to('/')}}/public/js/jquery.min.js"></script>
<script src="{{URL::to('/')}}/public/js/jquery.ui.custom.js"></script> 
<script src="{{URL::to('/')}}/public/js/bootstrap.min.js"></script> 
<script src="{{URL::to('/')}}/public/js/jquery.uniform.js"></script> 
<script src="{{URL::to('/')}}/public/js/select2.min.js"></script> 
<script src="{{URL::to('/')}}/public/js/jquery.dataTables.min.js"></script> 
<script src="{{URL::to('/')}}/public/js/matrix.js"></script> 





<script src="{{URL::to('/')}}/public/js/excanvas.min.js"></script> 



<script src="{{URL::to('/')}}/public/js/jquery.flot.min.js"></script> 
<script src="{{URL::to('/')}}/public/js/jquery.flot.resize.min.js"></script> 
<script src="{{URL::to('/')}}/public/js/jquery.peity.min.js"></script> 
<script src="{{URL::to('/')}}/public/js/fullcalendar.min.js"></script> 

<script src="{{URL::to('/')}}/public/js/matrix.dashboard.js"></script> 
<script src="{{URL::to('/')}}/public/js/jquery.gritter.min.js"></script> 
<script src="{{URL::to('/')}}/public/js/matrix.interface.js"></script> 
<script src="{{URL::to('/')}}/public/js/matrix.chat.js"></script> 
<script src="{{URL::to('/')}}/public/js/jquery.validate.js"></script> 
<script src="{{URL::to('/')}}/public/js/matrix.form_validation.js"></script> 
<script src="{{URL::to('/')}}/public/js/jquery.wizard.js"></script> 

 
<script src="{{URL::to('/')}}/public/js/matrix.popover.js"></script>

<script type="text/javascript">
  // This function is called from the pop-up menus to transfer to
  // a different page. Ignore if the value returned is a null string:
  function goPage (newURL) {

      // if url is empty, skip the menu dividers and reset the menu selection to default
      if (newURL != "") {
      
          // if url is "-", it is this page -- reset the menu:
          if (newURL == "-" ) {
              resetMenu();            
          } 
          // else, send page to designated URL            
          else {  
            document.location.href = newURL;
          }
      }
  }

// resets the menu selection upon entry to this page:
function resetMenu() {
   document.gomenu.selector.selectedIndex = 2;
}
</script>
</body>
</html>
