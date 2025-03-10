<section class="panel">
	<div class="panel-body">
		<div id="catwisestudents" style="height: 400px;"></div>
	</div>
</section>
<script type="application/javascript">
	//STUDENT LAST EXAM MARK GRAPH
	Highcharts.chart('catwisestudents', {
		chart: {
			type: 'column',
			backgroundColor: 'transparent'
		},
		title: {
			text: 'Class Category Wise Students'
		},
		xAxis: {
			categories: [
	        "Pre Section",
	        "Primary Section",
	        "Middle Section",
	        "High",
			],
			crosshair: true
		},
		yAxis: {
			min: 0,
			title: {
				text: 'No of Students'
			}
		},
		tooltip: {
			crosshairs: true,
			shared: true
			},
		credits: {
			enabled: false
		},
		legend: {
			itemStyle: { "color": "#505461"},
			itemHoverStyle: { "color": "#505461" }
		},
		plotOptions: {
			bar: {
				dataLabels: {
					enabled: true
				}
			}
		},
		series: [
			{
				name: 'Total No. of Male Students',
				data: [
					{y:<?php echo $pre_boys; ?>},
					{y:<?php echo $primary_boys; ?>},
					{y:<?php echo $middle_boys; ?>},
					{y:<?php echo $high_boys; ?>},
				]
			},
			{
				name: 'Total No. of Female Students',
				data: [
					{y:<?php echo $pre_female; ?>},
					{y:<?php echo $primary_female; ?>},
					{y:<?php echo $middle_female; ?>},
					{y:<?php echo $high_female; ?>},
				]
			}
		]
	});
	
</script>