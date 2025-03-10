<?php
include "../../dbsetting/lms_vars_config.php";
include "../../dbsetting/classdbconection.php";
$dblms = new dblms();
include "../../functions/login_func.php";
include "../../functions/functions.php";
checkCpanelLMSALogin();

if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('6', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '6', 'edit' => '1'))) {
	$sqllms	= $dblms->querylms("SELECT  sec.section_id, sec.section_name, sec.section_strength, sec.id_class, sec.section_status, sec.id_campus
								   		FROM ".CLASS_SECTIONS." sec  
										WHERE sec.section_id = '".cleanvars($_GET['id'])."' LIMIT 1");
	$rowsvalues = mysqli_fetch_array($sqllms);
	echo'
	<script src="assets/javascripts/user_config/forms_validation.js"></script>
	<script src="assets/javascripts/theme.init.js"></script>
	<section class="panel panel-featured panel-featured-primary">
		<form action="classsections.php" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
		<input type="hidden" name="section_id" id="section_id" value="'.cleanvars($_GET['id']).'">
			<header class="panel-heading">
				<h2 class="panel-title"><i class="glyphicon glyphicon-edit"></i> Edit Section</h2>
			</header>
			<div class="panel-body">
				<div class="form-group mt-sm">
					<label class="col-md-3 control-label">Section Name <span class="required">*</span></label>
					<div class="col-md-9">
						<input type="text" class="form-control" name="section_name" id="section_name" required title="Must Be Required" value="'.$rowsvalues['section_name'].'" />
					</div>
				</div>				
				<div class="form-group mt-sm">
					<label class="col-md-3 control-label">Section Strength</label>
					<div class="col-md-9">
						<input type="text" class="form-control" name="section_strength" id="section_strength" value="'.$rowsvalues['section_strength'].'" />
					</div>
				</div>';
				if(!empty($_SESSION['userlogininfo']['SUBCAMPUSES'])){
                    echo'
                    <div class="form-group mb-md">
                        <label class="col-md-3 control-label">Sub Campus</label>
						<div class="col-md-9">
							<select class="form-control" title="Must Be Required" data-plugin-selectTwo data-width="100%" id="id_campus" name="id_campus" title="Must Be Required" onchange="get_class(this.value)">
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
				<div class="form-group mt-sm">
					<label class="col-md-3 control-label">Class Name <span class="required">*</span></label>
					<div class="col-md-9">
							<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" id="id_class" name="id_class">
								<option value="">Select</option>';
								$sqlLevelClasses    = $dblms->querylms("SELECT  l.level_classes
                                                                        FROM ".CAMPUS." c
                                                                        LEFT JOIN ".CAMPUS_LEVELS." l ON l.level_id = c.id_level
                                                                        WHERE campus_id = '".cleanvars($rowsvalues['id_campus'])."' LIMIT 1");
                                $valLevelClasses    = mysqli_fetch_array($sqlLevelClasses);

                                $sqllmsclass	= $dblms->querylms("SELECT class_id, class_name
                                                                    FROM ".CLASSES."
                                                                    WHERE class_id != '' AND class_status = '1'
                                                                    AND class_id IN (".$valLevelClasses['level_classes'].")
                                                                    ORDER BY class_id ASC");
                                while($value_class 	= mysqli_fetch_array($sqllmsclass)) {
                                    echo'<option value="'.$value_class['class_id'].'" '.($value_class['class_id'] == $rowsvalues['id_class'] ? 'selected' : '').'>'.$value_class['class_name'].'</option>';
                                }
								echo '
							</select>
						</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Status <span class="required">*</span></label>
					<div class="col-md-9">';
						if($rowsvalues['section_status'] == 1) { 
							echo '
								<div class="radio-custom radio-inline">
									<input type="radio" id="section_status" name="section_status" value="1" checked>
									<label for="radioExample1">Active</label>
								</div>';
						} else { 
							echo '
								<div class="radio-custom radio-inline">
									<input type="radio" id="section_status" name="section_status" value="1">
									<label for="radioExample1">Active</label>
								</div>';
						}
						if($rowsvalues['section_status'] == 2) { 
							echo '
								<div class="radio-custom radio-inline">
									<input type="radio" id="section_status" name="section_status" checked value="2">
									<label for="radioExample2">Inactive</label>
								</div>';
						} else { 
							echo '
								<div class="radio-custom radio-inline">
									<input type="radio" id="section_status" name="section_status" value="2">
									<label for="radioExample2">Inactive</label>
								</div>';
						}
						echo '
					</div>
				</div>
			</div>
			<footer class="panel-footer">
				<div class="row">
					<div class="col-md-12 text-right">
						<button type="submit" class="btn btn-primary" id="changes_section" name="changes_section">Update</button>
						<button class="btn btn-default modal-dismiss">Cancel</button>
					</div>
				</div>
			</footer>
		</form>
	</section>';
}
?>