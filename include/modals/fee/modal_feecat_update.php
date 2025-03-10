<?php
include "../../dbsetting/lms_vars_config.php";
include "../../dbsetting/classdbconection.php";
$dblms = new dblms();
include "../../functions/login_func.php";
include "../../functions/functions.php";
checkCpanelLMSALogin();

if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || ($_SESSION['userlogininfo']['LOGINTYPE']  == 2) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '69', 'edit' => '1'))){ 
	$sqllms	= $dblms->querylms("SELECT c.cat_id, c.cat_status, c.cat_name, c.cat_detail , c.cat_ordering, c.cat_for 
								   		FROM ".FEE_CATEGORY." c  
										WHERE c.id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."' 
										AND c.cat_id = '".cleanvars($_GET['id'])."' LIMIT 1");
	$rowsvalues = mysqli_fetch_array($sqllms);
	echo'
	<script src="assets/javascripts/user_config/forms_validation.js"></script>
	<script src="assets/javascripts/theme.init.js"></script>
	<section class="panel panel-featured panel-featured-primary">
		<form action="fee-category.php" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
		<input type="hidden" name="cat_id" id="id" value="'.cleanvars($_GET['id']).'">
			<header class="panel-heading">
				<h2 class="panel-title"><i class="glyphicon glyphicon-edit"></i> Edit Fee Category</h2>
			</header>
			<div class="panel-body">			
				<div class="form-group">
					<label class="col-md-3 control-label">Priority <span class="required">*</span></label>
					<div class="col-md-9">
						<input type="text" class="form-control" name="cat_ordering" id="cat_ordering" required title="Must Be Required" value="'.$rowsvalues['cat_ordering'].'" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Catgory Name <span class="required">*</span></label>
					<div class="col-md-9">
						<input type="text" class="form-control" name="cat_name" id="cat_name" required title="Must Be Required" value="'.$rowsvalues['cat_name'].'" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Category Detail <span class="required">*</span></label>
					<div class="col-md-9">
						<textarea type="text" class="form-control" rows="7" name="cat_detail" id="cat_detail" required title="Must Be Required">'.$rowsvalues['cat_detail'].'</textarea>
					</div>
				</div>				
				<div class="form-group">
					<label class="col-sm-3 control-label">Applied To <span class="required">*</span></label>
					<div class="col-md-9">
						<div class="radio-custom radio-inline">
							<input type="radio" id="cat_for" name="cat_for" value="1" '.($rowsvalues['cat_for'] == 1 ? 'checked' : '').'>
							<label for="radioExample1">All</label>
						</div>
						<div class="radio-custom radio-inline">
							<input type="radio" id="cat_for" name="cat_for" value="2" '.($rowsvalues['cat_for'] == 2 ? 'checked' : '').'>
							<label for="radioExample2">Hostel</label>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Applied To <span class="required">*</span></label>
					<div class="col-md-9">
						<div class="radio-custom radio-inline">
							<input type="radio" id="cat_status" name="cat_status" value="1" '.($rowsvalues['cat_status'] == 1 ? 'checked' : '').'>
							<label for="radioExample1">Active</label>
						</div>
						<div class="radio-custom radio-inline">
							<input type="radio" id="cat_status" name="cat_status" value="2" '.($rowsvalues['cat_status'] == 2 ? 'checked' : '').'>
							<label for="radioExample2">Inactive</label>
						</div>
					</div>
				</div>
			</div>
			<footer class="panel-footer">
				<div class="row">
					<div class="col-md-12 text-right">
						<button type="submit" class="btn btn-primary" id="changes_feecat" name="changes_feecat">Update</button>
						<button class="btn btn-default modal-dismiss">Cancel</button>
					</div>
				</div>
			</footer>
		</form>
	</section>';
}
?>