<?php
if(isset($_GET['id'])){
	$sqllmsSche  = $dblms->querylms("SELECT schedule_id, schedule_month, schedule_approval
									FROM ".INSPECTION_SCHEDULE." 
									WHERE schedule_id = '".cleanvars($_GET['id'])."' 
									AND id_adde = '".$value_emp['emply_id']."' 
									AND is_deleted != '1' 
									LIMIT 1");
	if(mysqli_num_rows($sqllmsSche) > 0) {

		$valueSch = mysqli_fetch_array($sqllmsSche);

		$sqllmsCampus = $dblms->querylms("SELECT c.campus_id, c.campus_name, c.campus_code, t.city_name, d.dist_name
												FROM ".CAMPUS." c
												INNER JOIN ".TEHSIL_CITIES." t ON t.city_id = c.id_city 
												INNER JOIN ".DISTRICTS." d ON d.dist_id = c.id_dist 
												WHERE c.campus_status	= '1' 
												AND c.is_deleted		= '0' 
												AND (c.id_ad = '".$value_emp['emply_id']."' OR c.id_de = '".$value_emp['emply_id']."') 
												ORDER BY c.campus_name ASC");

		echo'
		<div class="row">
			<div class="col-md-12">
				<section class="panel panel-featured panel-featured-primary">
					<form action="inspectionSchedule.php" class="mb-lg validate" enctype="multipart/form-data" method="post" accept-charset="utf-8">
						<div class="panel-heading">
							<h4 class="panel-title"><i class="fa fa-plus-square"></i> Add Inspection Schedule For <b>'.get_monthtypes($valueSch['schedule_month']).'</b></h4>
						</div>
						<div class="panel-body">';
							while($valueCampus = mysqli_fetch_array($sqllmsCampus)) {
								$sqllmsDet  = $dblms->querylms("SELECT purposed_date
																FROM ".INSPECTION_SCHEDULE_DET." 
																WHERE id_schedule = '".cleanvars($valueSch['schedule_id'])."' 
																AND id_campus = '".$valueCampus['campus_id']."' 
																LIMIT 1");
								if(mysqli_num_rows($sqllmsDet) > 0) {
									$valueDet = mysqli_fetch_array($sqllmsDet);
									$converted_date = date("m/d/Y", strtotime($valueDet['purposed_date']));
								}else{
									$converted_date = '';
								}

								if($valueSch['schedule_approval'] == 2) {
									$date = '<input type="text" class="form-control" name="purposed_date[]" id="dated" data-plugin-datepicker="" value="'.$converted_date.'" placeholder="Select Visit Date" aria-invalid="true">';
								} else {
									$date = '<h5>'.$converted_date.'</h5>';
								}
								echo'
								<div class="row mt-sm p-xs mr-xs ml-xs rounded" style="background: #cb3f44; color: #fff;">
									<div class="col-md-6 heading-modal ">
										<h5 class="font-weight-bold"> '.$valueCampus['campus_name'].' ('.$valueCampus['campus_code'].')</h5>
										<input type="hidden" name="id_campus[]" value="'.$valueCampus['campus_id'].'" style="color: #000;">
									</div>
									<div class="col-md-3 heading-modal">
										<h6> '.$valueCampus['city_name'].', '.$valueCampus['dist_name'].'</h6>
									</div>
									<div class="col-md-3">'.$date.'</div>
								</div>';
							}
					
							echo'
							<input type="hidden" name="id_adde" value="'.$value_emp['emply_id'].'">
							<input type="hidden" name="schedule_id" value="'.$_GET['id'].'">
						</div>';
						if($valueSch['schedule_approval'] == 2) {
							echo'
							<footer class="panel-footer">
								<div class="row">
									<div class="col-md-12 text-right">
										<button type="submit" id="update_schedule" name="update_schedule" class="mr-xs btn btn-primary">Confirm Schedule</button>
										<a href="inspectionSchedule.php" class="btn btn-default">Cancel</a>
									</div>
								</div>
							</footer>';
						}
						echo'
					</form>
				</section>
			</div>
		</div>';
	} else {
		header("Location: inspectionSchedule.php");
	}

} else {
	header("Location: inspectionSchedule.php");
}