<?php
if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('12', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '12', 'add' => '1'))) {
	if(isset($_POST['id_exam'])){$exam = $_POST['id_exam'];}else{$exam = "";}
	if(isset($_POST['id_class'])){$class = $_POST['id_class'];}else{$class = "";}
	if(isset($_POST['status'])){$status = $_POST['status'];}else{$status = "";}
	echo'
	<section class="panel panel-featured panel-featured-primary">
		<form action="#" class="mb-lg validate" enctype="multipart/form-data" method="post" accept-charset="utf-8">
		<div class="panel-heading">
			<h4 class="panel-title"><i class="fa fa-list"></i> Select</h4>
		</div>
		<div class="panel-body">
			<div class="row mt-sm">
				<div class="col-md-4 col-md-offset-2">
					<label class="control-label">Exam <span class="required">*</span></label>
					<select class="form-control" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="id_exam" required title="Must Be Required" >
						<option value="">Select</option>';
						// EXAM TYPES
						$condition = array(
												 'select'       =>  'DISTINCT t.type_id, t.type_status, t.type_name'
												,'where'        =>  array(
																			 't.type_status'	=> 1
																			,'t.is_deleted'  	=> 0
																)
												,'search_by'	=>	' AND (t.id_campus = '.$_SESSION['userlogininfo']['LOGINCAMPUS'].' OR t.id_campus = '.$_SESSION['userlogininfo']['PARENTCAMPUS'].')'
												,'return_type'  =>  'all'
						);
   						$EXAM_TYPES = $dblms->getRows(EXAM_TYPES.' AS t', $condition);
						foreach ($EXAM_TYPES AS $key => $val) {
							echo'
							<option value="'.$val['type_id'].'" '.($val['type_id'] == $exam?'selected':'').'>'.$val['type_name'].'</option>';
						}
					echo '
					</select>
				</div>
				<div class="col-md-4">
					<label class="control-label">Class <span class="required">*</span></label>
					<select class="form-control" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="id_class"  required title="Must Be Required" >
						<option value="">Select</option>';
						// CLASSES
						$condition = array(
												 'select'       => ' DISTINCT c.class_id, c.class_status, c.class_name'
												,'join'			=> 'LEFT JOIN '.STUDENTS.' AS s ON c.class_id = s.id_class'
												,'where'        =>  array(
																			 'c.class_status'	=> 1
																			,'c.is_deleted'  	=> 0
																)
												,'search_by'  	=>  ' AND c.class_id IN ('.cleanvars($_SESSION['userlogininfo']['LOGINCAMPUSCLASSES']).')'
												,'return_type'  =>  'all'
						);
   						$CLASSES = $dblms->getRows(CLASSES.' AS c', $condition);
						foreach ($CLASSES AS $key => $val) {
							echo'
							<option value="'.$val['class_id'].'" '.($val['class_id'] == $class?'selected':'').'>'.$val['class_name'].'</option>';
						}
						echo '
					</select>
				</div>
			</div>				  
			<div class="form-group">
				<label class="col-md-1 col-md-offset-2 control-label mt-lg">Publish <span class="required">*</span></label>
				<div class="col-md-8 mt-lg">
					<div class="radio-custom radio-inline">
						<input type="radio" id="status" name="status" value="1" checked>
						<label for="radioExample1">Yes</label>
					</div>
					<div class="radio-custom radio-inline mb-xs">
						<input type="radio" id="status" name="status" value="2">
						<label for="radioExample2">No</label>
					</div>
				</div>
			</div>
		</div>
		<footer class="panel-footer">
			<div class="row">
				<div class="col-md-12 text-center">';
				if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '9', 'add' => '1'))){ 	
					echo'
					<button type="submit" id="view_details" name="view_details" class="mr-xs btn btn-primary">Get Details</button>';
				}
				echo'
				</div>
			</div>
		</footer>
		</form>
	</section>';

	if(isset($_POST['view_details'])){
		echo'
		<section class="panel panel-featured panel-featured-primary">
			<form action="exam_datesheet.php" class="mb-lg validate" enctype="multipart/form-data" method="post" accept-charset="utf-8">
				<div class="panel-heading">
					<h4 class="panel-title"><i class="fa fa-plus-square"></i> Add Datesheet</h4>
				</div>
				<div class="panel-body">
					<div id="checkboxes">';					
					$condition = array(
											 'select'       =>  'COUNT(subject_id) as subjects'
											,'where'        =>  array(
																		 'subject_status'  	=> 1
																		,'is_deleted'  		=> 0
																		,'id_class'  		=> $class
																	)
											,'return_type'  =>  'single'
					);
					$CLASS_SUBJECTS = $dblms->getRows(CLASS_SUBJECTS, $condition);
					if ($CLASS_SUBJECTS) {
						// CLASS SUBJECTS
						$condition = array(
												 'select'       =>  'subject_id, subject_name, subject_code'
												,'where'        =>  array(
																			 'subject_status'  	=> 1
																			,'is_deleted'		=> 0
																			,'id_class'  		=> $class
																)
												,'return_type'  =>  'all'
						);
   						$C_SUBJECTS = $dblms->getRows(CLASS_SUBJECTS, $condition);
						// EMPLOYEES
						$condition = array(
												 'select'       =>  'emply_id, emply_name'
												,'where'        =>  array(
																			 'emply_status'  	=> 1
																			,'id_type'  		=> 1
																			,'id_campus'  		=> $_SESSION['userlogininfo']['LOGINCAMPUS']
																)
												,'return_type'  =>  'all'
						);
						$EMPLOYEES = $dblms->getRows(EMPLOYEES, $condition);
						// CLASS ROOMS
						$condition = array(
												 'select'       =>  'room_id, room_status, room_no'
												,'where'        =>  array(
																			 'room_status'  	=> 1
																			,'id_campus'  		=> $_SESSION['userlogininfo']['LOGINCAMPUS']
																)
												,'return_type'  =>  'all'
						);
						$CLASS_ROOMS = $dblms->getRows(CLASS_ROOMS, $condition);
						for ($i = 1; $i <= $CLASS_SUBJECTS['subjects']; $i++) {
							echo '
							<div class="col-sm-41">
								<div class="form_sep" style="margin-top:10px;">
									<div class="col-sm-6" style="padding:10px;">
										<label><b style="color: #cb3f44">Paper: '.$i.'</b></label><br>
										<input type="hidden" name="paper_no['.$i.']" value="'.$i.'">
										<div class="col-sm-12">
											<div class="col-md-4 form-group">
												<label class="control-label">Subject </label>
												<select data-plugin-selectTwo data-width="100%" name="id_subject['.$i.']" id="id_subject" title="Must Be Required" class="form-control populate" >
													<option value="">Select</option>';
													foreach ($C_SUBJECTS AS $key => $val) {
														echo'
														<option value="'.$val['subject_id'].'">'.$val['subject_name'].' ('.$val['subject_code'].')</option>';
													}
													echo'
												</select>
											</div>';
											/*
											<div class="col-md-4 form-group">
												<label class="control-label">Invigilator </label>
												<select data-plugin-selectTwo data-width="100%" name="id_teacher['.$i.']" id="id_teacher" title="Must Be Required" class="form-control populate" >
													<option value="">Select</option>';
													foreach ($EMPLOYEES AS $key => $val) {
														echo'
														<option value="'.$val['emply_id'].'">'.$val['emply_name'].'</option>';
													}
													echo'
												</select>
											</div>
											<div class="col-md-4 form-group">
												<label class="control-label">Room </label>
												<select data-plugin-selectTwo data-width="100%" name="id_room['.$i.']" id="id_room" title="Must Be Required" class="form-control populate" >
													<option value="">Select</option>';													
													foreach ($CLASS_ROOMS AS $key => $val) {
														echo'
														<option value="'.$val['room_id'].'">'.$val['room_no'].'</option>';
													}
													echo'
												</select>
											</div>
											*/
											echo'
											<div class="col-md-4 form-group">
												<label class="control-label">Time From </label>
												<input type="text" class="form-control" name="start_time['.$i.']" id="start_time" data-plugin-timepicker/>
											</div>
											<div class="col-md-4 form-group">
												<label class="control-label">Time To </label>
												<input type="text" class="form-control" name="end_time['.$i.']" id="end_time" data-plugin-timepicker/>
											</div>
										</div>
										<div class="col-sm-12">
											<div class="col-md-4 form-group">
												<label class="control-label">Date </label>
												<input type="text" class="form-control" name="dated['.$i.']" id="dated" data-plugin-datepicker/>
											</div>
											<div class="col-md-4 form-group">
												<label class="control-label">Total Marks </label>
												<input type="number" class="form-control" name="total_marks['.$i.']"/>
											</div>
											<div class="col-md-4 form-group">
												<label class="control-label">Passing Marks </label>
												<input type="number" class="form-control" name="passing_marks['.$i.']"/>
											</div>
										</div>
									</div>
								</div> 
							</div>';
						}
					} else {
						echo'<h3 class="center danger">No Subject Found!</h3>';
					}
					echo '
					<input type="hidden" name="exam" value="'.$exam.'">
					<input type="hidden" name="class" value="'.$class.'">
					<input type="hidden" name="status" value="'.$status.'">
				</div>
				</div>
				<footer class="panel-footer">
					<div class="row">
						<div class="col-md-12 text-right">
							<button type="submit" id="submit_datesheet" name="submit_datesheet" class="mr-xs btn btn-primary">Add Datesheet</button>
							<button type="reset" class="btn btn-default">Reset</button>
						</div>
					</div>
				</footer>
			</form>
		</section>';
	}
}else{
	header("Location: exam_datesheet.php", true, 301);
}
?>