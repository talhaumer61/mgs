<?php 
include "../../dbsetting/lms_vars_config.php";
include "../../dbsetting/classdbconection.php";
$dblms = new dblms();
include "../../functions/login_func.php";
include "../../functions/functions.php";
checkCpanelLMSALogin();

if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('77', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '77', 'edit' => '1'))) {
	$sqllms	= $dblms->querylms("SELECT *
								   FROM ".SCHOLARSHIP."
								   WHERE id = '".cleanvars($_GET['id'])."' LIMIT 1");
	$rowvalues = mysqli_fetch_array($sqllms);
	echo'
	<script src="assets/javascripts/user_config/forms_validation.js"></script>
	<script src="assets/javascripts/theme.init.js"></script>
	<div class="row">
		<div class="col-md-12">
			<section class="panel panel-featured panel-featured-primary">
				<form action="fine.php" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
				<input type="hidden" name="id" id="id" value="'.cleanvars($_GET['id']).'">
					<header class="panel-heading">
						<h2 class="panel-title"><i class="glyphicon glyphicon-edit"></i> Edit Scholarship</h2>
					</header>
					<div class="panel-body">';
						if(!empty($_SESSION['userlogininfo']['SUBCAMPUSES'])){
							echo'
							<div class="form-group mb-md">
								<label class="col-md-3 control-label">Sub Campus</label>
								<div class="col-md-9">
									<select class="form-control" title="Must Be Required" data-plugin-selectTwo data-width="100%" id="id_campus" name="id_campus">
										<option value="">Select</option>';
										$sqlSubCampus	= $dblms->querylms("SELECT campus_id, campus_name 
																			FROM ".CAMPUS." 
																			WHERE campus_id IN (".$_SESSION['userlogininfo']['SUBCAMPUSES'].")
																			AND campus_status	= '1'
																			AND is_deleted		= '0'
																			ORDER BY campus_id ASC");
										while($valSubCampus = mysqli_fetch_array($sqlSubCampus)) {
											echo '<option value="'.$valSubCampus['campus_id'].'" '.($valSubCampus['campus_id'] == $rowsvalues['id_campus'] ? 'selected' : '').'>'.$valSubCampus['campus_name'].'</option>';
										}
										echo'
									</select>
								</div>
							</div>';
						}
						echo'
						<div class="form-group mb-md">
							<label class="col-md-3 control-label">Category <span class="required">*</span></label>
							<div class="col-md-9">
								<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="id_cat">
									<option value="">Select</option>';
										$sqllms	= $dblms->querylms("SELECT cat_id, cat_status, cat_type, cat_name 
																	FROM ".SCHOLARSHIP_CAT."
																	WHERE cat_id != '' 
																	AND cat_status = '1' 
																	AND cat_type = '3'
																	AND is_deleted = '0'
																	ORDER BY cat_name ASC");
										while($valFineCat = mysqli_fetch_array($sqllms)) {
											echo '<option value="'.$valFineCat['cat_id'].'" '.($valFineCat['cat_id']==$rowvalues['id_cat'] ? 'selected' : '').'>'.$valFineCat['cat_name'].'</option>';
										}
									echo '
								</select>
							</div>
						</div>
						<div class="form-group mb-md">
							<label class="col-md-3 control-label">Class <span class="required">*</span></label>
							<div class="col-md-9">
								<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" id="id_class" name="id_class" onchange="get_class_student(this.value)">
									<option value="">Select</option>';
										$sqllmscls	= $dblms->querylms("SELECT class_id, class_status, class_name 
																		FROM ".CLASSES."
																		WHERE class_status = '1' 
																		ORDER BY class_id ASC");
										while($valuecls = mysqli_fetch_array($sqllmscls)) {
											echo '<option value="'.$valuecls['class_id'].'" '.($valuecls['class_id']==$rowvalues['id_class'] ? 'selected' : '').'>'.$valuecls['class_name'].'</option>';
										}
										echo'
								</select>
							</div>
						</div>
						<div class="form-group mb-md">
							<label class="col-md-3 control-label">Student <span class="required">*</span></label>
							<div class="col-md-9">
								<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" id="id_std" name="id_std">
									<option value="">Select</option>';
									$sqllms	= $dblms->querylms("SELECT std_id, std_status, std_name, std_fathername, id_campus
																FROM ".STUDENTS."
																WHERE std_id   != ''
																AND std_status 	= '1'
																AND id_campus	= '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
																AND id_class	= '".cleanvars($rowvalues['id_class'])."'
																ORDER BY std_name ASC");
									while($valStudent = mysqli_fetch_array($sqllms)) {
										echo '<option value="'.$valStudent['std_id'].'|'.$valStudent['id_class'].'|'.$valStudent['id_section'].'" '.($valStudent['std_id'] == $rowvalues['id_std'] ? 'selected' : '').'>'.$valStudent['std_name'].'</option>';
									}
									echo'
								</select>
							</div>
						</div>
						<!--
						<div class="form-group mb-md">
							<label class="col-md-3 control-label">Fine On <span class="required">*</span></label>
							<div class="col-md-9">
								<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" id="id_feecat" name="id_feecat">
									<option value="">Select</option>
									<option value="0" '.($rowvalues['id_feecat']=='0' ? 'selected' : '').'>All</option>';
									$sqlFeeCat	= $dblms->querylms("SELECT cat_id, cat_name 
																	FROM ".FEE_CATEGORY."
																	WHERE cat_status	= '1'
																	AND is_deleted		= '0' 
																	ORDER BY cat_id ASC");
									while($valFeeCat = mysqli_fetch_array($sqlFeeCat)) {
										echo '<option value="'.$valFeeCat['cat_id'].'" '.($valFeeCat['cat_id'] == $rowvalues['id_feecat'] ? 'selected' : '').'>'.$valFeeCat['cat_name'].'</option>';
									}
									echo'
								</select>
							</div>
						</div>
						-->
						<div class="form-group mt-sm">
							<label class="col-md-3 control-label">Amount <span class="required">*</span></label>
							<div class="col-md-9">
								<input type="text" class="form-control" name="amount" id="amount" value="'.$rowvalues['amount'].'" required title="Must Be Required"/>
							</div>
						</div>
						<div class="form-group mb-md">
							<label class="col-md-3 control-label">Month <span class="required">*</span></label>
							<div class="col-md-9">
								<input type="month" class="form-control" id="yearmonth" name="yearmonth" value="'.date('Y-m' , strtotime($rowvalues['yearmonth'])).'" required title="Must Be Required" />
							</div>
						</div>
						<div class="form-group mb-md">
							<label class="col-md-3 control-label">Date <span class="required">*</span></label>
							<div class="col-md-9">
								<input class="form-control" name="date" id="date" value="'.date('m/d/Y' , strtotime($rowvalues['date'])).'" data-plugin-datepicker>
							</div>
						</div>
						<div class="form-group mb-md">
							<label class="col-md-3 control-label">Note </label>
							<div class="col-md-9">
								<textarea class="form-control" rows="2" name="note" id="note">'.$rowvalues['note'].'</textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Status <span class="required">*</span></label>
							<div class="col-md-9">
								<div class="radio-custom radio-inline">
									<input type="radio" id="status" name="status" value="1" '.($rowvalues['status'] == 1 ? 'checked' : '').'>
									<label for="radioExample1">Active</label>
								</div>
								<div class="radio-custom radio-inline">
									<input type="radio" id="status" name="status" value="2" '.($rowvalues['status'] == 2 ? 'checked' : '').'>
									<label for="radioExample2">Inactive</label>
								</div>
							</div>
						</div>
					</div>
					<footer class="panel-footer">
						<div class="row">
							<div class="col-md-12 text-right">
								<button type="submit" class="btn btn-primary" id="changes_fine" name="changes_fine">Update</button>
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