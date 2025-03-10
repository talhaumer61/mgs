<?php
if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('1', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '1', 'add' => '1'))) {	
	$sqllms	= $dblms->querylms("SELECT id, form_no, status, name, fathername, gender, cell_no, address, id_class, note, id_campus
								FROM ".ADMISSIONS_INQUIRY." 
								WHERE id != '' AND status = '1' AND id = '".$_GET['inquiry']."' LIMIT 1");
	$srno = 0;
	$rowsvalues = mysqli_fetch_array($sqllms);
	echo'
	<div class="row">
		<div class="col-md-12">
			<section class="panel panel-featured panel-featured-primary">
				<form action="students.php" class="mb-lg validate" enctype="multipart/form-data" method="post" accept-charset="utf-8">
					<div class="panel-heading">
						<h4 class="panel-title"><i class="fa fa-plus-square"></i> Add Student</h4>
					</div>
					<div class="panel-body">
						<label class="control-label">Photo</label>
						<div class="row">
							<div class="col-md-6">
								<div class="image_area">
									<label for="upload_image">
										<img src="uploads/default-student.jpg" id="uploaded_image" class="img-responsive thumbnail mb-none" width="200" />
										<div class="overlay">
											<div class="text-overlay"><h6>Click to Change Profile Image</h6></div>
										</div>
										<input type="file" name="image" class="image" id="upload_image" accept="image/*" oninput="getCropped()" style="display:none;"/>
										<input type="hidden" name="std_photo" id="std_photo">
									</label>
								</div>

								<!--
								<div class="fileinput fileinput-new" data-provides="fileinput">
									<div class="fileinput-new thumbnail" style="width: 130px; height: 130px;" data-trigger="fileinput">
										<img src="uploads/default-student.jpg" alt="...">
									</div>
									<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 130px; max-height: 130px">
										
									</div>
									<div>
										<span class="btn btn-xs btn-default btn-file">
											<span class="fileinput-new">Select image</span>
											<span class="fileinput-exists">Change</span>
											<input type="file" name="std_photo" accept="image/*">
										</span>
										<a href="#" class="btn btn-xs btn-warning fileinput-exists" data-dismiss="fileinput">Remove</a>
									</div>
								</div>
								-->
							</div>
						</div>
						<div class="row">
							<div class="col-sm-4">
								<div class="form-group">
									<label class="control-label">Form Number </label>
									<input type="text" class="form-control" name="form_no" value="'.$rowsvalues['form_no'].'" id="form_no" title="Must Be Required" autofocus onchange="get_formno(this.value)">
								</div>
							</div>';
							if(!empty($_SESSION['userlogininfo']['SUBCAMPUSES'])){
								echo'
								<div class="col-sm-4">
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
											echo '<option value="'.$valSubCampus['campus_id'].'" '.($valSubCampus['campus_id'] == $rowsvalues['id_campus'] ? 'selected' : '').'>'.$valSubCampus['campus_name'].'</option>';
										}
										echo'
									</select>
								</div>';
							}
							echo'
							<div class="col-sm-4">
								<label class="control-label">Session  <span class="required">*</span></label>
								<select class="form-control" title="Must Be Required" data-plugin-selectTwo data-width="100%" id="id_session" name="id_session" required>
									<option value="">Select</option>';
									$sqlSession = $dblms->querylms("SELECT session_id, session_name
																	FROM ".SESSIONS." 
																	WHERE session_status	= '1'
																	AND is_deleted			= '0' 
																  ");
									while($valSession = mysqli_fetch_array($sqlSession)) {
										echo '<option value="'.$valSession['session_id'].'">'.$valSession['session_name'].'</option>';
									}
									echo' 
								</select>
							</div>
						</div>
						<div id="getadmissiondetail">
							<div class="row">
								<div class="col-sm-4">
									<div class="form-group">
										<label class="control-label">Student Name <span class="required">*</span></label>
										<input type="text" class="form-control" name="std_name" id="std_name" value="'.$rowsvalues['name'].'" required title="Must Be Required" autofocus>
									</div>
								</div>
								<div class="col-sm-4">
									<div class="form-group">
										<label class="control-label">Father Name <span class="required">*</span></label>
										<input type="text" class="form-control" name="std_fathername" id="std_fathername" value="'.$rowsvalues['fathername'].'" required title="Must Be Required" >
									</div>
								</div>
								<div class="col-sm-4">
									<div class="form-group">
										<label class="control-label">Gender <span class="required">*</span></label>
										<select name="std_gender" data-plugin-selectTwo  data-width="100%" class="form-control populate" required title="Must Be Required">
											<option value="">Select</option>
											<option value="Male"'; if($rowsvalues['gender'] == 'Male'){echo'selected';} echo'>Male</option>
											<option value="Female"'; if($rowsvalues['gender'] == 'Female'){echo'selected';} echo'>Female</option>
										</select>
									</div>
								</div>	
							</div>
							<div class="row">
								<div class="col-sm-3">
									<div class="form-group">
										<label class="control-label">Family No <span class="text-danger">(Father CNIC)</span></label>
										<input type="text" class="form-control" name="std_familyno" id="std_familyno"">
									</div>
								</div>
								<div class="col-sm-3">
									<div class="form-group">
										<label class="control-label">NIC / B-Form</label>
										<input type="text" class="form-control" name="std_nic" id="std_nic"">
									</div>
								</div>
								<div class="col-sm-3">
									<div class="form-group">
										<label class="control-label">Guardian</label>
										<select class="form-control" data-plugin-selectTwo data-width="100%"  name="id_guardian">
											<option value="">Select</option>';
										foreach($guardian as $value){
										echo '<option value="'.$value['id'].'">'.$value['name'].'</option>';
										}
										echo '
										</select>
									</div>
								</div>
								<div class="col-sm-3">
									<div class="form-group">
										<label class="control-label">Phone <span class="required">*</span></label>
										<input type="text" class="form-control" name="std_phone" id="std_phone" value="'.$rowsvalues['cell_no'].'" required title="Must Be Required">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-3">
									<div class="form-group">
										<label class="control-label">Whatsapp </label>
										<input type="text" class="form-control" id="std_whatsapp" name="std_whatsapp">
									</div>
								</div>
								<div class="col-sm-3">
									<div class="form-group">
										<label class="control-label">Date of Birth </label>
										<input type="text" class="form-control" name="std_dob" id="std_dob" data-plugin-datepicker>
									</div>
								</div>
								<div class="col-sm-3">
									<div class="form-group">
										<label class="control-label">Blood Group </label>
										<select class="form-control" data-plugin-selectTwo data-width="100%"  name="std_bloodgroup">
											<option value="">Select</option>';
											foreach($bloodgroup as $listblood){
												echo '<option value="'.$listblood.'">'.$listblood.'</option>';
											}
											echo '
										</select>
									</div>
								</div>
								<div class="col-sm-3">
									<div class="form-group">
										<label class="control-label">Religion </label>
										<select class="form-control" data-plugin-selectTwo data-width="100%"  name="std_religion">
											<option value="">Select</option>';
											foreach($religion as $rel)
											{
												echo' <option value="'.$rel.'">'.$rel.'</option>';
											}
											echo'
										</select>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3">
									<label class="control-label">Is Hostelize</label>
									<select class="form-control" title="Must Be Required" data-plugin-selectTwo data-width="100%" id="is_hostel" name="is_hostel" title="Must Be Required">
										<option value="">Select</option>';
										foreach ($statusyesno as $hostel_status) {
											echo '<option value="'.$hostel_status['id'].'">'.$hostel_status['name'].'</option>';
										}
										echo'
									</select>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label class="control-label">Group </label>
										<select class="form-control" data-plugin-selectTwo data-width="100%"  name="id_group">
											<option value="">Select</option>';
												$sqllmscls	= $dblms->querylms("SELECT group_id, group_name 
																	FROM ".GROUPS."
																	WHERE group_status = '1'
																	ORDER BY group_name ASC");
												while($valuecls = mysqli_fetch_array($sqllmscls)) {
											echo '<option value="'.$valuecls['group_id'].'">'.$valuecls['group_name'].'</option>';
											}
										echo '
										</select>
									</div>
								</div>
								<div class="col-md-3">
									<label class="control-label">Class <span class="required">*</span></label>
									<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" id="id_class" name="id_class" onchange="get_section(this.value)">
										<option value="">Select</option>';
											$sqllmscls	= $dblms->querylms("SELECT class_id, class_status, class_name 
																			FROM ".CLASSES."
																			WHERE class_status = '1' 
																			AND class_id IN (".$_SESSION['userlogininfo']['LOGINCAMPUSCLASSES'].")
																			ORDER BY class_id ASC");
											while($valuecls = mysqli_fetch_array($sqllmscls)) {
												if($valuecls['class_id'] ==  $rowsvalues['id_class']){
													echo '<option value="'.$valuecls['class_id'].'" selected>'.$valuecls['class_name'].'</option>';
												}
												else{
													echo '<option value="'.$valuecls['class_id'].'">'.$valuecls['class_name'].'</option>';
												}
											}
										echo '
									</select>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label class="control-label">Section <span class="required">*</span></label>
										<select class="form-control" data-plugin-selectTwo data-width="100%" id="id_section" name="id_section" required>
											<option value="">Select</option>';
											$sqlSection	= $dblms->querylms("SELECT section_id, section_name 
																			FROM ".CLASS_SECTIONS."
																			WHERE section_status = '1'
																			AND is_deleted = '0'
																			AND id_class = '".$rowsvalues['id_class']."'
																			AND id_campus = '".$rowsvalues['id_campus']."'
																			ORDER BY section_id ASC");
											if(mysqli_num_rows($sqlSection) > 0){
												while($valSection = mysqli_fetch_array($sqlSection)) {
													echo '<option value="'.$valSection['section_id'].'">'.$valSection['section_name'].'</option>';
												}
											} else {
												echo '<option value="">Please Add Section First</option>';
											}
											echo'
										</select>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-4">
									<div class="form-group">
										<label class="control-label">Roll No.</label>
										<input type="text" class="form-control" name="std_rollno" id="std_rollno">
									</div>
								</div>
								<div class="col-sm-4">
									<div class="form-group">
										<label class="control-label">Admission Date <span class="required">*</span></label>
										<input type="text" class="form-control" name="std_admissiondate" id="std_admissiondate" data-plugin-datepicker required title="Must Be Required">
									</div>
								</div>
								<div class="col-sm-4">
									<div class="form-group">
										<label class="control-label">City</label>
										<input type="text" class="form-control" name="std_city" id="std_city">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-6">
									<div class="form-group">
										<label class="control-label">ID Card</label>
										<input type="file" class="form-control" name="std_idcard" id="std_idcard" accept="image/*, application/msword, application/pdf">
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group">
										<label class="control-label">Father ID Card</label>
										<input type="file" class="form-control" name="std_fatheridcard" id="std_fatheridcard" accept="image/*, application/msword, application/pdf">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-4">
									<div class="form-group">
										<label class="control-label">Birth Certificate</label>
										<input type="file" class="form-control" name="std_birthcertificate" id="std_birthcertificate" accept="image/*, application/msword, application/pdf">
									</div>
								</div>
								<div class="col-sm-4">
									<div class="form-group">
										<label class="control-label">School Leaving Certificate</label>
										<input type="file" class="form-control" name="std_leavingcertificate" id="std_leavingcertificate" accept="image/*, application/msword, application/pdf">
									</div>
								</div>
								<div class="col-sm-4">
									<div class="form-group">
										<label class="control-label">Other Documents</label>
										<input type="file" class="form-control" name="std_otherdocuments" id="std_otherdocuments" accept="image/*, application/msword, application/pdf">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12">
									<div class="form-group">
										<label class="control-label">Address </label>
										<textarea type="text" class="form-control" name="std_address" id="std_address">'.$rowsvalues['address'].'</textarea>
									</div>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">Status <span class="required">*</span></label>
							<div class="col-md-10">';
							foreach($stdstatus as $status){
								echo'
								<div class="radio-custom radio-inline">
									<input type="radio" id="std_status" name="std_status" value="'.$status['id'].'"'; if($status['id'] == 1){echo'checked';} echo'>
									<label for="radioExample1">'.$status['name'].'</label>
								</div>';
							}
							echo'
							</div>
						</div>
					</div>
					<footer class="panel-footer">
						<div class="row">
							<div class="col-md-12 text-right">
								<button type="submit" id="submit_student" name="submit_student" class="mr-xs btn btn-primary">Add Student</button>
							</div>
						</div>
					</footer>
				</form>
			</section>
		</div>
	</div>';
}
else{
	header("Location: students.php");
}
?>