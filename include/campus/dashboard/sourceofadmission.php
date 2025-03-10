<section class="panel">
	<div class="panel-body">
		<div id="sourceofadmission" style="height: 400px;"></div>
	</div>
</section>
<script type="application/javascript">
	//STUDENT LAST EXAM MARK GRAPH
	Highcharts.chart('sourceofadmission', {
		chart: {
			type: 'column',
			backgroundColor: 'transparent'
		},
		title: {
			text: 'Source of Students Admission'
		},
		xAxis: {
			categories: [
				<?php
					foreach(get_inquirysrc() as $key => $val):
						echo "\"".$val."\",";
					endforeach;
				?>
			],
			crosshair: true
		},
		yAxis: {
			min: 0,
			title: {
				text: 'Percentage of Source'
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
				name: 'Sources',
				data: [
					<?php
						foreach(get_inquirysrc() as $key => $val):
							$sql = array ( 
											'select' 	=> '
																id
															',
											'where' 	=> array( 
																	  'is_deleted'  => '0'
																	, 'source'   	=> $key
																),
											'search_by'	=> 'AND id_campus IN ('.$id_campus.')',
											'return_type' 	=> 'count' 
										); 
							$count  = $dblms->getRows(ADMISSIONS_INQUIRY, $sql);
							echo '{y:'.((!empty($count)) ? $count: 0).'},';		
						endforeach;
					?>
				]
			}
		]
	});
	
</script>