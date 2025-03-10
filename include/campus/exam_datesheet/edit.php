<?php
if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('12', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '12', 'edit' => '1'))) {
	// DATESHEET
	$condition = array(
							 'select'       =>  'id, status, id_exam, id_session, id_class, id_section'
							,'where'        =>  array(
														 'id_campus'	=> cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
														,'id'  			=> cleanvars($_GET['id'])
											)
							,'return_type'  =>  'single'
	);
	$DATESHEET = $dblms->getRows(DATESHEET, $condition);
	echo'
	<section class="panel panel-featured panel-featured-primary">
		<form action="exam_datesheet.php" class="mb-lg validate" enctype="multipart/form-data" method="post" accept-charset="utf-8">
			<input type="hidden" name="id" id="id" value="'.cleanvars($_GET['id']).'">
			<div class="panel-heading">
				<h4 class="panel-title"><i class="fa fa-edit"></i> Edit Datesheet</h4>
			</div>				
			<div class="panel-body">
				<div class="row mt-sm">
					<div class="col-md-4 col-md-offset-2">
						<label class="control-label">Exam <span class="required">*</span></label>
						<select class="form-control" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="id_exam" required title="Must Be Required" >
							<option value="">Select</option>';
							// EXAM TYPES
							$condition = array(
												     'select'       =>  'type_id, type_status, type_name'
													,'where'        =>  array(
																				 'type_status'	=> 1
																				,'is_deleted'  	=> 0
																	)
													,'return_type'  =>  'all'
							);
							$EXAM_TYPES = $dblms->getRows(EXAM_TYPES, $condition);
							foreach ($EXAM_TYPES AS $key => $val) {
								echo'
								<option value="'.$val['type_id'].'" '.($val['type_id'] == $DATESHEET['id_exam']?'selected':'').'>'.$val['type_name'].'</option>';
							}
							echo '
						</select>
					</div>
					<div class="col-md-4">
						<label class="control-label">Class <span class="required">*</span></label>
						<select class="form-control" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="id_class" disabled required title="Must Be Required" >
							<option value="">Select</option>';
							// EXAM TYPES
							$condition = array(
												     'select'       =>  'class_id, class_status, class_name'
													,'where'        =>  array(
																				 'class_status'	=> 1
																				,'is_deleted'  	=> 0
																	)
													,'search_by'  	=>  ' AND class_id IN ('.cleanvars($_SESSION['userlogininfo']['LOGINCAMPUSCLASSES']).')'
													,'return_type'  =>  'all'
							);
							$CLASSES = $dblms->getRows(CLASSES, $condition);
							foreach ($CLASSES AS $key => $val) {
								echo'
								<option value="'.$val['class_id'].'" '.($val['class_id'] == $DATESHEET['id_class']?'selected':'').'>'.$val['class_name'].'</option>';
							}
							echo '
						</select>
					</div>
				</div>				  
				<div class="form-group">
					<label class="col-md-1 col-md-offset-2 control-label mt-lg">Publish <span class="required">*</span></label>
					<div class="col-md-8 mt-lg">
						<div class="radio-custom radio-inline">
							<input type="radio" id="status" name="status" value="1"'; if($DATESHEET['status'] == 1){ echo'checked'; } echo'>
							<label for="radioExample1">Yes</label>
						</div>
						<div class="radio-custom radio-inline mb-xs">
							<input type="radio" id="status" name="status" value="2"'; if($DATESHEET['status'] == 2){ echo'checked'; } echo'>
							<label for="radioExample2">No</label>
						</div>
					</div>
				</div>
				<div id="checkboxes">';
					// CLASS SUBJECTS
					$condition = array(
											 'select'       =>  'COUNT(subject_id) as subjects'
											,'where'        =>  array(
																		 'subject_status'  	=> 1
																		,'is_deleted'  		=> 0
																		,'id_class'  		=> $DATESHEET['id_class']
															)
											,'return_type'  =>  'single'
					);
					$CLASS_SUBJECTS = $dblms->getRows(CLASS_SUBJECTS, $condition);
					if ($CLASS_SUBJECTS) {
						$rowsubject = mysqli_fetch_array($sqllmssubjects);
						// CLASS SUBJECTS
						$condition = array(
												 'select'       =>  'subject_id, subject_name, subject_code'
												,'where'        =>  array(
																			 'subject_status'  	=> 1
																			,'is_deleted'  		=> 0
																			,'id_class'  		=> $DATESHEET['id_class']
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
						for($i = 1 ; $i <= $CLASS_SUBJECTS['subjects'] ; $i++){
							// DATESHEET DETAIL
							$condition = array(
													 'select'       =>  'id_subject, dated, start_time, end_time, total_marks, passing_marks'
													,'where'        =>  array(
																				 'id_setup'	=> cleanvars($_GET['id'])
																				,'paper_no' => $i
																	)
													,'return_type'  =>  'single'
							);
							$DATESHEET_DETAIL = $dblms->getRows(DATESHEET_DETAIL, $condition);
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
														<option value="'.$val['subject_id'].'" '.($DATESHEET_DETAIL['id_subject'] == $val['subject_id']?'selected':'').'>'.$val['subject_name'].' ('.$val['subject_code'].')</option>';
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
															<option value="'.$rowsvalues['emply_id'].'" '.($rowsvalues['emply_id'] == $val['id_teacher']?'selected':'').'>'.$rowsvalues['emply_name'].'</option>';
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
															<option value="'.$val['room_id'].'" '.($rowsvalues['room_id'] == $valuedetail['id_room']?'selected':'').'>'.$val['room_no'].'</option>';
														}
													echo'
												</select>
											</div>';
											*/
											echo'
											<div class="col-md-4 form-group">
												<label class="control-label">Time From </label>
												<input type="text" class="form-control" name="start_time['.$i.']" id="start_time" value="'.$DATESHEET_DETAIL['start_time'].'" data-plugin-timepicker/>
											</div>
											<div class="col-md-4 form-group">
												<label class="control-label">Time To </label>
												<input type="text" class="form-control" name="end_time['.$i.']" id="end_time" value="'.$DATESHEET_DETAIL['end_time'].'" data-plugin-timepicker/>
											</div>
										</div>
										<div class="col-sm-12">
											<div class="col-md-4 form-group">
												<label class="control-label">Date </label>
												<input type="text" class="form-control" name="dated['.$i.']" id="dated" value="'.($DATESHEET_DETAIL['dated']?date("m/d/Y", strtotime($DATESHEET_DETAIL['dated'])):'').'" data-plugin-datepicker/>
											</div>
											<div class="col-md-4 form-group">
												<label class="control-label">Total Marks </label>
												<input type="number" class="form-control" name="total_marks['.$i.']" value="'.$DATESHEET_DETAIL['total_marks'].'"/>
											</div>
											<div class="col-md-4 form-group">
												<label class="control-label">Passing Marks </label>
												<input type="number" class="form-control" name="passing_marks['.$i.']" value="'.$DATESHEET_DETAIL['passing_marks'].'"/>
											</div>
										</div>
									</div>
								</div> 
							</div>';
						}
					}else{
						echo'<h3 class="center danger">No Subject Found!</h3>';
					}
					echo '
				</div>
			</div>
			<footer class="panel-footer">
				<div class="row">
					<div class="col-md-12 text-right">
						<button type="submit" id="change_datesheet" name="change_datesheet" class="mr-xs btn btn-primary">Update Datesheet</button>
					</div>
				</div>
			</footer>
		</form>
	</section>';
}else{
	header("Location: exam_datesheet.php", true, 301);
}
?>