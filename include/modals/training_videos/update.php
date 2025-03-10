<?php 
//---------------------------------------------------------
	include "../../dbsetting/lms_vars_config.php";
	include "../../dbsetting/classdbconection.php";
	$dblms = new dblms();
	include "../../functions/login_func.php";
	include "../../functions/functions.php";
	checkCpanelLMSALogin();
//---------------------------------------------------------
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '65', 'edit' => '1'))){ 
//---------------------------------------------------------
	$sqllms	= $dblms->querylms("SELECT *
								   FROM ".TRAINING_VIDEOS." 
								   WHERE id = '".cleanvars($_GET['id'])."' LIMIT 1");
	$rowsvalues = mysqli_fetch_array($sqllms);
//---------------------------------------------------------
	$campus_type =  explode("," ,$rowsvalues['campus_type']);
//---------------------------------------------------------
echo '
<script src="assets/javascripts/user_config/forms_validation.js"></script>
<script src="assets/javascripts/theme.init.js"></script>
<div class="row">
<div class="col-md-12">
<section class="panel panel-featured panel-featured-primary">
	<form action="training_videos.php" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
	<input type="hidden" name="id" id="id" value="'.cleanvars($_GET['id']).'">
		<header class="panel-heading">
			<h2 class="panel-title"><i class="glyphicon glyphicon-edit"></i> Edit Training Video </h2>
		</header>
		<div class="panel-body">
			<div class="form-group">
				<label class="col-md-3 control-label">Session <span class="required">*</span></label>
				<div class="col-md-9">
					<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" id="id_session" name="id_session">
						<option value="">Select</option>';
							$sqllmscls	= $dblms->querylms("SELECT session_id, session_name 
													FROM ".SESSIONS."
													WHERE session_status = '1'
													ORDER BY session_name DESC");
							while($valuecls = mysqli_fetch_array($sqllmscls)) {
								if($valuecls['session_id'] == $rowsvalues['id_session']) { 
									echo '<option value="'.$valuecls['session_id'].'" selected>'.$valuecls['session_name'].'</option>';
								} else {
									echo '<option value="'.$valuecls['session_id'].'">'.$valuecls['session_name'].'</option>';
								}	
							}
					echo '
					</select>
				</div>
			</div>
			<div class="form-group mb-md">
				<label class="col-md-3 control-label">Title <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="text" class="form-control" id="title" name="title" value="'.$rowsvalues['title'].'" required title="Must Be Required">
				</div>
			</div>
			<div class="form-group mb-md">
				<label class="col-md-3 control-label">Thumbnail <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="text" class="form-control" id="thumbnail" name="thumbnail" value="'.$rowsvalues['thumbnail'].'" required title="Must Be Required">
				</div>
			</div>
			<div class="form-group mb-md">
				<label class="col-md-3 control-label">Youtube Link <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="text" class="form-control" id="youtube_url" name="youtube_url"  value="'.$rowsvalues['youtube_url'].'" required title="Must Be Required">
				</div>
			</div>
			<div class="form-group mb-md">
				<label class="col-md-3 control-label">Details</label>
				<div class="col-md-9">
					<textarea class="form-control" id="details" name="details">'.$rowsvalues['details'].'</textarea>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label">Recipient <span class="required" aria-required="true">*</span></label>
				<div class="col-md-9">
					<div class="checkbox-custom checkbox-inline">
						<input type="checkbox" id="for_campus" name="for_campus"  '; if($rowsvalues['for_campus'] == 1){echo 'checked';} echo' value="1">
						<label for="checkboxExample&quot;">Campus </label>
					</div>
					<div class="checkbox-custom checkbox-inline">
						<input type="checkbox" id="for_staffs" name="for_staffs" '; if($rowsvalues['for_staffs'] == 1){echo 'checked';} echo' value="1">
						<label for="checkboxExample&quot;">Staff</label>
					</div>
					<div class="checkbox-custom checkbox-inline">
						<input type="checkbox" id="for_parent" name="for_parent" '; if($rowsvalues['for_parent'] == 1){echo 'checked';} echo' value="1">
						<label for="checkboxExample&quot;">Parent</label>
					</div>
					<div class="checkbox-custom checkbox-inline">
						<input type="checkbox" id="for_students" name="for_students" '; if($rowsvalues['for_students'] == 1){echo 'checked';} echo' value="1">
						<label for="checkboxExample2&quot;">Student</label>
					</div>
				</div>
			</div>
			<div class="form-group" >
				<label class="col-md-3 control-label">Campus Type<span class="required">*</span></label>
				<div class="col-md-9">
					<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" id="campus_type" name="campus_type[]" multiple="multiple">
						<option value="">Select</option>';
						$sqllmslvl	= $dblms->querylms("SELECT level_id, level_name, level_code
														FROM ".CAMPUS_LEVELS."
														WHERE level_status = '1'
														ORDER BY level_ordering DESC");
							while($valuelvl = mysqli_fetch_array($sqllmslvl)) {
								echo '<option value="'.$valuelvl['level_id'].'" '; if(in_array($valuelvl['level_id'], $campus_type ) ){ echo'selected';} echo'>'.$valuelvl['level_name'].' ('.$valuelvl['level_code'].')</option>';
							}
					echo '
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Status <span class="required">*</span></label>
				<div class="col-md-9">
					<div class="radio-custom radio-inline">
						<input type="radio" id="status" name="status" value="1"'; if($rowsvalues['status'] == 1) {echo ' checked';} echo '>
						<label for="radioExample1">Active</label>
					</div>
					<div class="radio-custom radio-inline">
						<input type="radio" id="status" name="status" value="2"'; if($rowsvalues['status'] == 2) {echo ' checked';} echo '>
						<label for="radioExample2">Inactive</label>
					</div>
				</div>
			</div>
		</div>
		<footer class="panel-footer">
			<div class="row">
				<div class="col-md-12 text-right">
					<button type="submit" class="btn btn-primary" id="changes_video" name="changes_video">Update</button>
					<button class="btn btn-default modal-dismiss">Cancel</button>
				</div>
			</div>
		</footer>
	</form>
</section>
</div>
</div>';
}
?>