<script src="{{URL::to('/')}}/public/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
<script src="{{URL::to('public')}}/js/Chart.js"></script>
    @for($chart_count=1;$chart_count<=count($chartArray);$chart_count++)
    <form action="#" method="post" accept-charset="utf-8" id="chart_form-{{$chartArray[$chart_count]['chart_id']}}">
    {{csrf_field()}}

    <div class="{{$chartArray[$chart_count]['chart_div_class']}}">
        <!-- BEGIN CHART PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-bar-chart font-green-haze"></i>
                    <span class="caption-subject bold uppercase font-green-haze"> {{$chartArray[$chart_count]['chart_header']}}</span>
                </div>                            
            </div>
            <div class="portlet-body">
                <div id="{{$chartArray[$chart_count]['chart_id']}}-before" class="loading">
                    <center><img src="{{URL::to('public')}}/loading.svg"/></center>
                </div>

                <div id="{{$chartArray[$chart_count]['chart_id']}}-after" style="display: none;">
                    
                </div>
            </div>
        </div>
        <!-- END CHART PORTLET-->
    </div>

    </form>
    @endfor

    <div class="col-md-6">
        <!-- BEGIN CHART PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-bar-chart font-green-haze"></i>
                    <span class="caption-subject bold uppercase font-green-haze"> Upcoming Holidays</span>
                </div>                            
            </div>
            <div class="portlet-body">
                <ul style="padding-left: 15px; padding-bottom: 0;">
                @if(isset($holidayDate) && count($holidayDate))
                    @foreach ($holidayDate as $hd)
                      <li style="padding: 5px 0">
                        <b>
                          @if(isset($holiday) && count($holiday))
                            @foreach ($holiday as $h)
                                @if($hd->holiday_date==$h->holiday_date)
                                    {{$h->holiday_description}}&nbsp;,&nbsp; 
                                @endif
                            @endforeach
                          @endif
                        </b> 
                        <br> 
                        @php echo date('d F Y', strtotime($hd->holiday_date)); @endphp
                      </li>

                    @endforeach
                @endif
                </ul>
            
                <!--<div class="caption">
                    <i class="icon-bar-chart font-green-haze"></i>
                    <span class="caption-subject bold uppercase font-green-haze"> Tickets</span>
                </div>
                <ul style="padding-left: 15px; padding-bottom: 0;" id="ticket_info">
                    
                </ul>-->

            </div>
        </div>
        <!-- END CHART PORTLET-->
    </div>

<div id="chart_functions">
    
</div>

<script type="text/javascript">
    function getTicketInfo() {
        $('#ticket_info').html('<b>Loading...</b>');
        var ticket_depart_id=$('#emp_depart_id').val();
        $.ajax({
            url: "{{URL::to('getTicketInfo')}}/"+ticket_depart_id,
            type: 'GET',
            data: {},
            success:function(data) {
                $('#ticket_info').html(data);
            }
        });
        
    }
</script>


