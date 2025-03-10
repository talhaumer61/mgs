<?php 
include "../../dbsetting/lms_vars_config.php";
include "../../dbsetting/classdbconection.php";
$dblms = new dblms();
include "../../functions/login_func.php";
include "../../functions/functions.php";
checkCpanelLMSALogin();

if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '83', 'edit' => '1'))){
	$sqllms	= $dblms->querylms("SELECT question_id, question_status, question_ordering, question_name, id_cat
								FROM ".FACILITY_QESTIONS."
								WHERE question_id != '' AND is_deleted != '1'
								AND question_id = '".cleanvars($_GET['id'])."'
								LIMIT 1");
	$rowsvalues = mysqli_fetch_array($sqllms);
	echo'
	<script src="assets/javascripts/user_config/forms_validation.js"></script>
	<script src="assets/javascripts/theme.init.js"></script>
	<div class="row">
		<div class="col-md-12">
			<section class="panel panel-featured panel-featured-primary">
				<form action="facility_question.php" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
				<input type="hidden" name="question_id" id="question_id" value="'.cleanvars($_GET['id']).'">
					<header class="panel-heading">
						<h2 class="panel-title"><i class="glyphicon glyphicon-edit"></i> Edit Inspection Statement</h2>
					</header>
					<div class="panel-body">
						<div class="form-group mt-sm">
							<label class="col-md-3 control-label">Name <span class="required">*</span></label>
							<div class="col-md-9">
								<input type="text" class="form-control" name="question_name" id="question_name" required title="Must Be Required" value="'.$rowsvalues['question_name'].'" />
							</div>
						</div>
						<div class="form-group mt-sm">
							<label class="col-md-3 control-label">Ordering <span class="required">*</span></label>
							<div class="col-md-9">
								<input type="number" class="form-control" name="question_ordering" id="question_ordering" required title="Must Be Required" value="'.$rowsvalues['question_ordering'].'" />
							</div>
						</div>
						<div class="form-group mb-md">
							<label class="col-md-3 control-label">Facility Category <span class="required">*</span></label>
							<div class="col-md-9">
								<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="id_cat">
									<option value="">Select</option>';
										$sqllmszone	= $dblms->querylms("SELECT  cat_id, cat_name
															FROM ".FACILITY_CATS."
															WHERE cat_id != '' AND cat_status = '1'
															AND is_deleted != '1'
															ORDER BY cat_ordering ASC");
										while($valuezone = mysqli_fetch_array($sqllmszone)) {
									echo '<option value="'.$valuezone['cat_id'].'"'; if($valuezone['cat_id'] == $rowsvalues['id_cat']){echo'selected';} echo'>'.$valuezone['cat_name'].'</option>';
									}
								echo '
								</select>
							</div>
						</div>	
						<div class="form-group">
							<label class="col-sm-3 control-label">Status <span class="required">*</span></label>
							<div class="col-md-9">
								<div class="radio-custom radio-inline">
									<input type="radio" id="question_status" name="question_status" value="1"'; if($rowsvalues['question_status'] == 1) {echo'checked';} echo'>
									<label for="radioExample1">Active</label>
								</div>
								<div class="radio-custom radio-inline">
									<input type="radio" id="question_status" name="question_status" value="2"'; if($rowsvalues['question_status'] == 2){echo'checked';} echo'>
									<label for="radioExample2">Inactive</label>
								</div>
							</div>
						</div>
					</div>
					<footer class="panel-footer">
						<div class="row">
							<div class="col-md-12 text-right">
								<button type="submit" class="btn btn-primary" id="changes_question" name="changes_question">Update</button>
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