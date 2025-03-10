<section class="panel">
	<div class="panel-body">
		<div id="classwisestudents" style="height: 400px;"></div>
	</div>
</section>
<script type="application/javascript">
	//STUDENT LAST EXAM MARK GRAPH
	Highcharts.chart('classwisestudents', {
		chart: {
			type: 'column',
			backgroundColor: 'transparent'
		},
		title: {
			text: 'Class Wise Students'
		},
		xAxis: {
			categories: [
				<?php
				$classid = array();
				$sqllmscls	= $dblms->querylms("SELECT class_id, class_name 
													FROM ".CLASSES."
													WHERE class_status = '1' AND is_deleted != '1'
													ORDER BY class_id ASC");
				while($value_cls = mysqli_fetch_array($sqllmscls)) {
					$classid[] = $value_cls['class_id'];
					echo '"'.$value_cls['class_name'].'",';
				}
				?>
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
					<?php
					$pre_boys = 0;
					$primary_boys = 0;
					$middle_boys = 0;
					$high_boys = 0;
					foreach($classid as $id){
						$sqllmsstdBoys	= $dblms->querylms("SELECT COUNT(std_id) as total
															FROM ".STUDENTS."
															WHERE std_id != ''
															AND std_gender = 'Male'
															AND std_status = '1' 
															AND id_class = '".$id."' 
															AND is_deleted != '1' 
															AND id_campus IN (".$id_campus.") ");
						$valuestdBoys = mysqli_fetch_array($sqllmsstdBoys);
						if($id <= 3){
							$pre_boys = $pre_boys + $valuestdBoys['total'];

						} elseif($id > 3 && $id <= 8){

							$primary_boys = $primary_boys + $valuestdBoys['total'];

						} elseif($id > 8 && $id <= 11){

							$middle_boys = $middle_boys + $valuestdBoys['total'];
							
						} elseif($id > 11 ){

							$high_boys = $high_boys + $valuestdBoys['total'];
							
						}
						echo '{y:'.$valuestdBoys['total'].'},';
					}
					?>
				]
			},
			{
				name: 'Total No. of Female Students',
				data: [
					<?php
					$pre_female = 0;
					$primary_female = 0;
					$middle_female = 0;
					$high_female = 0;
					foreach($classid as $id){
						$sqllmsstdGirls	= $dblms->querylms("SELECT COUNT(std_id) as total
															FROM ".STUDENTS."
															WHERE std_id != '' AND std_status = '1' 
															AND std_gender = 'Female'
															AND id_class = '".$id."' AND is_deleted != '1' AND id_campus IN (".$id_campus.") ");
						$valuestdGirls = mysqli_fetch_array($sqllmsstdGirls);
						if($id <= 3){
							$pre_female = $pre_female + $valuestdGirls['total'];

						} elseif($id > 3 && $id <= 8){

							$primary_female = $primary_female + $valuestdGirls['total'];

						} elseif($id > 8 && $id <= 11){

							$middle_female = $middle_female + $valuestdGirls['total'];
							
						} elseif($id > 11 ){

							$high_female = $high_female + $valuestdGirls['total'];
							
						}
						echo '{y:'.$valuestdGirls['total'].'},';
					}
					?>
				]
			}
		]
	});
</script>