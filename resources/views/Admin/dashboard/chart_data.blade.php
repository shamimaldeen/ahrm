<div style="height:auto;text-align: center">
<canvas id="{{$chart['chart_id']}}" height="250"></canvas>
</div>
<script type="text/javascript">
    new Chart(document.getElementById("{{$chart['chart_id']}}"),{
        "type":"bar",
        "data":{
            "labels":[<?php echo $chartDataArray['labels']; ?>],
            "datasets":[
            {
                "label":"<?php echo $chart['chart_header_label']; ?>",
                "data":[<?php echo $chartDataArray['data']; ?>],
                "fill":false,
                "backgroundColor":[<?php echo $chartDataArray['backgroundColor']; ?>]
            }
            ]
        },
        "options":{
            "scales":{
                "yAxes":[{
                    "ticks":{
                        "beginAtZero":true
                    }
                }],
                "xAxes": [{
                    "ticks": {
                        "autoSkip": false
                    }
                }]
            },
            tooltips: {
                mode: 'point',
            },
        }
     });
</script>
