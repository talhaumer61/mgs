<?php
include "../../../dbsetting/lms_vars_config.php";
include "../../../dbsetting/classdbconection.php";
$dblms = new dblms();
include "../../../functions/login_func.php";
include "../../../functions/functions.php";
checkCpanelLMSALogin();

if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('8', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '8', 'edit' => '1'))) {
	$sqllms	= $dblms->querylms("SELECT p.period_id, p.period_status, p.period_name, p.period_timestart, p.period_timeend, p.period_timestart_friday, p.period_timeend_friday, p.id_campus  
									FROM ".PERIODS." p  
									WHERE p.period_id = '".cleanvars($_GET['id'])."' LIMIT 1");
	$rowsvalues = mysqli_fetch_array($sqllms);
	echo'
	<script src="assets/javascripts/user_config/forms_validation.js"></script>
	<script src="assets/javascripts/theme.init.js"></script>
	<section class="panel panel-featured panel-featured-primary">
		<form action="timetable_period.php" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
			<input type="hidden" name="period_id" id="period_id" value="'.cleanvars($_GET['id']).'">
			<header class="panel-heading">
				<h2 class="panel-title"><i class="glyphicon glyphicon-edit"></i> Edit Period</h2>
			</header>
			<div class="panel-body">
				<div class="form-group">
					<label class="col-md-3 control-label">Period Name <span class="required">*</span></label>
					<div class="col-md-9">
						<input type="text" class="form-control" name="period_name" id="period_name" required title="Must Be Required" value="'.$rowsvalues['period_name'].'" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Friday Time  <span class="required">*</span></label>
					<div class="col-md-9">
						<div class="input-timerange input-group">
							<span class="input-group-addon">
								<i class="fa fa-clock-o"></i>
							</span>
							<input type="text" class="form-control valid" name="period_timestart_friday" id="period_timestart_friday" value="'.$rowsvalues['period_timestart_friday'].'" required  data-plugin-timepicker title="Must Be Required">
							<span class="input-group-addon">to</span>
							<input type="text" class="form-control" name = "period_timeend_friday" id="period_timeend_friday"  value="'.$rowsvalues['period_timeend_friday'].'" required data-plugin-timepicker title="Must Be Required">
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Other than Friday  <span class="required">*</span></label>
					<div class="col-md-9">
						<div class="input-timerange input-group">
							<span class="input-group-addon">
								<i class="fa fa-clock-o"></i>
							</span>
							<input type="text" class="form-control valid" name="period_timestart" id="period_timestart" value="'.$rowsvalues['period_timestart'].'" required  data-plugin-timepicker title="Must Be Required">
							<span class="input-group-addon">to</span>
							<input type="text" class="form-control" name = "period_timeend" id="period_timeend"  value="'.$rowsvalues['period_timeend'].'" required data-plugin-timepicker title="Must Be Required">
						</div>
					</div>
				</div>';
				if(!empty($_SESSION['userlogininfo']['SUBCAMPUSES'])){
					echo'
					<div class="form-group mb-md">
						<label class="col-md-3 control-label">Sub Campus</label>
						<div class="col-md-9">
							<select class="form-control" title="Must Be Required" data-plugin-selectTwo data-width="100%" id="id_campus" name="id_campus">
								<option value="">Select</option>';
								$sqlSubCampus	= $dblms->querylms("SELECT campus_id, campus_name 
																	FROM ".CAMPUS." 
																	WHERE campus_id IN (".$_SESSION['userlogininfo']['SUBCAMPUSES'].")
																	AND campus_status	= '1'
																	AND is_deleted		= '0'
																	ORDER BY campus_id ASC");
								while($valSubCampus = mysqli_fetch_array($sqlSubCampus)) {
									echo '<option value="'.$valSubCampus['campus_id'].'" '.($valSubCampus['campus_id'] == $rowsvalues['id_campus'] ? 'selected' : '').'>'.$valSubCampus['campus_name'].'</option>';
								}
								echo'
							</select>
						</div>
					</div>';
				}
				echo'
				<div class="form-group">
					<label class="col-sm-3 control-label">Status <span class="required">*</span></label>
					<div class="col-md-9">';
						if($rowsvalues['period_status'] == 1) { 
							echo '
								<div class="radio-custom radio-inline">
									<input type="radio" id="period_status" name="period_status" value="1" checked>
									<label for="radioExample1">Active</label>
								</div>';
						} else { 
							echo '
								<div class="radio-custom radio-inline">
									<input type="radio" id="period_status" name="period_status" value="1">
									<label for="radioExample1">Active</label>
								</div>';
						}
						if($rowsvalues['period_status'] == 2) { 
							echo '
								<div class="radio-custom radio-inline">
									<input type="radio" id="period_status" name="period_status" checked value="2">
									<label for="radioExample2">Inactive</label>
								</div>';
						} else { 
							echo '
								<div class="radio-custom radio-inline">
									<input type="radio" id="period_status" name="period_status" value="2">
									<label for="radioExample2">Inactive</label>
								</div>';
						}
						echo '
					</div>
				</div>
			</div>
			<footer class="panel-footer">
				<div class="row">
					<div class="col-md-12 text-right">
						<button type="submit" class="btn btn-primary" id="changes_period" name="changes_period">Update</button>
						<button class="btn btn-default modal-dismiss">Cancel</button>
					</div>
				</div>
			</footer>
		</form>
	</section>>';
}
?>