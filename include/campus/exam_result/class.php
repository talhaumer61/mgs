<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '12', 'view' => '1'))){
	$is_hostel		= ((isset($_POST['is_hostel']) && !empty($_POST['is_hostel'])))	? cleanvars($_POST['is_hostel'])	: '';

	// EXAM TYPES
	$condition = array(
						 'select'		=>  't.type_id, t.type_status, t.type_name'
						,'join'			=>	'INNER JOIN '.DATESHEET.' d on d.id_exam = t.type_id'
						,'where'        =>  array(
													 't.type_status'	=>	1
													,'t.is_deleted'  	=>	0
												)
						,'search_by'	=>	' AND (t.id_campus = '.$_SESSION['userlogininfo']['LOGINCAMPUS'].' OR t.id_campus = '.$_SESSION['userlogininfo']['PARENTCAMPUS'].')'
						,'group_by'		=>	' d.id_exam'
						,'return_type'  =>  'all'
	);
	// SESSION CHECK IS REMOVED 
	//  AND d.id_session = '.cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION']).'
	$EXAM_TYPES = $dblms->getRows(EXAM_TYPES.' t', $condition);
	// EXAM CLASSES
	$condition = array(
						 'select'       =>  'GROUP_CONCAT(l.level_classes) campus_classes'
						,'join'			=>	'LEFT JOIN '.CAMPUS_LEVELS.' l ON l.level_id = c.id_level'
						,'where'        =>  array(
													 'c.is_deleted'	=> 0
                                                    ,'c.campus_id'  =>  cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
												)
						,'return_type'  =>  'single'
	);
	$CAMPUS_CLASSES = $dblms->getRows(CAMPUS.' c', $condition);
	echo '
	<section class="panel panel-featured panel-featured-primary">
		<header class="panel-heading">
			<h2 class="panel-title"><i class="fa fa-filter"></i> Filters</h2>
		</header>
		<div class="panel-body">
			<form method="POST" action="exam_'.LMS_VIEW.'_result_print.php" target="_blank">
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
									echo '<option value="'.$valSubCampus['campus_id'].'" '.($valSubCampus['campus_id'] == $_SESSION['userlogininfo']['LOGINCAMPUS'] ? 'selected' : '').'>'.$valSubCampus['campus_name'].'</option>';
								}
								echo'
							</select>
						</div>';
					}else{
						echo'<input type="hidden" name="id_campus" id="id_campus" value="'.$_SESSION['userlogininfo']['LOGINCAMPUS'].'">';
					}
					echo'
					<div class="col-md-'.(!empty($_SESSION['userlogininfo']['SUBCAMPUSES'])?'4':'6').' mb-xs">
						<label class="control-label">Exam Type <span class="required">*</span></label>
						<select class="form-control" data-plugin-selectTwo data-width="100%" id="id_exam" name="id_exam" required title="Must Be Required">
							<option value="">Select</option>';
							foreach ($EXAM_TYPES as $key => $val) {
								echo'<option value="'.$val['type_id'].'" '.($val['type_id'] == $id_exam ? 'selected' : '').'>'.$val['type_name'].'</option>';
							}
							echo'
						</select>
					</div>
					<div class="col-md-'.(!empty($_SESSION['userlogininfo']['SUBCAMPUSES'])?'4':'6').' mb-xs">
						<label class="control-label">Class <span class="required">*</span></label>
						<select class="form-control" data-plugin-selectTwo data-width="100%" name="id_class" id="id_class" required title="Must Be Required" >';
							if($CLASSES) {
								echo'<option value="">Select</option>';
								foreach ($CLASSES as $key => $val) {
									echo'<option value="'.$val['class_id'].'" '.($val['class_id'] == $id_class ? 'selected' : '').'>'.$val['class_name'].'</option>';
								}
							} else {							
								echo'<option value="">Select Exam Type First</option>';
							}
							echo'
						</select>
					</div>
					<div class="col-md-4 mb-xs">
						<label class="control-label">Section <span class="required">*</span></label>
						<select class="form-control" data-plugin-selectTwo data-width="100%" name="id_section" id="id_section" required title="Must Be Required" >
							<option value="">Select Class First</option>
						</select>
					</div>
					<div class="col-md-4 mb-xs">
						<div class="form-group">
							<label class="control-label">Hostelize</label>
							<select class="form-control" data-plugin-selectTwo data-width="100%" id="is_hostel" name="is_hostel" title="Must Be Required">
								<option value="">Select</option>';
								foreach ($statusyesno as $hostel_status) {
									echo '<option value="'.$hostel_status['id'].'">'.$hostel_status['name'].'</option>';
								}
								echo'
							</select>
						</div>
					</div>
					<div class="col-md-4 mb-xs">
						<div class="form-group">
							<label class="control-label">Order By <span class="required">*</span></label>
							<select class="form-control" data-plugin-selectTwo data-width="100%" id="order_by" name="order_by" title="Must Be Required" required>
								<option value="">Select</option>';
								foreach (get_OrderBy() AS $key => $val) {
									echo '<option value="'.$key.'">'.$val.'</option>';
								}
								echo'
							</select>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12 text-center mt-sm">
						<button type="submit" class="mr-xs btn btn-primary"><i class="glyphicon glyphicon-print"></i> Print</button>
					</div>
				</div>
			</form>
		</div>
	</section>';
}else{
	header("Location: dashboard.php");
}
?>