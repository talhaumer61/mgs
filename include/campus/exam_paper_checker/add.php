<?php
if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('12', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '12', 'add' => '1'))) {
 	$id_campus	= (!empty($_POST['id_campus']) ? $_POST['id_campus'] : $_SESSION['userlogininfo']['LOGINCAMPUS']);

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

	// EMPLOYEES
	$condition = array(
						 'select'		=>  'emply_id, emply_name'
						,'where'        =>  array(
													 'emply_status'	=>	1
													,'is_deleted'  	=>	0
													,'is_ad'  		=>	0
													,'is_de'  		=>	0
													,'id_type' 		=>	1
													,'id_campus'  	=>	cleanvars($id_campus)
												)
						,'return_type'  =>  'all'
	);
	$EMPLOYEES = $dblms->getRows(EMPLOYEES, $condition);
	echo'
	<section class="panel panel-featured panel-featured-primary">
		<form class="mb-lg validate" enctype="multipart/form-data" method="post" accept-charset="utf-8" autocomplete="off">
			<div class="panel-heading">
				<h4 class="panel-title"><i class="fa fa-plus-square"></i> '.moduleName(false).'</h4>
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
						<select class="form-control" data-plugin-selectTwo data-width="100%" name="id_class" id="id_class" required title="Must Be Required" >
							<option value="">Select Exam Type First</option>
						</select>
					</div>
					<div class="col-md-6 mb-xs">
						<label class="control-label">Section <span class="required">*</span></label>
						<select class="form-control" data-plugin-selectTwo data-width="100%" name="id_section" id="id_section" required title="Must Be Required" >
							<option value="">Select Class First</option>
						</select>
					</div>
					<div class="col-md-6 mb-xs">
						<label class="control-label">Subject <span class="required">*</span></label>
						<select class="form-control" data-plugin-selectTwo data-width="100%" name="id_subject" id="id_subject" required title="Must Be Required" >
							<option value="">Select Section First</option>
						</select>
					</div>			
					<div class="col-md-6 mb-xs">
						<label class="control-label">Paper Checker <span class="required">*</span></label>
						<select class="form-control" data-plugin-selectTwo data-width="100%" name="id_emply" id="id_emply" required title="Must Be Required" >';
							if($EMPLOYEES){
								echo'<option value="">Select</option>';
								foreach ($EMPLOYEES as $key => $val) {
									echo'<option value="'.$val['emply_id'].'">'.$val['emply_name'].'</option>';
								}
							}else{
								echo'<option value="">No Record Found</option>';
							}
							echo'
						</select>
					</div>
					<div class="col-md-6 mb-xs">
						<label class="control-label">Quantity of Paper <span class="required">*</span></label>
						<input type="number" class="form-control" name="paper_qty" id="paper_qty" value="0" required title="Must Be Required" readonly/>
					</div>
					<div class="col-md-6 mb-xs">
						<label class="control-label">Hand Over Date <span class="required">*</span></label>
						<input type="text" class="form-control" name="date_handover" id="date_handover" value="'.date('m/d/Y').'" required title="Must Be Required"/>
					</div>
					<div class="col-md-6 mb-xs">
						<label class="control-label">Due Date <span class="required">*</span></label>
						<input type="text" class="form-control" name="date_submission" id="date_submission" data-plugin-datepicker required title="Must Be Required"/>
					</div>
				</div>
			</div>
			<footer class="panel-footer">
				<div class="row">
					<div class="col-md-12 text-right">
						<button type="submit" id="add_record" name="add_record" class="mr-xs btn btn-primary"><i class="fa fa-check"></i> Save Record</button>
						<a href="'.moduleName().'.php" class="mr-xs btn btn-default">Cancel</a>
					</div>
				</div>
			</footer>
		</form>
	</section>';
}else{
	header("Location: ".moduleName().".php", true, 301);
}
?>