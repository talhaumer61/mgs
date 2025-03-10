<?php 
//---------------------------------------------------------
	include "../../dbsetting/lms_vars_config.php";
	include "../../dbsetting/classdbconection.php";
	$dblms = new dblms();
	include "../../functions/login_func.php";
	include "../../functions/functions.php";
	checkCpanelLMSALogin();
if(($_SESSION['userlogininfo']['LOGINAFOR'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '8', 'updated' => '1'))){
//---------------------------------------------------------
	$sqllms	= $dblms->querylms("SELECT prov_status, prov_ordering, prov_name, prov_code  
								   FROM ".PROVINCES."
								   WHERE prov_id != '' AND is_deleted != '1'
								   AND prov_id = '".cleanvars($_GET['id'])."'
								   ORDER BY prov_id ASC LIMIT 1");
	$rowsvalues = mysqli_fetch_array($sqllms);
//---------------------------------------------------------
echo '
<script src="assets/javascripts/user_config/forms_validation.js"></script>
<script src="assets/javascripts/theme.init.js"></script>
<div class="row">
<div class="col-md-12">
<section class="panel panel-featured panel-featured-primary">
	<form action="province.php" class="form-horizontal" id="form" enctype="multipart/form-data" autocomplete="off" method="post" accept-charset="utf-8">
	<input type="hidden" name="prov_id" id="prov_id" value="'.cleanvars($_GET['id']).'">
		<header class="panel-heading">
			<h2 class="panel-title"><i class="glyphicon glyphicon-edit"></i> Edit Province</h2>
		</header>
		<div class="panel-body">
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label">Name <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="text" class="form-control" name="prov_name" id="prov_name" required title="Must Be Required" value="'.$rowsvalues['prov_name'].'" />
				</div>
			</div>
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label">Code <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="text" class="form-control" name="prov_code" id="prov_code" required title="Must Be Required" value="'.$rowsvalues['prov_code'].'" />
				</div>
			</div>
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label">Ordering <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="number" class="form-control" name="prov_ordering" id="prov_ordering" required title="Must Be Required" value="'.$rowsvalues['prov_ordering'].'" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Status <span class="required">*</span></label>
				<div class="col-md-9">
					<div class="radio-custom radio-inline">
						<input type="radio" id="prov_status" name="prov_status" value="1"'; if($rowsvalues['prov_status'] == 1) {echo'checked';} echo'>
						<label for="radioExample1">Active</label>
					</div>
					<div class="radio-custom radio-inline">
						<input type="radio" id="prov_status" name="prov_status" value="2"'; if($rowsvalues['prov_status'] == 2){echo'checked';} echo'>
						<label for="radioExample2">Inactive</label>
					</div>
				</div>
			</div>
		</div>
		<footer class="panel-footer">
			<div class="row">
				<div class="col-md-12 text-right">
					<button type="submit" class="btn btn-primary" id="changes_province" name="changes_province">Update</button>
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