<?php
if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('12', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '12', 'add' => '1'))) {
 	$id_campus	= (!empty($_POST['id_campus']) ? $_POST['id_campus'] : $_SESSION['userlogininfo']['LOGINCAMPUS']);
	$offset = 'col-md-offset-3';

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



	// EXAM TYPES
	// $condition = array(
	// 					 'select'		=>  't.type_id, t.type_status, t.type_name'
	// 					,'join'			=>	'INNER JOIN '.DATESHEET.' d on d.id_exam = t.type_id AND d.id_session = '.cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION']).'
	// 										 INNER JOIN '.EXAM_PAPER_CHECKER.' AS pc ON (pc.id_campus = '.$_SESSION['userlogininfo']['LOGINCAMPUS'].' OR pc.id_campus = '.$_SESSION['userlogininfo']['PARENTCAMPUS'].') AND t.type_id = pc.id_exam AND pc.is_deleted = 0'
	// 					,'where'        =>  array(
	// 												 't.type_status'	=>	1
	// 												,'t.is_deleted'  	=>	0
	// 											)
	// 					,'search_by'	=>	' AND (t.id_campus = '.$_SESSION['userlogininfo']['LOGINCAMPUS'].' OR t.id_campus = '.$_SESSION['userlogininfo']['PARENTCAMPUS'].')'
	// 					,'group_by'		=>	'd.id_exam'
	// 					,'return_type'  =>  'all'
	// );
	// $EXAM_TYPES = $dblms->getRows(EXAM_TYPES.' t', $condition, $sql);
	echo'
	<section class="panel panel-featured panel-featured-primary">
		<form action="exam_paper_checker_print.php" target="_blank" class="mb-lg validate" enctype="multipart/form-data" method="post" accept-charset="utf-8" autocomplete="off">
			<div class="panel-heading">
				<h4 class="panel-title"><i class="fa fa-plus-square"></i> '.moduleName(false).'</h4>
			</div>
			<div class="panel-body">
				<div class="row">';
					if(!empty($_SESSION['userlogininfo']['SUBCAMPUSES'])){
						$offset = '';
						echo'
						<div class="col-md-6 mb-xs">
							<label class="control-label">Sub Campus</label>
							<select class="form-control" title="Must Be Required" data-plugin-selectTwo data-width="100%" name="id_campus" title="Must Be Required">
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
					<div class="col-md-6 mb-xs '.$offset.'">
						<label class="control-label">Exam Type <span class="required">*</span></label>
						<select class="form-control" data-plugin-selectTwo data-width="100%" id="id_exam" name="id_exam" required title="Must Be Required">
							<option value="">Select</option>';
							foreach ($EXAM_TYPES as $key => $val) {
								echo'<option value="'.$val['type_id'].'|'.$val['type_name'].'" '.($val['type_id'] == $id_exam ? 'selected' : '').'>'.$val['type_name'].'</option>';
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