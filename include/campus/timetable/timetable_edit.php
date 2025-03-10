<?php
if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('9', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '9', 'edit' => '1'))) {
	$sqllmstimetableedit	= $dblms->querylms("SELECT t.id, t.status, t.id_offday, t.id_session, t.id_class, t.id_section, t.id_campus,
												c.class_id, c.class_status, c.class_name, cm.campus_name,
												se.section_id, se.section_status, se.section_name
												FROM ".TIMETABLE." t
												INNER JOIN ".CAMPUS." cm ON cm.campus_id = t.id_campus
												INNER JOIN ".CLASSES." c ON c.class_id = t.id_class
												INNER JOIN ".CLASS_SECTIONS." se ON se.section_id = t.id_section					   
												WHERE t.id= '".cleanvars($_GET['id'])."' LIMIT 1");
	$value_edit = mysqli_fetch_array($sqllmstimetableedit);
	echo '
	<div class="row">
		<div class="col-md-12">
			<section class="panel panel-featured panel-featured-primary">
				<form action="timetable.php" class="mb-lg validate" enctype="multipart/form-data" method="post" accept-charset="utf-8">
					<input type="hidden" name="id" id="id" value="'.cleanvars($_GET['id']).'">
					<input type="hidden" name="id_class" id="id_class" value="'.cleanvars($value_edit['id_class']).'">
					<input type="hidden" name="id_section" id="id_section" value="'.cleanvars($value_edit['id_section']).'">					
					<input type="hidden" name="id_campus" id="id_campus" value="'.cleanvars($value_edit['id_campus']).'">
					<div class="panel-heading">
						<h4 class="panel-title"><i class="fa fa-edit"></i> Edit Timetable</h4>
					</div>
					<div class="panel-body">
						<div class="row">';							
							if(!empty($_SESSION['userlogininfo']['SUBCAMPUSES'])){
								echo'
								<div class="col-md-4">
									<label class="control-label">Sub Campus</label>
									<input type="text" class="form-control" value="'.$value_edit['campus_name'].'" readonly/>
								</div>';
							}
							echo'
							<div class="col-sm-4 '.(!empty($_SESSION['userlogininfo']['SUBCAMPUSES']) ? '' : 'col-md-offset-2').'">
								<div class="form-group">
									<label class="control-label">Class <span class="required">*</span></label>
									<input type="text" class="form-control" value="'.$value_edit['class_name'].'" readonly/>
								</div>
							</div>						
							<div class="col-sm-4">
								<div class="form-group">
									<label class="control-label">Section <span class="required">*</span></label>
									<input type="text" class="form-control" value="'.$value_edit['section_name'].'" readonly/>
								</div>
							</div>
						</div>					
						<div class="col-md-2"></div>		  
						<div class="form-group">
							<label class="col-md-1 control-label mt-lg">Status <span class="required">*</span></label>
							<div class="col-md-8 mt-lg">
								<div class="radio-custom radio-inline">
									<input type="radio" id="status" name="status" value="1" '.($value_edit['status'] == '1' ? 'checked' : '').'>
									<label for="radioExample1">Active</label>
								</div>
								<div class="radio-custom radio-inline">
									<input type="radio" id="status" name="status" value="2" '.($value_edit['status'] == '2' ? 'checked' : '').'>
									<label for="radioExample2">Inactive</label>
								</div>
							</div>
						</div>
					

						<div id="checkboxes">';
							$not_period = 0;
							$sri = 0;
							$ji = 0;
							$kij = 0;

							// for looop	
							foreach($daytypes as $day) {
								$sqllmsperiods  = $dblms->querylms("SELECT p.period_id, p.period_name
															FROM ".PERIODS." p  
															WHERE p.id_campus = '".$value_edit['id_campus']."' 
															AND p.period_status = '1' AND is_deleted != '1'
															ORDER BY p.period_name ASC");
								if (mysqli_num_rows($sqllmsperiods) > 0) {
									$kij++;
									$idCommaOffDay = explode(',', $value_edit['id_offday']);
									
									echo '
									<div style="clear:both;"></div>
									<div class="col-lg-12 heading-modal mt-sm" style="background-color: #cb3f44; color: white; padding: 5px 10px; border-radius: 5px;">
										<b> '.$day['name'].'</b>
										<span class="pull-right">
											<input type="hidden" name="id_offdayVal['.$day['id'].']" value="'.$day['id'].'">
											<input type="checkbox" name="id_offday['.$day['id'].']" value="'.$day['id'].'" '.((in_array($day['id'], $idCommaOffDay))? 'checked' : '').'> 
											<b>Mark as day off</b>
										</span>
									</div>
									<div style="clear:both;"></div>';
									while($rowperiods = mysqli_fetch_array($sqllmsperiods)) {
										$sri++;
										$ji++;
										$sqllmsdetail  = $dblms->querylms("SELECT *    
																				FROM ".TIMETABEL_DETAIL." d 
																				WHERE d.id_period 	= '".cleanvars($rowperiods['period_id'])."' 
																				AND d.day 			= '".cleanvars($day['id'])."' 
																				AND d.id_setup 		= '".cleanvars($_GET['id'])."' LIMIT 1");
										$valuedetail 		= mysqli_fetch_array($sqllmsdetail);
										echo '
										<div class="col-sm-41">
											<div class="form_sep" style="margin-top:10px;">
												<div class="col-sm-6" style="padding:10px;">
													<input type="hidden" id="day['.$sri.']" name="day['.$sri.']" value="'.$day['id'].'">
													<input type="hidden" id="period_id['.$sri.']" name="id_period['.$sri.']" value="'.$rowperiods['period_id'].'">
													<label><b style="color: #cb3f44"> '.$rowperiods['period_name'].'</b></label><br>
													<div class="col-sm-12">
													<div class="col-md-4 form-group">
														<label class="control-label">Subject</label>
														<select data-plugin-selectTwo data-width="100%" name="id_subject['.$sri.']" id="id_subject['.$sri.']" title="Must Be Required" class="form-control populate">
															<option value="">Select</option>
															<option value="99998" '.($valuedetail['id_subject'] == '99998' ? 'selected' : '').'>Break</option>';
															$sqllmscls	= $dblms->querylms("SELECT subject_id, subject_name, subject_code
																							FROM ".CLASS_SUBJECTS."  
																							WHERE subject_status = '1' AND is_deleted != '1'
																							AND id_class = '".$value_edit['id_class']."'
																							ORDER BY subject_name ASC");
															while($valuecls = mysqli_fetch_array($sqllmscls)) {
																if($valuecls['subject_id'] == $valuedetail['id_subject']) { 
																	echo '<option value="'.$valuecls['subject_id'].'" selected>'.$valuecls['subject_name'].' ('.$valuecls['subject_code'].')</option>';
																} else { 
																	echo '<option value="'.$valuecls['subject_id'].'">'.$valuecls['subject_name'].' ('.$valuecls['subject_code'].')</option>';
																}
															}
															echo'
														</select>
													</div>
													<div class="col-md-4 form-group">
														<label class="control-label">Teacher</label>
														<select data-plugin-selectTwo data-width="100%" name="id_emply['.$sri.']" id="id_emply['.$sri.']" title="Must Be Required" class="form-control populate">
															<option value="">Select</option>';
															$sqllmsteacher	= $dblms->querylms("SELECT emply_id, emply_name
																									FROM ".EMPLOYEES." 
																									WHERE emply_status = '1' AND id_type = '1' AND is_deleted != '1'
																									AND id_campus = '".$value_edit['id_campus']."'
																									ORDER BY emply_name ASC");
															while($valueteacher = mysqli_fetch_array($sqllmsteacher)) {
																if($valueteacher['emply_id'] == $valuedetail['id_teacher']) { 
																	echo '<option value="'.$valueteacher['emply_id'].'" selected>'.$valueteacher['emply_name'].'</option>';
																} else { 
																	echo '<option value="'.$valueteacher['emply_id'].'">'.$valueteacher['emply_name'].'</option>';
																}
															}
															echo'
														</select>
													</div>
													<div class="col-md-4 form-group">
														<label class="control-label">Room</label>
														<select data-plugin-selectTwo data-width="100%" name="id_room['.$sri.']" id="id_room['.$sri.']" title="Must Be Required" class="form-control populate">
															<option value="">Select</option>';
															$sqllmsroom	= $dblms->querylms("SELECT r.room_id, r.room_status, r.room_no
																							FROM ".CLASS_ROOMS." r
																							WHERE r.id_campus = '".$value_edit['id_campus']."' 
																							AND r.room_status = '1'  AND is_deleted != '1'
																							ORDER BY r.room_no ASC");
															while($valueroom = mysqli_fetch_array($sqllmsroom)) {
																if($valueroom['room_id'] == $valuedetail['id_room']) { 
																	echo '<option value="'.$valueroom['room_id'].'" selected>'.$valueroom['room_no'].'</option>';
																} else { 
																	echo '<option value="'.$valueroom['room_id'].'">'.$valueroom['room_no'].'</option>';
																}
															}
															echo'
															</select>
													</div>
												</div>
											</div>
										</div> 
										</div>';
									}  // end while loop
								} // end if count
							} // end foreach
							echo '
						</div>
					</div>
					<footer class="panel-footer">
						<div class="row">
							<div class="col-md-12 text-right">
								<button type="submit" id="change_timetable" name="change_timetable" class="mr-xs btn btn-primary">Update Timetable</button>
							</div>
						</div>
					</footer>
				</form>
			</section>
		</div>
	</div>';
}else{
	header("Location: timetable.php", true, 301);
}
?>