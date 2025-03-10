<?php
$sqllms	= $dblms->querylms("SELECT e.emply_id, e.emply_status, e.emply_regno, e.emply_name, e.emply_nic, e.id_dept, 
								e.id_designation, e.id_type, e.id_class, e.id_section, e.emply_gender, 
								e.emply_dob, e.emply_joindate, e.emply_education, e.emply_experence, 
								e.emply_religion, e.emply_bloodgroup, e.emply_address, e.emply_phone,
								e.emply_email, e.emply_photo,
								d.dept_name, dp.designation_name 
								FROM ".EMPLOYEES." e 
								LEFT JOIN ".DESIGNATIONS." dp ON dp.designation_id = e.id_designation
								LEFT JOIN ".DEPARTMENTS." d ON d.dept_id = e.id_dept
								WHERE e.id_campus = '".cleanvars($id_campus)."' 
								AND e.emply_id = '".cleanvars($_GET['id'])."' LIMIT 1");
$rowsvalues = mysqli_fetch_array($sqllms);
$class = explode(',', $rowsvalues['id_class']);
echo'
<div id="edit" class="tab-pane active">
	<form action="#" class="form-horizontal validate" enctype="multipart/form-data" method="post" accept-charset="utf-8">
 		<input type="hidden" name="emply_id" id="emply_id" value="'.cleanvars($_GET['id']).'">
		<fieldset class="mt-lg">
			<div class="form-group">
				<label class="col-sm-3 control-label">Photo</label>
				<div class="col-md-8">
					<div class="fileinput fileinput-new" data-provides="fileinput">
						<div class="fileinput-new thumbnail" style="width: 100px; height: 100px;" data-trigger="fileinput">';
							if($rowsvalues['emply_photo']){
								echo'<img src="uploads/images/employees/'.$rowsvalues['emply_photo'].'" class="rounded img-responsive">' ;
							}else{
								echo'<img src="uploads/defualt.png" class="rounded img-responsive">';
							}
							echo'
						</div>
						<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px"></div>
						<div>
							<span class="mr-xs btn btn-xs btn-default btn-file">
								<span class="fileinput-new">Select image</span>
								<span class="fileinput-exists">Change</span>
								<input type="file" name="emply_photo" accept="image/*">
							</span>
							<a href="#" class="btn btn-xs btn-warning fileinput-exists" data-dismiss="fileinput">Remove</a>
						</div>
					</div>
				</div>
			</div>';
			if(!empty($_SESSION['userlogininfo']['SUBCAMPUSES'])){
				echo'				
				<div class="form-group">
					<label class="col-sm-3 control-label">Sub Campus </label>
					<div class="col-md-8">
						<select class="form-control" title="Must Be Required" data-plugin-selectTwo data-width="100%" id="id_campus" name="id_campus" onchange="get_dept_and_designation_edit(this.value)">
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
					</div>
				</div>';
			}
			echo'
			<div class="form-group">
				<label class="col-sm-3 control-label">Name <span class="required">*</span></label>
				<div class="col-md-8">
					<input type="text" class="form-control" required name="emply_name" id="emply_name" value="'.$rowsvalues['emply_name'].'"/>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">NIC <span class="required">*</span></label>
				<div class="col-md-8">
					<input type="text" class="form-control" required name="emply_nic" id="emply_nic" value="'.$rowsvalues['emply_nic'].'"/>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Registration Number <span class="required">*</span></label>
				<div class="col-md-8">
					<input type="text" class="form-control" required name="emply_regno" id="emply_regno" value="'.$rowsvalues['emply_regno'].'"/>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Type <span class="required">*</span></label>
				<div class="col-md-8">					
					<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" name="id_type">
						<option value="">Select</option>';
						foreach($emply_type as $emplyType){
							echo'<option value="'.$emplyType['id'].'" '.($emplyType['id'] == $rowsvalues['id_type'] ? 'selected' : '').'>'.$emplyType['name'].'</option>';
						}
						echo'
					</select>
				</div>
			</div>
			<div id="get_dept_and_designation">
				<div class="form-group">
					<label class="col-md-3 control-label">Designation <span class="required">*</span></label>
					<div class="col-md-8">
						<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" id="id_designation" name="id_designation">
							<option value="">Select</option>';
							$sqlDesignation	= $dblms->querylms("SELECT designation_id, designation_name 
																FROM ".DESIGNATIONS."
																WHERE id_campus         = '".cleanvars($id_campus)."' 
																AND is_deleted          = '0'
																AND designation_status  = '1'
																ORDER BY designation_name ASC");
							while($valDesignation = mysqli_fetch_array($sqlDesignation)) {
								echo'<option value="'.$valDesignation['designation_id'].'" '.($valDesignation['designation_id'] == $rowsvalues['id_designation'] ? 'selected' : '').'>'.$valDesignation['designation_name'].'</option>';
							}
							echo'
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Department <span class="required">*</span></label>
					<div class="col-md-8">
						<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" name="id_dept">
							<option value="">Select</option>';
							$sqlDept = $dblms->querylms("SELECT dept_id, dept_name 
															FROM ".DEPARTMENTS."
															WHERE id_campus = '".cleanvars($id_campus)."' 
															AND dept_status = '1'
															AND is_deleted  = '0'
															ORDER BY dept_name ASC");
							while($valDept = mysqli_fetch_array($sqlDept)) {
								echo '<option value="'.$valDept['dept_id'].'" '.($valDept['dept_id'] == $rowsvalues['id_dept'] ? 'selected' : '').'>'.$valDept['dept_name'].'</option>';
							}
							echo'
						</select>
					</div>
				</div>
			</div>';
			if($rowsvalues['id_type'] == 1){
				echo'
				<div class="form-group">
					<label class="col-md-3 control-label">Classes </label>
					<div class="col-md-8">
						<select class="form-control" data-plugin-selectTwo data-width="100%" name="id_class[]" multiple>
							<option value="">Select</option>';
							$sqllmscls	= $dblms->querylms("SELECT class_id, class_name 
																FROM ".CLASSES."
																WHERE class_status = '1'
																ORDER BY class_id ASC");
							while($valuecls = mysqli_fetch_array($sqllmscls)){
								echo'<option value="'.$valuecls['class_id'].'" '.(in_array($valuecls['class_id'], $class) ? 'selected' : '').'>'.$valuecls['class_name'].'</option>';
							}
							echo'
						</select>
					</div>
				</div>
				<!--
				<div id="geteditclasssection">
					<div class="form-group mb-lg">
						<label class="col-md-3 control-label">Section</label>
						<div class="col-md-8">
							<select class="form-control" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="id_section">
								<option value="">Select</option>';
								$sqllmscls	= $dblms->querylms("SELECT section_id, section_name 
															FROM ".CLASS_SECTIONS."
															WHERE id_campus		= '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'
															AND section_status	= '1' AND id_class = '".$rowsvalues['id_class']."' 
															ORDER BY section_name ASC");
								while($valuecls = mysqli_fetch_array($sqllmscls)){
									if($valuecls['section_id'] == $rowsvalues['id_section']){ 
										echo'<option value="'.$valuecls['section_id'].'" selected>'.$valuecls['section_name'].'</option>';
									}else{ 
										echo'<option value="'.$valuecls['section_id'].'">'.$valuecls['section_name'].'</option>';
									}
								}
								echo'
							</select>
						</div>
					</div>
				</div>
				-->';
			}
			echo'
			<div class="form-group">
				<label class="col-sm-3 control-label">Education <span class="required">*</span></label>
				<div class="col-md-8">
					<input type="text" class="form-control" name="emply_education" value="'.$rowsvalues['emply_education'].'" data-plugin-options=\'{ "startView": 2 }\' />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Experience <span class="required">*</span></label>
				<div class="col-md-8">
					<input type="text" class="form-control" name="emply_experence" id="emply_experence" value="'.$rowsvalues['emply_experence'].'" data-plugin-options=\'{ "startView": 2 }\' />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Date of Birth <span class="required">*</span></label>
				<div class="col-md-8">
					<input type="text" class="form-control" name="emply_dob" value="'.date('m/d/Y',strtotime($rowsvalues['emply_dob'])).'" data-plugin-datepicker />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Joining Date <span class="required">*</span></label> 
				<div class="col-md-8">
					<input type="text" class="form-control" name="emply_joindate" value="'.date('m/d/Y',strtotime($rowsvalues['emply_joindate'])).'" data-plugin-datepicker />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Gender <span class="required">*</span></label>
				<div class="col-md-8">
					<select data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="emply_gender" class="form-control populate">
						<option value="'.$rowsvalues['emply_gender'].'">'.$rowsvalues['emply_gender'].'</option>	
						<option value="male" >Male</option>
						<option value="female" >Female</option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Religion <span class="required">*</span></label>
				<div class="col-md-8">
					<input type="text" class="form-control" required name="emply_religion" value="'.$rowsvalues['emply_religion'].'"/>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Blood group <span class="required">*</span></label>
				<div class="col-md-8">
					<select name="emply_bloodgroup" value="'.$rowsvalues['emply_bloodgroup'].'" class="form-control populate" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" >
						<option value="'.$rowsvalues['emply_bloodgroup'].'">'.$rowsvalues['emply_bloodgroup'].'</option>
						<option value="A+">A+</option>
						<option value="A-">A-</option>
						<option value="B+">B+</option>
						<option value="B-">B-</option>
						<option value="O+">O+</option>
						<option value="O-">O-</option>
						<option value="AB+">AB+</option>
						<option value="AB-">AB-</option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Phone <span class="required">*</span></label>
				<div class="col-md-8">
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-phone"></i></span>
						<input type="text" class="form-control" name="emply_phone" value="'.$rowsvalues['emply_phone'].'" />
					</div>
				</div>
			</div>
			<div class="form-group mb-md">
				<label class="col-sm-3 control-label">Email <span class="required">*</span></label>
				<div class="col-md-8">
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-envelope"></i></span>
						<input type="email" class="form-control" name="emply_email" required id="email" value="'.$rowsvalues['emply_email'].'" />
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Address <span class="required">*</span></label>
				<div class="col-md-8">
					<textarea name="emply_address" rows="2" class="form-control" value="" aria-required="true">'.$rowsvalues['emply_address'].'</textarea>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Status <span class="required">*</span></label>
				<div class="col-md-9">
					<div class="radio-custom radio-inline">
						<input type="radio" id="emply_status" name="emply_status" value="1" '.($rowsvalues['emply_status']==1 ? 'checked' : '').'>
						<label for="radioExample1">Active</label>
					</div>				
					<div class="radio-custom radio-inline">
						<input type="radio" id="emply_status" name="emply_status" value="2" '.($rowsvalues['emply_status']==2 ? 'checked' : '').'>
						<label for="radioExample2">Inactive</label>
					</div>	
				</div>
			</div>
		</fieldset>';
		if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || ($_SESSION['userlogininfo']['LOGINTYPE']  == 2)|| Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '22', 'updated' => '1'))){ 
			echo'
			<div class="panel-footer">
				<div class="row">
					<div class="col-sm-offset-3 col-sm-5">
						<button type="submit"  name="changes_employee" id="changes_employee" class="btn btn-primary">Update Profile</button>
					</div>
				</div>
			</div>';
		}
		echo'
	</form>
</div>

<script type="text/javascript">
	function get_editclasssection(id_class) {  
		$("#loading").html("<img src="images/ajax-loader-horizintal.gif"> loading...");  
		$.ajax({  
			type: "POST",  
			url: "include/ajax/get_editclasssection.php",  
			data: "id_class="+id_class,  
			success: function(msg){  
				$("#geteditclasssection").html(msg); 
				$("#loading").html(" "); 
			}
		});  
	}
</script>';
?>