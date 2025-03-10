<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '12', 'view' => '1'))){
	$id_campus	= (!empty($_POST['id_campus']) ? $_POST['id_campus'] : $_SESSION['userlogininfo']['LOGINCAMPUS']);
	$col		= '6';
	echo '
	<section class="panel panel-featured panel-featured-primary">
		<header class="panel-heading">
			<h2 class="panel-title"><i class="fa fa-list"></i> RollNo Slips Print</h2>
		</header>
		<div class="panel-body">
			<form method="POST" action="exam_rollnoslips_print.php" target="_blank">
				<div class="row mt-sm">';
					if(!empty($_SESSION['userlogininfo']['SUBCAMPUSES'])){
						echo'
						<div class="col-md-'.$col.' mb-xs">
							<label class="control-label">Sub Campus</label>
							<select class="form-control" title="Must Be Required" data-plugin-selectTwo data-width="100%" id="id_campus" name="id_campus" title="Must Be Required">
								<option value="">Select</option>';
								// CAMPUS
								$condition = array(
														 'select'		=>  'campus_id, campus_name'
														,'where'        =>  array(
																					 'campus_status'	=>	1
																					,'is_deleted'  		=>	0
																		)
														,'search_by'	=>	' AND campus_id IN ('.$_SESSION['userlogininfo']['SUBCAMPUSES'].')'
														,'group_by'		=>	'campus_id ASC'
														,'return_type'  =>  'all'
								);
								$CAMPUS = $dblms->getRows(CAMPUS, $condition);
								foreach ($CAMPUS AS $key => $val) {
									echo '<option value="'.$val['campus_id'].'">'.$val['campus_name'].'</option>';
								}
								echo'
							</select>
						</div>';
					}else{
						echo'<input type="hidden" name="id_campus" id="id_campus" value="'.cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS']).'">';
					}
					echo'
					<div class="col-md-'.$col.' mb-xs">
						<label class="control-label">Exam <span class="text-danger">*</span></label>
						<select class="form-control" data-plugin-selectTwo data-width="100%" id="id_exam" name="id_exam" required title="Must Be Required">
							<option value="">Select</option>';
							// EXAM TYPES
							$condition = array(
													 'select'		=>  't.type_id, t.type_status, t.type_name'
													,'join'			=>	'INNER JOIN '.DATESHEET.' d on d.id_exam = t.type_id AND d.id_session = '.cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION']).''
													,'where'        =>  array(
																				 't.type_status'	=>	1
																				,'t.is_deleted'  	=>	0
																			)
													,'search_by'	=>	' AND (t.id_campus = '.$_SESSION['userlogininfo']['LOGINCAMPUS'].' OR t.id_campus = '.$_SESSION['userlogininfo']['PARENTCAMPUS'].')'
													,'group_by'		=>	' d.id_exam'
													,'return_type'  =>  'all'
							);
							$EXAM_TYPES = $dblms->getRows(EXAM_TYPES.' t', $condition);
							foreach ($EXAM_TYPES AS $key => $val) {
								echo'
								<option value="'.$val['type_id'].'">'.$val['type_name'].'</option>';
							}
							echo '
						</select>
					</div>
					<div class="col-md-'.$col.' mb-xs">
						<label class="control-label">Class <span class="text-danger">*</span></label>
						<select class="form-control" data-plugin-selectTwo data-width="100%" name="id_class" id="id_class" required title="Must Be Required">
							<option value="">Select First Exam</option>
						</select>
					</div>
					<div class="col-md-'.$col.' mb-xs">
						<label class="control-label">Section</label>
						<select class="form-control" data-plugin-selectTwo data-width="100%" name="id_section" id="id_section">
							<option value="">Select First Class</option>
						</select>
					</div>
					<div class="col-md-'.$col.' mb-xs">
						<label class="control-label">Reg No</label>
						<input type="text" class="form-control" name="std_regno" placeholder="Student Registration Number."/>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12 text-center mt-sm">
						<button type="submit" name="view_details" class="mr-xs btn btn-primary"><i class="glyphicon glyphicon-print"></i> Print Rollno Slips</button>
					</div>
				</div>
			</form>
		</div>
	</section>';
}else{
	header("Location: dashboard.php");
}
?>