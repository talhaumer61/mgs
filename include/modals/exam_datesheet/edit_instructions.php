<?php 
include "../../dbsetting/lms_vars_config.php";
include "../../dbsetting/classdbconection.php";
$dblms = new dblms();
include "../../functions/login_func.php";
include "../../functions/functions.php";
checkCpanelLMSALogin();
if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || ($_SESSION['userlogininfo']['LOGINTYPE']  == 2) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '82', 'updated' => '1'))){
	$sqllms	= $dblms->querylms("SELECT *
								FROM ".EXAM_INSTRUCTIONS."  
								WHERE id = '".cleanvars($_GET['id'])."' LIMIT 1");
	$rowsvalues = mysqli_fetch_array($sqllms);
	echo'
	<script src="assets/javascripts/user_config/forms_validation.js"></script>
	<script src="assets/javascripts/theme.init.js"></script>
	<div class="row">
		<div class="col-md-12">
			<section class="panel panel-featured panel-featured-primary">
				<form action="exam_datesheet.php" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
				<input type="hidden" name="id" id="id" value="'.cleanvars($_GET['id']).'">
					<header class="panel-heading">
						<h2 class="panel-title"><i class="glyphicon glyphicon-edit"></i> Edit Instructions</h2>
					</header>
					<div class="panel-body">
						<div class="form-group">
							<div class="col-md-6">
								<label class="control-label">Exam Type <span class="required">*</span></label>
								<select class="form-control" data-plugin-selectTwo data-width="100%" id="id_examtype" name="id_examtype" required title="Must Be Required">
									<option value="">Select</option>';
									$sqllmsexam	= $dblms->querylms("SELECT DISTINCT t.type_id, t.type_status, t.type_name 
																	FROM ".EXAM_TYPES." t												 
																	WHERE t.is_deleted	= '0'
																	AND t.type_status	= '1'
																	AND t.id_campus 	= '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'
																");
									while($valueexam = mysqli_fetch_array($sqllmsexam)) {
										echo '<option value="'.$valueexam['type_id'].'" '.($valueexam['type_id']==$rowsvalues['id_examtype'] ? 'selected' : '').'>'.$valueexam['type_name'].'</option>';
									}
									echo'
								</select>
							</div>
							<div class="col-md-6">
								<label class="control-label">Class <span class="required">*</span></label>
								<select class="form-control" data-plugin-selectTwo data-width="100%" name="id_class" id="id_class" required title="Must Be Required" >
									<option value="">Select</option>';
									$sqllmscls = $dblms->querylms("SELECT class_id, class_status, class_name 
																	FROM ".CLASSES."
																	WHERE class_status	= '1'
																	AND is_deleted		= '0'
																	ORDER BY class_id ASC");
									while($valuecls = mysqli_fetch_array($sqllmscls)) {
										echo '<option value="'.$valuecls['class_id'].'" '.($valuecls['class_id']==$rowsvalues['id_class'] ? 'selected' : '').'>'.$valuecls['class_name'].'</option>';
									}
									echo'
								</select>
							</div>
							<div class="col-md-12">
								<label class="control-label">Instructions <span class="required">*</span></label>
								<textarea data-plugin-summernote class="summernote summernoteEx" rows="5" name="instructions" id="instructions">'.html_entity_decode($rowsvalues['instructions']).'</textarea>
							</div>
							<div class="col-md-12">
								<label class="col-sm-3 control-label">Status <span class="required">*</span></label>
								<div class="col-md-9">
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
					</div>
					<footer class="panel-footer">
						<div class="row">
							<div class="col-md-12 text-right">
								<button type="submit" class="btn btn-primary" id="update_inst" name="update_inst">Update</button>
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