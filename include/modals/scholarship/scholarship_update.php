<?php 
include "../../dbsetting/lms_vars_config.php";
include "../../dbsetting/classdbconection.php";
$dblms = new dblms();
include "../../functions/login_func.php";
include "../../functions/functions.php";
checkCpanelLMSALogin();
	
if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('73', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '73', 'edit' => '1'))) {
	$sqllms	= $dblms->querylms("SELECT  *
								   FROM ".SCHOLARSHIP."
								   WHERE id = '".cleanvars($_GET['id'])."' LIMIT 1");
	$rowvalues = mysqli_fetch_array($sqllms);
	echo'
	<script src="assets/javascripts/user_config/forms_validation.js"></script>
	<script src="assets/javascripts/theme.init.js"></script>
	<section class="panel panel-featured panel-featured-primary">
		<form action="scholarship.php" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8" autocomplete="off">
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
				<div class="form-group">
					<label class="col-md-3 control-label">Category <span class="required">*</span></label>
					<div class="col-md-9">
						<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" name="id_cat">
							<option value="">Select</option>';
							$sqllms	= $dblms->querylms("SELECT cat_id, cat_type, cat_status, cat_name 
												FROM ".SCHOLARSHIP_CAT."
												WHERE cat_id   != ''
												AND cat_status	= '1'
												AND cat_type	= '1'
												AND id_campus	= '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
												ORDER BY cat_name ASC");
							while($valCat = mysqli_fetch_array($sqllms)) {
								echo '<option value="'.$valCat['cat_id'].'" '.($rowvalues['id_cat'] == $valCat['cat_id'] ? 'selected' : '').'>'.$valCat['cat_name'].'</option>';
							}
							echo'
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Class <span class="required">*</span></label>
					<div class="col-md-9">
						<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" id="id_class" name="id_class" onchange="get_class_student(this.value)">
							<option value="">Select</option>';
								$sqllmscls	= $dblms->querylms("SELECT class_id, class_status, class_name 
																FROM ".CLASSES."
																WHERE class_status = '1' 
																ORDER BY class_id ASC");
								while($valuecls = mysqli_fetch_array($sqllmscls)) {
									echo '<option value="'.$valuecls['class_id'].'" '.($rowvalues['id_class'] == $valuecls['class_id'] ? 'selected' : '').'>'.$valuecls['class_name'].'</option>';
								}
								echo'
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Student <span class="required">*</span></label>
					<div class="col-md-9">
						<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" id="id_std" name="id_std">
							<option value="">Select</option>';
							$sqlStudent = $dblms->querylms("SELECT std_id, std_name, id_class, id_section
																FROM ".STUDENTS."
																WHERE id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
																AND std_status  = '1'
																AND id_class    = '".cleanvars($rowvalues['id_class'])."'
																AND is_deleted  = '0'
																ORDER BY std_name ASC");
							while($valStudent = mysqli_fetch_array($sqlStudent)){
								echo '<option value="'.$valStudent['std_id'].'|'.$valStudent['id_class'].'|'.$valStudent['id_section'].'" '.($rowvalues['id_std'] == $valStudent['std_id'] ? 'selected' : '').'>'.$valStudent['std_name'].'</option>';
							}
							echo'
						</select>
					</div>
				</div>						
				<div class="form-group">
					<label class="col-md-3 control-label">Scholarship On <span class="required">*</span></label>
					<div class="col-md-9">
						<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" id="id_feecat" name="id_feecat" onchange="get_tuitionfee(this.value)">
							<option value="">Select</option>
							<option value="0" '.($rowvalues['id_feecat'] == '0' ? 'selected' : '').'>All</option>';
							$sqlFeeCat	= $dblms->querylms("SELECT cat_id, cat_name 
															FROM ".FEE_CATEGORY."
															WHERE cat_status	= '1'
															AND is_deleted		= '0' 
															ORDER BY cat_id ASC");
							while($valFeeCat = mysqli_fetch_array($sqlFeeCat)) {
								echo '<option value="'.$valFeeCat['cat_id'].'" '.($rowvalues['id_feecat'] == $valFeeCat['cat_id'] ? 'selected' : '').'>'.$valFeeCat['cat_name'].'</option>';
							}
							echo'
						</select>
					</div>
				</div>
				<div id="get_tuitionfee">';
					if($rowvalues['id_feecat'] == '0'){
						$sql = "";
					}else{
						$sql = "AND d.id_cat = '".$rowvalues['id_feecat']."'";
					}
					$sqllmsfeesetup	= $dblms->querylms("SELECT f.id, SUM(d.amount) as amount
															FROM ".FEESETUP." f
															INNER JOIN ".FEESETUPDETAIL." d ON d.id_setup = f.id	
															WHERE f.status      = '1'
															AND f.is_deleted    = '0'
															AND f.id_session    = '".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'
															AND f.id_campus     = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
															AND f.id_class      = '".cleanvars($rowvalues['id_class'])."'
															$sql LIMIT 1");
					if(mysqli_num_rows($sqllmsfeesetup) > 0){
						$valTuitionFee = mysqli_fetch_array($sqllmsfeesetup);
						echo'    
						<div class="form-group">
							<label class="col-md-3 control-label">Fee Amount </label>
							<div class="col-md-9">
								<input type="text" class="form-control" value="'.$valTuitionFee['amount'].'" title="Must Be Required" readonly/>
							</div>
						</div>';
					}
					echo'
				</div>						
				<div class="form-group">
					<label class="col-md-3 control-label">Concession Type <span class="required">*</span></label>
					<div class="col-md-9">
						<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" id="concession_type" name="concession_type" onchange="get_percent_amount(this.value)">
							<option value="">Select</option>';
							foreach ($rolyaltyAmount as $concession_type) {
								echo '<option value="'.$concession_type['id'].'" '.($rowvalues['concession_type'] == $concession_type['id'] ? 'selected' : '').'>'.$concession_type['name'].'</option>';	
							}
							echo '
						</select>
					</div>
				</div>
				<div class="form-group" id="percent_amount">';
					if($rowvalues['concession_type'] == '1'){
						echo'
						<label class="col-md-3 control-label">Scholarship (Rs.) <span class="required">*</span></label>
						<div class="col-md-9">
							<input type="text" class="form-control" name="amount" id="amount" value="'.$rowvalues['amount'].'" required title="Must Be Required"/>
						</div>';
					}elseif($rowvalues['concession_type'] == '2'){
						echo'
						<label class="col-md-3 control-label">Scholarship (%) <span class="required">*</span></label>
						<div class="col-md-9">
							<input type="text" class="form-control" placeholder="0-100" name="percent" id="percent" value="'.$rowvalues['percent'].'" required title="Must Be Required" min="0" max="100"/>
						</div>';
					}
					echo'
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Date <span class="required" aria-required="true">*</span></label>
					<div class="col-md-9">
						<div class="input-daterange input-group" data-plugin-datepicker="" data-plugin-options="{&quot;format&quot;: &quot;dd-mm-yyyy&quot;}">
							<span class="input-group-addon">
								<i class="fa fa-calendar"></i>
							</span>
							<input type="text" class="form-control" required title="Must Be Required" name="start_date" value="'.date('d-m-Y' , strtotime($rowvalues['start_date'])).'">
							<span class="input-group-addon">to</span>
							<input type="text" class="form-control" required title="Must Be Required" name="end_date" value="'.date('d-m-Y' , strtotime($rowvalues['end_date'])).'">
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Note </label>
					<div class="col-md-9">
						<textarea class="form-control" rows="2" name="note" id="note">'.$rowvalues['note'].'</textarea>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Status <span class="required">*</span></label>
					<div class="col-md-9">
						<div class="radio-custom radio-inline">
							<input type="radio" id="status" name="status" value="1" '.($rowvalues['status'] == '1' ? 'checked' : '').'>
							<label for="radioExample1">Active</label>
						</div>
						<div class="radio-custom radio-inline">
							<input type="radio" id="status" name="status" value="2" '.($rowvalues['status'] == '2' ? 'checked' : '').'>
							<label for="radioExample2">Inactive</label>
						</div>
					</div>
				</div>
			</div>
			<footer class="panel-footer">
				<div class="row">
					<div class="col-md-12 text-right">
						<button type="submit" class="btn btn-primary" id="changes_scholarship" name="changes_scholarship">Update</button>
						<button class="btn btn-default modal-dismiss">Cancel</button>
					</div>
				</div>
			</footer>
		</form>
	</section>';
}
?>