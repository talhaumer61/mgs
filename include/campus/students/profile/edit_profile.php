<?php
$sqllms	= $dblms->querylms("SELECT s.std_id, s.std_status, s.std_name, s.std_fathername, s.std_gender, s.id_guardian,
								s.std_nic, s.std_familyno, s.std_phone, s.std_whatsapp, s.id_class, s.id_section, s.id_group, s.id_session,  s.std_rollno,
								s.std_regno, s.std_photo, s.std_gender, s.std_dob, s.std_bloodgroup, s.id_country,
								s.std_city, s.std_prev_school, s.std_religion, s.std_address, s.admission_formno, s.std_admissiondate,
								c.class_id, c.class_status, c.class_name,
								se.section_id, se.section_status, se.section_name, 
								gr.group_id, gr.group_status, gr.group_name 
								FROM ".STUDENTS." s
								INNER JOIN ".CLASSES." c ON c.class_id = s.id_class
								LEFT JOIN ".CLASS_SECTIONS." se ON se.section_id = s.id_section
								LEFT JOIN ".GROUPS." gr ON gr.group_id = s.id_group
								WHERE s.id_campus = '".$id_campus."' 
								AND s.std_id = '".cleanvars($_GET['id'])."' LIMIT 1");
$rowsvalues = mysqli_fetch_array($sqllms);

// DATE VARIABLES
$dob = date('m/d/Y' , strtotime(cleanvars($rowsvalues['std_dob'])));
$admissiondate = date('m/d/Y' , strtotime(cleanvars($rowsvalues['std_admissiondate'])));
$admission_year = date('Y' , strtotime(cleanvars($rowsvalues['std_admissiondate'])));
echo '
<div id="edit" class="tab-pane active">
	<form action="#" class="form-horizontal validate" enctype="multipart/form-data" method="post" accept-charset="utf-8">
		<input type="hidden" name="std_id" id="std_id" value="'.cleanvars($_GET['id']).'">
		<input type="hidden" name="id_campus" id="id_campus" value="'.cleanvars($id_campus).'">';
		if($_GET['class_id']){
			echo'<input type="hidden" name="filtered_class" id="filtered_class" value="'.cleanvars($_GET['class_id']).'">';
		}
		echo'
		<fieldset class="mt-lg">
			<div class="form-group">
				<label class="col-sm-3 control-label">Photo</label>
				<div class="col-md-8">

					<!--
					<div class="image_area">
						<label for="upload_image">
							<img src="uploads/'.((isset($rowsvalues['std_photo']) && !empty($rowsvalues['std_photo'])) ? 'images/students/'.$rowsvalues['std_photo'].'' : 'default-student.jpg').'" id="uploaded_image" class="img-responsive thumbnail mb-none" width="200" />
							<div class="overlay">
								<div class="text-overlay"><h6>Click to Change Profile Image</h6></div>
							</div>
							<input type="file" name="image" class="image" id="upload_image" accept="image/*" oninput="getCropped()" style="display:none;"/>
							<input type="hidden" name="std_photo" id="std_photo">
						</label>
					</div>
					
					-->
					
					<div class="fileinput fileinput-new" data-provides="fileinput">
						<div class="fileinput-new thumbnail" style="width: 100px; height: 100px;" data-trigger="fileinput">';
							if($rowsvalues['std_photo']) { 
							echo '
								<img src="uploads/images/students/'.$rowsvalues['std_photo'].'" class="rounded img-responsive">' ;
							} else {
								echo '<img src="uploads/default-student.jpg" class="rounded img-responsive">';
							}
							echo'
						</div>
						<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px"></div>
						<div>
							<span class="mr-xs btn btn-xs btn-default btn-file">
								<span class="fileinput-new">Select image</span>
								<span class="fileinput-exists">Change</span>
								<input type="file" name="std_photo" accept="image/*">
							</span>
							<a href="#" class="btn btn-xs btn-warning fileinput-exists" data-dismiss="fileinput">Remove</a>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Admission Form </label>
				<div class="col-md-8">
					<input type="text" class="form-control" name="admission_formno" value="'.$rowsvalues['admission_formno'].'" readonly />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Student Name <span class="required">*</span></label>
				<div class="col-md-8">
					<input type="text" class="form-control" required name="std_name" id="std_name" value="'.$rowsvalues['std_name'].'"/>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Father Name <span class="required">*</span></label>
				<div class="col-md-8">
					<input type="text" class="form-control" required name="std_fathername" id="std_fathername" value="'.$rowsvalues['std_fathername'].'"/>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Roll No</label>
				<div class="col-md-8">
					<input type="text" class="form-control" name="std_rollno" id="std_rollno" value="'.$rowsvalues['std_rollno'].'"/>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Is Hostel</label>
				<div class="col-md-8">
					<select class="form-control" title="Must Be Required" data-plugin-selectTwo data-width="100%" id="is_hostel" name="is_hostel" title="Must Be Required">
						<option value="">Select</option>';
						foreach ($statusyesno as $hostel_status) {
							echo '<option value="'.$hostel_status['id'].'">'.$hostel_status['name'].'</option>';
						}
						echo'
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label">Group</label>
				<div class="col-md-8">
					<select class="form-control" data-plugin-selectTwo data-width="100%"  name="id_group">
						<option value="">Select</option>';
							$sqllmscls	= $dblms->querylms("SELECT group_id, group_name 
														FROM ".GROUPS."
														WHERE group_status = '1' 
														ORDER BY group_name ASC");
							while($valuecls = mysqli_fetch_array($sqllmscls)) {
								if($valuecls['group_id'] == $rowsvalues['id_group']) { 
									echo '<option value="'.$valuecls['group_id'].'" selected>'.$valuecls['group_name'].'</option>';
								} else { 
									echo '<option value="'.$valuecls['group_id'].'">'.$valuecls['group_name'].'</option>';
								}
							}
					echo '
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label">Class <span class="required">*</span></label>
				<div class="col-md-8">
					<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" id="id_class" name="id_class" onchange="get_section(this.value)">
						<option value="">Select</option>';
							$sqllmscls	= $dblms->querylms("SELECT class_id, class_name 
														FROM ".CLASSES."
														WHERE class_status = '1'
														AND class_id IN (".$_SESSION['userlogininfo']['LOGINCAMPUSCLASSES'].")
														ORDER BY class_id ASC");
							while($valuecls = mysqli_fetch_array($sqllmscls)) {
								if($valuecls['class_id'] == $rowsvalues['id_class']) { 
									echo '<option value="'.$valuecls['class_id'].'" selected>'.$valuecls['class_name'].'</option>';
								} else { 
									echo '<option value="'.$valuecls['class_id'].'">'.$valuecls['class_name'].'</option>';
								}
							}
					echo '
					</select>
				</div>
			</div>
			<div class="form-group mb-lg">
				<label class="col-md-3 control-label">Section <span class="required">*</span></label>
				<div class="col-md-8">
					<select class="form-control" data-plugin-selectTwo data-width="100%" id="id_section" name="id_section">
						<option value="">Select</option>';
						$sqllmscls	= $dblms->querylms("SELECT section_id, section_name 
													FROM ".CLASS_SECTIONS."
													WHERE id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'
													AND section_status = '1' AND id_class = '".$rowsvalues['id_class']."' AND is_deleted = '0'
													ORDER BY section_name ASC");
						while($valuecls = mysqli_fetch_array($sqllmscls)) {
							if($valuecls['section_id'] == $rowsvalues['id_section']) { 
								echo '<option value="'.$valuecls['section_id'].'" selected>'.$valuecls['section_name'].'</option>';
							} else { 
								echo '<option value="'.$valuecls['section_id'].'">'.$valuecls['section_name'].'</option>';
							}
						}
						echo'
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Phone <span class="required">*</span></label>
				<div class="col-md-8">
					<input type="text" class="form-control" name="std_phone" value="'.$rowsvalues['std_phone'].'" data-plugin-options=\'{ "startView": 2 }\' />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Whatsapp</label>
				<div class="col-md-8">
					<input type="text" class="form-control" name="std_whatsapp" value="'.$rowsvalues['std_whatsapp'].'" data-plugin-options=\'{ "startView": 2 }\' />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Gender <span class="required">*</span></label>
				<div class="col-md-8">
					<select name="std_gender" data-plugin-selectTwo  data-width="100%" class="form-control populate" required title="Must Be Required">
						<option value="">Select</option>
							<option value="Male"'; if($rowsvalues['std_gender'] == 'Male'){ echo 'selected';} echo'>Male</option>
							<option value="Female"'; if($rowsvalues['std_gender'] == 'Female'){ echo 'selected';} echo'>Female</option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Blood Group</label>
				<div class="col-md-8">
					<select name="std_bloodgroup" class="form-control populate" data-plugin-selectTwo data-width="100%"  >
						<option value="">Select</option>';
						foreach($bloodgroup as $listblood){
							echo '<option value="'.$listblood.'"'; if($rowsvalues['std_bloodgroup'] == $listblood){echo ' selected';}echo'>'.$listblood.'</option>';
						}
						echo '
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Date of Birth</label> 
				<div class="col-md-8">
					<input type="text" class="form-control" name="std_dob" value="'.$dob.'" data-plugin-datepicker />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Family No</label>
				<div class="col-md-8">
					<input type="text" class="form-control" name="std_familyno" id="std_familyno" value="'.$rowsvalues['std_familyno'].'"/>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">NIC / B-Form <span class="required">*</span></label>
				<div class="col-md-8">
					<input type="text" class="form-control" required name="std_nic" value="'.$rowsvalues['std_nic'].'"/>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Religion</label>
				<div class="col-md-8">
					<select name="std_religion" value="'.$rowsvalues['std_religion'].'" class="form-control populate" data-plugin-selectTwo data-width="100%"  >';
						foreach($religion as $rel)
						{
							echo '<option value="'.$rel.'"'; if($rowsvalues['std_religion'] == $rel){echo ' selected';} echo '>'.$rel.'</option>';
						}
						echo'
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Admission Date <span class="required">*</span></label>
				<div class="col-md-8">
					<input type="text" class="form-control" required name="std_admissiondate" value="'.$admissiondate.'" data-plugin-datepicker />
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label">Guardian</label>
				<div class="col-md-8">
					<select class="form-control" data-plugin-selectTwo data-width="100%"  name="id_guardian">
						<option value="">Select</option>';
						foreach($guardian as $value){
						echo '<option value="'.$value['id'].'"'; if($rowsvalues['id_guardian'] == $value['id']){echo ' selected';} echo'>'.$value['name'].'</option>';
						}
						echo '
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">ID Card</label>
				<div class="col-md-8">
					<input type="file" class="form-control" name="std_idcard" id="std_idcard" accept="image/*, application/msword, application/pdf">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Father ID Card</label>
				<div class="col-md-8">
					<input type="file" class="form-control" name="std_fatheridcard" id="std_fatheridcard" accept="image/*, application/msword, application/pdf">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Birth Certificate</label>
				<div class="col-md-8">
					<input type="file" class="form-control" name="std_birthcertificate" id="std_birthcertificate" accept="image/*, application/msword, application/pdf">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Leaving Certificate</label>
				<div class="col-md-8">
					<input type="file" class="form-control" name="std_leavingcertificate" id="std_leavingcertificate" accept="image/*, application/msword, application/pdf">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Other Documents</label>
				<div class="col-md-8">
					<input type="file" class="form-control" name="std_otherdocuments" id="std_otherdocuments" accept="image/*, application/msword, application/pdf">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">City</label>
				<div class="col-md-8">
					<input type="text" class="form-control" name="std_city" value="'.$rowsvalues['std_city'].'"/>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Previous School</label>
				<div class="col-md-8">
					<input type="text" class="form-control" name="std_prev_school" value="'.$rowsvalues['std_prev_school'].'"/>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Address</label>
				<div class="col-md-8">
					<textarea type="text" class="form-control" name="std_address">'.$rowsvalues['std_address'].'</textarea>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Status <span class="required">*</span></label>
				<div class="col-md-9">';
				foreach($stdstatus as $status){
					echo'
					<div class="radio-custom radio-inline">
						<input type="radio" id="std_status" name="std_status" value="'.$status['id'].'"'; if($rowsvalues['std_status'] == $status['id']){echo'checked';} echo'>
						<label for="radioExample1">'.$status['name'].'</label>
					</div>';
				}
				echo'	
				</div>
			</div>
		</fieldset>';
		if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || ($_SESSION['userlogininfo']['LOGINTYPE']  == 2) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '73', 'updated' => '1'))){
			echo '
			<div class="panel-footer">
				<div class="row">
					<div class="col-sm-offset-3 col-sm-5">
						<button type="submit"  name="changes_student" id="changes_student" class="btn btn-primary">Update Profile</button>
					</div>
				</div>
			</div>';
		}
		echo '
	</form>
</div>';