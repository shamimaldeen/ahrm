<div class="portlet-title">
  <div class="caption font-dark">
      <i class="icon-settings font-dark"></i>
      <span class="caption-subject bold uppercase" id="hidden_table_title"> {!!$info['chart_title']!!}</span>
  </div>
</div>

<div class="portlet-body">
	<form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{URL::to('')}}" id="data_form">
	{{ csrf_field() }}
		 <canvas id="chart"></canvas>
		 <script src="{{URL::to('public')}}/js/Chart.js"></script>

		 <script>
		 new Chart(document.getElementById("chart"),{
		 	"type":"bar",
		 	"data":{
		 		"labels":[{!! $info['chart_levels']['1'] !!}],
		 		"datasets":[
		 		<?php
		 		for ($i = 0; $i < $info['chart_num_datasets'] ; $i++) {
		 		if($i=="1" || $i%2!="0"){
		 		?>
		 		{
		 			"label":"{{$info['chart_datasets'][$i]}}",
	   				"data":[<?php echo $info['chart_data'][$i] ?>],
	   				"fill":false,
	   				"backgroundColor":[<?php echo $info['chart_backgroundColor'][$i]?>],
	   				"borderColor":[<?php echo $info['chart_borderColor'][$i]?>],
	   				"borderWidth":1
		 		},
		 		<?php
		 		}
		 		}
		 		?>

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
	   			title: {
		            display: true,
		            text: "<?php echo $info['chart_short_title']?>"
		        },
		        tooltips: {
		            mode: 'point'
		        }
		   	}
		 });
		</script>
	</form>
</div>