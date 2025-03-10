<?php
include "../../dbsetting/lms_vars_config.php";
include "../../dbsetting/classdbconection.php";
$dblms = new dblms();
include "../../functions/login_func.php";
include "../../functions/functions.php";
checkCpanelLMSALogin();

if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('5', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '5', 'edit' => '1'))) {
	$sqllms	= $dblms->querylms("SELECT b.*
									FROM ".SUBJECT_BOOKS." b  
									WHERE b.id = '".cleanvars($_GET['id'])."' LIMIT 1");
	$rowsvalues = mysqli_fetch_array($sqllms);
	echo'
	<script src="assets/javascripts/user_config/forms_validation.js"></script>
	<script src="assets/javascripts/theme.init.js"></script>
	<div class="row">
		<div class="col-md-12">
			<section class="panel panel-featured panel-featured-primary">
				<form action="classsubjects.php" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
					<input type="hidden" name="id" id="id" value="'.cleanvars($_GET['id']).'">
					<header class="panel-heading">
						<h2 class="panel-title"><i class="glyphicon glyphicon-edit"></i> Edit Subject Book</h2>
					</header>
					<div class="panel-body">
						<div class="form-group mb-md">
							<label class="col-md-4 control-label">Class Name <span class="required">*</span></label>
							<div class="col-md-8">
								<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" id="id_class" name="id_class" required onchange="get_subject(this.value)" title="Must Be Required">
									<option value="">Select</option>';
									$sqllmscls	= $dblms->querylms("SELECT class_id, class_name 
																	FROM ".CLASSES." 
																	WHERE class_status = '1'
																	ORDER BY class_id ASC");
									while($valuecls = mysqli_fetch_array($sqllmscls)) {
										echo'<option value="'.$valuecls['class_id'].'" '.($valuecls['class_id'] == $rowsvalues['id_class'] ? 'selected' : '').'>'.$valuecls['class_name'].'</option>';
									}
									echo'
								</select>
							</div>
						</div>
						<div class="form-group mb-md">
							<label class="col-md-4 control-label">Subject <span class="required">*</span></label>
							<div class="col-md-8">
								<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" id="id_subject" name="id_subject" required title="Must Be Required">
									<option value="">Select</option>';
									$sqlSubject	= $dblms->querylms("SELECT subject_id, subject_code, subject_name 
																	FROM ".CLASS_SUBJECTS."
																	WHERE id_class		= '".cleanvars($rowsvalues['id_class'])."'
																	AND subject_status	= '1'
																	AND is_deleted		= '0'
																	ORDER BY subject_name ASC");
									while($valSubject = mysqli_fetch_array($sqlSubject)) {
										echo '<option value="'.$valSubject['subject_id'].'" '.($valSubject['subject_id'] == $rowsvalues['id_subject'] ? 'selected' : '').'>'.$valSubject['subject_name'].' - '.$valSubject['subject_code'].'</option>';
									}
									echo'
								</select>
							</div>
						</div>
						<!--
						<div class="form-group mb-md">
							<label class="col-md-4 control-label">Subject Total Marks <span class="required">*</span></label>
							<div class="col-md-8">
								<input type="text" class="form-control" name = "subject_totalmarks" id="subject_totalmarks" required title="Must Be Required">
							</div>
						</div>
						<div class="form-group mb-md">
							<label class="col-md-4 control-label">Subject Pass Marks <span class="required">*</span></label>
							<div class="col-md-8">
								<input type="text" class="form-control" name = "subject_passmarks" id="subject_passmarks" required title="Must Be Required">
							</div>
						</div>
						-->
						<div class="form-group mb-md">
							<label class="col-md-4 control-label">Subject Type <span class="required">*</span></label>
							<div class="col-md-8">
								<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" id="type" name="type" required title="Must Be Required">
									<option value="">Select</option>';
									foreach ($subjecttype as $type) {
										echo'<option value="'.$type['id'].'" '.($type['id'] == $rowsvalues['type'] ? 'selected' : '').'>'.$type['name'].'</option>';
									}
									echo'
								</select>
							</div>
						</div>
						<div class="form-group mb-md">
							<label class="col-md-4 control-label">Book Name <span class="required">*</span></label>
							<div class="col-md-8">
								<input type="text" class="form-control" name="name" id="name" value="'.$rowsvalues['name'].'" required title="Must Be Required">
							</div>
						</div>
						<div class="form-group mb-md">
							<label class="col-md-4 control-label">Book Edition <span class="required">*</span></label>
							<div class="col-md-8">
								<input type="text" class="form-control" name="edition" id="edition" value="'.$rowsvalues['edition'].'" required title="Must Be Required">
							</div>
						</div>
						<div class="form-group mb-md">
							<label class="col-md-4 control-label">Book Publisher <span class="required">*</span></label>
							<div class="col-md-8">
								<input type="text" class="form-control" name="publisher" id="publisher" value="'.$rowsvalues['publisher'].'" required title="Must Be Required">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label">Status <span class="required">*</span></label>
							<div class="col-md-8">
								<div class="radio-custom radio-inline">
									<input type="radio" id="status" name="status" value="1" '.($rowsvalues['status'] == '1' ? 'checked' : '').'>
									<label for="radioExample1">Active</label>
								</div>
								<div class="radio-custom radio-inline">
									<input type="radio" id="status" name="status" value="2" '.($rowsvalues['status'] == '2' ? 'checked' : '').'>
									<label for="radioExample2">Inactive</label>
								</div>
							</div>
						</div>
					</div>
					<footer class="panel-footer">
						<div class="row">
							<div class="col-md-12 text-right">
								<button type="submit" class="btn btn-primary" id="changes_book" name="changes_book">Update</button>
								<button class="btn btn-default modal-dismiss">Cancel</button>
							</div>
						</div>
					</footer>
				</form>
			</section>
		</div>
	</div>';
}else{
	header("Location: dashboard.php");
}
?>