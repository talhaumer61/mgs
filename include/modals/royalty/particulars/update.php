<?php
include "../../../dbsetting/lms_vars_config.php";
include "../../../dbsetting/classdbconection.php";
$dblms = new dblms();
include "../../../functions/login_func.php";
include "../../../functions/functions.php";
checkCpanelLMSALogin();

if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '69', 'edit' => '1'))){
	$sqllms	= $dblms->querylms("SELECT  part_id, part_status, part_name, part_for, part_type, part_amount_type, part_months, part_detail
								FROM ".ROYALTY_PARTICULARS." 
								WHERE is_deleted != '1' AND part_id = '".cleanvars($_GET['id'])."' LIMIT 1 ");
	$rowsvalues = mysqli_fetch_array($sqllms);
	echo'
	<script src="assets/javascripts/user_config/forms_validation.js"></script>
	<script src="assets/javascripts/theme.init.js"></script>
	<div class="row">
		<div class="col-md-12">
			<section class="panel panel-featured panel-featured-primary">
				<form action="royaltyParticulars.php" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
				<input type="hidden" name="part_id" id="part_id" value="'.cleanvars($_GET['id']).'">
					<header class="panel-heading">
						<h2 class="panel-title"><i class="glyphicon glyphicon-edit"></i> Edit Particular</h2>
					</header>
					<div class="panel-body">
						<div class="form-group mt-sm">
							<label class="col-md-2 control-label">Title <span class="required">*</span></label>
							<div class="col-md-10">
								<input type="text" class="form-control" name="part_name" id="part_name" required title="Must Be Required" value="'.$rowsvalues['part_name'].'" />
							</div>
						</div>
						<div class="form-group mt-sm">
							<label class="col-md-2 control-label"> Type <span class="required">*</span></label>
							<div class="col-md-10">
								<select data-plugin-selectTwo data-width="100%" name="part_type" id="part_type" required title="Must Be Required" class="form-control populate" onchange="get_royalty_type(this.value)">
									<option value="">Select</option>';
									foreach($rolyaltyType as $type){
										echo'<option  value="'.$type['id'].'"'; if($rowsvalues['part_type'] == $type['id']){ echo'selected'; } echo'>'.$type['name'].'</option>';
									}
									echo'
								</select>	
							</div>
						</div>
						<div id="getroyaltytype">';
							if($rowsvalues['part_type'] == 1) {
								echo'
								<div class="form-group mt-sm">
									<label class="col-md-2 control-label"> For <span class="required">*</span></label>
									<div class="col-md-10">
										<select data-plugin-selectTwo data-width="100%" name="part_for" id="part_for" required title="Must Be Required" class="form-control populate">
											<option value="">Select</option>';
											foreach($rolyaltyFor as $for){
												echo'<option  value="'.$for['id'].'"'; if($rowsvalues['part_for'] == $for['id']){ echo'selected'; } echo'>'.$for['name'].'</option>';
											}
											echo'
										</select>	
									</div>
								</div>';
							}
							echo'							
							<div id="get_amount_type">';
								if($rowsvalues['part_for'] == 1){
									echo'
									<div class="form-group mt-sm">
										<label class="col-md-2 control-label"> Amount <span class="required">*</span></label>
										<div class="col-md-10">
											<select data-plugin-selectTwo data-width="100%" name="part_amount_type" id="part_amount_type" required title="Must Be Required" class="form-control populate">
												<option value="">Select</option>';
												foreach($rolyaltyAmount as $amount){
													echo'<option  value="'.$amount['id'].'" '.($rowsvalues['part_amount_type'] == $amount['id'] ? 'selected' : '').'>'.$amount['name'].'</option>';
												}
												echo'
											</select>	
										</div>
									</div>';
								}
								echo'
							</div>
						</div>
						<div class="form-group mt-sm">
							<label class="col-md-2 control-label"> Months <span class="required">*</span></label>
							<div class="col-md-10">
								<select data-plugin-selectTwo data-width="100%" name="part_months[]" id="part_months" required title="Must Be Required" class="form-control populate" multiple>
									<option value="">Select</option>';
									foreach($monthtypes as $month){
										echo'<option  value="'.$month['id'].'" '.(strpos(','.$rowsvalues['part_months'].',', ','.$month['id'].',') !== false ? 'selected' : '').'>'.$month['name'].'</option>';
									}
									echo'
								</select>	
							</div>
						</div>
						<div class="form-group mt-sm">
							<label class="col-md-2 control-label">Details</label>
							<div class="col-md-10">
								<textarea class="form-control" rows="2" name="part_detail" id="part_detail">'.$rowsvalues['part_detail'].'</textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">Status <span class="required">*</span></label>
							<div class="col-md-10">
								<div class="radio-custom radio-inline">
									<input type="radio" id="part_status" name="part_status" value="1"'; if($rowsvalues['part_status'] == 1) { echo'checked';} echo' >
									<label for="radioExample1">Active</label>
								</div>
								<div class="radio-custom radio-inline">
									<input type="radio" id="part_status" name="part_status" value="2"'; if($rowsvalues['part_status'] == 2) { echo'checked';} echo' >
									<label for="radioExample2">Inactive</label>
								</div>
							</div>
						</div>
					</div>
					<footer class="panel-footer">
						<div class="row">
							<div class="col-md-12 text-right">
								<button type="submit" class="btn btn-primary" id="changes_particular" name="changes_particular">Update</button>
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