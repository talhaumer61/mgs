<?php
if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('12', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '12', 'add' => '1'))) {
 	$id_campus	= (!empty($_POST['id_campus']) ? $_POST['id_campus'] : $_SESSION['userlogininfo']['LOGINCAMPUS']);
	$id_exam	= (!empty($_POST['id_exam']) ? $_POST['id_exam'] : '');
	$id_class	= (!empty($_POST['id_class']) ? $_POST['id_class'] : '');
	$id_section	= (!empty($_POST['id_section']) ? $_POST['id_section'] : '');
	$id_subject	= (!empty($_POST['id_subject']) ? $_POST['id_subject'] : '');

	// EXAM TYPES
	$condition = array(
						 'select'		=>  't.type_id, t.type_status, t.type_name'
						,'join'			=>	'INNER JOIN '.DATESHEET.' d on d.id_exam = t.type_id AND d.id_session = '.cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION']).''
						,'where'        =>  array(
													 't.type_status'	=>	1
													,'t.is_deleted'  	=>	0
												)
						,'search_by'	=>	' AND (t.id_campus = '.$_SESSION['userlogininfo']['LOGINCAMPUS'].' OR t.id_campus = '.$_SESSION['userlogininfo']['PARENTCAMPUS'].')'
						,'group_by'		=>	'd.id_exam'
						,'return_type'  =>  'all'
	);
	$EXAM_TYPES = $dblms->getRows(EXAM_TYPES.' t', $condition);
	// EXAM CLASSES
	$condition = array(
						 'select'       =>  'GROUP_CONCAT(l.level_classes) campus_classes'
						,'join'			=>	'LEFT JOIN '.CAMPUS_LEVELS.' l ON l.level_id = c.id_level'
						,'where'        =>  array(
													 'c.is_deleted'	=> 0
                                                    ,'c.campus_id'  =>  cleanvars($id_campus)
												)
						,'return_type'  =>  'single'
	);
	$CAMPUS_CLASSES = $dblms->getRows(CAMPUS.' c', $condition);

	$condition = array(
						 'select'       =>  'c.class_id, c.class_status, c.class_name'
						,'join'			=>	'INNER JOIN '.DATESHEET.' d on d.id_class = c.class_id'
						,'where'        =>  array(
													 'c.class_status'	=> 1
													,'c.is_deleted'  	=> 0
													,'d.id_session'		=> cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
                                                    ,'d.id_exam'        => cleanvars($id_exam)
												)
						,'search_by'	=>	'AND (d.id_campus = '.$id_campus.' OR d.id_campus = '.$_SESSION['userlogininfo']['LOGINCAMPUS'].' OR d.id_campus = '.$_SESSION['userlogininfo']['PARENTCAMPUS'].')
                                             AND c.class_id IN ('.$CAMPUS_CLASSES['campus_classes'].')'
						,'return_type'  =>  'all'
	);
	$CLASSES = $dblms->getRows(CLASSES.' c', $condition);
	// EXAM SUBJECTS
	$condition = array(
						 'select'       =>  'sb.subject_id, sb.subject_code, sb.subject_name, dd.dated'
						,'join'			=>	'INNER JOIN '.DATESHEET_DETAIL.' dd on dd.id_subject = sb.subject_id 
											 INNER JOIN '.DATESHEET.' d on d.id = dd.id_setup'
						,'where'        =>  array(
													 'sb.subject_status'	=> 1
													,'sb.is_deleted'  		=> 0
													,'d.id_session'			=> cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
													,'d.id_exam'  			=> cleanvars($id_exam)
													,'d.id_class'  			=> cleanvars($id_class)
												)
						,'search_by'	=>	' AND (d.id_campus = '.$id_campus.' OR d.id_campus = '. $_SESSION['userlogininfo']['LOGINCAMPUS'].' OR d.id_campus = '.$_SESSION['userlogininfo']['PARENTCAMPUS'].')'
						,'return_type'  =>  'all'
	);
	$CLASS_SUBJECTS = $dblms->getRows(CLASS_SUBJECTS.' sb', $condition);
	// EXAM SECTIONS
	$condition = array(
						 'select'       =>  'section_id, section_name'
						,'where'        =>  array(
													 'section_status'  		=> 1
													,'is_deleted'			=> 0
													,'id_class'				=> cleanvars($id_class)
													,'id_campus'  			=> cleanvars($id_campus)
												)
						,'return_type'  =>  'all'
	);
	$CLASS_SECTIONS = $dblms->getRows(CLASS_SECTIONS, $condition);
	echo'
	<section class="panel panel-featured panel-featured-primary">
		<form action="exam_award_list_print.php" target="_blank" class="mb-lg validate" enctype="multipart/form-data" method="post" accept-charset="utf-8">
		<div class="panel-heading">
			<h4 class="panel-title"><i class="fa fa-filter"></i> Select</h4>
		</div>
		<div class="panel-body">
			<div class="row">';
				if(!empty($_SESSION['userlogininfo']['SUBCAMPUSES'])){
					echo'
					<div class="col-md-6 mb-xs">
						<label class="control-label">Sub Campus</label>
						<select class="form-control" title="Must Be Required" data-plugin-selectTwo data-width="100%" id="id_campus" name="id_campus" title="Must Be Required">
							<option value="">Select</option>';
							$sqlSubCampus	= $dblms->querylms("SELECT campus_id, campus_name 
															FROM ".CAMPUS." 
															WHERE campus_id IN (".$_SESSION['userlogininfo']['SUBCAMPUSES'].")
															AND campus_status	= '1'
															AND is_deleted		= '0'
															ORDER BY campus_id ASC");
							while($valSubCampus = mysqli_fetch_array($sqlSubCampus)) {
								echo '<option value="'.$valSubCampus['campus_id'].'" '.($valSubCampus['campus_id'] == $id_campus ? 'selected' : '').'>'.$valSubCampus['campus_name'].'</option>';
							}
							echo'
						</select>
					</div>';
				}else{
					echo'<input type="hidden" name="id_campus" id="id_campus" value="'.$id_campus.'">';
				}
				echo'
				<div class="col-md-6 mb-xs">
					<label class="control-label">Exam Type <span class="required">*</span></label>
					<select class="form-control" data-plugin-selectTwo data-width="100%" id="id_exam" name="id_exam" required title="Must Be Required">
						<option value="">Select</option>';
						foreach ($EXAM_TYPES as $key => $val) {
							echo'<option value="'.$val['type_id'].'" '.($val['type_id'] == $id_exam ? 'selected' : '').'>'.$val['type_name'].'</option>';
						}
						echo'
					</select>
				</div>
				<div class="col-md-6 mb-xs">
					<label class="control-label">Class <span class="required">*</span></label>
					<select class="form-control" data-plugin-selectTwo data-width="100%" name="id_class" id="id_class" required title="Must Be Required" >';
						if($CLASSES){
							echo'<option value="">Select</option>';
							foreach ($CLASSES as $key => $val) {
								echo'<option value="'.$val['class_id'].'" '.($val['class_id'] == $id_class ? 'selected' : '').'>'.$val['class_name'].'</option>';
							}
						}else{							
							echo'<option value="">Select Exam Type First</option>';
						}
						echo'
					</select>
				</div>
				<div class="col-md-6 mb-xs">
					<label class="control-label">Section <span class="required">*</span></label>
					<select class="form-control" data-plugin-selectTwo data-width="100%" name="id_section" id="id_section" required title="Must Be Required" >';
						if($CLASS_SECTIONS){
							echo'<option value="">Select</option>';
							foreach ($CLASS_SECTIONS as $key => $val) {
								echo'<option value="'.$val['section_id'].'" '.($val['section_id'] == $id_section ? 'selected' : '').'>'.$val['section_name'].'</option>';
							}
						}else{							
							echo'<option value="">Select Class First</option>';
						}
						echo'
					</select>
				</div>
				<div class="col-md-6 mb-xs">
					<label class="control-label">Subject <span class="required">*</span></label>
					<select class="form-control" data-plugin-selectTwo data-width="100%" name="id_subject" id="id_subject" required title="Must Be Required" >';
						if($CLASS_SUBJECTS){
							echo'<option value="">Select</option>';
							foreach ($CLASS_SUBJECTS as $key => $val) {
								echo'<option value="'.$val['subject_id'].'" '.($val['subject_id'] == $id_subject ? 'selected' : '').'>'.$val['subject_name'].' - '.$val['dated'].'</option>';
							}
						}else{							
							echo'<option value="">Select Class First</option>';
						}
						echo'
					</select>
				</div>
			</div>
		</div>
		<footer class="panel-footer">
			<div class="row">
				<div class="col-md-12 text-center">
					<button type="submit" class="mr-xs btn btn-primary"><i class="fa fa-print"></i> Print Award List</button>
				</div>
			</div>
		</footer>
		</form>
	</section>';
}else{
	header("Location: ".moduleName().".php", true, 301);
}
?>