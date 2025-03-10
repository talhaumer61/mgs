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
	$sqllms	= $dblms->querylms("SELECT city_status, city_ordering, city_name, city_code, id_dist, id_prov 
								   FROM ".TEHSIL_CITIES."
								   WHERE city_id != '' AND is_deleted != '1'
								   AND city_id = '".cleanvars($_GET['id'])."'
								   ORDER BY city_id ASC LIMIT 1");
	$rowsvalues = mysqli_fetch_array($sqllms);
//---------------------------------------------------------
echo '
<script src="assets/javascripts/user_config/forms_validation.js"></script>
<script src="assets/javascripts/theme.init.js"></script>
<div class="row">
<div class="col-md-12">
<section class="panel panel-featured panel-featured-primary">
	<form action="city.php" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
	<input type="hidden" name="city_id" id="city_id" value="'.cleanvars($_GET['id']).'">
		<header class="panel-heading">
			<h2 class="panel-title"><i class="glyphicon glyphicon-edit"></i> Edit Area</h2>
		</header>
		<div class="panel-body">
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label">Name <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="text" class="form-control" name="city_name" id="city_name" required title="Must Be Required" value="'.$rowsvalues['city_name'].'" />
				</div>
			</div>
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label">Code <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="text" class="form-control" name="city_code" id="city_code" required title="Must Be Required" value="'.$rowsvalues['city_code'].'" />
				</div>
			</div>
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label">Ordering <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="number" class="form-control" name="city_ordering" id="city_ordering" required title="Must Be Required" value="'.$rowsvalues['city_ordering'].'" />
				</div>
			</div>
			<div class="form-group mb-md">
				<label class="col-md-3 control-label">Province <span class="required">*</span></label>
				<div class="col-md-9">
					<select class="form-control selectTwo" required title="Must Be Required" name="id_values">
						<option value="">Select</option>';
						$sqllmsdist	= $dblms->querylms("SELECT dist_id, dist_name, id_prov 
												FROM ".DISTRICTS."
												WHERE dist_id != '' AND dist_status = '1'
												AND is_deleted != '1'
												ORDER BY dist_ordering ASC");
						while($valuedist = mysqli_fetch_array($sqllmsdist)) {
							echo'<option value="'.$valuedist['dist_id'].'|'.$valuedist['id_prov'].'"'; if($valuedist['dist_id'] == $rowsvalues['id_dist']){echo'selected';} echo'>'.$valuedist['dist_name'].'</option>';
						}
						echo'
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Status <span class="required">*</span></label>
				<div class="col-md-9">
					<div class="radio-custom radio-inline">
						<input type="radio" id="city_status" name="city_status" value="1"'; if($rowsvalues['city_status'] == 1) {echo'checked';} echo'>
						<label for="radioExample1">Active</label>
					</div>
					<div class="radio-custom radio-inline">
						<input type="radio" id="city_status" name="city_status" value="2"'; if($rowsvalues['city_status'] == 2){echo'checked';} echo'>
						<label for="radioExample2">Inactive</label>
					</div>
				</div>
			</div>
		</div>
		<footer class="panel-footer">
			<div class="row">
				<div class="col-md-12 text-right">
					<button type="submit" class="btn btn-primary" id="changes_city" name="changes_city">Update</button>
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
<script type="text/javascript">
	jQuery(document).ready(function($) {
		$(".selectTwo").select2({
			dropdownParent: $("#show_modal"),
			minimumResultsForSearch: 0,
			width: "100%"
		});
	});
</script>