<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('16', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '16', 'add' => '1'))) {
	$sqllms	= $dblms->querylms("SELECT e.emply_regno, e.emply_ordering, c.campus_regno 
								FROM ".CAMPUS." c 
								LEFT JOIN ".EMPLOYEES." e ON c.campus_id = e.id_campus 
								WHERE c.campus_id = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."' 
								ORDER BY e.emply_id DESC LIMIT 1");
	$rowsemployee = mysqli_fetch_array($sqllms);

	// GENERATE REGISTERATION NUMBER 
	$sqllmscampus = $dblms->querylms("SELECT  c.campus_code, cg.group_code_numeric, b.brand_code_numeric, d.dist_code
									FROM ".CAMPUS." c 
									INNER JOIN ".CAMPUS_GROUPS." cg ON cg.group_id = c.id_group
									INNER JOIN ".BRANDS." b ON b.brand_id = c.id_brand
									INNER JOIN ".DISTRICTS." d ON d.dist_id  = c.id_dist
									WHERE c.is_deleted = '0'
									AND c.campus_id = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
									LIMIT 1
								");
	$value_campus = mysqli_fetch_array($sqllmscampus);

	$regnoStr = EMP_PREFIX.$value_campus['group_code_numeric'].$value_campus['brand_code_numeric'].'-'.$value_campus['dist_code'].$value_campus['campus_code'].'-'.substr(date("Y"), -2);
	// REMOVE NULL SPACES
	$regnoStr = str_replace(' ', '', $regnoStr);
	$sqllmsstudentregno = $dblms->querylms("SELECT emply_regno FROM ".EMPLOYEES." 
											WHERE emply_regno LIKE '".$regnoStr."%'
											AND id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
											ORDER by emply_regno DESC LIMIT 1 ");
	$value_regno = mysqli_fetch_array($sqllmsstudentregno);
	if(mysqli_num_rows($sqllmsstudentregno) < 1) {
		$regno	= $regnoStr.'-0001';
	}else{
		$regno = $value_regno['emply_regno'];
		$regno++;
	}

	$order = $rowsemployee['emply_ordering'] + 1;
	// $regno = "MES-".$rowsemployee['campus_regno']."-Emp-".str_pad($order,4,"0",STR_PAD_LEFT);

	echo'
	<div class="row">
		<div class="col-md-12">
			<section class="panel panel-featured panel-featured-primary">
				<form action="#" class="mb-lg validate" enctype="multipart/form-data" method="post" accept-charset="utf-8">
					<div class="panel-heading">
						<h4 class="panel-title"><i class="fa fa-plus-square"></i> Add Employee</h4>
					</div>
					<div class="panel-body">
						<label class="control-label">Photo</label>
						<div class="row">
							<div class="col-md-6">
								<div class="fileinput fileinput-new" data-provides="fileinput">
									<div class="fileinput-new thumbnail" style="width: 130px; height: 130px;" data-trigger="fileinput">
										<img src="uploads/defualt.png" alt="...">
									</div>
									<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 130px; max-height: 130px"></div>
									<div>
										<span class="btn btn-xs btn-default btn-file">
											<span class="fileinput-new">Select image</span>
											<span class="fileinput-exists">Change</span>
											<input type="file" name="emply_photo" accept="image/*">
										</span>
										<a href="#" class="btn btn-xs btn-warning fileinput-exists" data-dismiss="fileinput">Remove</a>
									</div>
								</div>
							</div>
						</div>
						<div class="row mt-sm">
							<div class="col-sm-6">
								<div class="form-group">
									<label class="control-label">Reg. No <span class="required">*</span></label>
									<input type="text" class="form-control" name="emply_regno" readonly value="'.$regno.'" required title="Must Be Required" value="" >
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label class="control-label">Order # <span class="required">*</span></label>
									<input type="text" class="form-control" id="emply_ordering" name="emply_ordering" value="'.$order.'" readonly  required title="Must Be Required" value="" >
								</div>
							</div>
						</div>
						<div class="row mt-sm">
							<div class="col-sm-4">
								<div class="form-group">
									<label class="control-label">Full Name <span class="required">*</span></label>
									<input type="text" class="form-control" name="emply_name" id="emply_name" required title="Must Be Required"/>
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<label class="control-label">Email <span class="required">*</span></label>
									<input type="text" class="form-control" required title="Must Be Required" name="emply_email" id="emply_email"/>
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<label class="control-label">NIC <span class="required">*</span></label>
									<input type="text" class="form-control" title="Must Be Required" name="emply_nic" id="emply_nic" required>
								</div>
							</div>
						</div>
						<div class="row mt-sm">
							<div class="col-sm-4">
								<div class="form-group">
									<label class="control-label">Phone <span class="required">*</span></label>
									<input type="text" class="form-control" required title="Must Be Required" name="emply_phone" id="emply_phone"/>
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<label class="control-label">WhatsApp # <span class="required">*</span></label>
									<input type="text" class="form-control" required title="Must Be Required" name="emply_whatsapp" id="emply_whatsapp"/>
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<label class="control-label">Gender <span class="required">*</span></label>
									<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="emply_gender">
										<option value="">Select</option>';
										foreach($gender as $gen){
											echo '<option value="'.$gen.'">'.$gen.'</option>';
										}
										echo'
									</select>
								</div>
							</div>
						</div>
						<div class="row mt-sm">
							<div class="col-sm-4">
								<div class="form-group">
									<label class="control-label">Birthday <span class="required">*</span></label>
									<input type="text" class="form-control" data-plugin-datepicker required title="Must Be Required" name="emply_dob" id="emply_dob" autocomplete="off"/>
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<label class="control-label">Employee Type <span class="required">*</span></label>
									<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="id_type">
										<option value="">Select</option>';
										foreach($emply_type as $emplyType){
											echo'<option value="'.$emplyType['id'].'">'.$emplyType['name'].'</option>';
										}
										echo'
									</select>
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<label class="control-label">Department <span class="required">*</span></label>
									<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="id_dept">
										<option value="">Select</option>';
										$sqllmscls	= $dblms->querylms("SELECT dept_id, dept_name 
																	FROM ".DEPARTMENTS."
																	WHERE id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."' 
																	ORDER BY dept_name ASC");
										while($valuecls = mysqli_fetch_array($sqllmscls)) {
											echo'<option value="'.$valuecls['dept_id'].'">'.$valuecls['dept_name'].'</option>';
										}
										echo'
									</select>
								</div>
							</div>
						</div>
						<div class="row mt-sm">
							<div class="col-sm-4">
								<div class="form-group">
									<label class="control-label">Designation <span class="required">*</span></label>
									<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="id_designation">
										<option value="">Select</option>';
										$sqllmscls	= $dblms->querylms("SELECT designation_id, designation_name 
																	FROM ".DESIGNATIONS."
																	WHERE id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."' 
																	ORDER BY designation_name ASC");
										while($valuecls = mysqli_fetch_array($sqllmscls)) {
											echo'<option value="'.$valuecls['designation_id'].'">'.$valuecls['designation_name'].'</option>';
										}
										echo'
									</select>
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<label class="control-label">Qualification <span class="required">*</span></label>
									<input type="text" class="form-control" name="emply_education" id="emply_education" required title="Must Be Required"/>
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<label class="control-label">Experience <span class="required">*</span></label>
									<input type="text" class="form-control" required title="Must Be Required" name="emply_experence" id="emply_experence"/>
								</div>
							</div>
						</div>
						<div class="row mt-sm">
							<div class="col-sm-4">
								<div class="form-group">
									<label class="control-label">Join Date <span class="required">*</span></label>
									<input type="text" class="form-control" required title="Must Be Required" data-plugin-datepicker name="emply_joindate" id="emply_joindate"/>
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<label class="control-label">Religion <span class="required">*</span></label>
									<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="emply_religion">
										<option value="">Select</option>';
										foreach($religion as $rel){
											echo'<option value="'.$rel.'">'.$rel.'</option>';
										}
										echo'
									</select>
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<label class="control-label">Blood Group <span class="required">*</span></label>
									<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="emply_bloodgroup">
										<option value="">Select</option>';
										foreach($bloodgroup as $listblood){
											echo'<option value="'.$listblood.'">'.$listblood.'</option>';
										}
										echo'
									</select>
								</div>
							</div>
						</div>
						<div class="row mt-sm">
							<div class="col-md-12">
								<div class="form-group">
									<label class="control-label">Classes </label>
									<select class="form-control" data-plugin-selectTwo data-width="100%" multiple name="id_class[]">
										<option value="">Select</option>';
										$sqllmscls	= $dblms->querylms("SELECT class_id, class_status, class_name 
																		FROM ".CLASSES."
																		WHERE class_status = '1' 
																		ORDER BY class_id ASC");
										while($valuecls = mysqli_fetch_array($sqllmscls)) {
											echo'<option value="'.$valuecls['class_id'].'">'.$valuecls['class_name'].'</option>';
										}
									echo '
									</select>
								</div>
							</div>
							<!--
							<div id="getclasssection">
								<div class="col-sm-4">
									<div class="form-group">
										<label class="control-label">Section </label>
										<select class="form-control" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="id_section">
											<option value="">Select</option>';
											$sqllmscls	= $dblms->querylms("SELECT section_id, section_name 
																FROM ".CLASS_SECTIONS."
																WHERE id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'
																AND section_status = '1' AND id_class = '".$class."'
																ORDER BY section_name ASC");
											while($valuecls = mysqli_fetch_array($sqllmscls)) {
												if($valuecls['section_id'] == $section){
													echo '<option value="'.$valuecls['section_id'].'" selected>'.$valuecls['section_name'].'</option>';
												} else{
													echo '<option value="'.$valuecls['section_id'].'">'.$valuecls['section_name'].'</option>';
												}

											}
											echo '
										</select>
									</div>
								</div>
							</div>
							-->
						</div>
						<div class="row mt-sm">
							<div class="col-sm-12">
								<div class="form-group">
									<label class="control-label">Address</label>
									<textarea type="text" class="form-control" required title="Must Be Required" name="emply_address" id="emply_address"></textarea>
								</div>
							</div>
						</div>
						<div class="form-group mt-sm mb-sm">
							<label class="col-sm-3 control-label">Status <span class="required">*</span></label>
							<div class="col-md-9">
								<div class="radio-custom radio-inline">
									<input type="radio" id="emply_status" name="emply_status" value="1" checked>
									<label for="radioExample1">Active</label>
								</div>
								<div class="radio-custom radio-inline">
									<input type="radio" id="emply_status" name="emply_status" value="2">
									<label for="radioExample2">Inactive</label>
								</div>
							</div>
						</div>
					</div>
					<footer class="panel-footer">
						<div class="row">
							<div class="col-md-12 text-right">
								<button type="submit" class="mr-xs btn btn-primary" id="submit_emply" name="submit_emply">Add Employee</button>
								<button type="reset" class="btn btn-default">Reset</button>
							</div>
						</div>
					</footer>
				</form>
			</section>
		</div>
	</div>';
}else{
	header("Location: employee.php");
}
?>
<script type="text/javascript">
	function get_classsection(id_class) {
		$("#loading").html('<img src="images/ajax-loader-horizintal.gif"> loading...');
		$.ajax({
			type: "POST",
			url: "include/ajax/get_classsection.php",
			data: "id_class=" + id_class,
			success: function(msg) {
			$("#getclasssection").html(msg);
			$("#loading").html('');
			}
		});
	}
</script>