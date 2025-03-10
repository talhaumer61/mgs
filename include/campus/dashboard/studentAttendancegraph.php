<?php
echo'
<section class="panel">
	<div class="panel-body">
		<div id="stdAttendancegraph" style="height: 400px;"></div>
	</div>
</section>';
?>
<script type="application/javascript">
//TOTAL SCHOOL ATTENDANCE GRAPH SCRIPT
	Highcharts.chart('stdAttendancegraph', {
		chart: {
			type: 'areaspline',
			backgroundColor: 'transparent'
		},
		title: {
						text: 'Students Attendance'
		},
		xAxis: {
			categories:[
				<?php
					$sqllmsStdAtt	= $dblms->querylms("SELECT a.id, a.dated
														FROM ".STUDENT_ATTENDANCE." a
														WHERE a.id_campus IN (".cleanvars($id_campus).")  
														AND a.id_session = '".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'
														ORDER BY a.dated");
					$idsAtt = array();
					while($valStdAtt = mysqli_fetch_array($sqllmsStdAtt)) {
						$idsAtt[] = $valStdAtt['id'];
						// echo json_encode($dated);
						echo '"'.$valStdAtt['dated'].'",';
					}
				?>
			],
			crosshair: true
		},
		yAxis: {
			min: 0,
			title: {
				text: 'No. of Students'
			}
		},
		tooltip: {
			headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
			pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
				'<td style="padding:0"><b>{point.y}</b></td></tr>',
			footerFormat: '</table>',
			shared: true,
			useHTML: true
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
				name: 'Present',
				data: [
					<?php
						foreach($idsAtt as $idAtt){
							$sqllmsStdPresDet	= $dblms->querylms("SELECT COUNT(status) as totPresent
																FROM ".STUDENT_ATTENDANCE_DETAIL."
																WHERE id_setup = '".$idAtt."' AND status = '1' ");
							$valStdPresDet = mysqli_fetch_array($sqllmsStdPresDet);
							if($valStdPresDet['totPresent'] > 0){
								echo"{ y:".$valStdPresDet['totPresent']."},";
							}
							else{
								echo"{ y:0},";
							}
						}
					?>
					// { y:0},{ y:4},{ y:0},{ y:0},{ y:0},{ y:0},{ y:6},{ y:0},{ y:0},{ y:0},{ y:0},{ y:0},{ y:0},{ y:0},{ y:0},{ y:6},{ y:0},{ y:5},{ y:0},			
					// {y:6},{y:2},{y:9},
				
				]

			}, {
				name: 'Absent',
				data: [
					<?php
					foreach($idsAtt as $idAtt){
						$sqllmsStdAbDet	= $dblms->querylms("SELECT COUNT(status) as totAbsent
															FROM ".STUDENT_ATTENDANCE_DETAIL."
															WHERE id_setup = '".$idAtt."' AND status = '2'");
						$valStdAbDet = mysqli_fetch_array($sqllmsStdAbDet);
						if($valStdAbDet['totAbsent'] > 0){
							echo"{ y:".$valStdAbDet['totAbsent']."},";
						}
						else{
							echo"{ y:0},";
						}
					}
					?>
					// { y:0},{ y:0},{ y:0},{ y:0},{ y:0},{ y:0},{ y:0},{ y:0},{ y:0},{ y:0},{ y:0},{ y:0},{ y:0},{ y:0},{ y:0},{ y:0},{ y:0},{ y:1},{ y:0},
				],
				color: 'rgba(192,2,22,0.75)'
			}, {
				name: 'Late',
				data: [
					<?php
					foreach($idsAtt as $idAtt){
						$sqllmsStdAbDet	= $dblms->querylms("SELECT COUNT(status) as totLate
															FROM ".STUDENT_ATTENDANCE_DETAIL."
															WHERE id_setup = '".$idAtt."' AND status = '4' ");
						$valStdAbDet = mysqli_fetch_array($sqllmsStdAbDet);
						if($valStdAbDet['totLate'] > 0){
							echo"{ y:".$valStdAbDet['totLate']."},";
						}
						else{
							echo"{ y:0},";
						}
					}
					?>
					// { y:0},{ y:0},{ y:0},{ y:0},{ y:0},{ y:0},{ y:0},{ y:0},{ y:0},{ y:0},{ y:0},{ y:0},{ y:0},{ y:0},{ y:0},{ y:0},{ y:0},{ y:1},{ y:0},
				],
				color: 'rgb(119,119,119)'
			}
		]
	});
</script>