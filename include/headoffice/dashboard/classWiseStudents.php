<?php
	$sql2 = '';
	if(isset($_GET['class'])){
		$class = cleanvars($_GET['class']);
		$sql2 .=" AND class_id = $class ";
	}

	$classId = array();
	$className = array();
	$sqllmsClasses	= $dblms->querylms("SELECT class_id, class_name
											FROM ".CLASSES."
											WHERE class_status = '1'
											$sql2
											ORDER BY class_id ASC");
	$classData = array();										
	while($valueClass = mysqli_fetch_array($sqllmsClasses)) {
		$classId[] = $valueClass['class_id'];
		$className[] = $valueClass['class_name'];
		// $classData[] = $valueClass;
	}

	$sqlClasses = $dblms->querylms("SELECT class_id, class_code, class_name
									FROM ".CLASSES."
									WHERE class_status = '1'
									ORDER BY class_id ASC");
	$classesData = array();										
	while($valueClass = mysqli_fetch_array($sqlClasses)) {
		$classesData[] = $valueClass;
	}

	
?>
<div class="row">
    <div class="col-md-12">
		<section class="panel">
			<header class="panel-heading">
				<h2 class="panel-title">
					<i class="fa fa-list"></i> 
					<span class="hidden-xs">
						Class Wise Students Graph
					</span>
				</h2>
			</header>
			<div class="panel-body">
				<form class="form-inline" method="Get" action="dashboard.php">
					<div class="form-group col-sm-4 mb-2">
						<label for="class" class="sr-only">Class</label>
						<select data-plugin-selectTwo data-width="100%" name="class" id="class" 
								required title="Must Be Required" class="form-control populate">
								<option value="">Select Class</option>
							<?php
								foreach($classesData as $classData){
									if(isset($class) && ($class == $classData['class_id']) ){
										echo'<option value="'.$classData['class_id'].'" selected>
										'.$classData['class_name'].' ('.$classData['class_code'].')
										</option>';
									} else{
										echo'<option value="'.$classData['class_id'].'">
										'.$classData['class_name'].' ('.$classData['class_code'].')
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
					<div id="classWiseStudents" style="height: 400px; width:100%;"></div>
				</div>
				

				
			</div>
		</section>
    </div>
</div>

<script type="application/javascript">
	//STUDENT LAST EXAM MARK GRAPH
	Highcharts.chart('classWiseStudents', {
		chart: {
			type: 'bar'
		},
		title: {
			text: 'Class Wise Students'
		},
		xAxis: {
			type: 'category',
			title: {
				text: 'Classes'
			},
			
			max: 6,
			scrollbar: {
				enabled: true
			},
			categories: [
				<?php
				foreach($className as $className) {
					// $campusid[] = $value_campdata'campus_id'];
					echo '"'.$className.'",';
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
				text: 'Class Wise Students'
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
						foreach($classId as $id){
						$sqllmsstdBoys	= $dblms->querylms("SELECT COUNT(std_id) as total
															FROM ".STUDENTS."
															WHERE std_id != ''
															AND  std_gender = 'Male' 
															AND id_class = '".$id."'");
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
						foreach($classId as $id){
						$sqllmsstdGirls	= $dblms->querylms("SELECT COUNT(std_id) as total
															FROM ".STUDENTS."
															WHERE std_id != ''
															AND  std_gender = 'Female' 
															AND id_class = '".$id."'");
						$value_stdGirls = mysqli_fetch_array($sqllmsstdGirls);
							echo $value_stdGirls['total'].',';
						}
						?>
					]
			}
		]
	});
</script>

