<?php
	$sql2 = '';
	if(isset($_GET['campus'])){
		$campus = cleanvars($_GET['campus']);
		$sql2 .=" AND campus_id = $campus ";
	}

	$campusid = array();
	$campusname = array();
	$sqllmscampus	= $dblms->querylms("SELECT campus_id ,campus_name
											FROM ".CAMPUS."
											WHERE campus_status = '1'
											$sql2
											ORDER BY campus_id ASC");
	$campus_datas = array();										
	while($value_camp = mysqli_fetch_array($sqllmscampus)) {
		$campusid[] = $value_camp['campus_id'];
		$campusname[] = $value_camp['campus_name'];
		// $campus_datas[] = $value_camp;
	}

	$sqlCampus = $dblms->querylms("SELECT campus_id, campus_code ,campus_name
									FROM ".CAMPUS."
									WHERE campus_status = '1'
									ORDER BY campus_id ASC");
	$campus_datas = array();										
	while($camp_value = mysqli_fetch_array($sqlCampus)) {
		$campus_datas[] = $camp_value;
	}

	
?>
<div class="row">
    <div class="col-md-12">
		<section class="panel">
			<header class="panel-heading">
				<h2 class="panel-title">
					<i class="fa fa-list"></i> 
					<span class="hidden-xs">
						Campus Wise Students Graph
					</span>
				</h2>
			</header>
			<div class="panel-body">
				<form class="form-inline" method="Get" action="dashboard.php">
					<div class="form-group col-sm-4 mb-2">
						<label for="campus" class="sr-only">Campus</label>
						<select data-plugin-selectTwo data-width="100%" name="campus" id="campus" 
								required title="Must Be Required" class="form-control populate">
								<option value="">Select Campus</option>
							<?php
								foreach($campus_datas as $campus_data){
									if(isset($campus) && ($campus == $campus_data['campus_id']) ){
										echo'<option value="'.$campus_data['campus_id'].'" selected>
										'.$campus_data['campus_name'].' ('.$campus_data['campus_code'].')
										</option>';
									} else{
										echo'<option value="'.$campus_data['campus_id'].'">
										'.$campus_data['campus_name'].' ('.$campus_data['campus_code'].')
										</option>';
									}	
									

								}
							?>
						</select>
					</div>
					<div class="form-group col-sm-6 mb-2">
						<a href="dashboard.php" class="btn btn-danger mb-2 "><i class="fa fa-times"></i></a>
						<button type="submit" class="btn btn-info mb-2"><i class="fa fa-search"></i></button>
					</div>
				</form>
				<div class="row">
					<div id="campusWiseStudents" style="height: 400px; width:100%;"></div>
				</div>
				

				
			</div>
		</section>
    </div>
</div>

<script type="application/javascript">
	//STUDENT LAST EXAM MARK GRAPH
	Highcharts.chart('campusWiseStudents', {
		chart: {
			type: 'bar'
		},
		title: {
			text: 'Campus Wise Students'
		},
		xAxis: {
			type: 'category',
			title: {
				text: 'Campuses'
			},
			
			max: 6,
			scrollbar: {
				enabled: true
			},
			categories: [
				<?php
				foreach($campusname as $campusName) {
					// $campusid[] = $value_campdata'campus_id'];
					echo '"'.$campusName.'",';
				}
				?>
			],
			tickLength: 0
		},
		credits: {
			enabled: false
		},
		yAxis: {
			min: 0,
			title: {
				text: 'Campus Wise Students'
			}
		},
		legend: {
			reversed: true
		},
		plotOptions: {
			series: {
				stacking: 'normal'
			}
		},
		
		series: [
			
			{
				name: 'Total No. of Male Students',
				data: [
						<?php
						foreach($campusid as $id){
						$sqllmsstdBoys	= $dblms->querylms("SELECT COUNT(std_id) as total
															FROM ".STUDENTS."
															WHERE std_id != ''
															AND  std_gender = 'Male' 
															AND id_campus = '".$id."'");
						$value_stdBoys = mysqli_fetch_array($sqllmsstdBoys);
						// $value_stdGirls = mysqli_fetch_array($sqllmsstdGirls);
							echo $value_stdBoys['total'].',';
						}
						?>
					]
			}, 
			{
				name: 'Total No. of Female Students',
				data: [
						<?php
						foreach($campusid as $id){
						$sqllmsstdGirls	= $dblms->querylms("SELECT COUNT(std_id) as total
															FROM ".STUDENTS."
															WHERE std_id != ''
															AND  std_gender = 'Female' 
															AND id_campus = '".$id."'");
						$value_stdGirls = mysqli_fetch_array($sqllmsstdGirls);
							echo $value_stdGirls['total'].',';
						}
						?>
					]
			}
		]
	});
</script>

