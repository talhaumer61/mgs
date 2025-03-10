<?php 
include "../../dbsetting/lms_vars_config.php";
include "../../dbsetting/classdbconection.php";
$dblms = new dblms();
include "../../functions/login_func.php";
include "../../functions/functions.php";
checkCpanelLMSALogin();

if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '20', 'edit' => '1'))){
	$sqllms	= $dblms->querylms("SELECT group_id, group_status, group_ordering, group_name, group_code, group_code_numeric, group_detail, group_logo
								FROM ".CAMPUS_GROUPS."
								WHERE group_id != '' AND is_deleted != '1'
								AND group_id = '".cleanvars($_GET['id'])."'
								LIMIT 1");
	$rowsvalues = mysqli_fetch_array($sqllms);
	echo'
	<script src="assets/javascripts/user_config/forms_validation.js"></script>
	<script src="assets/javascripts/theme.init.js"></script>
	<div class="row">
		<div class="col-md-12">
			<section class="panel panel-featured panel-featured-primary">
				<form action="campus_group.php" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
				<input type="hidden" name="group_id" id="group_id" value="'.cleanvars($_GET['id']).'">
					<header class="panel-heading">
						<h2 class="panel-title"><i class="glyphicon glyphicon-edit"></i> Edit Campus Level</h2>
					</header>
					<div class="panel-body">
						<div class="form-group mt-xl">
							<div class="row">
								<label class="col-md-3 control-label">Logo </label>
								<div class="col-md-9">
									<div class="fileinput fileinput-new" data-provides="fileinput">
										<div class="fileinput-new thumbnail" style="width: 130px; height: 130px;" data-trigger="fileinput">';
											if($rowsvalues['group_logo']) { 
											echo '
												<img src="uploads/images/campus_groups/'.$rowsvalues['group_logo'].'" class="rounded img-responsive">' ;
											} else {
												echo '<img src="uploads/logo.png" class="rounded img-responsive">';
											}
												echo'
										</div>
										<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 130px; max-height: 130px"></div>
										<div>
											<span class="btn btn-xs btn-default btn-file">
												<span class="fileinput-new">Select image</span>
												<span class="fileinput-exists">Change</span>
												<input type="file" name="group_logo" accept="image/*">
											</span>
											<a href="#" class="btn btn-xs btn-warning fileinput-exists" data-dismiss="fileinput">Remove</a>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="form-group mt-md">
							<label class="col-md-3 control-label">Ordering <span class="required">*</span></label>
							<div class="col-md-9">
								<input type="number" class="form-control" name="group_ordering" id="group_ordering" readonly required title="Must Be Required" value="'.$rowsvalues['group_ordering'].'" />
							</div>
						</div>
						<div class="form-group mt-md">
							<label class="col-md-3 control-label">Name <span class="required">*</span></label>
							<div class="col-md-9">
								<input type="text" class="form-control" name="group_name" id="group_name" required title="Must Be Required" value="'.$rowsvalues['group_name'].'" />
							</div>
						</div>
						<div class="form-group mt-md">
							<label class="col-md-3 control-label">Alpha Code <span class="required">*</span></label>
							<div class="col-md-9">
								<input type="text" class="form-control" name="group_code" id="group_code" required title="Must Be Required" value="'.$rowsvalues['group_code'].'" />
							</div>
						</div>
						<div class="form-group mt-md">
							<label class="col-md-3 control-label">Numeric Code <span class="required">*</span></label>
							<div class="col-md-9">
								<input type="text" class="form-control" name="group_code_numeric" id="group_code_numeric" required title="Must Be Required" value="'.$rowsvalues['group_code_numeric'].'" />
							</div>
						</div>
						<div class="form-group mt-md">
							<label class="col-md-3 control-label">Detail </label>
							<div class="col-md-9">
								<textarea class="form-control" name="group_detail" id="group_detail" required title="Must Be Required">'.$rowsvalues['group_detail'].'</textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Status <span class="required">*</span></label>
							<div class="col-md-9">
								<div class="radio-custom radio-inline">
									<input type="radio" id="group_status" name="group_status" value="1"'; if($rowsvalues['group_status'] == 1) {echo'checked';} echo'>
									<label for="radioExample1">Active</label>
								</div>
								<div class="radio-custom radio-inline">
									<input type="radio" id="group_status" name="group_status" value="2"'; if($rowsvalues['group_status'] == 2){echo'checked';} echo'>
									<label for="radioExample2">Inactive</label>
								</div>
							</div>
						</div>
					</div>
					<footer class="panel-footer">
						<div class="row">
							<div class="col-md-12 text-right">
								<button type="submit" class="btn btn-primary" id="changes_group" name="changes_group">Update</button>
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