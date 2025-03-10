<?php
include "../../dbsetting/lms_vars_config.php";
include "../../dbsetting/classdbconection.php";
$dblms = new dblms();
include "../../functions/login_func.php";
include "../../functions/functions.php";
checkCpanelLMSALogin();

if(($_SESSION['userlogininfo']['LOGINAFOR'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '8', 'updated' => '1'))){
	$sqllms	= $dblms->querylms("SELECT dist_status, dist_ordering, dist_name, dist_code, id_zone, id_prov
								   FROM ".DISTRICTS."
								   WHERE dist_id != '' AND is_deleted != '1'
								   AND dist_id = '".cleanvars($_GET['id'])."'
								   ORDER BY dist_id ASC LIMIT 1");
	$rowsvalues = mysqli_fetch_array($sqllms);
	echo'
	<script src="assets/javascripts/user_config/forms_validation.js"></script>
	<script src="assets/javascripts/theme.init.js"></script>
	<div class="row">
		<div class="col-md-12">
			<section class="panel panel-featured panel-featured-primary">
				<form action="district.php" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
				<input type="hidden" name="dist_id" id="dist_id" value="'.cleanvars($_GET['id']).'">
					<header class="panel-heading">
						<h2 class="panel-title"><i class="glyphicon glyphicon-edit"></i> Edit District</h2>
					</header>
					<div class="panel-body">
						<div class="form-group mt-sm">
							<label class="col-md-3 control-label">Name <span class="required">*</span></label>
							<div class="col-md-9">
								<input type="text" class="form-control" name="dist_name" id="dist_name" required title="Must Be Required" value="'.$rowsvalues['dist_name'].'" />
							</div>
						</div>
						<div class="form-group mt-sm">
							<label class="col-md-3 control-label">Code <span class="required">*</span></label>
							<div class="col-md-9">
								<input type="text" class="form-control" name="dist_code" id="dist_code" required title="Must Be Required" value="'.$rowsvalues['dist_code'].'" />
							</div>
						</div>
						<div class="form-group mt-sm">
							<label class="col-md-3 control-label">Ordering <span class="required">*</span></label>
							<div class="col-md-9">
								<input type="number" class="form-control" name="dist_ordering" id="dist_ordering" required title="Must Be Required" value="'.$rowsvalues['dist_ordering'].'" />
							</div>
						</div>
						<div class="form-group mb-md">
							<label class="col-md-3 control-label">Province <span class="required">*</span></label>
							<div class="col-md-9">
								<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" name="id_prov">
									<option value="">Select</option>';
									$sqllmsprov	= $dblms->querylms("SELECT prov_id, prov_name
																	FROM ".PROVINCES."
																	WHERE prov_status	= '1'
																	AND is_deleted		= '0'
																	ORDER BY prov_ordering ASC");
									while($valueprov = mysqli_fetch_array($sqllmsprov)) {
										echo'<option value="'.$valueprov['prov_id'].'" '.($rowsvalues['id_prov']==$valueprov['prov_id'] ? 'selected' : '').'>'.$valueprov['prov_name'].'</option>';
									}
								echo '
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Status <span class="required">*</span></label>
							<div class="col-md-9">
								<div class="radio-custom radio-inline">
									<input type="radio" id="dist_status" name="dist_status" value="1"'; if($rowsvalues['dist_status'] == 1) {echo'checked';} echo'>
									<label for="radioExample1">Active</label>
								</div>
								<div class="radio-custom radio-inline">
									<input type="radio" id="dist_status" name="dist_status" value="2"'; if($rowsvalues['dist_status'] == 2){echo'checked';} echo'>
									<label for="radioExample2">Inactive</label>
								</div>
							</div>
						</div>
					</div>
					<footer class="panel-footer">
						<div class="row">
							<div class="col-md-12 text-right">
								<button type="submit" class="btn btn-primary" id="changes_district" name="changes_district">Update</button>
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