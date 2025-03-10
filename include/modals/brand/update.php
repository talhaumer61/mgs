<?php 
include "../../dbsetting/lms_vars_config.php";
include "../../dbsetting/classdbconection.php";
$dblms = new dblms();
include "../../functions/login_func.php";
include "../../functions/functions.php";
checkCpanelLMSALogin();

if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '20', 'edit' => '1'))){
	$sqllms	= $dblms->querylms("SELECT brand_status, brand_ordering, brand_name, brand_code, brand_code_numeric, brand_detail, brand_logo 
								FROM ".BRANDS."
								WHERE brand_id != '' AND is_deleted != '1'
								AND brand_id = '".cleanvars($_GET['id'])."'
								LIMIT 1");
	$rowsvalues = mysqli_fetch_array($sqllms);
	echo'
	<script src="assets/javascripts/user_config/forms_validation.js"></script>
	<script src="assets/javascripts/theme.init.js"></script>
	<div class="row">
		<div class="col-md-12">
			<section class="panel panel-featured panel-featured-primary">
				<form action="brand.php" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
				<input type="hidden" name="brand_id" id="brand_id" value="'.cleanvars($_GET['id']).'">
					<header class="panel-heading">
						<h2 class="panel-title"><i class="glyphicon glyphicon-edit"></i> Edit Brand</h2>
					</header>
					<div class="panel-body">
						<div class="form-group mt-xl">
							<div class="row">
								<label class="col-md-3 control-label">Logo </label>
								<div class="col-md-9">
									<div class="fileinput fileinput-new" data-provides="fileinput">
										<div class="fileinput-new thumbnail" style="width: 130px; height: 130px;" data-trigger="fileinput">';
											if($rowsvalues['brand_logo']){ 
												echo'
												<img src="uploads/images/brands/'.$rowsvalues['brand_logo'].'" class="rounded img-responsive">' ;
											}else{
												echo '<img src="uploads/logo.png" class="rounded img-responsive">';
											}
											echo'
										</div>
										<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 130px; max-height: 130px"></div>
										<div>
											<span class="btn btn-xs btn-default btn-file">
												<span class="fileinput-new">Select image</span>
												<span class="fileinput-exists">Change</span>
												<input type="file" name="brand_logo" accept="image/*">
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
								<input type="number" class="form-control" name="brand_ordering" id="brand_ordering" required readonly title="Must Be Required" value="'.$rowsvalues['brand_ordering'].'" />
							</div>
						</div>
						<div class="form-group mt-md">
							<label class="col-md-3 control-label">Name <span class="required">*</span></label>
							<div class="col-md-9">
								<input type="text" class="form-control" name="brand_name" id="brand_name" required title="Must Be Required" value="'.$rowsvalues['brand_name'].'" />
							</div>
						</div>
						<div class="form-group mt-md">
							<label class="col-md-3 control-label">Alpha Code <span class="required">*</span></label>
							<div class="col-md-9">
								<input type="text" class="form-control" name="brand_code" id="brand_code" required title="Must Be Required" value="'.$rowsvalues['brand_code'].'" />
							</div>
						</div>
						<div class="form-group mt-md">
							<label class="col-md-3 control-label">Numeric Code <span class="required">*</span></label>
							<div class="col-md-9">
								<input type="number" class="form-control" name="brand_code_numeric" id="brand_code_numeric" required title="Must Be Required" value="'.$rowsvalues['brand_code_numeric'].'" />
							</div>
						</div>
						<div class="form-group mt-md">
							<label class="col-md-3 control-label">Detail </label>
							<div class="col-md-9">
								<textarea class="form-control" name="brand_detail" id="brand_detail" required title="Must Be Required">'.$rowsvalues['brand_detail'].'</textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Status <span class="required">*</span></label>
							<div class="col-md-9">
								<div class="radio-custom radio-inline">
									<input type="radio" id="brand_status" name="brand_status" value="1"'; if($rowsvalues['brand_status'] == 1) {echo'checked';} echo'>
									<label for="radioExample1">Active</label>
								</div>
								<div class="radio-custom radio-inline">
									<input type="radio" id="brand_status" name="brand_status" value="2"'; if($rowsvalues['brand_status'] == 2){echo'checked';} echo'>
									<label for="radioExample2">Inactive</label>
								</div>
							</div>
						</div>
					</div>
					<footer class="panel-footer">
						<div class="row">
							<div class="col-md-12 text-right">
								<button type="submit" class="btn btn-primary" id="changes_brand" name="changes_brand">Update</button>
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