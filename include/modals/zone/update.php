<?php
include "../../dbsetting/lms_vars_config.php";
include "../../dbsetting/classdbconection.php";
$dblms = new dblms();
include "../../functions/login_func.php";
include "../../functions/functions.php";
checkCpanelLMSALogin();

if(($_SESSION['userlogininfo']['LOGINAFOR'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '8', 'updated' => '1'))){
	$sqllms	= $dblms->querylms("SELECT zone_status, zone_ordering, zone_name, zone_code
								   FROM ".ZONES."
								   WHERE zone_id != '' AND is_deleted != '1'
								   AND zone_id = '".cleanvars($_GET['id'])."'
								   ORDER BY zone_id ASC LIMIT 1");
	$rowsvalues = mysqli_fetch_array($sqllms);
	echo'
	<script src="assets/javascripts/user_config/forms_validation.js"></script>
	<script src="assets/javascripts/theme.init.js"></script>
	<div class="row">
		<div class="col-md-12">
			<section class="panel panel-featured panel-featured-primary">
				<form action="zone.php" class="form-horizontal" id="form" autocomplete="off" enctype="multipart/form-data" method="post" accept-charset="utf-8">
					<input type="hidden" name="zone_id" id="zone_id" value="'.cleanvars($_GET['id']).'">
					<header class="panel-heading">
						<h2 class="panel-title"><i class="glyphicon glyphicon-edit"></i> Edit Zone</h2>
					</header>
					<div class="panel-body">
						<div class="form-group mt-sm">
							<label class="col-md-3 control-label">Name <span class="required">*</span></label>
							<div class="col-md-9">
								<input type="text" class="form-control" name="zone_name" id="zone_name" required title="Must Be Required" value="'.$rowsvalues['zone_name'].'" />
							</div>
						</div>
						<div class="form-group mt-sm">
							<label class="col-md-3 control-label">Code <span class="required">*</span></label>
							<div class="col-md-9">
								<input type="text" class="form-control" name="zone_code" id="zone_code" required title="Must Be Required" value="'.$rowsvalues['zone_code'].'" />
							</div>
						</div>
						<div class="form-group mt-sm">
							<label class="col-md-3 control-label">Ordering <span class="required">*</span></label>
							<div class="col-md-9">
								<input type="number" class="form-control" name="zone_ordering" id="zone_ordering" required title="Must Be Required" value="'.$rowsvalues['zone_ordering'].'" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Status <span class="required">*</span></label>
							<div class="col-md-9">
								<div class="radio-custom radio-inline">
									<input type="radio" id="zone_status" name="zone_status" value="1"'; if($rowsvalues['zone_status'] == 1) {echo'checked';} echo'>
									<label for="radioExample1">Active</label>
								</div>
								<div class="radio-custom radio-inline">
									<input type="radio" id="zone_status" name="zone_status" value="2"'; if($rowsvalues['zone_status'] == 2){echo'checked';} echo'>
									<label for="radioExample2">Inactive</label>
								</div>
							</div>
						</div>
					</div>
					<footer class="panel-footer">
						<div class="row">
							<div class="col-md-12 text-right">
								<button type="submit" class="btn btn-primary" id="changes_zone" name="changes_zone">Update</button>
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