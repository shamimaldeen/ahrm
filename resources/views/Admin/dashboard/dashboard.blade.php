<style type="text/css" media="screen">
    .amcharts-chart-div > a {
    display: none !important;
}
</style>
<div class="row">
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <a class="dashboard-stat dashboard-stat-v2 green">
            <div class="visual">
                <i class="fa fa-users"></i>
            </div>
            <div class="details">
                <div class="number">
                    <span data-counter="counterup" data-value="{{$chart[1]['dates'][date('Y-m-d')]}}" style="color: white;font-weight: bold">{{$chart[1]['dates'][date('Y-m-d')]}}</span>
                </div>
                <div class="desc"> <strong>PRESENT</strong> </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-12">
                    <div class="portlet light bordered">
                        <div class="portlet-body">
                            <div id="chart_1" class="chart" style="height: 150px;">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <a class="dashboard-stat dashboard-stat-v2" style="background: #FF6600">
            <div class="visual">
                <i class="fa fa-bar-chart-o" style="color: white;opacity: 0.2"></i>
            </div>
            <div class="details">
                <div class="number">
                    <span data-counter="counterup" data-value="{{$chart[2]['dates'][date('Y-m-d')]}}" style="color: white;font-weight: bold">{{$chart[2]['dates'][date('Y-m-d')]}}</span></div>
                <div class="desc" style="color: white"><strong>LATE</strong>  </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-12">
                    <div class="portlet light bordered">
                        <div class="portlet-body">
                            <div id="chart_2" class="chart" style="height: 150px;">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <a class="dashboard-stat dashboard-stat-v2 red">
            <div class="visual">
                <i class="fa fa-shopping-cart"></i>
            </div>
            <div class="details">
                <div class="number">
                    <span data-counter="counterup" data-value="{{$chart[3]['dates'][date('Y-m-d')]-$chart[4]['dates'][date('Y-m-d')]}}"  style="color: white;font-weight: bold">{{$chart[3]['dates'][date('Y-m-d')]-$chart[4]['dates'][date('Y-m-d')]}}</span>
                </div>
                <div class="desc" style="color: white"><strong>ABSENT</strong>  </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-12">
                    <div class="portlet light bordered">
                        <div class="portlet-body">
                            <div id="chart_3" class="chart" style="height: 150px;">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <a class="dashboard-stat dashboard-stat-v2" style="background: purple">
            <div class="visual">
                <i class="fa fa-globe" style="color: white;opacity: 0.2"></i>
            </div>
            <div class="details">
                <div class="number">
                    <span data-counter="counterup" data-value="{{$chart[4]['dates'][date('Y-m-d')]}}" style="color: white;font-weight: bold">{{$chart[4]['dates'][date('Y-m-d')]}}</span></div>
                <div class="desc" style="color: white"><strong> ON LEAVE</strong> </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-12">
                    <div class="portlet light bordered">
                        <div class="portlet-body">
                            <div id="chart_4" class="chart" style="height: 150px;">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>

<script src="{{url('/')}}/public/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function(){
        @foreach($chart as $key => $c)
            var e=AmCharts.makeChart("chart_{{$key}}",{
                type:"serial",
                theme:"light",
                pathToImages:App.getGlobalPluginsPath()+"amcharts/amcharts/images/",
                autoMargins:!1,
                marginLeft:30,
                marginRight:8,
                marginTop:10,
                marginBottom:26,
                fontFamily:"Open Sans",
                color:"green",
                dataProvider:[
                    @foreach($c['dates'] as $key => $p)
                    @if($key!=date('Y-m-d'))
                        {date:"{{date('M j',strtotime($key))}}","{{$c['valueField']}}":"{{$p}}"},
                    @endif
                    @endforeach
                ],
                valueAxes:[{
                    axisAlpha:0,
                    position:"left",
                }],
                startDuration:1,
                graphs:[{
                    alphaField:"alpha",
                    colorField:"color",
                    balloonText:"<span style='font-size:13px;'>[[title]] in [[category]]:&nbsp&nbsp;<b>[[value]]</b> [[additional]]</span>",
                    dashLengthField:"dashLengthColumn",
                    fillAlphas:1,
                    title:"{{$c['title']}}",
                    type:"column",
                    valueField:"{{$c['valueField']}}"
                }],
                categoryField:"date",
                categoryAxis:{
                    gridPosition:"start",
                    axisAlpha:0,
                    tickLength:0
                }
            });
        @endforeach
    });
</script>
