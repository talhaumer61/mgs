<?php
if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('9', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '9', 'add' => '1'))) {
	$id_class	= '';
	$id_campus	= ((isset($_POST['id_campus']) && !empty($_POST['id_campus'])) ? $_POST['id_campus'] : $_SESSION['userlogininfo']['LOGINCAMPUS']);
	$id_section = ((isset($_POST['id_section']) && !empty($_POST['id_section'])) ? $_POST['id_section'] : '');
	$status		= ((isset($_POST['status']) && !empty($_POST['status'])) ? $_POST['status'] : '');
	
	if(!empty($_POST['id_class'])){
		$array		= explode("|",$_POST['id_class']);
		$id_class	= $array[0];
	}
	echo'
	<section class="panel panel-featured panel-featured-primary">
		<form action="#" class="mb-lg validate" enctype="multipart/form-data" method="post" accept-charset="utf-8">
			<div class="panel-heading">
				<h4 class="panel-title"><i class="fa fa-plus-square"></i> Add Timetable</h4>
			</div>
			<div class="panel-body">
				<div class="row">';
					if(!empty($_SESSION['userlogininfo']['SUBCAMPUSES'])){
						echo'
						<div class="col-md-4">
							<label class="control-label">Sub Campus</label>
							<select class="form-control" title="Must Be Required" data-plugin-selectTwo data-width="100%" id="id_campus" name="id_campus" title="Must Be Required" onchange="get_class(this.value)">
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
					}
					echo'
					<div class="col-md-4 '.(!empty($_SESSION['userlogininfo']['SUBCAMPUSES']) ? '' : 'col-md-offset-2').'">
						<label class="control-label">Class <span class="required">*</span></label>
						<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" id="id_class" name="id_class" onchange="get_section(this.value)">
							<option value="">Select</option>';
							$sqlLevelClasses    = $dblms->querylms("SELECT  l.level_classes
																	FROM ".CAMPUS." c
																	LEFT JOIN ".CAMPUS_LEVELS." l ON l.level_id = c.id_level
																	WHERE campus_id = '".cleanvars($id_campus)."' LIMIT 1");
							$valLevelClasses    = mysqli_fetch_array($sqlLevelClasses);

							$sqllmsclass	= $dblms->querylms("SELECT class_id, class_name
																FROM ".CLASSES."
																WHERE class_id != '' AND class_status = '1'
																AND class_id IN (".$valLevelClasses['level_classes'].")
																ORDER BY class_id ASC");
							while($value_class 	= mysqli_fetch_array($sqllmsclass)) {
								echo'<option value="'.$value_class['class_id'].'" '.($value_class['class_id'] == $id_class ? 'selected' : '').'>'.$value_class['class_name'].'</option>';
							}
							echo'
						</select>
					</div>
					<div class="col-sm-4">
						<div class="form-group">
							<label class="control-label">Section <span class="required">*</span></label>
							<select class="form-control" data-plugin-selectTwo data-width="100%" id="id_section" name="id_section" required>
								<option value="">Select</option>';
								$sqllmscls	= $dblms->querylms("SELECT section_id, section_name 
																FROM ".CLASS_SECTIONS."
																WHERE id_campus     = '".$id_campus."'
																AND id_class        = '".$id_class."'
																AND section_status  = '1'
																AND is_deleted      = '0'
																ORDER BY section_name ASC");
								if(mysqli_num_rows($sqllmscls) > 0){
									echo'<option value="">Select</option>';
									while($valuecls = mysqli_fetch_array($sqllmscls)) {
										echo '<option value="'.$valuecls['section_id'].'" '.($valuecls['section_id'] == $id_section ? 'selected' : '').'>'.$valuecls['section_name'].'</option>';
									}
								}else{
									echo '<option value="">No Record Found</option>';
								}
								echo'
							</select>
						</div>
					</div>
				</div>
				<div class="col-md-2"></div>		  
				<div class="form-group">
					<label class="col-md-1 control-label mt-lg">Status <span class="required">*</span></label>
					<div class="col-md-8 mt-lg">
						<div class="radio-custom radio-inline">
							<input type="radio" id="status" name="status" value="1" checked>
							<label for="radioExample1">Active</label>
						</div>
						<div class="radio-custom radio-inline">
							<input type="radio" id="status" name="status" value="2">
							<label for="radioExample2">Inactive</label>
						</div>
					</div>
				</div>
			</div>
			<footer class="panel-footer">
				<div class="row">
					<div class="col-md-12 text-center">';
						if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '9', 'add' => '1'))){ 	
							echo'<button type="submit" id="view_details" name="view_details" class="mr-xs btn btn-primary">Get Details</button>';
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
			<form action="timetable.php" class="mb-lg validate" enctype="multipart/form-data" method="post" accept-charset="utf-8">
				<div class="panel-heading">
					<h4 class="panel-title"><i class="fa fa-plus-square"></i> Add Timetable</h4>
				</div>
				<div class="panel-body">
					<div id="checkboxes">';
						$not_period = 0;
						$sri = 0;
						$ji = 0;
						$kij = 0;
						$rolesarray = array();
						$brolesarray = array();

						// for looop	
						foreach($daytypes as $day) {
							// PERIOD NAMES
							$sqllmsperiods  = $dblms->querylms("SELECT period_id, period_name
																	FROM ".PERIODS." 
																	WHERE id_campus = '".$id_campus."' 
																	AND period_id != '' AND period_status = '1' AND is_deleted != '1'
																	ORDER BY period_name ASC");
							if (mysqli_num_rows($sqllmsperiods) > 0) {
								$kij++;
								echo '
								<div style="clear:both;"></div>
								<div class="col-lg-12 heading-modal mt-sm" style="background-color: #cb3f44; color: white; padding: 5px 10px; border-radius: 5px;">
									<b> '.$day['name'].'</b>
									<span class="pull-right">
										<input type="hidden" name="id_offdayVal['.$day['id'].']" value="'.$day['id'].'">
										<input type="checkbox" name="id_offday['.$day['id'].']" value="'.$day['id'].'"> 
										<b>Mark as day off</b>
									</span>
								</div>
								<div style="clear:both;"></div>';
								while($rowperiods = mysqli_fetch_array($sqllmsperiods)) {
									$sri++;
									$ji++;
									echo'
									<div class="col-sm-41">
										<div class="form_sep" style="margin-top:10px;">
											<div class="col-sm-6" style="padding:10px;">
												<input type="hidden" id="day['.$sri.']" name="day['.$sri.']" value="'.$day['id'].'">
												<input type="hidden" id="id_period['.$sri.']" name="id_period['.$sri.']" value="'.$rowperiods['period_id'].'">
												<label><b style="color: #cb3f44"> '.$rowperiods['period_name'].'</b></label><br>
												<div class="col-sm-12">
													<div class="col-md-4 form-group">
														<label class="control-label">Subject</label>
														<select data-plugin-selectTwo data-width="100%" name="id_subject['.$sri.']" id="id_subject['.$sri.']" title="Must Be Required" class="form-control populate">
															<option value="">Select</option>
															<option value="99998">Break</option>';
															$sqllms	= $dblms->querylms("SELECT subject_id, subject_name, subject_code
																						FROM ".CLASS_SUBJECTS." 
																						WHERE subject_status = '1' AND is_deleted != '1' AND id_class = '".$id_class."'
																						ORDER BY subject_name ASC");
															while($rowsvalues = mysqli_fetch_array($sqllms)){
															echo'<option value="'.$rowsvalues['subject_id'].'">'.$rowsvalues['subject_name'].' ('.$rowsvalues['subject_code'].')</option>';
															}
															echo'
														</select>
													</div>
													<div class="col-md-4 form-group">
														<label class="control-label">Teacher</label>
														<select data-plugin-selectTwo data-width="100%" name="id_emply['.$sri.']" id="id_emply['.$sri.']" title="Must Be Required" class="form-control populate">
															<option value="">Select</option>';
															$sqllms	= $dblms->querylms("SELECT emply_id, emply_name
																							FROM ".EMPLOYEES." 
																							WHERE emply_status = '1' AND is_deleted != '1' AND id_type = '1'
																							AND id_campus = '".$id_campus."'
																							ORDER BY emply_name ASC");
																while($rowsvalues = mysqli_fetch_array($sqllms)){
																echo'<option value="'.$rowsvalues['emply_id'].'">'.$rowsvalues['emply_name'].'</option>';
																}
																echo'
														</select>
													</div>
													<div class="col-md-4 form-group">
														<label class="control-label">Room</label>
														<select data-plugin-selectTwo data-width="100%" name="id_room['.$sri.']" id="id_room['.$sri.']" title="Must Be Required" class="form-control populate">			
															<option value="">Select</option>
															';
															$sqllms	= $dblms->querylms("SELECT r.room_id, r.room_status, r.room_no
																							FROM ".CLASS_ROOMS." r
																							WHERE r.id_campus = '".$id_campus."' 
																							AND r.room_status = '1' AND is_deleted != '1'
																							ORDER BY r.room_no ASC");
															while($rowsvalues = mysqli_fetch_array($sqllms)){
																echo'
																<option value="'.$rowsvalues['room_id'].'">'.$rowsvalues['room_no'].'</option>
															';
															}
															echo'
														</select>
													</div>
												</div>
											</div>
										</div> 
									</div>';
								}  // end while loop
							}else{
								$not_period ++;
								if($not_period == 1){
									echo'<h3 class="center text text-danger">No Periods Added Yet!</h3>';
								}
							} // end if count
						} // end foreach
						echo'
						<input type="hidden" name="id_class" value="'.$id_class.'">
						<input type="hidden" name="id_section" value="'.$id_section.'">
						<input type="hidden" name="id_campus" value="'.$id_campus.'">
						<input type="hidden" name="status" value="'.$status.'">
					</div>
				</div>
				<footer class="panel-footer">
					<div class="row">
						<div class="col-md-12 text-right">
							<button type="submit" id="submit_timetable" name="submit_timetable" class="mr-xs btn btn-primary">Add Timetable</button>
							<button type="reset" class="btn btn-default">Reset</button>
						</div>
					</div>
				</footer>
			</form>
		</section>';
	}
}else{
	header("Location: timetable.php", true, 301);
}
?>