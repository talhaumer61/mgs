<?php 

	// Month
	if(isset($_POST['schedule_month'])){$month = $_POST['schedule_month'];}else{$month = "";}

	if(!isset($_POST['view_details'])){
		echo '
		<div class="row">
			<div class="col-md-12">
				<section class="panel panel-featured panel-featured-primary">
					<form action="#" class="mb-lg validate" enctype="multipart/form-data" method="post" accept-charset="utf-8">
						<div class="panel-heading">
							<h4 class="panel-title"><i class="fa fa-plus-square"></i> Add Inspecion Schedule</h4>
						</div>
						<div class="panel-body">
							<div class="row mt-sm">
								<div class="col-md-offset-4 col-md-4">
									<label class="control-label">Month <span class="required">*</span></label>
									<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" name="schedule_month">
										<option value="">Select</option>';
											foreach($monthtypes as $month) {
												echo '<option value="'.$month['id'].'">'.$month['name'].'</option>';
											}
										echo '
									</select>
								</div>
								
								<div class="col-md-12 text-center mt-md">
									<button type="submit" id="view_details" name="view_details" class="mr-xs btn btn-primary">Get Details</button>
								</div>
							</div>			
						</div>
					</form>
				</section>
			</div>
		</div>';
	}


	if(isset($_POST['view_details'])){

		$sqllmscheck  = $dblms->querylms("SELECT schedule_id
										FROM ".INSPECTION_SCHEDULE." 
										WHERE schedule_month = '".$month."' 
										AND id_adde = '".$value_emp['emply_id']."' AND is_deleted != '1' LIMIT 1");
		if(mysqli_num_rows($sqllmscheck) > 0) {
			$_SESSION['msg']['title'] 	= 'Error';
			$_SESSION['msg']['text'] 	= 'Record Already Exists';
			$_SESSION['msg']['type'] 	= 'error';
			header("Location: inspectionSchedule.php", true, 301);
			exit();
		} 

		$sqllmsCampus = $dblms->querylms("SELECT c.campus_id, c.campus_name, c.campus_code, t.city_name, d.dist_name
												FROM ".CAMPUS." c
												INNER JOIN ".CAMPUS_BIOGRAPHY." b ON b.id_campus = c.campus_id 
												LEFT JOIN ".TEHSIL_CITIES." t ON t.city_id = c.id_city 
												LEFT JOIN ".DISTRICTS." d ON d.dist_id = c.id_dist 
												WHERE c.campus_id != '' 
												AND c.campus_status = '1' 
												AND c.is_deleted != '1' 
												AND (b.id_ad = '".$value_emp['emply_id']."' OR b.id_de = '".$value_emp['emply_id']."') 
												AND b.is_deleted != '1' 
												GROUP By b.id_campus
												ORDER BY c.campus_name ASC, b.bio_id DESC");

		echo'
		<div class="row">
			<div class="col-md-12">
				<section class="panel panel-featured panel-featured-primary">
					<form action="inspectionSchedule.php" class="mb-lg validate" enctype="multipart/form-data" method="post" accept-charset="utf-8">
						<div class="panel-heading">
							<h4 class="panel-title"><i class="fa fa-plus-square"></i> Add Inspection Schedule For <b>'.get_monthtypes($month).'</b></h4>
						</div>
						<div class="panel-body">';
								while($valueCampus = mysqli_fetch_array($sqllmsCampus)) {
									echo'
									<div class="row mt-sm ml-xs mr-xs"  style="background-color: #cb3f44; color: white; padding: 6px 10px; border-radius: 5px; mt-sm">
										<div class="col-md-4 heading-modal ">
											<h5 class="font-weight-bold"> '.$valueCampus['campus_name'].' ('.$valueCampus['campus_code'].')</h5>
											<input type="hidden" name="id_campus[]" value="'.$valueCampus['campus_id'].'">
										</div>
										<div class="col-md-4 heading-modal">
											<h6> '.$valueCampus['city_name'].', '.$valueCampus['dist_name'].'</h6>
										</div>
										<div class="col-md-3">
											<input type="text" class="form-control" name="purposed_date[]" id="dated" data-plugin-datepicker="" value="Select Visit Date" aria-invalid="true">
										</div>
									</div>';
								}
						
								echo '
								<input type="hidden" name="schedule_month" value="'.$month.'">
								<input type="hidden" name="id_adde" value="'.$value_emp['emply_id'].'">
						</div>
						<footer class="panel-footer mt-sm">
							<div class="row">
								<div class="col-md-12 text-right">
									<button type="submit" id="submit_schedule" name="submit_schedule" class="mr-xs btn btn-primary">Confirm Schedule</button>
									<button type="reset" class="btn btn-default">Reset</button>
								</div>
							</div>
						</footer>
					</form>
				</section>
			</div>
		</div>';
	}
?>