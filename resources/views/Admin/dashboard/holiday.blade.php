<div class="col-md-4">
    <!-- BEGIN CHART PORTLET-->
    <div class="portlet light bordered">
        <div class="portlet-title ">
            <div class="caption">
                <i class="icon-bar-chart font-green-haze"></i>
                <span class="caption-subject bold uppercase font-green-haze "> Upcoming Holidays</span>
            </div>                            
        </div>
        <div class="portlet-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="portlet light portlet-fit bordered calendar">
                        <div class="portlet-body">
                            <div class="row">
                                <div class="col-md-12 col-sm-12">
                                    <div id="calendar" class="has-toolbar"> </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END CHART PORTLET-->
</div>
<script src="{{url('/')}}/public/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
<script src="{{url('public')}}/assets/global/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
<script src="{{url('public')}}/assets/global/plugins/moment.min.js" type="text/javascript"></script>
<script src="{{url('public')}}/assets/global/plugins/fullcalendar/fullcalendar.min.js" type="text/javascript"></script>
<script type="text/javascript">  
  $('#calendar').fullCalendar({
    defaultView: 'month',
    events: "{{url('/')}}/calender",
    eventMouseover: function(calEvent, jsEvent) {
      var tooltip = '<div class="alert alert-info tooltipevent" style="width:auto;height:auto;color:red;border:1px solid green;position:absolute;z-index:10001;"><strong>' + calEvent.title + '</<strong></strong></div>';
      var $tooltip = $(tooltip).appendTo('body');

      $(this).mouseover(function(e) {
          $(this).css('z-index', 10000);
          $tooltip.fadeIn('500');
          $tooltip.fadeTo('10', 1.9);
      }).mousemove(function(e) {
          $tooltip.css('top', e.pageY + 10);
          $tooltip.css('left', e.pageX + 20);
      });
  },
  eventMouseout: function(calEvent, jsEvent) {
      $(this).css('z-index', 8);
      $('.tooltipevent').remove();
  },
  });

  $('.fc-left').attr('style','padding:10px');

</script>