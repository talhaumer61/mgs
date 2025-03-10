<?php 
include "../../dbsetting/lms_vars_config.php";
include "../../dbsetting/classdbconection.php";
$dblms = new dblms();
include "../../functions/login_func.php";
include "../../functions/functions.php";
checkCpanelLMSALogin();

if(($_SESSION['userlogininfo']['LOGINAFOR'] == 1 && $_SESSION['userlogininfo']['LOGINTYPE'] == 1) || ($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('88', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '88', 'edit' => '1'))) {
	$sqllms	= $dblms->querylms("SELECT  not_id, not_status, id_type, not_title, dated, not_description, id_session, to_campus, to_staff, to_parent, to_student, date_to, date_from, id_campus
										FROM ".NOTIFICATIONS." 
										WHERE is_deleted != '1' AND not_id = '".cleanvars($_GET['id'])."' 
										ORDER BY dated ASC LIMIT 1 
										");
	$rowsvalues = mysqli_fetch_array($sqllms);
	$dated = date('m/d/Y' , strtotime(cleanvars($rowsvalues['dated'])));	
	echo'
	<script src="assets/javascripts/user_config/forms_validation.js"></script>
	<script src="assets/javascripts/theme.init.js"></script>
	<section class="panel panel-featured panel-featured-primary">
		<form action="notifications.php" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
		<input type="hidden" name="not_id" id="not_id" value="'.cleanvars($_GET['id']).'">
			<header class="panel-heading">
				<h2 class="panel-title"><i class="glyphicon glyphicon-edit"></i> Edit Notification</h2>
			</header>
			<div class="panel-body">';
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
				<div class="form-group mt-sm">
					<label class="col-md-3 control-label">Title <span class="required">*</span></label>
					<div class="col-md-9">
						<input type="text" class="form-control" name="not_title" id="not_title" required title="Must Be Required" value="'.$rowsvalues['not_title'].'" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Dated <span class="required">*</span></label>
					<div class="col-md-9">
						<input type="text" class="form-control" name="dated" id="dated" value="'.$dated.'" data-plugin-datepicker required title="Must Be Required" />
					</div>
				</div>
				<div class="form-group mb-md">
					<label class="col-md-3 control-label">Date From <span class="required">*</span></label>
					<div class="col-md-9">
						<div class="input-daterange input-group" data-plugin-datepicker>
							<span class="input-group-addon">
								<i class="fa fa-calendar"></i>
							</span>
							<input type="text" class="form-control valid" name="date_from" value="'.date('m/d/Y' , strtotime($rowsvalues['date_from'])).'" id="date_from" required="" title="Must Be Required" aria-required="true" aria-invalid="false">
							<span class="input-group-addon">to</span>
							<input type="text" class="form-control" name = "date_to" id="date_to"value="'.date('m/d/Y' , strtotime($rowsvalues['date_to'])).'" required="" title="Must Be Required"  aria-required="true">
						</div>
					</div>
				</div>
				<div class="form-group mb-md">
					<label class="col-md-3 control-label">Details</label>
					<div class="col-md-9">
						<textarea class="form-control" rows="2" name="not_description" id="not_description">'.$rowsvalues['not_description'].'</textarea>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Recipient <span class="required">*</span></label>
					<div class="col-md-9">';
						if($_SESSION['userlogininfo']['LOGINAFOR'] == 1){
							echo'
							<div class="checkbox-custom checkbox-inline">
								<input type="checkbox" id="to_campus" name="to_campus"'; if($rowsvalues['to_campus'] == 1){echo'checked';}echo'>
								<label for=checkboxExample">Campus </label>
							</div>';
						}
						echo'
						<div class="checkbox-custom checkbox-inline">
							<input type="checkbox" id="to_staff" name="to_staff"'; if($rowsvalues['to_staff'] == 1){echo'checked';}echo'>
							<label for=checkboxExample">Staff</label>
						</div>
						<div class="checkbox-custom checkbox-inline">
							<input type="checkbox" id="to_parent" name="to_parent"'; if($rowsvalues['to_parent'] == 1){echo'checked';}echo'>
							<label for=checkboxExample">Parent</label>
						</div>
						<div class="checkbox-custom checkbox-inline">
							<input type="checkbox" id="to_student" name="to_student"'; if($rowsvalues['to_student'] == 1){echo'checked';}echo'>
							<label for=checkboxExample2">Student</label>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Type <span class="required">*</span></label>
					<div class="col-md-9">
						<div class="radio-custom radio-inline">
							<input type="radio" id="id_type" name="id_type" value="1"'; if($rowsvalues['id_type'] == 1) { echo'checked';} echo' >
							<label for="radioExample1">Popup</label>
						</div>
						<div class="radio-custom radio-inline">
							<input type="radio" id="id_type" name="id_type" value="2"'; if($rowsvalues['id_type'] == 2) { echo'checked';} echo' >
							<label for="radioExample2">Navbar</label>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Status <span class="required">*</span></label>
					<div class="col-md-9">
						<div class="radio-custom radio-inline">
							<input type="radio" id="not_status" name="not_status" value="1"'; if($rowsvalues['not_status'] == 1) { echo'checked';} echo' >
							<label for="radioExample1">Active</label>
						</div>
						<div class="radio-custom radio-inline">
							<input type="radio" id="not_status" name="not_status" value="2"'; if($rowsvalues['not_status'] == 2) { echo'checked';} echo' >
							<label for="radioExample2">Inactive</label>
						</div>
					</div>
				</div>
			</div>
			<footer class="panel-footer">
				<div class="row">
					<div class="col-md-12 text-right">
						<button type="submit" class="btn btn-primary" id="changes_notification" name="changes_notification">Update</button>
						<button class="btn btn-default modal-dismiss">Cancel</button>
					</div>
				</div>
			</footer>
		</form>
	</section>';
}
?>