<?php
include "../../dbsetting/lms_vars_config.php";
include "../../dbsetting/classdbconection.php";
$dblms = new dblms();
include "../../functions/login_func.php";
include "../../functions/functions.php";
checkCpanelLMSALogin();

if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '20', 'edit' => '1'))){
	$sqllms	= $dblms->querylms("SELECT level_id, level_status, level_ordering, level_name, level_code, level_classes
								   FROM ".CAMPUS_LEVELS."
								   WHERE level_id != '' AND is_deleted != '1'
								   AND level_id = '".cleanvars($_GET['id'])."'
								   LIMIT 1");
	$rowsvalues = mysqli_fetch_array($sqllms);
	echo'
	<script src="assets/javascripts/user_config/forms_validation.js"></script>
	<script src="assets/javascripts/theme.init.js"></script>
	<div class="row">
		<div class="col-md-12">
			<section class="panel panel-featured panel-featured-primary">
				<form action="campus_level.php" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
				<input type="hidden" name="level_id" id="level_id" value="'.cleanvars($_GET['id']).'">
					<header class="panel-heading">
						<h2 class="panel-title"><i class="glyphicon glyphicon-edit"></i> Edit Campus Level</h2>
					</header>
					<div class="panel-body">
						<div class="form-group mt-sm">
							<label class="col-md-3 control-label">Name <span class="required">*</span></label>
							<div class="col-md-9">
								<input type="text" class="form-control" name="level_name" id="level_name" required title="Must Be Required" value="'.$rowsvalues['level_name'].'" />
							</div>
						</div>
						<div class="form-group mt-sm">
							<label class="col-md-3 control-label">Code <span class="required">*</span></label>
							<div class="col-md-9">
								<input type="text" class="form-control" name="level_code" id="level_code" required title="Must Be Required" value="'.$rowsvalues['level_code'].'" />
							</div>
						</div>
						<div class="form-group mt-sm">
							<label class="col-md-3 control-label">Ordering <span class="required">*</span></label>
							<div class="col-md-9">
								<input type="number" class="form-control" name="level_ordering" id="level_ordering" required title="Must Be Required" value="'.$rowsvalues['level_ordering'].'" />
							</div>
						</div>';
						
						//------------------------------------------------
						$classes = $rowsvalues['level_classes'];
						$values = explode(",",$rowsvalues['level_classes']);
						//------------------------------------------------
						echo'
						<div class="form-group mb-md">
							<label class="col-md-3 control-label">Classes </label>
							<div class="col-md-9">';
							$sqllmscls = $dblms->querylms("SELECT class_id, class_name
															FROM ".CLASSES."
															WHERE class_id != ''
															AND class_status = '1' AND is_deleted != '1'
															ORDER BY class_id ASC");
							$i = 0;
							while($valuecls = mysqli_fetch_array($sqllmscls)) {
								echo'
								<div class="col-md-3">
									<div class="row">
										<div class="checkbox-custom checkbox-inline">
											<input type="checkbox" id="level_classes" name="level_classes[]" value="'.$valuecls['class_id'].'"'; if(in_array($valuecls['class_id'], $values)){ echo'checked';} echo'>
											<label for="checkboxExample1">'.$valuecls['class_name'].'</label>
										</div>
									</div>
								</div>';
							}
							echo'
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Status <span class="required">*</span></label>
							<div class="col-md-9">
								<div class="radio-custom radio-inline">
									<input type="radio" id="level_status" name="level_status" value="1"'; if($rowsvalues['level_status'] == 1) {echo'checked';} echo'>
									<label for="radioExample1">Active</label>
								</div>
								<div class="radio-custom radio-inline">
									<input type="radio" id="level_status" name="level_status" value="2"'; if($rowsvalues['level_status'] == 2){echo'checked';} echo'>
									<label for="radioExample2">Inactive</label>
								</div>
							</div>
						</div>
					</div>
					<footer class="panel-footer">
						<div class="row">
							<div class="col-md-12 text-right">
								<button type="submit" class="btn btn-primary" id="changes_level" name="changes_level">Update</button>
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